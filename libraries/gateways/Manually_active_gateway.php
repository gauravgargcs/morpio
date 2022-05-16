<?php

defined('BASEPATH') or exit('No direct script access allowed');


class Manually_Active_gateway extends App_gateway
{

    /**
     * REQUIRED FUNCTION
     * @param  array $data
     * @return mixed
     */
    public function process_payment($data)
    {
        $input_data = (object)$data;
        $result = $this->addSubsPayment($input_data);
        if ($result['type'] == 'success') {
            set_message($result['type'], $result['message']);
        } else {
            set_message($result['type'], $result['message']);
        }
        redirect('admin/dashboard');

    }
}
