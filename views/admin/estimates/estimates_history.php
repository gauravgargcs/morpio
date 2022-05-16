
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
    <div class="col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="float-end pull-right">
                    <a style="margin-top: -5px" href="<?= base_url() ?>admin/estimates/index/edit_estimates" data-original-title="<?= lang('new_estimate') ?>" data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-icon btn-<?= config_item('button_color') ?> btn-sm pull-right"><i class="fa fa-plus"></i></a>
                </div>
                <h4 class="card-title mb-4"><?= lang('all_estimates') ?> </h4>
                <div data-simplebar style="max-height: 550px;">  
                    <ul class="nav flex-column" role="tablist" aria-orientation="vertical">
                        <?php
                            if (!empty($all_estimates_info)) {
                                foreach ($all_estimates_info as $key => $v_estimate) {
                                    if ($v_estimate->invoiced == 'Yes') {
                                        $invoice_status = 'INVOICED';
                                        $label = 'success';
                                    } elseif ($v_estimate->emailed == 'Yes') {
                                        $invoice_status = 'SENT';
                                        $label = 'info';
                                    } else {
                                        $invoice_status = 'DRAFT';
                                        $label = 'default';
                                    }
                                    ?>
                        <li class="nav-item">
                            <a class="nav-link <?php
                                if ($v_estimate->estimates_id == $this->uri->segment(3)) {
                                    echo "active";
                                }
                                ?>" href="<?= base_url() ?>admin/estimates/index/estimates_details/<?= $v_estimate->estimates_id ?>">

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
                                    <?= display_money($this->estimates_model->estimate_calculation('estimate_amount', $estimates_info->estimates_id), $currency->symbol) ?>
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
    <div class="col-xl-9">
        <div class="card">
            <div class="card-body"  id="chat-box">
                <h4 class="card-title mb-4"><?= lang('activities') ?> </h4>
                <div data-simplebar style="max-height: 800px;">  
                    <ul class="verti-timeline list-unstyled">
                    <?php
                    $activities_info = get_order_by('tbl_activities', array('module' => 'estimates', 'module_field_id' => $estimates_info->estimates_id), 'activity_date');
                    if (!empty($activities_info)) {
                        foreach ($activities_info as $v_activities) {
                            $profile_info = $this->db->where(array('user_id' => $v_activities->user))->get('tbl_account_details')->row();
                            $user_info = $this->db->where(array('user_id' => $v_activities->user))->get('tbl_users')->row();
                            ?>
                    <li class="event-list">
                        <div class="event-timeline-dot">
                            <i class="bx bx-right-arrow-circle font-size-18"></i>
                        </div>
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <h5 class="font-size-14"><?php echo date('d', strtotime($v_activities->activity_date)) ?> <?php echo date('M', strtotime($v_activities->activity_date)) ?> <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5>
                            </div>
                            <div class="flex-grow-1">
                                <div>
                                    <?php if (!empty($profile_info)) { ?>
                                    <h5 class="notice-calendar-heading-title">
                                            <a href="<?= base_url() ?>admin/user/user_details/<?= $profile_info->user_id ?>"
                                                   class="text-info"><?= $profile_info->fullname ?></a>
                                    </h5>
                                    <?php } ?>
                                    
                                    <div class="notice-calendar-date">
                                        <p><?= sprintf(lang($v_activities->activity)) ?>
                                            <strong><?= $v_activities->value1 ?></strong>
                                            <?php if (!empty($v_activities->value2)){ ?>
                                            <p class="m0 p0"><strong><?= $v_activities->value2 ?></strong></p>
                                            <?php } ?>
                                        </p>
                                    </div>
                                    <span style="font-size: 10px;" class="">
                                        <strong>
                                            <?= time_ago($v_activities->activity_date); ?>
                                        </strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php } } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end -->