<div class="modal-header">
    <h5 class="modal-title"><?= lang('ban_reason') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<?php echo form_open(base_url('admin/companies/set_banned/' . $flag . '/' . $companies_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
    <div class="modal-body wrap-modal wrap">    
        <div class="row mb-3">
            <div class="col-sm-12">
                <textarea type="text" name="ban_reason" value="" required="" rows="5" class="form-control"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <button type="submit" class="btn btn-primary w-md waves-effect waves-light"><?= lang('save') ?></button>            
        </div>
    </div>
<?php echo form_close(); ?>

<script type="text/javascript">
$(document).ready(function () {
    $("#ban_reason").validate({
        rules: {
            ban_reason: {
                required: true,
            }
        }
    });
});
</script>