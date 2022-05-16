<?= message_box('success'); ?>
<?= message_box('error');  ?>
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
$created = can_action('95', 'created');
$edited = can_action('95', 'edited');
$deleted = can_action('95', 'deleted');
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 1 ? 'active' : ''; ?>" href="#manage" data-bs-toggle="tab"><?= lang('hourly_rate_list') ?></a>
                    </li>
                    <?php if (!empty($created) || !empty($edited)){ ?>
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 2 ? 'active' : ''; ?>" href="#create" data-bs-toggle="tab"><?= lang('set_hourly_grade') ?></a></li>
                    <?php } ?>
                </ul>
                <div class="tab-content p-3 text-muted">
                    <!-- Stock Category List tab Starts -->
                    <div class="tab-pane <?= $active == 1 ? 'active' : '' ?>" id="manage">
                        <!-- ************** general *************-->
                        <h4 class="card-title mb-4"><?= lang('hourly_rate_list') ?></h4>

                        <table class="table table-striped dt-responsive nowrap w-100" id="list_hourly_rate" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="col-sm-1"><?= lang('sl') ?></th>
                                    <?php super_admin_opt_th() ?>
                                    <th><?= lang('hourly_grade') ?></th>
                                    <th><?= lang('hourly_rates') ?></th>
                                    <?php if (!empty($edited) || !empty($deleted)) { ?>
                                    <th class="col-sm-2"><?= lang('action') ?></th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $key = 1; ?>
                                <?php if (!empty($hourly_rate_info)): foreach ($hourly_rate_info as $v_hourly_rate): ?>
                                    <tr>
                                        <td><?php echo $key; ?></td>
                                        <?php super_admin_opt_td($v_hourly_rate->companies_id) ?>
                                        <td><?php echo $v_hourly_rate->hourly_grade; ?></td>
                                        <td><?php
                                            $curency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                                            echo display_money($v_hourly_rate->hourly_rate, $curency->symbol);
                                            ?></td>
                                        <?php if (!empty($edited) || !empty($deleted)) { ?>
                                            <td>
                                                <?php if (!empty($edited)) { ?>
                                                    <?php echo btn_edit('admin/payroll/hourly_rate/' . $v_hourly_rate->hourly_rate_id); ?>
                                                <?php }
                                                if (!empty($deleted)) { ?>
                                                    <?php echo btn_delete('admin/payroll/delete_hourly_rate/' . $v_hourly_rate->hourly_rate_id); ?>
                                                <?php } ?>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                    <?php
                                    $key++;
                                endforeach;
                                    ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
              
                    <?php if (!empty($created) || !empty($edited)) {
                        if (!empty($hourly_rate)) {
                            $hourly_rate_id = $hourly_rate->hourly_rate_id;
                            $companies_id = $hourly_rate->companies_id;
                        } else {
                            $hourly_rate_id = null;
                            $companies_id = null;
                        } ?>
                    <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                        <?php echo form_open(base_url('admin/payroll/set_hourly_rate/' . $hourly_rate_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php super_admin_form($companies_id, 3, 5) ?>
                                <div class="row mb-3" id="border-none">
                                    <label class="col-sm-3 col-form-label"><?= lang('hourly_grade') ?><span class="required"> *</span></label>
                                    <div class="col-sm-5">
                                        <input type="text" name="hourly_grade" value="<?php
                                        if (!empty($hourly_rate->hourly_grade)) {  echo $hourly_rate->hourly_grade; } ?>" class="form-control" required placeholder="<?= lang('enter') . ' ' . lang('hourly_grade') ?>">
                                    </div>
                                </div>
                                <div class="row mb-3" id="border-none">
                                    <label class="col-sm-3 col-form-label"><?= lang('hourly_rates') ?><span class="required"> *</span></label>
                                    <div class="col-sm-5">
                                        <input type="text" data-parsley-type="number" name="hourly_rate" value="<?php  if (!empty($hourly_rate->hourly_rate)) {  echo $hourly_rate->hourly_rate;  }  ?>" class="salary form-control" required  placeholder="<?= lang('enter') . ' ' . lang('hourly_rates') ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-5">
                                        <button type="submit" class="btn btn-primary btn-block"><?= lang('save') ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>




