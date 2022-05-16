<?php
echo message_box('success');
echo message_box('error');
$created = can_action('149', 'created');
$edited = can_action('149', 'edited');
?>
<div class="modal-header">
    <h5 class="modal-title"><?= $title ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<?php  if (!empty($created) || !empty($edited)) {
$companies_id = null;
?>
<form method="post" id="lead_statuss" action="<?= base_url() ?>admin/purchase/save_supplier/inline" class="form-horizontal" data-parsley-validate="" novalidate="">
    <div class="modal-body wrap-modal wrap">           
        <div class="row">
            <div class="col-sm-6 col-xs-12 br pv">
                <?php super_admin_form_modal($companies_id, 4, 7) ?>
                <div class="row mb-3">
                    <label class="col-lg-4 col-form-label"><?= lang('name') ?> <span
                            class="text-danger">*</span></label>
                    <div class="col-lg-7">
                        <input type="text" class="form-control" value="<?php
                        if (!empty($supplier_info)) {
                            echo $supplier_info->name;
                        }
                        ?>" name="name" required="">
                    </div>

                </div>
                <div class="row mb-3">
                    <label class="col-lg-4 col-form-label"><?= lang('email') ?> <span
                            class="text-danger">*</span></label>
                    <div class="col-lg-7">
                        <input type="text" class="form-control" value="<?php
                        if (!empty($supplier_info)) {
                            echo $supplier_info->email;
                        }
                        ?>" name="email" required="">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-4 col-form-label"><?= lang('phone') ?> <span
                            class="text-danger">*</span></label>
                    <div class="col-lg-7">
                        <input type="text" class="form-control" value="<?php
                        if (!empty($supplier_info)) {
                            echo $supplier_info->phone;
                        }
                        ?>" name="phone" required="">
                    </div>
                </div>
                <!-- End discount Fields -->
                <div class="row mb-3 terms">
                    <label class="col-lg-4 col-form-label"><?= lang('address') ?> </label>
                    <div class="col-lg-7">
                    <textarea name="address" class="form-control"><?php
                        if (!empty($supplier_info)) {
                            echo $supplier_info->address;
                        }
                        ?></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-4 col-form-label"><?= lang('city') ?></label>
                    <div class="col-lg-7">
                        <input type="text" class="form-control" value="<?php
                        if (!empty($supplier_info)) {
                            echo $supplier_info->city;
                        }
                        ?>" name="city">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12 br pv">
                <div class="row">
                    <div class="row mb-3">
                        <label class="col-lg-4 col-form-label"><?= lang('state') ?></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" value="<?php
                            if (!empty($supplier_info)) {
                                echo $supplier_info->state;
                            }
                            ?>" name="state">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-4 col-form-label"><?= lang('zip_code') ?></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" value="<?php
                            if (!empty($supplier_info)) {
                                echo $supplier_info->zip_code;
                            }
                            ?>" name="zip_code">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label
                            class="col-lg-4 col-form-label"><?= lang('country') ?></label>
                        <div class="col-lg-7">
                            <select name="country" class="form-control select_box"
                                    style="width: 100%">
                                <optgroup label="Default Country">
                                    <option
                                        value="<?= $this->config->item('company_country') ?>"><?= $this->config->item('company_country') ?></option>
                                </optgroup>
                                <optgroup label="<?= lang('other_countries') ?>">
                                    <?php if (!empty($countries)): foreach ($countries as $country): ?>
                                        <option
                                            value="<?= $country->value ?>" <?= (!empty($supplier_info->country) && $supplier_info->country == $country->value ? 'selected' : NULL) ?>><?= $country->value ?>
                                        </option>
                                        <?php
                                    endforeach;
                                    endif;
                                    ?>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-4 col-form-label"><?= lang('tax') ?></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" value="<?php
                            if (!empty($supplier_info)) {
                                echo $supplier_info->tax;
                            }
                            ?>" name="tax">
                        </div>
                    </div>


                    <div class="row mb-3" id="border-none">
                        <label for="field-1" class="col-sm-4 col-form-label"><?= lang('permission') ?> <span
                                class="required">*</span></label>
                        <div class="col-sm-7">
                            <div class="form-check form-radio-outline form-radio-primary mb-3">
                                <input id="everyone" <?php
                                    if (!empty($supplier_info->permission) && $supplier_info->permission == 'all') {
                                        echo 'checked';
                                    }
                                    ?> type="radio" name="permission" value="everyone" class="form-check-input">
                                <label class="form-check-label" for="everyone"><?= lang('everyone') ?>
                                    <i title="<?= lang('permission_for_all') ?>"
                                       class="fa fa-question-circle" data-toggle="tooltip"
                                       data-placement="top"></i>
                                </label>
                            </div>
                            <div class="form-check form-radio-outline form-radio-primary mb-3">
                                <input id="custom_permission" <?php
                                    if (!empty($supplier_info->permission) && $supplier_info->permission != 'all') {
                                        echo 'checked';
                                    } elseif (empty($supplier_info)) {
                                        echo 'checked';
                                    }
                                    ?> type="radio" name="permission" value="custom_permission"  class="form-check-input">
                                <label class="form-check-label" for="custom_permission"><?= lang('custom_permission') ?> <i
                                        title="<?= lang('permission_for_customization') ?>"
                                        class="fa fa-question-circle" data-toggle="tooltip"
                                        data-placement="top"></i>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 <?php
                        if (!empty($supplier_info->permission) && $supplier_info->permission != 'all') {
                            echo 'show';
                        }
                        ?>" id="permission_user">
                        <label  class="col-sm-3 col-form-label"><?= lang('select') . ' ' . lang('users') ?>
                            <span  class="required">*</span></label>
                        <div class="col-sm-7">
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
                                                if (!empty($supplier_info->permission) && $supplier_info->permission != 'all') {
                                                    $get_permission = json_decode($supplier_info->permission);
                                                    foreach ($get_permission as $user_id => $v_permission) {
                                                        if ($user_id == $v_user->user_id) {
                                                            echo 'checked';
                                                        }
                                                    }

                                                }
                                                ?>  value="<?= $v_user->user_id ?>"  name="assigned_to[]" data-name="<?= $v_user->username;?>"  class="form-check-input" id ="user_<?= $v_user->user_id ?>">
                                        <label class="form-check-label" for="user_<?= $v_user->user_id ?>"><?= $v_user->username . ' ' . $role ?>
                                        </label>

                                    </div>
                                    <div class="action_1 p
                                        <?php
                                        if (!empty($supplier_info->permission) && $supplier_info->permission != 'all') {
                                            $get_permission = json_decode($supplier_info->permission);

                                            foreach ($get_permission as $user_id => $v_permission) {
                                                if ($user_id == $v_user->user_id) {
                                                    echo 'show';
                                                }
                                            }

                                        }
                                        ?>  " id="action_1<?= $v_user->user_id ?>">
                                        <div class="form-check form-check-primary mb-3 mr">         
                                            <input id="view_<?= $v_user->user_id ?>" checked type="checkbox" name="action_1<?= $v_user->user_id ?>[]" disabled value="view" class="form-check-input">
                                            <label class="form-check-label" for="view_<?= $v_user->user_id ?>"><?= lang('view') ?></label>
                                        </div>
                                        <div class="form-check form-check-primary mb-3 mr">         
                                            <input id="edit_<?= $v_user->user_id ?>"
                                                <?php

                                                if (!empty($supplier_info->permission) && $supplier_info->permission != 'all') {
                                                    $get_permission = json_decode($supplier_info->permission);

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
                                            <input id="delete_<?= $v_user->user_id;?>"
                                                <?php

                                                if (!empty($supplier_info->permission) && $supplier_info->permission != 'all') {
                                                    $get_permission = json_decode($supplier_info->permission);
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
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="mb-3">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <button type="submit" class="btn btn-primary w-md waves-effect waves-light"><?= lang('save') ?></button>            
        </div>
    </div>
</form>
<?php } ?>
<script type="text/javascript">
    $(document).on("submit", "form", function (event) {
        var form = $(event.target);
        if (form.attr('action') == '<?= base_url('admin/purchase/save_supplier/inline')?>') {
            event.preventDefault();
        }
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize()
        }).done(function (response) {
            response = JSON.parse(response);
            if (response.status == 'success') {
                if (typeof(response.id) != 'undefined') {
                    var groups = $('select[name="supplier_id"]');
                    groups.prepend('<option selected value="' + response.id + '">' + response.name + '</option>');
                    var select2Instance = groups.data('select2');
                    var resetOptions = select2Instance.options.options;
                    groups.select2('destroy').select2(resetOptions)
                }
                toastr[response.status](response.message);
            }
            $('#myModal_extra_lg').modal('hide');
        }).fail(function () {
            alert('There was a problem with AJAX');
        });
    });
</script>