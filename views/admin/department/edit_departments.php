<div class="modal-header">
    <h5 class="modal-title"><?= lang('edit') . ' ' . lang('departments') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<?php
$created = can_action('70', 'created');
$edited = can_action('70', 'edited');
if (!empty($created) || !empty($edited)) {
    if (!empty($department_info)) {
        $departments_id = $department_info->departments_id;
        $companies_id = $department_info->companies_id;
    } else {
        $departments_id = null;
        $companies_id = null;
    }
    ?>
    <?php echo form_open(base_url('admin/departments/edit_departments/' . $departments_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
        <div class="modal-body wrap-modal wrap">    
            <div class="row">
                <div class="col-sm-12">
                    <?php super_admin_form_modal($companies_id, 4, 8) ?>
                    <div class="row mb-3" id="border-none">
                        <label for="field-1" class="col-sm-4 col-form-label"><?= lang('edit') . ' ' . lang('departments') ?>
                            <span class="required">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" name="deptname" required class="form-control" value="<?= (!empty($department_info->deptname) ? $department_info->deptname : '') ?>"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="mb-3">
                <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
                <button type="submit" class="btn btn-primary w-md waves-effect waves-light"><?= lang('update') ?></button>            
            </div>
        </div>
    <?php echo form_close(); ?>
<?php } ?>
