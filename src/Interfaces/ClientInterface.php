<?php

namespace src\Interfaces;

use src\Api\Interfaces\RequestInterface;
use src\Api\Interfaces\PostRequestInterface;
use src\Exceptions\RequestException;

interface ClientInterface
{

    /**
     * @param PostRequestInterface $request
     * @param string               $method
     *
     * @return string
     *
     * @throws RequestException
     */
    public function sendPostRequest(PostRequestInterface $request, string $method = 'POST'): string;

    /**
     * @param RequestInterface $request
     * @param string           $method
     *
     * @return string
     *
     * @throws RequestException
     */
    public function sendRequest(RequestInterface $request, string $method = 'GET'): string;

}
