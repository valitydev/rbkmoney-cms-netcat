<?php

namespace src\Api\Payments\RefundResponse;

use DateTime;
use src\Api\Error;
use src\Api\Exceptions\WrongDataException;
use src\Api\RbkDataObject;
use src\Helpers\ResponseHandler;
use stdClass;

/**
 * Объект ответа на запрос возврата указанного платежа
 */
class RefundResponse extends RbkDataObject
{

    /**
     * Идентификатор возврата
     *
     * @var string
     */
    protected $id;

    /**
     * Дата и время осуществления
     *
     * @var DateTime
     */
    protected $createdAt;

    /**
     * Причина осуществления возврата
     *
     * @var string | null
     */
    protected $reason;

    /**
     * @var Status
     */
    protected $status;

    /**
     * @var Error | null
     */
    protected $error;

    /**
     * @param stdClass $responseObject
     *
     * @throws WrongDataException
     */
    public function __construct(stdClass $responseObject)
    {
        $this->id = $responseObject->id;
        $this->createdAt = new DateTime($responseObject->createdAt);
        $this->status = new Status($responseObject->status);

        if (property_exists($responseObject, 'reason')) {
            $this->reason = $responseObject->reason;
        }

        if (property_exists($responseObject, 'error')) {
            $this->error = ResponseHandler::getError($responseObject->error);
        }
    }

}
