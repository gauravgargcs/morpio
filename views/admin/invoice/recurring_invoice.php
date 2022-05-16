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
<?php
if (!empty($invoice_info)) {
    $invoices_id = $invoice_info->invoices_id;
    $companies_id = $invoice_info->companies_id;
} else {
    $invoices_id = null;
    $companies_id = null;
}
echo form_open(base_url('admin/invoice/save_invoice/' . $invoices_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form'));
?>
<div class="row <?php if (!isset($invoice_info) || (isset($invoice_info) && count($invoices_to_merge) == 0)) { echo ' hide'; } ?>" id="invoice_top_info">
    <div id="merge" class="col-lg-12">
        <?php if (isset($invoice_info)) {
            $this->load->view('admin/invoice/merge_invoice', array('invoices_to_merge' => $invoices_to_merge));
        } ?>
    </div>
</div>
<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action('51', 'created');
$edited = can_action('51', 'edited');
$deleted = can_action('51', 'deleted');
$saved_items = $this->invoice_model->get_all_items();
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light">
                        <a class="nav-link <?= $active == 1 ? 'active' : ''; ?>" href="#manage" data-bs-toggle="tab"><?= lang('recurring_invoice') ?></a>
                    </li>
                    <?php if (!empty($created) || !empty($edited)){ ?>
                    <li class="nav-item waves-light">
                        <a class="nav-link <?= $active == 2 ? 'active' : ''; ?>" href="#create" data-bs-toggle="tab"><?= lang('create_invoice') ?></a>
                    </li>
                    <?php } ?>
                </ul>
                <div class="tab-content bg-white">
                    <!-- ************** general *************-->
                    <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                        <h4 class="card-title mb-4 mt"><strong><?= lang('recurring_invoice') ?></strong></h4>
            
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap w-100" id="contentTable">
                                <thead>
                                        <tr>
                                        <?php super_admin_opt_th() ?>
                                        <th><?= lang('invoice') ?></th>
                                        <th class="col-date"><?= lang('due_date') ?></th>
                                        <th><?= lang('client_name') ?></th>
                                        <th class="col-currency"><?= lang('amount') ?></th>
                                        <th class="col-currency"><?= lang('due_amount') ?></th>
                                        <th><?= lang('status') ?></th>
                                        <?php if (!empty($edited) || !empty($deleted)) { ?>
                                            <th class="hidden-print"><?= lang('action') ?></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <?php /* ?><tbody>
                                    <?php
                                    if (!empty($all_invoices_info)) {
                                    foreach ($all_invoices_info as $v_invoices) {
                                        if ($v_invoices->recurring == 'Yes') {
                                            $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $v_invoices->invoices_id));
                                            $can_delete = $this->invoice_model->can_action('tbl_invoices', 'delete', array('invoices_id' => $v_invoices->invoices_id));
                                            if ($this->invoice_model->get_payment_status($v_invoices->invoices_id) == lang('fully_paid')) {
                                                $invoice_status = lang('fully_paid');
                                                $label = "success";
                                            } elseif ($v_invoices->emailed == 'Yes') {
                                                $invoice_status = lang('sent');
                                                $label = "info";
                                            } else {
                                                $invoice_status = lang('draft');
                                                $label = "default";
                                            }
                                            ?>
                                            <tr id="table_recurr_<?= $v_invoices->invoices_id ?>">
                                                <?php super_admin_opt_td($v_invoices->companies_id) ?>
                                                <td><a class="text-info"
                                                       href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $v_invoices->invoices_id ?>"><?= $v_invoices->reference_no ?></a>
                                                </td>
                                                <td><?= display_datetime($v_invoices->due_date) ?></td>
                                                <?php
                                                $client_info = $this->invoice_model->check_by(array('client_id' => $v_invoices->client_id), 'tbl_client');
                                                if (empty($client_info)) {
                                                    $client_name = '-';
                                                } else {
                                                    $client_name = $client_info->name;
                                                }
                                                ?>
                                                <td><?= $client_name; ?></td>
                                                <?php $currency = $this->invoice_model->client_currency_sambol($v_invoices->client_id); ?>
                                                <td><?= display_money($this->invoice_model->calculate_to('invoice_cost', $v_invoices->invoices_id), $currency->symbol) ?></td>
                                                <td><?= display_money($this->invoice_model->calculate_to('invoice_due', $v_invoices->invoices_id), $currency->symbol) ?></td>
                                                <td><span class="label label-<?= $label ?>"><?= $invoice_status ?></span>
                                                    <?php if ($v_invoices->recurring == 'Yes') { ?>
                                                        <span data-bs-toggle="tooltip" data-bs-placement="top"
                                                              title="<?= lang('recurring') ?>"
                                                              class="label label-primary"><i
                                                                    class="fa fa-retweet"></i></span>
                                                    <?php } ?>

                                                </td>
                                                <?php if (!empty($edited) || !empty($deleted)) { ?>
                                                <td>
                                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                        <?= btn_edit('admin/invoice/manage_invoice/create_invoice/' . $v_invoices->invoices_id) ?>
                                                    <?php }
                                                    if (!empty($can_delete) && !empty($deleted)) {
                                                        ?>
                                                        <?php echo ajax_anchor(base_url("admin/invoice/delete/delete_invoice/" . $v_invoices->invoices_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_recurr_" . $v_invoices->invoices_id)); ?>
                                                    <?php } ?>
                                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                    <div class="dropdown tbl-action mt">
                                                        <button class="btn btn-outline-success dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('change_status') ?><i class="mdi mdi-chevron-down"></i></button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                           
                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $v_invoices->invoices_id ?>"><?= lang('preview_invoice') ?></a>

                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/manage_invoice/payment/<?= $v_invoices->invoices_id ?>"><?= lang('pay_invoice') ?></a>

                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/manage_invoice/email_invoice/<?= $v_invoices->invoices_id ?>"><?= lang('email_invoice') ?></a>

                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/manage_invoice/send_reminder/<?= $v_invoices->invoices_id ?>"><?= lang('send_reminder') ?></a>

                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/manage_invoice/send_overdue/<?= $v_invoices->invoices_id ?>"><?= lang('send_invoice_overdue') ?></a>

                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_history/<?= $v_invoices->invoices_id ?>"><?= lang('invoice_history') ?></a>

                                                            <a class="dropdown-item" href="<?= base_url() ?>admin/invoice/manage_invoice/pdf_invoice/<?= $v_invoices->invoices_id ?>"><?= lang('pdf') ?></a>
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                                </td>
                                                <?php } ?>
                                            </tr>
                                    <?php }  }  }   ?>
                                </tbody><?php */ ?>
                            </table>
                        </div>
                    </div>
                    <?php if (!empty($created) || !empty($edited)) { ?>
                    <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                        <?php
                        if (!empty($invoice_info)) {
                            $client_info = $this->invoice_model->check_by(array('client_id' => $invoice_info->client_id), 'tbl_client');
                            if (!empty($client_info)) {
                                $client_lang = $client_info->language;
                                $currency = $this->invoice_model->client_currency_sambol($invoice_info->client_id);
                            } else {
                                $client_lang = 'english';
                                $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                            }
                        } else {
                            $client_lang = 'english';
                            $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                        }
                        unset($this->lang->is_loaded[5]);
                        $language_info = $this->lang->load('sales_lang', $client_lang, TRUE, FALSE, '', TRUE);
                        ?>

                        <div class="row invoice accounting-template">                    
                            <div class="card border">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6 br pv">
                                            <?php super_admin_form($companies_id, 5, 7) ?>
                                            <div class="row mb-3">
                                                <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('reference_no') ?><span class="text-danger">*</span></label>
                                                <div class="col-lg-7 col-md-7 col-sm-7">
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
                                            </div>
                                            <div class="row mb-3">
                                                <label
                                                        class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('recur_frequency') ?> </label>
                                                <div class="col-lg-7 col-md-7 col-sm-7">
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
                                                <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('start_date') ?></label>
                                                <div class="col-lg-7 col-md-7 col-sm-7">
                                                    <?php
                                                    if (!empty($invoice_info) && $invoice_info->recurring == 'Yes') {
                                                        $recur_start_date = date('d-m-Y H-i', strtotime($invoice_info->recur_start_date));
                                                        $recur_end_date = date('d-m-Y H-i', strtotime($invoice_info->recur_end_date));
                                                    } else {
                                                        $recur_start_date = date('d-m-Y H-i');
                                                        $recur_end_date = date('d-m-Y H-i');
                                                    }
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
                                                <div class="col-lg-7 col-md-7 col-sm-7">
                                                    <div class="input-group">
                                                        <input class="form-control datepicker" type="text"
                                                               value="<?= $recur_end_date; ?>"
                                                               name="recur_end_date"
                                                               data-date-format="<?= config_item('date_picker_format'); ?>">
                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="f_client_id">
                                                <div class="row mb-3">
                                                    <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('client') ?> <span
                                                                class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                                        <select class="form-control select_box" required
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
                                                                    } elseif (!empty($invoice_info->client_id)) {
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
                                            </div>

                                            <div class="row mb-3">
                                                <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('project') ?></label>
                                                <div class="col-lg-7 col-md-7 col-sm-7">
                                                    <select class="form-control" style="width: 100%"
                                                            name="project_id"
                                                            id="client_project">
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
                                                <label
                                                        class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('invoice_date') ?></label>
                                                <div class="col-lg-7 col-md-7 col-sm-7">
                                                    <div class="input-group">
                                                        <input type="text" name="invoice_date"  class="form-control datepicker"
                                                           value="<?php
                                                           if (!empty($invoice_info->invoice_date)) {
                                                               echo date('d-m-Y H-i', strtotime($invoice_info->invoice_date));
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
                                                <div class="col-lg-7 col-md-7 col-sm-7">
                                                    <div class="input-group">
                                                        <input type="text" name="due_date"
                                                               class="form-control datepicker"
                                                               value="<?php
                                                               if (!empty($invoice_info->due_date)) {
                                                                   echo date('d-m-Y H-i', strtotime($invoice_info->due_date));
                                                               } else {
                                                                   echo date('d-m-Y H-i');
                                                               }
                                                               ?>"
                                                               data-date-format="<?= config_item('date_picker_format'); ?>">
                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3" id="border-none">
                                                <label class="col-xl-5 col-form-label col-md-5 col-sm-5">
                                                    <?= lang('permission') ?> <span class="required">*</span>
                                                </label>
                                                <div class="col-xl-7 col-md-7 col-sm-7">
                                                    <div class="form-check form-radio-outline form-radio-primary mt mr">
                                                        <input id="everyone" <?php
                                                            if (!empty($invoice_info->permission) && $invoice_info->permission == 'all') {
                                                                echo 'checked';
                                                            }
                                                            ?> type="radio" name="permission" value="everyone" class="form-check-input">
                                                        <label class="form-check-label" for="everyone">
                                                            <?= lang('everyone') ?>
                                                            <i title="<?= lang('permission_for_all') ?>"
                                                               class="fa fa-question-circle" data-bs-toggle="tooltip"
                                                               data-bs-placement="top"></i></label>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-radio-outline form-radio-primary mt mr">
                                                        <input id="custom_permission" <?php
                                                            if (!empty($invoice_info->permission) && $invoice_info->permission != 'all') {
                                                                echo 'checked';
                                                            } elseif (empty($invoice_info)) {
                                                                echo 'checked';
                                                            }
                                                            ?> type="radio" name="permission" value="custom_permission" class="form-check-input">
                                                        <label class="form-check-label" for="custom_permission"><?= lang('custom_permission') ?>
                                                            <i title="<?= lang('permission_for_customization') ?>" class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 <?php if (!empty($invoice_info->permission) && $invoice_info->permission != 'all') { echo 'show'; } ?>" id="permission_user_1">
                                                <label class="col-xl-5 col-form-label col-md-5 col-sm-5"><?= lang('select') . ' ' . lang('users') ?>
                                                    <span class="required">*</span></label>
                                                <div class="col-lg-5 col-md-5 col-sm-5">
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
                                                                    if (!empty($invoice_info->permission) && $invoice_info->permission != 'all') {
                                                                        $get_permission = json_decode($invoice_info->permission);
                                                                        foreach ($get_permission as $user_id => $v_permission) {
                                                                            if ($user_id == $v_user->user_id) {
                                                                                echo 'checked';
                                                                            }
                                                                        }

                                                                    }
                                                                    ?>  value="<?= $v_user->user_id ?>" name="assigned_to[]" data-name="<?= $v_user->username;?>"  class="form-check-input" id="user_<?= $v_user->user_id ?>">
                                                            <label class="form-check-label" for="user_<?= $v_user->user_id ?>">
                                                                <?= $v_user->username . ' ' . $role ?>
                                                            </label>
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
                                                                    ?>>
                                                                <label class="form-check-label" for="edit_<?= $v_user->user_id ?>">
                                                                    <?= lang('can') . ' ' . lang('edit') ?>
                                                                </label>
                                                            </div>

                                                            <div class="form-check form-check mb-3 mr-5">
                                                                <input class="form-check-input" name="action_<?= $v_user->user_id ?>[]" type="checkbox" value="delete" id="delete_<?= $v_user->user_id ?>"
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
                                            <?php
                                            if (!empty($invoice_info)) {
                                                $invoices_id = $invoice_info->invoices_id;
                                            } else {
                                                $invoices_id = null;
                                            }
                                            ?>
                                            <?= custom_form_Fields(9, $invoices_id); ?>
                                        </div>
                                        <div class="col-lg-6 br pv">
                                            <div class="row mb-3">
                                                <label class="col-xl-4 col-form-label col-md-4 col-sm-4 "><?= lang('sales') . ' ' . lang('agent') ?></label>
                                                <div class="col-xl-7 col-md-7 col-sm-7">
                                                    <select class="form-control select_box" required style="width: 100%"  name="user_id">
                                                        <option value=""><?= lang('select') . ' ' . lang('sales') . ' ' . lang('agent') ?></option>
                                                        <?php
                                                        $all_user = get_staff_details();
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

                                            <div class="row mb-3">
                                                <label class="col-xl-4 col-form-label col-md-4 col-sm-4 "><?= lang('discount_type') ?></label>
                                                <div class="col-lg-7 col-md-7 col-sm-7">
                                                    <select name="discount_type" class="select_box form-control " data-width="100%">
                                                        <option value=""
                                                                selected><?php echo lang('no') . ' ' . lang('discount'); ?></option>
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
                                            <?php if (config_item('paypal_status') == 'active'): ?>
                                            <div class="row mb-3">
                                                <label class="col-xl-4 col-form-label col-md-4 col-sm-4"><?= lang('allow_paypal') ?></label>
                                                <div class="col-lg-7 col-md-7 col-sm-7">
                                                    <div class="form-check form-check mb-3 mr">
                                                        <input type="checkbox" value="Yes"
                                                                <?php if (!empty($invoice_info) && $invoice_info->allow_paypal == 'Yes') {
                                                                    echo 'checked';
                                                                } ?> name="allow_paypal"  class="form-check-input">
                                                        <label class="form-check-label" for="allow_paypal"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif ?>
                                            <?php if (config_item('stripe_status') == 'active'): ?>
                                            <div class="row mb-3">
                                                <label class="col-xl-4 col-form-label col-md-4 col-sm-4"><?= lang('allow_stripe') ?></label>
                                                <div class="col-lg-7 col-md-7 col-sm-7">
                                                    <div class="form-check form-check mb-3 mr">
                                                        <input type="checkbox" value="Yes"
                                                                <?php if (!empty($invoice_info) && $invoice_info->allow_stripe == 'Yes') {
                                                                    echo 'checked';
                                                                } ?> name="allow_stripe" class="form-check-input">
                                                        <label class="form-check-label"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (config_item('2checkout_status') == 'active'): ?>
                                            <div class="row mb-3">
                                                <label class="col-xl-4 col-form-label col-md-4 col-sm-4"><?= lang('allow_2checkout') ?></label>
                                                <div class="col-lg-7 col-md-7 col-sm-7">
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
                                                <label class="col-xl-4 col-form-label col-md-4 col-sm-4"><?= lang('allow_authorize.net') ?></label>
                                                <div class="col-lg-7 col-md-7 col-sm-7">
                                                    <div class="form-check form-check mb-3 mr">
                                                        <input type="checkbox" value="Yes"
                                                            <?php if (!empty($invoice_info) && $invoice_info->allow_authorize == 'Yes') {
                                                                echo 'checked';
                                                            } ?> name="allow_authorize" class="form-check-input">
                                                        <label class="form-check-label"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (config_item('ccavenue_status') == 'active'): ?>
                                            <div class="row mb-3">
                                                <label class="col-xl-4 col-form-label col-md-4 col-sm-4"><?= lang('allow_ccavenue') ?></label>
                                                <div class="col-lg-7 col-md-7 col-sm-7">
                                                    <div class="form-check form-check mb-3 mr">
                                                        <input type="checkbox" value="Yes"
                                                            <?php if (!empty($invoice_info) && $invoice_info->allow_ccavenue == 'Yes') {
                                                                echo 'checked';
                                                            } ?>
                                                               name="allow_ccavenue" class="form-check-input">
                                                        <label class="form-check-label"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (config_item('braintree_status') == 'active'): ?>
                                            <div class="row mb-3">
                                                <label class="col-xl-4 col-form-label col-md-4 col-sm-4"><?= lang('allow_braintree') ?></label>
                                                <div class="col-lg-7 col-md-7 col-sm-7">
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
                                                <label class="col-xl-4 col-form-label col-md-4 col-sm-4"><?= lang('allow_mollie') ?></label>
                                                <div class="col-lg-7 col-md-7 col-sm-7">
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
                                                <label class="col-xl-4 col-form-label col-md-4 col-sm-4"><?= lang('allow_payumoney') ?></label>
                                                <div class="col-lg-7 col-md-7 col-sm-7">
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
                                                <label class="col-xl-4 col-form-label col-md-4 col-sm-4"><?= lang('visible_to_client') ?>
                                                    <span class="required">*</span></label>
                                                <div class="col-xl-7 col-md-7 col-sm-7">
                                                    <input data-bs-toggle="toggle" name="client_visible" value="Yes" <?php
                                                    if (!empty($invoice_info->client_visible) && $invoice_info->client_visible == 'Yes') {
                                                        echo 'checked';
                                                    }
                                                    ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>" data-onstyle="success" data-offstyle="danger" type="checkbox">
                                                </div>
                                            </div>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                    <hr class="row"/>
                        
                                    <div class="row">
                                        <div class="col-lg-6 br pv">
                                            <div class="terms row mb-3">
                                                <label class="col-xl-2 col-form-label col-md-2 col-sm-2"><?= lang('notes') ?> </label>
                                                <div class="col-xl-9 col-md-9 col-sm-9">
                                                      <textarea name="notes" id="elm1" class="textarea_">
                                                        <?php
                                                        if (!empty($invoice_info)) {
                                                            echo $invoice_info->notes;
                                                        } else {
                                                            echo $this->config->item('default_terms');
                                                        }
                                                        ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12 br pv ">
                                            <div class="col-lg-12 col-md-12">
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
                                                        } else {
                                                            load_data();
                                                        }
                                                    });
                                                });
                                            </script>
                                            <div id="product_result" class="product_result row"></div>
                                        </div>
                                    </div>
                                    <hr class="row"/>

                                    <div class="row p-3">
                                        <div class="col-xl-4">
                                            <div class="row mb-3">
                                                <input type="text" placeholder="<?= lang('search_product_by_name_code'); ?>"  id="invoice_item" class="form-control">
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
                                                            <?php if (isset($invoice_info) && $invoice_info->show_quantity_as == 'qty') {
                                                                echo 'checked';
                                                            } else if (!isset($hours_quantity) && !isset($qty_hrs_quantity)) {
                                                                echo 'checked';
                                                            } ?> class="form-check-input">
                                                        <label class="form-check-label" for="<?php echo lang('qty'); ?>"><?php echo lang('qty'); ?></label>
                                                    </div>
                                                    <div class="form-check form-radio-outline form-radio-primary mt mr">
                                                        <input type="radio" value="hours" id="<?php echo lang('hours'); ?>"
                                                               name="show_quantity_as" <?php if (isset($invoice_info) && $invoice_info->show_quantity_as == 'hours' || isset($hours_quantity)) {
                                                            echo 'checked';
                                                        } ?> class="form-check-input">
                                                        <label class="form-check-label" for="<?php echo lang('hours'); ?>"><?php echo lang('hours'); ?></label>
                                                    </div>
                                                    <div class="form-check form-radio-outline form-radio-primary mt mr">
                                                        <input type="radio" value="qty_hours"
                                                               id="<?php echo lang('qty') . '/' . lang('hours'); ?>"
                                                               name="show_quantity_as"
                                                            <?php if (isset($invoice_info) && $invoice_info->show_quantity_as == 'qty_hours' || isset($qty_hrs_quantity)) {
                                                                echo 'checked';
                                                            } ?>  class="form-check-input">
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
                                                    <th class="col-lg-2 col-md-2 col-sm-2"><?= $language_info['hsn_code'] ?></th>
                                                <?php } ?>
                                                <?php
                                                $qty_heading = $language_info['qty'];
                                                if (isset($invoice_info) && $invoice_info->show_quantity_as == 'hours' || isset($hours_quantity)) {
                                                    $qty_heading = lang('hours');
                                                } else if (isset($invoice_info) && $invoice_info->show_quantity_as == 'qty_hours') {
                                                    $qty_heading = lang('qty') . '/' . lang('hours');
                                                }
                                                ?>
                                                <th class="qty col-lg-2 col-md-2 col-sm-2"><?php echo $qty_heading; ?></th>
                                                <th class="col-lg-2 col-md-2 col-sm-2"><?= $language_info['price'] ?></th>
                                                <th class="col-lg-2 col-md-2 col-sm-2"><?= $language_info['tax_rate'] ?> </th>
                                                <th class="col-lg-1 col-md-1 col-sm-1"><?= $language_info['total'] ?></th>
                                                <th class="col-lg-1 col-md-1 col-sm-1 hidden-print"><?= $language_info['action'] ?></th>
                                            </tr>
                                            </thead>
                                            <?php if (isset($invoice_info)) {
                                                echo form_hidden('merge_current_invoice', $invoice_info->invoices_id);
                                                echo form_hidden('isedit', $invoice_info->invoices_id);
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
                                                                if (isset($invoice_info)) {
                                                                    if ($invoice_info->discount_percent != 0) {
                                                                        $discount_percent = $invoice_info->discount_percent;
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
                                    </div>
                                    <div class="modal-footer">
                                        <input type="button" id="InvoiceReset" value="<?= lang('reset') ?>" name="update"
                                               class="btn btn-danger">
                                        <input type="submit" value="<?= lang('save_as_draft') ?>" name="save_as_draft"
                                               class="btn btn-primary">
                                        <input type="submit" value="<?= lang('update') ?>" name="update"
                                               class="btn btn-success">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
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
                        $('select[name="client_id"]').find('option').not(':first').remove();
                        $.each(data, function (key, value) {
                            $('select[name="client_id"]').append('<option value="' + value.client_id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('select[name="client_id"]').empty();
            }
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        init_items_sortable();
        $('#start_recurring').click(function () {
            $('#recurring').slideToggle("fast");
            $('#recurring').removeClass("hide");
        });
    });
</script>
<?php
if (isset($invoice_info)) {
    $add_items = $this->invoice_model->ordered_items_by_id($invoice_info->invoices_id, 'invoices', true);
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
    if (localStorage.getItem('remove_invoice')) {
        if (localStorage.getItem('InvoiceItems')) {
            localStorage.removeItem('InvoiceItems');
        }
        localStorage.removeItem('remove_invoice');
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        <?php
        $editInvoice = $this->uri->segment(5);
        $edit_invoice = $this->session->userdata('edit_invoice');
        if(empty($editInvoice) && !empty($edit_invoice)){
        ?>
        if (get('InvoiceItems')) {
            remove('InvoiceItems');
        }
        <?php
        $this->session->unset_userdata('edit_invoice');
        }
        ?>
    });
</script>
<?php include_once 'skote_assets/js/invoice.php'; ?>

<!-- Script -->
 <script type="text/javascript">
     $(document).ready(function(){
        $('#contentTable').DataTable({
          'processing': true,
          'serverSide': true,
          'serverMethod': 'post',
          'ajax': {
             'url':'<?=base_url()?>admin/datatable/recurring_invoice'
          },
          'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
            $(nRow).attr("id", "table_recurr_"+iDisplayIndex);
            return nRow;
          },
          'columns': [
		  <?php if (is_company_column_ag()) { ?>
             { data: 'companies_id' },
		  <?php } ?>
             { data: 'invoice' },
             { data: 'due_date' },
             { data: 'client_name' },
             { data: 'amount' },
             { data: 'due_amount' },
             { data: 'status' },
             { data: 'action' },
          ]
        });
     });
 </script>