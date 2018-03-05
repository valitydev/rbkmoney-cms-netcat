<?php

namespace src\Api\Webhooks;

use src\Api\Exceptions\WrongDataException;

class CustomersTopicScope extends WebhookScope
{

    /**
     * Предмет оповещений
     */
    public const CUSTOMERS_TOPIC = 'CustomersTopic';

    /**
     * Плательщик создан
     */
    public const CUSTOMER_CREATED = 'CustomerCreated';

    /**
     * Плательщик удален
     */
    public const CUSTOMER_DELETED = 'CustomerDeleted';

    /**
     * Плательщик готов
     */
    public const CUSTOMER_READY = 'CustomerReady';

    /**
     * Привязка к плательщику запущена
     */
    public const CUSTOMER_BINDING_STARTED = 'CustomerBindingStarted';

    /**
     * Привязка к плательщику успешно завершена
     */
    public const CUSTOMER_BINDING_SUCCEEDED = 'CustomerBindingSucceeded';

    /**
     * Привязка к плательщику завершена неудачей
     */
    public const CUSTOMER_BINDING_FAILED = 'CustomerBindingFailed';

    /**
     * Допустимые значения типов событий
     */
    private const EVENT_TYPES = [
        self::CUSTOMER_CREATED,
        self::CUSTOMER_DELETED,
        self::CUSTOMER_READY,
        self::CUSTOMER_BINDING_STARTED,
        self::CUSTOMER_BINDING_SUCCEEDED,
        self::CUSTOMER_BINDING_FAILED,
    ];

    /**
     * @param string $shopID
     * @param array  $eventTypes
     *
     * @throws WrongDataException
     */
    public function __construct(string $shopID, array $eventTypes)
    {
        $this->shopID = $shopID;
        $this->topic = self::CUSTOMERS_TOPIC;

        if (!empty(array_diff($eventTypes, self::EVENT_TYPES))) {
            throw new WrongDataException('Недопустимое значение `eventTypes`');
        }

        $this->eventTypes = $eventTypes;
    }

}
