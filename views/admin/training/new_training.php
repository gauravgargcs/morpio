<?php
$created = can_action('101', 'created');
$edited = can_action('101', 'edited');
if (!empty($created) || !empty($edited)) {
    if (!empty($training_info)) {
        $training_id = $training_info->training_id;
        $companies_id = $training_info->companies_id;
    } else {
        $training_id = null;
        $companies_id = null;
    }
    ?>
<div class="modal-header">
    <h5 class="modal-title"><?= lang('new') . ' ' . lang('training') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<?php echo form_open(base_url('admin/training/save_training/' . $training_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
    <div class="modal-body wrap-modal wrap">
        <?php super_admin_form_modal($companies_id, 3, 8) ?>
        <div class="row mb-3" id="border-none">
            <label class="col-sm-3 form-label"><?= lang('employee') ?> <span class="required">*</span></label>
            <div class="col-sm-8">
                <select name="user_id" style="width: 100%" id="employee" class="form-control modal_select_box" required>
                    <option value=""><?= lang('select_employee') ?>...</option>
                    <?php
                    $all_employee = $this->training_model->get_all_employee($companies_id);
                    if (!empty($all_employee)): ?>
                        <?php foreach ($all_employee as $dept_name => $v_all_employee) : ?>
                            <optgroup label="<?php echo $dept_name; ?>">
                                <?php if (!empty($v_all_employee)):foreach ($v_all_employee as $v_employee) : ?>
                                    <option value="<?php echo $v_employee->user_id; ?>"
                                        <?php
                                        if (!empty($training_info->user_id)) {
                                            $user_id = $training_info->user_id;
                                        } else {
                                            $user_id = $this->session->userdata('user_id');
                                        }
                                        if (!empty($user_id)) {
                                            echo $v_employee->user_id == $user_id ? 'selected' : '';
                                        }
                                        ?>><?php echo $v_employee->fullname . ' ( ' . $v_employee->designations . ' )' ?></option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
            
        <div class="row mb-3">
            <label class="col-sm-3 form-label"><?= lang('course_training') ?> <span
                        class="required">*</span></label>
            <div class="col-sm-8">
                <input type="text" name="training_name" required class="form-control" value="<?php
                if (!empty($training_info->training_name)) {
                    echo $training_info->training_name;
                }
                ?>"/>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 form-label"><?= lang('vendor') ?> <span class="required">*</span></label>
            <div class="col-sm-8">
                <input type="text" name="vendor_name" class="form-control" value="<?php
                if (!empty($training_info->vendor_name)) {
                    echo $training_info->vendor_name;
                }
                ?>" required/>
            </div>
        </div>

        <div class="row mb-3">
            <label class="form-label col-sm-3"><?= lang('start_date') ?><span
                        class="required">*</span></label>
            <div class="col-sm-8">
                <div class="input-group ">
                    <input type="text" name="start_date" value="<?php
                    if (!empty($training_info->start_date)) {
                        echo date('d-m-Y H-i', strtotime($training_info->start_date));
                    } else {
                        echo date('d-m-Y H-i');
                    }
                    ?>" class="form-control datepicker" required>
                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <label class="form-label col-sm-3"><?= lang('finish_date') ?><span
                        class="required">*</span></label>
            <div class="col-sm-8">
                <div class="input-group">
                    <input type="text" name="finish_date" value="<?php
                    if (!empty($training_info->finish_date)) {
                        echo date('d-m-Y H-i', strtotime($training_info->finish_date));
                    }else {
                        echo date('d-m-Y H-i');
                    }
                    ?>" class="form-control datepicker" required>
                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 form-label"><?= lang('training_cost') ?></label>
            <div class="col-sm-8">
                <input type="text" data-parsley-type="number" name="training_cost" class="form-control"
                       value="<?php
                       if (!empty($training_info->training_cost)) {
                           echo $training_info->training_cost;
                       }
                       ?>"/>
            </div>
        </div>
        <?php
        if (!empty($training_info)) {
            $training_id = $training_info->training_id;
        } else {
            $training_id = null;
        }
        ?>
        <?= custom_form_Fields(15, $training_id); ?>


        <div class="row mb-3" id="border-none">
            <label for="field-1" class="col-sm-3 form-label"><?= lang('status') ?> <span
                        class="required">*</span></label>
            <div class="col-sm-8">
                <select name="status" class="form-select" required>
                    <option
                            value="0 <?php if (!empty($training_info->status)) echo $training_info->status == 0 ? 'selected' : '' ?>">
                        <?= lang('pending') ?>
                    </option>
                    <option
                            value="1 <?php if (!empty($training_info->status)) echo $training_info->status == 1 ? 'selected' : '' ?>">
                        <?= lang('started') ?>
                    </option>
                    <option
                            value="2 <?php if (!empty($training_info->status)) echo $training_info->status == 2 ? 'selected' : '' ?>">
                        <?= lang('completed') ?>
                    </option>
                    <option
                            value="3 <?php if (!empty($training_info->status)) echo $training_info->status == 3 ? 'selected' : '' ?>">
                        <?= lang('terminated') ?>

                    </option>
                </select>
            </div>
        </div>

        <div class="row mb-3" id="border-none">
            <label for="field-1" class="col-sm-3 form-label"><?= lang('performance') ?></label>
            <div class="col-sm-8">
                <select name="performance" id="employee" class="form-select">
                    <option
                            value="0 <?php if (!empty($training_info->performance)) echo $training_info->performance == 0 ? 'selected' : '' ?>">
                        <?= lang('not_concluded') ?>
                    </option>
                    <option
                            value="1 <?php if (!empty($training_info->performance)) echo $training_info->performance == 1 ? 'selected' : '' ?>">
                        <?= lang('satisfactory') ?>

                    </option>
                    <option
                            value="2 <?php if (!empty($training_info->performance)) echo $training_info->performance == 2 ? 'selected' : '' ?>">
                        <?= lang('average') ?>
                    </option>
                    <option
                            value="3 <?php if (!empty($training_info->performance)) echo $training_info->performance == 3 ? 'selected' : '' ?>">
                        <?= lang('poor') ?>
                    </option>
                    <option
                            value="4 <?php if (!empty($training_info->performance)) echo $training_info->performance == 4 ? 'selected' : '' ?>">
                        <?= lang('excellent') ?>
                    </option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 form-label"><?= lang('remarks') ?></label>
            <div class="col-sm-8">
                <textarea class="form-control textarea_2" name="remarks" id="elm1"><?php
                    if (!empty($training_info->remarks)) {
                        echo $training_info->remarks;
                    }
                    ?></textarea>
            </div>
        </div>
        <div class="row mb-3" style="margin-bottom: 0px">
            <label for="field-1"
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
                            <?php
                            if (!empty($training_info->upload_file)) {
                                $uploaded_file = json_decode($training_info->upload_file);
                            }
                            if (!empty($uploaded_file)) {
                                foreach ($uploaded_file as $v_files_image) { ?>
                                    <div id="" class="col-sm-6 mt file-upload-row dz-image-preview dz-complete">
                                        <span data-dz-remove="" class="pull-right existing_image" style="cursor: pointer">
                                                        <i class="fa fa-times"></i>
                                                </span>
                                        <div class="preview box-content pr-lg">
                                            <?php if ($v_files_image->is_image == 1) { ?>
                                                <img data-dz-thumbnail=""
                                                     src="<?php echo base_url() . $v_files_image->path ?>"
                                                     class="img-thumbnail upload-thumbnail-sm"/>
                                            <?php } else { ?>
                                                <span data-toggle="tooltip" data-placement="top"
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
                                        <div class="box-content">
                                            <p class="clearfix mb0 p0">
                                                <span class="name pull-left" data-dz-name=""><?php echo $v_files_image->fileName ?></span>
                                                
                                            </p>
                                        </div>
                                    </div>
                                <?php }; ?>
                            <?php }; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3" id="border-none">
            <label for="field-1" class="col-sm-3 form-label"><?= lang('permission') ?> <span
                        class="required">*</span></label>
            <div class="col-sm-9">
                <div class="form-check form-check-primary mb-3 col-xl-4">
                    <input id="everyone" <?php
                            if (!empty($training_info->permission) && $training_info->permission == 'all') {
                                echo 'checked';
                            }
                            ?> type="radio" name="permission" value="everyone" class="form-check-input">
                    <label class="form-check-label" for="everyone"><?= lang('everyone') ?> <i title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                    </label>
                </div>
                <div class="form-check form-check-primary mb-3 col-xl-7">
                    <input id="custom_permission" <?php
                            if (!empty($training_info->permission) && $training_info->permission != 'all') {
                                echo 'checked';
                            } elseif (empty($training_info)) {
                                echo 'checked';
                            }
                            ?> type="radio" name="permission" value="custom_permission" class="form-check-input">
                    <label class="form-check-label" for="custom_permission"><?= lang('custom_permission') ?> <i title="<?= lang('permission_for_customization') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                    </label>
                </div>
            </div>
        </div>

        <div class="row mb-3 <?php
            if (!empty($training_info->permission) && $training_info->permission != 'all') {
                echo 'show';
            }
            ?>" id="permission_user">
            <label for="field-1" class="col-sm-3 form-label"><?= lang('select') . ' ' . lang('users') ?>
                <span  class="required">*</span></label>
            <div class="col-sm-9">
                <?php
                if (!empty($assign_user)) { ?>
                    <input type="text" name="search_assigned_user" value="" placeholder="<?=lang('search_by').' '.lang('username'); ?>" class="form-control mb-4" id="search_assigned_user" autocomplete="off">
                    <div data-simplebar style="max-height: 250px;">  
                        <?php 
                        foreach ($assign_user as $key => $v_user) {

                            if ($v_user->role_id == 1) {
                                $disable = true;
                                $role = '<strong class="badge btn-danger">' . lang('admin') . '</strong>';
                            } else {
                                $disable = false;
                                $role = '<strong class="badge btn-primary">' . lang('staff') . '</strong>';
                            }

                        ?>
                        <div class="form-check form-check-primary mb-3">
                            <input class="form-check-input" type="checkbox" id="user_<?= $v_user->user_id ?>"  value="<?= $v_user->user_id ?>" name="assigned_to[]"  data-name="<?= $v_user->username;?>" <?php
                                    if (!empty($training_info->permission) && $training_info->permission != 'all') {
                                        $get_permission = json_decode($training_info->permission);
                                        foreach ($get_permission as $user_id => $v_permission) {
                                            if ($user_id == $v_user->user_id) {
                                                echo 'checked';
                                            }
                                        }

                                    }
                                    ?>>
                            <label class="form-check-label" for="user_<?= $v_user->user_id ?>">
                                <?= $v_user->username . ' ' . $role ?>
                            </label>
                        </div>
                            
                        <div class="action p  <?php
                            if (!empty($training_info->permission) && $training_info->permission != 'all') {
                                $get_permission = json_decode($training_info->permission);

                                foreach ($get_permission as $user_id => $v_permission) {
                                    if ($user_id == $v_user->user_id) {
                                        echo 'show';
                                    }
                                }

                            }
                            ?>" id="action_<?= $v_user->user_id ?>">
                            <div class="form-check form-check-primary mb-3 mr">
                                <input class="form-check-input" type="checkbox" id="view_<?= $v_user->user_id ?>" checked name="action_<?= $v_user->user_id ?>[]" disabled  value="view">
                                <label class="form-check-label" for="view_<?= $v_user->user_id ?>">
                                    <?= lang('can') . ' ' . lang('view') ?>
                                </label>
                            </div>
                                  

                            <div class="form-check form-check-primary mb-3 mr">
                                <input class="form-check-input" type="checkbox" value="edit" name="action_<?= $v_user->user_id ?>[]" <?php if (!empty($disable)) {
                                        echo 'disabled' . ' ' . 'checked';
                                    } ?> id="edit_<?= $v_user->user_id ?>"
                                    <?php

                                    if (!empty($training_info->permission) && $training_info->permission != 'all') {
                                        $get_permission = json_decode($training_info->permission);

                                        foreach ($get_permission as $user_id => $v_permission) {
                                            if ($user_id == $v_user->user_id) {
                                                if (in_array('edit', $v_permission)) {
                                                    echo 'checked';
                                                };

                                            }
                                        }

                                    }
                                    ?>>
                                <label class="form-check-label" for="edit_<?= $v_user->user_id ?>">
                                    <?= lang('can') . ' ' . lang('edit') ?>
                                </label>
                            </div>

                            <div class="form-check form-check-primary mb-3 mr">
                                <input class="form-check-input" name="action_<?= $v_user->user_id ?>[]" type="checkbox" value="delete" <?php if (!empty($disable)) {
                                        echo 'disabled' . ' ' . 'checked';
                                    } ?> id="delete_<?= $v_user->user_id ?>"
                                    <?php

                                    if (!empty($training_info->permission) && $training_info->permission != 'all') {
                                        $get_permission = json_decode($training_info->permission);
                                        foreach ($get_permission as $user_id => $v_permission) {
                                            if ($user_id == $v_user->user_id) {
                                                if (in_array('delete', $v_permission)) {
                                                    echo 'checked';
                                                };
                                            }
                                        }

                                    }
                                    ?> >
                                <label class="form-check-label" for="delete_<?= $v_user->user_id ?>">
                                    <?= lang('can') . ' ' . lang('delete') ?>
                                </label>
                            </div>
                            <input id="<?= $v_user->user_id ?>" type="hidden"
                                   name="action_<?= $v_user->user_id ?>[]" value="view">
                        </div>
                        <?php  } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div id="file-modal-footer"></div>
    </div>
    <div class="modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <button id="file-save-btn" type="submit" class="btn btn-primary start-upload"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>            
        </div>
    </div>
<?php echo form_close(); ?>
<?php } ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('select[name="companies_id"]').on('change', function () {
            var companies_id = $(this).val();
            if (companies_id) {
                $.ajax({
                        url: '<?= base_url('admin/global_controller/json_get_employee/')?>' + companies_id,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('select[name="user_id"]').empty();
                            if (data) {
                                $.each(data, function (key, value) {
                                    $('select[name="user_id"]').append('<optgroup label="' + key + '">');
                                    $.each(value, function (keys, values) {
                                        $('select[name="user_id"]').append('<option value="' + values.user_id + '">' + values.fullname + '( ' + values.designations + ' )' + '</option>');
                                    });
                                    $('select[name="user_id"]').append('</optgroup>');
                                });
                            }
                        }
                    }
                );
            } else {
                $('select[name="user_id"]').empty();
            }
        })
        ;
    })
    ;
</script>
<style type="text/css">
    .action{
        display: inline-flex;
    }
</style>