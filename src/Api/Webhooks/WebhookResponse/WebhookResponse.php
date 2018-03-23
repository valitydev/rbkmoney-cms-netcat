<?php

namespace src\Api\Webhooks\WebhookResponse;

use src\Api\Exceptions\WrongDataException;
use src\Api\Interfaces\ResponseInterface;
use src\Api\RbkDataObject;
use src\Api\Webhooks\CustomersTopicScope;
use src\Api\Webhooks\InvoicesTopicScope;
use src\Api\Webhooks\WebhookScope;
use stdClass;

/**
 * Родительский объект ответов с информацией о вебхуках
 */
class WebhookResponse extends RbkDataObject implements ResponseInterface
{

    /**
     * Идентификатор webhook'а
     *
     * @var string | null
     */
    public $id;

    /**
     * Включена ли в данный момент доставка оповещений?
     *
     * @var bool | null
     */
    public $active;

    /**
     * Область охвата webhook'а, ограничивающая набор типов
     * событий, по которым следует отправлять оповещения
     *
     * @var WebhookScope
     */
    public $scope;

    /**
     * URL, на который будут поступать оповещения о произошедших событиях
     *
     * @var string
     */
    public $url;

    /**
     * Содержимое публичного ключа, служащего для проверки авторитативности приходящих на url оповещений
     *
     * @var string | null
     */
    public $publicKey;

    /**
     * @param stdClass $responseObject
     *
     * @throws WrongDataException
     */
    public function __construct(stdClass $responseObject)
    {
        $this->url = $responseObject->url;
        $this->scope = $this->getScope($responseObject->scope);

        if (property_exists($responseObject, 'id')) {
            $this->id = $responseObject->id;
        }

        if (property_exists($responseObject, 'active')) {
            $this->active = $responseObject->active;
        }

        if (property_exists($responseObject, 'publicKey')) {
            $this->publicKey = $responseObject->publicKey;
        }
    }

    /**
     * @param stdClass $scope
     *
     * @return WebhookScope
     *
     * @throws WrongDataException
     */
    private function getScope(stdClass $scope)
    {
        if (InvoicesTopicScope::INVOICES_TOPIC === $scope->topic) {
            return new InvoicesTopicScope($scope->shopID, $scope->eventTypes);
        } elseif (CustomersTopicScope::CUSTOMERS_TOPIC === $scope->topic) {
            return new CustomersTopicScope($scope->shopID, $scope->eventTypes);
        }

        throw new WrongDataException('Недопустимое значение `topic`');
    }

}
