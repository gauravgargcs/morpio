<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Omnipay\Omnipay;

require_once(APPPATH . 'third_party/omnipay/vendor/autoload.php');

class Stripe_gateway extends App_gateway
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
        $this->setId('stripe');

        /**
         * REQUIRED
         * Gateway name
         */
        $this->setName('Stripe Checkout');


        /**
         * REQUIRED
         * Hook gateway with other online payment modes
         */
//        add_action('before_add_online_payment_modes', [ $this, 'initMode' ]);
    }

    public function process_payment($data)
    {
        $data['title'] = lang('process_payment_by') . lang($data['payment_method']);
      
        $this->ci->session->set_userdata([
            'input_info' => $data,
        ]);
        redirect(site_url('payment/stripe/make_subs_payment'));
    }

    public function finish_payment($data)
    {
        $this->ci->load->library('stripe_core');
        $input_data = $data['input_data'];
        $sub_info = get_old_data('tbl_subscriptions', array('subscriptions_id' => $input_data->subscriptions_id));

        $metadata = array(
            'subscriptions_id' => $input_data->subscriptions_id,
            'pricing_id' => $input_data->pricing_id,
            'billing_cycle' => $input_data->billing_cycle,
            'name' => $sub_info->name,
            'email' => $sub_info->email,
        );

        $result = $this->ci->stripe_core->charge([
            'amount' => $input_data->total * 100,
            'currency' => $input_data->currency,
            "card" => $_POST['stripeToken'],
            'metadata' => $metadata,
            'description' => getDescription($data['input_data'], true),
        ]);

        return $result;
    }

    public function finish_invoice_payment($data)
    {
        $this->ci->load->library('stripe_core');
        $invoice_info = get_row('tbl_invoices', array('invoices_id' => $data['invoice_id']));

        $metadata = array(
            'invoice_id' => $data['invoice_id'],
            'amount' => $data['amount'],
        );

        $result = $this->ci->stripe_core->charge([
            'amount' => $data['amount'] * 100,
            'currency' => $invoice_info->currency,
            "card" => $_POST['stripeToken'],
            'metadata' => $metadata,
            'description' => 'Invoice ' . $invoice_info->reference_no . ' via Stripe ',
        ]);
        return $result;
    }
}
