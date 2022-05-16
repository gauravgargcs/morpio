<?php
echo message_box('success');
echo message_box('error');
$created = can_action('125', 'created');
$edited = can_action('125', 'edited');
?>
<div class="modal-header">
    <h5 class="modal-title"><?= lang('payment_method') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<?php if (!empty($created) || !empty($edited)) { ?>
        <?php echo form_open(base_url('admin/settings/update_payment_method'), array('id' => 'update_payment_method', 'class' => 'form-horizontal')); ?>
    <div class="modal-body wrap-modal wrap">
        <div class="row mb-3">
            <label
                class="col-sm-4 col-form-label"><?= lang('method_name') ?></label>
            <div class="col-sm-8">
                <input type="text" name="method_name" class="form-control"
                       placeholder="<?= lang('method_name') ?>" required>
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
    $(document).on("submit", "#update_payment_method", function (event) {
        var form = $(event.target);
        var id = form.attr('id');
         event.preventDefault();
     $('#loader-wrapper').show();
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize()
        }).done(function (response) {
     $('#loader-wrapper').hide();

            response = JSON.parse(response);
            if (response.status == 'success') {
                if (id == 'saved_account') {
                    if (typeof(response.id) != 'undefined') {
                        var groups = $('select[name="account_id"]');
                        groups.prepend('<option selected value="' + response.id + '">' + response.account_name + '</option>');
                        var select2Instance = groups.data('select2');
                        var resetOptions = select2Instance.options.options;
                        groups.select2('destroy').select2(resetOptions)
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