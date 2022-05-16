<div class="modal-header">
    <h5 class="modal-title"><?= lang('comments') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form role="form"  action="<?php echo base_url(); ?>admin/tickets/index/changed_ticket_status/<?= $id ?>/<?= $status ?>" method="post" class="form-horizontal">
    <div class="modal-body wrap-modal wrap">
        <div class="row mb-3">
            <div class="col-sm-12">
                <textarea type="text" name="body" id="elm1" class="form-control textarea-md"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <button type="submit" class="btn btn-primary  w-md waves-effect waves-light"> <?php echo lang('save'); ?></button>            
        </div>
    </div>
</form>