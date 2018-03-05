<?php

namespace src\Client;

use src\Api\Exceptions\WrongDataException;
use src\Api\Invoices\GetInvoiceById\Response\GetInvoiceByIdResponse;
use src\Api\Invoices\GetInvoiceById\Request\GetInvoiceByIdRequest;
use src\Api\Webhooks\CreateWebhook\Request\CreateWebhookRequest;
use src\Api\Webhooks\CreateWebhook\Response\CreateWebhookResponse;
use src\Api\Webhooks\GetWebhooks\Request\GetWebhooksRequest;
use src\Api\Webhooks\GetWebhooks\Response\GetWebhooksResponse;
use src\Exceptions\RequestException;
use src\Interfaces\ClientInterface;
use src\Api\Invoices\CreateInvoice\Request\CreateInvoiceRequest;
use src\Api\Invoices\CreateInvoice\Response\CreateInvoiceResponse;

class Sender
{

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param CreateInvoiceRequest $request
     *
     * @return CreateInvoiceResponse
     *
     * @throws RequestException
     * @throws WrongDataException
     */
    public function sendCreateInvoiceRequest(CreateInvoiceRequest $request
    ): CreateInvoiceResponse {
        $response = $this->client->sendPostRequest($request);

        return new CreateInvoiceResponse(json_decode($response));
    }

    /**
     * @param GetInvoiceByIdRequest $request
     *
     * @return GetInvoiceByIdResponse
     *
     * @throws RequestException
     * @throws WrongDataException
     */
    public function sendGetInvoiceByIdRequest(GetInvoiceByIdRequest $request
    ): GetInvoiceByIdResponse {
        $response = $this->client->sendRequest($request);

        return new GetInvoiceByIdResponse(json_decode($response));
    }

    /**
     * @param CreateWebhookRequest $request
     *
     * @return CreateWebhookResponse
     *
     * @throws RequestException
     * @throws WrongDataException
     */
    public function sendCreateWebhookRequest(CreateWebhookRequest $request
    ): CreateWebhookResponse {
        $response = $this->client->sendPostRequest($request);

        return new CreateWebhookResponse(json_decode($response));
    }

    /**
     * @param GetWebhooksRequest $request
     *
     * @return GetWebhooksResponse
     *
     * @throws RequestException
     * @throws WrongDataException
     */
    public function sendGetWebhooksRequest(GetWebhooksRequest $request): GetWebhooksResponse
    {
        $response = $this->client->sendRequest($request);

        return new GetWebhooksResponse(json_decode($response));
    }

}
