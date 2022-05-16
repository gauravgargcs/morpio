<?php include_once 'asset/admin-ajax.php'; ?>
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
$created = can_action('88', 'created');
$edited = can_action('88', 'edited');
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4"><?= lang('give_performance_appraisal') ?></h4>
           
                <?php echo form_open(base_url('admin/performance/give_performance_appraisal/'), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                    <?php super_admin_form($companies_id, 2, 7) ?>

                    <div class="row mb-3" id="border-none">
                        <label for="field-1" class="col-sm-2 col-form-label"><?= lang('employee') ?> <span
                                class="required">*</span></label>
                        <div class="col-sm-7">
                            <select name="user_id" style="width: 100%" id="employee"
                                    class="form-control select_box" required>
                                <option value=""><?= lang('select_employee') ?>...</option>
                                <?php
                                $all_employee = $this->performance_model->get_all_employee($companies_id);
                                if (!empty($all_employee)): ?>
                                    <?php foreach ($all_employee as $dept_name => $v_all_employee) : ?>
                                        <optgroup label="<?php echo $dept_name; ?>">
                                            <?php if (!empty($v_all_employee)):foreach ($v_all_employee as $v_employee) : ?>
                                                <option value="<?php echo $v_employee->user_id; ?>"
                                                    <?php
                                                    if (!empty($get_appraisal_info->user_id)) {
                                                        echo $v_employee->user_id == $get_appraisal_info->user_id ? 'selected' : '';
                                                    } elseif (!empty($user_id)) {
                                                        echo $v_employee->user_id == $user_id ? 'selected' : '';
                                                    }
                                                    ?>><?php echo $v_employee->fullname . ' ( ' . $v_employee->designations . ' )' ?></option>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </optgroup>
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
                                            url: '<?= base_url('admin/global_controller/json_get_employee/')?>' + companies_id,
                                            type: "GET",
                                            dataType: "json",
                                            success: function (data) {
                                                $('select[name="user_id"]').empty();
                                                if (data) {
                                                    $.each(data, function (key, value) {
                                                        $('select[name="user_id"]').append('<optgroup label="' + key + '">');
                                                        $.each(value, function (keys, values) {
                                                            $('select[name="user_id"]').append('<option value="' + values.user_id + '">' + values.fullname + '( ' + values.designations + ' )' + '</option>');
                                                        });
                                                        $('select[name="user_id"]').append('</optgroup>');
                                                    });
                                                }
                                            }
                                        }
                                    );
                                }
                                else {
                                    $('select[name="user_id"]').empty();
                                }
                            })
                            ;
                        })
                        ;
                    </script>
                    <div class="row mb-3" id="border-none">
                        <label class="col-sm-2 col-form-label"><?= lang('select') . ' ' . lang('month') ?><span
                                class="required"> *</span></label>
                        <div class="col-sm-7">
                            <div class="input-group ">
                                <input type="text" required value="<?php
                                if (!empty($appraisal_month)) {
                                    echo $appraisal_month;
                                } elseif (!empty($get_appraisal_info->appraisal_month)) {
                                    echo $get_appraisal_info->appraisal_month;
                                }
                                ?>" class="form-control monthyear" name="appraisal_month">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3" id="border-none">
                        <label class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-2">
                            <button id="btn_emp" type="submit" class="btn btn-primary btn-xs"><?= lang('go') ?></button>
                        </div>
                    </div>
                    <!-- Select Department / Designation Section --->
                </form>
            </div>
        </div>
    </div>
</div>
<?php if (!empty($indicator_flag)) { ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <?php if (!empty($appraisal_once_given)) { ?>
                <h4 class="card-title mb-4"><?= lang('appraisal_already_provided') ?> <?= date('F Y', strtotime($appraisal_month)) ?></h4>
                <?php } ?>
                <form role="form" id="give_performance_appraisal" action="<?php echo base_url() ?>admin/performance/save_performance_appraisal/<?php if (!empty($get_appraisal_info->performance_appraisal_id)) { echo $get_appraisal_info->performance_appraisal_id; }  ?>" method="post" class="form-horizontal" style="margin-top: 10px;">
                    <div class="row">
                        <!-- Technical Competency Starts ---->
                        <div class="col-sm-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h4 class="card-title mb-4"><?= lang('technical_competency') ?></h4>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th><?= lang('performance_indicator') ?></th>
                                                <th><?= lang('expected_value') ?></th>
                                                <th><?= lang('set_value') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr> <!-- customer experience management row ---->
                                                <td><?= lang('customer_experience_management') ?></td>
                                                <td><?php
                                                    if (!empty($performance_indicator_details->customer_experiece_management) && $performance_indicator_details->customer_experiece_management == 1) {
                                                        echo lang('beginner');
                                                    } elseif (!empty($performance_indicator_details->customer_experiece_management) && $performance_indicator_details->customer_experiece_management == 2) {
                                                        echo lang('intermediate');
                                                    } elseif (!empty($performance_indicator_details->customer_experiece_management) && $performance_indicator_details->customer_experiece_management == 3) {
                                                        echo lang('advanced');
                                                    } elseif (!empty($performance_indicator_details->customer_experiece_management) && $performance_indicator_details->customer_experiece_management == 4) {
                                                        echo lang('expert_leader');
                                                    } else {
                                                        echo "<span style='color:red;font - style: italic;line - height:2.4;'>" . lang('not_set') . "</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($performance_indicator_details->customer_experiece_management) && $performance_indicator_details->customer_experiece_management != 0) { ?>
                                                        <select name="customer_experiece_management" class="form-control">
                                                            <option value=""><?= lang('none') ?></option>
                                                            <option
                                                                value="1" <?= (!empty($get_appraisal_info->customer_experiece_management) && $get_appraisal_info->customer_experiece_management == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                            <option
                                                                value="2" <?= (!empty($get_appraisal_info->customer_experiece_management) && $get_appraisal_info->customer_experiece_management == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                            <option
                                                                value="3" <?= (!empty($get_appraisal_info->customer_experiece_management) && $get_appraisal_info->customer_experiece_management == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                            <option
                                                                value="4" <?= (!empty($get_appraisal_info->customer_experiece_management) && $get_appraisal_info->customer_experiece_management == 4 ? 'selected' : '') ?>> <?= lang('expert_leader') ?></option>
                                                        </select>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr> <!-- marketing row ---->
                                                <td><?= lang('marketing') ?></td>
                                                <td><?php
                                                    if (!empty($performance_indicator_details->marketing) && $performance_indicator_details->marketing == 1) {
                                                        echo lang('beginner');
                                                    } elseif (!empty($performance_indicator_details->marketing) && $performance_indicator_details->marketing == 2) {
                                                        echo lang('intermediate');
                                                    } elseif (!empty($performance_indicator_details->marketing) && $performance_indicator_details->marketing == 3) {
                                                        echo lang('advanced');
                                                    } elseif (!empty($performance_indicator_details->marketing) && $performance_indicator_details->marketing == 4) {
                                                        echo lang('expert_leader');
                                                    } else {
                                                        echo "<span style='color:red;font - style: italic;line - height:2.4;'>" . lang('not_set') . "</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($performance_indicator_details->marketing) && $performance_indicator_details->marketing != 0) { ?>
                                                        <select name="marketing" class="form-control">
                                                            <option value=""><?= lang('none') ?></option>
                                                            <option
                                                                value="1" <?= (!empty($get_appraisal_info->marketing) && $get_appraisal_info->marketing == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                            <option
                                                                value="2" <?= (!empty($get_appraisal_info->marketing) && $get_appraisal_info->marketing == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                            <option
                                                                value="3" <?= (!empty($get_appraisal_info->marketing) && $get_appraisal_info->marketing == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                            <option
                                                                value="4" <?= (!empty($get_appraisal_info->marketing) && $get_appraisal_info->marketing == 4 ? 'selected' : '') ?>> <?= lang('expert_leader') ?></option>
                                                        </select>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr> <!-- management row ---->
                                                <td><?= lang('management') ?></td>
                                                <td><?php
                                                    if (!empty($performance_indicator_details->management) && $performance_indicator_details->management == 1) {
                                                        echo lang('beginner');
                                                    } elseif (!empty($performance_indicator_details->management) && $performance_indicator_details->management == 2) {
                                                        echo lang('intermediate');
                                                    } elseif (!empty($performance_indicator_details->management) && $performance_indicator_details->management == 3) {
                                                        echo lang('advanced');
                                                    } elseif (!empty($performance_indicator_details->management) && $performance_indicator_details->management == 4) {
                                                        echo lang('expert_leader');
                                                    } else {
                                                        echo "<span style='color:red;font - style: italic;line - height:2.4;'>" . lang('not_set') . "</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($performance_indicator_details->management) && $performance_indicator_details->management != 0) { ?>
                                                        <select name="management" class="form-control">
                                                            <option value=""><?= lang('none') ?></option>
                                                            <option
                                                                value="1" <?= (!empty($get_appraisal_info->management) && $get_appraisal_info->management == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                            <option
                                                                value="2" <?= (!empty($get_appraisal_info->management) && $get_appraisal_info->management == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                            <option
                                                                value="3" <?= (!empty($get_appraisal_info->management) && $get_appraisal_info->management == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                            <option
                                                                value="4" <?= (!empty($get_appraisal_info->management) && $get_appraisal_info->management == 4 ? 'selected' : '') ?>> <?= lang('expert_leader') ?></option>
                                                        </select>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr> <!-- administration row ---->
                                                <td><?= lang('administration') ?></td>
                                                <td><?php
                                                    if (!empty($performance_indicator_details->administration) && $performance_indicator_details->administration == 1) {
                                                        echo lang('beginner');
                                                    } elseif (!empty($performance_indicator_details->administration) && $performance_indicator_details->administration == 2) {
                                                        echo lang('intermediate');
                                                    } elseif (!empty($performance_indicator_details->administration) && $performance_indicator_details->administration == 3) {
                                                        echo lang('advanced');
                                                    } elseif (!empty($performance_indicator_details->administration) && $performance_indicator_details->administration == 4) {
                                                        echo lang('expert_leader');
                                                    } else {
                                                        echo "<span style='color:red;font - style: italic;line - height:2.4;'>" . lang('not_set') . "</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($performance_indicator_details->administration) && $performance_indicator_details->administration != 0) { ?>
                                                        <select name="administration" class="form-control">
                                                            <option value=""><?= lang('none') ?></option>
                                                            <option
                                                                value="1" <?= (!empty($get_appraisal_info->administration) && $get_appraisal_info->administration == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                            <option
                                                                value="2" <?= (!empty($get_appraisal_info->administration) && $get_appraisal_info->administration == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                            <option
                                                                value="3" <?= (!empty($get_appraisal_info->administration) && $get_appraisal_info->administration == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                            <option
                                                                value="4" <?= (!empty($get_appraisal_info->administration) && $get_appraisal_info->administration == 4 ? 'selected' : '') ?>> <?= lang('expert_leader') ?></option>
                                                        </select>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr> <!-- presentation_skill row ---->
                                                <td><?= lang('presentation_skill') ?></td>
                                                <td><?php
                                                    if (!empty($performance_indicator_details->presentation_skill) && $performance_indicator_details->presentation_skill == 1) {
                                                        echo lang('beginner');
                                                    } elseif (!empty($performance_indicator_details->presentation_skill) && $performance_indicator_details->presentation_skill == 2) {
                                                        echo lang('intermediate');
                                                    } elseif (!empty($performance_indicator_details->presentation_skill) && $performance_indicator_details->presentation_skill == 3) {
                                                        echo lang('advanced');
                                                    } elseif (!empty($performance_indicator_details->presentation_skill) && $performance_indicator_details->presentation_skill == 4) {
                                                        echo lang('expert_leader');
                                                    } else {
                                                        echo "<span style='color:red;font - style: italic;line - height:2.4;'>" . lang('not_set') . "</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($performance_indicator_details->presentation_skill) && $performance_indicator_details->presentation_skill != 0) { ?>
                                                        <select name="presentation_skill" class="form-control">
                                                            <option value=""><?= lang('none') ?></option>
                                                            <option
                                                                value="1" <?= (!empty($get_appraisal_info->presentation_skill) && $get_appraisal_info->presentation_skill == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                            <option
                                                                value="2" <?= (!empty($get_appraisal_info->presentation_skill) && $get_appraisal_info->presentation_skill == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                            <option
                                                                value="3" <?= (!empty($get_appraisal_info->presentation_skill) && $get_appraisal_info->presentation_skill == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                            <option
                                                                value="4" <?= (!empty($get_appraisal_info->presentation_skill) && $get_appraisal_info->presentation_skill == 4 ? 'selected' : '') ?>> <?= lang('expert_leader') ?></option>
                                                        </select>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr> <!-- quality_of_work row ---->
                                                <td><?= lang('quality_of_work') ?></td>
                                                <td><?php
                                                    if (!empty($performance_indicator_details->quality_of_work) && $performance_indicator_details->quality_of_work == 1) {
                                                        echo lang('beginner');
                                                    } elseif (!empty($performance_indicator_details->quality_of_work) && $performance_indicator_details->quality_of_work == 2) {
                                                        echo lang('intermediate');
                                                    } elseif (!empty($performance_indicator_details->quality_of_work) && $performance_indicator_details->quality_of_work == 3) {
                                                        echo lang('advanced');
                                                    } elseif (!empty($performance_indicator_details->quality_of_work) && $performance_indicator_details->quality_of_work == 4) {
                                                        echo lang('expert_leader');
                                                    } else {
                                                        echo "<span style='color:red;font - style: italic;line - height:2.4;'>" . lang('not_set') . "</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($performance_indicator_details->quality_of_work) && $performance_indicator_details->quality_of_work != 0) { ?>
                                                        <select name="quality_of_work" class="form-control">
                                                            <option value=""><?= lang('none') ?></option>
                                                            <option
                                                                value="1" <?= (!empty($get_appraisal_info->quality_of_work) && $get_appraisal_info->quality_of_work == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                            <option
                                                                value="2" <?= (!empty($get_appraisal_info->quality_of_work) && $get_appraisal_info->quality_of_work == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                            <option
                                                                value="3" <?= (!empty($get_appraisal_info->quality_of_work) && $get_appraisal_info->quality_of_work == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                            <option
                                                                value="4" <?= (!empty($get_appraisal_info->quality_of_work) && $get_appraisal_info->quality_of_work == 4 ? 'selected' : '') ?>> <?= lang('expert_leader') ?></option>
                                                        </select>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr> <!-- efficiency row ---->
                                                <td><?= lang('efficiency') ?></td>
                                                <td><?php
                                                    if (!empty($performance_indicator_details->efficiency) && $performance_indicator_details->efficiency == 1) {
                                                        echo lang('beginner');
                                                    } elseif (!empty($performance_indicator_details->efficiency) && $performance_indicator_details->efficiency == 2) {
                                                        echo lang('intermediate');
                                                    } elseif (!empty($performance_indicator_details->efficiency) && $performance_indicator_details->efficiency == 3) {
                                                        echo lang('advanced');
                                                    } elseif (!empty($performance_indicator_details->efficiency) && $performance_indicator_details->efficiency == 4) {
                                                        echo lang('expert_leader');
                                                    } else {
                                                        echo "<span style='color:red;font - style: italic;line - height:2.4;'>" . lang('not_set') . "</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($performance_indicator_details->efficiency) && $performance_indicator_details->efficiency != 0) { ?>
                                                        <select name="efficiency" class="form-control">
                                                            <option value=""><?= lang('none') ?></option>
                                                            <option
                                                                value="1" <?= (!empty($get_appraisal_info->efficiency) && $get_appraisal_info->efficiency == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                            <option
                                                                value="2" <?= (!empty($get_appraisal_info->efficiency) && $get_appraisal_info->efficiency == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                            <option
                                                                value="3" <?= (!empty($get_appraisal_info->efficiency) && $get_appraisal_info->efficiency == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                            <option
                                                                value="4" <?= (!empty($get_appraisal_info->efficiency) && $get_appraisal_info->efficiency == 4 ? 'selected' : '') ?>> <?= lang('expert_leader') ?></option>
                                                        </select>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Technical Competency Ends ---->

                        <!-- Behavioural Competency Ends ---->
                        <div class="col-sm-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h4 class="card-title mb-4"><?= lang('behavioural_competency') ?> </h4>
                                    <table class="table table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th><?= lang('performance_indicator') ?></th>
                                            <th><?= lang('expected_value') ?></th>
                                            <th><?= lang('set_value') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <tr> <!-- integrity row ---->
                                            <td><?= lang('integrity') ?></td>
                                            <td><?php
                                                if (!empty($performance_indicator_details->integrity) && $performance_indicator_details->integrity == 1) {
                                                    echo lang('beginner');
                                                } elseif (!empty($performance_indicator_details->integrity) && $performance_indicator_details->integrity == 2) {
                                                    echo lang('intermediate');
                                                } elseif (!empty($performance_indicator_details->integrity) && $performance_indicator_details->integrity == 3) {
                                                    echo lang('advanced');
                                                } else {
                                                    echo "<span style='color:red;font - style: italic;line - height:2.4;'>" . lang('not_set') . "</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($performance_indicator_details->integrity) && $performance_indicator_details->integrity != 0) { ?>
                                                    <select name="integrity" class="form-control">
                                                        <option value=""><?= lang('none') ?></option>
                                                        <option
                                                            value="1" <?= (!empty($get_appraisal_info->integrity) && $get_appraisal_info->integrity == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                        <option
                                                            value="2" <?= (!empty($get_appraisal_info->integrity) && $get_appraisal_info->integrity == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                        <option
                                                            value="3" <?= (!empty($get_appraisal_info->integrity) && $get_appraisal_info->integrity == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                    </select>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <tr> <!-- professionalism row ---->
                                            <td><?= lang('professionalism') ?></td>
                                            <td><?php
                                                if (!empty($performance_indicator_details->professionalism) && $performance_indicator_details->professionalism == 1) {
                                                    echo lang('beginner');
                                                } elseif (!empty($performance_indicator_details->professionalism) && $performance_indicator_details->professionalism == 2) {
                                                    echo lang('intermediate');
                                                } elseif (!empty($performance_indicator_details->professionalism) && $performance_indicator_details->professionalism == 3) {
                                                    echo lang('advanced');
                                                } else {
                                                    echo "<span style='color:red;font - style: italic;line - height:2.4;'>" . lang('not_set') . "</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($performance_indicator_details->professionalism) && $performance_indicator_details->professionalism != 0) { ?>
                                                    <select name="professionalism" class="form-control">
                                                        <option value=""><?= lang('none') ?></option>
                                                        <option
                                                            value="1" <?= (!empty($get_appraisal_info->professionalism) && $get_appraisal_info->professionalism == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                        <option
                                                            value="2" <?= (!empty($get_appraisal_info->professionalism) && $get_appraisal_info->professionalism == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                        <option
                                                            value="3" <?= (!empty($get_appraisal_info->professionalism) && $get_appraisal_info->professionalism == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                    </select>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <tr> <!-- team_work row ---->
                                            <td><?= lang('team_work') ?></td>
                                            <td><?php
                                                if (!empty($performance_indicator_details->team_work) && $performance_indicator_details->team_work == 1) {
                                                    echo lang('beginner');
                                                } elseif (!empty($performance_indicator_details->team_work) && $performance_indicator_details->team_work == 2) {
                                                    echo lang('intermediate');
                                                } elseif (!empty($performance_indicator_details->team_work) && $performance_indicator_details->team_work == 3) {
                                                    echo lang('advanced');
                                                } else {
                                                    echo "<span style='color:red;font - style: italic;line - height:2.4;'>" . lang('not_set') . "</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($performance_indicator_details->team_work) && $performance_indicator_details->team_work != 0) { ?>
                                                    <select name="team_work" class="form-control">
                                                        <option value=""><?= lang('none') ?></option>
                                                        <option
                                                            value="1" <?= (!empty($get_appraisal_info->team_work) && $get_appraisal_info->team_work == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                        <option
                                                            value="2" <?= (!empty($get_appraisal_info->team_work) && $get_appraisal_info->team_work == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                        <option
                                                            value="3" <?= (!empty($get_appraisal_info->team_work) && $get_appraisal_info->team_work == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                    </select>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <tr> <!-- critical_thinking row ---->
                                            <td><?= lang('critical_thinking') ?></td>
                                            <td><?php
                                                if (!empty($performance_indicator_details->critical_thinking) && $performance_indicator_details->critical_thinking == 1) {
                                                    echo lang('beginner');
                                                } elseif (!empty($performance_indicator_details->critical_thinking) && $performance_indicator_details->critical_thinking == 2) {
                                                    echo lang('intermediate');
                                                } elseif (!empty($performance_indicator_details->critical_thinking) && $performance_indicator_details->critical_thinking == 3) {
                                                    echo lang('advanced');
                                                } else {
                                                    echo "<span style='color:red;font - style: italic;line - height:2.4;'>" . lang('not_set') . "</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($performance_indicator_details->critical_thinking) && $performance_indicator_details->critical_thinking != 0) { ?>
                                                    <select name="critical_thinking" class="form-control">
                                                        <option value=""><?= lang('none') ?></option>
                                                        <option
                                                            value="1" <?= (!empty($get_appraisal_info->critical_thinking) && $get_appraisal_info->critical_thinking == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                        <option
                                                            value="2" <?= (!empty($get_appraisal_info->critical_thinking) && $get_appraisal_info->critical_thinking == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                        <option
                                                            value="3" <?= (!empty($get_appraisal_info->critical_thinking) && $get_appraisal_info->critical_thinking == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                    </select>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <tr> <!-- conflict_management row ---->
                                            <td><?= lang('conflict_management') ?></td>
                                            <td><?php
                                                if (!empty($performance_indicator_details->conflict_management) && $performance_indicator_details->conflict_management == 1) {
                                                    echo lang('beginner');
                                                } elseif (!empty($performance_indicator_details->conflict_management) && $performance_indicator_details->conflict_management == 2) {
                                                    echo lang('intermediate');
                                                } elseif (!empty($performance_indicator_details->conflict_management) && $performance_indicator_details->conflict_management == 3) {
                                                    echo lang('advanced');
                                                } else {
                                                    echo "<span style='color:red;font - style: italic;line - height:2.4;'>" . lang('not_set') . "</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($performance_indicator_details->conflict_management) && $performance_indicator_details->conflict_management != 0) { ?>
                                                    <select name="conflict_management" class="form-control">
                                                        <option value=""><?= lang('none') ?></option>
                                                        <option
                                                            value="1" <?= (!empty($get_appraisal_info->conflict_management) && $get_appraisal_info->conflict_management == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                        <option
                                                            value="2" <?= (!empty($get_appraisal_info->conflict_management) && $get_appraisal_info->conflict_management == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                        <option
                                                            value="3" <?= (!empty($get_appraisal_info->conflict_management) && $get_appraisal_info->conflict_management == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                    </select>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <tr> <!-- attendance row ---->
                                            <td><?= lang('attendance') ?></td>
                                            <td><?php
                                                if (!empty($performance_indicator_details->attendance) && $performance_indicator_details->attendance == 1) {
                                                    echo lang('beginner');
                                                } elseif (!empty($performance_indicator_details->attendance) && $performance_indicator_details->attendance == 2) {
                                                    echo lang('intermediate');
                                                } elseif (!empty($performance_indicator_details->attendance) && $performance_indicator_details->attendance == 3) {
                                                    echo lang('advanced');
                                                } else {
                                                    echo "<span style='color:red;font - style: italic;line - height:2.4;'>" . lang('not_set') . "</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($performance_indicator_details->attendance) && $performance_indicator_details->attendance != 0) { ?>
                                                    <select name="attendance" class="form-control">
                                                        <option value=""><?= lang('none') ?></option>
                                                        <option
                                                            value="1" <?= (!empty($get_appraisal_info->attendance) && $get_appraisal_info->attendance == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                        <option
                                                            value="2" <?= (!empty($get_appraisal_info->attendance) && $get_appraisal_info->attendance == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                        <option
                                                            value="3" <?= (!empty($get_appraisal_info->attendance) && $get_appraisal_info->attendance == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                    </select>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <tr> <!-- ability_to_meet_deadline row ---->
                                            <td><?= lang('ability_to_meet_deadline') ?></td>
                                            <td><?php
                                                if (!empty($performance_indicator_details->ability_to_meed_deadline) && $performance_indicator_details->ability_to_meed_deadline == 1) {
                                                    echo lang('beginner');
                                                } elseif (!empty($performance_indicator_details->ability_to_meed_deadline) && $performance_indicator_details->ability_to_meed_deadline == 2) {
                                                    echo lang('intermediate');
                                                } elseif (!empty($performance_indicator_details->ability_to_meed_deadline) && $performance_indicator_details->ability_to_meed_deadline == 3) {
                                                    echo lang('advanced');
                                                } else {
                                                    echo "<span style='color:red;font - style: italic;line - height:2.4;'>" . lang('not_set') . "</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($performance_indicator_details->ability_to_meed_deadline) && $performance_indicator_details->ability_to_meed_deadline != 0) { ?>
                                                    <select name="ability_to_meed_deadline" class="form-control">
                                                        <option value=""><?= lang('none') ?></option>
                                                        <option
                                                            value="1" <?= (!empty($get_appraisal_info->ability_to_meed_deadline) && $get_appraisal_info->ability_to_meed_deadline == 1 ? 'selected' : '') ?>> <?= lang('beginner') ?></option>
                                                        <option
                                                            value="2" <?= (!empty($get_appraisal_info->ability_to_meed_deadline) && $get_appraisal_info->ability_to_meed_deadline == 2 ? 'selected' : '') ?>> <?= lang('intermediate') ?></option>
                                                        <option
                                                            value="3" <?= (!empty($get_appraisal_info->ability_to_meed_deadline) && $get_appraisal_info->ability_to_meed_deadline == 3 ? 'selected' : '') ?>> <?= lang('advanced') ?></option>
                                                    </select>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div> <!-- Behavioural Competency Ends ---->
                        </div>
                        <?php if (!empty($performance_indicator_details)){ ?>
                        <hr/>
                        <div class="col-sm-12"> <!-- General Remarks and Save button --->
                            <?php if (!empty($created) || !empty($edited)) { ?>
                                <div class="col-sm-9">
                                    <div class="row mb-3" id="border-none">
                                        <label class="col-sm-1 col-form-label"
                                               style="padding-top: 14px;"><?= lang('remarks') ?>: </label>
                                        <div class="col-sm-10">
                                        <textarea name="general_remarks" id="elm1"
                                                  class="form-control textarea"><?php if (!empty($get_appraisal_info->general_remarks)) {
                                                echo $get_appraisal_info->general_remarks;
                                            } ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div> <!--- Hidden Inputs ---->
                                <input type="hidden" name="user_id" value="<?php
                                if (!empty($user_id)) {
                                    echo $user_id;
                                } elseif (!empty($get_appraisal_info->user_id)) {
                                    echo $get_appraisal_info->user_id;
                                }
                                ?>">
                                <input type="hidden" name="appraisal_month" value="<?php
                                if (!empty($appraisal_month)) {
                                    echo $appraisal_month;
                                } elseif (!empty($get_appraisal_info->appraisal_month)) {
                                    echo $get_appraisal_info->appraisal_month;
                                }
                                ?>">
                            </div>
                            <?php if (!empty($created) || !empty($edited)) { ?>
                                <div class="col-sm-3 pull-right">
                                    <div class="row mb-3" style="margin-right: -30px;">
                                        <div class="col-sm-12" style="padding-top: 12px;">
                                            <button id="btn_emp" type="submit"
                                                    class="btn btn-primary btn-xs"><?= lang('update') ?></button>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php } else { ?>
                                <div class="text-danger text-center">
                                    <h4><?php echo lang('atleast_one_appraisal'); ?></h4>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php } ?>




