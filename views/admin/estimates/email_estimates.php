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
                            <a style="margin-top: -5px" href="<?= base_url() ?>admin/estimates/index/edit_estimates"
                               data-original-title="<?= lang('new_estimate') ?>" data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-icon btn-primary btn-sm pull-right"><i class="fa fa-plus"></i></a>
                        </div>
                        <h4 class="card-title mb-4"><?= lang('all_estimates') ?></h4>
                        <div data-simplebar style="max-height: 550px;">  
                            <ul class="nav flex-column" role="tablist" aria-orientation="vertical">
                                <?php
                                if (!empty($all_estimates_info)) {
                                    foreach ($all_estimates_info as $key => $v_estimate) {
                                    if ($v_estimate->invoiced == 'Yes') {
                                        $invoice_status = strtoupper(lang('invoiced'));
                                        $label = 'success';
                                    } elseif ($v_estimate->emailed == 'Yes') {
                                        $invoice_status = strtoupper(lang('sent'));
                                        $label = 'info';
                                    } else {
                                        $invoice_status = lang($v_estimate->status);
                                        $label = 'default';
                                    }
                                    ?>
                                <li class="nav-item">
                                    <a class="nav-link <?php
                                        if ($v_estimate->estimates_id == $this->uri->segment(5)) {
                                            echo "active";
                                        }
                                        ?>" href="<?= base_url() ?>admin/estimates/index/email_estimates/<?= $v_estimate->estimates_id ?>">

                                            <?php if ($v_estimate->client_id == '0') { ?>
                                                <span class="label label-success">General Estimate</span>
                                                <?php
                                            } else {
                                                $client_info = $this->estimates_model->check_by(array('client_id' => $estimates_info->client_id), 'tbl_client');
                                                ?>
                                                <?= ucfirst($client_info->name) ?>
                                            <?php } ?>
                                            <div class="pull-right">
                                                <?php $currency = $this->estimates_model->client_currency_sambol($estimates_info->client_id); ?>
                                                <?= display_money($this->estimates_model->estimate_calculation('estimate_amount', $estimates_info->estimates_id), $currency->symbol); ?>
                                            </div>
                                            <br>
                                            <small class="block small text-muted"><?= $v_estimate->reference_no ?> <span
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
                        <h4 class="card-title mb-4"><?= lang('email_estimate') ?></h4>
                
                        <form class="form-horizontal" method="post" action="<?= base_url() ?>admin/estimates/send_estimates_email/<?= $estimates_info->estimates_id ?>">
                            <input type="hidden" name="ref" value="<?= $estimates_info->reference_no ?>">
                            <?php $client_info = $this->estimates_model->check_by(array('client_id' => $estimates_info->client_id), 'tbl_client'); ?>
                            <input type="hidden" name="client_name" value="<?= ucfirst($client_info->name) ?>">
                            <input type="hidden" name="currency" value="<?= $estimates_info->currency; ?>">

                            <input type="hidden" name="amount"
                                   value="<?= ($this->estimates_model->estimate_calculation('total', $estimates_info->estimates_id)) ?>">

                            <div class="row mb-3">
                                <label class="col-lg-1 col-form-label"><?= lang('subject') ?></label>
                                <div class="col-lg-7">
                                    <?php
                                    $email_template = $this->estimates_model->check_by(array('email_group' => 'estimate_email'), 'tbl_email_templates');
                                    $message = $email_template->template_body;
                                    $subject = $email_template->subject;
                                    ?>
                                    <input type="text" class="form-control" value="<?= $subject ?> <?= $estimates_info->reference_no ?>" name="subject">
                                </div>
                            </div>
                            <textarea name="message" class="form-control" id="ck_editor"><?= $message ?></textarea>
                            <?php echo display_ckeditor($editor['ckeditor']); ?>

                            <div class="row mb-3">
                                <label class=" col-lg-1 col-form-label">
                                    <button type="submit"  class="submit btn btn-primary"><?= lang('send') ?></button>
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


