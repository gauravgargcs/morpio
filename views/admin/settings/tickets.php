<div class="card"  xmlns="http://www.w3.org/1999/html">
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>
    <!-- Start Form -->
    <form role="form" id="form" action="<?php echo base_url(); ?>admin/settings/save_tickets" method="post" class="form-horizontal">
        <div class="card-body">
            <h4 class="card-title mb-4"><?= lang('tickets_settings') ?></h4>
            <?php echo validation_errors(); ?>
            <input type="hidden" name="settings" value="<?= $load_setting ?>">
            
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('default_department') ?></label>
                <div class="col-lg-5">
                    <select name="default_department" class="form-control select_box">
                        <?php
                        $department_info = get_result('tbl_departments');
                        if (!empty($department_info)) {
                            foreach ($department_info as $v_department) : ?>
                                <option
                                        value="<?= $v_department->departments_id ?>"<?= (config_item('default_department') == $v_department->departments_id ? ' selected="selected"' : '') ?>><?= $v_department->deptname ?></option>
                            <?php endforeach;
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('default_status') ?></label>
                <div class="col-lg-5">
                    <select name="default_status" class="form-select">
                        <?php
                        $status_info = $this->db->get('tbl_status')->result();
                        if (!empty($status_info)) {
                            foreach ($status_info as $v_status) {
                                ?>
                                <option value="<?= $v_status->status ?>"<?= (config_item('default_status') == $v_status->status ? ' selected="selected"' : '') ?>><?= $v_status->status ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-lg-2">
                    <a data-bs-toggle="modal" data-bs-target="#myModal_lg" href="<?= base_url() ?>admin/settings/manage_status/status" class="btn btn-sm btn-success mt-btn-10" title="<?= lang('new') . ' ' . lang('status') ?>"><i class="fa fa-plus text-white"></i></a>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('default_priority') ?></label>
                <div class="col-lg-5">
                    <?php
                    $all_priority = $this->db->get('tbl_priority')->result();
                    foreach ($all_priority as $priority) {
                        $options[$priority->priority] = $priority->priority;
                    }
                    echo form_dropdown('default_priority', $options, config_item('default_priority'), 'style="width:100%" class="form-select"'); ?>
                </div>
                <div class="col-lg-2">
                    <a data-bs-toggle="modal" data-bs-target="#myModal_lg"
                       href="<?= base_url() ?>admin/settings/manage_status/priority"
                       class="btn btn-sm btn-success mt-btn-10" title="<?= lang('new') . ' ' . lang('priority') ?>"><i class="fa fa-plus text-white"></i></a>
                </div>
            </div>
            <div class="row mt-3">
                <h4 class="card-title mb-4"><?= lang('leads_settings') ?></h4>
            </div>

            <div class="row mb-3">
                <label
                        class="col-lg-3 col-form-label"><?= lang('default') . ' ' . lang('source') ?></label>
                <div class="col-lg-5">
                    <select name="default_leads_source" style="width: 100%"
                            class="form-control select_box">
                        <?php
                        $all_lead_source = get_result('tbl_lead_source');
                        if (!empty($all_lead_source)) {
                            foreach ($all_lead_source as $lead_source) {
                                ?>
                                <option
                                        value="<?= $lead_source->lead_source_id ?>"<?= (config_item('default_leads_source') == $lead_source->lead_source_id ? ' selected="selected"' : '') ?>><?= $lead_source->lead_source ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-lg-2">
                    <a href="<?= base_url() ?>admin/settings/lead_source" target="_blank" class="btn btn-sm btn-success mt-btn-10" title="<?= lang('new') . ' ' . lang('source') ?>"><i class="fa fa-plus text-white"></i></a>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('default') . ' ' . lang('status') ?></label>
                <div class="col-lg-5">
                    <select name="default_lead_status" style="width: 100%"
                            class="form-control select_box">
                        <?php
                        $all_lead_status = get_result('tbl_lead_status');
                        if (!empty($all_lead_status)) {
                            foreach ($all_lead_status as $lead_status) {
                                $lead_type = '';
                                if (!empty($lead_status->lead_type)) {
                                    $lead_type = '(' . lang($lead_status->lead_type) . ')';
                                }
                                ?>
                                <option
                                        value="<?= $lead_status->lead_status_id ?>"<?= (config_item('default_lead_status') == $lead_status->lead_status_id ? ' selected="selected"' : '') ?>><?= $lead_status->lead_status . ' ' . $lead_type ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-lg-2">
                    <a href="<?= base_url() ?>admin/settings/lead_status" target="_blank" class="btn btn-sm btn-success mt-btn-10" title="<?= lang('new') . ' ' . lang('status') ?>"><i class="fa fa-plus text-white"></i></a>
                </div>
            </div>
            <?php $lead_permission = config_item('default_lead_permission'); ?>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"><?= lang('permission_for_new_leads') ?></label>
                <div class="col-sm-6">
                    <div class="form-check form-radio-outline form-radio-primary mb-3">
                        <input id="user_permission_1" <?php
                            if (isset($lead_permission) && $lead_permission == 'all') {
                                echo 'checked';
                            }
                            ?> type="radio" name="default_lead_permission" value="everyone" class="form-check-input">
                        <label for="user_permission_1" class="form-check-label"> <?= lang('everyone') ?>
                            <i title="<?= lang('permission_for_all') ?>"
                               class="fa fa-question-circle" data-bs-toggle="tooltip"
                               data-placement="top"></i>
                        </label>
                    </div>
                    <div class="form-check form-radio-outline form-radio-primary mb-3">
                        <input id="user_custom_permission" <?php
                            if (isset($lead_permission) && $lead_permission != 'all') {
                                echo 'checked';
                            } elseif (empty($lead_permission)) {
                                echo 'checked';
                            }
                            ?> type="radio" name="default_lead_permission" value="custom_permission"  class="form-check-input">
                        <label for="user_custom_permission" class="form-check-label"> <?= lang('custom_permission') ?> <i  title="<?= lang('permission_for_customization') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-placement="top"></i>
                        </label>
                    </div>
                </div>
            </div>
            <div class="row mb-3 <?php
                if (!empty($lead_permission) && $lead_permission != 'all') {
                    echo 'show';
                }
                ?>" id="permission_user_1">
                <label for="field-1"  class="col-lg-3 col-form-label"><?= lang('select') . ' ' . lang('users') ?>
                    <span class="required">*</span></label>
                <div class="col-lg-6">
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
                                <input type="checkbox"
                                        <?php
                                        if (!empty($lead_permission) && $lead_permission != 'all') {
                                            $get_permission = json_decode(config_item('default_lead_permission'));
                                            foreach ($get_permission as $user_id => $v_permission) {
                                                if ($user_id == $v_user->user_id) {
                                                    echo 'checked';
                                                }
                                            }
                                            
                                        }
                                        ?>  value="<?= $v_user->user_id ?>" name="assigned_to[]" class="form-check-input" id ="user_<?= $v_user->user_id ?>"  data-name="<?= $v_user->username;?>">
                                <label for="user_<?= $v_user->user_id ?>" class="form-check-label"><?= $v_user->username . ' ' . $role ?></label>
                            </div>
                            <div class="action_1 p
                                <?php
                                if (!empty($lead_permission) && $lead_permission != 'all') {
                                    $get_permission = json_decode(config_item('default_lead_permission'));
                                    
                                    foreach ($get_permission as $user_id => $v_permission) {
                                        if ($user_id == $v_user->user_id) {
                                            echo 'show';
                                        }
                                    }
                                    
                                }
                                ?> " id="action_1<?= $v_user->user_id ?>">
                                <div class="form-check form-check-primary mb-3 mr">     
                                    <input id="view_<?= $v_user->user_id ?>" checked  type="checkbox" name="action_1<?= $v_user->user_id ?>[]"  disabled   value="view" class="form-check-input">
                                    <label class="form-check-label" for="view_<?= $v_user->user_id ?>"><?= lang('can') . ' ' . lang('view') ?>
                                    </label>
                                </div>
                                <div class="form-check form-check-primary mb-3 mr">
                                    <input <?php if (!empty($disable)) {
                                        echo 'disabled' . ' ' . 'checked';
                                    } ?> id="edit_<?= $v_user->user_id ?>"
                                        <?php
                                        
                                        if (!empty($lead_permission) && $lead_permission != 'all') {
                                            $get_permission = json_decode(config_item('default_lead_permission'));
                                            
                                            foreach ($get_permission as $user_id => $v_permission) {
                                                if ($user_id == $v_user->user_id) {
                                                    if (in_array('edit', $v_permission)) {
                                                        echo 'checked';
                                                    };
                                                    
                                                }
                                            }
                                            
                                        }
                                        ?>   type="checkbox"  value="edit"  name="action_<?= $v_user->user_id ?>[]" class="form-check-input">
                                        
                                    <label class="form-check-label" for="edit_<?= $v_user->user_id ?>"><?= lang('can') . ' ' . lang('edit') ?></label>
                                </div>
                                <div class="form-check form-check-primary mb-3 mr">
                                    <input <?php if (!empty($disable)) {
                                        echo 'disabled' . ' ' . 'checked';
                                    } ?> id="delete_<?= $v_user->user_id ?>"
                                        <?php
                                        
                                        if (!empty($lead_permission) && $lead_permission != 'all') {
                                            $get_permission = json_decode(config_item('default_lead_permission'));
                                            foreach ($get_permission as $user_id => $v_permission) {
                                                if ($user_id == $v_user->user_id) {
                                                    if (in_array('delete', $v_permission)) {
                                                        echo 'checked';
                                                    };
                                                }
                                            }
                                            
                                        }
                                        ?> name="action_<?= $v_user->user_id ?>[]" type="checkbox" value="delete" class="form-check-input">
                                    <label class="form-check-label" for="delete_<?= $v_user->user_id ?>"><?= lang('can') . ' ' . lang('delete') ?>
                                    </label>
                                </div>
                                <input id="<?= $v_user->user_id ?>" type="hidden" name="action_<?= $v_user->user_id ?>[]" value="view">
                            </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-lg-3 col-form-label"></label>
                <div class="col-lg-6">
                    <button type="submit" class="btn btn-xs btn-primary"><?= lang('save_changes') ?></button>
                </div>
            </div>
        </div>
    </form>
</div>