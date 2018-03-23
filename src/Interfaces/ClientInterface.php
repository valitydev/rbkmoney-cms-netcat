<?php

namespace src\Interfaces;

use src\Api\Exceptions\WrongRequestException;
use src\Api\Interfaces\RequestInterface;
use src\Exceptions\RequestException;

interface ClientInterface
{

    const DELETE = 'DELETE';
    const POST = 'POST';
    const GET = 'GET';
    const PUT = 'PUT';

    /**
     * @param RequestInterface $request
     * @param string           $method
     *
     * @return string
     * @throws RequestException
     * @throws WrongRequestException
     */
    public function sendRequest(RequestInterface $request, $method);

}
