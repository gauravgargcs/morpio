<?= message_box('success'); ?>
<?= message_box('error'); ?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
          <h4 class="mb-sm-0 font-size-18">  <?php echo $title ?> 
            </h4>

            <?php $this->load->view('admin/skote_layouts/title'); ?>
            
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="card"> 
        <div class="card-body">
            <div class="pull-right pr-lg">
                <a href="<?php echo base_url() ?>assets/sample/project_sample.xlsx" class="btn btn-primary"><i class="fa fa-download"> <?= lang('download_sample') ?></i></a>
            </div>
            <h4 class="card-title mb-4"><?= lang('import') . ' ' . lang('project') ?></h4>

            <form role="form" enctype="multipart/form-data" id="form"   action="<?php echo base_url(); ?>admin/projects/save_imported" method="post"  class="form-horizontal  ">
                <div class="row mb-3">
                    <label for="formFile"  class="col-xl-2 col-form-label">
                        <?= lang('choose_file') ?><span class="required">*</span></label>
                    <div class="col-xl-5">
                        <input class="form-control" type="file" id="formFile" name="upload_file" >
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-2 control-label"><?= lang('select_client') ?> <span
                            class="text-danger">*</span></label>
                    <div class="col-xl-5">
                        <select name="client_id" class="form-control select_box" style="width: 100%" required="">
                            <option value=""><?= lang('select_client') ?></option>
                            <?php
                            $all_client = get_result('tbl_client');
                            if (!empty($all_client)) {
                                foreach ($all_client as $v_client) {
                                    ?>
                                    <option value="<?= $v_client->client_id ?>" <?php
                                    if (!empty($project_info) && $project_info->client_id == $v_client->client_id) {
                                        echo 'selected';
                                    }
                                    ?>><?= $v_client->name ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3" id="border-none">
                    <label  class="col-xl-2 col-form-label"><?= lang('assined_to') ?> <span
                            class="required">*</span></label>
                    <div class="col-xl-5">
                        <div class="form-check form-radio-outline form-radio-primary mb-3">
                            <input id="everyone" <?php
                                if (!empty($leads_info->permission) && $leads_info->permission == 'all') {
                                    echo 'checked';
                                }
                                ?> type="radio" name="permission" value="everyone" class="form-check-input">
                            <label class="form-check-label" for="everyone">
                                 <?= lang('everyone') ?>
                                <i title="<?= lang('permission_for_all') ?>"
                                   class="fa fa-question-circle" data-bs-toggle="tooltip"
                                   data-bs-placement="top"></i>
                            </label>
                        </div>
                        <div class="form-check form-radio-outline form-radio-primary mb-3">
                            
                            <input id="custom_permission" <?php
                                if (!empty($leads_info->permission) && $leads_info->permission != 'all') {
                                    echo 'checked';
                                } elseif (empty($leads_info)) {
                                    echo 'checked';
                                }
                                ?> type="radio" name="permission" value="custom_permission" class="form-check-input">
                            <label class="form-check-label" for="custom_permission">
                                <?= lang('custom_permission') ?> <i
                                    title="<?= lang('permission_for_customization') ?>"
                                    class="fa fa-question-circle" data-bs-toggle="tooltip"
                                    data-bs-placement="top"></i>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3 <?php
                    if (!empty($leads_info->permission) && $leads_info->permission != 'all') {
                        echo 'show';
                    }
                    ?>" id="permission_user_1">
                    <label  class="col-xl-2 col-form-label"><?= lang('select') . ' ' . lang('users') ?>
                        <span  class="required">*</span></label>
                    <div class="col-xl-5">
                    <?php
                        if (!empty($assign_user)) { ?>
                            <input type="text" name="search_assigned_user" value="" placeholder="<?=lang('search_by').' '.lang('username'); ?>" class="form-control mb-4" id="search_assigned_user" autocomplete="off">
                            <div data-simplebar style="max-height: 250px;">  
                                <?php   foreach ($assign_user as $key => $v_user) {

                                if ($v_user->role_id == 1) {
                                    $disable = true;
                                    $role = '<strong class="badge btn-danger">' . lang('admin') . '</strong>';
                                } else {
                                    $disable = false;
                                    $role = '<strong class="badge btn-primary">' . lang('staff') . '</strong>';
                                }

                                ?>
                                <div class="form-check form-check-primary mb-3">
                                    <input type="checkbox"
                                            <?php
                                            if (!empty($leads_info->permission) && $leads_info->permission != 'all') {
                                                $get_permission = json_decode($leads_info->permission);
                                                foreach ($get_permission as $user_id => $v_permission) {
                                                    if ($user_id == $v_user->user_id) {
                                                        echo 'checked';
                                                    }
                                                }

                                            }
                                            ?>  value="<?= $v_user->user_id ?>" name="assigned_to[]" class="form-check-input" id ="user_<?= $v_user->user_id ?>" data-name="<?= $v_user->username;?>">
                                    <label class="form-check-label" for="user_<?= $v_user->user_id ?>"><?= $v_user->username . ' ' . $role ?>
                                    </label>
                                </div>
                                <div class="action_1 p
                                    <?php

                                    if (!empty($leads_info->permission) && $leads_info->permission != 'all') {
                                        $get_permission = json_decode($leads_info->permission);

                                        foreach ($get_permission as $user_id => $v_permission) {
                                            if ($user_id == $v_user->user_id) {
                                                echo 'show';
                                            }
                                        }

                                    }
                                    ?>" id="action_1<?= $v_user->user_id ?>">
                                    <div class="form-check form-check-primary mb-3 mr">         
                                        <input id="view_<?= $v_user->user_id ?>" checked type="checkbox" name="action_1<?= $v_user->user_id ?>[]" disabled value="view" class="form-check-input">
                                        <label class="form-check-label" for="view_<?= $v_user->user_id ?>"><?= lang('view') ?></label>
                                    </div>

                                    <div class="form-check form-check-primary mb-3 mr">   
                                        <input <?php if (!empty($disable)) { echo 'disabled' . ' ' . 'checked'; } ?> id="edit_<?= $v_user->user_id ?>"
                                            <?php

                                            if (!empty($leads_info->permission) && $leads_info->permission != 'all') {
                                                $get_permission = json_decode($leads_info->permission);

                                                foreach ($get_permission as $user_id => $v_permission) {
                                                    if ($user_id == $v_user->user_id) {
                                                        if (in_array('edit', $v_permission)) {
                                                            echo 'checked';
                                                        };

                                                    }
                                                }

                                            }
                                            ?> type="checkbox" value="edit" name="action_<?= $v_user->user_id ?>[]" class="form-check-input">
                                        <label class="form-check-label" for="edit_<?= $v_user->user_id ?>"><?= lang('edit') ?></label>
                                    </div>
                                    <div class="form-check form-check-primary mb-3 mr">         
                                        <input <?php if (!empty($disable)) { echo 'disabled' . ' ' . 'checked'; } ?> id="delete_<?= $v_user->user_id ?>"
                                            <?php

                                            if (!empty($leads_info->permission) && $leads_info->permission != 'all') {
                                                $get_permission = json_decode($leads_info->permission);
                                                foreach ($get_permission as $user_id => $v_permission) {
                                                    if ($user_id == $v_user->user_id) {
                                                        if (in_array('delete', $v_permission)) {
                                                            echo 'checked';
                                                        };
                                                    }
                                                }

                                            }
                                            ?> name="action_<?= $v_user->user_id ?>[]"  type="checkbox"  value="delete" class="form-check-input">
                                        <label class="form-check-label" for="delete_<?= $v_user->user_id ?>"><?= lang('delete') ?></label>
                                    </div>
                                    <input id="<?= $v_user->user_id ?>" type="hidden" name="action_<?= $v_user->user_id ?>[]" value="view">

                                </div>
                                <?php  } ?>
                            </div>
                        <?php }  ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-xl-2 col-form-label"></label>
                    <div class="col-xl-5">
                        <button type="submit" class="btn btn-xs btn-primary"></i> <?= lang('upload') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
