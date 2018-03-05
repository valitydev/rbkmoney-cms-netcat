<?php

use src\Api\Invoices\CreateInvoice\TaxMode;

class RbkAdmin
{
    protected $modulePath;
    protected $moduleFolder;

    protected $settings;

    protected $form;

    public function __construct()
    {
        $nc_core = nc_Core::get_object();

        $this->moduleFolder = $nc_core->MODULE_FOLDER;
        $this->modulePath = str_replace($nc_core->DOCUMENT_ROOT, '', $nc_core->MODULE_FOLDER) . 'rbk/';

        include dirname(__DIR__) . '/rbk/src/autoload.php';

        $this->settings = $nc_core->get_settings('', 'rbk');

        $this->getForm();
    }

    /**
     * Save tad "Insformation".
     *
     * @return boolean
     */
    public function info_save()
    {
        return true;
    }

    /**
     * Tab "General settings".
     *
     * @global $UI_CONFIG
     */
    public function settings_show()
    {
        global $UI_CONFIG;
        $UI_CONFIG->add_settings_toolbar();
        $this->getForm();
        require_once($this->moduleFolder . 'rbk/page_settings.php');
    }

    protected function getForm()
    {
        $nc_core = nc_Core::get_object();

        $save = false;
        if (!empty($nc_core->input->fetch_get_post('act')) && $nc_core->input->fetch_get_post('act') == 'save') {
            $save = true;
        }
        $this->form = [
            'apiKey' => [
                'label' => API_KEY,
                'type' => 'input',
                'value' => ($save ? $nc_core->input->fetch_get_post('apiKey')
                    : $this->settings['apiKey']),
                'placeholder' => API_KEY
            ],
            'shopId' => [
                'label' => SHOP_ID,
                'type' => 'input',
                'value' => ($save ? $nc_core->input->fetch_get_post('shopId')
                    : $this->settings['shopId']),
                'placeholder' => SHOP_ID
            ],
            'failUrl' => [
                'label' => FAIL_URL,
                'type' => 'input',
                'value' => ($save ? $nc_core->input->fetch_get_post('failUrl')
                    : $this->settings['failUrl']),
                'placeholder' => FAIL_URL
            ],
            'successUrl' => [
                'label' => SUCCESS_URL,
                'type' => 'input',
                'value' => ($save ? $nc_core->input->fetch_get_post('successUrl')
                    : $this->settings['successUrl']),
                'placeholder' => SUCCESS_URL
            ],
            'paymentType' => [
                'label' => PAYMENT_TYPE,
                'type' => 'select',
                'value' => ($save ? $nc_core->input->fetch_get_post('paymentType')
                    : $this->settings['paymentType']),
                'options' => [PAYMENT_TYPE_HOLD, PAYMENT_TYPE_INSTANTLY],
                'placeholder' => PAYMENT_TYPE
            ],
            'holdExpiration' => [
                'label' => HOLD_EXPIRATION,
                'type' => 'select',
                'value' => ($save ? $nc_core->input->fetch_get_post('holdExpiration')
                    : $this->settings['holdExpiration']),
                'options' => [EXPIRATION_PAYER, EXPIRATION_SHOP],
                'placeholder' => HOLD_EXPIRATION
            ],
            'cardHolder' => [
                'label' => CARD_HOLDER,
                'type' => 'select',
                'value' => ($save ? $nc_core->input->fetch_get_post('cardHolder')
                    : $this->settings['cardHolder']),
                'options' => [true, false],
                'placeholder' => CARD_HOLDER
            ],
            'taxRate' => [
                'label' => TAX_RATE,
                'type' => 'select',
                'value' => ($save ? $nc_core->input->fetch_get_post('taxRate')
                    : $this->settings['taxRate']),
                'options' => TaxMode::VALID_VALUES,
                'placeholder' => TAX_RATE
            ],
        ];
    }

    /**
     * Save tab "General settings".
     */
    public function settings_save()
    {
        $nc_core = nc_core::get_object();
        foreach ($this->form as $key => $v) {
            $val = $nc_core->input->fetch_get_post($key);
            if (!is_array($val)) {
                $nc_core->set_settings($key, $val, 'rbk');
            } else {
                $nc_core->set_settings($key, serialize($val), 'rbk');
            }
        }

        $this->settings = $nc_core->get_settings('', 'rbk');
    }

}
