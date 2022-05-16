<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pay extends My_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->input->post()) {
            $this->load->model('payments_model');
            $this->payments_model->invoice_payment($this->input->post());
        } else {
            $type = 'error';
            $message = lang('please_select_payment_method');
            set_message($type, $message);
            redirect('checkoutPayment');
        }
    }
}
