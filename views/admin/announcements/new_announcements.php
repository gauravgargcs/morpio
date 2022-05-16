<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/plugins/dropzone/dropzone.min.css">
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/dropzone/dropzone.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/dropzone/dropzone.custom.js"></script>

<?php
$created = can_action('100', 'created');
$edited = can_action('100', 'edited');
if (!empty($created) || !empty($edited)) {
    if (!empty($announcements)) {
        $announcements_id = $announcements->announcements_id;
        $companies_id = $announcements->companies_id;
    } else {
        $announcements_id = null;
        $companies_id = null;
    }
    ?>
<?php echo form_open(base_url('admin/announcements/save_announcements/' . $announcements_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
    <div class="modal-header">
        <h5 class="modal-title"><?= lang('new') . ' ' . lang('announcements') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body wrap-modal wrap">
        <?php super_admin_form_modal($companies_id, 3, 8) ?>
        <div class="row mb-3">
            <label  class="col-sm-3 form-label"><?= lang('title') ?> <span
                    class="required">*</span></label>

            <div class="col-sm-8">
                <input type="text" name="title"
                       value="<?= (!empty($announcements->title) ? $announcements->title : ''); ?>"
                       class="form-control"
                       requried=""/>
            </div>
        </div>
        <div class="row mb-3">
            <label  class="col-sm-3 form-label"><?= lang('description') ?></label>

            <div class="col-sm-8">
                <textarea name="description" id="elm1" 
                          class="form-control textarea"><?= (!empty($announcements->description) ? $announcements->description : ''); ?></textarea>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-3 form-label"><?= lang('start_date') ?> <span
                    class="required">*</span></label>

            <div class="col-sm-8">
                <div class="input-group">
                    <input type="text" name="start_date"
                           placeholder="<?= lang('enter') . ' ' . lang('start_date') ?>"
                           class="form-control datepicker" value="<?php
                    if (!empty($announcements->start_date)) {
                        echo date('d-m-Y H-i', strtotime($announcements->start_date));
                    }
                    ?>">
                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-3 form-label"><?= lang('end_date') ?> <span
                    class="required">*</span></label>

            <div class="col-sm-8">
                <div class="input-group">
                    <input type="text" name="end_date"
                           placeholder="<?= lang('enter') . ' ' . lang('end_date') ?>"
                           class="form-control datepicker" value="<?php
                    if (!empty($announcements->end_date)) {
                        echo date('d-m-Y H-i', strtotime($announcements->end_date));
                    }
                    ?>">
                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                </div>
            </div>
        </div>
        <div class="row mb-3" style="margin-bottom: 0px">
            <label 
                   class="col-sm-3 form-label"><?= lang('attachment') ?></label>

            <div class="col-sm-8">
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
            
                <?php
                if (!empty($announcements->attachment)) {
                    $uploaded_file = json_decode($announcements->attachment);
                }
                if (!empty($uploaded_file)) {
                    foreach ($uploaded_file as $v_files_image) { ?>
                        <div class="pull-left mt pr-lg mb" style="width:100px;">
                            <span data-dz-remove class="pull-right existing_image"
                                  style="cursor: pointer"><i
                                    class="fa fa-times"></i></span>
                            <?php if ($v_files_image->is_image == 1) { ?>
                                <img data-dz-thumbnail
                                     src="<?php echo base_url() . $v_files_image->path ?>"
                                     class="upload-thumbnail-sm img-fluid img-thumbnail"/>
                            <?php } else { ?>
                                <span data-bs-toggle="tooltip" data-bs-placement="top"
                                      title="<?= $v_files_image->fileName ?>"
                                      class="mailbox-attachment-icon"><i
                                        class="fa fa-file-text-o"></i></span>
                            <?php } ?>

                            <input type="hidden" name="path[]"
                                   value="<?php echo $v_files_image->path ?>">
                            <input type="hidden" name="fileName[]"
                                   value="<?php echo $v_files_image->fileName ?>">
                            <input type="hidden" name="fullPath[]"
                                   value="<?php echo $v_files_image->fullPath ?>">
                            <input type="hidden" name="size[]"
                                   value="<?php echo $v_files_image->size ?>">
                            <input type="hidden" name="is_image[]"
                                   value="<?php echo $v_files_image->is_image ?>">
                        </div>
                    <?php }; ?>
                <?php }; ?>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $(".existing_image").click(function () {
                            $(this).parent().remove();
                        });
                    })
                </script>
            </div>
        </div>
        <?php
        if (!empty($announcements)) {
            $announcements_id = $announcements->announcements_id;
        } else {
            $announcements_id = null;
        }
        ?>
        <?= custom_form_Fields(16, $announcements_id); ?>
        <div class="row mb-3">
            <label  class="col-sm-3 form-label"><?= lang('share_with') ?></label>

            <div class="col-sm-8 row">
                <div class="col-sm-4">

                    <div class="form-check form-check-primary">
                        <input <?= (!empty($announcements->all_client) ? 'checked' : ''); ?> type="checkbox"  name="all_client" value="1" id="all_client" class="form-check-input">
                            <label class="form-check-label" for="all_client"> <?= lang('all_client') ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <label  class="col-sm-3 form-label"><?= lang('status') ?></label>

            <div class="col-sm-8 row">
                <div class="col-sm-4">
                    <div class="form-check form-check-primary">
                        <input <?= (!empty($announcements->status) && $announcements->status == 'published' ? 'checked' : ''); ?>
                                class="select_one form-check-input" type="checkbox" name="status" value="published" id="published_sts">
                        <label class="form-check-label" for="published_sts">
                         <?= lang('published') ?>
                        </label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-check form-check-primary">
                        <input <?= (!empty($announcements->status) && $announcements->status == 'unpublished' ? 'checked' : ''); ?>
                                class="select_one form-check-input" type="checkbox" name="status" value="unpublished" id="unpublished_sts">
                        <label class="form-check-label" for="unpublished_sts"><?= lang('unpublished') ?>
                        </label>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <button id="file-save-button" type="submit" class="btn btn-primary start-upload"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>            
        </div>
    </div>
<?php echo form_close(); ?>

<?php } ?>