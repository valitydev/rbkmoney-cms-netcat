<?php

/**
 * Class output menu in the admin
 */
class ui_config_module_rbkmoney extends ui_config_module {

    public $headerText = RBK_MONEY;

    /**
     * The output settings in the admin.
     * @param $view
     * @param $params
     */
    function ui_config_module_rbkmoney($view, $params)
    {
		$this->tabs[] = [
            'id' => 'settings',
            'caption' => SETTINGS,
            'location' => 'module.rbkmoney.settings',
            'group' => 'admin',
        ];
        $this->tabs[] = [
            'id' => 'transactions',
            'caption' => TRANSACTIONS,
            'location' => 'module.rbkmoney.transactions',
            'group' => 'admin',
        ];
        $this->tabs[] = [
            'id' => 'recurrent',
            'caption' => RECURRENT,
            'location' => 'module.rbkmoney.recurrent',
            'group' => 'admin',
        ];
        $this->tabs[] = [
            'id' => 'recurrent_items',
            'caption' => RECURRENT_ITEMS,
            'location' => 'module.rbkmoney.recurrent_items',
            'group' => 'admin',
        ];
        $this->tabs[] = [
            'id' => 'logs',
            'caption' => RBK_MONEY_LOGS,
            'location' => 'module.rbkmoney.logs',
            'group' => 'admin',
        ];

		$this->activeTab = $view;
		$this->locationHash = "module.rbkmoney.$view" . ($params ? "($params)" : '');
		$this->treeMode = 'modules';

		$module_settings = nc_Core::get_object()->modules->get_by_keyword('rbkmoney');
		$this->treeSelectedNode = "module-".$module_settings['Module_ID'];
	}

	public function add_settings_toolbar()
    {
		$this->activeTab = 'settings';
    }

}
