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
                            <a style="margin-top: -5px" href="<?= base_url() ?>admin/proposals/index/edit_proposals" data-original-title="<?= lang('new_proposal') ?>" data-toggle="tooltip" data-placement="top" class="btn btn-icon btn-<?= config_item('button_color') ?> btn-sm pull-right"><i class="fa fa-plus"></i></a>
                        </div>
                        <h4 class="card-title mb-4"><?= lang('all_proposals') ?></h4>
                        <div data-simplebar style="max-height: 550px;">  
                            <ul class="nav flex-column" role="tablist" aria-orientation="vertical">
                                <?php
                                if (!empty($all_proposals_info)) {
                                    foreach ($all_proposals_info as $key => $v_proposal) {
                                        if ($v_proposal->convert == 'Yes') {
                                            if ($v_proposal->convert_module == 'estimate') {
                                                $status = strtoupper(lang('estimated'));
                                            } else {
                                                $status = strtoupper(lang('invoiced'));
                                            }
                                            $label = 'success';
                                        } elseif ($v_proposal->emailed == 'Yes') {
                                            $status = strtoupper(lang('sent'));
                                            $label = 'info';
                                        } else {
                                            $status = strtoupper(lang($v_proposal->status));
                                            $label = 'default';
                                        }
                                        if ($v_proposal->module == 'client') {
                                            $client_info = $this->proposal_model->check_by(array('client_id' => $v_proposal->module_id), 'tbl_client');
                                            if (!empty($client_info)) {
                                                $name = $client_info->name . ' ' . '[' . lang('client') . ']';;
                                            } else {
                                                $name = '-';
                                            }
                                            $currency = $this->proposal_model->client_currency_sambol($v_proposal->module_id);
                                        } else if ($v_proposal->module == 'leads') {
                                            $client_info = $this->proposal_model->check_by(array('leads_id' => $v_proposal->module_id), 'tbl_leads');
                                            if (!empty($client_info)) {
                                                $name = $client_info->contact_name . ' ' . '[' . lang('leads') . ']';
                                            } else {
                                                $name = '-';
                                            }
                                            $currency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                                        } else {
                                            $name = '-';
                                            $currency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                                        }
                                        ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?php
                                        if ($v_proposal->proposals_id == $this->uri->segment(5)) {
                                            echo "active";
                                        }
                                        ?>" href="<?= base_url() ?>admin/proposals/index/email_proposals/<?= $v_proposal->proposals_id ?>">
                                                <?= $name ?>
                                                <div class="pull-right">
                                                    <?= display_money($this->proposal_model->proposal_calculation('total', $v_proposal->proposals_id), $currency->symbol); ?>
                                                </div>
                                                <br>
                                                <small class="block small text-muted"><?= $v_proposal->reference_no ?> <span
                                                        class="label label-<?= $label ?>"><?= $status ?></span>
                                                </small>

                                        </a>
                                    </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><?= lang('email_proposal') ?></h4>
                        <form class="form-horizontal" method="post"
                              action="<?= base_url() ?>admin/proposals/send_proposals_email/<?= $proposals_info->proposals_id ?>">
                            <input type="hidden" name="ref" value="<?= $proposals_info->reference_no ?>">
                            <?php
                            if ($proposals_info->module == 'client') {
                                $client_info = $this->proposal_model->check_by(array('client_id' => $proposals_info->module_id), 'tbl_client');
                                $name = $client_info->name;
                                $currency = $this->proposal_model->client_currency_sambol($proposals_info->module_id);
                            } else if ($proposals_info->module == 'leads') {
                                $client_info = $this->proposal_model->check_by(array('leads_id' => $proposals_info->module_id), 'tbl_leads');
                                $name = $client_info->contact_name;
                                $currency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                            } else {
                                $name = '-';
                                $currency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                            }

                            ?>
                            <input type="hidden" name="client_name" value="<?= $name ?>">
                            <input type="hidden" name="currency" value="<?= $proposals_info->currency; ?>">

                            <input type="hidden" name="amount"
                                   value="<?= display_money($this->proposal_model->proposal_calculation('total', $proposals_info->proposals_id), $currency->symbol) ?>">

                            <div class="row mb-3">
                                <label class=" col-lg-1 col-form-label"><?= lang('subject') ?></label>
                                <div class="col-lg-7">
                                    <?php
                                    $email_template = $this->proposal_model->check_by(array('email_group' => 'proposal_email'), 'tbl_email_templates');
                                    $message = $email_template->template_body;
                                    $subject = $email_template->subject;
                                    ?>
                                    <input type="text" class="form-control"
                                           value="<?= $subject ?> <?= $proposals_info->reference_no ?>" name="subject">
                                </div>
                            </div>


                            <textarea name="message" class="form-control" id="ck_editor"><?= $message ?></textarea>
                            <?php echo display_ckeditor($editor['ckeditor']); ?>

                            <div class="row mb-3">
                                <label class=" col-lg-1 col-form-label">
                                    <button type="submit"
                                            class="submit btn btn-primary"><?= lang('send') ?></button>
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    

