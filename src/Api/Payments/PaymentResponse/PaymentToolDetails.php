<?php

namespace src\Api\Payments\PaymentResponse;

use src\Api\RbkDataObject;

/**
 * Тип платежного средства
 */
abstract class PaymentToolDetails extends RbkDataObject
{

    /**
     * Типы информации о платежном средстве
     */
    const BANK_CARD = 'PaymentToolDetailsBankCard';
    const DIGITAL_WALLET = 'PaymentToolDetailsDigitalWallet';
    const PAYMENT_TERMINAL = 'PaymentToolDetailsPaymentTerminal';

    /**
     * @var string
     */
    public $detailsType;

}
