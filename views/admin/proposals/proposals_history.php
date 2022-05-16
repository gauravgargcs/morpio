
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
                    <a style="margin-top: -5px" href="<?= base_url() ?>admin/estimates/index/edit_estimates"
                   data-original-title="<?= lang('new_estimate') ?>" data-toggle="tooltip" data-placement="top"
                   class="btn btn-icon btn-<?= config_item('button_color') ?> btn-sm pull-right"><i
                        class="fa fa-plus"></i></a>
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
                                ?>" href="<?= base_url() ?>admin/proposals/index/proposals_history/<?= $v_proposal->proposals_id ?>">
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
                    $activities_info = $this->db->where(array('module' => 'proposals', 'module_field_id' => $proposals_info->proposals_id))->order_by('activity_date', 'DESC')->get('tbl_activities')->result();
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