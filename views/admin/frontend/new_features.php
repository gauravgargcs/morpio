<?php include_once 'asset/admin-ajax.php'; ?>
<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>

<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                class="sr-only">Close</span></button>
        <h4 class="modal-title"
            id="myModalLabel"><?= lang('new') . ' ' . lang('features') ?></h4>
    </div>
    <div class="modal-body wrap-modal wrap">
        <?php
        if (!empty($features_info)) {
            $id = $features_info->id;
        } else {
            $id = null;
        }
        echo form_open(base_url('admin/frontend/save_features/' . $id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form'));
        ?>
        <div class="form-group">
            <label for="field-1" class="col-sm-3 control-label"><?= lang('icon') ?>
                <span class="required">*</span></label>
            <div class="col-sm-7">
                <?php
                if (!empty($type)) {
                    ?>
                    <input type="hidden" name="type" value="<?= $type ?>"/>
                <?php }
                if (!empty($type) && $type == 'consultant' || !empty($features_info->type) && $features_info->type == 'consultant') { ?>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('image') ?></label>
                        <div class="col-lg-7">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 210px;">
                                    <?php if (!empty($features_info->icon) && $features_info->icon != '') : ?>
                                        <img src="<?php echo base_url() . $features_info->icon; ?>">
                                    <?php else: ?>
                                        <img src="http://placehold.it/350x260" alt="Please Connect Your Internet">
                                    <?php endif; ?>
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="width: 210px;"></div>
                                <div>
                                    <span class="btn btn-default btn-file">
                                        <span class="fileinput-new">
                                            <input type="file" name="icon" value="upload"
                                                   data-buttonText="<?= lang('choose_file') ?>" id="myImg"/>
                                            <span class="fileinput-exists"><?= lang('change') ?></span>
                                        </span>
                                        <a href="#" class="btn btn-default fileinput-exists"
                                           data-dismiss="fileinput"><?= lang('remove') ?></a>

                                </div>

                                <div id="valid_msg" style="color: #e11221"></div>

                            </div>
                        </div>
                    </div>
                <?php }
                if (!empty($type) && $type == 'latest_project' || !empty($features_info->type) && $features_info->type == 'latest_project') { ?>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('image') ?></label>
                        <div class="col-lg-7">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 210px;">
                                    <?php if (!empty($features_info->icon) && $features_info->icon != '') : ?>
                                        <img src="<?php echo base_url() . $features_info->icon; ?>">
                                    <?php else: ?>
                                        <img src="http://placehold.it/350x260" alt="Please Connect Your Internet">
                                    <?php endif; ?>
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="width: 210px;"></div>
                                <div>
                                    <span class="btn btn-default btn-file">
                                        <span class="fileinput-new">
                                            <input type="file" name="icon" value="upload"
                                                   data-buttonText="<?= lang('choose_file') ?>" id="myImg"/>
                                            <span class="fileinput-exists"><?= lang('change') ?></span>
                                        </span>
                                        <a href="#" class="btn btn-default fileinput-exists"
                                           data-dismiss="fileinput"><?= lang('remove') ?></a>

                                </div>

                                <div id="valid_msg" style="color: #e11221"></div>

                            </div>
                        </div>
                    </div>
                <?php } else {
                    ?>
                    <input required type="text" name="icon"
                           placeholder="<?= lang('enter') . ' ' . lang('icon') ?>"
                           class="form-control" value="<?php
                    if (!empty($features_info->icon)) {
                        echo $features_info->icon;
                    }
                    ?>"/>
                <?php } ?>
            </div>
        </div>
        <div class="form-group">
            <label for="field-1" class="col-sm-3 control-label"><?= lang('title') ?>
                <span class="required">*</span></label>

            <div class="col-sm-7">
                <input required type="text" name="title"
                       placeholder="<?= lang('enter') . ' ' . lang('title') ?>"
                       class="form-control" value="<?php
                if (!empty($features_info->title)) {
                    echo $features_info->title;
                }
                ?>"/>
            </div>
        </div>

        <div class="form-group">
            <label for="field-1" class="col-sm-3 control-label"><?= lang('description') ?></label>

            <div class="col-sm-8">
                    <textarea name="description"
                              class="form-control "><?= (!empty($features_info->description) ? $features_info->description : ''); ?></textarea>
            </div>
        </div>

        <div class="form-group">
            <label for="field-1" class="col-sm-3 control-label"><?= lang('status') ?></label>

            <div class="col-sm-8">
                <div class="col-sm-4 row">
                    <div class="checkbox-inline c-checkbox">
                        <label>
                            <input <?= (!empty($features_info->status) && $features_info->status == '1' ? 'checked' : ''); ?>
                                class="select_one" type="checkbox" name="status" value="1">
                            <span class="fa fa-check"></span> <?= lang('published') ?>
                        </label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="checkbox-inline c-checkbox">
                        <label>
                            <input <?= (!empty($features_info->status) && $features_info->status == '0' ? 'checked' : ''); ?>
                                class="select_one" type="checkbox" name="status" value="0">
                            <span class="fa fa-check"></span> <?= lang('unpublished') ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-7">
                <button type="submit" id="sbtn" name="sbtn" value="1"
                        class="btn btn-primary"><?= lang('save') ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
