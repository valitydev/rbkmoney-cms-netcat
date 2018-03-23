<?php

namespace src\Api\Tokens\CreatePaymentResource\Response;

use src\Api\Exceptions\WrongDataException;
use src\Api\Interfaces\ResponseInterface;
use src\Api\Payments\PaymentResponse\ClientInfo;
use src\Api\Payments\PaymentResponse\PaymentToolDetails;
use src\Api\RbkDataObject;
use src\Helpers\ResponseHandler;
use stdClass;

class CreatePaymentResourceResponse extends RbkDataObject implements ResponseInterface
{

    /**
     * Токен платежного средства, предоставленного плательщиком
     *
     * @var string
     */
    public $paymentToolToken;

    /**
     * Идентификатор платежной сессии
     *
     * @var string
     */
    public $paymentSession;

    /**
     * Детали платежного средства
     *
     * @var PaymentToolDetails | null
     */
    public $paymentToolDetails;

    /**
     * Данные клиентского устройства плательщика
     *
     * @var ClientInfo | null
     */
    public $clientInfo;

    /**
     * @param stdClass $token
     *
     * @throws WrongDataException
     */
    public function __construct(stdClass $token)
    {
        $this->paymentToolToken = $token->paymentToolToken;
        $this->paymentSession = $token->paymentSession;

        if (property_exists($token, 'paymentTool')) {
            $this->paymentToolDetails = ResponseHandler::getPaymentToolDetails($token->paymentTool);
        }

        if (property_exists($token, 'clientInfo')) {
            $this->paymentToolDetails = ResponseHandler::getClientInfo($token->clientInfo);
        }
    }

}
