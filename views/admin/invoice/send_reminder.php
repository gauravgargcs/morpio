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
    <div class="col-lg-12">
        <div class="row mb-3">
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="pull-right float-end">
                            <a style="margin-top: -5px" href="<?= base_url() ?>admin/invoice/manage_invoice/create_invoice" data-original-title="<?= lang('new_invoice') ?>" data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-icon btn-<?= config_item('button_color') ?> btn-sm pull-right"><i class="fa fa-plus"></i></a>
                        </div>
                        <h4 class="card-title mb-4"> <?= lang('all_invoices') ?></h4>
                        <div data-simplebar style="max-height: 550px;">  
                            <ul class="nav flex-column" role="tablist" aria-orientation="vertical">            
                                <?php
                                if (!empty($all_invoices_info)) {
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
                                        $currency = $this->invoice_model->client_currency_sambol($v_invoices->client_id);
                                    } else {
                                        $client_name = '-';
                                        $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                                    }

                                    ?>
                                <li class="nav-item">
                                    <a class="nav-link <?php if ($v_invoices->invoices_id == $this->uri->segment(5)) { echo "active"; } ?>" href="<?= base_url() ?>admin/invoice/manage_invoice/send_reminder/<?= $v_invoices->invoices_id ?>">
                                        <?= $client_name ?>
                                        <div class="pull-right">
                                            <?= display_money($this->invoice_model->get_invoice_cost($v_invoices->invoices_id), $currency->symbol); ?>
                                        </div>
                                        <br>
                                        <small class="block small text-muted"><?= $v_invoices->reference_no ?> <span
                                                class="label label-<?= $label ?>"><?= $invoice_status ?></span>
                                        </small>
                                    </a>
                                </li>
                                <?php } } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><?= lang('send_reminder') ?></h4>
                        <form class="form-horizontal" method="post" action="<?= base_url() ?>admin/invoice/send_invoice_email/<?= $invoice_info->invoices_id ?>">
                            <input type="hidden" name="ref" value="<?= $invoice_info->reference_no ?>">
                            <?php $client_info = $this->invoice_model->check_by(array('client_id' => $invoice_info->client_id), 'tbl_client'); ?>
                            <input type="hidden" name="client_name" value="<?= ucfirst($client_info->name) ?>">
                            <input type="hidden" name="currency" value="<?= $invoice_info->currency; ?>">

                            <input type="hidden" name="amount"
                                   value="<?= ($this->invoice_model->calculate_to('invoice_due', $invoice_info->invoices_id)) ?>">

                            <div class="row mb-3">
                                <label class=" col-lg-1 col-form-label"><?= lang('subject') ?></label>
                                <div class="col-lg-7">
                                    <?php
                                    $email_template = $this->invoice_model->check_by(array('email_group' => 'invoice_reminder'), 'tbl_email_templates');
                                    $message = $email_template->template_body;
                                    $subject = $email_template->subject;
                                    ?>
                                    <input type="text" class="form-control" value="<?= $subject ?> <?= $invoice_info->reference_no ?>" name="subject">
                                </div>
                            </div>


                            <textarea name="message" class="form-control" id="ck_editor"><?= $message ?></textarea>
                            <?php echo display_ckeditor($editor['ckeditor']); ?>

                            <div class="row mb-3">
                                <label class=" col-lg-1 col-form-label">
                                    <button type="submit" class="submit btn btn-<?= config_item('button_color') ?>"><?= lang('send') ?></button>
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


