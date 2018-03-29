<?php
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$modulePath = $_SERVER['DOCUMENT_ROOT'] . '/rbkmoney/';

include_once($_SERVER['DOCUMENT_ROOT'] . '/vars.inc.php');
include_once($ROOT_FOLDER . 'connect_io.php');
include_once($modulePath . "$lang.lang.php");

$db = $nc_core->db;

InstallThisModule($db);

function InstallThisModule($db)
{
    $db->query("INSERT INTO `Module` (`Module_Name`, `Keyword`, `Description`,
      `Parameters`, `Example_URL`, `Help_URL`, `Installed`, `Number`, `Inside_Admin`, `Checked`)
      VALUES ('RBK_MONEY', 'rbkmoney', 'RBK_MONEY', 'ADMIN_SETTINGS_LOCATION=module.rbkmoney.settings', '', '', 1, '', 1, 1)"
    );

    $db->query("INSERT INTO `Classificator_PaymentSystem` (`PaymentSystem_Name`, `PaymentSystem_Priority`, `Value`, `Checked`)
	VALUES ('RBKmoney', '1', 'nc_payment_system_rbkmoney', '1')"
    );

    $db->query("CREATE TABLE `RBKmoney_Recurrent_Items` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `article` VARCHAR (20) NOT NULL,
        PRIMARY KEY (`id`))"
    );

    $db->query("CREATE TABLE `RBKmoney_Recurrent` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `recurrent_customer_id` INT(10) UNSIGNED NOT NULL,
        `amount` INT(11) NOT NULL,
        `name` VARCHAR(250) NOT NULL,
        `message_id` INT(11) NOT NULL,
        `sub_class_id` INT(11) NOT NULL,
        `currency` VARCHAR (5) NOT NULL,
        `vat_rate` VARCHAR(10) NULL,
        PRIMARY KEY (`id`),
        KEY `recurrent_customer` (`recurrent_customer_id`))"
    );

    $db->query("CREATE TABLE `RBKmoney_Recurrent_Customers` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `customer_id` VARCHAR(20) NOT NULL,
        `status` VARCHAR(20) NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `user_id` (`user_id`),
        UNIQUE KEY `customer_id` (`customer_id`))"
    );

    $db->query("CREATE TABLE `RBKmoney_Invoice` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `invoice_id` VARCHAR(100) NOT NULL,
        `payload` TEXT NOT NULL,
        `end_date` DATETIME NOT NULL,
        `order_id` INT(11) NOT NULL,
        PRIMARY KEY (`id`))"
    );

    $db->query("INSERT INTO `CronTasks`
			(`Cron_Minutes`, `Cron_Hours`, `Cron_Days`, `Cron_Months`, `Cron_Weekdays`, `Cron_Script_URL`)
		VALUES
      (0, 0, 0, 0, 0, '/netcat/modules/rbkmoney/recurrentCron.php')"
    );

    $db->query("INSERT INTO `Settings`
			(`Key`, `Value`, `Module`)
		VALUES
      ('apiKey', '', 'rbkmoney'),
      ('shopId', '', 'rbkmoney'),
      ('successUrl', 'http://example.ru', 'rbkmoney')"
    );
    $result["Success"] = 1;
}

echo INSTALLATION_SUCCESS;