<?php
$error_message = $this->session->userdata('error_message');
$error_type = $this->session->userdata('error_type');
if (!empty($error_message)) {
    foreach ($error_message as $key => $v_message) {
        ?>
        <div class="alert-<?php echo $error_type[$key] ?>"
             style="padding: 8px;margin-bottom: 21px;border: 1px solid transparent;">
            <?php echo $v_message; ?>
        </div>
        <?php
    }
}
$this->session->unset_userdata('error_message');
?>
<div class="card">
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>
    <div class="card-body">
        <h4 class="card-title mb-4"><?php echo lang('email') . ' ' . lang('notification') . ' ' . lang('settings') ?></h4>
                    
        <form id="form" action="<?php echo base_url() ?>admin/settings/save_email_notification"  method="post"  class="form-horizontal form-bordered">
            <div class="row mb-3">
                <label for="field-1"  class="col-lg-3 col-md-5 col-sm-5 form-label"><?= lang('clock') . ' ' . lang('email') ?>
                    : <span class="required">*</span></label>

                <div class="col-lg-5 col-md-5 col-sm-5 form-check form-switch form-switch-md  ml">
                    <input class="form-check-input" name="send_clock_email" value="1" <?php
                    $clock_email = config_item('send_clock_email');
                    if (!empty($clock_email) && $clock_email == 1) {
                        echo 'checked';
                    }
                    ?> type="checkbox">
                </div>
            </div>
            <div class="row mb-3">
                <label for="field-1"
                       class="col-lg-3 col-md-5 col-sm-5 form-label"><?= lang('leave') . ' ' . lang('email') ?>
                    : <span
                            class="required">*</span></label>

                <div class="col-lg-5 col-md-5 col-sm-5 form-check form-switch form-switch-md  ml">
                    <input class="form-check-input" name="leave_email" value="1" <?php
                    $leave_email = config_item('leave_email');
                    if (!empty($leave_email) && $leave_email == 1) {
                        echo 'checked';
                    }
                    ?> type="checkbox">
                </div>
            </div>
            <div class="row mb-3">
                <label for="field-1" class="col-lg-3 col-md-5 col-sm-5 form-label"><?= lang('overtime') . ' ' . lang('email') ?>  : <span  class="required">*</span></label>

                <div class="col-lg-5 col-md-5 col-sm-5 form-check form-switch form-switch-md  ml">
                    <input class="form-check-input" name="overtime_email" value="1" <?php

                    $overtime_email = config_item('overtime_email');
                    if (!empty($overtime_email) && $overtime_email == 1) {
                        echo 'checked';
                    }
                    ?> type="checkbox">
                </div>
            </div>
            <div class="row mb-3">
                <label for="field-1"  class="col-lg-3 col-md-5 col-sm-5 form-label"><?= lang('payslip') . ' ' . lang('email') ?>
                    : <span class="required">*</span></label>

                <div class="col-lg-5 col-md-5 col-sm-5 form-check form-switch form-switch-md  ml">
                    <input class="form-check-input" name="payslip_email" value="1" <?php

                    $payslip_email = config_item('payslip_email');
                    if (!empty($payslip_email) && $payslip_email == 1) {
                        echo 'checked';
                    }
                    ?> type="checkbox">
                </div>
            </div>
            <div class="row mb-3">
                <label for="field-1" class="col-lg-3 col-md-5 col-sm-5 form-label"><?= lang('advance_salary') . ' ' . lang('email') ?> : <span  class="required">*</span></label>

                <div class="col-lg-5 col-md-5 col-sm-5 form-check form-switch form-switch-md  ml">
                    <input class="form-check-input" name="advance_salary_email" value="1" <?php

                    $advance_salary_email = config_item('advance_salary_email');
                    if (!empty($advance_salary_email) && $advance_salary_email == 1) {
                        echo 'checked';
                    }
                    ?> type="checkbox">
                </div>
            </div>
            <div class="row mb-3">
                <label  class="col-lg-3 col-md-5 col-sm-5 form-label"><?= lang('award') . ' ' . lang('email') ?> : <span  class="required">*</span></label>

                <div class="col-lg-5 col-md-5 col-sm-5 form-check form-switch form-switch-md  ml">
                    <input class="form-check-input" name="award_email" value="1" 
                        <?php

                        $award_email = config_item('award_email');
                        if (!empty($award_email) && $award_email == 1) {
                            echo 'checked';
                        }
                        ?> type="checkbox">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-md-5 col-sm-5 form-label"><?= lang('job_circular') . ' ' . lang('email') ?> : <span class="required">*</span></label>

                <div class="col-lg-5 col-md-5 col-sm-5 form-check form-switch form-switch-md  ml">
                    <input class="form-check-input" name="job_circular_email" value="1" 
                        <?php

                    $job_circular_email = config_item('job_circular_email');
                    if (!empty($job_circular_email) && $job_circular_email == 1) {
                        echo 'checked';
                    }
                    ?> type="checkbox">
                </div>

            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-md-5 col-sm-5 form-label"><?= lang('announcements') . ' ' . lang('email') ?>
                    : <span
                            class="required">*</span></label>

                <div class="col-lg-5 col-md-5 col-sm-5 form-check form-switch form-switch-md  ml">
                    <input class="form-check-input" name="announcements_email" value="1" 
                        <?php

                    $announcements_email = config_item('announcements_email');
                    if (!empty($announcements_email) && $announcements_email == 1) {
                        echo 'checked';
                    }
                    ?> type="checkbox">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-md-5 col-sm-5 form-label"><?= lang('training') . ' ' . lang('email') ?>  : <span  class="required">*</span></label>

                <div class="col-lg-5 col-md-5 col-sm-5 form-check form-switch form-switch-md  ml">
                    <input class="form-check-input" name="training_email" value="1" 
                        <?php

                    $training_email = config_item('training_email');
                    if (!empty($training_email) && $training_email == 1) {
                        echo 'checked';
                    }
                    ?> type="checkbox">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-md-5 col-sm-5 form-label"><?= lang('expense') . ' ' . lang('email') ?>  : <span  class="required">*</span></label>

                <div class="col-lg-5 col-md-5 col-sm-5 form-check form-switch form-switch-md  ml">
                    <input class="form-check-input" name="expense_email" value="1" 
                        <?php

                    $expense_email = config_item('expense_email');
                    if (!empty($expense_email) && $expense_email == 1) {
                        echo 'checked';
                    }
                    ?> type="checkbox">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-md-5 col-sm-5 form-label"><?= lang('deposit') . ' ' . lang('email') ?> : <span class="required">*</span></label>

                <div class="col-lg-5 col-md-5 col-sm-5 form-check form-switch form-switch-md  ml">
                    <input class="form-check-input" name="deposit_email" value="1" 
                        <?php

                    $deposit_email = config_item('deposit_email');
                    if (!empty($deposit_email) && $deposit_email == 1) {
                        echo 'checked';
                    }
                    ?> type="checkbox">
                </div>
            </div>

            <div class="row mb-3">
                <label for="field-1" class="col-lg-3 col-md-5 col-sm-5 form-label"></label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <button type="submit" id="sbtn" class="btn btn-primary"><?= lang('update') ?></button>
                </div>
            </div>
        </form>
    </div>
</div>