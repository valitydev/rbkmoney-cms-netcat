<?php

use src\Api\Exceptions\WrongDataException;
use src\Api\Invoices\CreateInvoice\Cart;
use src\Api\Invoices\CreateInvoice\Request\CreateInvoiceRequest;
use src\Api\Invoices\CreateInvoice\TaxMode;
use src\Api\Invoices\Status;
use src\Api\Metadata;
use src\Api\Webhooks\CreateWebhook\Request\CreateWebhookRequest;
use src\Api\Webhooks\GetWebhooks\Request\GetWebhooksRequest;
use src\Api\Webhooks\InvoicesTopicScope;
use src\Api\Webhooks\WebhookResponse\WebhookResponse;
use src\Client\Client;
use src\Client\Sender;
use src\Exceptions\RequestException;

class nc_payment_system_rbk extends nc_payment_system
{

    public const ERROR_SHOP_ID_IS_NOT_VALID = NETCAT_MODULE_PAYMENT_RBK_ERROR_ESHOPID_IS_NOT_VALID;
    public const ERROR_AMOUNT = NETCAT_MODULE_PAYMENT_RBK_ERROR_AMOUNT;
    public const ERROR_ORDER_ID_IS_NOT_VALID = NETCAT_MODULE_PAYMENT_RBK_ERROR_ORDER_ID_IS_NOT_VALID;

    private const TARGET_URL = 'https://api.rbk.money/v1';

    private const BEGIN_KEY = '-----BEGIN PUBLIC KEY-----';
    private const END_KEY = '-----END PUBLIC KEY-----';

    protected $accepted_currencies = array('RUB');

    protected $currency_map = array('RUR' => 'RUB');

    /**
     * @var Sender
     */
    private $sender;

    public function __construct()
    {
        $this->settings = nc_Core::get_object()->get_settings('','rbk');

        include dirname(__DIR__)."/../../rbk/src/autoload.php";

        $this->sender = new Sender(new Client(
            $this->get_setting('apiKey'),
            $this->get_setting('shopId'),
            self::TARGET_URL
        ));
    }

    public function validate_payment_request_parameters()
    {
        if (!$this->get_setting('shopId')) {
            $this->add_error(nc_payment_system_rbk::ERROR_SHOP_ID_IS_NOT_VALID);
        }
        if (!$this->get_setting('taxRate')) {
            $this->add_error(ERROR_TAX_RATE_IS_NOT_VALID);
        }
        if (!$this->get_setting('apiKey')) {
            $this->add_error(ERROR_API_KEY_IS_NOT_VALID);
        }
        if (!$this->get_setting('successUrl')) {
            $this->add_error(ERROR_SUCCESS_URL_IS_NOT_VALID);
        }
        if (!$this->get_setting('successUrl')) {
            $this->add_error(ERROR_SUCCESS_URL_IS_NOT_VALID);
        }
        if (!$this->get_setting('paymentType')) {
            $this->add_error(ERROR_PAYMENT_TYPE_IS_NOT_VALID);
        }
        if (PAYMENT_TYPE_HOLD === $this->get_setting('paymentType')) {
            if (!$this->get_setting('holdExpiration')) {
                $this->add_error(ERROR_HOLD_EXPIRATION_IS_NOT_VALID);
            }
        }
    }

    /**
     * @param nc_payment_invoice|null $invoice
     *
     * @throws nc_record_exception
     */
    public function on_response(nc_payment_invoice $invoice = null)
    {
        $message = file_get_contents('php://input');
        $callback = json_decode($message);
        $invoice = new nc_payment_invoice();

        if (!empty($orderId = $callback->invoice->metadata->orderId)) {
            $invoice->load($orderId);
        }

        if(!empty($invoice->get('status')) && $invoice->get('status') == $invoice::STATUS_SUCCESS){
            $this->on_payment_success($invoice);
        }
        elseif (!empty($invoice->get('status')) && $invoice->get('status') != $invoice::STATUS_SUCCESS) {
            echo '<html>
              <head>
                <title>Оплата заказа</title>
                <style>
                  p {
                    text-align: center;
                    top: 15%;
                    position: relative;
                    font-weight: bold;
                    font-size: 20px;
                  }
                  a {
                    color: red;
                  }
                </style>
              </head>
              <body>
                <p>Ваш заказ ожидает оплаты. Перейти на <a href="/">сайт</a>.</p>
              </body>
            </html>';
        }
        else {
            $this->on_payment_failure($invoice);
        }
    }

    /**
     * @param nc_payment_invoice | null $invoice
     *
     * @throws WrongDataException
     * @throws nc_record_exception
     */
    public function validate_payment_callback_response(nc_payment_invoice $invoice = null)
    {
        $headers = getallheaders();
        $signature = $this->getParametersContentSignature($headers['Content-Signature']);

        if (empty($signature)) {
            throw new WrongDataException('Недопустимое содержимое `Content-Signature`');
        }

        $signDecode = base64_decode(strtr($signature, '-_,', '+/='));

        $message = file_get_contents('php://input');

        if (empty($message)) {
            throw new WrongDataException('Недопустимое содержимое `callback`');
        }

        if (!$this->verificationSignature($message, $signDecode)) {
            throw new WrongDataException('Недопустимая сигнатура');
        }

        $callback = json_decode($message);
        $invoice = new nc_payment_invoice();

        if (!empty($orderId = $callback->invoice->metadata->orderId)) {
            $invoice->load($orderId);

            $status = $callback->invoice->status;

            if (in_array($status, [Status::PAID, Status::FULFILLED])) {
                $invoice->set('status', $invoice::STATUS_SUCCESS)->save();
            } elseif (Status::CANCELLED === $status) {
                $invoice->set('status', $invoice::STATUS_CANCELLED)->save();
            } elseif (Status::UNPAID === $status) {
                $invoice->set('status', $invoice::STATUS_WAITING)->save();
            }
        }
    }

    /**
     * @param string $data
     * @param string $signature
     *
     * @return bool
     */
    function verificationSignature(string $data, string $signature): bool
    {
        $publicKey = chunk_split($this->get_setting('payPublicKey', 64, "\n"));

        $key = self::BEGIN_KEY . PHP_EOL . $publicKey . self::END_KEY;

        $publicKeyId = openssl_pkey_get_public($key);

        if (empty($publicKeyId)) {
            return false;
        }

        $verify = openssl_verify($data, $signature, $publicKeyId, OPENSSL_ALGO_SHA256);

        return ($verify == 1);
    }

    /**
     * Возвращает сигнатуру из хедера для верификации
     *
     * @param string $contentSignature
     *
     * @return string
     *
     * @throws WrongDataException
     */
    private function getParametersContentSignature(string $contentSignature): string
    {
        $signature = preg_replace("/alg=(\S+);\sdigest=/", '', $contentSignature);

        if (empty($signature)) {
            throw new WrongDataException('Недопустимое значение `Content-Signature`');
        }

        return $signature;
    }

    /**
     * Проверка корректности счёта
     * @param nc_payment_invoice $invoice
     */
    protected function validate_invoice(nc_payment_invoice $invoice)
    {
        if (!($invoice->get_id())) {
            $this->add_error(NETCAT_MODULE_PAYMENT_ORDER_ID_IS_NULL);
        }

        if (!$this->is_amount_valid(number_format($invoice->get_amount("%0.2F"), 2, '', ''))) {
            $this->add_error(NETCAT_MODULE_PAYMENT_INCORRECT_PAYMENT_AMOUNT);
        }

        if (!$this->is_currency_accepted($this->get_currency_code($invoice->get_currency()))) {
            $error = sprintf(NETCAT_MODULE_PAYMENT_INCORRECT_PAYMENT_CURRENCY,
                htmlspecialchars($invoice->get_currency()));

            $this->add_error($error);
        }
    }

    /**
     * @param nc_payment_invoice $invoice
     *
     * @return void
     *
     * @throws Exception
     */
    public function execute_payment_request(nc_payment_invoice $invoice)
    {
        if (PAYMENT_TYPE_HOLD === $this->get_setting('paymentType')) {
            $holdType =  true;
        } else {
            $holdType =  false;
        }
        if ($holdType) {
            if (EXPIRATION_PAYER === $this->get_setting('holdExpiration')) {
                $holdExpiration = 'data-hold-expiration="cancel"';
            } else {
                $holdExpiration = 'data-hold-expiration="capture"';
            }
        } else {
            $holdExpiration = '';
        }
        if (NOT_SHOW_CARD_HOLDER === $this->get_setting('cardHolder')) {
            $requireCardHolder = false;
        } else {
            $requireCardHolder = true;
        }

        $shopId = $this->get_setting('shopId');
        $endDate = (new DateTime())->add(new DateInterval('PT2H'));
        $invoiceId = $invoice->get_id();
        $product = 'Оплата заказа на ' . nc_core('catalogue')->get_current('Domain');
        $carts = [];

        if (!$this->issetWebhook()) {
            $this->createWebhook($shopId);
        }

        /**
         * @var $item nc_payment_invoice_item
         */
        foreach ($invoice->get_items() as $item) {
            $quantity = $item->get('qty');
            $itemName = $item->get('name');
            $carts[] = new Cart(
                "$itemName ($quantity)",
                $quantity,
                number_format($item->get('item_price'), 2, '', ''),
                new TaxMode($this->get_setting('taxRate'))
            );
        }

        $createInvoice = new CreateInvoiceRequest(
            $shopId,
            $endDate,
            $this->get_currency_code($invoice->get_currency()),
            $product,
            new Metadata(['orderId' => $invoiceId]),
            number_format($invoice->get_amount('%0.2F'), 2, '', '')
        );

        $createInvoice->addCarts($carts);

        $invoiceResponse = $this->sender->sendCreateInvoiceRequest($createInvoice);

        ob_end_clean();

        $form = '<form action="' . $this->get_setting('successUrl') . '" name="pay_form" method="GET">
        <input type="hidden" name="orderId" value="' . $invoiceId . '">
        <input type="hidden" name="paySystem" value="' . get_class($this) . '">
    <script src="https://checkout.rbk.money/checkout.js" class="rbkmoney-checkout"
            data-invoice-id="' . $invoiceResponse->id . '"
            data-payment-flow-hold="' . $holdType . '"
            data-obscure-card-cvv="true"
            data-require-cardholder="'.$requireCardHolder.'"
            ' . $holdExpiration . '
            data-invoice-access-token="' . $invoiceResponse->payload . '"
            data-name="Оплата заказа №' . $invoiceId . '"
            data-description="' . $product . '"
            data-label="Оплатить">
    </script>
</form>
<script>window.onload = function() {
     document.getElementById("rbkmoney-button").style.display = "none";
     document.getElementById("rbkmoney-button").click();
  };
</script>';

        echo $form;
    }

    /**
     * @param string $shopId
     *
     * @return void
     *
     * @throws RequestException
     * @throws WrongDataException
     */
    private function createWebhook(string $shopId): void
    {
        $invoiceScope = new InvoicesTopicScope($shopId, [InvoicesTopicScope::INVOICE_PAID]);

        $webhook = $this->sender->sendCreateWebhookRequest(
            new CreateWebhookRequest($invoiceScope, $this->get_callback_script_url())
        );

        nc_Core::get_object()->set_settings('payPublicKey', $webhook->publicKey, 'rbk');
    }

    /**
     * @return bool
     *
     * @throws RequestException
     * @throws WrongDataException
     */
    private function issetWebhook(): bool
    {
        $webhooks = $this->sender->sendGetWebhooksRequest(new GetWebhooksRequest());

        /**
         * @var $webhook WebhookResponse
         */
        foreach ($webhooks->webhooks as $webhook) {
            if (empty($webhook)) {
                continue;
            }
            if ($this->get_setting('payPublicKey') === $webhook->publicKey) {
                return true;
            } else {
                $invoicePaid = in_array(InvoicesTopicScope::INVOICE_PAID, $webhook->scope->eventTypes);

                if ($this->get_callback_script_url() === $webhook->url && $invoicePaid) {
                    nc_Core::get_object()->set_settings('payPublicKey', $webhook->publicKey, 'rbk');

                    return true;
                }
            }
        }

        return false;
    }

}
