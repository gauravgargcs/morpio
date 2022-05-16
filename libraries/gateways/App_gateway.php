<?php

defined('BASEPATH') or exit('No direct script access allowed');

class App_gateway
{
    /**
     * Hold Codeigniter instance
     * @var object
     */
    protected $ci;

    /**
     * Stores the gateway id
     * @var alphanumeric
     */
    protected $id = '';

    /**
     * Gateway name
     * @var mixed
     */
    protected $name = '';

    /**
     * All gateway settings
     * @var array
     */
    protected $settings = [];

    /**
     * Must be called from the main gateway class that extends this class
     * @param alphanumeric $id Gateway id - required
     * @param mixed $name Gateway name
     */
    public function __construct()
    {
        $this->ci = &get_instance();
    }

    public function initMode($modes)
    {
        /**
         * Autoload the options defined below
         * Options are used over the system while working and it's necessary to be autoloaded for performance.
         * @var array
         */

        $autoload = [
            'label', 'default_selected', 'active',
        ];

        /**
         * Inject the mode with other modes with action hook
         */
        $modes[] = [
            'id' => $this->getId(),
            'name' => $this->getSetting('label'),
            'description' => '',
            'selected_by_default' => $this->getSetting('default_selected'),
            'active' => $this->getSetting('active'),
        ];

        return $modes;
    }

    /**
     * Set gateway name
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Return gateway name
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set gateway id
     * @param string alphanumeric $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Return gateway id
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Add payment based on payment method
     * @param array $data payment data
     * Params
     * amount - Required
     * invoiceid - Required
     * transactionid - Optional but recommended
     * paymentmethod - Optional
     * note - Optional
     */
    public function addSubsPayment($data)
    {
        $this->ci->load->model('payments_model');
        return $this->ci->payments_model->addSubsPayment($data);
    }

    /**
     * Add payment based on payment method
     * @param array $data payment data
     * Params
     * amount - Required
     * invoiceid - Required
     * transactionid - Optional but recommended
     * paymentmethod - Optional
     * note - Optional
     */
    public function addPayment($invoices_id, $amount)
    {
        $this->ci->load->model('payments_model');
        return $this->ci->payments_model->addPayment($invoices_id, $amount);
    }

    /**
     * Get all gateway settings
     * @param  boolean $formatted Should the setting be formated like is on db or like it passed from the settings
     * @return array
     */
    public function getSettings($formatted = true)
    {
        $settings = $this->settings;
        if ($formatted) {
            foreach ($settings as $key => $option) {
                $settings[$key]['name'] = 'paymentmethod_' . $this->getId() . '_' . $option['name'];
            }
        }

        return $settings;
    }

    /**
     * Return single setting passed by name
     * @param  mixed $name Option name
     * @return string
     */
    public function getSetting($name)
    {
        return trim(get_option('paymentmethod_' . $this->getId() . '_' . $name));
    }

    /**
     * Return single setting passed by name
     * @param  mixed $name Option name
     * @return string
     */
    public function getDescription($subscriptions_id)
    {
        $subscription_info = get_old_data('tbl_subscriptions', array('subscriptions_id' => $subscriptions_id));
        $plan_info = get_old_data('tbl_subscriptions_history', array('status' => $subscription_info->status, 'subscriptions_id' => $subscription_info->subscriptions_id));
        if (!empty($plan_info)) {
            $currency = get_old_data('tbl_currencies', array('code' => $plan_info->currency));
            if ($plan_info->frequency == 'monthly') {
                $frequency = lang('mo');
            } else {
                $frequency = lang('yr');
            }
            $plan_name = '<a data-toggle="modal" data-target="#myModal" href="' . base_url('admin/global_controller/subs_package_details/' . $plan_info->id) . '">' . lang('for') . ' ' . lang('plan') . ' :' . $plan_info->name . ' ' . display_money($plan_info->amount, $currency->symbol) . ' /' . $frequency . ' ' . '</a>';
        } else {
            $plan_name = '-';
        }
        return $plan_name;
    }

    /**
     * Return single setting passed by name
     * @param  mixed $name Option name
     * @return string
     */
    public function getConfigItems($name)
    {
        $config_old_data = get_old_result('tbl_config');
        foreach ($config_old_data as $v_old_config) {
            if ($v_old_config->config_key == $name) {
                return $v_old_config->value;
            }
        }
    }

    /**
     * Decrypt setting value
     * @return string
     */
    public function decryptSetting($name)
    {
        return trim($this->ci->encryption->decrypt($this->getSetting($name)));
    }

    /**
     * Check if payment gateway is initialized and options are added into database
     * @return boolean
     */
    protected function isInitialized()
    {
        return $this->getSetting('initialized') == '' ? false : true;
    }

    /**
     * Check if is settings page in admin area
     * @return boolean
     */
    private function isOptionsPage()
    {
        return $this->ci->input->get('group') == 'online_payment_modes' && $this->ci->uri->segment(2) == 'settings';
    }

    /**
     * @deprecated
     * @return string
     */
    public function get_id()
    {
        return $this->getId();
    }

    /**
     * @deprecated
     * @return array
     */
    public function get_settings()
    {
        return $this->getSettings();
    }

    /**
     * @deprecated
     * @return string
     */
    public function get_name()
    {
        return $this->getName();
    }

    /**
     * @deprecated
     * @return string
     */
    public function get_setting_value($name)
    {
        return $this->getSetting($name);
    }
}
