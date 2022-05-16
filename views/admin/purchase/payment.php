
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
                    $currency = $this->purchase_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                    if (empty($purchase_info)) {
                        redirect('admin/purchase/manage_purchase');
                    }
                    if ($this->session->userdata('user_type') == '1') {
                        ?>
                        <a style="margin-top: -5px;" href="<?= base_url() ?>admin/purchase/manage_purchase"
                           data-original-title="<?= lang('new_invoice') ?>" data-bs-toggle="tooltip" data-bs-placement="top"
                           class="btn btn-icon btn-<?= config_item('button_color') ?> btn-sm pull-right"><i
                                class="fa fa-plus"></i></a>
                    <?php } ?>
                </div>
                <h4 class="card-title mb-4"><?= lang('all') . ' ' . lang('purchase') ?></h4>
                <div data-simplebar style="max-height: 550px;">  
                    <ul class="nav flex-column" role="tablist" aria-orientation="vertical">
                        <?php
                        if (!empty($all_purchases)) {
                            $all_purchases = array_reverse($all_purchases);
                            foreach ($all_purchases as $v_purchase) {
                                $payment_status = $this->purchase_model->get_payment_status($v_purchase->purchase_id);
                                if ($payment_status == ('fully_paid')) {
                                    $label = "success";
                                } elseif ($payment_status == ('draft')) {
                                    $label = "secondary";
                                } elseif ($payment_status == ('cancelled')) {
                                    $label = "danger";
                                } elseif ($payment_status == ('partially_paid')) {
                                    $label = "warning";
                                } elseif ($v_purchase->emailed == 'Yes') {
                                    $label = "info";
                                    $payment_status = ('sent');
                                } else {
                                    $label = "danger";
                                }
                                ?>
                        <li class="nav-item">
                            <?php
                            $client_info = $this->purchase_model->check_by(array('supplier_id' => $v_purchase->supplier_id), 'tbl_suppliers');
                            if (!empty($client_info)) {
                                $client_name = $client_info->name;
                            } else {
                                $client_name = '-';
                            }
                            ?>
                            <a class="nav-link <?php  if ($v_purchase->purchase_id == $this->uri->segment(5)) {  echo "active";  } ?>"  href="<?= base_url() ?>admin/purchase/payment/<?= $v_purchase->purchase_id ?>">
                                <?= $client_name ?>
                                <div class="pull-right">
                                    <?= display_money($this->purchase_model->get_purchase_cost($v_purchase->purchase_id), $currency->symbol); ?>
                                </div>
                                <br>
                                <small class="block small text-muted"><?= $v_purchase->reference_no ?> <span
                                        class="badge badge-soft-<?= $label ?>"><?= lang($payment_status) ?></span>
                                </small>
                            </a>
                        </li>
                        <?php } }   ?>
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
                <h4 class="card-title mb-4"><?= lang('pay') . ' ' . lang('purchase') ?></h4>
                <form method="post" data-parsley-validate="" novalidate="" action="<?= base_url() ?>admin/purchase/get_payemnt/<?= $purchase_info->purchase_id ?>"  class="form-horizontal">
                    <input type="hidden" name="currency" value="<?= $currency->symbol ?>">

                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label"><?= lang('trans_id') ?> <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <?php $this->load->helper('string'); ?>
                            <input type="text" class="form-control" value="<?= random_string('nozero', 6); ?>"
                                   name="trans_id" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">

                        <label class="col-lg-3 col-form-label"><?= lang('amount') ?> (<?= $currency->symbol ?>) <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="text" required="" class="form-control"
                                   value="<?= round($this->purchase_model->calculate_to('purchase_due', $purchase_info->purchase_id), 2) ?>"
                                   name="amount">
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
                                    <a  title="<?= lang('new') . ' ' . lang('payment_method') ?>" data-bs-toggle="modal" data-bs-target="#myModal" href="<?= base_url() ?>admin/settings/inline_payment_method"><i class="fa fa-plus"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label"><?= lang('notes') ?></label>
                        <div class="col-lg-6">
                            <textarea name="notes" id="elm1"  class="form-control"></textarea>
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
                        <label class="col-lg-3 col-form-label"><?= lang('deduct_from_default_account') ?>
                            <i class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"
                               title="<?= lang('will_be_deduct_into_account') ?>"></i>
                        </label>
                        <div class="col-lg-6">
                            <div class="form-check form-check mb-3 mr-5">
                                <input type="checkbox" checked class="custom-checkbox form-check-input" id="use_postmark" name="deduct_from_account">
                                <label class="form-check-label"></label>
                            </div>
                        </div>
                    </div>
                    <div id="postmark_config" <?php echo (empty($payment_info->account_id)) ? 'style="display:block"' : '' ?>>
                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label"><?= lang('select') . ' ' . lang('account') ?></label>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <select name="account_id" style="width:85%;" class="form-control select_box">
                                        <?php
                                        $account_info = get_order_by('tbl_accounts', null, 'account_id');
                                        if (!empty($account_info)) {
                                            foreach ($account_info as $v_account) : ?>
                                                <option
                                                    value="<?= $v_account->account_id ?>"<?= (config_item('default_account') == $v_account->account_id ? ' selected="selected"' : '') ?>><?= $v_account->account_name ?></option>
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
            $reference = ": <a href='" . base_url('admin/invoice/manage_invoice/invoice_details/' . $purchase_info->purchase_id) . "' >" . $purchase_info->reference_no . "</a>";
            $purchase_due = $this->purchase_model->calculate_to('purchase_due', $purchase_info->purchase_id);
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
                            $currency = $this->purchase_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                            $payment_methods = $this->purchase_model->check_by(array('payment_methods_id' => $v_payment_history->payment_method), 'tbl_payment_methods');
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
                        <span class="h4 font-bold m-t block"><?= display_money($this->purchase_model->calculate_to('total', $purchase_info->purchase_id), $currency->symbol) ?></span>
                        <small class="text-muted m-b block"><?= lang('total') . ' ' . lang('invoice_amount') ?></small>
                    </div>
                    <div class="col-xl-4 b-r b-light">
                        <span class="h4 font-bold m-t block"><?= display_money($this->purchase_model->calculate_to('paid_amount', $purchase_info->purchase_id), $currency->symbol) ?></span>
                        <small class="text-muted m-b block"><?= lang('total') . ' ' . lang('paid_amount') ?></small>
                    </div>
                    <div class="col-xl-4">
                        <span class="h4 font-bold m-t block"><?= display_money($purchase_due, $currency->symbol) ?></span>
                        <small class="text-muted m-b block"><?= lang('total') . ' ' . lang('due_amount') ?></small>
                    </div>
                 </div>
            </div>
        </div>

        <?php } ?>
        <!-- end -->
    </div>
</div>







