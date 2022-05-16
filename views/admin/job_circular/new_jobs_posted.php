<?php
if (!empty($job_posted)) {
    $job_circular_id = $job_posted->job_circular_id;
    $companies_id = $job_posted->companies_id;
    $employment_type=$job_posted->employment_type;
} else {
    $job_circular_id = null;
    $companies_id = null;
    $employment_type=null;
}
echo form_open(base_url('admin/job_circular/save_job_posted/' . $job_circular_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
    <div class="modal-header">
        <h5 class="modal-title"><?= lang('new') . ' ' . lang('jobs_posted') ?></h5>
        <div class="pull-right ml">
            <?= btn_pdf('admin/job_circular/jobs_posted_pdf/' . $job_circular_id) ?>
        </div>    
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body wrap-modal wrap">
        <div class="row mb-3" id="border-none">
            <label for="field-1" class="col-sm-3 form-label"><?= lang('job_title') ?><span
                        class="required"> *</span></label>
            <div class="col-sm-5">
                <input type="text" name="job_title" value="<?php
                if (!empty($job_posted->job_title)) {
                    echo $job_posted->job_title;
                }
                ?>" class="form-control" required="1" placeholder="<?= lang('enter') . ' ' . lang('job_title') ?>">
            </div>
        </div>
        <?php super_admin_form_modal($companies_id, 3, 5) ?>
        <div class="row mb-3">
            <label class="col-sm-3 form-label"><?= lang('designation') ?>
                <span class="required">*</span></label>
            <div class="col-sm-5">
                <select name="designations_id" class="form-control select_box" style="width:100%"
                        required>
                    <option value=""><?= lang('select') . ' ' . lang('designation') ?></option>
                    <?php if (!empty($all_department_info)): foreach ($all_department_info as $dept_name => $v_department_info) : ?>
                        <?php if (!empty($v_department_info)):
                            if (!empty($all_dept_info[$dept_name]->deptname)) {
                                $deptname = $all_dept_info[$dept_name]->deptname;
                            } else {
                                $deptname = lang('undefined_department');
                            }
                            ?>
                            <optgroup label="<?php echo $deptname; ?>">
                                <?php foreach ($v_department_info as $designation) : ?>
                                    <option
                                            value="<?php echo $designation->designations_id; ?>"
                                        <?php
                                        if (!empty($job_posted->designations_id)) {
                                            echo $designation->designations_id == $job_posted->designations_id ? 'selected' : '';
                                        }
                                        ?>><?php echo $designation->designations ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $('select[name="companies_id"]').on('change', function () {
                    var companies_id = $(this).val();
                    if (companies_id) {
                        $.ajax({
                            url: '<?= base_url('admin/global_controller/json_get_department/')?>' + companies_id,
                            type: "GET",
                            dataType: "json",
                            success: function (data) {
                                $('select[name="designations_id"]').empty();
                                $.each(data, function (key, value) {
                                    $('select[name="designations_id"]').append('<optgroup label="' + key + '">');
                                    $.each(value, function (keys, values) {
                                        $('select[name="designations_id"]').append('<option value="' + values.designations_id + '">' + values.designations + '</option>');
                                    });
                                    $('select[name="designations_id"]').append('</optgroup>');
                                });
                                $(".select_box").select2({});

                            }
                        });
                    } else {
                        $('select[name="designations_id"]').empty();
                    }
                });
            });
        </script>
        <div class="row mb-3" id="border-none">
            <label for="field-1" class="col-sm-3 form-label"><?= lang('employment_type') ?><span
                        class="required"> *</span></label>
            <div class="col-sm-5">
                <select class="form-select" id="employment_type" name="employment_type" required>
                   <option value="<?= lang('contractual') ?>" <?= ($employment_type == lang('contractual') ? 'selected' : '') ?> ><?= lang('contractual') ?></option>

                   <option value="<?= lang('full_time') ?>" <?= ($employment_type == lang('full_time') ? 'selected' : '') ?> ><?= lang('full_time') ?></option>

                   <option value="<?= lang('part_time') ?>" <?= ($employment_type == lang('part_time') ? 'selected' : '') ?> ><?= lang('part_time') ?></option>

                </select> 
            
            </div>
        </div>
        <div class="row mb-3" id="border-none">
            <label for="field-1" class="col-sm-3 form-label"><?= lang('experience') ?><span
                        class="required"> *</span></label>
            <div class="col-sm-5">
                <input type="text" name="experience" value="<?php
                if (!empty($job_posted->experience)) {
                    echo $job_posted->experience;
                }
                ?>" class="form-control" required="1" placeholder="<?= lang('experience_placeholder') ?>">
            </div>
        </div>
      
        <div class="row mb-3" id="border-none">
            <label for="field-1" class="col-sm-3 form-label"><?= lang('salary_range') ?><span
                        class="required"> *</span></label>
            <div class="col-sm-5">
                <input type="text" name="salary_range" value="<?php
                if (!empty($job_posted->salary_range)) {
                    echo $job_posted->salary_range;
                }
                ?>" class="form-control" required="1" placeholder="<?= lang('salary_range_placeholder') ?>">
            </div>
        </div>
        <div class="row mb-3" id="border-none">
            <label for="field-1" class="col-sm-3 form-label"><?= lang('vacancy_no') ?><span
                        class="required"> *</span></label>
            <div class="col-sm-5">
                <input type="number" name="vacancy_no" value="<?php
                if (!empty($job_posted->vacancy_no)) {
                    echo $job_posted->vacancy_no;
                }
                ?>" class="form-control" required="1" placeholder="<?= lang('enter') . ' ' . lang('vacancy_no') ?>">
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-3 form-label"><?= lang('posted_date') ?> <span
                        class="required"> *</span></label>
            <div class="col-sm-5">
                <div class="input-group ">
                    <input type="text" required value="<?php
                    if (!empty($job_posted->posted_date)) {
                        echo date('d-m-Y H-i', strtotime($job_posted->posted_date));
                    } else {
                        date('d-m-Y H-i');
                    }
                    ?>" class="form-control datepicker" name="posted_date">

                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                    
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-3 form-label"><?= lang('last_date_to_apply') ?> <span
                        class="required"> *</span></label>
            <div class="col-sm-5">
                <div class="input-group ">
                    <input type="text" required value="<?php
                    if (!empty($job_posted->last_date)) {
                        echo date('d-m-Y H-i', strtotime($job_posted->last_date));
                    } else {
                        date('d-m-Y H-i');
                    }
                    ?>" class="form-control datepicker" name="last_date">

                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                    
                </div>
            </div>
        </div>
        <?php
        if (!empty($job_posted)) {
            $job_circular_id = $job_posted->job_circular_id;
        } else {
            $job_circular_id = null;
        }
        ?>
        <?= custom_form_Fields(14, $job_circular_id); ?>
        <div class="row mb-3" id="border-none">
            <label for="field-1" class="col-sm-3 form-label"><?= lang('description') ?><span
                        class="required"> *</span></label>
            <div class="col-sm-5">
                <textarea class="form-control textarea_2" id="elm1" name="description"><?php
                    if (!empty($job_posted->description)) {
                        echo $job_posted->description;
                    }
                    ?></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <label for="field-1" class="col-sm-3 form-label"><?= lang('status') ?></label>

            <div class="col-sm-5">
                <div class="row">
                <div class="col-sm-5">
                    <div class="form-check form-check-primary mb-3">
                        <input <?= (!empty($job_posted->status) && $job_posted->status == 'published' || empty($job_posted) ? 'checked' : ''); ?>
                                    class="select_one form-check-input" type="checkbox" name="status" value="published" id="published">
                        <label class="form-check-label" for="published"><?= lang('published') ?></label>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-check form-check-primary mb-3">
                        <input <?= (!empty($job_posted->status) && $job_posted->status == 'unpublished' ? 'checked' : ''); ?> class="select_one form-check-input" type="checkbox" name="status" value="unpublished" id="unpublished">
                        <label class="form-check-label" for="unpublished"><?= lang('unpublished') ?></label>
                    </div>
                </div></div>
            </div>
        </div>
        <div class="row mb-3" id="border-none">
            <label for="field-1" class="col-sm-3 form-label"><?= lang('permission') ?> <span class="required">*</span></label>
            <div class="col-sm-6">
                <div class="form-check form-check-primary mb-3 col-xl-6">
                    <input id="everyone" <?php
                        if (!empty($job_posted->permission) && $job_posted->permission == 'all') {
                            echo 'checked';
                        }
                        ?> type="radio" name="permission" value="everyone" class="form-check-input">
                    <label class="form-check-label" for="everyone"><?= lang('everyone') ?> <i title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                    </label>
                </div>
                <div class="form-check form-check-primary mb-3 col-xl-6">
                    <input id="custom_permission" <?php
                        if (!empty($job_posted->permission) && $job_posted->permission != 'all') {
                            echo 'checked';
                        } elseif (empty($job_posted)) {
                            echo 'checked';
                        }
                        ?> type="radio" name="permission" value="custom_permission" class="form-check-input">
                    <label class="form-check-label" for="custom_permission"><?= lang('custom_permission') ?> <i title="<?= lang('permission_for_customization') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                    </label>
                </div>
            </div>
        </div>

        <div class="row mb-3 <?php
            if (!empty($job_posted->permission) && $job_posted->permission != 'all') {
                echo 'show';
            }
            ?>" id="permission_user">
            <label class="col-sm-3 form-label"><?= lang('select') . ' ' . lang('users') ?>
                <span class="required">*</span></label>
            <div class="col-sm-5">
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
                            <input class="form-check-input" type="checkbox" id="user_<?= $v_user->user_id ?>"  value="<?= $v_user->user_id ?>" name="assigned_to[]"  data-name="<?= $v_user->username;?>" 
                                <?php
                                if (!empty($job_posted->permission) && $job_posted->permission != 'all') {
                                    $get_permission = json_decode($job_posted->permission);
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
                            if (!empty($job_posted->permission) && $job_posted->permission != 'all') {
                                $get_permission = json_decode($job_posted->permission);

                                foreach ($get_permission as $user_id => $v_permission) {
                                    if ($user_id == $v_user->user_id) {
                                        echo 'show';
                                    }
                                }

                            }
                            ?> " id="action_<?= $v_user->user_id ?>">
                            <div class="form-check form-check-primary mb-3 mr">
                                <input class="form-check-input" type="checkbox" id="view_<?= $v_user->user_id ?>" checked name="action_<?= $v_user->user_id ?>[]" disabled  value="view">
                                <label class="form-check-label" for="view_<?= $v_user->user_id ?>">
                                    <?= lang('can') . ' ' . lang('view') ?>
                                </label>
                            </div>
                                  

                            <div class="form-check form-check-primary mb-3 mr">
                                <input class="form-check-input" type="checkbox" value="edit" name="action_<?= $v_user->user_id ?>[]" <?php if (!empty($disable)) {  echo 'disabled' . ' ' . 'checked';  } ?> id="edit_<?= $v_user->user_id ?>"
                                <?php

                                if (!empty($job_posted->permission) && $job_posted->permission != 'all') {
                                    $get_permission = json_decode($job_posted->permission);

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
                                <input class="form-check-input" name="action_<?= $v_user->user_id ?>[]" type="checkbox" value="delete" <?php if (!empty($disable)) {  echo 'disabled' . ' ' . 'checked'; } ?> id="delete_<?= $v_user->user_id ?>"
                                <?php

                                if (!empty($job_posted->permission) && $job_posted->permission != 'all') {
                                    $get_permission = json_decode($job_posted->permission);
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
                            <input id="<?= $v_user->user_id ?>" type="hidden"  name="action_<?= $v_user->user_id ?>[]" value="view">
                        </div>
                        <?php  } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <button type="submit" class="btn btn-primary w-md waves-effect waves-light"><?= lang('save') ?></button>            
        </div>
    </div>
<?php echo form_close(); ?>
<style type="text/css">
    .action{
        display: inline-flex;
    }
</style>