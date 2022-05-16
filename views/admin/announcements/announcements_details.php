<div class="modal-header">
    <h5 class="modal-title"><?= lang('announcements_details') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body wrap-modal wrap">
        <?php super_admin_details($announcements_details->companies_id,'4','8') ?>
        <div class="row mb-3 notice-details-margin">
            <div class="col-sm-4 text-right">
                <label class="col-form-label"><strong><?= lang('title') ?> :</strong></label>
            </div>
            <div class="col-sm-5">
                <p class="form-control-static"><?php if (!empty($announcements_details->title)) echo $announcements_details->title; ?></p>
            </div>
        </div>
        <div class="row mb-3 notice-details-margin">
            <div class="col-sm-4 text-right">
                <label class="col-form-label"><?= lang('start_date') ?> :</label>
            </div>

            <div class="col-sm-5">
                <p class="form-control-static"><?= display_datetime($announcements_details->start_date) ?></p>
            </div>
        </div>
        <div class="row mb-3 notice-details-margin">
            <div class="col-sm-4 text-right">
                <label class="col-form-label"><?= lang('end_date') ?> :</label>
            </div>

            <div class="col-sm-5">
                <p class="form-control-static"><?= display_datetime($announcements_details->end_date) ?></p>
            </div>
        </div>

        <div class="row mb-3 notice-details-margin">
            <div class="col-sm-4 text-right">
                <label class="col-form-label"><strong><?= lang('created_by') ?> :</strong></label>
            </div>
            <div class="col-sm-5">
                <p class="form-control-static"><span><?= fullname($announcements_details->user_id) ?></span>
                </p>
            </div>
        </div>
        <div class="row mb-3 notice-details-margin">
            <div class="col-sm-4 text-right">
                <label class="col-form-label"><strong><?= lang('created_date') ?> :</strong></label>
            </div>
            <div class="col-sm-5">
                <p class="form-control-static">
                    <span><?= display_datetime($announcements_details->created_date); ?></span>
                </p>
            </div>
        </div>
        <div class="row mb-3 notice-details-margin">
            <div class="col-sm-4 text-right">
                <label class="col-form-label"><strong><?= lang('status') ?> :</strong></label>
            </div>
            <div class="col-sm-5">
                <p class="form-control-static">
                    <?php if ($announcements_details->status == 'unpublished') : ?>
                        <span class="badge badge-soft-danger"><?= lang('unpublished') ?></span>
                    <?php else : ?>
                        <span class="badge badge-soft-success"><?= lang('published') ?></span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <?php
        if (!empty($announcements_details->attachment)) {
            $uploaded_file = json_decode($announcements_details->attachment);
        }
        ?>
        <div class="row mb-3 notice-details-margin">
            <div class="col-sm-4 text-right">
                <label class="col-form-label"><strong><?= lang('attachment') ?> :</strong></label>
            </div>
            <div class="col-sm-5">
                <ul class="mailbox-attachments clearfix mt list-unstyled">
                    <?php
                    if (!empty($uploaded_file)):
                        foreach ($uploaded_file as $v_files):

                            if (!empty($v_files)):

                                ?>
                                <li>
                                    <?php if ($v_files->is_image == 1) : ?>
                                        <span class="mailbox-attachment-icon has-img"><img
                                                src="<?= base_url() . $v_files->path ?>"
                                                alt="Attachment" width="100"></span>
                                    <?php else : ?>
                                        <span class="mailbox-attachment-icon"><i
                                                class="fa fa-file-pdf-o"></i></span>
                                    <?php endif; ?>
                                    <div class="mailbox-attachment-info">
                                        <a href="<?= base_url() ?>admin/announcements/download_files/<?= $announcements_details->announcements_id . '/' . $v_files->fileName ?>"
                                           class="mailbox-attachment-name"><i class="fa fa-paperclip"></i>
                                            <?= $v_files->fileName ?></a>
                    <span class="mailbox-attachment-size">
                      <?= $v_files->size ?> <?= lang('kb') ?>
                        <a href="<?= base_url() ?>admin/announcements/download_files/<?= $announcements_details->announcements_id . '/' . $v_files->fileName ?>"
                           class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                    </span>
                                    </div>
                                </li>
                                <?php
                            endif;
                        endforeach;
                    endif;
                    ?>
                </ul>
            </div>
        </div>
        <?php

        if (!empty($announcements_details->all_client)) {
            ?>
            <div class="row mb-3 notice-details-margin">
                <div class="col-sm-4 text-right">
                    <label class="col-form-label"><strong><?= lang('share_with') ?> :</strong></label>
                </div>
                <div class="col-sm-5">
                    <p class="form-control-static">
                        <span class="badge badge-soft-info"><?= lang('client') ?></span>
                    </p>
                </div>
            </div>
        <?php } ?>
        <?php $show_custom_fields = custom_form_label(16, $announcements_details->announcements_id);

        if (!empty($show_custom_fields)) {
            foreach ($show_custom_fields as $c_label => $v_fields) {
                if (!empty($v_fields)) {
                    ?>
                    <div class="row mb-3 notice-details-margin">
                        <div class="col-sm-4 text-right">
                            <label class="col-form-label"><strong><?= $c_label ?> :</strong></label>
                        </div>
                        <div class="col-sm-5">
                            <p class="form-control-static"><?= $v_fields ?></p>
                        </div>
                    </div>
                <?php }
            }
        }
        ?>
        <div class="row mb-3 notice-details-margin">
            <div class="col-sm-4 text-right">
                <label class="col-form-label"><strong><?= lang('description') ?> :</strong></label>
            </div>
            <div class="col-sm-5">
                <blockquote style="font-size: 12px"><?php echo $announcements_details->description; ?></blockquote>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <div class="mb-3">
        <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
    </div>
</div>



