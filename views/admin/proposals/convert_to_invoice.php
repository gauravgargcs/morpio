<div class="modal-header">
    <h5 class="modal-title"><?= lang('convert_to_invoice') ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
 <style type="text/css">
    .dropdown-menu > li > a {
        white-space: normal;
    }

    .dragger {
        background: url(../../../../skote_assets/images/dragger.png) 10px 32px no-repeat;
        cursor: pointer;
    }

    <?php if (!empty($proposals_info)) { ?>
    .dragger {
        background: url(../../../../skote_assets/images/dragger.png) 10px 32px no-repeat;
        cursor: pointer;
    }

    <?php }?>
    .input-transparent {
        box-shadow: none;
        outline: 0;
        border: 0 !important;
        background: 0 0;
        padding: 3px;
    }

</style>
<script type="text/javascript">
$(document).ready(function () {
    init_items_sortable();
});
</script>
<form role="form" data-parsley-validate="" novalidate="" action="<?php echo base_url(); ?>admin/proposals/converted_to_invoice/<?= $proposals_info->proposals_id ?>" method="post" class="form-horizontal">
    <div class="modal-body wrap-modal wrap p-4">
        <div class="row mb-lg invoice-accounting-template">
            <div class="row">
                <div class="col-xl-6 br pv"> 
                    <div class="row mb-3">
                        <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('reference_no') ?> <span
                                class="text-danger">*</span></label>
                        <div class="col-xl-4 col-md-4 col-sm-4">
                            <input type="text" class="form-control" value="<?php
                            echo config_item('invoice_prefix');
                            if (config_item('increment_invoice_number') == 'FALSE') {
                                $this->load->helper('string');
                                echo random_string('nozero', 6);
                            } else {
                                echo $this->proposal_model->generate_invoice_number();
                            }
                            ?>" name="reference_no">
                        </div>
                        <div class="col-xl-1 col-md-1 col-sm-1">
                            <div class="btn btn-sm btn-info" id="start_recurring"><?= lang('recurring') ?></div>
                        </div>
                    </div>
                    <div id="recurring" class="hide">
                        <div class="row mb-3">
                            <label
                                class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('recur_frequency') ?> </label>
                            <div class="col-xl-7 col-md-7 col-sm-7">
                                <select name="recuring_frequency" id="recuring_frequency"
                                        class="form-control">
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
                        <div class="row mb-3">
                            <label
                                class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('start_date') ?></label>
                            <div class="col-xl-7 col-md-7 col-sm-7">
                                <?php
                                $recur_start_date = date('Y-m-d');
                                $recur_end_date = date('Y-m-d');
                                ?>
                                <div class="input-group">
                                    <input class="form-control datepicker" type="text"
                                           value="<?= $recur_start_date; ?>"
                                           name="recur_start_date"
                                           data-date-format="<?= config_item('date_picker_format'); ?>">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label
                                class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('end_date') ?></label>
                            <div class="col-xl-7 col-md-7 col-sm-7">
                                <div class="input-group">
                                    <input class="form-control datepicker" type="text"
                                           value="<?= $recur_end_date; ?>"
                                           name="recur_end_date"
                                           data-date-format="<?= config_item('date_picker_format'); ?>">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="f_client_id">
                        <div class="row mb-3">
                            <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('client') ?> <span
                                    class="text-danger">*</span>
                            </label>
                            <div class="col-xl-7 col-md-7 col-sm-7">
                                <select class="form-control modal_select_box" required
                                        style="width: 100%"
                                        name="client_id"
                                        onchange="get_project_by_id(this.value)">
                                    <option
                                        value="-"><?= lang('select') . ' ' . lang('client') ?></option>
                                    <?php
                                    if (!empty($all_client)) {
                                        foreach ($all_client as $v_client) {
                                            if (!empty($project_info->client_id)) {
                                                $client_id = $project_info->client_id;
                                            } elseif (!empty($proposals_info->module) && $proposals_info->module == 'client') {
                                                $client_id = $proposals_info->module_id;
                                            }
                                            ?>
                                            <option value="<?= $v_client->client_id ?>"
                                                <?php
                                                if (!empty($client_id)) {
                                                    echo $client_id == $v_client->client_id ? 'selected' : null;
                                                }
                                                ?>
                                            ><?= ($v_client->name) ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('project') ?></label>
                        <div class="col-xl-7 col-md-7 col-sm-7">
                            <select class="form-control" style="width: 100%"
                                    name="project_id"
                                    id="client_project">
                                <option value=""><?= lang('none') ?></option>
                                <?php

                                if (!empty($client_id)) {

                                    if (!empty($project_info->project_id)) {
                                        $project_id = $project_info->project_id;
                                    } elseif ($proposals_info->project_id) {
                                        $project_id = $proposals_info->project_id;
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
                        <label
                            class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('invoice_date') ?></label>
                        <div class="col-xl-7 col-md-7 col-sm-7">
                            <div class="input-group">
                                <input type="text" name="invoice_date"
                                       class="form-control datepicker"
                                       value="<?php
                                       if (!empty($proposals_info->proposal_date)) {
                                           echo $proposals_info->proposal_date;
                                       } else {
                                           echo date('d-m-Y H-i');
                                       }
                                       ?>"
                                       data-date-format="<?= config_item('date_picker_format'); ?>">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('due_date') ?></label>
                        <div class="col-xl-7 col-md-7 col-sm-7">
                            <div class="input-group">
                                <input type="text" name="due_date"
                                       class="form-control datepicker"
                                       value="<?php
                                       if (!empty($proposals_info->due_date)) {
                                           echo $proposals_info->due_date;
                                       } else {
                                           echo date('d-m-Y H-i');
                                       }
                                       ?>"
                                       data-date-format="<?= config_item('date_picker_format'); ?>">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="discount_type"
                               class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('discount_type') ?></label>
                        <div class="col-xl-7 col-md-7 col-sm-7">
                            <select name="discount_type" class="modal_select_box form-control" data-width="100%">
                                <option value=""
                                        selected><?php echo lang('no') . ' ' . lang('discount'); ?></option>
                                <option value="before_tax" <?php
                                if (isset($proposals_info)) {
                                    if ($proposals_info->discount_type == 'before_tax') {
                                        echo 'selected';
                                    }
                                } ?>><?php echo lang('before_tax'); ?></option>
                                <option value="after_tax" <?php if (isset($proposals_info)) {
                                    if ($proposals_info->discount_type == 'after_tax') {
                                        echo 'selected';
                                    }
                                } ?>><?php echo lang('after_tax'); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3" id="border-none">
                        <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('permission') ?> <span
                                class="required">*</span></label>
                        <div class="col-xl-7 col-md-7 col-sm-7">
                            <div class="form-check form-radio-outline form-radio-primary mt mr">
                                <input id="everyone" <?php
                                        if (!empty($proposals_info->permission) && $proposals_info->permission == 'all') {
                                            echo 'checked';
                                        } 
                                        ?> type="radio" name="permission" value="everyone" class="form-check-input">
                                <label class="form-check-label" for="everyone"><?= lang('everyone') ?>
                                    <i title="<?= lang('permission_for_all') ?>"
                                       class="fa fa-question-circle" data-bs-toggle="tooltip"
                                       data-bs-placement="top"></i>
                                </label>
                            </div>
                            <div class="form-check form-radio-outline form-radio-primary mt mr">
                                <input id="custom_permission" <?php
                                        if (!empty($proposals_info->permission) && $proposals_info->permission != 'all') {
                                            echo 'checked';
                                        }elseif (empty($proposals_info)) {
                                            echo 'checked';
                                        }
                                        ?> type="radio" name="permission" value="custom_permission" class="form-check-input">
                                <label class="form-check-label" for="custom_permission"><?= lang('custom_permission') ?>
                                    <i title="<?= lang('permission_for_customization') ?>"
                                        class="fa fa-question-circle" data-bs-toggle="tooltip"
                                        data-bs-placement="top"></i>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 <?php
                        if (!empty($proposals_info->permission) && $proposals_info->permission != 'all') {
                            echo 'show';
                        }
                        ?>" id="permission_user">
                        <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('select') . ' ' . lang('users') ?>
                            <span class="required">*</span></label>
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
                                <div class="form-check form-check mb-3 mr">
                                    <input type="checkbox" 
                                        <?php
                                        if (!empty($proposals_info->permission) && $proposals_info->permission != 'all') {
                                            $get_permission = json_decode($proposals_info->permission);
                                            foreach ($get_permission as $user_id => $v_permission) {
                                                if ($user_id == $v_user->user_id) {
                                                    echo 'checked';
                                                }
                                            }

                                        }
                                        ?>  value="<?= $v_user->user_id ?>" name="assigned_to[]" data-name="<?= $v_user->username;?>"  class="form-check-input" id="user_<?= $v_user->user_id ?>">
                                    <label class="form-check-label" for="user_<?= $v_user->user_id ?>"><?= $v_user->username . ' ' . $role ?>
                                    </label>
                                </div>
                                <div class="action_1 p   
                                    <?php
                                    if (!empty($proposals_info->permission) && $proposals_info->permission != 'all') {
                                        $get_permission = json_decode($proposals_info->permission);

                                        foreach ($get_permission as $user_id => $v_permission) {
                                            if ($user_id == $v_user->user_id) {
                                                echo 'show';
                                            }
                                        }

                                    }
                                    ?>" id="action_1<?= $v_user->user_id ?>">
                                    <div class="form-check form-check mb-3 mr-5">
                                        <input class="form-check-input" type="checkbox" id="view_<?= $v_user->user_id ?>" checked name="action_<?= $v_user->user_id ?>[]" disabled  value="view">
                                        <label class="form-check-label" for="view_<?= $v_user->user_id ?>">
                                            <?= lang('can') . ' ' . lang('view') ?>
                                        </label>
                                    </div>
                                    
                                    <div class="form-check form-check mb-3 mr-5">
                                        <input class="form-check-input" type="checkbox" value="edit" name="action_<?= $v_user->user_id ?>[]" id="edit_<?= $v_user->user_id ?>"
                                            <?php

                                            if (!empty($proposals_info->permission) && $proposals_info->permission != 'all') {
                                                $get_permission = json_decode($proposals_info->permission);

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

                                    <div class="form-check form-check mb-3 mr-5">
                                        <input class="form-check-input" name="action_<?= $v_user->user_id ?>[]" type="checkbox" value="delete" id="delete_<?= $v_user->user_id ?>"
                                            <?php

                                            if (!empty($proposals_info->permission) && $proposals_info->permission != 'all') {
                                                $get_permission = json_decode($proposals_info->permission);
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
                        <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 br pv">
                    <div class="row">
                        <div class="row mb-3">
                            <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('sales') . ' ' . lang('agent') ?></label>
                            <div class="col-xl-7 col-md-7 col-sm-7">
                                <select class="form-control modal_select_box" required style="width: 100%" name="user_id">
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
                                                    if (!empty($proposals_info->user_id)) {
                                                        echo $proposals_info->user_id == $v_user->user_id ? 'selected' : null;
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
                            <label  class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('allow_paypal') ?></label>
                            <div class="col-xl-7 col-md-7 col-sm-7">
                                <div class="form-check form-check mb-3 mr">
                                    <input type="checkbox" value="Yes"
                                        <?php if (!empty($invoice_info) && $invoice_info->allow_paypal == 'Yes') {
                                            echo 'checked';
                                        } ?> name="allow_paypal" class="form-check-input">
                                    <label class="form-check-label"></label>
                                </div>
                            </div>
                        </div>
                        <?php endif ?>
                        <?php if (config_item('stripe_status') == 'active'): ?>
                        <div class="row mb-3">
                            <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('allow_stripe') ?></label>
                            <div class="col-xl-7 col-md-7 col-sm-7">
                                <div class="form-check form-check mb-3 mr">
                                    <input type="checkbox" value="Yes"
                                                <?php if (!empty($invoice_info) && $invoice_info->allow_stripe == 'Yes') {
                                                    echo 'checked';
                                                } ?> name="allow_stripe"  class="form-check-input">
                                    <label class="form-check-label"></label>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (config_item('2checkout_status') == 'active'): ?>
                        <div class="row mb-3">
                            <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('allow_2checkout') ?></label>

                            <div class="col-xl-7 col-md-7 col-sm-7">

                                <div class="form-check form-check mb-3 mr">
                                    <input type="checkbox" value="Yes"
                                        <?php if (!empty($invoice_info) && $invoice_info->allow_2checkout == 'Yes') {
                                            echo 'checked';
                                        } ?> name="allow_2checkout" class="form-check-input">
                                    <label class="form-check-label"></label>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (config_item('authorize_status') == 'active'): ?>
                        <div class="row mb-3">
                            <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('allow_authorize.net') ?></label>

                            <div class="col-xl-7 col-md-7 col-sm-7">
                                <div class="form-check form-check mb-3 mr">
                                    <input type="checkbox" value="Yes"
                                        <?php if (!empty($invoice_info) && $invoice_info->allow_authorize == 'Yes') {
                                            echo 'checked';
                                        } ?>  name="allow_authorize" class="form-check-input">
                                    <label class="form-check-label"></label>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (config_item('ccavenue_status') == 'active'): ?>
                        <div class="row mb-3">
                            <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('allow_ccavenue') ?></label>

                            <div class="col-xl-7 col-md-7 col-sm-7">
                                <div class="form-check form-check mb-3 mr">
                                    <input type="checkbox" value="Yes"
                                        <?php if (!empty($invoice_info) && $invoice_info->allow_ccavenue == 'Yes') {
                                            echo 'checked';
                                        } ?> name="allow_ccavenue"class="form-check-input">
                                    <label class="form-check-label"></label>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (config_item('braintree_status') == 'active'): ?>
                        <div class="row mb-3">
                            <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('allow_braintree') ?></label>

                            <div class="col-xl-7 col-md-7 col-sm-7">
                                <div class="form-check form-check mb-3 mr">
                                    <input type="checkbox" value="Yes"
                                            <?php if (!empty($invoice_info) && $invoice_info->allow_braintree == 'Yes') {
                                                echo 'checked';
                                            } ?>
                                               name="allow_braintree" class="form-check-input">
                                    <label class="form-check-label"></label>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (config_item('mollie_status') == 'active'): ?>
                        <div class="row mb-3">
                            <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('allow_mollie') ?></label>

                            <div class="col-xl-7 col-md-7 col-sm-7">
                                <div class="form-check form-check mb-3 mr">
                                    <input type="checkbox" value="Yes"
                                            <?php if (!empty($invoice_info) && $invoice_info->allow_mollie == 'Yes') {
                                                echo 'checked';
                                            } ?>
                                               name="allow_mollie" class="form-check-input">
                                    <label class="form-check-label"></label>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (config_item('payumoney_status') == 'active'): ?>
                        <div class="row mb-3">
                            <label  class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('allow_payumoney') ?></label>

                            <div class="col-xl-7 col-md-7 col-sm-7">
                                <div class="form-check form-check mb-3 mr">
                                    <input type="checkbox" value="Yes"
                                            <?php if (!empty($invoice_info) && $invoice_info->allow_payumoney == 'Yes') {
                                                echo 'checked';
                                            } ?>
                                               name="allow_payumoney" class="form-check-input">
                                    <label class="form-check-label"></label>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($project_id)): ?>
                        <div class="row mb-3">
                            <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('visible_to_client') ?>
                                <span class="required">*</span></label>
                            <div class="col-xl-7 col-md-7 col-sm-7">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" name="client_visible" value="Yes" <?php
                                    if (!empty($proposals_info->client_visible) && $proposals_info->client_visible == 'Yes') {
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
            <hr>
            <div class="row">
                <div class="col-xl-6 br pv">
                    <div class="terms row mb-3">
                        <label class="col-xl-2 col-form-label col-md-2 col-sm-2"><?= lang('notes') ?> </label>
                        <div class="col-xl-9 col-md-9 col-sm-9">
                            <textarea name="notes" id="elm1" class="textarea_2"><?php
                                if (!empty($proposals_info)) {
                                    echo $proposals_info->notes;
                                } else {
                                    echo $this->config->item('default_terms');
                                }
                                ?>        
                            </textarea>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 br pv ">
                    <div class="col-md-12">
                        <input type="text" class="form-control" name="term"
                               placeholder="<?= lang('enter_product_name_and_code') ?>"
                               id="search_text">
                        <hr class="m0 mb-lg mt-lg"/>
                    </div>
                    <script>
                        $(document).ready(function () {
                            load_data();
                            function load_data(query) {
                                $.ajax({
                                    url: "<?php echo base_url(); ?>admin/global_controller/items_suggestions/12",
                                    method: "POST",
                                    data: {term: query},
                                    success: function (data) {
                                        $('#product_result').html(data);
                                    }
                                })
                            }

                            $('#search_text').keyup(function () {
                                var search = $(this).val();
                                if (search != '') {
                                    load_data(search);
                                }
                                else {
                                    load_data();
                                }
                            });
                        });
                    </script>
                    <div id="product_result" class="product_result row">

                    </div>
                </div>
            </div>
            <?php
            if ($proposals_info->module == 'client') {
                $client_info = $this->proposal_model->check_by(array('client_id' => $proposals_info->module_id), 'tbl_client');
                if (!empty($client_info)) {
                    $currency = $this->proposal_model->client_currency_sambol($proposals_info->module_id);
                    $client_lang = $client_info->language;
                } else {
                    $client_lang = 'english';
                    $currency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                }
            } else if ($proposals_info->module == 'leads') {
                $client_info = $this->proposal_model->check_by(array('leads_id' => $proposals_info->module_id), 'tbl_leads');
                if (!empty($client_info)) {
                    $client_info->name = $client_info->contact_name;
                    $client_info->zipcode = null;
                }
                $client_lang = 'english';
                $currency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
            } else {
                $client_lang = 'english';
                $currency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
            }
            unset($this->lang->is_loaded[5]);
            $language_info = $this->lang->load('sales_lang', $client_lang, TRUE, FALSE, '', TRUE);
            $saved_items = $this->proposal_model->get_all_items();
            ?>
            <hr class=""/>
            <div class="row p-3">
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
                                <input type="radio" value="qty" id="<?php echo lang('qty'); ?>"
                                   name="show_quantity_as"
                                    <?php if (isset($proposals_info) && $proposals_info->show_quantity_as == 'qty') {
                                        echo 'checked';
                                    } else if (!isset($hours_quantity) && !isset($qty_hrs_quantity)) {
                                        echo 'checked';
                                    } ?>  class="form-check-input">
                                <label class="form-check-label" for="<?php echo lang('qty'); ?>"><?php echo lang('qty'); ?></label>
                            </div>
                            <div class="form-check form-radio-outline form-radio-primary mt mr">
                                <input type="radio" value="hours" id="<?php echo lang('hours'); ?>"
                                       name="show_quantity_as" <?php if (isset($proposals_info) && $proposals_info->show_quantity_as == 'hours' || isset($hours_quantity)) {
                                        echo 'checked';
                                    } ?> class="form-check-input">
                                <label class="form-check-label" for="<?php echo lang('hours'); ?>"><?php echo lang('hours'); ?></label>
                            </div>
                            <div class="form-check form-radio-outline form-radio-primary mt mr">
                                <input type="radio" value="qty_hours"
                                       id="<?php echo lang('qty') . '/' . lang('hours'); ?>"
                                       name="show_quantity_as"
                                        <?php if (isset($proposals_info) && $proposals_info->show_quantity_as == 'qty_hours' || isset($qty_hrs_quantity)) {
                                            echo 'checked';
                                        } ?> class="form-check-input">
                                <label class="form-check-label" for="<?php echo lang('qty') . '/' . lang('hours'); ?>"><?php echo lang('qty') . '/' . lang('hours'); ?></label>
                            </div>
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
                            $invoice_view = config_item('invoice_view');
                            if (!empty($invoice_view) && $invoice_view == '2') {
                                ?>
                                <th class="col-sm-2"><?= $language_info['hsn_code'] ?></th>
                            <?php } ?>
                            <?php
                            $qty_heading = $language_info['qty'];
                            if (isset($proposals_info) && $proposals_info->show_quantity_as == 'hours' || isset($hours_quantity)) {
                                $qty_heading = lang('hours');
                            } else if (isset($proposals_info) && $proposals_info->show_quantity_as == 'qty_hours') {
                                $qty_heading = lang('qty') . '/' . lang('hours');
                            }
                            ?>
                            <th class="qty col-sm-1"><?php echo $qty_heading; ?></th>
                            <th class="col-sm-2"><?= $language_info['price'] ?></th>
                            <th class="col-sm-2"><?= $language_info['tax_rate'] ?> </th>
                            <th class="col-sm-1"><?= $language_info['total'] ?></th>
                            <th class="col-sm-1 hidden-print"><?= $language_info['action'] ?></th>
                        </tr>
                    </thead>
                    <?php if (isset($proposals_info)) {
                        echo form_hidden('merge_current_invoice', $proposals_info->proposals_id);
                        echo form_hidden('isedit', $proposals_info->proposals_id);
                    }
                    ?>
                    <tbody id="InvoiceTable">
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
                                            if (isset($proposals_info)) {
                                                if ($proposals_info->discount_percent != 0) {
                                                    $discount_percent = $proposals_info->discount_percent;
                                                }
                                            }
                                            ?>
                                            <input type="text" data-parsley-type="number" value="<?php echo $discount_percent; ?>" class="form-control pull-left" min="0" max="100" name="discount_percent">
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
                                        <input type="text" data-parsley-type="number"
                                           value="<?php if (isset($proposals_info)) {
                                               echo $proposals_info->adjustment;
                                           } else {
                                               echo 0;
                                           } ?>"  class="form-control pull-left"
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
    </div>
    <div class="modal-footer">
        <div class="mb-3 p-4">
            <button type="button" class="btn btn-secondary w-md waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close"><?= lang('close') ?></button>
            <input type="submit" class="btn btn-primary w-md waves-effect waves-light" value="<?= lang('save_as_draft') ?>" name="save_as_draft" >            
            <input type="submit" class="btn btn-success w-md waves-effect waves-light" value="<?= lang('save') ?>" name="update" >            
        </div>
    </div>
</form>

<?php
if (isset($proposals_info)) {
    $add_items = $this->proposal_model->ordered_items_by_id($proposals_info->proposals_id, true);
    if (!empty($add_items)) {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                store('InvoiceItems', JSON.stringify(<?= $add_items; ?>));
            });
        </script>
    <?php }
} ?>
<script type="text/javascript">
    var InvoiceItems = {};
</script>
<script type="text/javascript">

    $(document).ready(function () {
        $('#start_recurring').click(function () {
            $('#recurring').slideToggle("fast");
            $('#recurring').removeClass("hide");
            $('#recuring_frequency').prop('disabled', false);
        });
    });
</script>
<?php include_once 'skote_assets/js/invoice.php'; ?>
<script src="<?=base_url()?>skote_assets/js/pages/form-editor.init.js"></script>
