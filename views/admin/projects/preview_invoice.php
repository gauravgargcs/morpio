<?php $saved_items = $this->invoice_model->get_all_items(); ?>
<style type="text/css">
    .dropdown-menu > li > a {
        white-space: normal;
    }

    .dragger {
        background: url(<?= base_url()?>skote_assets/images/dragger.png) 10px 32px no-repeat;
        cursor: pointer;
    }

    .input-transparent {
        box-shadow: none;
        outline: 0;
        border: 0 !important;
        background: 0 0;
        padding: 3px;
    }
</style>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
          <h4 class="mb-sm-0 font-size-18">  <?php echo $title; ?> 
            </h4>

            <?php $this->load->view('admin/skote_layouts/title'); ?>
            
        </div>
    </div>
</div>
<!-- end page title -->
<form action="<?php echo base_url() ?>admin/projects/save_invoice/<?php if (!empty($project_info->project_id)) echo $project_info->project_id; ?>" method="post" class="form-horizontal">
<div class="row">
    <div class="card"> 
        <div class="card-body">
            <div class="pull-right pr-lg">
                <a class="btn btn-sm btn-primary" href="<?= base_url() ?>admin/projects/project_details/<?= $project_info->project_id ?>">
                    <span aria-hidden="true">&times;</span><span  class="sr-only"><?php echo lang('close') ?></span>
                </a>
            </div>
            <h4 class="card-title mb-4"><?= $project_info->project_name . ' - ' . lang('preview_invoice') ?></h4>
            
            <?php
            $client_info = $this->invoice_model->check_by(array('client_id' => $project_info->client_id), 'tbl_client');
            $currency = $this->invoice_model->client_currency_sambol($project_info->client_id);
            $client_lang = $client_info->language;
            unset($this->lang->is_loaded[5]);
            $language_info = $this->lang->load('sales_lang', $client_lang, TRUE, FALSE, '', TRUE);
            ?>

            <div class="row mb-lg">
                <div class="col-xl-6 br pv">
                    <div class="row">
                        <div class="row mb-3">
                            <label class="col-xl-4 col-form-label"><?= lang('reference_no') ?> <span class="text-danger">*</span></label>
                            <div class="col-xl-5">
                                <input type="text" class="form-control" value="<?php
                                if (!empty($invoice_info)) {
                                    echo $invoice_info->reference_no;
                                } else {
                                    echo config_item('invoice_prefix');
                                    if (config_item('increment_invoice_number') == 'FALSE') {
                                        $this->load->helper('string');
                                        echo random_string('nozero', 6);
                                    } else {
                                        echo $this->invoice_model->generate_invoice_number();
                                    }
                                }
                                ?>" name="reference_no">
                            </div>
                            <div class="col-xl-2">
                                <div class="btn btn-sm btn-info" id="start_recurring"><?= lang('recurring') ?></div>
                            </div>
                        </div>
                        <div class="row mb-3 recurring hide" style="display:inline-flex;">
                            <label class="col-xl-4 col-form-label"><?= lang('recur_frequency') ?> </label>
                            <div class="col-xl-7">
                                <select name="recuring_frequency" id="recuring_frequency" class="form-control select_box" style="width: 100%">
                                    <option value="none"><?= lang('none') ?></option>
                                    <option
                                        value="7D"><?= lang('week') ?></option>
                                    <option
                                        value="1M"><?= lang('month') ?></option>
                                    <option
                                        value="3M"><?= lang('quarter') ?></option>
                                    <option
                                        value="6M"><?= lang('six_months') ?></option>
                                    <option
                                        value="1Y"><?= lang('1year') ?></option>
                                    <option
                                        value="2Y"><?= lang('2year') ?></option>
                                    <option
                                        value="3Y"><?= lang('3year') ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3 recurring hide" style="display:inline-flex;">
                            <label class="col-xl-4 col-form-label"><?= lang('start_date') ?></label>
                            <div class="col-xl-7">
                                <?php
                                if (!empty($invoice_info) && $invoice_info->recurring == 'Yes') {
                                    $recur_start_date = date('d-m-Y H-i', strtotime($invoice_info->recur_start_date));
                                    $recur_end_date = date('d-m-Y H-i', strtotime($invoice_info->recur_end_date));
                                } else {
                                    $recur_start_date = date('d-m-Y H-i');
                                    $recur_end_date = date('d-m-Y H-i');
                                }
                                ?>
                                <div class="input-group" id="datepicker1">
                                    <input class="form-control datepicker" autocomplete="off" type="text" value="<?= $recur_start_date; ?>" name="recur_start_date" data-date-format="<?= config_item('date_picker_format'); ?>" data-date-container="#datepicker1">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3 recurring hide" style="display:inline-flex;">
                            <label class="col-xl-4 col-form-label"><?= lang('end_date') ?></label>
                            <div class="col-xl-7">
                                <div class="input-group" id="datepicker2">
                                    <input class="form-control datepicker" autocomplete="off" type="text" value="<?= $recur_end_date; ?>" name="recur_end_date" data-date-format="<?= config_item('date_picker_format'); ?>" data-date-container="#datepicker2">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-xl-4 col-form-label"><?= lang('client') ?> <span class="text-danger">*</span>
                            </label>
                            <div class="col-xl-7">
                                <select class="form-control select_box" required style="width: 100%" name="client_id"
                                        onchange="get_project_by_id(this.value)">
                                    <option value="-"><?= lang('select') . ' ' . lang('client') ?></option>
                                    <?php
                                    if (!empty($all_client)) {
                                        foreach ($all_client as $v_client) {
                                            if (!empty($project_info->client_id)) {
                                                $client_id = $project_info->client_id;
                                            } elseif ($invoice_info->client_id) {
                                                $client_id = $invoice_info->client_id;
                                            }
                                            ?>
                                            <option value="<?= $v_client->client_id ?>"
                                                <?php
                                                if (!empty($client_id)) {
                                                    echo $client_id == $v_client->client_id ? 'selected' : null;
                                                }
                                                ?>
                                            ><?= ucfirst($v_client->name) ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-xl-4 col-form-label"><?= lang('project') ?></label>
                            <div class="col-xl-7">
                                <select class="form-control select_box" style="width: 100%" name="project_id" id="client_project">
                                    <option value=""><?= lang('none') ?></option>
                                    <?php

                                    if (!empty($client_id)) {

                                        if (!empty($project_info->project_id)) {
                                            $project_id = $project_info->project_id;
                                        } elseif ($invoice_info->project_id) {
                                            $project_id = $invoice_info->project_id;
                                        }
                                        $all_project = $this->db->where('client_id', $client_id)->get('tbl_project')->result();
                                        if (!empty($all_project)) {
                                            foreach ($all_project as $v_project) {
                                                ?>
                                                <option value="<?= $v_project->project_id ?>" <?php
                                                if (!empty($project_id)) {
                                                    echo $v_project->project_id == $project_id ? 'selected' : '';
                                                }
                                                ?>><?= $v_project->project_name ?></option>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-xl-4 col-form-label"><?= lang('invoice_date') ?></label>
                            <div class="col-xl-7">
                                <div class="input-group" id="datepicker3">
                                    <input type="text" name="invoice_date" class="form-control datepicker" autocomplete="off" value="<?php if (!empty($invoice_info->invoice_date)) { echo date('d-m-Y H-i', strtotime($invoice_info->invoice_date)); } else { echo date('d-m-Y H-i'); } ?>" data-date-format="<?= config_item('date_picker_format'); ?>" data-date-container="#datepicker3">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-xl-4 col-form-label"><?= lang('due_date') ?></label>
                            <div class="col-xl-7">
                                <div class="input-group" id="datepicker4">
                                    <input type="text" name="due_date" class="form-control datepicker" autocomplete="off" value="<?php
                                        if (!empty($invoice_info->due_date)) {
                                            echo date('d-m-Y H-i', strtotime($invoice_info->due_date));
                                        } else {
                                            echo date('d-m-Y H-i');
                                        } ?>" data-date-format="<?= config_item('date_picker_format'); ?>" data-date-container="#datepicker4" data-provide="datepicker">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="discount_type" class="col-xl-4 col-form-label"><?= lang('discount_type') ?></label>
                            <div class="col-xl-7">
                                <select name="discount_type" class="form-control select_box" data-width="100%">
                                    <option value="" selected><?php echo lang('no') . ' ' . lang('discount'); ?></option>
                                    <option value="before_tax" <?php
                                    if (isset($invoice_info)) {
                                        if ($invoice_info->discount_type == 'before_tax') {
                                            echo 'selected';
                                        }
                                    } ?>><?php echo lang('before_tax'); ?></option>
                                    <option value="after_tax" <?php if (isset($invoice_info)) {
                                        if ($invoice_info->discount_type == 'after_tax') {
                                            echo 'selected';
                                        }
                                    } ?>><?php echo lang('after_tax'); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3" id="border-none">
                            <label for="field-1" class="col-xl-4 col-form-label"><?= lang('permission') ?> <span
                                    class="required">*</span></label>
                            <div class="col-xl-7">
                                <div class="form-check form-radio-outline form-radio-primary mb-3">
                                    <input id="everyone" <?php
                                        if (!empty($invoice_info->permission) && $invoice_info->permission == 'all') {
                                            echo 'checked';
                                        }
                                        ?> type="radio" name="permission" value="everyone" class="form-check-input">
                                    <label class="form-check-label" for="everyone"><?= lang('everyone') ?>
                                        <i title="<?= lang('permission_for_all') ?>"
                                           class="fa fa-question-circle" data-bs-toggle="tooltip"
                                           data-bs-placement="top"></i>
                                    </label>
                                </div>
                                <div class="form-check form-radio-outline form-radio-primary mb-3">
                                    <input id="custom_permission" <?php
                                        if (!empty($invoice_info->permission) && $invoice_info->permission != 'all') {
                                            echo 'checked';
                                        } elseif (empty($invoice_info)) {
                                            echo 'checked';
                                        }
                                        ?> type="radio" name="permission" value="custom_permission" class="form-check-input">
                                    <label class="form-check-label" for="custom_permission"><?= lang('custom_permission') ?> <i
                                            title="<?= lang('permission_for_customization') ?>"
                                            class="fa fa-question-circle" data-bs-toggle="tooltip"
                                            data-bs-placement="top"></i>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 <?php
                            if (!empty($invoice_info->permission) && $invoice_info->permission != 'all') {
                                echo 'show';
                            }
                            ?>" id="permission_user_1">
                            <label class="col-xl-4 col-form-label"><?= lang('select') . ' ' . lang('users') ?>
                                <span  class="required">*</span></label>
                            <div class="col-xl-5">
                            <?php
                            if (!empty($assign_user)) { ?>
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
                                        <div class="col-xl-form-check form-check-primary mb-3">
                                            <input type="checkbox"
                                                    <?php
                                                    if (!empty($invoice_info->permission) && $invoice_info->permission != 'all') {
                                                        $get_permission = json_decode($invoice_info->permission);
                                                        foreach ($get_permission as $user_id => $v_permission) {
                                                            if ($user_id == $v_user->user_id) { echo 'checked'; } } } ?> 
                                                            value="<?= $v_user->user_id ?>" name="assigned_to[]" class="form-check-input" id="user_<?= $v_user->user_id ?>" data-name="<?= $v_user->username;?>">
                                            <label class="form-check-label" for="user_<?= $v_user->user_id ?>"><?= $v_user->username . ' ' . $role ?></label>
                                        </div>
                                        <div class="action_1 p
                                            <?php
                                            if (!empty($invoice_info->permission) && $invoice_info->permission != 'all') {
                                                $get_permission = json_decode($invoice_info->permission);
                                                foreach ($get_permission as $user_id => $v_permission) {
                                                    if ($user_id == $v_user->user_id) {
                                                        echo 'show';
                                                    }
                                                }
                                            } ?>" id="action_1<?= $v_user->user_id ?>">
                                            <div class="form-check form-check-primary mb-3 mr">         
                                                <input id="view_<?= $v_user->user_id ?>" checked type="checkbox" name="action_1<?= $v_user->user_id ?>[]" disabled  value="view" class="form-check-input">
                                                <label class="form-check-label" for="view_<?= $v_user->user_id ?>"><?= lang('can') . ' ' . lang('view') ?></label>
                                            </div>
                                            <div class="form-check form-check-primary mb-3 mr">         
                                                <input id="edit_<?= $v_user->user_id ?>"
                                                    <?php
                                                    if (!empty($invoice_info->permission) && $invoice_info->permission != 'all') {
                                                        $get_permission = json_decode($invoice_info->permission);
                                                        foreach ($get_permission as $user_id => $v_permission) {
                                                            if ($user_id == $v_user->user_id) {
                                                                if (in_array('edit', $v_permission)) {
                                                                    echo 'checked';
                                                                };
                                                            }
                                                        }
                                                    }
                                                    ?> type="checkbox" value="edit" name="action_<?= $v_user->user_id ?>[]" class="form-check-input">
                                                <label class="form-check-label" for="edit_<?= $v_user->user_id ?>"><?= lang('can') . ' ' . lang('edit') ?></label>
                                            </div>
                                            <div class="form-check form-check-primary mb-3 mr">         
                                                <input id="delete_<?= $v_user->user_id ?>"
                                                    <?php

                                                    if (!empty($invoice_info->permission) && $invoice_info->permission != 'all') {
                                                        $get_permission = json_decode($invoice_info->permission);
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


                                        <?php
                                    } ?>
                                </div>
                                <?php }
                                ?>
                            </div>
                        </div>
                        <?php
                        if (!empty($invoice_info)) {
                            $invoices_id = $invoice_info->invoices_id;
                        } else {
                            $invoices_id = null;
                        }
                        ?>
                        <?= custom_form_Fields(9, $invoices_id); ?>
                    </div>
                </div>
                <div class="col-xl-6 br pv">
                    <div class="row">
                        <div class="row mb-3">
                            <label class="col-xl-4 col-form-label"><?= lang('sales') . ' ' . lang('agent') ?></label>
                            <div class="col-xl-7">
                                <select class="form-control select_box" required style="width: 100%" name="user_id">
                                    <option value=""><?= lang('select') . ' ' . lang('sales') . ' ' . lang('agent') ?></option>
                                    <?php
                                    $all_user = get_result('tbl_users', array('role_id !=' => 2));
                                    if (!empty($all_user)) {
                                        foreach ($all_user as $v_user) {
                                            $profile_info = $this->db->where('user_id', $v_user->user_id)->get('tbl_account_details')->row();
                                            if (!empty($profile_info)) {
                                                ?>
                                                <option value="<?= $v_user->user_id ?>"
                                                    <?php
                                                    if (!empty($invoice_info->user_id)) {
                                                        echo $invoice_info->user_id == $v_user->user_id ? 'selected' : null;
                                                    } else {
                                                        echo $this->session->userdata('user_id') == $v_user->user_id ? 'selected' : null;
                                                    }
                                                    ?>
                                                ><?= $profile_info->fullname ?></option>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php if (config_item('paypal_status') == 'active'): ?>
                            <div class="row mb-3">
                                <label class="col-xl-4 col-form-label"><?= lang('allow_paypal') ?></label>
                                <div class="col-xl-7">
                                    <div class="col-xl-form-check form-check-primary mb-3">
                                        <input type="checkbox" value="Yes"
                                                <?php if (!empty($invoice_info) && $invoice_info->allow_paypal == 'Yes') {
                                                    echo 'checked';
                                                } ?> name="allow_paypal" id="allow_paypal" class="form-check-input">
                                        <label class="form-check-label" for="allow_paypal"></label>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                        <?php if (config_item('stripe_status') == 'active'): ?>
                            <div class="row mb-3">
                                <label class="col-xl-4 col-form-label"><?= lang('allow_stripe') ?></label>
                                <div class="col-xl-7">
                                    <div class="col-xl-form-check form-check-primary mb-3">
                                        <input type="checkbox" value="Yes"
                                                <?php if (!empty($invoice_info) && $invoice_info->allow_stripe == 'Yes') {
                                                    echo 'checked';
                                                } ?>  name="allow_stripe" id="allow_stripe" class="form-check-input">
                                        <label class="form-check-label" for="allow_stripe"></label>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (config_item('2checkout_status') == 'active'): ?>
                            <div class="row mb-3">
                                <label class="col-xl-4 col-form-label"><?= lang('allow_2checkout') ?></label>
                                <div class="col-xl-7">
                                    <div class="col-xl-form-check form-check-primary mb-3">
                                        <input type="checkbox" value="Yes"
                                                <?php if (!empty($invoice_info) && $invoice_info->allow_2checkout == 'Yes') {
                                                    echo 'checked';
                                                } ?>
                                                   name="allow_2checkout" class="form-check-input" id="allow_2checkout">
                                        <label class="form-check-label" for="allow_2checkout"></label>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (config_item('authorize_status') == 'active'): ?>
                            <div class="row mb-3">
                                <label class="col-xl-4 col-form-label"><?= lang('allow_authorize.net') ?></label>

                                <div class="col-xl-7">
                                    <div class="col-xl-form-check form-check-primary mb-3">
                                        <input type="checkbox" value="Yes"
                                                <?php if (!empty($invoice_info) && $invoice_info->allow_authorize == 'Yes') {
                                                    echo 'checked';
                                                } ?>    name="allow_authorize" class="form-check-input" for="allow_authorize">
                                        <label class="form-check-label"></label>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (config_item('ccavenue_status') == 'active'): ?>
                            <div class="row mb-3">
                                <label class="col-xl-4 col-form-label"><?= lang('allow_ccavenue') ?></label>

                                <div class="col-xl-7">
                                    <div class="col-xl-form-check form-check-primary mb-3">
                                        <input type="checkbox" value="Yes"
                                                <?php if (!empty($invoice_info) && $invoice_info->allow_ccavenue == 'Yes') {
                                                    echo 'checked';
                                                } ?>
                                                   name="allow_ccavenue" class="form-check-input" id="allow_ccavenue">
                                        <label class="form-check-label" for="allow_ccavenue"> </label>
                                    </div>

                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (config_item('braintree_status') == 'active'): ?>
                            <div class="row mb-3">
                                <label class="col-xl-4 col-form-label"><?= lang('allow_braintree') ?></label>

                                <div class="col-xl-7">
                                    <div class="col-xl-form-check form-check-primary mb-3">
                                        <input type="checkbox" value="Yes"
                                                <?php if (!empty($invoice_info) && $invoice_info->allow_braintree == 'Yes') {
                                                    echo 'checked';
                                                } ?>  name="allow_braintree" class="form-check-input" id="allow_braintree">
                                        <label class="form-check-label"> </label>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (config_item('mollie_status') == 'active'): ?>
                            <div class="row mb-3">
                                <label class="col-xl-4 col-form-label"><?= lang('allow_mollie') ?></label>

                                <div class="col-xl-7">
                                    <div class="col-xl-form-check form-check-primary mb-3">
                                        <input type="checkbox" value="Yes"
                                                <?php if (!empty($invoice_info) && $invoice_info->allow_mollie == 'Yes') {
                                                    echo 'checked';
                                                } ?> name="allow_mollie" class="form-check-input" id="allow_mollie">
                                        <label class="form-check-label" for="allow_mollie"></label>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (config_item('payumoney_status') == 'active'): ?>
                            <div class="row mb-3">
                                <label class="col-xl-4 col-form-label"><?= lang('allow_payumoney') ?></label>

                                <div class="col-xl-7">
                                    <div class="col-xl-form-check form-check-primary mb-3">
                                        <input type="checkbox" value="Yes"
                                                <?php if (!empty($invoice_info) && $invoice_info->allow_payumoney == 'Yes') {
                                                    echo 'checked';
                                                } ?>  name="allow_payumoney" class="form-check-input" id="allow_payumoney">
                                        <label class="form-check-label" for="allow_payumoney"></label>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($project_id)): ?>
                            <div class="row mb-3">
                                <label class="col-xl-4 col-form-label"><?= lang('visible_to_client') ?>
                                    <span class="required">*</span></label>
                                <div class="col-xl-8">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" name="client_visible" value="Yes" <?php
                                          if (!empty($invoice_info->client_visible) && $invoice_info->client_visible == 'Yes') {
                                            echo 'checked';
                                            }
                                        ?> type="checkbox" id="client_visible">                                           
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <div class="row mb-lg">
                <div class="col-xl-12">
                    <div class="row mb-3">
                        <label class="col-xl-2 col-form-label"><?= lang('notes') ?> </label>
                        <div class="col-xl-10">
                            <textarea name="notes" id="elm1" class="textarea"><?php
                                if (!empty($invoice_info)) {
                                    echo $invoice_info->notes;
                                } else {
                                    echo $this->config->item('default_terms');
                                }
                                ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-xl-4">
                    <div class="row mb-3">
                        <label class="col-xl-3 col-form-label"><?php echo lang('add_items'); ?></label>
                        <div class="templating-select col-xl-9">
                            <select name="item_select" class="form-control select_box select2-templating" id="item_select" style="width: 100%">
                                <option value=""></option>
                                <?php
                                if (!empty($saved_items)) {
                                    $saved_items = array_reverse($saved_items, true);
                                    foreach ($saved_items as $group_id => $v_saved_items) {
                                        if ($group_id != 0) {
                                            $group = $this->db->where('customer_group_id', $group_id)->get('tbl_customer_group')->row()->customer_group;
                                        } else {
                                            $group = '';
                                        }
                                        ?>
                                        <optgroup label="<?php echo $group; ?>">
                                            <?php
                                            if (!empty($v_saved_items)) {
                                                foreach ($v_saved_items as $v_item) { ?>
                                                    <option value="<?php echo $v_item->saved_items_id; ?>">
                                                        (<?= display_money($v_item->unit_cost, $currency->symbol); ?>
                                                        ) <?php echo $v_item->item_name; ?></option>
                                                <?php }
                                            }
                                            ?>
                                        </optgroup>

                                    <?php } ?>
                                    <?php
                                    $item_created = can_action('39', 'created');
                                    if (!empty($item_created)) { ?>
                                        <option value="newitem"><span class='text-info'><?php echo lang('new_item'); ?></span></option>
                                    <?php } ?>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                </div>
                <div class="col-xl-5">
                    <div class="row mb-3">
                        <label class="col-xl-4 col-form-label"><?php echo lang('show_quantity_as'); ?></label>
                        <div class="col-xl-8" style="display:inline-flex;">
                            <div class="form-check form-radio-outline form-radio-primary mt mr">
                                <input type="radio" value="qty" id="<?php echo lang('qty'); ?>" name="show_quantity_as"
                                    <?php if (isset($invoice_info) && $invoice_info->show_quantity_as == 'qty') {
                                        echo 'checked';
                                    } else if (!isset($hours_quantity) && !isset($qty_hrs_quantity)) {
                                        echo 'checked';
                                    } ?> class="form-check-input">
                                <label class="form-check-label" for="<?php echo lang('qty'); ?>"><?php echo lang('qty'); ?></label>
                            </div>
                            <div class="form-check form-radio-outline form-radio-primary mt mr">
                                <input type="radio" value="hours" id="<?php echo lang('hours'); ?>" name="show_quantity_as" <?php if (isset($invoice_info) && $invoice_info->show_quantity_as == 'hours' || isset($hours_quantity)) {
                                    echo 'checked'; } ?> class="form-check-input">
                                <label class="form-check-label" for="<?php echo lang('hours'); ?>"><?php echo lang('hours'); ?></label>
                            </div>
                            <div class="form-check form-radio-outline form-radio-primary mt mr">
                                <input type="radio" value="qty_hours" id="<?php echo lang('qty') . '/' . lang('hours'); ?>" name="show_quantity_as"   <?php if (isset($invoice_info) && $invoice_info->show_quantity_as == 'qty_hours' || isset($qty_hrs_quantity)) {
                                        echo 'checked';    } ?> class="form-check-input">
                                <label class="form-check-label" for="<?php echo lang('qty') . '/' . lang('hours'); ?>"><?php echo lang('qty') . '/' . lang('hours'); ?></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive s_table">
                    <table class="table table-editable table-nowrap align-middle table-edits invoice-items-table items">
                        <thead style="background: #e8e8e8">
                        <tr>
                            <th></th>
                            <th><?= $language_info['item_name'] ?></th>
                            <th><?= $language_info['description'] ?></th>
                            <?php
                            $qty_heading = $language_info['qty'];
                            if (isset($invoice_info) && $invoice_info->show_quantity_as == 'hours' || isset($hours_quantity)) {
                                $qty_heading = lang('hours');
                            } else if (isset($invoice_info) && $invoice_info->show_quantity_as == 'qty_hours') {
                                $qty_heading = lang('qty') . '/' . lang('hours');
                            }
                            ?>
                            <th class="qty col-xl-2"><?php echo $qty_heading; ?></th>
                            <th class="col-xl-2"><?= $language_info['price'] ?></th>
                            <th class="col-xl-2"><?= $language_info['tax_rate'] ?> </th>
                            <th class="col-xl-1"><?= $language_info['total'] ?></th>
                            <th class="col-xl-1 hidden-print"><?= $language_info['action'] ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($invoice_info)) {
                            echo form_hidden('merge_current_invoice', $invoice_info->invoices_id);
                            echo form_hidden('isedit', $invoice_info->invoices_id);
                        }
                        ?>
                        <tr class="main">
                            <td></td>
                            <td>
                                <textarea name="item_name" class="form-control"
                              placeholder="<?php echo lang('item_name'); ?>"></textarea>
                            </td>
                            <td>
                    <textarea name="item_desc" class="form-control"
                              placeholder="<?php echo lang('description'); ?>"></textarea>
                            </td>
                            <td>
                                <input type="number" name="quantity" step="0.01" min="0" value="1" class="form-control" id="floatingnameInput" placeholder="<?php echo lang('qty'); ?>">
                                <input type="text" placeholder="<?php echo lang('unit') . ' ' . lang('type'); ?>" name="unit" class="form-control input-transparent">
                            </td>
                            <td>
                                <input type="number" step="0.01" name="unit_cost" class="form-control" placeholder="<?php echo lang('price'); ?>">
                            </td>
                            <td>
                                <?php
                                $taxes = get_order_by('tbl_tax_rates', null, 'tax_rate_percent', true);
                                $default_tax = config_item('default_tax');
                                if (!is_numeric($default_tax)) {
                                    $default_tax = unserialize($default_tax);
                                }
                                $select = '<select class="select_box form-control tax main-tax select2-multiple" multiple="multiple" name="taxname" data-placeholder="' . lang('no_tax') . '">';
                                foreach ($taxes as $tax) {
                                    $selected = '';
                                    if (!empty($default_tax) && is_array($default_tax)) {
                                        if (in_array($tax->tax_rates_id, $default_tax)) {
                                            $selected = ' selected ';
                                        }
                                    }
                                    $select .= '<option value="' . $tax->tax_rate_name . '|' . $tax->tax_rate_percent . '"' . $selected . 'data-taxrate="' . $tax->tax_rate_percent . '" data-taxname="' . $tax->tax_rate_name . '" data-subtext="' . $tax->tax_rate_name . '">' . $tax->tax_rate_percent . '%</option>';
                                }
                                $select .= '</select>';
                                echo $select;
                                ?>
                            </td>
                            <td></td>
                            <td>
                                <?php
                                $new_item = 'undefined';
                                if (isset($invoice_info)) {
                                    $new_item = true;
                                }
                                ?>
                                <button type="button"
                                        onclick="add_item_to_table('undefined','undefined',<?php echo $new_item; ?>); return false;"
                                        class="btn-xs btn btn-info"><i class="fa fa-check"></i>
                                </button>
                            </td>
                        </tr>
                        <?php
                        if (!empty($tasks)) {
                            $tasks = $tasks;
                        } else {
                            $tasks = null;
                        }

                        if (!empty($expense)) {
                            $expense = $expense;
                        } else {
                            $expense = null;
                        }
                        $add_items = $this->items_model->make_all_items($project_info->project_id, $items_name, $tasks, $expense);

                        if (isset($invoice_info) || isset($add_items)) {
                            $i = 1;
                            $items_indicator = 'items';
                            if (isset($invoice_info)) {
                                $add_items = $this->invoice_model->ordered_items_by_id(0);
                                $items_indicator = 'items';
                            }
                            foreach ($add_items as $item) {
                                $manual = false;
                                $table_row = '<tr class="sortable item">';
                                $table_row .= '<td class="dragger">';
                                if (!is_numeric($item->quantity)) {
                                    $item->quantity = 1;
                                }
                                $taxes = get_order_by('tbl_tax_rates', null, 'tax_rate_percent', true);
                                $default_tax = config_item('default_tax');
                                if (!is_numeric($default_tax)) {
                                    $default_tax = unserialize($default_tax);
                                }
                                $select = '<select class="select_box form-control tax main-tax select2-multiple" multiple="multiple" name="taxname" data-placeholder="' . lang('no_tax') . '">';
                                foreach ($taxes as $tax) {
                                    $selected = '';
                                    if (!empty($default_tax) && is_array($default_tax)) {
                                        if (in_array($tax->tax_rates_id, $default_tax)) {
                                            $selected = ' selected ';
                                        }
                                    }
                                    $select .= '<option value="' . $tax->tax_rate_name . '|' . $tax->tax_rate_percent . '"' . $selected . 'data-taxrate="' . $tax->tax_rate_percent . '" data-taxname="' . $tax->tax_rate_name . '" data-subtext="' . $tax->tax_rate_name . '">' . $tax->tax_rate_percent . '%</option>';
                                }
                                $select .= '</select>';

                                $table_row .= form_hidden('' . $items_indicator . '[' . $i . '][items_id]', $item->items_id);
                                $amount = $item->unit_cost * $item->quantity;
                                $amount = ($amount);
                                // order input
                                $table_row .= '<input type="hidden" class="order" name="' . $items_indicator . '[' . $i . '][order]"><input type="hidden" name="items_id[]" value="' . $item->items_id . '">';
                                if (!empty($item->task_id)) {
                                    $table_row .= '<input type="hidden" name="task_id[]" value="' . $item->task_id . '">';
                                }

                                $table_row .= '</td>';
                                $table_row .= '<td class="bold item_name"><textarea name="' . $items_indicator . '[' . $i . '][item_name]" class="form-control">' . $item->item_name . '</textarea></td>';
                                $table_row .= '<td><textarea name="' . $items_indicator . '[' . $i . '][item_desc]" class="form-control" >' . strip_html_tags($item->item_desc) . '</textarea></td>';
                                $table_row .= '<td><input type="number" step="0.01" min="0" onblur="calculate_total();" onchange="calculate_total();" data-quantity name="' . $items_indicator . '[' . $i . '][quantity]" value="' . $item->quantity . '" class="form-control">';
                                $unit_placeholder = '';
                                if (!$item->unit) {
                                    $unit_placeholder = lang('unit');
                                    $item->unit = '';
                                }
                                $table_row .= '<input type="text" placeholder="' . $unit_placeholder . '" name="' . $items_indicator . '[' . $i . '][unit]" class="form-control input-transparent text-right" value="' . $item->unit . '">';
                                $table_row .= '</td>';
                                $table_row .= '<td class="rate"><input type="text" data-bs-toggle="tooltip"  onblur="calculate_total();" onchange="calculate_total();" name="' . $items_indicator . '[' . $i . '][unit_cost]" value="' . $item->unit_cost . '" class="form-control"></td>';
                                $table_row .= '<td class="taxrate">' . $select . '</td>';
                                $table_row .= '<td class="amount">' . $amount . '</td>';
                                $table_row .= '<td><a href="#" class="btn-xs btn btn-danger pull-left" onclick="delete_item(this,' . $item->items_id . '); return false;"><i class="fa fa-trash"></i></a></td>';
                                $table_row .= '</tr>';
                                echo $table_row;
                                $i++;
                            }
                        }
                        ?>

                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-xl-5"></div>
                    <div class="col-xl-7 pull-right">
                        <table class="table text-right">
                            <tbody>
                            <tr id="subtotal" class="text-end">
                                <td><span class="bold"><?php echo lang('sub_total'); ?> :</span>
                                </td>
                                <td class="subtotal text-end">
                                </td>
                            </tr>
                            <tr id="discount_percent" class="text-end">
                                <td>
                                    <div class="row">
                                        <div class="col-md-7 mt"> <span class="bold"><?php echo lang('discount'); ?> (%)</span>
                                        </div>
                                        <div class="col-md-5 text-end">
                                            <?php
                                            $discount_percent = 0;
                                            if (isset($invoice_info)) {
                                                if ($invoice_info->discount_percent != 0) {
                                                    $discount_percent = $invoice_info->discount_percent;
                                                }
                                            }
                                            ?>
                                            <input type="number" step="0.01"
                                                   value="<?php echo $discount_percent; ?>"
                                                   class="form-control pull-left" min="0" max="100"
                                                   name="discount_percent">
                                        </div>
                                    </div>
                                </td>
                                <td class="discount_percent text-end"></td>
                            </tr>
                            <tr class="text-end">
                                <td>
                                    <div class="row">
                                        <div class="col-md-7 mt">
                                            <span class="bold"><?php echo lang('adjustment'); ?></span>
                                        </div>
                                        <div class="col-md-5 text-end">
                                            <input type="number" step="0.01"
                                                   value="<?php if (isset($invoice_info)) {
                                                       echo $invoice_info->adjustment;
                                                   } else {
                                                       echo 0;
                                                   } ?>" class="form-control pull-left"
                                                   name="adjustment">
                                        </div>
                                    </div>
                                </td>
                                <td class="adjustment text-end"></td>
                            </tr>
                            <tr class="text-end">
                                <td><span class="bold"><?php echo lang('total'); ?> :</span>
                                </td>
                                <td class="total text-end">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="removed-items"></div>
                <div class="modal-footer">
                    <a href="<?= base_url() ?>admin/projects/project_details/<?= $project_info->project_id ?>"
                       class="btn btn-secondary"><?= lang('close') ?></a>
                    <input type="submit" value="<?= lang('save_as_draft') ?>" name="save_as_draft"
                           class="btn btn-primary">
                    <input type="submit" value="<?= lang('update') ?>" name="update"
                           class="btn btn-success">
                </div>
            </div>

        </div>
    </div>
</div>
</form>
<script type="text/javascript">
    function slideToggle($id) {
        $('#quick_state').attr('data-original-title', '<?= lang('view_quick_state') ?>');
        $($id).slideToggle("slow");
    }
    $(document).ready(function () {
        $("#select_all_tasks").click(function () {
            $(".tasks_list").prop('checked', $(this).prop('checked'));
        });
        $("#select_all_expense").click(function () {
            $(".expense_list").prop('checked', $(this).prop('checked'));
        });
        $('[data-bs-toggle="popover"]').popover();

    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        init_items_sortable();
        $('#start_recurring').click(function () {
            $('.recurring').slideToggle("fast");
            $('.recurring').removeClass("hide");
            $('#recuring_frequency').prop('disabled', false);
        });
    });
</script>

