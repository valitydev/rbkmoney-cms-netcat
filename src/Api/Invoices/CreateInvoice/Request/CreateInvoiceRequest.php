<?php

namespace src\Api\Invoices\CreateInvoice\Request;

use DateTime;
use src\Api\Interfaces\PostRequestInterface;
use src\Api\Invoices\CreateInvoice\Cart;
use src\Api\Metadata;
use src\Api\RbkDataObject;

class CreateInvoiceRequest extends RbkDataObject implements PostRequestInterface
{

    private const URL = '/processing/invoices';

    /**
     * Идентификатор магазина
     *
     * @var string
     */
    private $shopID;

    /**
     * Дата и время окончания действия инвойса, после наступления которых его уже невозможно будет
     * оплатить
     *
     * @var string
     */
    private $dueDate;

    /**
     * Стоимость предлагаемых товаров или услуг, в минорных денежных единицах,
     * например в копейках в случае указания российских рублей в качестве валюты.
     *
     * @var int
     */
    private $amount;

    /**
     * Валюта, символьный код согласно ISO 4217.
     *
     * @var string
     */
    private $currency;

    /**
     * Наименование предлагаемых товаров или услуг
     *
     * @var string
     */
    private $product;

    /**
     * Описание предлагаемых товаров или услуг
     *
     * @var string | null
     */
    private $description;

    /**
     * @var array | Cart[] | null
     */
    private $cart;

    /**
     * @var Metadata
     */
    private $metadata;

    /**
     * @param string   $shopId
     * @param DateTime $endDate
     * @param string   $currency
     * @param string   $product
     * @param Metadata $metadata
     * @param int      $amount
     */
    public function __construct(
        string $shopId,
        DateTime $endDate,
        string $currency,
        string $product,
        Metadata $metadata,
        int $amount
    ) {
        $this->shopID = $shopId;
        $this->dueDate = $endDate->format(DATE_ATOM);
        $this->currency = $currency;
        $this->product = $product;
        $this->metadata = $metadata;
        $this->amount = $amount;
    }

    /**
     * @param string $description
     *
     * @return CreateInvoiceRequest
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param Cart $cart
     *
     * @return CreateInvoiceRequest
     */
    public function addCart(Cart $cart): self
    {
        $this->cart[] = $cart;

        return $this;
    }

    /**
     * @param array | Cart[] $carts
     *
     * @return CreateInvoiceRequest
     */
    public function addCarts(array $carts): self
    {
        foreach ($carts as $cart) {
            if ($cart instanceof Cart) {
                $this->cart[] = $cart;
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $properties = [];

        foreach ($this as $property => $value) {
            if (!empty($value)) {
                if (is_object($value)) {
                    $properties[$property] = $value->toArray();
                } else {
                    $properties[$property] = $value;
                }
            }
        }

        return $properties;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return self::URL;
    }

}
