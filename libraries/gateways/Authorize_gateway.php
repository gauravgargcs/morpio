<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Omnipay\Omnipay;

require_once(APPPATH . 'third_party/omnipay/vendor/autoload.php');

class Authorize_gateway extends App_gateway
{
    public function __construct()
    {
        /**
         * Call App_gateway __construct function
         */
        parent::__construct();
        /**
         * REQUIRED
         * Gateway unique id
         * The ID must be alpha/alphanumeric
         */
        $this->setId('authorize_aim');

        /**
         * REQUIRED
         * Gateway name
         */
        $this->setName('Authorize.net AIM');


        /**
         * REQUIRED
         * Hook gateway with other online payment modes
         */
//        add_action('before_add_online_payment_modes', [ $this, 'initMode' ]);

//        add_action('before_render_payment_gateway_settings', 'authorize_aim_notice');
    }

    public function process_payment($data)
    {
        $data['title'] = lang('process_payment_by') . lang($data['payment_method']);
        $this->ci->session->set_userdata([
            'input_info' => $data,
        ]);
        redirect(site_url('payment/authorize/make_subs_payment'));
    }

    public function finish_payment($data)
    {
        $input_data = $data['input_data'];
        if (getConfigItems('aim_authorize_live') == 'TRUE') {
            $mode = 'FALSE';
        } else {
            $mode = 'TRUE';
        }
        $gateway = Omnipay::create('AuthorizeNet_AIM');
        $gateway->setApiLoginId(getConfigItems('aim_api_login_id'));
        $gateway->setTransactionKey(getConfigItems('aim_authorize_transaction_key'));
        $gateway->setTestMode($mode);
        $billing_data = [];

        $billing_data['billingCompany'] = config_item('company_name');
        $billing_data['billingAddress1'] = $this->ci->input->post('billingAddress1');
        $billing_data['billingName'] = $this->ci->input->post('billingName');
        $billing_data['billingCity'] = $this->ci->input->post('billingCity');
        $billing_data['billingState'] = $this->ci->input->post('billingState');
        $billing_data['billingPostcode'] = $this->ci->input->post('billingPostcode');
        $billing_data['billingCountry'] = $this->ci->input->post('billingCountry');

        $billing_data['number'] = $this->ci->input->post('ccNo');
        $billing_data['expiryMonth'] = $this->ci->input->post('expMonth');
        $billing_data['expiryYear'] = $this->ci->input->post('expYear');
        $billing_data['cvv'] = $this->ci->input->post('cvv');

        $requestData = [
            'amount' => number_format($input_data->total, 2, '.', ''),
            'currency' => $input_data->currency,
            'description' => getDescription($data['input_data'], true),
            'transactionId' => $input_data->subscriptions_id,
            'invoiceNumber' => $input_data->subscriptions_id . ' ' . $input_data->pricing_id,
            'card' => $billing_data,
        ];
        $oResponse = $gateway->purchase($requestData)->send();
        return $oResponse;
    }

    public function finish_invoice_payment($data)
    {
        if (config_item('aim_authorize_live') == 'TRUE') {
            $mode = 'FALSE';
        } else {
            $mode = 'TRUE';
        }
        $gateway = Omnipay::create('AuthorizeNet_AIM');
        $gateway->setApiLoginId(config_item('aim_api_login_id'));
        $gateway->setTransactionKey(config_item('aim_authorize_transaction_key'));
        $gateway->setTestMode($mode);
        $billing_data = [];

        $billing_data['billingCompany'] = config_item('company_name');
        $billing_data['billingAddress1'] = $this->ci->input->post('billingAddress1');
        $billing_data['billingName'] = $this->ci->input->post('billingName');
        $billing_data['billingCity'] = $this->ci->input->post('billingCity');
        $billing_data['billingState'] = $this->ci->input->post('billingState');
        $billing_data['billingPostcode'] = $this->ci->input->post('billingPostcode');
        $billing_data['billingCountry'] = $this->ci->input->post('billingCountry');

        $billing_data['number'] = $this->ci->input->post('ccNo');
        $billing_data['expiryMonth'] = $this->ci->input->post('expMonth');
        $billing_data['expiryYear'] = $this->ci->input->post('expYear');
        $billing_data['cvv'] = $this->ci->input->post('cvv');

        $requestData = [
            'amount' => number_format($data['amount'], 2, '.', ''),
            'currency' => $data['currency'],
            'description' => 'Invoice Payment by Authorize.net' . $data['amount'],
            'transactionId' => $data['invoice_id'],
            'invoiceNumber' => $data['ref'],
            'card' => $billing_data,
        ];
        $oResponse = $gateway->purchase($requestData)->send();
        return $oResponse;
    }
}

