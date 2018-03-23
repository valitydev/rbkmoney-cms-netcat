<?php

namespace src\Api\Customers\CreateCustomer\Response;

use src\Api\Customers\CustomerResponse\CustomerResponse;
use src\Api\Exceptions\WrongDataException;
use src\Api\Interfaces\ResponseInterface;
use src\Api\RbkDataObject;
use stdClass;

class CreateCustomerResponse extends RbkDataObject implements ResponseInterface
{

    /**
     * @var CustomerResponse
     */
    public $customer;

    /**
     * Содержимое токена для доступа
     *
     * @var string
     */
    public $payload;

    /**
     * @param stdClass $responseObject
     *
     * @throws WrongDataException
     */
    public function __construct(stdClass $responseObject)
    {
        $this->customer = new CustomerResponse($responseObject->customer);
        $this->payload = $responseObject->customerAccessToken->payload;
    }

}
