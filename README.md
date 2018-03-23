1. Скачайте и установите NetCat на сервер (https://netcat.ru/democentre).
2. Создайте папку rbkmoney в /netcat/modules и переместите туда папку src и следующие файлы: admin.inc.php, admin.php,
   customers.php, en.lang.php, function.inc.php, index.php, install.php, page_order_waiting.php, page_recurrent.php,
   page_recurrent_items.php, page_settings.php, page_transactions.php, recurrentCron.php, ru.lang.php, settings.php,
   settings_table.php, ui_config.php, url_routes.js
3. Переместите в папку /netcat/modules/payment/classes/system файл rbkmoney.php
4. Зайдите на страницу по адресу http://ваш_сайт/netcat/modules/rbkmoney/install.php
5. После установки модуля нужно заполнить необходимые поля для его корректной работы.
   Сделать это нужно через админку в настройках модуля (http://ваш_сайт/netcat/admin) Настройки­>Управление модулями, знак шестеренки напротив названия модуля
   После заполнения полей нажмите кнопку сохранить в конце страницы
6. Перейдите в настройки модуля Интернет-магазин (Настройки­>Интернет­магазин), в меню слева выберите пункт Настройки->Оплата.
   Добавьте новый способ оплаты с указанием платежной системы RBKmoney (кнопка "Добавить" внизу страницы).

Настройка окончена, теперь при оплате товаров среди способов оплаты будет выводится RBKmoney