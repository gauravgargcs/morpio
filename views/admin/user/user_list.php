<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action('24', 'created');
$edited = can_action('24', 'edited');
$deleted = can_action('24', 'deleted');
?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4>

            <?php $this->load->view('admin/skote_layouts/title'); ?>
            

        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 1 ? 'active' : ''; ?>" href="<?= base_url('admin/user/user_list'); ?>"><?= lang('all_users') ?></a></li>
                    <?php if (!empty($created) || !empty($edited)){ ?>
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 2 ? 'active' : ''; ?>" href="#new" data-bs-toggle="tab"><?= lang('new_user') ?></a>
                    </li>
                    <?php } ?>
                </ul>
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane <?= $active == 1 ? 'active' : '' ?>" id="manage">
                        <h4 class="card-title mb-4"><?= lang('all_users') ?></h4>
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100" id="list_users_datatable">
                                <thead>
                                    <tr>
                                        <?php super_admin_opt_th() ?>
                                        <th class="col-sm-1"><?= lang('photo') ?></th>
                                        <th><?= lang('name') ?></th>
                                        <th class="col-sm-2"><?= lang('username') ?></th>
                                        <th class="col-sm-1"><?= lang('active') ?></th>
                                        <th class="col-sm-1"><?= lang('user_type') ?></th>
                                        <?php $show_custom_fields = custom_form_table(13, null);
                                        if (!empty($show_custom_fields)) {
                                            foreach ($show_custom_fields as $c_label => $v_fields) {
                                                if (!empty($c_label)) {
                                                    ?>
                                                    <th><?= $c_label ?> </th>
                                                <?php }
                                            }
                                        }
                                        ?>
                                        <th class="col-sm-2"><?= lang('action') ?></th>

                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($all_user_info)): foreach ($all_user_info as $v_user) :
                                    $account_info = $this->user_model->check_by(array('user_id' => $v_user->user_id), 'tbl_account_details');
                                    if (!empty($account_info)) {
                                        $can_edit = $this->user_model->can_action('tbl_users', 'edit', array('user_id' => $v_user->user_id));
                                        $can_delete = $this->user_model->can_action('tbl_users', 'delete', array('user_id' => $v_user->user_id));
                                        ?>
                                        <tr>
                                            <?php super_admin_opt_td($v_user->companies_id) ?>
                                            <td><img style="width: 36px;margin-right: 10px;"
                                                     src="<?= base_url() ?><?= $account_info->avatar ?>" class="img-circle">
                                            </td>
                                            <td>
                                                <?php if ($v_user->role_id != 2) { ?>
                                                    <a href="<?= base_url() ?>admin/user/user_details/<?= $v_user->user_id ?>"><?= $account_info->fullname ?></a>
                                                <?php } else { ?>
                                                    <?= $account_info->fullname ?>
                                                <?php } ?>

                                            </td>
                                            <td><?= ($v_user->username) ?></td>
                                            <td><?php if ($v_user->user_id != $this->session->userdata('user_id')): ?>
                                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                        <div class="change_user_status form-check form-switch mb-3">
                                                            <input class="form-check-input" data-id="<?= $v_user->user_id ?>" name="active" value="1" <?php
                                                            if (!empty($v_user->activated) && $v_user->activated == '1') {
                                                                echo 'checked';
                                                            }
                                                            ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>" data-onstyle="success btn-sm" data-offstyle="danger btn-sm" type="checkbox">
                                                        </div>
                                                    <?php } else { ?>
                                                        <?php if ($v_user->activated == 1): ?>
                                                            <span class="badge badge-soft-success"><?= lang('active') ?></span>
                                                        <?php else: ?>
                                                            <span class="badge badge-soft-danger"><?= lang('deactive') ?></span>
                                                        <?php endif; ?>
                                                    <?php } ?>
                                                <?php else: ?>
                                                    <?php if ($v_user->activated == 1): ?>
                                                        <span class="badge badge-soft-success"><?= lang('active') ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-soft-danger"><?= lang('deactive') ?></span>
                                                    <?php endif; ?>
                                                <?php endif ?>
                                                <?php
                                                if ($v_user->banned == 1) {
                                                    ?>
                                                    <span class="badge badge-soft-danger" data-bs-toggle='tooltip' data-bs-placement='top'
                                                          title="<?= $v_user->ban_reason ?>"><?= lang('banned') ?></span>
                                                <?php }
                                                ?></td>
                                            <td><?php
                                                if ($v_user->role_id == 1 && $v_user->super_admin == 'Yes') {
                                                    echo '<span class="text-danger">' . lang('super_admin') . '</span>';
                                                } elseif ($v_user->role_id == 1 && $v_user->super_admin == 'owner') {
                                                    echo '<span class="text-danger">' . lang('owner') . '</span>';
                                                } elseif ($v_user->role_id == 1) {
                                                    echo lang('admin');
                                                } elseif ($v_user->role_id == 3) {
                                                    echo lang('staff');
                                                } else {
                                                    echo lang('client');
                                                }
                                                ?></td>
                                            <?php $show_custom_fields = custom_form_table(13, $v_user->user_id);
                                            if (!empty($show_custom_fields)) {
                                                foreach ($show_custom_fields as $c_label => $v_fields) {
                                                    if (!empty($c_label)) {
                                                        ?>
                                                        <td><?= $v_fields ?> </td>
                                                    <?php }
                                                }
                                            }
                                            ?>
                                            <td><?php if ($v_user->user_id != $this->session->userdata('user_id')): ?>
                                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                        <?php if ($v_user->banned == 1): ?>
                                                            <a data-bs-toggle="tooltip" data-bs-placement="top"  class="btn btn-success btn-sm"  title="Click to <?= lang('unbanned') ?> "  href="<?php echo base_url() ?>admin/user/set_banned/0/<?php echo $v_user->user_id; ?>"><span  class="fa fa-check"></span></a>
                                                        <?php else: ?>
                                                            <span data-bs-toggle="tooltip" data-bs-placement="top"  title="Click to <?= lang('banned') ?> ">
                                                        <?php echo btn_banned_modal('admin/user/change_banned/' . $v_user->user_id); ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    <?php } ?>

                                                    <a data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-outline-info btn-sm" title="<?= lang('send') . ' ' . lang('wellcome_email') ?> "  href="<?php echo base_url() ?>admin/user/send_welcome_email/<?php echo $v_user->user_id; ?>"><span class="fa fa-envelope-o"></span></a>

                                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                        <?php echo btn_edit('admin/user/user_list/edit_user/' . $v_user->user_id); ?>
                                                    <?php }
                                                    if (!empty($can_delete) && !empty($deleted)) {
                                                        ?>
                                                        <?php echo btn_delete('admin/user/delete_user/' . $v_user->user_id); ?>
                                                    <?php } ?>
                                                <?php endif; ?>
                                                <?php if( !$v_user->is_listing_connected){ ?>
                                                 <a data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-outline-info btn-sm" title="<?= lang('Connect With Listing');?> "  href="<?php echo base_url() ?>admin/user/connect_listing/<?php echo $v_user->user_id; ?>"><span class="fa fa-plug"></span></a>
                                               <?php  }else{ ?>
                                                  <a data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-info btn-sm" title="<?= lang('Connected with Listing');?> "  href="<?php echo base_url() ?>admin/listings"><span class="fa fa-plug"></span></a>
                                              <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    };
                                endforeach;
                                    ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if (!empty($created) || !empty($edited)){
                        if (!empty($login_info)) {
                            $user_id = $login_info->user_id;
                            $companies_id = $login_info->companies_id;
                        } else {
                            $user_id = null;
                            $companies_id = null;
                        }
                        ?>
                    <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="new">
                        <h4 class="card-title mb-4"><?= lang('new_user') ?></h4>
                        <?php echo form_open(base_url('admin/user/save_user/' . $user_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <?php super_admin_form($companies_id, 3, 9) ?>
                                <?php
                                if (!empty($login_info->user_id)) {
                                    $profile_info = $this->user_model->check_by(array('user_id' => $login_info->user_id), 'tbl_account_details');
                                }
                                ?>
                                <input type="hidden" id="username_flag" value="">
                                <input type="hidden" id="user_id" name="user_id" value="<?php
                                if (!empty($login_info)) {
                                    echo $login_info->user_id;
                                }
                                ?>">
                                <input type="hidden" name="account_details_id" value="<?php
                                if (!empty($profile_info)) {
                                    echo $profile_info->account_details_id;
                                }
                                ?>">

                                <div class="row mb-3">
                                    <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('full_name') ?> <span
                                                class="text-danger">*</span></label>
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        <input type="text" class="input-sm form-control" value="<?php
                                        if (!empty($profile_info)) {
                                            echo $profile_info->fullname;
                                        }
                                        ?>" placeholder="<?= lang('eg') ?> <?= lang('enter_your') . ' ' . lang('full_name') ?>"  name="fullname"  required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('employment_id') ?> </label>
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        <input type="text" id="check_employment_id"
                                               class="input-sm form-control" value="<?php
                                        if (!empty($profile_info)) {
                                            echo $profile_info->employment_id;
                                        }
                                        ?>" placeholder="<?= lang('eg') ?> 15351" name="employment_id">
                                        <span class="required" id="employment_id_error"></span>
                                    </div>
                                </div>

                                <?php if (empty($login_info->user_id)) { ?>
                                    <div class="row mb-3">
                                        <label
                                                class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('username'); ?><span
                                                    class="text-danger">*</span></label>
                                        <div class="col-lg-9 col-md-9 col-sm-9">
                                            <input type="text" name="username" id="check_username"
                                                   placeholder="<?= lang('eg') ?> <?= lang('enter_your') . ' ' . lang('username') ?>"
                                                   value="<?php
                                                   if (!empty($login_info)) {
                                                       echo $login_info->username;
                                                   }
                                                   ?>" class="input-sm form-control" required>
                                            <span class="required" id="check_username_error"></span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('password') ?><span
                                                    class="text-danger">*</span></label>
                                        <div class="col-lg-9 col-md-9 col-sm-9">
                                            <input type="password" id="new_password" placeholder="<?= lang('password') ?>"
                                                   name="password" class="input-sm form-control" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label
                                                class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('confirm_password') ?><span
                                                    class="text-danger">*</span></label>
                                        <div class="col-lg-9 col-md-9 col-sm-9">
                                            <input type="password" data-parsley-equalto="#new_password"
                                                   placeholder="<?= lang('confirm_password') ?>"
                                                   name="confirm_password" class="input-sm form-control" required>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <input type="hidden" name="username"
                                           placeholder="<?= lang('eg') ?> <?= lang('user_placeholder_username') ?>"
                                           value="<?php
                                           if (!empty($login_info)) {
                                               echo $login_info->username;
                                           }
                                           ?>" class="input-sm form-control" required>
                                <?php } ?>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('email') ?> <span
                                                class="text-danger">*</span></label>
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        <input type="email" id="check_email_addrees"
                                               placeholder="<?= lang('eg') ?> <?= lang('user_placeholder_email') ?>"
                                               name="email" value="<?php
                                        if (!empty($login_info)) {
                                            echo $login_info->email;
                                        }
                                        ?>" class="input-sm form-control" required>
                                        <span class="required" id="email_addrees_error"></span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('locale') ?></label>
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        <select class="  form-control select_box" style="width: 100%" name="locale">

                                            <?php
                                            $locales = $this->db->get('tbl_locales')->result();
                                            foreach ($locales as $loc) :
                                                ?>
                                                <option lang="<?= $loc->code ?>" value="<?= $loc->locale ?>" <?php
                                                if (!empty($profile_info)) {
                                                    if ($profile_info->locale == $loc->locale) {
                                                        echo 'selected';
                                                    }
                                                } else {
                                                    echo($this->config->item('locale') == $loc->locale ? 'selected="selected"' : '');
                                                }
                                                ?>><?= $loc->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('language') ?></label>
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        <select name="language" class="form-control select_box" style="width: 100%">
                                            <?php foreach ($languages as $lang) : ?>
                                                <option value="<?= $lang->name ?>"<?php
                                                if (!empty($profile_info)) {
                                                    if ($profile_info->language == $lang->name) {
                                                        echo 'selected';
                                                    }
                                                } else {
                                                    echo($this->config->item('language') == $lang->name ? ' selected="selected"' : '');
                                                }
                                                ?>><?= ucfirst($lang->name) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('phone') ?></label>
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        <input type="text" class="input-sm form-control" value="<?php
                                        if (!empty($profile_info)) {
                                            echo $profile_info->phone;
                                        }
                                        ?>" name="phone"
                                               placeholder="<?= lang('eg') ?> <?= lang('user_placeholder_phone') ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('mobile') ?></label>
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        <input type="text" class="input-sm form-control" value="<?php
                                        if (!empty($profile_info)) {
                                            echo $profile_info->mobile;
                                        }
                                        ?>" name="mobile"
                                               placeholder="<?= lang('eg') ?> <?= lang('user_placeholder_mobile') ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('skype_id') ?></label>
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        <input type="text" class="input-sm form-control" value="<?php
                                        if (!empty($profile_info)) {
                                            echo $profile_info->skype;
                                        }
                                        ?>" name="skype"
                                               placeholder="<?= lang('eg') ?> <?= lang('user_placeholder_skype') ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="field-1"
                                           class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('user_type') ?><span
                                                class="required">*</span></label>
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        <select id="user_type" name="role_id" class="form-control" required>
                                            <option value=""><?= lang('select_user_type') ?></option>
                                            <option <?php
                                            if (!empty($login_info)) {
                                                echo $login_info->role_id == 1 ? 'selected' : '';
                                            }
                                            ?> value="1"><?= lang('admin') ?></option>
                                            <option <?php
                                            if (!empty($login_info)) {
                                                echo $login_info->role_id == 3 ? 'selected' : '';
                                            }
                                            ?> value="3"><?= lang('staff') ?></option>
                                            <option <?php
                                            if (!empty($login_info)) {
                                                echo $login_info->role_id == 2 ? 'selected' : '';
                                            }
                                            ?> value="2"><?= lang('client') ?></option>
                                        </select>
                                    </div>
                                </div>
                                <?php

                                if (!empty($profile_info->direction)) {
                                    $direction = $profile_info->direction;
                                } else {
                                    $RTL = config_item('RTL');
                                    if (!empty($RTL)) {
                                        $direction = 'rtl';
                                    }
                                }
                                ?>
                                <div class="row mb-3">
                                    <label for="direction"
                                           class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('direction') ?></label>
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        <select name="direction" class="select_box form-control"
                                                data-width="100%">
                                            <option <?php
                                            if (!empty($direction)) {
                                                echo $direction == 'ltr' ? 'selected' : '';
                                            }
                                            ?> value="ltr"><?= lang('ltr') ?></option>
                                            <option <?php
                                            if (!empty($direction)) {
                                                echo $direction == 'rtl' ? 'selected' : '';
                                            }
                                            ?> value="rtl"><?= lang('rtl') ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3" id="border-none">
                                    <label for="field-1" class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('permission') ?> <span
                                                class="required">*</span></label>
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        <div class="form-check form-radio-outline form-radio-primary mb-3">
                                            <input id="everyone" <?php
                                                if (!empty($login_info->permission) && $login_info->permission == 'all') {
                                                    echo 'checked';
                                                }
                                                ?> type="radio" name="permission" value="everyone" class="form-check-input">
                                            <label class="form-check-label" for="everyone"><?= lang('everyone') ?>
                                                <i title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip"
                                                   data-bs-placement="top"></i>
                                            </label>
                                        </div>
                                        <div class="form-check form-radio-outline form-radio-primary mb-3">
                                            <input id="custom_permission" <?php
                                                if (!empty($login_info->permission) && $login_info->permission != 'all') {
                                                    echo 'checked';
                                                } elseif (empty($login_info)) {
                                                    echo 'checked';
                                                }
                                                ?> type="radio" name="permission" value="custom_permission" class="form-check-input">
                                            <label class="form-check-label" for="custom_permission">
                                                <?= lang('custom_permission') ?>
                                                <i  title="<?= lang('permission_for_customization') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3 <?php
                                    if (!empty($login_info->permission) && $login_info->permission != 'all') {
                                        echo 'show';
                                    }
                                    ?>" id="permission_user_1">
                                    <label for="field-1" class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('select') . ' ' . lang('users') ?>
                                        <span class="required">*</span></label>
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        <?php
                                        if (!empty($permission_user)) { ?>
                                            <input type="text" name="search_assigned_user" value="" placeholder="<?=lang('search_by').' '.lang('username'); ?>" class="form-control mb-4" id="search_assigned_user" autocomplete="off">
                                            <div data-simplebar style="max-height: 250px;">  
                                                <?php 

                                                foreach ($permission_user as $key => $v_user) {

                                                if ($v_user->role_id == 1) {
                                                    $role = '<strong class="badge btn-danger">' . lang('admin') . '</strong>';
                                                } else {
                                                    $role = '<strong class="badge btn-primary">' . lang('staff') . '</strong>';
                                                }

                                                ?>
                                                <div class="form-check form-check-primary mb-3">
                                                    <input type="checkbox"
                                                        <?php
                                                        if (!empty($login_info->permission) && $login_info->permission != 'all') {
                                                            $get_permission = json_decode($login_info->permission);
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
                                                    if (!empty($login_info->permission) && $login_info->permission != 'all') {
                                                        $get_permission = json_decode($login_info->permission);

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
                                                        <input id="edit_<?= $v_user->user_id ?>"
                                                            <?php
                                                            if (!empty($login_info->permission) && $login_info->permission != 'all') {
                                                                $get_permission = json_decode($login_info->permission);

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
                                                        <input id="<?= $v_user->user_id ?>"
                                                            <?php

                                                            if (!empty($login_info->permission) && $login_info->permission != 'all') {
                                                                $get_permission = json_decode($login_info->permission);
                                                                foreach ($get_permission as $user_id => $v_permission) {
                                                                    if ($user_id == $v_user->user_id) {
                                                                        if (in_array('delete', $v_permission)) {
                                                                            echo 'checked';
                                                                        };
                                                                    }
                                                                }

                                                            }
                                                            ?>  name="action_<?= $v_user->user_id ?>[]"  type="checkbox"  value="delete" class="form-check-input">
                                                        <label class="form-check-label" for="delete_<?= $v_user->user_id ?>"><?= lang('delete') ?></label>
                                                    </div>
                                                    <input id="<?= $v_user->user_id ?>" type="hidden" name="action_<?= $v_user->user_id ?>[]" value="view">
                                                </div>
                                                <?php  } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-6">
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('profile_photo') ?><span
                                                class="text-danger">*</span></label>
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="mb-3">
                                                <div class="col-xl-12">
                                                    <input class="form-control" type="file" id="myImg" name="avatar" value="upload">
                                                </div>   
                                            </div>
                                            
                                            <div class="fileinput-new thumbnail">
                                                <?php
                                                if (!empty($profile_info)) :
                                                    ?>
                                                    <img src="<?php echo base_url() . $profile_info->avatar; ?>" class="img-thumbnail img-fluid avatar-xs">
                                                <?php else: ?>
                                                    <img src="<?php echo base_url('uploads/no_user_img.png'); ?>"  alt="Profile Image" class="img-thumbnail img-fluid avatar-xs">
                                                <?php endif; ?>
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail" style="width: 210px;"></div>
                                            <div id="valid_msg" style="color: #e11221"></div>
                                        </div>   
                                    </div>
                                </div>
                                
                                <div class="row mb-3" id="super_admin" <?php
                                    if (!empty($login_info) && $login_info->super_admin == 'Yes') {
                                        echo 'style=""';
                                    }
                                    ?>>
                                    <label class="col-lg-3 col-md-3 col-sm-4 form-label"><?= lang('super_admin') ?> </label>
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        <div class="form-check form-check-primary mb-3">
                                            <input name="super_admin" <?php if (!empty($login_info) && $login_info->super_admin == 'Yes') { echo 'checked'; } ?> value="Yes" type="checkbox" class="super_admin form-check-input" id="super_admin">
                                            <label class="form-check-label" for="super_admin"></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3" id="owner" <?php
                                    if (!empty($login_info) && $login_info->super_admin == 'owner') {
                                        echo 'style=""';
                                    }
                                    ?>>
                                    <label class="col-lg-3 col-md-3 col-sm-4 form-label"><?= lang('owner') ?> </label>
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        <div class="form-check form-check-primary mb-3">
                                            <input name="super_admin" <?php
                                                if (!empty($login_info) && $login_info->super_admin == 'owner') {
                                                    echo 'checked';
                                                }
                                                ?> value="owner" type="checkbox" class="owner form-check-input" id="owner">
                                            <label class="form-check-label" for="owner"></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3" id="department" <?php
                                    if (!empty($login_info) && $login_info->role_id != 2) {
                                        echo 'style=""';
                                    }
                                    ?> >
                                    <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('designation') ?><span
                                                class="text-danger">*</span></label>
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        <select class="form-control select_box department" required style="width: 100%"
                                                name="designations_id">
                                            <option value=""><?= lang('select') . ' ' . lang('designation'); ?></option>
                                            <?php
                                            if (!empty($all_designation_info)) {
                                                foreach ($all_designation_info as $dept_name => $v_designation_info) {
                                                    ?>
                                                    <optgroup label="<?= $dept_name ?>">
                                                        <?php if (!empty($v_designation_info)) {
                                                            foreach ($v_designation_info as $v_designation) { ?>
                                                                <option value="<?= $v_designation->designations_id ?>" <?php
                                                                if (!empty($profile_info)) {
                                                                    if ($profile_info->designations_id == $v_designation->designations_id) {
                                                                        echo 'selected';
                                                                    }
                                                                }
                                                                ?>><?= $v_designation->designations; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </optgroup>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
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
                                                             $(".department").select2();
                                                        }
                                                    });
                                                } else {
                                                    $('select[name="designations_id"]').empty();
                                                }
                                            });
                                        });
                                    </script>

                                    <?php
                                    if (!empty($profile_info->designations_id)) {
                                        $designation_info = $this->db->where('designations_id', $profile_info->designations_id)->get('tbl_designations')->row();
                                        if (!empty($designation_info)) {
                                            $departments_info = $this->db->where('departments_id', $designation_info->designations_id)->get('tbl_departments')->row();
                                        }
                                    }
                                    ?>
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 mt">
                                        <div class="form-check form-check-primary mb-3">
                                            <input <?php if (!empty($departments_info) && $profile_info->user_id == $departments_info->department_head_id) {
                                                    echo 'checked';
                                                } ?> name="department_head_id" value="1" type="checkbox" class="form-check-input" id="dept_head">
                                            <label class="form-check-label" for="dept_head"><?= lang('is_he_department_head') ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3" id="client_permission" <?php if (!empty($login_info) && $login_info->role_id == 2) { echo 'style=""'; }?>>
                                    <style type="text/css">
                                        .toggle.btn-xs {
                                            min-width: 59px;
                                        }
                                    </style>
                                    <label class="col-lg-3 col-md-3 col-sm-4 col-form-label"><?= lang('select_client') ?></label>
                                    <div class="col-sm-9">
                                        <select class="form-control select_box" style="width: 100%"  name="company">
                                            <option value=""><?= lang('select_client') ?></option>
                                            <?php
                                            if (!empty($all_client_info)) {
                                                foreach ($all_client_info as $v_client) {
                                                    ?>
                                                    <option value="<?= $v_client->client_id ?>"
                                                        <?php
                                                        if (!empty($profile_info)) {
                                                            if ($profile_info->company == $v_client->client_id) {
                                                                echo 'selected';
                                                            }
                                                        }
                                                        ?>
                                                    ><?= $v_client->name ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                            $('select[name="companies_id"]').on('change', function () {
                                                var companies_id = $(this).val();
                                                if (companies_id) {
                                                    $.ajax({
                                                        url: '<?= base_url('admin/global_controller/json_by_company/tbl_client/')?>' + companies_id,
                                                        type: "GET",
                                                        dataType: "json",
                                                        success: function (data) {
                                                            $('select[name="company"]').find('option').not(':first').remove();
                                                            $.each(data, function (key, value) {
                                                                $('select[name="company"]').append('<option value="' + value.client_id + '">' + value.name + '</option>');
                                                            });
                                                        }
                                                    });
                                                    $.ajax({
                                                        url: "<?= base_url()?>admin/global_controller/get_client_menu/" + companies_id,
                                                        type: "GET",
                                                        dataType: 'json',
                                                        success: function (result) {
                                                            document.getElementById('package_details').innerHTML = result.client_menu;
                                                        }
                                                    });
                                                } else {
                                                    $('select[name="company"]').empty();
                                                }
                                            });
                                        });
                                    </script>
                                    <?php
                                    $all_client_menu = $all_menu = result_by_company('tbl_client_menu', array('parent' => 0), 'sort', true);
                                    if (empty($login_info)) {
                                        $login_info = null;
                                    }
                                    ?>
                                
                                    <div id="package_details" class="mt-4">
                                        <?php $this->load->view('admin/user/client_menu', array('all_client_menu' => $all_client_menu), array('login_info' => $login_info)) ?>
                                    </div>
                                </div>
                                <?php
                                if (!empty($profile_info->user_id)) {
                                    $user_id = $profile_info->user_id;
                                } else {
                                    $user_id = null;
                                }
                                ?>
                                <?= custom_form_Fields(13, $user_id); ?>
                            </div>
                        </div>
                            
                        <div class="row mt-4">
                            <div class="pull-left">
                                <label class="col-lg-3 col-md-3 col-sm-3"></label>
                                <div class="col-lg-9 col-md-9 col-sm-9">
                                    <button type="submit" id="new_uses_btn" class="btn btn-primary"><?php echo !empty($user_id) ? lang('update_user') : lang('create_user') ?></button>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $(document).on("click", '.change_user_status input[type="checkbox"]', function () {
            var user_id = $(this).data().id;
            var status = $(this).is(":checked");
            if (status == true) {
                status = 1;
            } else {
                status = 0;
            }
            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: '<?= base_url()?>admin/user/change_status/' + status + '/' + user_id, // the url where we want to POST
                dataType: 'json', // what type of data do we expect back from the server
                encode: true,
                success: function (res) {
                    console.log(res);
                    if (res) {
                        toastr[res.status](res.message);
                    } else {
                        alert('There was a problem with AJAX');
                    }
                }
            })

        });

    })
    <?php if (!empty($can_edit) && !empty($edited)) {
    $super_admin = super_admin();
    ?>
    $(document).ready(function () {
        $('#department').hide();
        $('#client_permission').hide();
        $("#owner").hide();
        $("#super_admin").hide();
        <?php if (!empty($login_info) && $login_info->super_admin == 'owner') {?>
        $("#owner").show();
        <?php }?>
        <?php if (!empty($login_info) && $login_info->super_admin == 'Yes') {?>
        $("#super_admin").show();
        <?php }?>


        var user_flag = document.getElementById("user_type").value;
        // on change user type select action
        $('#user_type').on('change', function () {
            if (this.value == '3' || this.value == '1') {
                if (this.value == '1') {
                    var super_admin = '<?= $super_admin?>';
                    if (super_admin == true) {
                        $("#super_admin").show();
                        $(".super_admin").removeAttr('disabled');
                    } else if (super_admin == 'owner') {
                        $("#owner").show();
                        $(".owner").removeAttr('disabled');
                    }
                } else {
                    $("#super_admin").hide();
                    $(".super_admin").attr('disabled', 'disabled');
                    $("#owner").hide();
                    $(".owner").attr('disabled', 'disabled');
                }
                $("#department").show();
                $(".department").removeAttr('disabled');
                $('#client_permission').hide();
                $(".client_permission").attr('disabled', 'disabled');
                $(".department").attr('required', true);

            } else if (this.value == '2') {
                $("#super_admin").hide();
                $(".super_admin").attr('disabled', 'disabled');
                $("#owner").hide();
                $(".owner").attr('disabled', 'disabled');
                $('#client_permission').show();
                $(".client_permission").removeAttr('disabled');
                $("#department").hide();
                $(".department").attr('disabled', 'disabled');
                $(".department").removeAttr('required');

            } else {
                $("#super_admin").hide();
                $(".super_admin").attr('disabled', 'disabled');
                $("#owner").hide();
                $(".owner").attr('disabled', 'disabled');
                $('#client_permission').hide();
                $(".client_permission").attr('disabled', 'disabled');
                $("#department").hide();
                $(".department").attr('disabled', 'disabled');
            }
        });
    });
    <?php }?>
</script>
<?php
if (!empty($login_info) && $login_info->role_id != 2) { ?>
    <script>
        $(document).ready(function () {
            $('#department').show();
            $(".department").removeAttr('disabled');
            $('#client_permission').hide();
            $(".client_permission").attr('disabled', 'disabled');
        });
    </script>
<?php }
?><?php
if (!empty($login_info) && $login_info->role_id == 2) { ?>
    <script>
        $(document).ready(function () {
            $('#client_permission').show();
            $(".client_permission").removeAttr('disabled');
            $("#department").hide();
            $(".department").attr('disabled', 'disabled');
            $(".department").removeAttr('required');
        });
    </script>
<?php }
?>