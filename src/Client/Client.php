<?php

namespace src\Client;

use src\Api\Interfaces\RequestInterface;
use src\Exceptions\RequestException;
use src\Interfaces\ClientInterface;
use src\Api\Interfaces\PostRequestInterface;

class Client implements ClientInterface
{

    private const HTTP_OK = 200;
    private const HTTP_CREATED = 201;
    private const HTTP_ACCEPTED = 202;
    private const HTTP_NO_CONTENT = 204;

    /**
     * Успешные коды ответов
     */
    private const SUCCESS_CODES = [
        self::HTTP_OK,
        self::HTTP_CREATED,
        self::HTTP_ACCEPTED,
        self::HTTP_NO_CONTENT,
    ];

    private const CONTENT_TYPE = 'Content-Type: application/json; charset=utf-8';
    private const AUTHORIZATION = 'Authorization: Bearer ';
    private const REQUEST_ID = 'X-Request-ID: ';

    private $headers = [];

    /**
     * Приватный ключ для доступа к API
     *
     * @var string
     */
    private $apiKey;

    /**
     * Id магазина
     *
     * @var string
     */
    private $shopId;

    /**
     * @var string
     */
    private $url;

    /**
     * @param string $apiKey
     * @param string $shopId
     * @param string $url
     */
    public function __construct(string $apiKey, string $shopId, string $url)
    {
        $this->apiKey = $apiKey;
        $this->shopId = $shopId;
        $this->url = $url;

        $this->setHeaders();
    }

    /**
     * Устанавливает хедеры
     *
     * @return void
     */
    private function setHeaders(): void
    {
        $this->headers = [
            self::CONTENT_TYPE,
            self::AUTHORIZATION . $this->apiKey,
            self::REQUEST_ID . $this->shopId,
        ];
    }

    /**
     * @param PostRequestInterface $request
     * @param string               $method
     *
     * @return string
     * @throws RequestException
     */
    public function sendPostRequest(PostRequestInterface $request, string $method = 'POST'): string
    {
        return $this->sendCurl($this->url . $request->getUrl(), [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $this->headers,
            CURLOPT_POSTFIELDS => json_encode($request->toArray()),
        ]);
    }

    /**
     * @param RequestInterface $request
     * @param string           $method
     *
     * @return string
     *
     * @throws RequestException
     */
    public function sendRequest(RequestInterface $request, string $method = 'GET'): string
    {
        return $this->sendCurl($this->url . $request->getUrl(), [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $this->headers,
        ]);
    }

    /**
     * @param string $url
     * @param array  $options
     *
     * @return string
     *
     * @throws RequestException
     */
    private function sendCurl(string $url, array $options): string
    {
        $ch = curl_init($url);

        curl_setopt_array($ch, $options);

        $result = curl_exec($ch);

        $responseInfo = curl_getinfo($ch);

        curl_close($ch);

        if (!in_array($responseInfo['http_code'], self::SUCCESS_CODES)) {
            throw new RequestException($result, $responseInfo['http_code']);
        } elseif (false === $result) {
            throw new RequestException('Ответ от RbkMoney не получен');
        }

        return $result;
    }

}
