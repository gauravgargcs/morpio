<?php
echo message_box('success');
echo message_box('error');
$created = can_action('125', 'created');
$edited = can_action('125', 'edited');
?>
<div class="modal-header">
    <h5 class="modal-title"><?= lang('customer_group') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<?php if (!empty($created) || !empty($edited)) { ?>
<?php echo form_open(base_url('admin/client/update_customer_group'), array('id' => 'group_modal', 'class' => 'form-horizontal')); ?>
<div class="modal-body wrap-modal wrap">          
    <div class="col-xl-12">       
        <div class="row mb-3">
            <label
                class="col-xl-5 col-form-label"><?= lang('customer_group') ?></label>
            <div class="col-xl-5">
                <input type="text" name="customer_group" class="form-control"
                       placeholder="<?= lang('customer_group') ?>" required>
            </div>
        </div>
        <div class="row mb-3">
            <label
                class="col-xl-5 col-form-label"><?= lang('description') ?></label>
            <div class="col-xl-5">
                <input type="text" name="description" class="form-control"
                       placeholder="<?= lang('description') ?>">
            </div>
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
    $(document).on("submit", "form", function (event) {
        var form = $(event.target);
        if (form.attr('action') == '<?= base_url('admin/client/update_customer_group')?>') {
            event.preventDefault();
        }
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize()
        }).done(function (response) {
            response = JSON.parse(response);
            if (response.status == 'success') {
                if (typeof(response.id) != 'undefined') {
                    var groups = $('select[name="customer_group_id"]');
                    groups.prepend('<option selected value="' + response.id + '">' + response.group + '</option>');
                    var select2Instance = groups.data('select2');
                    var resetOptions = select2Instance.options.options;
                    groups.select2('destroy').select2(resetOptions)
                }
                toastr[response.status](response.message);
            }
            $('#myModal').modal('hide');
        }).fail(function () {
            alert('There was a problem with AJAX');
        });
    });
</script>