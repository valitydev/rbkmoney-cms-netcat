<?php

namespace src\Api\Webhooks\CreateWebhook\Request;

use src\Api\Interfaces\PostRequestInterface;
use src\Api\RbkDataObject;
use src\Api\Webhooks\WebhookScope;

class CreateWebhookRequest extends RbkDataObject implements PostRequestInterface
{

    private const URL = '/processing/webhooks';

    /**
     * Область охвата webhook'а, ограничивающая набор типов
     * событий, по которым следует отправлять оповещения
     *
     * @var WebhookScope
     */
    protected $scope;

    /**
     * URL, на который будут поступать оповещения о произошедших событиях
     *
     * @var string
     */
    protected $url;

    /**
     * @param WebhookScope $scope
     * @param string       $url
     */
    public function __construct(WebhookScope $scope, string $url)
    {
        $this->scope = $scope;
        $this->url = $url;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'scope' => $this->scope,
            'url' => $this->url,
        ];
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return self::URL;
    }

}
