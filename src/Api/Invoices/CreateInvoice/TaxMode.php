<?php

namespace src\Api\Invoices\CreateInvoice;

use src\Api\Exceptions\WrongDataException;

/**
 * Схема налогообложения предлагаемого товара или услуги
 */
class TaxMode
{

    /**
     * Ставка налога 0%
     */
    public const TAX_0 = '0%';

    /**
     * Ставка налога 10%
     */
    public const TAX_10 = '10%';

    /**
     * Ставка налога 18%
     */
    public const TAX_18 = '18%';

    /**
     * Ставка налога 10/110
     */
    public const TAX_10_110 = '10/110';

    /**
     * Ставка налога 18/118
     */
    public const TAX_18_118 = '18/118';

    private const TYPE = 'InvoiceLineTaxVAT';

    /**
     * Валидные значения ставки налога
     */
    public const VALID_VALUES = [
        self::TAX_0,
        self::TAX_10,
        self::TAX_18,
        self::TAX_10_110,
        self::TAX_18_118,
    ];

    /**
     * Тип схемы налогообложения
     *
     * @var string
     */
    public $type = self::TYPE;

    /**
     * Ставка налога
     *
     * @var string
     */
    public $rate;

    /**
     * @param string $rate
     *
     * @throws WrongDataException
     */
    public function __construct(string $rate)
    {
        if (!in_array($rate, self::VALID_VALUES)) {
            throw new WrongDataException('Неверное значение поля `rate`');
        }

        $this->rate = $rate;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'rate' => $this->rate,
        ];
    }

}
