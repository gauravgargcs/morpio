<form role="form" id="ban_reason" data-parsley-validate="" novalidate="" action="<?php echo base_url(); ?>admin/user/set_banned/1/<?= $user_id ?>" method="post" class="form-horizontal">
   <div class="modal-header">
        <h5 class="modal-title"><?= lang('ban_reason') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body wrap-modal wrap">
        <div class="row mb-3">
            <div class="col-sm-12">
                <textarea type="text" name="ban_reason" value="" required="" rows="5" class="form-control"></textarea>
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

<script type="text/javascript">
    $(document).ready(function () {
        $("#ban_reason").validate({
            rules: {
                ban_reason: {
                    required: true,
                }
            }
        });
    });
</script>
