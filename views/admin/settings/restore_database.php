<div class="modal-header">
        <h5 class="modal-title"><?= lang('restore_database') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
<form id="form" action="<?php echo base_url() ?>admin/settings/restore_database" method="post" enctype="multipart/form-data" class="form-horizontal">
    <div class="modal-body wrap-modal wrap">
        <div class="alert alert-warning"><?= lang('restore_notice'); ?></div>
        <br/>
        <div class="row mb-3" style="margin-bottom: 0px">
            <label for="field-1" class="col-sm-4 form-label"><?= lang('upload') . ' ' . lang('database_backup') . ' ' . lang('zipped_file') ?></label>
            <div class="col-sm-8">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div>
                        <input class="form-control" type="file" id="formFile"  name="upload_file" value="upload"/>
                    </div>
                    <div class="fileinput-new thumbnail" style="width: 210px;">
                       
                    </div>
                    <div class="fileinput-preview fileinput-exists thumbnail" style="width: 210px;"></div>
                    
                    <div id="msg_pdf" style="color: #e11221"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <input type="submit" name="send" class="btn btn-primary"  value="<?= lang('upload'); ?>"/>
        </div>
    </div>
</form>          