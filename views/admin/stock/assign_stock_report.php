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
                <h4 class="card-title mb-4"><?= lang('assign_stock_report') ?></h4>
                
                <form id="form" action="<?php echo base_url() ?>admin/stock/assign_stock_report/<?php if (!empty($expense_category_info->expense_category_id)) { echo $expense_category_info->expense_category_id; } ?>" method="post" class="form-horizontal">
                    <div class="row mb-3">
                        <label for="field-1" class="col-sm-3 col-form-label"><?= lang('select_employee') ?><span
                                class="required">*</span></label>

                        <div class="col-sm-5">
                            <select class="form-control select_box" name="user_id">
                                <option value=""><?= lang('select_employee') ?></option>
                                <?php if (!empty($all_employee)): ?>
                                    <?php foreach ($all_employee as $dept_name => $v_all_employee) : ?>
                                        <optgroup label="<?php echo $dept_name; ?>">
                                            <?php if (!empty($v_all_employee)):foreach ($v_all_employee as $v_employee) : ?>
                                                <option value="<?php echo $v_employee->user_id; ?>"
                                                    <?php
                                                    if (!empty($employee_info->user_id)) {
                                                        echo $v_employee->user_id == $employee_info->user_id ? 'selected' : '';
                                                    }
                                                    ?>><?php echo $v_employee->fullname . ' ( ' . $v_employee->designations . ' )' ?></option>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </optgroup>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" id="sbtn" value="1" name="flag" class="btn btn-primary"><?= lang('go') ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br/>
        <?php if (!empty($flag)): ?>
        <div class="card">
            <div class="card-body">
                <div class="pull-right hidden-print float-end">
                    <span><?php echo btn_pdf('admin/stock/assign_stock_pdf/' . $employee_info->user_id); ?></span>
                </div>
                <h4 class="card-title mb-4">
                    <?php
                    if (!empty($employee_info)) {
                        echo $employee_info->fullname . ' ( ' . $employee_info->employment_id . ' )';
                    }
                    ?>    
                </h4>  
                <?php if (!empty($assign_list)): foreach ($assign_list as $sub_category => $v_assign_list) : ?>
                    <?php if (!empty($v_assign_list)): ?>
                        <div class="card-body">
                            <h4 class="card-title mb-4" style="border-bottom: 1px solid #a0a0a0;padding-bottom:5px;">
                                <?php echo $sub_category ?>
                            </h4>
                            <table class="table table-striped dt-responsive nowrap w-100" id="assign_stock_report_datatable">
                                <thead>
                                    <tr>
                                        <th class="col-sm-1"><?= lang('sl') ?></th>
                                        <?php super_admin_opt_th() ?>
                                        <th><?= lang('item_name') ?></th>
                                        <th><?= lang('assign_quantity') ?></th>
                                        <th><?= lang('assign_date') ?></th>
                                        <th class="col-sm-1 hidden-print"><?= lang('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($v_assign_list as $key => $v_assign_stock) : ?>
                                        <tr id="table_assign_stock_<?= $v_assign_stock->assign_item_id ?>">
                                            <td><?php echo $key + 1 ?></td>
                                            <?php super_admin_opt_td($v_assign_stock->companies_id) ?>
                                            <td><?php echo $v_assign_stock->item_name ?></td>
                                            <td><?php echo $v_assign_stock->assign_inventory ?></td>
                                            <td><?= display_datetime($v_assign_stock->assign_date); ?></td>
                                            <td class="hidden-print">
                                                <?php echo ajax_anchor(base_url("admin/stock/delete_assign_stock/" . $v_assign_stock->assign_item_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_assign_stock_" . $v_assign_stock->assign_item_id)); ?>
                                            </td>

                                        </tr>
                                        <?php
                                    endforeach;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php else : ?>
                    <div class="row">
                        <div class="col-lg-12" data-offset="0">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-center text-sm font-size-18"><?= lang('nothing_to_display') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>


