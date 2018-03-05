<?php

namespace src\Api\Webhooks;

use src\Api\RbkDataObject;

abstract class WebhookScope extends RbkDataObject
{
    /**
     * Предмет оповещений
     *
     * @var string
     */
    public $topic;

    /**
     * Идентификатор магазина
     *
     * @var string
     */
    public $shopID;

    /**
     * Набор типов событий, о которых следует оповещать
     *
     * @var array
     */
    public $eventTypes;

}