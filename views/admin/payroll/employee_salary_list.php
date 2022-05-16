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
                <h4 class="card-title mb-4"><?= lang('employee_salary_details') ?></h4>
       
                <!-- Table -->
                <table class="table table-striped dt-responsive nowrap w-100" id="emp_slry_list" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="col-sm-1"><?= lang('emp_id') ?></th>
                        <th><?= lang('name') ?></th>
                        <th><?= lang('salary_type') ?></th>
                        <th><?= lang('basic_salary') ?></th>
                        <th><?= lang('overtime') ?>
                            <small>(<?= lang('per_hour') ?>)</small>
                        </th>
                        <th class="col-sm-1"><?= lang('details') ?></th>
                        <th><?= lang('action') ?></th>

                    </tr>
                    </thead>
                    <tbody>
                        <?php

                        if (!empty($emp_salary_info)):foreach ($emp_salary_info as $v_emp_salary):
                            ?>
                            <tr>
                                <td><?php echo $v_emp_salary->employment_id; ?></td>
                                <td>
                                    <?php
                                    if (!empty($v_emp_salary->salary_grade)) {
                                        ?>
                                        <a href="<?= base_url() ?>admin/payroll/view_salary_details/<?= $v_emp_salary->salary_template_id ?>/<?= $v_emp_salary->user_id ?>"
                                           title="<?= lang('view') ?>" data-bs-toggle="modal" data-bs-target="#myModal_lg">
                                            <?php echo $v_emp_salary->fullname ?>
                                        </a>
                                    <?php } else {
                                        echo $v_emp_salary->fullname;
                                    } ?>
                                </td>
                                <td><?php
                                    if (!empty($v_emp_salary->salary_grade)) {
                                        echo $v_emp_salary->salary_grade . ' <small>(' . lang("monthly") . ')</small>';
                                    } else {
                                        echo $v_emp_salary->hourly_grade . ' <small>(' . lang("hourly") . ')</small>';
                                    }
                                    ?></td>
                                <td><?php
                                    $curency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                                    if (!empty($v_emp_salary->basic_salary)) {
                                        echo display_money($v_emp_salary->basic_salary, $curency->symbol);
                                    } else {
                                        echo display_money($v_emp_salary->hourly_rate, $curency->symbol) . ' <small>(' . lang("per_hour") . ')</small>';
                                    }
                                    ?></td>
                                <td><?php
                                    if (!empty($v_emp_salary->overtime_salary)) {
                                        echo display_money($v_emp_salary->overtime_salary, $curency->symbol);
                                    }
                                    ?></td>


                                <td>
                                    <?php
                                    if (!empty($v_emp_salary->salary_grade)) {
                                    ?>
                                    <?php echo btn_view_modal('admin/payroll/view_salary_details/' . $v_emp_salary->salary_template_id . '/' . $v_emp_salary->user_id); ?></td>
                                <?php } ?>
                                <td>
                                    <?php echo btn_edit('admin/payroll/manage_salary_details/' . $v_emp_salary->departments_id); ?>
                                    <?php if ($this->session->userdata('user_type') == '1') { ?>
                                        <?php echo btn_delete('admin/payroll/delete_salary/' . $v_emp_salary->payroll_id); ?>
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

