<?php

namespace src\Api\Webhooks\GetWebhooks\Request;

use src\Api\Interfaces\RequestInterface;
use src\Api\RbkDataObject;

class GetWebhooksRequest extends RbkDataObject implements RequestInterface
{

    private const URL = '/processing/webhooks';

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return self::URL;
    }

}
