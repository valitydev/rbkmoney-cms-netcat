<?php

namespace src\Api\Tokens\CreatePaymentResource\Request;

use src\Api\RbkDataObject;

abstract class PaymentTool extends RbkDataObject
{

    /**
     * Типы платежного средства
     */
    const CARD_DATA = 'CardData';
    const PAYMENT_TERMINAL_DATA = 'PaymentTerminalData';
    const DIGITAL_WALLET_DATA = 'DigitalWalletData';

    /**
     * @var string
     */
    public $paymentToolType;

    /**
     * @return array
     */
    public function toArray()
    {
        $properties = array();

        foreach ($this as $property => $value) {
            if (!empty($value)) {
                $properties[$property] = $value;
            }
        }

        return $properties;
    }

}
