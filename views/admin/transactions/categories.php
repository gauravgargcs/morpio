<?php
echo message_box('success');
echo message_box('error');
if ($type == 'income') {
    $created = can_action('125', 'created');
    $edited = can_action('125', 'edited');
} else {
    $created = can_action('124', 'created');
    $edited = can_action('124', 'edited');
}
?>
<div class="modal-header">
    <h5 class="modal-title"><?= lang($category) ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<?php if (!empty($created) || !empty($edited)) { ?>
    <?php echo form_open(base_url('admin/transactions/update_categories/' . $type), array('id' => 'transaction_modal', 'class' => 'form-horizontal'));
?>    
<div class="modal-body wrap-modal wrap">

    <input type="hidden" name="companies_id" id="companies_id_modal">
    <div class="row mb-3">
        <label
            class="col-sm-3 col-form-label"><?= lang($category) ?></label>
        <div class="col-sm-5">
            <input type="text" name="categories" class="form-control"
                   placeholder="<?= lang($category) ?>" required>
        </div>
    </div>
    <div class="row mb-3">
        <label
            class="col-sm-3 col-form-label"><?= lang('description') ?></label>
        <div class="col-sm-5">
            <input type="text" name="description" class="form-control"
                   placeholder="<?= lang('description') ?>">
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
    $(document).on("submit", "#transaction_modal", function (event) {
         event.preventDefault();
              $('#companies_id_modal').val($('#companies_id_div select[name="companies_id"]').val());
        var form = $(event.target);
        var id = form.attr('id');
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
    // $(document).ready(function () {
    // })
</script>