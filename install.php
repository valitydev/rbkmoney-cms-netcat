<?php
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$modulePath = $_SERVER['DOCUMENT_ROOT'] . '/rbk/';

include_once($_SERVER['DOCUMENT_ROOT'] . '/vars.inc.php');
include_once($ROOT_FOLDER . 'connect_io.php');
include_once($modulePath . "$lang.lang.php");

$db = $nc_core->db;

InstallThisModule($db);

function InstallThisModule($db)
{
    $db->query("INSERT INTO `Module` (`Module_Name`, `Keyword`, `Description`,
      `Parameters`, `Example_URL`, `Help_URL`, `Installed`, `Number`, `Inside_Admin`, `Checked`)
      VALUES ('RBK', 'rbk', 'RBK_DESCRIPTION', 'ADMIN_SETTINGS_LOCATION=module.rbk.settings', '', '', 1, '', 0, 1)");

    $db->query("INSERT INTO `Classificator_PaymentSystem` (`PaymentSystem_Name`, `PaymentSystem_Priority`, `Value`, `Checked`)
	VALUES ('RBK Money', '1', 'nc_payment_system_rbk', '1')");
    $result["Success"] = 1;

    $db->query("INSERT INTO `Settings`
			(`Key`, `Value`, `Module`)
		VALUES
      ('apiKey', '', 'rbk'),
      ('shopId', '', 'rbk'),
      ('failUrl', 'http://example.ru', 'rbk'),
      ('successUrl', 'http://example.ru', 'rbk'),
      ('paymentType', '', 'rbk'),
      ('holdExpiration', '', 'rbk'),
      ('payPublicKey', '', 'rbk'),
      ('cardHolder', '', 'rbk'),");
}

echo 'Установка модуля прошла успешно';