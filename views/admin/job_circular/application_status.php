<form id="form" role="form" enctype="multipart/form-data" action="<?php echo base_url() ?>admin/job_circular/change_application_status/<?php
  if (!empty($job_application_info->job_appliactions_id)) {
      echo $job_application_info->job_appliactions_id;
  }
  ?>" method="post" class="form-horizontal">
    <div class="modal-header">
        <h5 class="modal-title"><?= lang('change_status') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body wrap-modal wrap">
        <div class="row mb-3" id="border-none">
            <label for="field-1" class="col-sm-3 col-form-label"><?= lang('status') ?><span
                        class="required"> *</span></label>
            <div class="col-sm-5">
                <select class="form-select" id="job_applicarion_status" name="status">
                    <option
                            value="0" <?= ($job_application_info->application_status == 0 ? 'selected' : '') ?> ><?= lang('unread') ?></option>
                    <option
                            value="2" <?= ($job_application_info->application_status == 2 ? 'selected' : '') ?>><?= lang('primary_selected') ?></option>
                    <option
                            value="3" <?= ($job_application_info->application_status == 3 ? 'selected' : '') ?>><?= lang('call_for_interview') ?></option>
                    <option
                            value="1" <?= ($job_application_info->application_status == 1 ? 'selected' : '') ?>><?= lang('approved') ?></option>
                    <option
                            value="4" <?= ($job_application_info->application_status == 4 ? 'selected' : '') ?>><?= lang('rejected') ?></option>
                </select>
            </div>
        </div>
        <input type="hidden" name="flag" value="1"/>
        <div class="row mb-3 send_email" style="display: <?= ($job_application_info->application_status == 3 ? 'block' : 'none') ?>">
            <label for="field-1" class="col-sm-3 col-form-label"><?= lang('send_email') ?></label>
            <div class="col-sm-5">
                <div class="form-check form-check-primary mb-3">
                    <input <?= (!empty($job_application_info->send_email) && $job_application_info->send_email == 'Yes' ? 'checked' : ''); ?> class="select_one form-check-input" type="checkbox" name="send_email" value="Yes">
                </div>
            </div>
        </div>
        <div class="row mb-3 send_email" style="display: <?= ($job_application_info->application_status == 3 ? 'block' : 'none') ?>">
            <label class="col-form-label col-sm-3"><?= lang('interview_date') ?><span class="required">*</span></label>
            <div class="col-sm-5">
                <div class="input-group">
                    <input type="text" name="interview_date" value="<?php
                    if (!empty($job_application_info->interview_date)) {
                        echo date('d-m-Y H-i', strtotime($job_application_info->interview_date));
                    } else {
                        date('d-m-Y H-i');
                    }
                    ?>" class="form-control datepicker" required>
                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
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
</form>
<script type="text/javascript">
    $(document).ready(function () {
        $("select[name='status']").change(function () {
            if ($("select[name='status']").val() == 3) {
                $('.send_email').show();
                $(".send_email").removeAttr('disabled');
                $("input[name='interview_date']").attr('required', true);
            } else {
                $('.send_email').hide();
                $(".send_email").attr('disabled', 'disabled');
                $("input[name='interview_date']").removeAttr('required');
            }
        });
    });
</script>


