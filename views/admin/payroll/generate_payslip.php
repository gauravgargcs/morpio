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
                <h4 class="card-title mb-4"><?= lang('generate_payslip')?></h4> 

                <?php echo form_open(base_url('admin/payroll/generate_payslip/'), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                    <?php super_admin_form($companies_id, 3, 5) ?>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('select_department') ?>
                            <span class="required"> *</span></label>

                        <div class="col-sm-5">
                            <select name="departments_id" class="form-control select_box">
                                <option value=""><?= lang('select_department') ?></option>
                                <?php if (!empty($all_department_info)): foreach ($all_department_info as $v_department_info) :
                                    if (!empty($v_department_info->deptname)) {
                                        $deptname = $v_department_info->deptname;
                                    } else {
                                        $deptname = lang('undefined_department');
                                    }
                                    ?>
                                    <option value="<?php echo $v_department_info->departments_id; ?>"
                                        <?php
                                        if (!empty($departments_id)) {
                                            echo $v_department_info->departments_id == $departments_id ? 'selected' : '';
                                        }
                                        ?>><?php echo $deptname ?></option>
                                <?php endforeach; ?>
                                <?php endif; ?>
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
                        <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('select') . ' ' . lang('month') ?> <span
                                class="required"> *</span></label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" value="<?php if (!empty($payment_month)) { echo $payment_month; } ?>" class="form-control monthyear" id="payment_month" name="payment_month" data-provide="datepicker" data-date-container="#payment_month" data-date-format="MM yyyy" data-date-min-view-mode="1" data-date-autoclose="true">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3" id="border-none">
                        <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"></label>
                        <div class="col-sm-5">
                            <button id="submit" type="submit" name="flag" value="1"  class="btn btn-primary btn-block"><?= lang('go') ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php if (!empty($flag)): ?>
<div class="row">
    <div class="col-lg-12" data-offset="0">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <strong><?= lang('generate_payslip_for') ?><?php  if (!empty($payment_month)) {  echo ' <span class="text-danger">' . date('F Y', strtotime($payment_month)) . '</span>';  }  ?></strong>
                </h4>
                   
                <!-- Table -->
                <table class="table table-striped dt-responsive nowrap w-100" id="gen_payslip_dtable" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="col-sm-1"><?= lang('emp_id') ?></th>
                        <th><strong><?= lang('name') ?></strong></th>
                        <th><strong><?= lang('salary_type') ?></strong></th>
                        <th><strong><?= lang('basic_salary') ?></strong></th>
                        <th><strong><?= lang('net_salary') ?></strong></th>
                        <th><strong><?= lang('details') ?></strong></th>
                        <th><strong><?= lang('status') ?></strong></th>
                        <th><?= lang('action') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        $akey = 0;
                        if (!empty($employee_info)):foreach ($employee_info as $v_emp_info):
                            ?>
                            <?php if (!empty($v_emp_info)):
                            $akey += count($v_emp_info);
                            $key = $akey - 1;
                            foreach ($v_emp_info as $v_employee) { ?>
                                <tr>
                                    <td><?php echo $v_employee->employment_id ?></td>
                                    <td>
                                        <?php if (!empty($salary_info) && $salary_info->user_id == $v_employee->user_id) { ?>
                                            <a href="<?php echo base_url() ?>admin/payroll/salary_payment_details/<?php echo $salary_info->salary_payment_id ?>"
                                               title="View" data-bs-toggle="modal"
                                               data-bs-target="#myModal_lg"><?php echo $v_employee->fullname; ?></a>
                                        <?php } else { ?>
                                            <a href="<?php echo base_url() ?>admin/payroll/view_payment_details/<?php echo $v_employee->user_id . '/' . $payment_month ?>"
                                               title="View" data-bs-toggle="modal"
                                               data-bs-target="#myModal_lg"><?php echo $v_employee->fullname; ?></a>
                                        <?php } ?>

                                    </td>
                                    <td><?php
                                        $set_salary = false;
                                        if (!empty($v_employee->salary_grade)) {
                                            echo $v_employee->salary_grade . ' <small>(' . lang('monthly') . ')</small>';
                                        } else if (!empty($v_employee->hourly_grade)) {
                                            echo $v_employee->hourly_grade . ' <small>(' . lang('hourly') . ')</small>';
                                        } else {
                                            echo '<span class="text-danger">' . lang('did_not_set_salary_yet') . '</span>';
                                            $set_salary = true;
                                        }
                                        ?></td>
                                    <td><?php
                                        if (!empty($v_employee->basic_salary)) {
                                            echo $v_employee->basic_salary;
                                        } else if (!empty($v_employee->hourly_grade)) {
                                            echo $v_employee->hourly_rate . ' <small>(' . lang('per_hour') . ')</small>';
                                        } else {
                                            echo '-';
                                        }
                                        ?></td>
                                    <td><?php
                                        if (!empty($total_hours)) {
                                            foreach ($total_hours as $index => $v_total_hours) {
                                                if ($index == $v_employee->user_id) {
                                                    if (!empty($v_total_hours)) {
                                                        $total_hour = $v_total_hours['total_hours'];
                                                        $total_minutes = $v_total_hours['total_minutes'];
                                                        if ($total_hour > 0) {
                                                            $hours_ammount = $total_hour * $v_employee->hourly_rate;
                                                        } else {
                                                            $hours_ammount = 0;
                                                        }
                                                        if ($total_minutes > 0) {
                                                            $amount = 60 / $v_employee->hourly_rate;
                                                            $minutes_ammount = $total_minutes * $amount;
                                                        } else {
                                                            $minutes_ammount = 0;
                                                        }
                                                        if (!empty($advance_salary[$index])) {
                                                            $advance_amount = $advance_salary[$index]['advance_amount'];
                                                        } else {
                                                            $advance_amount = 0;
                                                        }
                                                        if (!empty($award_info[$index])) {
                                                            $total_award = $award_info[$index]['award_amount'];
                                                        } else {
                                                            $total_award = 0;
                                                        }
                                                        $total_amount = $hours_ammount + $minutes_ammount + $total_award - $advance_amount;
                                                        echo round($total_amount, 2);
                                                    }
                                                }
                                            }
                                        }
                                        if (!empty($v_employee->basic_salary)) {
                                            if (!empty($allowance_info)) {
                                                foreach ($allowance_info as $al_index => $v_allowance) {
                                                    if ($al_index == $v_employee->user_id) {
                                                        $total_allowance = $v_allowance;
                                                    }
                                                }
                                            }
                                            if (!empty($deduction_info)) {
                                                foreach ($deduction_info as $dd_index => $v_deduction) {
                                                    if ($dd_index == $v_employee->user_id) {
                                                        $total_deduction = $v_deduction;
                                                    }
                                                }
                                            }
                                            if (!empty($advance_salary)) {
                                                foreach ($advance_salary as $add_index => $v_advance) {
                                                    if ($add_index == $v_employee->user_id) {
                                                        $total_advance = $v_advance['advance_amount'];
                                                    }
                                                }
                                            }
                                            if (!empty($award_info)) {
                                                foreach ($award_info as $aw_index => $v_award_info) {
                                                    if ($aw_index == $v_employee->user_id) {
                                                        $total_award = $v_award_info['award_amount'];
                                                    }
                                                }
                                            }

                                            if (!empty($overtime_info) && !empty($v_employee->overtime_salary)) {
                                                foreach ($overtime_info as $over_index => $v_overtime) {
                                                    if ($over_index == $v_employee->user_id) {
                                                        $total_hour = $v_overtime['overtime_hours'];
                                                        $total_minutes = $v_overtime['overtime_minutes'];
                                                        if ($total_hour > 0) {
                                                            $hours_ammount = $total_hour * $v_employee->overtime_salary;
                                                        } else {
                                                            $hours_ammount = 0;
                                                        }
                                                        if ($total_minutes > 0) {
                                                            $amount = 60 / $v_employee->overtime_salary;
                                                            $minutes_ammount = $total_minutes * $amount;
                                                        } else {
                                                            $minutes_ammount = 0;
                                                        }
                                                        $total_amount = $hours_ammount + $minutes_ammount;
                                                    }
                                                }
                                            }

                                            if (empty($total_advance)) {
                                                $total_advance = 0;
                                            }
                                            if (empty($total_deduction)) {
                                                $total_deduction = 0;
                                            }
                                            if (empty($total_award)) {
                                                $total_award = 0;
                                            }
                                            if (empty($total_allowance)) {
                                                $total_allowance = 0;
                                            }
                                            if (empty($total_amount)) {
                                                $total_amount = 0;
                                            }
                                            if (empty($v_employee->basic_salary)) {
                                                $basic_salary = 0;
                                            } else {
                                                $basic_salary = $v_employee->basic_salary;
                                            }

                                            echo $basic_salary + $total_allowance + $total_amount + $total_award - $total_deduction - $total_advance;
                                        }
                                        // check existing payment by employee id and payment month
                                        $salary_info = $this->payroll_model->check_by(array('user_id' => $v_employee->user_id, 'payment_month' => $payment_month), 'tbl_salary_payment');
                                        ?></td>
                                    <td><?php if (!empty($salary_info) && $salary_info->user_id == $v_employee->user_id) { ?>
                                            <a href="<?php echo base_url() ?>admin/payroll/salary_payment_details/<?php echo $salary_info->salary_payment_id ?>"
                                               class="btn btn-info btn-sm" title="View" data-bs-toggle="modal"
                                               data-bs-target="#myModal_lg"><span class="fa fa-list-alt"></span></a>
                                        <?php } else { ?>
                                            <a href="<?php echo base_url() ?>admin/payroll/view_payment_details/<?php echo $v_employee->user_id . '/' . $payment_month ?>"
                                               class="btn btn-info btn-sm" title="View" data-bs-toggle="modal"
                                               data-bs-target="#myModal_lg"><span class="fa fa-list-alt"></span></a>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($salary_info) && $salary_info->user_id == $v_employee->user_id) { ?>
                                            <span class="badge badge-soft-success"><?= lang('paid') ?></span>
                                            <?php
                                        } else {
                                            if (empty($set_salary)) {
                                                ?>
                                                <span class="badge badge-soft-danger"><?= lang('unpaid') ?></span>
                                            <?php }
                                        } ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($salary_info) && $salary_info->user_id == $v_employee->user_id) { ?>
                                            <a class="text-success" target="_blank"
                                               href="<?php echo base_url() ?>admin/payroll/receive_generated/<?php echo $salary_info->salary_payment_id; ?>"><?= lang('generate_payslip') ?></a>
                                        <?php } else {
                                            if (!empty($set_salary)) {
                                                ?>
                                                <a class="text-warning text-bold" target="_blank"
                                                   href="<?php echo base_url() ?>admin/payroll/manage_salary_details/<?php echo $v_employee->departments_id; ?>"><?= lang('set_slary') ?></a>
                                            <?php } else {
                                                ?>
                                                <a class="text-danger"
                                                   href="<?php echo base_url() ?>admin/payroll/make_payment/<?php echo $v_employee->user_id . '/' . $v_employee->departments_id . '/' . $payment_month; ?>"><?= lang('make_payment') ?></a>
                                            <?php }
                                        } ?>
                                    </td>
                                </tr>
                                <?php
                            };
                            ?>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>