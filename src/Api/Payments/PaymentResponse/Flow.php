<?php

namespace src\Api\Payments\PaymentResponse;

use src\Api\RbkDataObject;

/**
 * Параметры созданного платежа
 */
abstract class Flow extends RbkDataObject
{

    const HOLD = 'PaymentFlowHold';
    const INSTANT = 'PaymentFlowInstant';

    /**
     * Тип процесса выполнения платежа
     *
     * @var string
     */
    public $type;

}
