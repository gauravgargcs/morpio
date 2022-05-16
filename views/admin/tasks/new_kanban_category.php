<?php //  include_once 'asset/admin-ajax.php'; ?>
<?php  echo message_box('success'); ?>
<?php echo message_box('error'); ?>

<form action="<?php echo base_url() ?>admin/tasks/save_kanban_category/<?php if (!empty($kanban_category_info->task_kanban_category_id)) { echo $kanban_category_info->task_kanban_category_id; } ?>" method="post">
    <div class="modal-header">
        <h5 class="modal-title"><?php if (!empty($kanban_category_info)) { echo lang('edit').' '.lang('kanban_category'); }else{ echo lang('add').' '.lang('kanban_category'); } ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-xl-9">
               <div class="mb-3">
                    <label class="form-label"><?= lang('name'); ?><span class="required">*</span></label>
                    <input type="text" name="kanban_category_name" class="form-control" required value="<?php if (!empty($kanban_category_info->kanban_category_name)) { echo $kanban_category_info->kanban_category_name; } ?>">
                </div>
            </div>
            <div class="col-xl-3">
               <div class="mb-3">
                    <label class="form-label"><?= lang('order_no'); ?><span class="required">*</span></label>
                    <input type="number" name="order_no" class="form-control" required value="<?php if (!empty($kanban_category_info->order_no)) { echo $kanban_category_info->order_no; } ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="mb-3">
            <div class="col-sm-offset-3 col-xl-12 col-sm-12">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"><?= lang('cancel') ?></button>
                <button type="submit" id="sbtn" name="sbtn" value="1" class="btn btn-primary"><?= lang('save') ?></button>
            </div>
        </div>
    </div>
</form>