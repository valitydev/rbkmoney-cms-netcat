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

    /**
     * @return array
     */
    public function toArray()
    {
        $properties = [];

        foreach ($this as $property => $value) {
            if (is_object($value)) {
                $properties[$property] = $value->getValue();
            } elseif (!empty($value)) {
                $properties[$property] = $value;
            }
        }

        return $properties;
    }
}
