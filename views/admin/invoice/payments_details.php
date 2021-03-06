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
<?php
$edited = can_action('13', 'edited');
$invoice_info = $this->invoice_model->check_by(array('invoices_id' => $payments_info->invoices_id), 'tbl_invoices');
$client_info = $this->invoice_model->check_by(array('client_id' => $payments_info->paid_by), 'tbl_client');
$payment_methods = $this->invoice_model->check_by(array('payment_methods_id' => $payments_info->payment_method), 'tbl_payment_methods');
$can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $payments_info->invoices_id));
$currency = $this->invoice_model->client_currency_sambol($client_info->client_id);
?>
<div class="row">
    <div class="col-lg-12">
        <div class="row mb-3">
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><?= lang('all_payments') ?></h4>
                        <div data-simplebar style="max-height: 550px;">  
                            <ul class="nav flex-column" role="tablist" aria-orientation="vertical">
                                <?php
                                if (!empty($all_invoices_info)) {
                                    foreach ($all_invoices_info as $v_invoice) {
                                        if (!empty($v_invoice)) {
                                            $all_payment_info = $this->db->where('invoices_id', $v_invoice->invoices_id)->get('tbl_payments')->result();
                                            if (!empty($all_payment_info)):foreach ($all_payment_info as $v_payments_info):
                                                $client_info = $this->invoice_model->check_by(array('client_id' => $v_payments_info->paid_by), 'tbl_client');
                                                if (!empty($client_info)) {
                                                    $client_name = $client_info->name;
                                                    $currency = $this->invoice_model->client_currency_sambol($v_invoice->client_id);
                                                } else {
                                                    $client_name = '-';
                                                    $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                                                }
                                                ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?= ($v_payments_info->payments_id == $this->uri->segment(5) ? 'active' : '') ?>" href="<?= base_url() ?>admin/invoice/manage_invoice/payments_details/<?= $v_payments_info->payments_id ?>">
                                            <?= $client_name ?>
                                            <div class="pull-right">
                                                <?=
                                                display_money($v_payments_info->amount, $currency->symbol)
                                                ?>
                                            </div>
                                            <br>
                                            <small
                                                class="block small text-info"><?= $v_payments_info->trans_id ?>
                                                | <?= display_datetime($v_payments_info->created_date); ?> </small>

                                        </a>
                                    </li>
                                <?php
                                    endforeach;
                                    endif;
                                } } } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $p_client_info = $this->invoice_model->check_by(array('client_id' => $payments_info->paid_by), 'tbl_client');
            if (!empty($p_client_info)) {
                $p_client_name = $p_client_info->name;
                $currency = $this->invoice_model->client_currency_sambol($payments_info->paid_by);
            } else {
                $p_client_name = '-';
                $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
            }
            ?>
            <div class="col-lg-9 col-md-9 col-sm-9">
                <div class="card">
                    <div class="card-body">
                        <div class="float-end pull-right">
                            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                <div class="btn-group">
                                    <a data-bs-toggle="tooltip" data-bs-placement="top"
                                       href="<?= base_url() ?>admin/invoice/all_payments/<?= $payments_info->payments_id ?>"
                                       title="<?= lang('edit_payment') ?>"
                                       class="btn btn-sm btn-primary mr">
                                        <i class="fa fa-pencil"></i> <?= lang('edit_payment') ?></a>
                                </div>

                                <a data-bs-toggle="tooltip" data-bs-placement="top"
                                   href="<?= base_url() ?>admin/invoice/send_payment/<?= $payments_info->payments_id . '/' . $payments_info->amount ?>"
                                   title="<?= lang('send_email') ?>"
                                   class="btn btn-sm btn-danger pull-right mr">
                                    <i class="fa fa-envelope"></i> <?= lang('send_email') ?></a>


                                <a data-bs-toggle="tooltip" data-bs-placement="top"
                                   href="<?= base_url() ?>admin/invoice/payments_pdf/<?= $payments_info->payments_id ?>"
                                   title="<?= lang('pdf') ?>"
                                   class="btn btn-sm btn-success pull-right mr">
                                    <i class="fa fa-file-pdf-o"></i> <?= lang('pdf') ?></a>
                            <?php } ?>
                        </div>
                        <div class="details-page" style="margin:45px 25px 25px 8px">
                            <div class="details-container clearfix" style="margin-bottom:20px">
                                <div style="font-size:10pt;">
                                    <div style="padding:5px;">
                                        <div style="padding-bottom:25px;border-bottom:1px solid #eee;width:100%;">
                                            <div>
                                                <div style="text-transform: uppercase;font-weight: bold;">
                                                    <div class="pull-left">
                                                        <img
                                                            style="width: 60px;width: 60px;margin-top: -10px;margin-right: 10px;"
                                                            src="<?= base_url() . config_item('invoice_logo') ?>">
                                                    </div>
                                                    <div class="pull-left">
                                                        <?= config_item('company_name') ?>
                                                        <p style="color:#999"><?= $this->config->item('company_address') ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="clear:both;"></div>
                                        </div>
                                        <div style="padding:15px 0 50px;text-align:center">
                                            <span
                                                style="text-transform: uppercase; border-bottom:1px solid #eee;font-size:13pt;"><?= lang('payments_received') ?></span>
                                        </div>
                                        <div style="width: 70%;float: left;">
                                            <div style="width: 100%;padding: 11px 0;">
                                                <div
                                                    style="color:#999;width:35%;float:left;"><?= lang('payment_date') ?></div>
                                                <div
                                                    style="width:65%;border-bottom:1px solid #eee;float:right;foat:right;"><?= display_datetime($payments_info->payment_date); ?></div>
                                                <div style="clear:both;"></div>
                                            </div>
                                            <div style="width: 100%;padding: 10px 0;">
                                                <div
                                                    style="color:#999;width:35%;float:left;"><?= lang('transaction_id') ?></div>
                                                <div
                                                    style="width:65%;border-bottom:1px solid #eee;float:right;foat:right;min-height:22px"><?= $payments_info->trans_id ?></div>
                                                <div style="clear:both;"></div>
                                            </div>
                                        </div>
                                        <div style="text-align:center;color:white;float:right;background:#1B9BA0;width: 25%;
                                             padding: 20px 5px;">
                                            <span> <?= lang('amount_received') ?></span><br>
                                            <span style="font-size:16pt;"><?= display_money($payments_info->amount, $currency->symbol); ?></span>
                                        </div>
                                        <div style="clear:both;"></div>
                                        <div style="padding-top:10px">
                                            <div style="width:75%;border-bottom:1px solid #eee;float:right"><strong><a
                                                        href="<?= base_url() ?>admin/client/client_details/<?= $payments_info->paid_by ?>"><?= $p_client_name ?></a></strong>
                                            </div>
                                            <div style="color:#999;width:25%"><?= lang('received_from') ?></div>
                                        </div>
                                        <?php
                                        $role = $this->session->userdata('user_type');
                                        if ($role == 1 && $payments_info->account_id != 0) {
                                            $account_info = $this->invoice_model->check_by(array('account_id' => $payments_info->account_id), 'tbl_accounts');
                                            if (!empty($account_info)) {
                                                ?>
                                                <div style="padding-top:25px">
                                                    <div
                                                        style="width:75%;border-bottom:1px solid #eee;float:right">
                                                        <a
                                                            href="<?= base_url() ?>admin/account/manage_account"><?= $account_info->account_name ?></a>
                                                    </div>
                                                    <div style="color:#999;width:25%"><?= lang('received_account') ?></div>
                                                </div>
                                            <?php }
                                        } ?>
                                        <div style="padding-top:25px">
                                            <div
                                                style="width:75%;border-bottom:1px solid #eee;float:right"><?= !empty($payment_methods->method_name) ? $payment_methods->method_name : '-' ?></div>
                                            <div style="color:#999;width:25%"><?= lang('payment_mode') ?></div>
                                        </div>

                                        <div style="padding-top:25px">
                                            <div
                                                style="width:75%;border-bottom:1px solid #eee;float:right"><?= $payments_info->notes ?></div>
                                            <div style="color:#999;width:25%"><?= lang('notes') ?></div>
                                        </div>
                                        <?php $invoice_due = $this->invoice_model->calculate_to('invoice_due', $payments_info->invoices_id);?>

                                        <div style="margin-top:50px">
                                            <div style="width:100%">
                                                <div style="width:50%;float:left"><h4><?= lang('payment_for') ?></h4></div>
                                                <div style="clear:both;"></div>
                                            </div>

                                            <table style="width:100%;margin-bottom:35px;table-layout:fixed;" cellpadding="0"
                                                   cellspacing="0" border="0">
                                                <thead>
                                                <tr style="height:40px;background:#f5f5f5">
                                                    <td style="padding:5px 10px 5px 10px;word-wrap: break-word;">
                                                        <?= lang('invoice_code') ?>
                                                    </td>
                                                    <td style="padding:5px 10px 5px 5px;word-wrap: break-word;"
                                                        align="right">
                                                        <?= lang('invoice_date') ?>
                                                    </td>
                                                    <td style="padding:5px 10px 5px 5px;word-wrap: break-word;"
                                                        align="right">
                                                        <?= lang('invoice_amount') ?>
                                                    </td>
                                                    <td style="padding:5px 10px 5px 5px;word-wrap: break-word;"
                                                        align="right">
                                                        <?= lang('paid_amount') ?>
                                                    </td>
                                                    <?php if ($invoice_due > 0) { ?>
                                                        <td style="padding:5px 10px 5px 5px;color:red;word-wrap: break-word;"
                                                            align="right">
                                                            <?= lang('due_amount') ?>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr style="border-bottom:1px solid #ededed">
                                                    <td style="padding: 10px 0px 10px 10px;"
                                                        valign="top"><a
                                                            href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $payments_info->invoices_id ?>"> <?= $invoice_info->reference_no ?></a>
                                                    </td>
                                                    <td style="padding: 10px 10px 5px 10px;text-align:right;word-wrap: break-word;"
                                                        valign="top">
                                                        <?= display_datetime($invoice_info->invoice_date) ?>
                                                    </td>
                                                    <td style="padding: 10px 10px 5px 10px;text-align:right;word-wrap: break-word;"
                                                        valign="top">
                                                        <span><?= display_money($this->invoice_model->calculate_to('total', $invoice_info->invoices_id), $currency->symbol); ?></span>
                                                    </td>
                                                    <td style="text-align:right;padding: 10px 10px 10px 5px;word-wrap: break-word;"
                                                        valign="top">
                                                        <span><?= display_money($payments_info->amount, $currency->symbol); ?></span>
                                                    </td>
                                                    <?php if ($invoice_due > 0) { ?>
                                                        <td style="text-align:right;padding: 10px 10px 10px 5px;word-wrap: break-word;color: red"
                                                            valign="top">
                                                            <span><?= display_money($invoice_due, $currency->symbol); ?></span>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Payment -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end -->