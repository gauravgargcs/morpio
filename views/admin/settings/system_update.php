<div class="card"  data-spy="scroll" data-offset="0">
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>
    <div class="card-body">
        <h4 class="card-title mb-4"><?= lang('update'); ?></h4>
        <div class="row">   
        <?php if(!is_subdomain() && super_admin()){ ?> 
            <div class="row mb-3">
                <label class="col-md-3 col-form-label"><?= lang('purchase_key') ?></label>
                <div class="col-md-9">
                    <input required type="text" placeholder="<?= lang('enter_your') . ' ' . lang('purchase_key') ?>" class="form-control" name="purchase_key"  value=""/>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-md-3 col-form-label"><?= lang('envato_username') ?></label>
                <div class="col-md-9">
                    <input required type="text" placeholder="unique_coder" class="form-control" name="buyer"  value=""/>
                </div>
            </div>
            <?php } ?>
            <div class="col-md-6 text-center ">
                <div class="alert-css alert-<?php if ($latest_version > $current_version) {
                    echo 'danger';
                } else {
                    echo 'info';
                } ?>  pb-1 mb-4">
                    <h4 class="bold p-3"><?php echo lang('your_version'); ?></h4>
                    <p class="font-medium bold mb-3"><?php echo wordwrap($current_version, 1, '.', true); ?></p>
                </div>
            </div>
            <div class="col-md-6 text-center">
                <div class="alert-css alert-<?php if ($latest_version > $current_version) {
                    echo 'success';
                } else if ($latest_version == $current_version) {
                    echo 'info';
                } ?> pb-1 mb-4">
                    <h4 class="bold p-3" ><?php echo lang('latest_version'); ?></h4>
                    <p class="font-medium bold"><?php echo wordwrap($latest_version, 1, '.', true); ?></p>
                    <?php echo form_hidden('latest_version', $latest_version); ?>
                </div>
            </div>
            <div class="clearfix"></div>
            <hr/>
            <div class="col-md-12 text-center">
                <?php if ($current_version != $latest_version && $latest_version > $current_version) { ?>
                    <div class="alert-css alert-warning">
                        Before performing an update, it is <b>strongly recommended to create a full backup</b>
                        of your current installation <b>(files and database)</b> and review the changelog.
                    </div>
                    <h3 class="bold text-center mbot20"><i class="fa fa-exclamation-circle"
                                                           aria-hidden="true"></i> <?php echo lang('update_available'); ?>
                    </h3>
                    <div class="update_app_wrapper" data-wait-text="<?php echo lang('wait_text'); ?>"
                         data-original-text="<?php echo lang('update_now'); ?>">
                        <?php if (count($update_errors) == 0) { ?>
                            <a href="#" id="update_app"
                               class="btn btn-success"><?php echo lang('update_now'); ?></a>
                        <?php } ?>
                    </div>
                    <div id="update_messages" class="mtop25 text-left">
                    </div>
                <?php } else { ?>
                    <h3 class="bold mbot20 text-success"><strong><i class="fa fa-exclamation-circle"
                                                            aria-hidden="true"></i> <?php echo lang('using_latest_version'); ?></strong>
                    </h3>
                <?php } ?>
                <?php if (count($update_errors) > 0) { ?>
                    <p class="text-danger">Please fix the errors listed below.</p>
                    <?php foreach ($update_errors as $error) { ?>
                        <div class="alert-css alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php } ?>
                <?php } ?>
                <?php if (isset($update_info->additional_data)) {
                    echo $update_info->additional_data;
                } ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('#update_app').on('click', function (e) {
            e.preventDefault();
            $('input[name="purchase_key"]').parents('.row mb-3').removeClass('has-error');
            $('input[name="buyer"]').parents('.row mb-3').removeClass('has-error');

            var purchase_key = $('input[name="purchase_key"]').val();
            var buyer = $('input[name="buyer"]').val();
            var latest_version = $('input[name="latest_version"]').val();
            var update_errors;
            if (purchase_key != '' && buyer != '') {
                var ubtn = $(this);
                ubtn.html('Please wait...');
                ubtn.addClass('disabled');
                $.post('<?= base_url()?>admin/auto_update', {
                    purchase_key: purchase_key,
                    latest_version: latest_version,
                    buyer: buyer,
                    auto_update: true
                }).done(function (res) {
                    if (res) {
                        var result = JSON.parse(res);
                        console.log(result);
                        $('#update_messages').html('<div class="alert alert-danger mt-lg"></div>');
                        $('#update_messages .alert').append('<p>' + result.message + '</p>');
                        ubtn.removeClass('disabled');
                        ubtn.html($('.update_app_wrapper').data('original-text'));
                    } else {
                        $.post('<?= base_url()?>admin/auto_update/database', {auto_update: true}).done(function (res) {
                            $('#update_messages').html('<div class="alert alert-success mt-lg"></div>');
                            $('#update_messages .alert').append('<p>' + res + '</p>');
                            ubtn.removeClass('disabled');
                            ubtn.html($('.update_app_wrapper').data('original-text'));
                            setTimeout(function () {
                                window.location.reload();
                            }, 5000);
                        }).fail(function (response) {
                            $('#update_messages').html('<div class="alert alert-danger mt-lg"></div>');
                            $('#update_messages .alert').append('<p>' + response + '</p>');
                            ubtn.removeClass('disabled');
                            ubtn.html($('.update_app_wrapper').data('original-text'));
                        });
                    }
                }).fail(function (response) {
                    $('#update_messages').html('<div class="alert alert-danger mt-lg"></div>');
                    $('#update_messages .alert').append('<p>' + response.responseText + '</p>');
                    ubtn.removeClass('disabled');
                    ubtn.html($('.update_app_wrapper').data('original-text'));
                });
            } else if (purchase_key != '' && buyer == '') {
                $('input[name="purchase_key"]').parents('.row mb-3').removeClass('has-error');
                $('input[name="buyer"]').parents('.row mb-3').addClass('has-error');
            } else if (buyer != '' && purchase_key == '') {
                $('input[name="purchase_key"]').parents('.row mb-3').addClass('has-error');
                $('input[name="buyer"]').parents('.row mb-3').removeClass('has-error');
            } else {
                $('input[name="purchase_key"]').parents('.row mb-3').addClass('has-error');
                $('input[name="buyer"]').parents('.row mb-3').addClass('has-error');
            }
        });
    });
</script>