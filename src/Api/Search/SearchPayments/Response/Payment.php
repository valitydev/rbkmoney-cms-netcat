<?php

namespace src\Api\Search\SearchPayments\Response;

use DateTime;
use src\Api\Error;
use src\Api\Exceptions\WrongDataException;
use src\Api\Interfaces\ResponseInterface;
use src\Api\Metadata;
use src\Api\Payments\PaymentResponse\Flow;
use src\Api\Payments\PaymentResponse\Payer;
use src\Api\RbkDataObject;
use src\Api\Status;
use src\Helpers\ResponseHandler;
use stdClass;

class Payment extends RbkDataObject implements ResponseInterface
{

    /**
     * @var Status
     */
    public $status;

    /**
     * @var Error | null
     */
    public $error;

    /**
     * Идентификатор платежа
     *
     * @var string
     */
    public $id;

    /**
     * Идентификатор инвойса, в рамках которого был создан платеж
     *
     * @var string
     */
    public $invoiceId;

    /**
     * Идентификатор магазина, в рамках которого был создан платеж
     *
     * @var string | null
     */
    public $shopId;

    /**
     * Дата и время создания
     *
     * @var DateTime
     */
    public $createdAt;

    /**
     * Стоимость предлагаемых товаров или услуг, в минорных денежных единицах,
     * например в копейках в случае указания российских рублей в качестве валюты.
     *
     * @var int
     */
    public $amount;

    /**
     * Комиссия системы, в минорных денежных единицах
     *
     * @var int | null
     */
    public $fee;

    /**
     * Валюта, символьный код согласно ISO 4217.
     *
     * @var string
     */
    public $currency;

    /**
     * @var Flow
     */
    public $flow;

    /**
     * @var Payer
     */
    public $payer;

    /**
     * @var GeoLocation
     */
    public $geoLocationInfo;

    /**
     * Связанные с платежом метаданные
     *
     * @var Metadata
     */
    public $metadata;

    /**
     * @param stdClass $responseObject
     *
     * @throws WrongDataException
     */
    public function __construct(stdClass $responseObject)
    {
        $this->status = new Status($responseObject->status);
        $this->id = $responseObject->id;
        $this->invoiceId = $responseObject->invoiceID;
        $this->createdAt = new DateTime($responseObject->createdAt);
        $this->amount = $responseObject->amount;
        $this->currency = $responseObject->currency;
        $this->flow = ResponseHandler::getFlow($responseObject->flow);
        $this->payer = ResponseHandler::getPayer($responseObject->payer);

        if (property_exists($responseObject, 'error')) {
            $this->error = ResponseHandler::getError($responseObject->error);
        }

        if (property_exists($responseObject, 'shopID')) {
            $this->shopId = $responseObject->shopID;
        }

        if (property_exists($responseObject, 'fee')) {
            $this->fee = $responseObject->fee;
        }

        if (property_exists($responseObject, 'geoLocationInfo')) {
            $location = $responseObject->geoLocationInfo;
            $this->geoLocationInfo = new GeoLocation($location->cityGeoID, $location->countryGeoID);
        }

        if (property_exists($responseObject, 'metadata')) {
            if (false !== current($responseObject->metadata)) {
                $this->metadata = new Metadata((array)current($responseObject->metadata));
            }
        }
    }

}
