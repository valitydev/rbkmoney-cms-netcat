<?php

namespace src\Api\Invoices\CreateInvoice;

use src\Api\RbkDataObject;

/**
 * Корзина с набором позиций продаваемых товаров или услуг
 */
class Cart extends RbkDataObject
{

    /**
     * Описание предлагаемого товара или услуги
     *
     * @var string
     */
    public $product;

    /**
     * Количество единиц товаров или услуг, предлагаемых на продажу в этой позиции
     *
     * @var int
     */
    public $quantity;

    /**
     * Цена предлагаемого товара или услуги, в минорных денежных единицах,
     * например в копейках в случае указания российских рублей в качестве валюты
     *
     * @var int
     */
    public $price;

    /**
     * @var TaxMode | null
     */
    public $taxMode = null;

    /**
     * @param string         $product
     * @param int            $quantity
     * @param int            $price
     * @param TaxMode | null $taxMode
     */
    public function __construct(string $product, int $quantity, int $price, ?TaxMode $taxMode = null)
    {
        $this->product = $product;
        $this->quantity = $quantity;
        $this->price = $price;

        $this->taxMode = $taxMode;
    }

    /**
     * @param TaxMode $taxMode
     *
     * @return Cart
     */
    public function setTaxMode(TaxMode $taxMode): self
    {
        $this->taxMode = $taxMode;

        return $this;
    }

}