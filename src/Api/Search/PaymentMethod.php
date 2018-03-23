<?php

namespace src\Api\Search;

use src\Api\Exceptions\WrongDataException;

/**
 * Метод оплаты
 */
class PaymentMethod
{

    const BANK_CARD = 'bankCard';
    const PAYMENT_TERMINAL = 'paymentTerminal';

    /**
     * Допустимые значения метода оплаты
     */
    private $validValues = array(
        self::BANK_CARD,
        self::PAYMENT_TERMINAL,
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
            throw new WrongDataException('Неверное значение поля `paymentMethod`');
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
