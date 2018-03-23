<?php

use src\Api\ContactInfo;
use src\Api\Customers\CreateCustomer\Request\CreateCustomerRequest;
use src\Api\Customers\CustomerResponse\CustomerResponse;
use src\Api\Exceptions\WrongDataException;
use src\Api\Exceptions\WrongRequestException;
use src\Api\Invoices\CreateInvoice\Response\CreateInvoiceResponse;
use src\Api\Metadata;
use src\Client\Sender;
use src\Exceptions\RequestException;

class Customers
{

    /**
     * @var Sender
     */
    private $sender;

    /**
     * @var array
     */
    private $settings;

    /**
     * @var nc_Core
     */
    private $ncCore;

    private $currencyMap = array('RUR' => 'RUB');

    /**
     * @param Sender $sender
     */
    public function __construct(Sender $sender)
    {
        $this->settings = nc_Core::get_object()->get_settings('', 'rbkmoney');
        $this->ncCore = nc_Core::get_object();

        $this->sender = $sender;
    }

    /**
     * Возвращает код валюты с учётом $this->currency_map
     */
    public function get_currency_code($iso_currency_code)
    {
        if (isset($this->currencyMap[$iso_currency_code])) {
            return $this->currencyMap[$iso_currency_code];
        }

        return $iso_currency_code;
    }

    /**
     * @return array
     */
    private function getRecurrentItems()
    {
        $recurrent = $this->ncCore->db->get_results("SELECT `article` FROM `RBKmoney_Recurrent_Items`");

        if (empty($recurrent)) {
            return array();
        }

        $result = '';

        foreach ($recurrent as $pay) {
            $result .= "$pay->article\n";
        }

        return explode("\n", trim($result));
    }

    /**
     * @param string $recurrentCustomerId
     * @param array  $item
     *
     * @return void
     */
    private function saveRecurrent($recurrentCustomerId, array $item)
    {
        $name = $item['name'];
        $amount = $item['amount'];
        $messageId = $item['message_id'];
        $subClassId = $item['sub_class_id'];
        $currency = $item['currency'];
        $vatRate = $item['vat_rate'];

        $this->ncCore->db->query(
            "INSERT INTO `RBKmoney_Recurrent` (`recurrent_customer_id`, `amount`, `name`, `message_id`, `sub_class_id`, `currency`, `vat_rate`)
                  VALUES ('$recurrentCustomerId', '$amount', '$name', '$messageId', '$subClassId', '$currency', '$vatRate')"
        );
    }

    /**
     * @param nc_payment_invoice    $invoice
     * @param                       $userId
     * @param CreateInvoiceResponse $invoiceResponse
     *
     * @return array
     *
     * @throws RequestException
     * @throws WrongDataException
     * @throws WrongRequestException
     */
    private function createCustomer(
        nc_payment_invoice $invoice,
        $userId,
        CreateInvoiceResponse $invoiceResponse
    ) {
        $contactInfo = new ContactInfo();
        $contact = $invoice->get_customer_contact_for_receipt();

        if (preg_match('/@/', $contact)) {
            $contactInfo->setEmail($contact);
        } else {
            $contactInfo->setPhone($contact);
        }

        $metadata = new Metadata(array(
            'shop' => nc_core('catalogue')->get_current('Domain'),
            'userId' => $userId,
            'firstInvoiceId' => $invoiceResponse->id,
            'cms' => "Netcat {$this->ncCore->get_edition_name()}",
            'cms_version' => $this->ncCore->get_full_version_number(),
            'module' => MODULE_NAME_SETTING,
            'module_version' => MODULE_VERSION_SETTING,
        ));

        $createCustomer = new CreateCustomerRequest(
            $this->settings['shopId'],
            $contactInfo,
            $metadata
        );

        $customer = $this->sender->sendCreateCustomerRequest($createCustomer);

        $this->saveCustomer($customer->customer, $userId);

        $response = $this->getCustomer($userId);
        $response += array(
            'hash' => $customer->payload,
        );

        return $response;
    }

    /**
     * @param CustomerResponse $customer
     * @param int              $userId
     *
     * @return void
     */
    private function saveCustomer(CustomerResponse $customer, $userId)
    {
        $this->ncCore->db->query(
            "INSERT INTO `RBKmoney_Recurrent_Customers` (`user_id`, `customer_id`, `status`)
                  VALUES ('$userId', '$customer->id', '{$customer->status->getValue()}')"
        );
    }

    /**
     * @param int $classId
     * @param int $messageId
     *
     * @return string | null
     */
    private function getArticle($classId, $messageId)
    {
        return $this->ncCore->db->get_var("SELECT `Article`
            FROM `Message$classId`
            WHERE `Message_ID` = '$messageId'"
        );
    }

    /**
     * @param nc_payment_invoice    $invoice
     * @param                       $userId
     * @param CreateInvoiceResponse $invoiceResponse
     *
     * @return string | null
     * @throws RequestException
     * @throws WrongDataException
     * @throws WrongRequestException
     * @throws nc_record_exception
     */
    public function createRecurrent(
        nc_payment_invoice $invoice,
        $userId,
        CreateInvoiceResponse $invoiceResponse
    ) {
        $articles = array();
        $resultCustomer = null;

        /**
         * @var $item nc_payment_invoice_item
         */
        foreach ($invoice->get_items() as $item) {
            $componentId = $item->get('source_component_id');
            if (empty($componentId)) {
                continue;
            }

            $article = $this->getArticle($item->get('source_component_id'), $item->get('source_item_id'));
            $articles[$item->get('item_price')] = $article;

            $items[$article] = array(
                'amount' => $item->get('item_price'),
                'name' => $item->get('name'),
                'message_id' => $item->get('source_component_id'),
                'sub_class_id' => $item->get('source_item_id'),
                'currency' => $this->get_currency_code($invoice->get_currency()),
                'vat_rate' => $item->get('vat_rate'),
            );
        }
        $intersections = array_intersect($articles, $this->getRecurrentItems());

        if (!empty($intersections)) {
            $customer = $this->getCustomer($userId);

            if (empty($customer)) {
                $customer = $this->createCustomer($invoice, $userId, $invoiceResponse);
            }

            foreach ($intersections as $article) {
                $this->saveRecurrent($customer['id'], $items[$article]);
            }
        }

        if (!empty($customer['hash'])) {
            $resultCustomer = 'data-customer-id="' . $customer['customer_id'] . '"
            data-customer-access-token="' . $customer['hash'] . '"';
        }

        return $resultCustomer;
    }

    /**
     * @param int $userId
     *
     * @return array | null
     */
    private function getCustomer($userId)
    {
        return (array) $this->ncCore->db->get_row("SELECT `customer_id`, `id` FROM `RBKmoney_Recurrent_Customers` WHERE `user_id` = '$userId'");
    }

}
