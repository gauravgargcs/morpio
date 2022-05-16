
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4>

            <?php $this->load->view('admin/skote_layouts/title'); ?>
            

        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="float-end pull-right">
                    <?php
                    if (empty($invoice_info)) {
                        redirect('admin/invoice/manage_invoice');
                    }
                    if ($this->session->userdata('user_type') == '1') {
                        ?>
                        <a style="margin-top: -5px;" href="<?= base_url() ?>admin/invoice/manage_invoice/create_invoice"
                           data-original-title="<?= lang('new_invoice') ?>" data-bs-toggle="tooltip" data-bs-placement="top"
                           class="btn btn-icon btn-<?= config_item('button_color') ?> btn-sm pull-right"><i
                                class="fa fa-plus"></i></a>
                    <?php } ?>
                </div>
                <h4 class="card-title mb-4"><?= lang('all_invoices') ?></h4>                
        
                <div data-simplebar style="max-height: 550px;">  
                    <ul class="nav flex-column" role="tablist" aria-orientation="vertical">
                        <?php
                            if (!empty($all_invoices_info)) {
                                $all_invoices_info = array_reverse($all_invoices_info);
                                foreach ($all_invoices_info as $v_invoices) {
                                    if ($this->invoice_model->get_payment_status($v_invoices->invoices_id) == lang('fully_paid')) {
                                        $invoice_status = lang('fully_paid');
                                        $label = "success";
                                    } elseif ($v_invoices->emailed == 'Yes') {
                                        $invoice_status = lang('sent');
                                        $label = "info";
                                    } else {
                                        $invoice_status = lang('draft');
                                        $label = "secondary";
                                    }
                                    $client_info = $this->invoice_model->check_by(array('client_id' => $v_invoices->client_id), 'tbl_client');
                                    if (!empty($client_info)) {
                                        $client_name = $client_info->name;
                                    } else {
                                        $client_name = '-';
                                    }
                                    ?>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($v_invoices->invoices_id == $this->uri->segment(5)) { echo "active";  } ?>" href="<?= base_url() ?>admin/invoice/manage_invoice/payment/<?= $v_invoices->invoices_id ?>">
                                <?= $client_name ?>
                                <div class="pull-right">
                                    <?php $currency = $this->invoice_model->client_currency_sambol($v_invoices->client_id);
                                    if (empty($currency)) {
                                        $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                                    }
                                    ?>
                                    <?= display_money($this->invoice_model->get_invoice_cost($v_invoices->invoices_id), $currency->symbol); ?>
                                </div>
                                <br>
                                <small class="block small text-muted"><?= $v_invoices->reference_no ?> <span
                                        class="badge badge-soft-<?= $label ?>"><?= $invoice_status ?></span>
                                </small>
                            </a>
                        </li>
                        <?php }  }  ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="card">
            <div class="card-body">
                <?= message_box('error'); ?>
                <!-- Start create invoice -->
                <h4 class="card-title mb-4"><?= lang('pay_invoice') ?></h4>

                <form method="post" data-parsley-validate="" novalidate=""
                      action="<?= base_url() ?>admin/invoice/get_payemnt/<?= $invoice_info->invoices_id ?>"
                      class="form-horizontal">
                    <?php $currency = $this->invoice_model->client_currency_sambol($v_invoices->client_id);
                    if (empty($currency)) {
                        $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                    }
                    ?>
                    <input type="hidden" name="currency" value="<?= $currency->symbol ?>">

                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label"><?= lang('trans_id') ?> <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <?php $this->load->helper('string'); ?>
                            <input type="text" class="form-control" value="<?= random_string('nozero', 6); ?>" name="trans_id" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label"><?= lang('amount') ?> (<?= $currency->symbol ?>) <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="text" required="" class="form-control" value="<?= round($this->invoice_model->calculate_to('invoice_due', $invoice_info->invoices_id), 2) ?>" name="amount">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label"><?= lang('payment_date') ?></label>
                        <div class="col-lg-6">
                            <div class="input-group">
                                <input type="text" required="" name="payment_date" class="form-control datepicker"
                                       value="<?php
                                       if (!empty($payment_info->payment_date)) {
                                           echo date('d-m-Y H-i', strtotime($payment_info->payment_date));
                                       } else {
                                           echo date('d-m-Y H:i');
                                       }
                                       ?>" data-date-format="<?= config_item('date_picker_format'); ?>">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label"><?= lang('payment_method') ?> <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <div class="input-group">
                                <select class="form-control select_box" style="width: 85%"
                                        name="payment_methods_id">
                                    <option value="0"><?= lang('select_payment_method') ?></option>
                                    <?php
                                    $payment_methods = $this->db->order_by('payment_methods_id', 'DESC')->get('tbl_payment_methods')->result();
                                    if (!empty($payment_methods)) {
                                        foreach ($payment_methods as $p_method) {
                                            ?>
                                            <option value="<?= $p_method->payment_methods_id ?>" <?php
                                            if (!empty($payment_info->payment_method)) {
                                                echo $payment_info->payment_method == $p_method->payment_methods_id ? 'selected' : '';
                                            }
                                            ?>><?= $p_method->method_name ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <span class="input-group-text"> 
                                    <a data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/settings/inline_payment_method"><i class="fa fa-plus"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label"><?= lang('notes') ?></label>
                        <div class="col-lg-6">
                            <textarea name="notes" id="elm1" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label"><?= lang('send_email') ?></label>
                        <div class="col-lg-6">
                            <div class="form-check form-check mb-3 mr-5">
                                <input type="checkbox" class="custom-checkbox form-check-input" name="send_thank_you">
                                <label class="form-check-label"></label>
                            </div>

                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label"><?= lang('save_into_default_account') ?>
                            <i class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"
                               title="<?= lang('will_be_added_into_deposit') ?>"></i>
                        </label>
                        <div class="col-lg-6">
                            <div class="form-check form-check mb-3 mr-5">
                                <input type="checkbox" checked class="custom-checkbox form-check-input" id="use_postmark" name="save_into_account">
                                <label class="form-check-label"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3" id="postmark_config" <?php echo (empty($payment_info->account_id)) ? 'style=""' : '' ?>>
                        <label class="col-lg-3 col-form-label"><?= lang('select') . ' ' . lang('account') ?></label>
                        <div class="col-lg-5">
                            <div class="input-group">

                                <select name="account_id" style="width:80%;" class="form-control select_box">
                                    <?php
                                    $account_info = get_order_by('tbl_accounts', null, 'account_id');
                                    if (!empty($account_info)) {
                                        foreach ($account_info as $v_account) : ?>
                                            <option value="<?= $v_account->account_id ?>"<?= (config_item('default_account') == $v_account->account_id ? ' selected="selected"' : '') ?>><?= $v_account->account_name ?></option>
                                        <?php endforeach;
                                    }
                                    ?>
                                </select>
                                <span class="input-group-text"> 
                                    <a data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/account/new_account" title="<?= lang('add_new') ?>"><i class="fa fa-plus"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3"></label>
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-primary"><?= lang('add_payment') ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php
        if (!empty($all_payments_history)) {
            $reference = ": <a href='" . base_url('admin/invoice/manage_invoice/invoice_details/' . $invoice_info->invoices_id) . "' >" . $invoice_info->reference_no . "</a>";
            $invoice_due = $this->invoice_model->calculate_to('invoice_due', $invoice_info->invoices_id);
         ?>

        <div class="card border">
            <div class="card-body">
                <h4 class="card-title mb-4"><?= lang('payment_history_for_this_invoice', $reference) ?></h4>
                <table class="table table-striped dt-responsive nowrap w-100 ">
                    <thead>
                        <tr>
                            <th><?= lang('trans_id') ?></th>
                            <th><?= lang('payment_date') ?></th>
                            <th><?= lang('paid_amount') ?></th>
                            <th><?= lang('payment_method') ?></th>
                            <th><?= lang('account') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($all_payments_history as $v_payment_history) {
                            $currency = $this->invoice_model->client_currency_sambol($invoice_info->client_id);
                            if (empty($currency)) {
                                $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                            }
                            $payment_methods = $this->invoice_model->check_by(array('payment_methods_id' => $v_payment_history->payment_method), 'tbl_payment_methods');
                            $account = get_row('tbl_accounts', array('account_id' => $v_payment_history->account_id), 'account_name')
                            ?>
                            <tr>
                                <td>
                                    <a href="<?= base_url() ?>admin/invoice/manage_invoice/payments_details/<?= $v_payment_history->payments_id ?>"> <?= $v_payment_history->trans_id ?></a>
                                </td>
                                <td>
                                    <?= display_datetime($v_payment_history->payment_date) ?>
                                </td>
                                <td>
                                    <span><?= display_money($v_payment_history->amount, $currency->symbol); ?></span>
                                </td>
                                <td>
                                    <span><?= !empty($payment_methods->method_name) ? $payment_methods->method_name : '-'; ?></span>
                                </td>
                                <td>
                                    <span><?= !empty($account) ? $account : '-'; ?></span>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer border-top text-muted">
                <div class="row text-center no-gutter">
                    <div class="col-xl-4 b-r b-light">
                        <span class="h4 font-bold m-t block"><?= display_money($this->invoice_model->calculate_to('total', $invoice_info->invoices_id), $currency->symbol) ?></span>
                        <small class="text-muted m-b block"><?= lang('total') . ' ' . lang('invoice_amount') ?></small>
                    </div>
                    <div class="col-xl-4 b-r b-light">
                        <span class="h4 font-bold m-t block"><?= display_money($this->invoice_model->calculate_to('paid_amount', $invoice_info->invoices_id), $currency->symbol) ?></span>
                        <small class="text-muted m-b block"><?= lang('total') . ' ' . lang('paid_amount') ?></small>
                    </div>
                    <div class="col-xl-4">
                        <span class="h4 font-bold m-t block"><?= display_money($invoice_due, $currency->symbol) ?></span>
                        <small class="text-muted m-b block"><?= lang('total') . ' ' . lang('due_amount') ?></small>
                    </div>
                </div>
            </div>
        </div>

        <?php } ?>
        <!-- end -->
    </div>
</div>






