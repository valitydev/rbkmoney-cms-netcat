<?php

namespace src\Api\Customers\CustomerResponse;

use src\Api\ContactInfo;
use src\Api\Exceptions\WrongDataException;
use src\Api\Interfaces\ResponseInterface;
use src\Api\Metadata;
use src\Api\RbkDataObject;
use src\Helpers\ResponseHandler;
use stdClass;

class CustomerResponse extends RbkDataObject implements ResponseInterface
{

    /**
     * Идентификатор плательщика
     *
     * @var string | null
     */
    public $id;

    /**
     * Идентификатор магазина
     *
     * @var string
     */
    public $shopId;

    /**
     * Контактные данные плательщика
     *
     * @var ContactInfo
     */
    public $contactInfo;

    /**
     * Статус плательщика
     *
     * @var Status | null
     */
    public $status;

    /**
     * Связанные с плательщиком метаданные
     *
     * @var Metadata
     */
    public $metadata;

    /**
     * @param stdClass $customer
     *
     * @throws WrongDataException
     */
    public function __construct(stdClass $customer)
    {
        $this->shopId = $customer->shopID;
        $this->contactInfo = ResponseHandler::getContactInfo($customer->contactInfo);
        $this->shopId = $customer->shopID;
        $this->metadata = new Metadata((array)$customer->metadata);

        if (property_exists($customer, 'id')) {
            $this->id = $customer->id;
        }

        if (property_exists($customer, 'status')) {
            $this->status = new Status($customer->status);
        }
    }

}
