<?php

namespace src\Client;

use src\Api\Exceptions\WrongRequestException;
use src\Api\Interfaces\GetRequestInterface;
use src\Api\Interfaces\RequestInterface;
use src\Exceptions\RequestException;
use src\Interfaces\ClientInterface;
use src\Api\Interfaces\PostRequestInterface;

class Client implements ClientInterface
{

    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_ACCEPTED = 202;
    const HTTP_NO_CONTENT = 204;
    const SEE_OTHER = 303;

    /**
     * Успешные коды ответов
     */
    private $successCodes = array(
        self::HTTP_OK,
        self::HTTP_CREATED,
        self::HTTP_ACCEPTED,
        self::HTTP_NO_CONTENT,
    );

    const CONTENT_TYPE = 'Content-Type: application/json; charset=utf-8';
    const AUTHORIZATION = 'Authorization: Bearer ';
    const REQUEST_ID = 'X-Request-ID: ';

    private $headers = array();

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
    public function __construct($apiKey, $shopId, $url)
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
    private function setHeaders()
    {
        $this->headers = array(
            self::CONTENT_TYPE,
            self::AUTHORIZATION . $this->apiKey,
            self::REQUEST_ID . $this->shopId,
        );
    }

    /**
     * @param RequestInterface $request
     * @param string           $method
     *
     * @return string
     * @throws RequestException
     * @throws WrongRequestException
     */
    public function sendRequest(RequestInterface $request, $method)
    {
        $params = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $this->headers,
        );

        if (ClientInterface::GET === $method) {
            if (!($request instanceof GetRequestInterface)) {
                throw new WrongRequestException('Недопустимое значение Request');
            }
        } elseif (ClientInterface::POST === $method) {
            if (!($request instanceof PostRequestInterface)) {
                throw new WrongRequestException('Недопустимое значение Request');
            }

            $params[CURLOPT_POSTFIELDS] = json_encode($request->toArray());
        }

        return $this->sendCurl($this->url . $request->getPath(), $params);
    }

    /**
     * @param string $url
     * @param array  $options
     *
     * @return string
     *
     * @throws RequestException
     */
    private function sendCurl($url, array $options)
    {
        $ch = curl_init($url);

        curl_setopt_array($ch, $options);

        $result = curl_exec($ch);

        $responseInfo = curl_getinfo($ch);

        curl_close($ch);

        if (self::SEE_OTHER === $responseInfo['http_code']) {
            return $responseInfo['location'];
        }

        if (false === $result) {
            throw new RequestException('Ответ от RbkMoney не получен');
        } elseif (!in_array($responseInfo['http_code'], $this->successCodes)) {
            throw new RequestException($result, $responseInfo['http_code']);
        }

        return $result;
    }

}
