<?php

namespace src\Api\Webhooks;

use src\Api\Exceptions\WrongDataException;

class InvoicesTopicScope extends WebhookScope
{

    /**
     * Предмет оповещений
     */
    public const INVOICES_TOPIC = 'InvoicesTopic';

    /**
     * Создан новый инвойс
     */
    public const INVOICE_CREATED = 'InvoiceCreated';

    /**
     * Инвойс перешел в состояние "Оплачен"
     */
    public const INVOICE_PAID = 'InvoicePaid';

    /**
     * Инвойс отменен по истечению срока давности
     */
    public const INVOICE_CANCELLED = 'InvoiceCancelled';

    /**
     * Инвойс успешно погашен
     */
    public const INVOICE_FULFILLED = 'InvoiceFulfilled';

    /**
     * Создан платеж
     */
    public const PAYMENT_STARTED = 'PaymentStarted';

    /**
     * Платеж в обработке
     */
    public const PAYMENT_PROCESSED = 'PaymentProcessed';

    /**
     * Платеж успешно завершен
     */
    public const PAYMENT_CAPTURED = 'PaymentCaptured';

    /**
     * Платеж успешно отменен
     */
    public const PAYMENT_CANCELLED = 'PaymentCancelled';

    /**
     * Платеж успешно возвращен
     */
    public const PAYMENT_REFUNDED = 'PaymentRefunded';

    /**
     * При проведении платежа возникла ошибка
     */
    public const PAYMENT_FAILED = 'PaymentFailed';

    /**
     * Допустимые значения типов событий
     */
    private const EVENT_TYPES = [
        self::INVOICE_CREATED,
        self::INVOICE_PAID,
        self::INVOICE_CANCELLED,
        self::INVOICE_FULFILLED,
        self::PAYMENT_STARTED,
        self::PAYMENT_PROCESSED,
        self::PAYMENT_CAPTURED,
        self::PAYMENT_CANCELLED,
        self::PAYMENT_REFUNDED,
        self::PAYMENT_FAILED,
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
        $this->topic = self::INVOICES_TOPIC;

        if (!empty(array_diff($eventTypes, self::EVENT_TYPES))) {
            throw new WrongDataException('Недопустимое значение `eventTypes`');
        }

        $this->eventTypes = $eventTypes;
    }

}
