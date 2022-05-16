<form action="<?= base_url() ?>admin/opportunities/save_attachment" class="form-horizontal" role="form" method="post" enctype="multipart/form-data" accept-charset="utf-8" novalidate="novalidate">
    <div class="modal-header">
        <h5 class="modal-title"><?= lang('new') . ' ' . lang('attachment') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body wrap-modal wrap">
        <div class="row mb-3">
            <label class="col-lg-3 col-form-label"><?= lang('file_title') ?> <span
                    class="text-danger">*</span></label>
            <div class="col-lg-6">
                <input name="title" class="form-control" value="<?php
                if (!empty($add_files_info)) {
                    echo $add_files_info->title;
                }
                ?>" required placeholder="<?= lang('file_title') ?>"/>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-lg-3 col-form-label"><?= lang('description') ?></label>
            <div class="col-lg-6">
                                <textarea name="description" class="form-control"
                                          placeholder="<?= lang('description') ?>"><?php
                                    if (!empty($add_files_info)) {
                                        echo $add_files_info->description;
                                    }
                                    ?></textarea>
            </div>
        </div>
        <input type="hidden" name="opportunities_id" value="<?php
        if (!empty($opportunity_details->opportunities_id)) {
            echo $opportunity_details->opportunities_id;
        }
        ?>" class="form-control">
        <div class="row mb-3">
            <div class="col-sm-12">
                <div id="file-dropzone" class="dropzone mb15">

                </div>
                <div data-simplebar style="max-height: 280px;">  
                    <div id="file-dropzone-scrollbar">
                        <div id="file-previews" class="row">
                            <div id="file-upload-row" class="col-sm-6 mt file-upload-row">
                                <div class="preview box-content pr-lg" style="width:100px;">
                                    <img data-dz-thumbnail class="upload-thumbnail-sm"/>
                                    <div class="mb progress progress-striped upload-progress-sm active mt-sm"
                                         role="progressbar" aria-valuemin="0" aria-valuemax="100"
                                         aria-valuenow="0">
                                        <div class="progress-bar progress-bar-success" style="width:0%;"
                                             data-dz-uploadprogress></div>
                                    </div>
                                </div>
                                <div class="box-content">
                                    <p class="clearfix mb0 p0">
                                        <span class="name pull-left" data-dz-name></span>
                                <span data-dz-remove class="pull-right" style="cursor: pointer">
                                <i class="fa fa-times"></i>
                            </span>
                                    </p>
                                    <p class="clearfix mb0 p0">
                                        <span class="size" data-dz-size></span>
                                    </p>
                                    <strong class="error text-danger" data-dz-errormessage></strong>
                                    <input class="file-count-field" type="hidden" name="files[]" value=""/>
                            <textarea class="form-control description-field" type="text" style="cursor: auto;"
                                      placeholder="<?php echo lang("comments") ?>"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="file-modal-footer"></div>
    </div>
    <div class="modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <button id="file-save-opp-btn" type="submit" disabled="disabled" class="btn btn-primary start-upload"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>            
        </div>
    </div>
</form>       