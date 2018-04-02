<?php

namespace src\Api\Webhooks\GetWebhooks\Request;

use src\Api\Interfaces\GetRequestInterface;
use src\Api\RBKMoneyDataObject;

class GetWebhooksRequest extends RBKMoneyDataObject implements GetRequestInterface
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
