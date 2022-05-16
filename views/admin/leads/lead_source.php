<?php
echo message_box('success');
echo message_box('error');
$created = can_action('128', 'created');
$edited = can_action('128', 'edited');
?>
<div class="modal-header">
    <h5 class="modal-title"><?= lang('lead_source') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<?php if (!empty($created) || !empty($edited)) { ?>
<form method="post" id="lead_sources" action="<?= base_url() ?>admin/leads/update_lead_source" class="form-horizontal" data-parsley-validate="" novalidate="">
    <div class="modal-body wrap-modal wrap">
        <div class="col-xl-12">       
            <div class="row mb-3">
                <label
                    class="col-xl-3 col-form-label"><?= lang('lead_source') ?></label>
                <div class="col-xl-5">
                    <input type="text" name="lead_source" class="form-control"
                           placeholder="<?= lang('lead_source') ?>" required>
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
</form>
<?php } ?>

<script type="text/javascript">
    $(document).on("submit", "form", function (event) {
        var form = $(event.target);
        if (form.attr('action') == '<?= base_url('admin/leads/update_lead_source')?>' || form.attr('action') == '<?= base_url('admin/leads/update_lead_status')?>') {
            event.preventDefault();
        }
        var id = form.attr('id');
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize()
        }).done(function (res) {
            res = JSON.parse(res);
            if (res.status == 'success') {
                if (id == 'lead_sources') {
                    if (typeof(res.id) != 'undefined' && res.lead_source != 'undefined') {
                        var lead_source = $('select[name="lead_source_id"]');
                        lead_source.prepend('<option selected value="' + res.id + '">' + res.lead_source + '</option>');
                        var select2Instance = lead_source.data('select2');
                        var resetOptions = select2Instance.options.options;
                        lead_source.select2('destroy').select2(resetOptions)

                    }
                } else {
                    if (typeof(res.id) != 'undefined' && res.lead_status != 'undefined') {
                        var lead_status = $('select[name="lead_status_id"]');
                        lead_status.prepend('<option selected value="' + res.id + '">' + res.lead_status + '</option>');
                        var select2Instance = lead_status.data('select2');
                        var resetOptions = select2Instance.options.options;
                        lead_status.select2('destroy').select2(resetOptions)
                    }
                }

            }
            toastr[res.status](res.message);
            $('#myModal').modal('hide');
        }).fail(function () {
            alert('There was a problem with AJAX');
        });
    });
</script>