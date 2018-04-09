<?php

use src\Api\Exceptions\WrongDataException;
use src\Api\Exceptions\WrongRequestException;
use src\Api\Invoices\CreateInvoice\Cart;
use src\Api\Invoices\CreateInvoice\Carts;
use src\Api\Invoices\CreateInvoice\Request\CreateInvoiceRequest;
use src\Api\Invoices\CreateInvoice\Response\CreateInvoiceResponse;
use src\Api\Invoices\CreateInvoice\TaxMode;
use src\Api\Metadata;
use src\Api\Payments\CreatePayment\Request\CreatePaymentRequest;
use src\Api\Payments\CreatePayment\Request\CustomerPayerRequest;
use src\Api\Payments\CreatePayment\Request\PaymentFlowInstantRequest;
use src\Client\Client;
use src\Client\Sender;
use src\Exceptions\RequestException;

$NETCAT_FOLDER = $_SERVER['DOCUMENT_ROOT'];
include_once($NETCAT_FOLDER . '/vars.inc.php');
include_once($ROOT_FOLDER . 'connect_io.php');
require($ADMIN_FOLDER . 'function.inc.php');
include dirname(__DIR__) . '/rbkmoney/src/autoload.php';

$recurrent = new Recurrent();

foreach ($recurrent->getRecurrentPayments() as $payment) {
    $customer = $recurrent->getCustomer($payment->recurrent_customer_id);
    $user = $recurrent->getUser($customer['user_id']);

    try {
        $invoice = $recurrent->createInvoice($payment, $user);
        $recurrent->createPayment($invoice, $customer['customer_id']);
        echo RECURRENT_SUCCESS . $payment->id . PHP_EOL;;
    } catch (Exception $exception) {
        echo $exception->getMessage();
    }
}

class Recurrent
{
    /**
     * @var array
     */
    private $settings;

    /**
     * @var nc_Core
     */
    private $nc_core;

    /**
     * @var Sender
     */
    private $sender;

    /**
     * @var array
     */
    protected $vat_map = [
        0    => '0%',
        10   => '10%',
        18   => '18%',
    ];

    public function __construct()
    {
        include dirname(__DIR__) . '/rbkmoney/settings.php';
        include dirname(__DIR__) . '/rbkmoney/function.inc.php';
        $this->nc_core = nc_Core::get_object();
        $this->settings = $this->nc_core->get_settings('', 'rbkmoney');

        $this->sender = new Sender(new Client(
            $this->settings['apiKey'],
            $this->settings['shopId'],
            RBK_MONEY_API_URL_SETTING
        ));
    }

    /**
     * @return array
     */
    public function getRecurrentPayments()
    {
        return $this->nc_core->db->get_results("SELECT * 
          FROM `RBKmoney_Recurrent`
          WHERE status = '" . RECURRENT_READY_STATUS . "'");
    }

    /**
     * @param int $recurrentCustomerId
     *
     * @return array | null
     */
    public function getCustomer($recurrentCustomerId)
    {
        return (array) $this->nc_core->db->get_row("SELECT `user_id`, `customer_id`
            FROM `RBKmoney_Recurrent_Customers`
            WHERE `id` = $recurrentCustomerId"
        );
    }

    /**
     * @param int $userId
     *
     * @return array
     */
    public function getUser($userId)
    {
        $user = new nc_User();

        return $user->get_by_id($userId);
    }

    /**
     * @param stdClass $payment
     * @param array    $user
     *
     * @return CreateInvoiceResponse
     *
     * @throws Exception
     * @throws RequestException
     * @throws WrongDataException
     * @throws nc_record_exception
     */
    public function createInvoice(stdClass $payment, array $user)
    {
        $rbkMoney = new rbkmoney();
        $ps = nc_payment_factory::create(get_class($rbkMoney));

        $amount = number_format($payment->amount, 2, '.', '');

        $invoice = new nc_payment_invoice([
            'payment_system_id' => $ps->get_id(),
            'amount' => $amount,
            'description' => RECURRENT_PAYMENT,
            'currency' => $payment->currency,
            'customer_id' => $user['User_ID'],
            'customer_email' => $user['Email'],
            'customer_name' => $user['Login'],
        ]);

        $invoice->save();
        $invoice->set('order_id', $invoice->get_id())->save();

        $invoiceItem = new nc_payment_invoice_item([
            'invoice_id' => $invoice->get_id(),
            'operation' => nc_payment::OPERATION_SELL,
            'name' => $payment->name,
            'source_component_id' => $payment->message_id,
            'source_item_id' => $payment->sub_class_id,
            'item_price' => $amount,
            'qty' => 1,
        ]);
        $invoiceItem->save();

        $ncNetshop = nc_netshop::get_instance();
        $created = new DateTime();
        $paymentMethod = new nc_netshop_payment_method();

        $order = $ncNetshop->create_order([
            'User_ID' => $user['User_ID'],
            'TotalPrice' => $payment->amount,
            'TotalGoods' => 1,
            'PaymentMethod' => $paymentMethod->load_where('name', 'rbkmoney')->get_id(),
            'Created' => $created->format(FULL_DATE_FORMAT),
            'ContactName' => $user['Login'],
            'Email' => $user['Email'],
        ])->save();

        $order->set('Priority', $order->get_id())->save();
        $invoice->set('order_source', $order->get_order_source_class())->save();

        $this->nc_core->db->query("INSERT INTO `Netshop_OrderGoods`
                   SET Order_Component_ID = {$order->get_order_component_id()},
                       Order_ID={$order->get_id()},
                       Item_Type='$payment->message_id',
                       Item_ID='$payment->sub_class_id',
                       Qty='1',
                       OriginalPrice='$payment->amount',
                       ItemPrice='$payment->amount'
                  ");

        $endDate = new DateTime();
        $shopId = $this->settings['shopId'];
        $product = ORDER_PAYMENT . "№{$invoice->get_id()} "
            . nc_core('catalogue')->get_current('Domain');

        $createInvoice = new CreateInvoiceRequest(
            $shopId,
            $endDate->add(new DateInterval(INVOICE_LIFETIME_DATE_INTERVAL_SETTING)),
            $payment->currency,
            $product,
            new Metadata([
                'orderId' => $invoice->get_id(),
                'cms' => "Netcat {$this->nc_core->get_edition_name()}",
                'cms_version' => $this->nc_core->get_full_version_number(),
                'module' => MODULE_NAME_SETTING,
                'module_version' => MODULE_VERSION_SETTING,
            ])
        );

        if (FISCALIZATION_USE === $this->settings['fiscalization']) {
            $cart = new Cart(
                "{$invoiceItem->get('name')} ({$invoiceItem->get('qty')})",
                $invoiceItem->get('qty'),
                $this->prepareAmount($invoiceItem->get('item_price'))
            );
            $vat = $payment->vat_rate;

            if (!empty($vat)) {
                $vatRate = $this->getVatRate($vat);

                if (in_array($vatRate, TaxMode::$validValues)) {
                    $taxMode = new TaxMode($vatRate);
                } else {
                    throw new WrongDataException(ERROR_TAX_RATE_IS_NOT_VALID . $payment->name, 400);
                }
                $cart->setTaxMode($taxMode);
            }

            $createInvoice->addCart($cart);
        } else {
            $createInvoice->setAmount($this->prepareAmount($invoice->get_amount('%0.2F')));
        }

        return $this->sender->sendCreateInvoiceRequest($createInvoice);
    }

    /**
     * @param $vat_rate
     *
     * @return string
     */
    public function getVatRate($vat_rate)
    {
        if (isset($this->vat_map[$vat_rate])) {
            return $this->vat_map[$vat_rate];
        }

        return $vat_rate;
    }

    /**
     * @param float $price
     *
     * @return string
     */
    private function prepareAmount($price)
    {
        return number_format($price, 2, '', '');
    }

    /**
     * @param CreateInvoiceResponse $invoice
     * @param string                $customerId
     *
     * @throws RequestException
     * @throws WrongDataException
     * @throws WrongRequestException
     */
    public function createPayment(CreateInvoiceResponse $invoice, $customerId)
    {
        $payRequest = new CreatePaymentRequest(
            new PaymentFlowInstantRequest(),
            new CustomerPayerRequest($customerId),
            $invoice->id
        );

        $this->sender->sendCreatePaymentRequest($payRequest);
    }
}

