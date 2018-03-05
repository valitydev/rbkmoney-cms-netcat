<?php

namespace src\Api;

use src\Api\Exceptions\WrongDataException;

/**
 * Статус платежа
 */
class Status
{

    public const PENDING = 'pending';
    public const PROCESSED = 'processed';
    public const CAPTURED = 'captured';
    public const CANCELLED = 'cancelled';
    public const REFUNDED = 'refunded';
    public const FAILED = 'failed';

    /**
     * Допустимые значения статуса возврата
     */
    private const VALID_VALUES = [
        self::PENDING,
        self::PROCESSED,
        self::CAPTURED,
        self::CANCELLED,
        self::REFUNDED,
        self::FAILED,
    ];

    /**
     * @var string
     */
    private $value;

    /**
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
