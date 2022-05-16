
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
                <div class="pull-right float-end">
                    <?php
                    if ($this->session->userdata('user_type') == '1') {
                    ?>
                    <a style="margin-top: -5px;" href="<?= base_url() ?>admin/invoice/manage_invoice/create_invoice"  data-original-title="<?= lang('new_invoice') ?>" data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-icon btn-primary btn-sm pull-right"><i
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
                                        $label = "default";
                                    }
                                    $client_info = $this->invoice_model->check_by(array('client_id' => $v_invoices->client_id), 'tbl_client');
                                    if (!empty($client_info)) {
                                        $client_name = $client_info->name;
                                    } else {
                                        $client_name = '-';
                                    }
                                ?>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($v_invoices->invoices_id == $this->uri->segment(5)) { echo "active"; } ?>" href="<?= base_url() ?>admin/invoice/manage_invoice/payment_history/<?= $v_invoices->invoices_id ?>">
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
                                        class="label label-<?= $label ?>"><?= $invoice_status ?></span>
                                </small>
                            </a>
                        </li>
                        <?php  } }  ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="card">
            <div class="card-body">

                <?= message_box('error') ?>
                <!-- Start create invoice -->
                <?php
                if (!empty($all_payments_history)) {
                $reference = ": <a href='" . base_url('admin/invoice/manage_invoice/invoice_details/' . $invoice_info->invoices_id) . "' >" . $invoice_info->reference_no . "</a>";
                $invoice_due = $this->invoice_model->calculate_to('invoice_due', $invoice_info->invoices_id);
                ?>
                <?php if ($invoice_due != 0) { ?>
                <div class="pull-right float-end">
                    <a class="btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" href="<?= base_url('admin/invoice/manage_invoice/payment/' . $invoice_info->invoices_id) ?>" title="<?= lang('add_payment') ?>">
                        <i class="fa fa-credit-card"></i> <?= lang('pay_invoice') ?>
                    </a>
                </div>
                <?php } ?>

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
                                    <span><?= !empty($payment_methods->method_name) ? $payment_methods->method_name : '-';; ?></span>
                                </td>
                                <td>
                                    <span><?= !empty($account) ? $account : '-'; ?></span>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php } ?>
            </div>
            <div class="card-footer bg-transparent border-top text-muted">
                <div class="row text-center no-gutter">
                    <div class="col-xs-4 b-r b-light">
                        <span
                            class="h4 font-bold m-t block"><?= display_money($this->invoice_model->calculate_to('total', $invoice_info->invoices_id), $currency->symbol) ?></span>
                        <small
                            class="text-muted m-b block"><?= lang('total') . ' ' . lang('invoice_amount') ?></small>
                    </div>
                    <div class="col-xs-4 b-r b-light">
                        <span
                            class="h4 font-bold m-t block"><?= display_money($this->invoice_model->calculate_to('paid_amount', $invoice_info->invoices_id), $currency->symbol) ?></span>
                        <small class="text-muted m-b block"><?= lang('total') . ' ' . lang('paid_amount') ?></small>
                    </div>
                    <div class="col-xs-4">
                        <span
                            class="h4 font-bold m-t block"><?= display_money($invoice_due, $currency->symbol) ?></span>
                        <small class="text-muted m-b block"><?= lang('total') . ' ' . lang('due_amount') ?></small>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end -->






