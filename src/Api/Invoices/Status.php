<?php

namespace src\Api\Invoices;

use src\Api\Exceptions\WrongDataException;

/**
 * Статус инвойса
 */
class Status
{

    public const UNPAID = 'unpaid';
    public const CANCELLED = 'cancelled';
    public const PAID = 'paid';
    public const FULFILLED = 'fulfilled';

    /**
     * Валидные значения статуса инвойса
     */
    private const VALID_VALUES = [
        self::UNPAID,
        self::CANCELLED,
        self::PAID,
        self::FULFILLED,
    ];

    /**
     * @var string
     */
    private $value;

    /**
     * Status constructor.
     *
     * @param string $value
     *
     * @throws WrongDataException
     */
    public function __construct(string $value)
    {
        if (!in_array($value, self::VALID_VALUES)) {
            throw new WrongDataException('Неверное значение поля `status`');
        }

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

}
