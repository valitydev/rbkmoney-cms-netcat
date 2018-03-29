<?php

namespace src\Api\Payments\PaymentResponse;

use src\Api\RBKMoneyDataObject;

abstract class Payer extends RBKMoneyDataObject
{

    const CUSTOMER_PAYER = 'CustomerPayer';
    const PAYMENT_RESOURCE_PAYER = 'PaymentResourcePayer';

    public $payerType;

}
