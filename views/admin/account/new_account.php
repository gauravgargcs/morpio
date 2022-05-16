<?php
echo message_box('success');
echo message_box('error');
$created = can_action('125', 'created');
$edited = can_action('125', 'edited');
?>
<div class="modal-header">
    <h5 class="modal-title"><?= lang('new_account') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<?php if (!empty($created) || !empty($edited)) {
$companies_id = null;
?>
<?php echo form_open(base_url('admin/account/saved_account'), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form' ,'id'=>'saved_account')); ?>
    <div class="modal-body wrap-modal wrap">
        <?php super_admin_form_modal($companies_id, 4, 8) ?>
        <div class="row mb-3">
            <label class="col-lg-4 col-form-label"><?= lang('account_name') ?> <span
                    class="text-danger">*</span></label>
            <div class="col-lg-8">
                <input type="text" class="form-control" value="<?php
                if (!empty($account_info)) {
                    echo $account_info->account_name;
                }
                ?>" name="account_name" required="">
            </div>

        </div>
        <!-- End discount Fields -->
        <div class="row mb-3 terms">
            <label class="col-lg-4 col-form-label"><?= lang('description') ?> </label>
            <div class="col-lg-8">
                        <textarea name="description" class="form-control"><?php
                            if (!empty($account_info)) {
                                echo $account_info->description;
                            }
                            ?></textarea>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-lg-4 col-form-label"><?= lang('initial_balance') ?> <span
                    class="text-danger">*</span></label>
            <div class="col-lg-8">
                <input type="text" data-parsley-type="number" class="form-control" value="<?php
                if (!empty($account_info)) {
                    echo $account_info->balance;
                }
                ?>" name="balance" required="">
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
<?php } ?>
<script type="text/javascript">
    $(document).on("submit", "#saved_account", function (event) {
        event.preventDefault();
        var form = $(event.target);
        
        var id = form.attr('id');
      $('#loader-wrapper').show();
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize()
        }).done(function (response) {
            response = JSON.parse(response);
         $('#loader-wrapper').hide();
            if (response.status == 'success') {
                if (id == 'saved_account') {
                    if (typeof(response.id) != 'undefined') {
                        var groups = $('select[name="default_account"]');
                        groups.prepend('<option selected value="' + response.id + '">' + response.account_name + '</option>');
                        var select2Instance = groups.data('select2');
                        var resetOptions = select2Instance.options.options;
                        groups.select2('destroy').select2(resetOptions);

                    }
                } else if (id == 'transaction_modal') {
                    if (typeof(response.id) != 'undefined') {
                        var groups = $('select[name="category_id"]');
                        groups.prepend('<option selected value="' + response.id + '">' + response.categories + '</option>');
                        var select2Instance = groups.data('select2');
                        var resetOptions = select2Instance.options.options;
                        groups.select2('destroy').select2(resetOptions)
                    }

                } else {
                    if (typeof(response.id) != 'undefined') {
                        var groups = $('select[name="payment_methods_id"]');
                        groups.prepend('<option selected value="' + response.id + '">' + response.method_name + '</option>');
                        var select2Instance = groups.data('select2');
                        var resetOptions = select2Instance.options.options;
                        groups.select2('destroy').select2(resetOptions)
                    }
                }
            }
            toastr[response.status](response.message);
            $('#myModal').modal('hide');
        }).fail(function () {
            $('#loader-wrapper').hide();
            alert('There was a problem with AJAX');
        });
    });
</script>