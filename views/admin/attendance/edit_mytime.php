<div class="modal-header">
    <h5 class="modal-title"><?= lang('edit') . ' ' . lang('time_log') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form data-parsley-validate="" novalidate="" action="<?php echo base_url() ?>admin/attendance/cheanged_mytime/<?php echo $clock_info->clock_id ?>" method="post" class="">
    <div class="modal-body wrap-modal wrap"> 
        <div class="row mb-1">
            <div class="col-xl-6 col-lg-6">
                <label class="form-label"><?= lang('old_time_in') ?> </label>
                <div class="input-group">
                    <p class="form-control-static"><?php echo display_time($clock_info->clockin_time); ?></p>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6">
                <label class="form-label"><?= lang('new_time_in') ?> </label>
                <div class="input-group" id="timepicker-input-group1">
                    <input type="text" required name="clockin_edit" class="form-control timepicker1"
                           value="<?php echo display_time($clock_info->clockin_time); ?>">
                    <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
            
                </div>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-xl-6 col-lg-6">
                <label class="form-label"><?= lang('old_time_out') ?> </label>
                <div class="input-group">
                    <p class="form-control-static"><?php echo display_time($clock_info->clockout_time); ?></p>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6">
                <label class="form-label"><?= lang('new_time_out') ?></label>
                <div class="input-group" id="timepicker-input-group2">
                    <input type="text" required name="clockout_edit" class="form-control timepicker2"
                           value="<?php echo display_time($clock_info->clockout_time); ?>">
                    <span class="input-group-text"><i class="fa fa-clock-o"></i></span>

                </div>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-xl-12 center-block">
                <label class="form-label"><?= lang('edit_reason') ?> <span
                        class="required">*</span></label>
                <div>
                    <textarea required class="form-control" name="reason" rows="6"></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <button type="submit" class="btn btn-primary w-md waves-effect waves-light"><?= lang('request') . ' ' . lang('update') ?></button>            
        </div>
    </div>
</form>
