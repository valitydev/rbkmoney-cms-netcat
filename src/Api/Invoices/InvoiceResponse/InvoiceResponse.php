<?php

namespace src\Api\Invoices\InvoiceResponse;

use DateTime;
use src\Api\Exceptions\WrongDataException;
use src\Api\Interfaces\ResponseInterface;
use src\Api\Invoices\CreateInvoice\Cart;
use src\Api\Metadata;
use src\Api\Invoices\Status;
use src\Api\RbkDataObject;
use src\Helpers\ResponseHandler;
use stdClass;

/**
 * Родительский объект ответов с информацией об инвойсе
 */
class InvoiceResponse extends RbkDataObject implements ResponseInterface
{

    /**
     * Идентификатор инвойса
     *
     * @var string
     */
    public $id;

    /**
     * Идентификатор магазина
     *
     * @var string
     */
    public $shopId;

    /**
     * Дата и время создания инвойса
     *
     * @var DateTime
     */
    public $createdAt;

    /**
     * Дата и время окончания действия инвойса
     *
     * @var DateTime
     */
    public $endDate;

    /**
     * Стоимость предлагаемых товаров или услуг, в минорных денежных единицах,
     * например в копейках в случае указания российских рублей в качестве валюты.
     *
     * @var int
     */
    public $amount;

    /**
     * Валюта, символьный код согласно ISO 4217.
     *
     * @var string
     */
    public $currency;

    /**
     * Наименование предлагаемых товаров или услуг
     *
     * @var string
     */
    public $product;

    /**
     * Описание предлагаемых товаров или услуг
     *
     * @var string | null
     */
    public $description;

    /**
     * Идентификатор шаблона (для инвойсов, созданных по шаблону)
     *
     * @var string
     */
    public $invoiceTemplateId;

    /**
     * Корзина с набором позиций продаваемых товаров или услуг
     *
     * @var array | Cart[] | null
     */
    public $cart;

    /**
     * Связанные с инвойсом метаданные
     *
     * @var Metadata
     */
    public $metadata;

    /**
     * Статус инвойса
     *
     * @var Status
     */
    public $status;

    /**
     * Причина отмены или погашения инвойса
     *
     * @var string
     */
    public $reason;

    /**
     * @param stdClass $invoice
     *
     * @throws WrongDataException
     */
    public function __construct(stdClass $invoice)
    {
        $this->id = $invoice->id;
        $this->shopId = $invoice->shopID;
        $this->createdAt = new DateTime($invoice->createdAt);
        $this->endDate = new DateTime($invoice->dueDate);
        $this->amount = $invoice->amount;
        $this->currency = $invoice->currency;
        $this->product = $invoice->product;
        $this->metadata = new Metadata((array)$invoice->metadata);
        $this->status = new Status($invoice->status);

        if (property_exists($invoice, 'description')) {
            $this->description = $invoice->description;
        }

        if (property_exists($invoice, 'invoiceTemplateID')) {
            $this->invoiceTemplateId = $invoice->invoiceTemplateID;
        }

        if (property_exists($invoice, 'cart')) {
            foreach ($invoice->cart as $cart) {
                $this->cart[] = ResponseHandler::getCart($cart);
            }
        }

        if (property_exists($invoice, 'reason')) {
            $this->reason = $invoice->reason;
        }
    }

}
