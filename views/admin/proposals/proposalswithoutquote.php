<?= message_box('success'); ?>
<?= message_box('error'); ?>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <a type="button" href="<?php echo base_url('admin/proposals'); ?>" class="btn btn-primary waves-effect waves-light btn-sm clock_in_button">All Proposal</a>
            <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4>
            <?php $this->load->view('admin/skote_layouts/title'); ?>
        </div>
    </div>
</div>
<!-- end page title -->
<?php
$created = can_action('140', 'created');
$edited = can_action('140', 'edited');
$deleted = can_action('140', 'deleted');
if (!empty($proposals_info)) {
    $proposals_id = $proposals_info->proposals_id;
    $companies_id = $proposals_info->companies_id;
    $unlayertemplate_id = $proposals_info->unlayertemplateid;
    $unlayertemplatejson = $proposals_info->unlayertemplatejson;
} else {
    $proposals_id = null;
    $companies_id = null;
    $unlayertemplate_id = null;
    $unlayertemplatejson = null;
}
echo form_open(base_url('admin/proposals/save_proposals_without_quote/' . $proposals_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form'));
?>
<?php
$curency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
$currency = $this->db->where(array('code' => config_item('default_currency')))->get('tbl_currencies')->row();
if ($this->session->userdata('user_type') == 1) {
    $margin = 'margin-bottom:30px';
    $h_s = config_item('proposal_state');

    $expired = 0;
    $draft = 0;
    $total_draft = 0;
    $total_sent = 0;
    $total_declined = 0;
    $total_accepted = 0;
    $total_expired = 0;
    $sent = 0;
    $declined = 0;
    $accepted = 0;
    $pending = 0;
    $cancelled = 0;
    $all_proposals = $this->proposal_model->get_permission('tbl_proposals');
    if (!empty($all_proposals)) {
        $all_proposals = array_reverse($all_proposals);
        foreach ($all_proposals as $v_invoice) {
            if (strtotime($v_invoice->due_date) < time() AND $v_invoice->status == ('pending') || strtotime($v_invoice->due_date) < time() AND $v_invoice->status == ('draft')) {
                $total_expired += $this->proposal_model->proposal_calculation('total', $v_invoice->proposals_id);
                $expired += count($v_invoice->proposals_id);;
            }
            if ($v_invoice->status == ('draft')) {
                $draft += count($v_invoice->proposals_id);
                $total_draft += $this->proposal_model->proposal_calculation('total', $v_invoice->proposals_id);
            }
            if ($v_invoice->status == ('sent')) {
                $sent += count($v_invoice->proposals_id);
                $total_sent += $this->proposal_model->proposal_calculation('total', $v_invoice->proposals_id);
            }
            if ($v_invoice->status == ('declined')) {
                $declined += count($v_invoice->proposals_id);
                $total_declined += $this->proposal_model->proposal_calculation('total', $v_invoice->proposals_id);
            }
            if ($v_invoice->status == ('accepted')) {
                $accepted += count($v_invoice->proposals_id);
                $total_accepted += $this->proposal_model->proposal_calculation('total', $v_invoice->proposals_id);
            }
            if ($v_invoice->status == ('pending')) {
                $pending += count($v_invoice->proposals_id);
            }
            if ($v_invoice->status == ('cancelled')) {
                $cancelled += count($v_invoice->proposals_id);
            }
        }
    }
    ?>
<?php
}
$type = $this->uri->segment(5);

if (empty($type)) {
    $type = '_' . date('Y');
}
?>

<div class="row">
    <div class="col-xl-12">
        <?php if (!empty($created) || !empty($edited)) { ?>
        <div class="tab-pane <?= $active == 3 ? 'active' : ''; ?>" id="withoutquote">
            <input type="hidden" name="proposal_type" id="proposal_type" value="presentationwithoutquote">
            <input type="hidden" name="notes" id="notes" value="">
            <input type="hidden" name="unlayertemplateid" id="unlayertemplateid" value="<?php
            if (!empty($proposals_info)) {
                echo $proposals_info->unlayertemplateid;
            }
            ?>">
            <textarea style="display:none" name="unlayertemplatejson" id="unlayertemplatejson" rows="5" cols="5"><?php
                                if (!empty($proposals_info)) {
                                    echo $proposals_info->unlayertemplatejson;
                                }
                                ?></textarea>
            <textarea style="display:none" name="unlayertemplatehtml" id="unlayertemplatehtml" rows="5" cols="5"><?php
                                if (!empty($proposals_info)) {
                                    echo ($proposals_info->unlayertemplatehtml);
                                }
                                ?></textarea>
            <div class="row invoice proposal-template">                    
                <div class="card border">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-lg-6 br pv">
                                        <?php super_admin_form($companies_id, 5, 7) ?>
                                <div class="row mb-3">
                                    <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('reference_no') ?> <span class="text-danger">*</span></label>
                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                <?php $this->load->helper('string'); ?>
                                        <input type="text" class="form-control" value="<?php
                                if (!empty($proposals_info)) {
                                    echo $proposals_info->reference_no;
                                } else {
                                    echo config_item('proposal_prefix');
                                    if (config_item('increment_proposal_number') == 'FALSE') {
                                        $this->load->helper('string');
                                        echo random_string('nozero', 6);
                                    } else {
                                        echo $this->proposal_model->generate_proposal_number();
                                    }
                                }
                                ?>" name="reference_no">
                                    </div>
                                </div>
                                <?php
                                if (!empty($proposals_info->module)) {
                                    if ($proposals_info->module == 'leads') {
                                        $leads_id = $proposals_info->module_id;
                                    }
                                    if ($proposals_info->module == 'client') {
                                        $client_id = $proposals_info->module_id;
                                    }
                                } elseif (!empty($module)) {
                                    if ($module == 'leads') {
                                        $leads_id = $module_id;
                                    }
                                    if ($module == 'client') {
                                        $client_id = $module_id;
                                    }
                                    ?>
                                    <input type="hidden" name="un_module" required class="form-control"
                                           value="<?php echo $module ?>"/>
                                    <input type="hidden" name="un_module_id" required class="form-control"
                                           value="<?php echo $module_id ?>"/>
<?php }
?>
                                <div class="row mb-3" id="border-none">
                                    <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('related_to') ?> </label>
                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                        <select name="module" class="form-control select_box"
                                                id="check_related" required
                                                onchange="get_related_moduleName(this.value, true)"
                                                style="width: 100%">
                                            <option value=""> <?= lang('none') ?> </option>
                                            <option
                                                value="leads" <?= (!empty($leads_id) ? 'selected' : '') ?>> <?= lang('leads') ?> </option>
                                            <option
                                                value="client" <?= (!empty($client_id) ? 'selected' : '') ?>> <?= lang('client') ?> </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="" id="related_to">
                                    <?php if (!empty($leads_id)): ?>
                                        <div class="row mb-3 <?= $leads_id ? 'leads_module' : 'company' ?>"
                                             id="border-none">
                                            <label for="field-1"
                                                   class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('select') . ' ' . lang('leads') ?>
                                                <span class="required">*</span></label>
                                            <div class=" col-lg-7 col-md-7 col-sm-7">
                                                <select name="leads_id" style="width: 100%"
                                                        class="select_box <?= $leads_id ? 'leads_module' : 'company' ?>"
                                                        required="1">
                                                    <?php
                                                    $all_leads_info = get_result('tbl_leads');

                                                    if (!empty($all_leads_info)) {
                                                    foreach ($all_leads_info as $v_leads) {
                                                    ?>
                                                                                                                <option value="<?= $v_leads->leads_id ?>" <?php
                                                    if (!empty($leads_id)) {
                                                    echo $v_leads->leads_id == $leads_id ? 'selected' : '';
                                                    }
                                                    ?>><?= $v_leads->contact_name ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3 <?= $leads_id ? 'leads_module' : 'company' ?>"
                                             id="border-none">
                                            <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('currency') ?>
                                                <span class="required">*</span></label>
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <select name="currency" style="width: 100%"
                                                        class="select_box <?= $leads_id ? 'leads_module' : 'company' ?>"
                                                        required="1">
                                                    <?php
                                                    $all_currency = $this->db->get('tbl_currencies')->result();
                                                    foreach ($all_currency as $v_currency) {
                                                    ?>
                                                                                                            <option
                                                                                                                value="<?= $v_currency->code ?>" <?= (config_item('default_currency') == $v_currency->code ? ' selected="selected"' : '') ?>><?= $v_currency->name ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    <?php if (!empty($client_id)): ?>
                                        <div class="row mb-3 <?= $client_id ? 'client_module' : 'company' ?>"
                                             id="border-none">
                                            <label for="field-1"
                                                   class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('select') . ' ' . lang('client') ?>
                                                <span class="required">*</span></label>
                                            <div class="col-lg-7col-lg-3  col-sm-7">
                                                <select name="client_id" style="width: 100%"
                                                        class="select_box <?= $client_id ? 'client_module' : 'company' ?>"
                                                        required="1">
                                                    <option value="">Select Client</option>
                                                        <?php
                                                        if (!empty($all_client)) {
                                                        foreach ($all_client as $v_client) {
                                                        ?>
                                                                                                                    <option value="<?= $v_client->client_id ?>"
                                                        <?php
                                                        if (!empty($client_id)) {
                                                        echo $client_id == $v_client->client_id ? 'selected' : '';
                                                        }
                                                        ?>
                                                                                                                            ><?= $v_client->name ?></option>
                                                        <?php
                                                        }
                                                        }
                                                        ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                </div>

                                    <div class="row mb-3" style="display:none">
                                    <label for="discount_type"
                                           class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('discount_type') ?></label>
                                    <div class="col-lg-7 co-md-7 col-sm-7">
                                        <select name="discount_type" class="select_box form-control" data-width="100%">
                                            <option value=""
                                                    selected><?php echo lang('no') . ' ' . lang('discount'); ?></option>
                                            <option value="before_tax" <?php
                                                if (isset($proposals_info)) {
                                                    if ($proposals_info->discount_type == 'before_tax') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>><?php echo lang('before_tax'); ?></option>
                                            <option value="after_tax" <?php
                                                if (isset($proposals_info)) {
                                                    if ($proposals_info->discount_type == 'after_tax') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>><?php echo lang('after_tax'); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('status') ?> </label>
                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                        <select name="status" class="select_box form-control" data-width="100%">
                                            <option
                                                value="draft"><?= lang('draft') ?></option>
                                            <option
                                                value="sent"><?= lang('sent') ?></option>
                                            <option
                                                value="open"><?= lang('open') ?></option>
                                            <option
                                                value="revised"><?= lang('revised') ?></option>
                                            <option
                                                value="declined"><?= lang('declined') ?></option>
                                            <option
                                                value="accepted"><?= lang('accepted') ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3" id="border-none">
                                    <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('permission') ?> <span class="required">*</span></label>
                                    <div class="col-xl-7 col-md-7 col-sm-7">
                                        <div class="form-check form-radio-outline form-radio-primary mt mr">
                                            <input id="everyone" <?php
                                        if (!empty($proposals_info->permission) && $proposals_info->permission == 'all') {
                                            echo 'checked';
                                        } elseif (empty($proposals_info)) {
                                            echo 'checked';
                                        }
                                        ?>  type="radio" name="permission" value="everyone" class="form-check-input">
                                            <label class="form-check-label" for="everyone"><?= lang('everyone') ?>
                                                <i title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                            </label>
                                        </div>
                                        <div class="form-check form-radio-outline form-radio-primary mt mr">
                                            <input id="custom_permission" <?php
                            if (!empty($proposals_info->permission) && $proposals_info->permission != 'all') {
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
                                            ?>" id="permission_user_1">
                                    <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('select') . ' ' . lang('users') ?>
                                        <span class="required">*</span></label>
                                    <div class="col-lg-5 col-md-5 col-sm-5">
                                                <?php if (!empty($permission_user)) { ?>
                                            <input type="text" name="search_assigned_user" value="" placeholder="<?= lang('search_by') . ' ' . lang('username'); ?>" class="form-control mb-4" id="search_assigned_user" autocomplete="off">
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
                                                    ?> value="<?= $v_user->user_id ?>" name="assigned_to[]" class="form-check-input" id="user_<?= $v_user->user_id ?>" data-name="<?= $v_user->username; ?>">
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
                                                                ?> " id="action_1<?= $v_user->user_id ?>">
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
                                                <?php }
                                            ?>
                                            </div>
                                        <?php }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 col-xs-12  pv">
                                <div class="row mb-3">
                                    <label class="col-xl-4 col-form-label col-md-4 col-sm-4"><?= lang('assigned') . ' ' . lang('users') ?></label>
                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                        <select class="form-control select_box" required style="width: 100%"
                                                name="user_id">
                                            <option
                                                value=""><?= lang('select') . ' ' . lang('assigned') . ' ' . lang('users') ?></option>
                                            <?php
                                            $all_user = get_staff_details();

                                            if (!empty($all_user)) {
                                                foreach ($all_user as $v_user) {
                                                    ?>
                                                    <option value="<?= $v_user->user_id ?>"
                                                    <?php
                                                    if (!empty($proposals_info->user_id)) {
                                                        echo $proposals_info->user_id == $v_user->user_id ? 'selected' : null;
                                                    } else {
                                                        echo $this->session->userdata('user_id') == $v_user->user_id ? 'selected' : null;
                                                    }
                                                    ?>
                                                            > <?= $v_user->fullname ?></option>
<?php
// }
}
}
?>
                                        </select>

                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label
                                        class="col-xl-4 col-form-label col-md-4 col-sm-4"><?= lang('proposal_date') ?></label>
                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                        <div class="input-group">
                                            <input type="text" name="proposal_date"
                                                   class="form-control datepicker"
                                                   value="<?php
                                               if (!empty($proposals_info->proposal_date)) {
                                                   echo date('d-m-Y H-i', strtotime($proposals_info->proposal_date));
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
                                    <label class="col-xl-4 col-form-label col-md-4 col-sm-4"><?= lang('expire_date') ?></label>
                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                        <div class="input-group">
                                            <input type="text" name="due_date"
                                                   class="form-control datepicker"
                                                   value="<?php
                                               if (!empty($proposals_info->due_date)) {
                                                   echo date('d-m-Y H-i', strtotime($proposals_info->due_date));
                                               } else {
                                                    echo date('d-m-Y H-i', strtotime("+30 Days"));
                                               }
?>"
                                                   data-date-format="<?= config_item('date_picker_format'); ?>">
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if (!empty($proposals_info)) {
                                    $proposals_id = $proposals_info->proposals_id;
                                } else {
                                    $proposals_id = null;
                                }
                                ?>
<?= custom_form_Fields(11, $proposals_id); ?>
                            </div>
                        </div>

                        <?php
                        if (!empty($proposals_info)) {
                            if ($proposals_info->module == 'client') {
                                $client_info = $this->proposal_model->check_by(array('client_id' => $proposals_info->module_id), 'tbl_client');
                                $currency = $this->proposal_model->client_currency_sambol($proposals_info->module_id);
                                $client_lang = $client_info->language;
                            } else {
                                $client_info = $this->proposal_model->check_by(array('leads_id' => $proposals_info->module_id), 'tbl_leads');
                                if (!empty($client_info)) {
                                    $client_info->name = $client_info->contact_name;
                                    $client_info->zipcode = null;
                                }
                                $client_lang = 'english';
                                $currency = $this->proposal_model->check_by(array('code' => $proposals_info->currency), 'tbl_currencies');
                            }
                        } else {
                            $client_lang = 'english';
                            $currency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                        }
                        unset($this->lang->is_loaded[5]);
                        $language_info = $this->lang->load('sales_lang', $client_lang, TRUE, FALSE, '', TRUE);
                        ?>

                        <style type="text/css">
                            .dropdown-menu > li > a {
                                white-space: normal;
                            }

                            .dragger {
                                background: url(../skote_assets/images/dragger.png) 10px 32px no-repeat;
                                cursor: pointer;
                            }

<?php if (!empty($proposals_info)) { ?>
                                .dragger {
                                    background: url(../../../../skote_assets/images/dragger.png) 10px 32px no-repeat;
                                    cursor: pointer;
                                }

<?php } ?>
                            .input-transparent {
                                box-shadow: none;
                                outline: 0;
                                border: 0 !important;
                                background: 0 0;
                                padding: 3px;
                            }
                        </style>
                       
                        
                        <div class="row">
                            <script src="https://editor.unlayer.com/embed.js"></script>
                            <style>

                                /* The grid: Four equal columns that floats next to each other */
                                .column {
                                    float: left;
                                    width: 25%;
                                    padding: 10px;
                                }

                                /* Style the images inside the grid */
                                .column img {
                                    opacity: 1; 
                                    cursor: pointer; 
                                }

                                .column img:hover {
                                    opacity: 0.7;
                                }
                                /* Closable button inside the expanded image */
                                .closebtn {
                                    position: absolute;
                                    top: 10px;
                                    right: 15px;
                                    color: white;
                                    font-size: 35px;
                                    cursor: pointer;
                                }
                                .loader {
                                    border: 16px solid #f3f3f3;
                                    border-radius: 50%;
                                    border-top: 16px solid #3498db;
                                    width: 120px;
                                    height: 120px;
                                    -webkit-animation: spin 2s linear infinite; /* Safari */
                                    animation: spin 2s linear infinite;
                                  }

                                  /* Safari */
                                  @-webkit-keyframes spin {
                                    0% { -webkit-transform: rotate(0deg); }
                                    100% { -webkit-transform: rotate(360deg); }
                                  }

                                  @keyframes spin {
                                    0% { transform: rotate(0deg); }
                                    100% { transform: rotate(360deg); }
                                  }
                                  .loadersmall {
                                      border: 5px solid #f3f3f3;
                                        border-top-color: rgb(243, 243, 243);
                                        border-top-style: solid;
                                        border-top-width: 5px;
                                    -webkit-animation: spin 1s linear infinite;
                                    animation: spin 1s linear infinite;
                                    border-top: 5px solid #555;
                                    border-radius: 50%;
                                    width: 50px;
                                    height: 50px;
                                  }
                            </style>
                            <div id="templates">
                                <div class="row">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4"><h4 class="mb-sm-0 font-size-18">Proposal Template Builder</h4></div>
                                        <div class="col-md-4">
                                            <select  class="select_box form-control" data-width="100%" name="templatecat" id="templatecat">
                                                <option value="" selected>Category to load template</option>
                                                <?php 
                                                    foreach ($unlayer_template_cat as $key => $value) { ?>
                                                        <option data-templatecount="<?php echo $value['templatecount'];?>" data-templateids="<?php echo $value['templateid'];?>" value="<?php echo $key; ?>"><?php echo $key; ?></option>
                                                   <?php }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="loadersmall" style="display:none"></div>
                                      <div id="templist"><p class='p-5 text-center no-template'> No template seleted</p></div>
                                </div>
                                <div class="loadersmall" style="display:none"></div>
                                <div id="templist">
                                        
                                </div>
                           
                            
                            <div id="tagss" >
                                <div style="text-align:center">
                                    <h2 id="templatename"></h2>
                                    
                                </div>
                                <div style="color:black" onclick="hideshowelem(this)" class="closebtn">&times;</div><br><br>
                                <div style="height:550px !important" id="editor"></div>

                            </div>
                        </div>
                        </div>
                        <br>

                        <div id="removed-items"></div>
                        <div class="modal-footer">
                            <input type="submit" value="<?= lang('update') ?>" name="update" class="btn btn-primary btn-block">
                            <button type="button" id="ProposalReset" class="btn btn-danger pull-left"><?= lang('reset') ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php } ?> 
    </div>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    function slideToggle($id) {
        $('#quick_state').attr('data-original-title', '<?= lang('view_quick_state') ?>');
        $($id).slideToggle("slow");
    }

    $(document).ready(function () {
        init_items_sortable();
    });
</script>
<?php
if (isset($proposals_info)) {
    $add_items = $this->proposal_model->ordered_items_by_id($proposals_info->proposals_id, true);
    if (!empty($add_items)) {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                store('ProposalItems', JSON.stringify(<?= $add_items; ?>));
            });
        </script>
    <?php }
}
?>
<script type="text/javascript">
    var useriid = "<?php echo $this->session->userdata('user_id'); ?>";
    var proposalid = "<?php echo $proposals_id; ?>";
    var templateunlayer = "<?php echo $unlayertemplate_id; ?>";
    var unlayertemplatejson = '';
<?php if ($unlayertemplatejson) { ?>
        unlayertemplatejson = <?php echo ($unlayertemplatejson); ?>;
<?php } ?>

    var ProposalItems = {};
    if (localStorage.getItem('remove_proposal')) {
        if (localStorage.getItem('ProposalItems')) {
            localStorage.removeItem('ProposalItems');
        }
        localStorage.removeItem('remove_proposal');
    }

    //Load tempate selected to modify
    function loadTemplates(templateid, name) {
        $('#loader-wrapper').show();
        $('#unlayertemplateid').val(templateid);
        $('#tagss').show();
        $('#templates').hide();
        $('#templatename').text(name);
        //unlayer.loadDesign({});
        unlayer.loadTemplate(templateid);
        setTimeout(function () {
            unlayer.exportHtml(function (data) {
                var json = data.design; // design json
                var html = (data.html);
                $('#unlayertemplatejson').text((JSON.stringify(json)));
                $('#unlayertemplatehtml').text(html);
                $('#loader-wrapper').hide();
                //Save the json, or html here
            });
        }, 2000);
    }

    function hideshowelem(obj) {
        obj.parentElement.style.display = 'none';
        $('#templates').show();
    }
    $(document).ready(function () {
         $('body').addClass('sidebar-enable vertical-collpsed');
        if (proposalid) {
            if (unlayertemplatejson) {
                unlayer.init({
                    id: 'editor',
                    projectId: 50579,
                    //templateId: templateunlayer,
                    displayMode: 'web',
                    user: {id: useriid}

                });
                $('#templates').hide();
                unlayer.loadDesign(unlayertemplatejson);

                setTimeout(function () {
                    unlayer.exportHtml(function (data) {
                        var json = data.design; // design json
                        var html = (data.html);
                        console.log('edithtml', html);
                        $('#unlayertemplatejson').text((JSON.stringify(json)));
                        $('#unlayertemplatehtml').text(html);
                        $('#unlayertemplatehtml').text(html);
                        //Save the json, or html here
                    });
                }, 2000);

            } else {
                if (templateunlayer) {
                    unlayer.init({
                        id: 'editor',
                        projectId: 50579,
                        templateId: templateunlayer,
                        displayMode: 'web',
                        user: {id: useriid}

                    });
                    $('#templates').hide();
                    setTimeout(function () {
                        unlayer.exportHtml(function (data) {
                            var json = data.design; // design json
                            var html = (data.html);
                            console.log('edithtml', html);
                            $('#unlayertemplatejson').text((JSON.stringify(json)));
                            $('#unlayertemplatehtml').text(html);
                            //Save the json, or html here
                        });
                    }, 2000);
                } else {
                    unlayer.init({
                        id: 'editor',
                        projectId: 50579,
                        //templateId: "104794",
                        displayMode: 'web',
                        user: {id: useriid}
                    });
                    $('#tagss').hide();
                }
            }
        } else {
            unlayer.init({
                id: 'editor',
                projectId: 50579,
                //templateId: "104794",
                displayMode: 'web',
                user: {id: useriid}
            });
            $('#tagss').hide();
        }
        
        $('#templatecat').on('change', function(){
            
            $('#templist').html('');
            var myArray = [];
            if(this.value != '' && this.value != 'undefined') {
                $('#loader-wrapper').show();
                var tempid = $(this).find(':selected').data('templateids');
                var tempCount = $(this).find(':selected').data('templatecount');
                if(tempCount > 1) {
                    myArray = tempid.split(" ");
                } else {
                    myArray = [tempid];
                }
                for (let i = 0; i < myArray.length; i++) {
                    $.ajax({
                        url: "https://api.unlayer.com/v1/templates/"+myArray[i],
                        type: 'GET',
                        dataType: 'json',
                        async: true,
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Basic ZVY3MDRuelFaZHFZUVVoMkdyRUU1UVJKVTFMWjR1VGZ6Q0Z6VndSNnFHaUZiM1pYVGZ4R2NCQVQ5S01LWVJ5TDo='
                        },
                        contentType: 'application/json; charset=utf-8',
                        success: function (result) {
                          if (result.success) {
                              var arrayItem = result.data;
                              //result.data.forEach(function (arrayItem) {
                                  //var x = arrayItem.prop1 + 2;
                                  if (arrayItem.displayMode == 'web') {
                                      var templateid = arrayItem.id;
                                      //var imageurl = exportImages(arrayItem.design);
                                      var imageurl = 'https://api.unlayer.com/v1/editor/50579/templates/'+templateid+'/thumbnail';
                                      var name = arrayItem.name;
                                      var htmla = '<div class="column"><a class="template" data-name="" onclick="loadTemplates(\'' + templateid + '\',\'' + name + '\')"><img class="img-thumbnail" style="width:100%" alt="' + name + '" src="' + imageurl + '" /></a><h5>'+name+'</h5></div>';
                                      $('#templist').append(htmla);
                                      $(".no-template").remove();
                                       $('#loader-wrapper').hide();
                                  }
                              //});
                          } else {
                              // $('#loader-wrapper').hide();
                               
                          }
                          // CallBack(result);
                           // $('#loader-wrapper').hide();

                        },
                        error: function (error) {
                            $('#loader-wrapper').hide();
                             if(!$('#templist .template').length) { $('#templist').html("<p class='p-5 text-center no-template'> No template found</p>"); 
                      }
                             
                        }
                    });
                }
                // $('#loader-wrapper').hide();
            }
        });
        
        unlayer.addEventListener('design:updated', function (updates) {
            unlayer.exportHtml(function (data) {
                var json = data.design; // design json
                var html = (data.html);
                //console.log(html);
                $('#unlayertemplatejson').text((JSON.stringify(json)));
                $('#unlayertemplatehtml').text(html);
                //Save the json, or html here
            })
        });



        //Export image functionality
        function exportImages(data) {
            var imageurl = 'https://api.unlayer.com/v1/editor/50579/templates/106261/thumbnail?t=1638921600000';
            //var imageurl = 'https://allbizhub.s3.ap-southeast-2.amazonaws.com/_generated/image/1639125472758-YDnUUp0ShxnpZlUx.png';
            $.ajax({
                url: "https://api.unlayer.com/v1/export/image",
                type: 'POST',
                dataType: 'json',
                //async: false,
                data: {
                    "displayMode": "web",
                    "design": data,
                },
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Basic ZVY3MDRuelFaZHFZUVVoMkdyRUU1UVJKVTFMWjR1VGZ6Q0Z6VndSNnFHaUZiM1pYVGZ4R2NCQVQ5S01LWVJ5TDo=',
                    'Content-Type': 'application/json',
                },
                contentType: 'application/json; charset=utf-8',
                success: function (result) {
                    if (result.success) {
                        imageurl = result.data.url;
                    }
                }
            });
            return imageurl;
        }

    });

    $(document).ready(function () {
<?php
$editProposal = $this->uri->segment(5);
$edit_proposal = $this->session->userdata('edit_proposal');
if (empty($editProposal) && !empty($edit_proposal)) {
    ?>
            if (get('ProposalItems')) {
                remove('ProposalItems');
            }
    <?php
    $this->session->unset_userdata('edit_proposal');
}
?>
    });
</script>
<?php include_once 'skote_assets/js/proposal.php'; ?>
