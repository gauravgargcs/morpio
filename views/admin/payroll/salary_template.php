<?= message_box('success'); ?>
<?= message_box('error'); ?>
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
$created = can_action('94', 'created');
$edited = can_action('94', 'edited');
$deleted = can_action('94', 'deleted');
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 1 ? 'active' : ''; ?>" href="#manage" data-bs-toggle="tab"><?= lang('salary_template_list') ?></a></li>
                    <?php if (!empty($created) || !empty($edited)) { ?>
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 2 ? 'active' : ''; ?>" href="#create" data-bs-toggle="tab"><?= lang('new_template') ?></a></li>
                    <?php } ?>
                </ul>
                <div class="tab-content p-3 text-muted">
                    <!-- Stock Category List tab Starts -->
                    <div class="tab-pane <?= $active == 1 ? 'active' : '' ?>" id="manage">
                        <h4 class="card-title mb-3"><?= lang('salary_template_list') ?></h4>
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100" id="list_slry_templt_datatable">
                                <thead>
                                    <tr>
                                        <th class="col-sm-1"><?= lang('sl') ?></th>
                                        <?php super_admin_opt_th() ?>
                                        <th><?= lang('salary_grade') ?></th>
                                        <th><?= lang('basic_salary') ?></th>
                                        <th><?= lang('overtime') ?>
                                            <small>(<?= lang('per_hour') ?>)</small>
                                        </th>
                                        <th class="col-sm-2"><?= lang('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php $key = 1; ?>
                                        <?php if (!empty($all_salary_template)): foreach ($all_salary_template as $v_salary_info): ?>
                                            <tr>
                                                <td><?php echo $key; ?></td>
                                                <?php super_admin_opt_td($v_salary_info->companies_id) ?>
                                                <td>
                                                    <a href="<?= base_url() ?>admin/payroll/salary_template_details/<?= $v_salary_info->salary_template_id ?>"
                                                       title="<?= lang('view') ?>" data-bs-toggle="modal"
                                                       data-bs-target="#myModal_lg">
                                                        <?php echo $v_salary_info->salary_grade; ?>
                                                    </a>
                                                </td>
                                                <td><?php
                                                    $curency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                                                    echo display_money($v_salary_info->basic_salary, $curency->symbol); ?></td>
                                                <td><?php
                                                    if (!empty($v_salary_info->overtime_salary)) {
                                                        echo display_money($v_salary_info->overtime_salary, $curency->symbol);
                                                    }
                                                    ?></td>
                                                <td>
                                                    <?php echo btn_view_modal('admin/payroll/salary_template_details/' . $v_salary_info->salary_template_id); ?>
                                                    <?php if (!empty($edited)) { ?>
                                                        <?php echo btn_edit('admin/payroll/salary_template/' . $v_salary_info->salary_template_id); ?>
                                                    <?php }
                                                    if (!empty($deleted)) { ?>
                                                        <?php echo btn_delete('admin/payroll/delete_salary_template/' . $v_salary_info->salary_template_id); ?>
                                                    <?php } ?>
                                                </td>
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
                    <?php if (!empty($created) || !empty($edited)) {
                        if (!empty($salary_template_info)) {
                            $salary_template_id = $salary_template_info->salary_template_id;
                            $companies_id = $salary_template_info->companies_id;
                        } else {
                            $salary_template_id = null;
                            $companies_id = null;
                        }
                        ?>
                        <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                            <?php echo form_open(base_url('admin/payroll/set_salary_details/' . $salary_template_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                            <div class="row">
                                <?php super_admin_form($companies_id, 3, 5) ?>
                                <div class="row mb-3" id="border-none">
                                    <label for="field-1" class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('salary_grade') ?><span class="required"> *</span></label>
                                    <div class="col-lg-5 col-md-5 col-sm-5">
                                        <input type="text" name="salary_grade" value="<?php
                                        if (!empty($salary_template_info->salary_grade)) {
                                            echo $salary_template_info->salary_grade;
                                        }
                                        ?>" class="form-control" required  placeholder="<?= lang('enter') . ' ' . lang('salary_grade') ?>">
                                    </div>
                                </div>
                                <div class="row mb-3" id="border-none">
                                    <label for="field-1" class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('basic_salary') ?><span
                                            class="required"> *</span></label>
                                    <div class="col-lg-5 col-md-5 col-sm-5">
                                        <input type="text" data-parsley-type="number" name="basic_salary" value="<?php
                                        if (!empty($salary_template_info->basic_salary)) {
                                            echo $salary_template_info->basic_salary;
                                        }
                                        ?>" class="salary form-control" required  placeholder="<?= lang('enter') . ' ' . lang('basic_salary') ?>">
                                    </div>
                                </div>
                                <div class="row mb-3" id="border-none">
                                    <label for="field-1" class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('overtime_rate') ?>
                                        <small> ( <?= lang('per_hour') ?>)</small>
                                    </label>
                                    <div class="col-lg-5 col-md-5 col-sm-5">
                                        <input type="text" data-parsley-type="number" name="overtime_salary" value="<?php
                                        if (!empty($salary_template_info->overtime_salary)) {
                                            echo $salary_template_info->overtime_salary;
                                        }
                                        ?>" class="form-control"
                                               placeholder="<?= lang('enter') . ' ' . lang('overtime_rate') ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title mb-4"><?= lang('allowances') ?></h4> 
                                            <?php
                                            $total_salary = 0;
                                            if (!empty($salary_allowance_info)):foreach ($salary_allowance_info as $v_allowance_info):  ?>
                                                <div class="mb-3">
                                                    <input type="text" style="margin:5px 0px;height: 28px;width: 56%;" class="form-control" name="allowance_label[]"  value="<?php echo $v_allowance_info->allowance_label; ?>" class="">
                                                    <input type="text" data-parsley-type="number" name="allowance_value[]" value="<?php echo $v_allowance_info->allowance_value; ?>" class="salary form-control">
                                                    <input type="hidden" name="salary_allowance_id[]"  value="<?php echo $v_allowance_info->salary_allowance_id; ?>"  class="salary form-control">
                                                </div>
                                                <?php $total_salary += $v_allowance_info->allowance_value; ?>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="mb-3">
                                                    <label class="col-form-label"><?= lang('house_rent_allowance') ?> </label>
                                                    <input type="text" data-parsley-type="number" name="house_rent_allowance"
                                                           value=""
                                                           class="salary form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="col-form-label"><?= lang('medical_allowance') ?> </label>
                                                    <input type="text" data-parsley-type="number" name="medical_allowance"
                                                           value=""
                                                           class="salary form-control">
                                                </div>
                                            <?php endif; ?>
                                            <div id="add_new">
                                            </div>
                                            <div class="margin">
                                                <strong><a href="javascript:void(0);" id="add_more" class="addCF "><i
                                                            class="fa fa-plus"></i>&nbsp;<?= lang('add_more') ?></a></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- ********************Allowance End ******************-->

                                <!-- ************** Deduction Panel Column  **************-->
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title mb-4"><?= lang('deductions') ?></h4>
                                            <?php
                                            $total_deduction = 0;
                                            if (!empty($salary_deduction_info)):foreach ($salary_deduction_info as $v_deduction_info):
                                                ?>
                                                <div class="mb-3">
                                                    <input type="text" style="margin:5px 0px;height: 28px;width: 56%;"
                                                           class="form-control" name="deduction_label[]"
                                                           value="<?php echo $v_deduction_info->deduction_label; ?>" class="">
                                                    <input type="text" data-parsley-type="number" name="deduction_value[]"
                                                           value="<?php echo $v_deduction_info->deduction_value; ?>"
                                                           class="deduction form-control">
                                                    <input type="hidden" name="salary_deduction_id[]"
                                                           value="<?php echo $v_deduction_info->salary_deduction_id; ?>"
                                                           class="deduction form-control">
                                                </div>
                                                <?php $total_deduction += $v_deduction_info->deduction_value ?>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="mb-3">
                                                    <label class="col-form-label"><?= lang('provident_fund') ?> </label>
                                                    <input type="text" data-parsley-type="number" name="provident_fund" value=""
                                                           class="deduction form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="col-form-label"><?= lang('tax_deduction') ?> </label>
                                                    <input type="text" data-parsley-type="number" name="tax_deduction" value=""
                                                           class="deduction form-control">
                                                </div>
                                            <?php endif; ?>
                                            <div id="add_new_deduc">
                                            </div>
                                            <div class="margin">
                                                <strong><a href="javascript:void(0);" id="add_more_deduc" class="addCF "><i
                                                            class="fa fa-plus"></i>&nbsp;<?= lang('add_more') ?></a></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- ****************** Deduction End  *******************-->
                                <!-- ************** Total Salary Details Start  **************-->
                            </div>
                            <div class="row">
                                <div class="col-md-8 pull-right">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title mb-4"><?= lang('total_salary_details') ?></h4>
                                            <table class="table table-striped dt-responsive nowrap w-100 custom-table">
                                                <tr><!-- Sub total -->
                                                    <th class="col-sm-8 vertical-td"><strong><?= lang('gross_salary') ?> :</strong>
                                                    </th>
                                                    <td class="">
                                                        <input type="text" name="" disabled value="<?php
                                                        if (!empty($total_salary) || !empty($salary_template_info->basic_salary)) { echo $total = $total_salary + $salary_template_info->basic_salary; } ?>" id="total" class="form-control">
                                                    </td>
                                                </tr> <!-- / Sub total -->
                                                <tr><!-- Total tax -->
                                                    <th class="col-sm-8 vertical-td"><strong><?= lang('total_deduction') ?>  :</strong></th>
                                                    <td class="">
                                                        <input type="text" name="" disabled value="<?php if (!empty($total_deduction)) { echo $total_deduction;  } ?>" id="deduc" class="form-control">
                                                    </td>
                                                </tr><!-- / Total tax -->
                                                <tr><!-- Grand Total -->
                                                    <th class="col-sm-8 vertical-td"><strong><?= lang('net_salary') ?> :</strong>
                                                    </th>
                                                    <td class="">
                                                        <input type="text" name="" disabled required value="<?php if (!empty($total) || !empty($total_deduction)) {  echo $total - $total_deduction;  } ?>" id="net_salary" class="form-control">
                                                    </td>
                                                </tr><!-- Grand Total -->
                                            </table><!-- Order Total table list start -->
                                        </div>
                                    </div>
                                </div><!-- ****************** Total Salary Details End  *******************-->
                                <div class="col-sm-6 margin pull-right">
                                    <button type="submit" class="btn btn-primary btn-block"><?= lang('save') ?></button>
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
<script type="text/javascript">
    $(document).ready(function () {
        var maxAppend = 0;
        $("#add_more").click(function () {
            if (maxAppend >= 100) {
                alert("Maximum 100 File is allowed");
            } else {
                var add_new = $('<div class="mb-3">\n\
                <input type="text" name="allowance_label[]" style="" class="form-control"  placeholder="<?= lang('enter') . ' ' . lang('allowances') . ' ' . lang('label')?>" required ></div>\n\
            <div class="row mb-3"><div class="col-sm-9"><input  type="text" data-parsley-type="number" name="allowance_value[]" placeholder="<?= lang('enter') . ' ' . lang('allowances') . ' ' . lang('value')?>" required  value="<?php if (!empty($emp_salary->allowance_value)) { echo $emp_salary->allowance_value; }  ?>"  class="salary form-control"></div>\n\
            <div class="col-sm-3"><strong><a href="javascript:void(0);" class="remCF"><i class="fa fa-times"></i>&nbsp;Remove</a></strong></div></div>');
                maxAppend++;
                $("#add_new").append(add_new);
            }
        });

        $("#add_new").on('click', '.remCF', function () {
            $(this).parent().parent().parent().remove();
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        var maxAppend = 0;
        $("#add_more_deduc").click(function () {
            if (maxAppend >= 100) {
                alert("Maximum 100 File is allowed");
            } else {
                var add_new = $('<div class="mb-3">\n\<input type="text" name="deduction_label[]" style="" class="form-control" placeholder="<?= lang('enter') . ' ' . lang('deductions') . ' ' . lang('label')?>" required></div>\n\
<div class="row mb-3"><div class="col-sm-9"><input  type="text" data-parsley-type="number" name="deduction_value[]" placeholder="<?= lang('enter') . ' ' . lang('deductions') . ' ' . lang('value')?>" required  value="<?php if (!empty($emp_salary->other_deduction)) {  echo $emp_salary->other_deduction;  } ?>"  class="deduction form-control"></div>\n\
<div class="col-sm-3"><strong><a href="javascript:void(0);" class="remCF_deduc"><i class="fa fa-times"></i>&nbsp;Remove</a></strong></div></div>');
                maxAppend++;
                $("#add_new_deduc").append(add_new);
            }
        });

        $("#add_new_deduc").on('click', '.remCF_deduc', function () {
            $(this).parent().parent().parent().remove();
        });
    });
</script>
<script type="text/javascript">
    $(document).on("change", function () {
        var sum = 0;
        var deduc = 0;
        $(".salary").each(function () {
            sum += +$(this).val();
        });

        $(".deduction").each(function () {
            deduc += +$(this).val();
        });
        var ctc = $("#ctc").val();

        $("#total").val(sum);
        $("#deduc").val(deduc);
        var net_salary = 0;
        net_salary = sum - deduc;
        $("#net_salary").val(net_salary);
    });
</script>

