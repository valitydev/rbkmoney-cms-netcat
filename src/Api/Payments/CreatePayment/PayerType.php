<?php

namespace src\Api\Payments\CreatePayment;

use src\Api\Exceptions\WrongDataException;

/**
 * Тип платежного средства
 */
class PayerType
{

    const CUSTOMER_PAYER = 'CustomerPayer';
    const PAYMENT_RESOURCE_PAYER = 'PaymentResourcePayer';

    /**
     * Массив возможных типов
     */
    private $validValues = array(
        self::CUSTOMER_PAYER,
        self::PAYMENT_RESOURCE_PAYER,
    );

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     *
     * @throws WrongDataException
     */
    public function __construct($value)
    {
        if (!in_array($value, $this->validValues)) {
            throw new WrongDataException('Неверное значение поля `payerType`');
        }

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

}
