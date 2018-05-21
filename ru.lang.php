<?php
define('TRANSACTIONS', 'Транзакции');
define('SAVE', 'Сохранить');
define('NEXT', 'Следующая');
define('PAY', 'Оплатить');
define('PREVIOUS', 'Предыдущая');
define('WRONG_VALUE', 'Недопустимое значение');
define('WRONG_SIGNATURE', 'Недопустимая сигнатура');
define('INSTALLATION_SUCCESS', 'Установка модуля прошла успешно');
define('SETTINGS', 'Настройки');
define('RECURRENT', 'Регулярные платежи');
define('RECURRENT_ITEMS', 'Товары для регулярных платежей');
define('API_KEY', 'API ключ');
define('ITEM_IDS', 'Артикулы товаров для рекуррентов по 1 в каждой строке');
define('SHOP_ID', 'ID магазина');
define('SUCCESS_URL', 'Страница успешной оплаты');
define('PAYMENT_TYPE', 'Тип списания средств');
define('SHOW_PARAMETER', 'Да');
define('NOT_SHOW_PARAMETER', 'Нет');
define('CARD_HOLDER', 'Отображение кардхолдера в форме оплаты');
define('HOLD_EXPIRATION', 'Списание средств по окончанию срока холдирования');
define('HOLD_STATUS', 'Статус инвойса при холде');
define('PROCESSED', 'Принят');
define('PAID', 'Оплачен');
define('RBK_MONEY', 'RBKmoney');
define('ERROR_SHOP_ID_IS_NOT_VALID', 'Некорректный параметр `shopID`');
define('ERROR_API_KEY_IS_NOT_VALID', 'Некорректное значение API кюча');
define('ERROR_SUCCESS_URL_IS_NOT_VALID', 'Некорректное значение страницы успешной оплаты');
define('ERROR_PAYMENT_TYPE_IS_NOT_VALID', 'Некорректное значение типа оплаты');
define('ERROR_HOLD_STATUS_IS_NOT_VALID', 'Некорректное значение статуса инвойса при холде');
define('ERROR_AMOUNT_IS_NOT_VALID', 'Некорректное значение суммы платежа');
define('ERROR_HOLD_EXPIRATION_IS_NOT_VALID', 'Некорректное значение стороны списания средств по окончанию срока холдированния');
define('ERROR_TAX_RATE_IS_NOT_VALID', 'Неверно указан НДС в товаре: ');
define('PAYMENT_TYPE_HOLD', 'Холд');
define('PAYMENT_TYPE_INSTANTLY', 'Мгновенное списание');
define('EXPIRATION_PAYER', 'В пользу плательщика');
define('EXPIRATION_SHOP', 'В пользу магазина');
define('RECURRENT_DELETED', 'Привязка успешно удалена');
define('RBK_MONEY_DATE_FILTER', 'Дата');
define('RBK_MONEY_DATE_FILTER_FROM', 'с');
define('RBK_MONEY_DATE_FILTER_TO', 'по');
define('RBK_MONEY_FILTER_SUBMIT', 'Искать');
define('RBK_MONEY_TRANSACTION_ID', 'ID');
define('RBK_MONEY_TRANSACTION_PRODUCT', 'Продукт');
define('RBK_MONEY_TRANSACTION_STATUS', 'Статус');
define('RBK_MONEY_TRANSACTION_AMOUNT', 'Стоимость');
define('RBK_MONEY_TRANSACTION_CREATED_AT', 'Время создания');
define('PAYMENT_CONFIRMED', 'Платеж успешно подтвержден');
define('PAYMENT_CANCELLED', 'Платеж успешно отменен');
define('REFUND_CREATED', 'Возврат платежа успешно создан');
define('PAYMENT_CAPTURE_ERROR', 'Ошибка подтверждения платежа');
define('PAYMENT_CANCELLED_ERROR', 'Ошибка отмены платежа');
define('REFUND_CREATE_ERROR', 'Ошибка создания возврата платежа');
define('USER_FIELD', 'Пользователь');
define('AMOUNT_FIELD', 'Сумма');
define('PRODUCT_FIELD', 'Товар');
define('RECURRENT_CREATE_DATE', 'Дата создания');
define('FORM_BUTTON_DELETE', 'Удалить');
define('FISCALIZATION', 'Фискализация по 54-ФЗ');
define('FISCALIZATION_USE', 'Использовать');
define('FISCALIZATION_NOT_USE', 'Не использовать');
define('ORDER_PAYMENT', 'Оплата заказа');
define('ORDER_PENDING', 'Ваш заказ ожидает оплаты. Перейти на');
define('WEBSITE', 'сайт');
define('STATUS_STARTED', 'Запущен');
define('STATUS_PROCESSED', 'Обработан');
define('STATUS_CAPTURED', 'Подтвержден');
define('STATUS_CANCELLED', 'Отменен');
define('STATUS_CHARGED_BACK', 'Совершен чарджбек');
define('STATUS_REFUNDED', 'Возвращен');
define('STATUS_FAILED', 'Неуспешен');
define('REFUNDED_BY_ADMIN', 'Сделан возврат администратором');
define('CANCELLED_BY_ADMIN', 'Отменено администратором');
define('CAPTURED_BY_ADMIN', 'Подтверждено администратором');
define('SETTINGS_SAVED', 'Настройки успешно сохранены');
define('CONFIRM_PAYMENT', 'Подтвердить платеж');
define('CANCEL_PAYMENT', 'Отменить платеж');
define('CREATE_PAYMENT_REFUND', 'Создать возврат платежа');
define('CUSTOMER_READY', 'Готов');
define('CUSTOMER_UNREADY', 'Не готов');
define('RECURRENT_SUCCESS', 'Платеж успешно проведен: ');
define('SHADING_CVV', 'Затенять карточный cvv код');
define('RECURRENT_PAYMENT', 'Рекуррентный платеж');
define('RBKMONEY_RESPONSE_NOT_RECEIVED', 'Ответ от RBKmoney не получен');
define('REDIRECT_TO_PAYMENT_PAGE', 'Сейчас вы будете перенаправлены на страницу оплаты.');
define('CLICK_BUTTON_PAY', 'Если это не произошло - нажмите на кнопку "Оплатить"');
define('ERROR_MESSAGE_PHP_VERSION', 'Для работы модуля необходима версия PHP 5.5 или выше');
define('ERROR_MESSAGE_CURL', 'Для работы модуля необходим curl');