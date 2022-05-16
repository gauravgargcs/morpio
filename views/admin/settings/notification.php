<?php $realtime_notification = config_item('realtime_notification'); ?>
<div class="card">
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>
    <div class="card-body">
        <h4 class="card-title mb-4">Notification Settings</h4>
        <div class="row">        
            <form id="form" action="<?php echo base_url() ?>admin/settings/set_noticifation" method="post" class="form-horizontal col-lg-8">

                <div class="row mb-3">
                    <label class="col-lg-6 col-md-6 col-sm-6 col-form-label"><?= lang('pusher_app_id') ?></label>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <input type="text" class="form-control"
                               value="<?= config_item('pusher_app_id') ?>"
                               name="pusher_app_id">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-lg-6 col-md-6 col-sm-6 col-form-label"><?= lang('pusher_app_key') ?></label>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <input type="text" class="form-control"
                               value="<?= config_item('pusher_app_key') ?>"
                               name="pusher_app_key">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-6 col-md-6 col-sm-6 col-form-label"><?= lang('pusher_app_secret') ?></label>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <input type="text" class="form-control"
                               value="<?= config_item('pusher_app_secret') ?>"
                               name="pusher_app_secret">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-6 col-md-6 col-sm-6 col-form-label"><?= lang('pusher_cluster') ?></label>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <input type="text" class="form-control"
                               value="<?= config_item('pusher_cluster') ?>"
                               name="pusher_cluster">
                    </div>
                </div>

                <div class="row mb-3">
                    <label
                        class="col-lg-6 col-md-6 col-sm-6 col-form-label"><?= lang('auto_check_for_new_notifications') ?></label>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <input <?php
                        if ($realtime_notification == 1) {
                            echo 'disabled';
                        } ?> type="number" class="form-control auto_check_for_new_notifications"
                             value="<?= config_item('auto_check_for_new_notifications') ?>"
                             name="auto_check_for_new_notifications">

                        <strong><?= lang('auto_check_for_new_notifications_help') ?></strong>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-lg-6 col-md-6 col-sm-6 col-form-label"><?= lang('desktop_notifications') ?></label>
                    <div class="col-lg-5 col-md-5 col-sm-5 form-check form-switch  ml">
                        <input class="form-check-input" data-id="" data-bs-toggle="toggle" name="desktop_notifications" value="1" <?php $desktop_notifications = config_item('desktop_notifications'); if (!empty($desktop_notifications) && $desktop_notifications == 1) {  echo 'checked'; }  ?> type="checkbox">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-6 col-md-6 col-sm-6 col-form-label"><?= lang('realtime_notification') ?></label>
                     <div class="col-lg-5 col-md-5 col-sm-5 form-check form-switch  ml">
                        <input class="form-check-input" data-id="" data-bs-toggle="toggle" name="realtime_notification" value="1" <?php  if (!empty($realtime_notification) && $realtime_notification == 1) {  echo 'checked';  } ?> type="checkbox">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="field-1" class="col-lg-6 col-md-6 col-sm-6 col-form-label"></label>
                    <div class="col-lg-3 col-md-4 col-sm-4">
                        <button type="submit" id="sbtn" class="btn-block btn btn-primary"><?= lang('update') ?></button>
                    </div>
                </div>
            </form>
            <div class="col-lg-4">
                <blockquote class="p-sm pt0" style="font-size: 12px">
                    Go to <a href="https://pusher.com/">pusher.com</a> after singup
                    create app. follow the details
                    <strong class="block pb-sm">Name your App – ex ultimate-pro</strong>
                    <strong class="block pb-sm">Select a Cluster – By default pusher.com will select your
                        cluster, you
                        can change it if its needed. You can read more here what is cluster:
                        https://pusher.com/docs/clusters</strong>
                    <strong class="block pb-sm">What’s your frond-end tech? – Select jQuery</strong>
                    <strong class="block pb-sm">What’s your back-end tech? – Select PHP</strong>
                </blockquote>
            </div>
        </div>
    </div>
</div>
    
<script type="text/javascript">
    $('#realtime_notification').change(function () {
        if (this.checked) {
            $(".auto_check_for_new_notifications").attr('disabled', 'disabled');
        } else {
            $(".auto_check_for_new_notifications").removeAttr('disabled');
        }
    })
</script>