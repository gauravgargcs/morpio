<style type="text/css">
    #myModal {
        z-index: 2000 !important;
    }
</style>
<?php
if ($status == '1') {
    $text = lang('pending');
} elseif ($status == '2') {
    $approved = true;
    $text = lang('approved');
} else {
    $status = 3;
    $text = lang('rejected');
}
?>
<div class="modal-header">
    <h5 class="modal-title"><?= lang('change') . ' ' . lang('status') . ' ' . lang('leave_to') . ' ' . $text ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="form_validation"  action="<?php echo base_url() ?>admin/leave_management/set_action/<?php if (!empty($application_info->leave_application_id)) echo $application_info->leave_application_id; ?>" method="post" class="form-horizontal">        
    <div class="modal-body wrap-modal wrap">
        <div class="row mb-3">
            <label for="field-1" class="col-sm-3 col-form-label row"><?= lang('give_comment') ?>: </label>

            <div class="col-sm-8">
                <textarea class="form-control" name="comment"><?php echo $application_info->comments; ?></textarea>

            </div>
            <!-- Hidden Input ---->
            <input type="hidden" name="application_status"
                   value="<?php echo $status ?>">
            <input type="hidden" name="approve_by" value="<?php echo $this->session->userdata('user_id') ?>">
            <input type="hidden" name="user_id" value="<?php echo $application_info->user_id; ?>">
            <input type="hidden" name="leave_category_id"
                   value="<?php echo $application_info->leave_category_id; ?>">
            <input type="hidden" name="leave_start_date" value="<?php echo $application_info->leave_start_date; ?>">
            <?php
            if (empty($application_info->leave_end_date)) {
                $application_info->leave_end_date = $application_info->leave_start_date;
            }
            ?>
            <input type="hidden" name="leave_end_date" value="<?php echo $application_info->leave_end_date; ?>">
        </div>
    </div>
    <div class="modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <button type="submit" <?php if (!empty($approved)) {  ?> onclick="return confirm('<?= lang('delete_leave_alert') ?>')" <?php } ?> class="btn btn-primary w-md waves-effect waves-light"><?= lang('update') ?> </button>           
        </div>
    </div>
</form>