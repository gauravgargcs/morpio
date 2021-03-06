<style type="text/css" media="print">
    @media print {
        @page {
            size: landscape
        }
    }
</style>
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
                <h4 class="card-title mb-4"> <?= lang('attendance_report') ?></h4>
                <?php  echo form_open(base_url('admin/attendance/get_report_2/'), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>

                <div class="row mb-3">
                    <label for="field-1" class="col-sm-3 col-form-label"><?= lang('department') ?><span class="required">*</span></label>

                    <div class="col-sm-5">
                        <select required name="departments_id" class="form-control select_box">
                            <option value=""><?= lang('select') . ' ' . lang('department') ?></option>
                            <?php if (!empty($all_department)): foreach ($all_department as $department):
                                if (!empty($department->deptname)) {
                                    $deptname = $department->deptname;
                                } else {
                                    $deptname = lang('undefined_department');
                                }
                                ?>
                                <option value="<?php echo $department->departments_id; ?>"
                                    <?php if (!empty($departments_id)): ?>
                                        <?php echo $department->departments_id == $departments_id ? 'selected ' : '' ?>
                                    <?php endif; ?>>
                                    <?php echo $deptname ?>
                                </option>
                                <?php
                            endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="field-1" class="col-sm-3 col-form-label"><?= lang('month') ?><span
                            class="required"> *</span></label>
                    <div class="col-sm-5">
                        <div class="input-group">
                            <input required type="text" class="form-control monthyear" value="<?php
                            if (!empty($date)) {
                                echo date('Y-n', strtotime($date));
                            }
                            ?>" name="date">
                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="field-1" class="col-sm-3 col-form-label"></label>
                    <div class="col-sm-5 ">
                        <button type="submit" id="sbtn" class="btn btn-primary"><?= lang('search') ?></button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<div class="row" id="EmpprintReport">
    <?php if (!empty($attendance)): ?>
    <div class="col-xl-12 show_print" hidden style="background-color: rgb(224, 224, 224);margin-bottom: 5px;padding: 5px;">
        <div class="card">
            <div class="card-body">
                <table style="margin: 3px 10px 0px 24px; width:100%;">
                    <tr>
                        <td style="font-size: 15px"><strong><?= lang('department') ?>
                                : </strong><?php echo $dept_name->deptname ?>
                        </td>
                        <td style="font-size: 15px"><strong><?= lang('date') ?> :</strong><?php echo $month ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-12 std_print">
        <div class="card">
            <div class="card-body">
                <div class="pull-right hidden-print">
                    <a href="<?= base_url() ?>admin/attendance/attendance_pdf/2/<?= $departments_id . '/' . $date; ?>"
                       class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                       title="<?= lang('pdf') ?>"><span><i class="fa fa-file-pdf-o"></i></span></a>
                    <a href="#" onclick="printEmp_report('EmpprintReport')" class="btn btn-danger btn-sm"
                       data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('print') ?>"><span><i
                                class="fa fa-print"></i></span></a>
                </div>
                <h4 class="card-title mb-4"> <?= lang('attendance_list') . ' ' . lang('of') . ' ' . $month; ?> </h4>
                <div class="table-responsive">
                    <table id="" class="table table-bordered table-hover std_table" style="width:100%">
                        <thead>
                        <tr>
                            <th><?= lang('name') ?></th>
                            <?php foreach ($dateSl as $edate) : ?>
                                <th class="std_p"><?php echo $edate ?></th>
                            <?php endforeach; ?>

                        </tr>

                        </thead>

                        <tbody>
                            <?php

                            foreach ($attendance as $key => $v_employee) { ?>
                                <tr>

                                    <td><?php echo $employee[$key]->fullname ?></td>
                                    <?php

                                    foreach ($v_employee as $v_result) {
                                        ?>
                                        <?php foreach ($v_result as $emp_attendance) { ?>
                                            <td>
                                                <?php
                                                if ($emp_attendance->attendance_status == 1) {
                                                    echo '<span  style="padding:2px; 4px" class="badge badge-soft-success std_p">' . lang('p') . '</span>';
                                                }
                                                if ($emp_attendance->attendance_status == '0') {
                                                    echo '<span style="padding:2px; 4px" class="badge badge-soft-danger std_p">' . lang('a') . '</span>';
                                                }
                                                if ($emp_attendance->attendance_status == 'H') {
                                                    echo '<span style="padding:2px; 4px" class="badge badge-soft-info std_p">' . lang('h') . '</span>';
                                                }
                                                if ($emp_attendance->attendance_status == 3) {
                                                    echo '<span style="padding:2px; 4px" class="badge badge-soft-warning std_p">' . lang('l') . '</span>';
                                                }
                                                ?>
                                            </td>
                                        <?php }; ?>


                                    <?php }; ?>
                                </tr>
                            <?php }; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script type="text/javascript">
    function printEmp_report(EmpprintReport) {
        var printContents = document.getElementById(EmpprintReport).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
