<?php

namespace src\Api\Search\SearchPayments\Response;

use src\Api\Exceptions\WrongDataException;
use src\Api\Interfaces\ResponseInterface;
use src\Api\RbkDataObject;
use stdClass;

class SearchPaymentsResponse extends RbkDataObject implements ResponseInterface
{

    /**
     * @var int | null
     */
    public $totalCount;

    /**
     * @var array | Payment[] | null
     */
    public $result = array();

    /**
     * @param stdClass $response
     *
     * @throws WrongDataException
     */
    public function __construct(stdClass $response)
    {
        if (property_exists($response, 'totalCount')) {
            $this->totalCount = $response->totalCount;
        }

        if (property_exists($response, 'result')) {
            foreach ($response->result as $result) {
                $this->result[] = new Payment($result);
            }
        }
    }

}
