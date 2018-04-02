<?php

namespace src\Api\Payments\PaymentResponse;

use src\Api\RBKMoneyDataObject;

/**
 * Тип платежного средства
 */
abstract class PaymentToolDetails extends RBKMoneyDataObject
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
