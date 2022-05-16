<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
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
$created = can_action('71', 'created');
$edited = can_action('71', 'edited');
$deleted = can_action('71', 'deleted');
?>
<div class="row">
    <div class="col-lg-12">
        <div class="row mb-3">
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="pull-left float-left">
                            <?php echo form_open(base_url('admin/settings/holiday_list'), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                            <div class="col-sm-8 input-group position-relative mb-3">
                                <input type="text" name="year" class="form-control years" value="<?php
                                if (!empty($year)) {
                                    echo $year;
                                }
                                ?>" data-format="yyyy">
                
                                <button type="submit" id="search_product" data-bs-toggle="tooltip" data-bs-placement="top" title="Search" class="btn btn-primary mt-sm-10"><span class="bx bx-search-alt"></span></button>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                        <div class="row">
                            <div class="mt nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <?php
                                foreach ($all_holiday_list as $key => $v_holiday_list):
                                    $year = date('Y');
                                    $month_name = date('F', strtotime($year . '-' . $key)); // get full name of month by date query
                                    ?>
                                 
                                    <a class="nav-link mb-2 <?php if ($current_month == $key) { echo 'active'; } ?>" data-bs-toggle="pill" role="tab" aria-selected="<?php if ($current_month == $key) {  echo 'true'; } else {  echo 'false'; }  ?>" href="#<?php echo $month_name ?>" aria-controls="<?=$month_name;?>"><i class="fa fa-fw fa-calendar"></i> <?php echo $month_name; ?></a>
        
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9 col-md-9 col-sm-9">
                <div class="card">
                    <div class="card-body">
                        <!-- Tabs within a box -->
                        <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
                            <?php
                            foreach ($all_holiday_list as $key => $v_holiday_list):
                                $year = date('Y');
                                $month_name = date('F', strtotime($year . '-' . $key)); // get full name of month by date query
                                ?>
                                <!-- Details tab Starts -->
                                <div class="tab-pane <?php if ($current_month == $key) { echo 'active'; } ?>" id="<?php echo $month_name ?>" style="position: relative;">
                                    <div class="card-body">
                                        <?php if (!empty($created)) { ?>
                                        <div class="pull-right float-end">
                                            <a href="<?= base_url() ?>admin/holiday/add_holiday" class="text-danger" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal"><span class="fa fa-plus "> <?= lang('new') . ' ' . lang('holiday') ?></span></a>
                                        </div>
                                        <?php } ?>

                                        <h4 class="card-title mb-4"><i class="fa fa-calendar"></i> <?php echo $month_name; ?></h4>
                                        <table class="table table-striped dt-responsive nowrap w-100 holiday_dtable">
                                            <thead>
                                            <tr>
                                                <?php super_admin_opt_th() ?>
                                                <th><?= lang('event_name') ?></th>
                                                <th class="col-sm-2"><?= lang('start_date') ?></th>
                                                <th class="col-sm-2"><?= lang('end_date') ?></th>
                                                <th class="col-sm-1"><?= lang('color') ?></th>
                                                <?php if (!empty($edited) || !empty($deleted)) { ?>
                                                    <th class="col-sm-2"><?= lang('action') ?></th>
                                                <?php } ?>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $key = 1 ?>
                                            <?php if (!empty($v_holiday_list)): foreach ($v_holiday_list as $v_holiday) : ?>
                                                <tr>
                                                    <?php super_admin_opt_td($v_holiday->companies_id) ?>
                                                    <td><?php echo $v_holiday->event_name ?></td>
                                                    <td><?= display_datetime($v_holiday->start_date) ?></td>
                                                    <td><?= display_datetime($v_holiday->end_date) ?></td>
                                                    <td><span style="background-color:<?= $v_holiday->color ?>"
                                                              class="color-box p-1"><?= $v_holiday->color ?></span></td>
                                                    <?php if (!empty($edited) || !empty($deleted)) { ?>
                                                        <td>
                                                            <?php if (!empty($edited)) { ?>

                                                                <?php echo btn_edit_modal('admin/holiday/add_holiday/' . $v_holiday->holiday_id); ?>
                                                            <?php }
                                                            if (!empty($deleted)) { ?>
                                                                <?php echo btn_delete('admin/holiday/delete_holiday/' . $v_holiday->holiday_id); ?>
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
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>