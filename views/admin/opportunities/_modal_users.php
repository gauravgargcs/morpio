 <form id="form_validation"  action="<?php echo base_url() ?>admin/opportunities/update_member/<?php if (!empty($opportunities_info->opportunities_id)) echo $opportunities_info->opportunities_id; ?>" method="post" class="form-horizontal">
    <div class="modal-header">
        <h5 class="modal-title"><?= lang('all_users') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body wrap-modal wrap">
        <div class="col-xl-12"> 
            <div class="row mb-3">
                <label for="field-1" class="col-xl-3 form-label"><?= lang('assined_to') ?> <span class="required">*</span></label>
                <div class="form-check mb-3 col-xl-3">
                    <input id="everyone" <?php
                            if (!empty($opportunities_info->permission) && $opportunities_info->permission == 'all') {
                                echo 'checked';
                            }
                            ?> type="radio" name="permission" value="everyone" class="form-check-input">
                    <label class="form-check-label" for="everyone"><?= lang('everyone') ?> <i title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                    </label>
                </div>
                <div class="form-check mb-3 col-xl-6">
                    <input id="custom_permission" <?php
                            if (!empty($opportunities_info->permission) && $opportunities_info->permission != 'all') {
                                echo 'checked';
                            } elseif (empty($opportunities_info)) {
                                echo 'checked';
                            }
                            ?> type="radio" name="permission" value="custom_permission" class="form-check-input">
                    <label class="form-check-label" for="custom_permission"><?= lang('custom_permission') ?> <i title="<?= lang('permission_for_customization') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                    </label>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-xl-12"> 
                <div class="row <?php
                    if (!empty($opportunities_info->permission) && $opportunities_info->permission != 'all') {
                        echo 'show';
                    }
                    ?>" id="permission_user">
                    <label class="col-sm-3 form-label"><?= lang('select') . ' ' . lang('users') ?><span class="required">*</span></label>
                    <div class="col-xl-9">
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
                            <div class="form-check form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="user_<?= $v_user->user_id ?>"  value="<?= $v_user->user_id ?>" name="assigned_to[]"  data-name="<?= $v_user->username;?>" 
                                        <?php
                                        if (!empty($opportunities_info->permission) && $opportunities_info->permission != 'all') {
                                            $get_permission = json_decode($opportunities_info->permission);
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
                                if (!empty($opportunities_info->permission) && $opportunities_info->permission != 'all') {
                                    $get_permission = json_decode($opportunities_info->permission);

                                    foreach ($get_permission as $user_id => $v_permission) {
                                        if ($user_id == $v_user->user_id) {
                                            echo 'show';
                                        }
                                    }

                                }
                                ?> " id="action_<?= $v_user->user_id ?>">
                                <div class="form-check form-check mb-3 mr">
                                    <input class="form-check-input" type="checkbox" id="view_<?= $v_user->user_id ?>" checked name="action_<?= $v_user->user_id ?>[]" disabled  value="view">
                                    <label class="form-check-label" for="view_<?= $v_user->user_id ?>">
                                        <?= lang('can') . ' ' . lang('view') ?>
                                    </label>
                                </div>
                                      

                                <div class="form-check form-check mb-3 mr">
                                    <input class="form-check-input" type="checkbox" value="edit" name="action_<?= $v_user->user_id ?>[]" <?php if (!empty($disable)) {
                                        echo 'disabled' . ' ' . 'checked';
                                    } ?>  id="edit_<?= $v_user->user_id ?>" 
                                        <?php
                                        if (!empty($opportunities_info->permission) && $opportunities_info->permission != 'all') {
                                            $get_permission = json_decode($opportunities_info->permission);

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

                                <div class="form-check form-check mb-3 mr">
                                    <input class="form-check-input" name="action_<?= $v_user->user_id ?>[]" type="checkbox" value="delete" <?php if (!empty($disable)) {
                                            echo 'disabled' . ' ' . 'checked';
                                        } ?> id="delete_<?= $v_user->user_id ?>"
                                        <?php

                                        if (!empty($opportunities_info->permission) && $opportunities_info->permission != 'all') {
                                            $get_permission = json_decode($opportunities_info->permission);
                                            foreach ($get_permission as $user_id => $v_permission) {
                                                if ($user_id == $v_user->user_id) {
                                                    if (in_array('delete', $v_permission)) {
                                                        echo 'checked';
                                                    };
                                                }
                                            }

                                        }
                                        ?>>
                                    <label class="form-check-label" for="delete_<?= $v_user->user_id ?>">
                                        <?= lang('can') . ' ' . lang('delete') ?>
                                    </label>
                                </div>
                                <input id="<?= $v_user->user_id ?>" type="hidden" name="action_<?= $v_user->user_id ?>[]" value="view">
                            </div>
                            <?php } ?>
                        </div>
                    <?php }  ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <button type="submit" class="btn btn-primary w-md waves-effect waves-light"><?= lang('update') ?></button>            
        </div>
    </div>
</form>
<style type="text/css">
    .action{
        display: inline-flex;
    }
</style>        