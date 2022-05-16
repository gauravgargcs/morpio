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
                <?php
                echo form_open(base_url('admin/attendance/get_report/'), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                <?php super_admin_form($companies_id, 3, 5) ?>
                <div class="row mb-3">
                    <label for="field-1" class="col-sm-3 col-form-label"><?= lang('department') ?><span
                            class="required">*</span></label>
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
                <script type="text/javascript">
                    $(document).ready(function () {
                        $('select[name="companies_id"]').on('change', function () {
                            var companies_id = $(this).val();
                            if (companies_id) {
                                $.ajax({
                                    url: '<?= base_url('admin/global_controller/json_by_company/tbl_departments/')?>' + companies_id,
                                    type: "GET",
                                    dataType: "json",
                                    success: function (data) {
                                        $('select[name="departments_id"]').find('option').not(':first').remove();
                                        $.each(data, function (key, value) {
                                            $('select[name="departments_id"]').append('<option value="' + value.departments_id + '">' + value.deptname + '</option>');
                                        });
                                    }
                                });
                            } else {
                                $('select[name="departments_id"]').empty();
                            }
                        });
                    });
                </script>
                <div class="row mb-3">
                    <label for="field-1" class="col-sm-3 col-form-label"><?= lang('month') ?><span class="required"> *</span></label>
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

<?php if ((!empty($date)) && !empty($attendace_info)): ?>
<div class="row" id="printableArea">
    <div class="col-lg-12 std_print">
        <div class="card">
            <div class="card-body">
                <div class="pull-right hidden-print float-end">
                    <a href="<?= base_url() ?>admin/attendance/attendance_pdf/1/<?= $departments_id . '/' . $date; ?>"
                       class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                       title="<?= lang('pdf') ?>"><span><i class="fa fa-file-pdf-o"></i></span></a>
                    <a href="" onclick="printEmp_report('printableArea')" class="btn btn-danger btn-sm"
                       data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('print') ?>"><span><i class="fa fa-print"></i></span></a>
                </div>
                <h4 class="card-title mb-4"> <?= lang('works_hours_deatils') . ' ' ?><?php echo $month; ?>
                    <div class="show_print">
                        <?= lang('department') . ' : ' . $dept_name->deptname ?>
                    </div>

                </h4>
                <div class="accordion accordion-flush" id="accordionFlushExample" aria-multiselectable="true">
                    <?php
                    foreach ($attendace_info as $week => $v_attndc_info):
                        ?>
                    <div class="accordion-item" id="">
                        <h2 class="card-title accordion-header" id="headingOne-<?php echo $week; ?>">        
                            <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#att-<?php echo $week ?>" aria-expanded="true" aria-controls="headingOne-<?php echo $week; ?>">
                                <span style="width:80%"><?= lang('week') ?> : <?php echo $week; ?> </span>
                              
                            </button>
                        </h2>
                        <div id="att-<?php echo $week ?>" class="accordion-collapse collapse" aria-labelledby="headingOne-<?php echo $week; ?>" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body text-muted">
                                <table class="table table-bordered table-hover table-striped dt-responsive nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th><?= lang('name') ?></th>
                                        <?php
                                        if (!empty($v_attndc_info)): foreach ($v_attndc_info as $date => $attendace):
                                            $total_hour = 0;
                                            $total_minutes = 0;
                                            ?>
                                            <th>

                                                <?= strftime(config_item('date_format'), strtotime($date)) ?></th>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($employee_info as $v_employee):
                                        ?>
                                        <tr>
                                            <td><?php echo $v_employee->fullname ?></td>
                                            <?php
                                            if (!empty($v_attndc_info)):foreach ($v_attndc_info as $date => $attendace):

                                                $total_hh = 0;
                                                $total_mm = 0;
                                                foreach ($attendace as $key => $v_attendace) {
                                                    if ($key == $v_employee->user_id) {
                                                        ?>
                                                        <?php
                                                        if (!empty($v_attendace)) {
                                                            $hourly_leave = null;
                                                            foreach ($v_attendace as $v_attandc) {
                                                                if (!empty($v_attandc->clockout_time)) {
                                                                    // calculate the start timestamp
                                                                    $startdatetime = strtotime($v_attandc->date_in . " " . $v_attandc->clockin_time);
                                                                    // calculate the end timestamp
                                                                    $enddatetime = strtotime($v_attandc->date_out . " " . $v_attandc->clockout_time);
                                                                    // calulate the difference in seconds
                                                                    $difference = $enddatetime - $startdatetime;
                                                                    $years = abs(floor($difference / 31536000));
                                                                    $days = abs(floor(($difference - ($years * 31536000)) / 86400));
                                                                    $hours = abs(floor(($difference - ($years * 31536000) - ($days * 86400)) / 3600));
                                                                    $mins = abs(floor(($difference - ($years * 31536000) - ($days * 86400) - ($hours * 3600)) / 60));#floor($difference / 60);
                                                                    $total_mm += $mins;
                                                                    $total_hh += $hours;
                                                                    if (!empty($v_attandc->leave_application_id)) { // check leave type is hours
                                                                        $is_hours = get_row('tbl_leave_application', array('leave_application_id' => $v_attandc->leave_application_id));
                                                                        if (!empty($is_hours) && $is_hours->leave_type == 'hours') {
                                                                            $hourly_leave = "<small class='label label-pink text-sm' data-bs-toggle='tooltip' data-bs-placement='top'  title='" . lang('hourly') . ' ' . lang('leave') . ": " . $is_hours->hours . ":00" . ' ' . lang('hour') . "'>" . lang('hourly') . ' ' . lang('leave') . "</small>";

                                                                        }
                                                                    }
                                                                    // output the result
                                                                    //echo round($hoursDiff) . " : " . round($minutesDiffRemainder) . " m";
                                                                } elseif (!empty($v_attandc->date) && $v_attandc->date == $date && $v_attandc->attendance_status == 'H') {
                                                                    $holiday = 1;
                                                                } elseif ($v_attandc->attendance_status == '3') {
                                                                    $leave = 1;
                                                                } elseif ($v_attandc->attendance_status == '0') {
                                                                    $absent = 1;
                                                                }

                                                            }
                                                        }
                                                    }
                                                }
                                                ?>
                                                <td>

                                                    <?php
                                                    if ($total_mm > 59) {
                                                        $total_hh += intval($total_mm / 60);
                                                        $total_mm = intval($total_mm % 60);
                                                    }
                                                    $total_hour += $total_hh;
                                                    $total_minutes += $total_mm;

                                                    if ($total_hh != 0 || $total_mm != 0) {
                                                        echo $total_hh . " : " . $total_mm . " m" . ' ' . $hourly_leave;
                                                    } elseif (!empty($holiday)) {
                                                        echo '<span style="font-size: 12px;" class="label label-info std_p">' . lang('holiday') . '</span>';
                                                    } elseif (!empty($leave)) {
                                                        echo '<span style="font-size: 12px;" class="label label-warning std_p">' . lang('on_leave') . '</span>';
                                                    } elseif (!empty ($absent)) {
                                                        echo '<span style="font-size: 12px;" class="label label-danger std_p">' . lang('absent') . '</span>';
                                                    } else {
                                                        echo $total_hh . " : " . $total_mm . " m";
                                                    }
                                                    ?>
                                                </td>
                                                <?php
                                                $holiday = NULL;
                                                $leave = NULL;
                                                $absent = NULL;
                                            endforeach;
                                            endif;
                                            ?>
                                        </tr>
                                    <?php endforeach; ?>

                                    <table>
                                        <tr>
                                            <td colspan="2" class="text-right">
                                                <strong
                                                    style="margin-right: 10px; "><?= lang('total_working_hour') ?>
                                                    : </strong>
                                            </td>
                                            <td>
                                                <?php
                                                if ($total_minutes > 60) {
                                                    $total_hour += intval($total_minutes / 60);
                                                    $total_minutes = intval($total_minutes % 60);
                                                }
                                                echo $total_hour . " : " . $total_minutes . " m";
                                                ?>
                                            </td>
                                        </tr>
                                    </table>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script type="text/javascript">
    function printEmp_report(printableArea) {
        $('div.wrapper').find('.collapse').css('display', 'block');
        var printContents = document.getElementById(printableArea).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>