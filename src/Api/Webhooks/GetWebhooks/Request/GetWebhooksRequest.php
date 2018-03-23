<?php

namespace src\Api\Webhooks\GetWebhooks\Request;

use src\Api\Interfaces\GetRequestInterface;
use src\Api\RbkDataObject;

class GetWebhooksRequest extends RbkDataObject implements GetRequestInterface
{

    const PATH = '/processing/webhooks';

    /**
     * @return string
     */
    public function getPath()
    {
        return self::PATH;
    }

}
