<?php
echo message_box('success');
echo message_box('error');
$created = can_action('129', 'created');
$edited = can_action('129', 'edited');
?>
<div class="modal-header">
    <h5 class="modal-title"><?= lang('opportunities_state_reason') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<?php
if (!empty($created) || !empty($edited)) { ?>
    <form method="post" id="state_reason" action="<?= base_url() ?>admin/opportunities/update_state_reason" class="form-horizontal" data-parsley-validate="" novalidate="">
        <div class="modal-body wrap-modal wrap">
            <div class="row mb-3">
                <label
                    class="col-sm-4 col-form-label"><?= lang('opportunities_state') ?></label>
                <div class="col-sm-7">
                    <select name="opportunities_state" class="form-control">
                        <option value="open"><?= lang('open') ?></option>
                        <option value="won"><?= lang('won') ?></option>
                        <option value="abandoned"><?= lang('abandoned') ?></option>
                        <option value="suspended"><?= lang('suspended') ?></option>
                        <option value="lost"><?= lang('lost') ?></option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label
                    class="col-sm-4 col-form-label"><?= lang('reason') ?></label>
                <div class="col-sm-7">
                    <input type="text" name="opportunities_state_reason" class="form-control"
                           placeholder="<?= lang('opportunities_state_reason') ?>">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="mb-3">
                <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
                <button type="submit" class="btn btn-primary w-md waves-effect waves-light"><?= lang('save') ?></button>            
            </div>
        </div>
    </form>
<?php } ?>

<script type="text/javascript">
    $(document).on("submit", "form", function (event) {
        var form = $(event.target);
        if (form.attr('action') == '<?= base_url('admin/opportunities/update_state_reason')?>') {
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
                    var groups = $('select[name="opportunities_state_reason_id"]');
                    groups.prepend('<option selected value="' + response.id + '">' + response.reason + '</option>');
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
