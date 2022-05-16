<div class="modal-header">
    
    <h5 class="modal-title mr"><?= lang('training_details') ?></h5>
    <div class="pull-right float-end ml">
        <?= btn_pdf('admin/training/training_pdf/' . $training_info->training_id) ?>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body wrap-modal wrap">
        <?php super_admin_details($training_info->companies_id)?>
        <div class="row mb-3 notice-details-margin">
            <div class="col-sm-4 text-right">
                <label class="col-form-label"><strong><?= lang('employee') ?> :</strong></label>
            </div>
            <div class="col-sm-8">
                <p class="form-control-static"><?php
                    if (!empty($training_info->employment_id)) {
                        echo $training_info->fullname
                        ?> (<?php
                        echo $training_info->employment_id . ' )';
                    }
                    ?></p>
            </div>
        </div>

        <div class="row mb-3 notice-details-margin">
            <div class="col-sm-4 text-right">
                <label class="col-form-label"><strong><?= lang('course_training') ?> :</strong></label>
            </div>
            <div class="col-sm-8">
                <p class="form-control-static"><?= $training_info->training_name; ?></p>
            </div>
        </div>
        <div class="row mb-3 notice-details-margin">
            <div class="col-sm-4 text-right">
                <label class="col-form-label"><strong><?= lang('vendor') ?> :</strong></label>
            </div>
            <div class="col-sm-8">
                <p class="form-control-static"><?= $training_info->vendor_name; ?></p>
            </div>
        </div>
        <div class="row mb-3 notice-details-margin">
            <div class="col-sm-4 text-right">
                <label class="col-form-label"><strong><?= lang('start_date') ?> :</strong></label>
            </div>
            <div class="col-sm-8">
                <p class="form-control-static"><?= display_datetime($training_info->start_date) ?></p>
            </div>
        </div>
        <div class="row mb-3 notice-details-margin">
            <div class="col-sm-4 text-right">
                <label class="col-form-label"><strong><?= lang('finish_date') ?> :</strong></label>
            </div>
            <div class="col-sm-8">
                <p class="form-control-static"><?= display_datetime($training_info->finish_date) ?></p>
            </div>
        </div>
        <div class="row mb-3 notice-details-margin">
            <div class="col-sm-4 text-right">
                <label class="col-form-label"><strong><?= lang('training_cost') ?> :</strong></label>
            </div>
            <?php
            $curency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
            ?>
            <div class="col-sm-8">
                <?php if (!empty($training_info->training_cost)) { ?>
                    <p class="form-control-static"><?= display_money($training_info->training_cost, $curency->symbol); ?></p>
                <?php } ?>
            </div>
        </div>
        <?php $show_custom_fields = custom_form_label(15, $training_info->training_id);

        if (!empty($show_custom_fields)) {
            foreach ($show_custom_fields as $c_label => $v_fields) {
                if (!empty($v_fields)) {
                    ?>
                    <div class="row mb-3 notice-details-margin">
                        <div class="col-sm-4 text-right">
                            <label class="col-form-label"><strong><?= $c_label ?> :</strong></label>
                        </div>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?= $v_fields ?></p>
                        </div>
                    </div>
                <?php }
            }
        }
        ?>
        <div class="row mb-3 notice-details-margin">
            <div class="col-sm-4 text-right">
                <label class="col-form-label"><strong><?= lang('status') ?> :</strong></label>
            </div>
            <div class="col-sm-8">
                <p class="form-control-static"><?php
                    if ($training_info->status == '0') {
                        echo '<span class="label label-warning">' . lang('pending') . ' </span>';
                    } elseif ($training_info->status == '1') {
                        echo '<span class="label label-info">' . lang('started') . '</span>';
                    } elseif ($training_info->status == '2') {
                        echo '<span class="label label-success"> ' . lang('completed') . ' </span>';
                    } else {
                        echo '<span class="label label-danger"> ' . lang('terminated ') . '</span>';
                    }
                    ?></p>
            </div>
        </div>
        <div class="row mb-3 notice-details-margin">
            <div class="col-sm-4 text-right">
                <label class="col-form-label"><strong><?= lang('performance') ?> :</strong></label>
            </div>
            <div class="col-sm-8">
                <p class="form-control-static"><?php
                    if ($training_info->performance == '0') {
                        echo '<span class="label label-warning">' . lang('not_concluded') . ' </span>';
                    } elseif ($training_info->performance == '1') {
                        echo '<span class="label label-info">' . lang('satisfactory') . '</span>';
                    } elseif ($training_info->performance == '2') {
                        echo '<span class="label label-primary"> ' . lang('average') . ' </span>';
                    } elseif ($training_info->performance == '3') {
                        echo '<span class="label label-danger"> ' . lang('poor') . ' </span>';
                    } else {
                        echo '<span class="label label-success"> ' . lang('excellent ') . '</span>';
                    }
                    ?></p>
            </div>
        </div>
        <?php
        $uploaded_file = json_decode($training_info->upload_file);
        if (!empty($uploaded_file)) {
            ?>
            <div class="row mb-3 notice-details-margin">
                <div class="col-sm-4 text-right">
                    <label class="col-form-label"><strong><?= lang('attachment') ?> :</strong></label>
                </div>
                <div class="col-sm-8">
                    <div class="row mailbox-attachments clearfix mt">
                    <?php
                    if (!empty($uploaded_file)):
                        foreach ($uploaded_file as $v_files):
                            if (!empty($v_files)): ?>
                        <div class="col-xl-4 col-6">
                            <div class="card">
                            
                            <?php if ($v_files->is_image == 1) : ?>
                               <img src="<?= base_url() . $v_files->path ?>" alt="Attachment" class="card-img-top img-fluid">
                            <?php else : ?>
                                <span class="mailbox-attachment-icon"><i class="fa fa-file-pdf-o"></i></span>
                            <?php endif; ?>
                                <div class="py-2">
                                    <a href="<?= base_url() ?>admin/training/ownload_file/<?= $training_info->training_id . '/' . $v_files->fileName ?>"
                                       class="mailbox-attachment-name fw-medium  text-center"><i class="fa fa-paperclip"></i>
                                        <?= $v_files->fileName ?></a>
                                    <div class="mailbox-attachment-size">
                                      <?= $v_files->size ?> <?= lang('kb') ?>
                                        <a href="<?= base_url() ?>admin/training/download_file/<?= $training_info->training_id . '/' . $v_files->fileName ?>"
                                           class="btn btn-default btn-sm pull-right fw-medium"><i class="fa fa-cloud-download"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        endif;
                        endforeach;
                    endif;
                    ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="row mb-3 notice-details-margin">
            <div class="col-sm-4 text-right">
                <label class="col-form-label"><strong><?= lang('remarks') ?> :</strong></label>
            </div>
            <div class="col-sm-8">
                <blockquote style="font-size: 12px"><?php echo $training_info->remarks; ?></blockquote>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <div class="mb-3">
        <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
    </div>
</div>
