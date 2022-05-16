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

<!-- ************ Expense Report List start ************-->
<div class="row">
    <div class="col-lg-12">
        <div class="row mb-3">
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="pull-left float-left">
                            <form data-parsley-validate="" novalidate="" action="<?php echo base_url() ?>admin/performance/performance_report"  method="post">
                                <div class="col-sm-8 input-group position-relative mb-3">
                                    <div class="col-sm-8">
                                        <input type="text" required name="year" class="form-control years" value="<?php if (!empty($year)) {  echo $year; } ?>" data-format="yyyy">
                                    </div>
                                    <button type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Search" class="btn btn-primary mt-sm-10 mlt-15-10">  <i class="fa fa-search"></i></button>
                                </div>
                            </form>
                        </div>
                        <div class="row">
                            <div class="mt nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <?php
                                foreach ($all_performance_info as $key => $v_performance_info):
                                    $month_name = date('F', strtotime($year . '-' . $key)); // get full name of month by date query
                                    ?>
                                    <a class="nav-link mb-2 <?php if ($current_month == $key) { echo 'active'; } ?>" aria-selected="<?php if ($current_month == $key) { echo 'true'; } else { echo 'false'; } ?>" data-bs-toggle="pill" role="tab" href="#<?php echo $month_name ?>" aria-controls="<?php echo $month_name ?>"><i class="fa fa-calendar fa-fw"></i> <?php echo $month_name; ?> </a>
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
                            foreach ($all_performance_info as $key => $v_performance_info):
                                $month_name = date('F', strtotime($year . '-' . $key)); // get full name of month by date query
                                ?>
                                <div id="<?php echo $month_name ?>" class="tab-pane <?php if ($current_month == $key) { echo 'active'; } ?>">
                                    <div class="card-body">
                                        <?php if ($this->session->userdata('user_type') == 1) { ?>
                                        <div class="pull-right float-end">
                                            <a href="<?= base_url() ?>admin/performance/give_performance_appraisal" class="text-danger">
                                            <span class="fa fa-plus ">
                                                <?= lang('give_appraisal') ?>
                                            </span></a>
                                        </div>
                                        <?php } ?>
                                        <h4 class="card-title mb-4"><i class="fa fa-calendar"></i> <?php echo $month_name . ' ' . $year; ?></h4>
                                        <!-- Table -->
                                        <table class="table table-striped dt-responsive nowrap w-100 performance_rep_dtable">
                                            <thead>
                                                <tr>
                                                    <th class="col-sm-1"><?= lang('emp_id') ?></th>
                                                    <th>
                                                        <?= lang('employee') . ' ' . lang('name') ?>
                                                    </th>
                                                    <th><?= lang('departments') ?> > <?= lang('designation') ?></th>
                                                    <th class="col-sm-4"><?= lang('remarks') ?></th>
                                                    <th class="col-sm-1"><?= lang('action') ?></th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $key = 1; ?>
                                                <?php if (!empty($v_performance_info)): foreach ($v_performance_info as $appraisal_info) : ?>
                                                    <tr>
                                                        <td><?php echo $appraisal_info->employment_id ?></td>
                                                        <td>
                                                            <a href="<?= base_url() ?>admin/performance/appraisal_details/<?= $appraisal_info->performance_appraisal_id ?>"
                                                               title="<?= lang('view') ?>" data-bs-toggle="modal"
                                                               data-target="#myModal_lg">
                                                                <?php echo $appraisal_info->fullname ?>
                                                            </a>
                                                        </td>
                                                        <td><?php echo $appraisal_info->deptname . ' > ' . $appraisal_info->designations ?></td>
                                                        <td><?php echo $appraisal_info->general_remarks; ?></td>
                                                        <td>
                                                            <?php echo btn_view_modal('admin/performance/appraisal_details/' . $appraisal_info->performance_appraisal_id); ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $key++; endforeach; ?>
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