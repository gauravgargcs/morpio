<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Omnipay\Omnipay;

require_once(APPPATH . 'third_party/omnipay/vendor/autoload.php');


class Paypal_gateway extends App_gateway
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
        $this->setId('paypal');

        /**
         * REQUIRED
         * Gateway name
         */
        $this->setName('Paypal');


        /**
         * REQUIRED
         * Hook gateway with other online payment modes
         */
//        add_action('before_add_online_payment_modes', [$this, 'initMode']);
    }

    /**
     * REQUIRED FUNCTION
     * @param  array $data
     * @return mixed
     */
    public function process_payment($data)
    {
        if (getConfigItems('website_name') == '') {
            $company_name = getConfigItems('company_name');
        } else {
            $company_name = getConfigItems('website_name');
        }
        if (getConfigItems('paypal_live') == 'TRUE') {
            $mode = '';
        } else {
            $mode = 'TRUE';
        }
        // Process online for PayPal payment start
        $gateway = Omnipay::create('PayPal_Express');
        $gateway->setUsername(getConfigItems('paypal_api_username'));
        $gateway->setPassword(decrypt(getConfigItems('paypal_api_password')));
        $gateway->setSignature(getConfigItems('api_signature'));
        $gateway->setTestMode($mode);
        $gateway->setlogoImageUrl(base_url(getConfigItems('company_logo')));
        $gateway->setbrandName($company_name);

        $request_data = [
            'amount' => number_format($data['total'], 2, '.', ''),
            'returnUrl' => base_url('payment/paypal/complete_subs_payment/' . $data['subscriptions_id']),
            'cancelUrl' => site_url('checkoutPayment'),
            'currency' => $data['currency'],
            'description' => getDescription($data, true),
        ];
        try {
            $response = $gateway->purchase($request_data)->send();
            if ($response->isRedirect()) {
                $this->ci->session->set_userdata([
                    'input_info' => $data,
                    'reference_no' => $response->getTransactionReference(),
                ]);
                $response->redirect();
            } else {
                exit($response->getMessage());
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . '<br />';
            exit('Sorry, there was an error processing your payment. Please try again later.');
        }
    }

    /**
     * Custom function to complete the payment after user is returned from paypal
     * @param  array $data
     * @return mixed
     */
    public function complete_purchase($data)
    {
        if (getConfigItems('paypal_live') == 'TRUE') {
            $mode = '';
        } else {
            $mode = 'TRUE';
        }
        $gateway = Omnipay::create('PayPal_Express');
        $gateway->setUsername(getConfigItems('paypal_api_username'));
        $gateway->setPassword(decrypt(getConfigItems('paypal_api_password')));
        $gateway->setSignature(getConfigItems('api_signature'));
        $gateway->setTestMode($mode);

        $response = $gateway->completePurchase([
            'transactionReference' => $data['token'],
            'payerId' => $this->ci->input->get('PayerID'),
            'amount' => number_format($data['amount'],2),
            'currency' => $data['currency'],
        ])->send();
		
        $paypalResponse = $response->getData();
        return $paypalResponse;
    }

    /**
     * REQUIRED FUNCTION
     * @param  array $data
     * @return mixed
     */
    public function invoice_payment($data)
    {
        if (config_item('website_name') == '') {
            $company_name = config_item('company_name');
        } else {
            $company_name = config_item('website_name');
        }
        if (config_item('paypal_live') == 'TRUE') {
            $mode = '';
        } else {
            $mode = 'TRUE';
        }
        // Process online for PayPal payment start
        $gateway = Omnipay::create('PayPal_Express');
        $gateway->setUsername(config_item('paypal_api_username'));
        $gateway->setPassword(decrypt(config_item('paypal_api_password')));
        $gateway->setSignature(config_item('api_signature'));
        $gateway->setTestMode($mode);
        $gateway->setlogoImageUrl(base_url(config_item('company_logo')));
        $gateway->setbrandName($company_name);

        $request_data = [
            'amount' => number_format($data['amount'], 2, '.', ''),
            'returnUrl' => base_url('payment/paypal/complete_payment/' . $data['invoices_id']),
            'cancelUrl' => $_SERVER["HTTP_REFERER"],
            'currency' => $data['currency'],
            'description' => $data['description'],
        ];
        try {
            $response = $gateway->purchase($request_data)->send();
            if ($response->isRedirect()) {
                $this->ci->session->set_userdata([
                    'input_info' => $data,
                    'reference_no' => $response->getTransactionReference(),
                ]);
                $response->redirect();
            } else {
                exit($response->getMessage());
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . '<br />';
            exit('Sorry, there was an error processing your payment. Please try again later.');
        }
    }

    /**
     * Custom function to complete the payment after user is returned from paypal
     * @param  array $data
     * @return mixed
     */
    public function invoice_purchase($data)
    {
        $gateway = Omnipay::create('PayPal_Express');
        $gateway->setUsername($this->decryptSetting('username'));
        $gateway->setPassword($this->decryptSetting('password'));
        $gateway->setSignature($this->decryptSetting('signature'));
        $gateway->setTestMode($this->getSetting('test_mode_enabled'));

        $response = $gateway->completePurchase([
            'transactionReference' => $data['token'],
            'payerId' => $this->ci->input->get('PayerID'),
            'amount' => number_format($data['amount'],2),
            'currency' => $data['currency'],
        ])->send();

        $paypalResponse = $response->getData();

        return $paypalResponse;
    }
}
