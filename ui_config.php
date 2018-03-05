<?php

/**
 * Class output menu in the admin
 */
class ui_config_module_rbk extends ui_config_module {

  public $headerText = 'RBKmoney';

  /**
   * The output settings in the admin.
   * @param $view
   * @param $params
   */
	function ui_config_module_rbk($view, $params) {
		$this->tabs[] = [
			'id' => 'settings',
			'caption' => 'Настройки',
			'location' => 'module.rbk.settings',
			'group' => 'admin',
		];

		$this->activeTab = $view;
		$this->locationHash = "module.rbk.$view" . ($params ? "($params)" : '');
		$this->treeMode = 'modules';

		$module_settings = nc_Core::get_object()->modules->get_by_keyword('rbk');
		$this->treeSelectedNode = "module-".$module_settings['Module_ID'];
	}
  
  /**
   * 
   */
	public function add_settings_toolbar() {
		$this->activeTab = 'settings';
  }
}


