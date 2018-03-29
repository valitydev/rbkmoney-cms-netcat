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
    const TAX_0 = '0%';

    /**
     * Ставка налога 10%
     */
    const TAX_10 = '10%';

    /**
     * Ставка налога 18%
     */
    const TAX_18 = '18%';

    /**
     * Ставка налога 10/110
     */
    const TAX_10_110 = '10/110';

    /**
     * Ставка налога 18/118
     */
    const TAX_18_118 = '18/118';

    const TYPE = 'InvoiceLineTaxVAT';

    /**
     * Валидные значения ставки налога
     */
    static $validValues = [
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
    public function __construct($rate)
    {
        if (!in_array($rate, self::$validValues)) {
            throw new WrongDataException(WRONG_VALUE . ' `rate`');
        }

        $this->rate = $rate;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'type' => $this->type,
            'rate' => $this->rate,
        ];
    }

}
