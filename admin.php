<?php
/**
 * The administrative interface module.
 * @author Wallet One
 */

$NETCAT_FOLDER = $_SERVER['DOCUMENT_ROOT'];
include_once ($NETCAT_FOLDER.'/vars.inc.php');
include_once($ROOT_FOLDER . 'connect_io.php');
require ($ADMIN_FOLDER . 'function.inc.php');

require_once ($MODULE_FOLDER . 'rbk/admin.inc.php');

require_once ($ADMIN_FOLDER . 'modules/ui.php');
require_once ($MODULE_FOLDER . 'rbk/ui_config.php');
require_once ($MODULE_FOLDER . 'rbk/ru.lang.php');

$rbkAdmin = new RbkAdmin();

if (!$view) {
    $view = 'settings';
}

$title1 = NETCAT_MODULES;
$title2 = 'RBKmoney';

$AJAX_SAVER = !($perm->isGuest() || $view == 'settings');

BeginHtml ($title2, $title1, "http://$DOC_DOMAIN/settings/modules/rbk/", 'rbk');

$perm->ExitIfNotAccess(NC_PERM_MODULE, 0, 0, 0, 1);
$UI_CONFIG = new ui_config_module_rbk($view, '');

// Name methods
$methodShow = $view."_show";
$methodSave = $view."_save";

if (!is_callable([$rbkAdmin, $methodShow]) || !is_callable([$rbkAdmin, $methodSave])) {
    nc_print_status("Incorrect view: ".  htmlspecialchars($view), 'error');
    exit;
}

// Save information
if ($nc_core->input->fetch_get_post('act') === 'save') {
    try {
        $rbkAdmin->$methodSave();
        nc_print_status('Настройки успешно сохранены', 'ok');
    } catch (Exception $exception) {
        nc_print_status($exception->getMessage(), 'error');
    }
}

// Show form method
$rbkAdmin->$methodShow();

EndHtml();
