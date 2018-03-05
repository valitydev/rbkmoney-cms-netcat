<?php

namespace src\Helpers;

use src\Api\Exceptions\WrongDataException;
use src\Api\Invoices\CreateInvoice\Cart;
use src\Api\Invoices\CreateInvoice\TaxMode;
use src\Api\Invoices\InvoiceResponse\CartResponse;
use stdClass;

/**
 * Обрабатывает ответы Rbk
 */
class ResponseHandler
{

    /**
     * @param stdClass $invoiceCart
     *
     * @return Cart
     *
     * @throws WrongDataException
     */
    public static function getCart(stdClass $invoiceCart): Cart
    {
        $cart = new CartResponse(
            $invoiceCart->product,
            $invoiceCart->quantity,
            $invoiceCart->price
        );

        if (property_exists($invoiceCart, 'taxMode')) {
            $cart->setTaxMode(new TaxMode($invoiceCart->taxMode->rate));
        }

        if (property_exists($invoiceCart, 'cost')) {
            $cart->setCost($invoiceCart->cost);
        }

        return $cart;
    }

}
