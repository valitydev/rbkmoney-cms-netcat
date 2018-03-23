<?php

namespace src\Api\Webhooks;

use src\Api\RbkDataObject;

class WebhookScope extends RbkDataObject
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

    /**
     * @return array
     */
    public function toArray()
    {
        $properties = array();

        foreach ($this as $property => $value) {
            if (!empty($value)) {
                $properties[$property] = $value;
            }
        }

        return $properties;
    }

}