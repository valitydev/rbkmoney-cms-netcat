<?php

namespace src\Api\Tokens\CreatePaymentResource\Request;

use src\Api\Exceptions\WrongDataException;

/**
 * Провайдер платежного терминала
 */
class TerminalProvider
{

    const EUROSET = 'euroset';

    /**
     * Допустимые значения провайдера платежного терминала
     */
    private $validValues = [
        self::EUROSET,
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
    public function __construct($value)
    {
        if (!in_array($value, $this->validValues)) {
            throw new WrongDataException(WRONG_VALUE . ' `terminalProvider`', 400);
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
