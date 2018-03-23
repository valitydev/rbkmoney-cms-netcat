<?php

namespace src\Api\Payments\PaymentResponse;

use src\Api\RbkDataObject;

abstract class Payer extends RbkDataObject
{

    const CUSTOMER_PAYER = 'CustomerPayer';
    const PAYMENT_RESOURCE_PAYER = 'PaymentResourcePayer';

    public $payerType;

}
