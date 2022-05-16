<?php
echo message_box('success');
echo message_box('error');
?>
<div class="modal-header">
    <h5 class="modal-title"><?= lang('manufacturer') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<?php echo form_open(base_url('admin/items/update_manufacturer'), array('id' => 'manufacturer_modal', 'class' => 'form-horizontal')); ?>
<div class="modal-body wrap-modal wrap">
    <div class="row mb-3">
        <label
                class="col-sm-3 col-form-label"><?= lang('manufacturer') ?></label>
        <div class="col-sm-9">
            <input type="text" name="manufacturer" class="form-control"
                   placeholder="<?= lang('enter') . ' ' . lang('manufacturer') ?>" required>
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="mb-3">
        <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
        <button type="submit" class="btn btn-primary  w-md waves-effect waves-light"> <?php echo lang('save'); ?></button>            
    </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    $(document).on("submit", "form", function (event) {
        var form = $(event.target);
        var id = form.attr('id');
        if (form.attr('action') == '<?= base_url('admin/items/update_group')?>' || form.attr('action') == '<?= base_url('admin/items/update_manufacturer')?>') {
            event.preventDefault();
        }
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize()
        }).done(function (response) {
            response = JSON.parse(response);
            if (response.status == 'success') {
                if (id == 'manufacturer_modal') {
                    if (typeof (response.id) != 'undefined') {
                        var groups = $('select[name="manufacturer_id"]');
                        groups.prepend('<option selected value="' + response.id + '">' + response.manufacturer + '</option>');
                        var select2Instance = groups.data('select2');
                        var resetOptions = select2Instance.options.options;
                        groups.select2('destroy').select2(resetOptions)
                    }
                } else {
                    if (typeof (response.id) != 'undefined') {
                        var groups = $('select[name="customer_group_id"]');
                        groups.prepend('<option selected value="' + response.id + '">' + response.group + '</option>');
                        var select2Instance = groups.data('select2');
                        var resetOptions = select2Instance.options.options;
                        groups.select2('destroy').select2(resetOptions)
                    }
                }
            }
            toastr[response.status](response.message);
            $('#myModal').modal('hide');
        }).fail(function () {
            alert('There was a problem with AJAX');
        });
    });
</script>