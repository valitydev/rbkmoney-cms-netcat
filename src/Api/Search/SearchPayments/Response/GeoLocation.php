<?php

namespace src\Api\Search\SearchPayments\Response;

use src\Api\RbkDataObject;

/**
 * Информация о геопозиции
 */
class GeoLocation extends RbkDataObject
{

    /**
     * @var int
     */
    public $cityGeoId;

    /**
     * @var int
     */
    public $countryGeoId;

    /**
     * @param int $cityGeoId
     * @param int $countryGeoId
     */
    public function __construct($cityGeoId, $countryGeoId)
    {
        $this->cityGeoId = $cityGeoId;
        $this->countryGeoId = $countryGeoId;
    }

}
