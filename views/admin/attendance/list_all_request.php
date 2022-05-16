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
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="pull-right hidden-print">
                    <a href="<?= base_url() ?>admin/attendance/add_time_manually" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal"> <i class="fa fa-plus "></i> <?= ' ' . lang('add_time_manually') ?></a>
                </div>
                <h4 class="card-title mb-4"><?= lang('all_timechange_request') ?></h4>
                <!-- Table -->
                <table class="table table-striped dt-responsive nowrap w-100" id="all_attendance_req_datatable">

                    <thead>

                    <tr>

                        <?php super_admin_opt_th() ?>

                        <th><?= lang('emp_id') ?></th>

                        <th><?= lang('name') ?></th>

                        <th><?= lang('time_in') ?></th>

                        <th><?= lang('time_out') ?></th>

                        <th><?= lang('status') ?></th>

                        <th><?= lang('action') ?></th>

                    </tr>

                    </thead>

                    <tbody>

                    <?php



                    if (!empty($all_clock_history)):foreach ($all_clock_history as $key => $v_clock_history):

                        ?>

                        <tr id="table_clock_history_<?= $v_clock_history->clock_history_id ?>">

                            <?php super_admin_opt_td($v_clock_history->companies_id) ?>

                            <td><?php echo $v_clock_history->employment_id; ?></td>

                            <td><?php echo $v_clock_history->fullname; ?></td>

                            <td><?php

                                if ($v_clock_history->clockin_edit != "00:00:00") {

                                    echo display_time($v_clock_history->clockin_edit);

                                }

                                ?></td>

                            <td><?php

                                if ($v_clock_history->clockout_edit != "00:00:00") {

                                    echo display_time($v_clock_history->clockout_edit);

                                }

                                ?></td>

                            <td><?php

                                if ($v_clock_history->status == 1) {

                                    $label = 'warning';

                                    $text = lang('pending');

                                } elseif ($v_clock_history->status == 2) {

                                    $label = 'success';

                                    $text = lang('accepted');

                                } elseif ($v_clock_history->status == 3) {

                                    $label = 'danger';

                                    $text = lang('rejected');

                                } ?>

                                <span class="badge badge-soft-<?= $label ?>"><?= $text ?></span>

                            </td>

                            <td>

                                <a href="<?= base_url() ?>admin/attendance/view_timerequest/<?= $v_clock_history->clock_history_id ?>"

                                   class="btn btn-outline-primary btn-sm"

                                   title="<?= lang('view') ?>" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal"><span

                                        class="fa fa-list-alt"></span></a>

                                <?php if ($this->session->userdata('user_type') == 1) { ?>

                                    <?php echo ajax_anchor(base_url("admin/attendance/delete_request/" . $v_clock_history->clock_history_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_clock_history_" . $v_clock_history->clock_history_id)); ?>

                                <?php } ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

