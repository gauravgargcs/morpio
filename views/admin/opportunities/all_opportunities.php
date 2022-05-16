<?= message_box('success'); ?>
<?= message_box('error'); ?>
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
<?php
$created = can_action('56', 'created');
$edited = can_action('56', 'edited');
$deleted = can_action('56', 'deleted');
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                 <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 1 ? 'active' : ''; ?>" href="<?= base_url().'admin/opportunities' ?>"><?= lang('all_opportunities') ?></a>
                    </li>
                    <?php if (!empty($created) || !empty($edited)) { ?>
                    <li class="nav-item waves-light"><a class="nav-link <?= $active == 2 ? 'active' : ''; ?>" href="#create" data-bs-toggle="tab">
                        <?php echo lang('new_opportunities'); ?></a>
                    </li>
                    <?php } ?>
                </ul>
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                        <h4 class="card-title mb-4"><?= lang('all_opportunities') ?></h4>
                        <table class="table table-striped dt-responsive nowrap w-100" id="list_oppo_datatable">
                            <thead>
                                <tr>
                                    <?php super_admin_opt_th() ?>
                                    <th><?= lang('opportunity_name') ?></th>
                                    <th><?= lang('state') ?></th>
                                    <th><?= lang('stages') ?></th>
                                    <th><?= lang('expected_revenue') ?></th>
                                    <th><?= lang('next_action') ?></th>
                                    <th><?= lang('next_action_date') ?></th>
                                    <?php $show_custom_fields = custom_form_table(8, null);
                                    if (!empty($show_custom_fields)) {
                                        foreach ($show_custom_fields as $c_label => $v_fields) {
                                            if (!empty($c_label)) {
                                                ?>
                                                <th><?= $c_label ?> </th>
                                            <?php }
                                        }
                                    }
                                    ?>
                                    <th class="col-options no-sort"><?= lang('action') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($all_opportunity)):foreach ($all_opportunity as $v_opportunity):
                                    $can_edit = $this->items_model->can_action('tbl_opportunities', 'edit', array('opportunities_id' => $v_opportunity->opportunities_id));
                                    $can_delete = $this->items_model->can_action('tbl_opportunities', 'delete', array('opportunities_id' => $v_opportunity->opportunities_id));
                                    $opportunities_state_info = $this->db->where('opportunities_state_reason_id', $v_opportunity->opportunities_state_reason_id)->get('tbl_opportunities_state_reason')->row();
                                    if ($opportunities_state_info->opportunities_state == 'open') {
                                        $label = 'primary';
                                    } elseif ($opportunities_state_info->opportunities_state == 'won') {
                                        $label = 'success';
                                    } elseif ($opportunities_state_info->opportunities_state == 'suspended') {
                                        $label = 'info';
                                    } else {
                                        $label = 'danger';
                                    }
                                    $currency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                                    ?>
                                    <tr id="table-opportunities-<?= $v_opportunity->opportunities_id ?>">
                                        <?php super_admin_opt_td($v_opportunity->companies_id) ?>
                                        <td>
                                            <a class="text-info"
                                               href="<?= base_url() ?>admin/opportunities/opportunity_details/<?= $v_opportunity->opportunities_id ?>"><?= $v_opportunity->opportunity_name ?></a>
                                            <?php
                                            if (strtotime($v_opportunity->close_date) < time() AND $v_opportunity->probability < 100) { ?>
                                                <span class="label label-danger pull-right"><?= lang('overdue') ?></span>
                                            <?php } ?>

                                            <div class="progress progress-xs progress-striped active">
                                                <div
                                                    class="progress-bar progress-bar-<?php echo ($v_opportunity->probability >= 100) ? 'success' : 'primary'; ?>"
                                                    data-bs-toggle="tooltip"
                                                    data-original-title="<?= lang('probability') . ' ' . $v_opportunity->probability ?>%"
                                                    style="width: <?= $v_opportunity->probability ?>%"></div>
                                            </div>
                                        </td>
                                        <td><?= lang($v_opportunity->stages) ?></td>
                                        <td><span data-bs-toggle="tooltip" data-bs-placement="top"
                                                  title="<?= $opportunities_state_info->opportunities_state_reason ?>"
                                                  class="label label-<?= $label ?>"><?= lang($opportunities_state_info->opportunities_state) ?></span>
                                        </td>
                                        <td><?php
                                            if (!empty($v_opportunity->expected_revenue)) {
                                                echo display_money($v_opportunity->expected_revenue, $currency->symbol);
                                            }
                                            ?></td>
                                        <td><?= $v_opportunity->next_action ?></td>
                                        <td><?= display_datetime($v_opportunity->next_action_date) ?></td>
                                        <?php $show_custom_fields = custom_form_table(8, $v_opportunity->opportunities_id);
                                        if (!empty($show_custom_fields)) {
                                            foreach ($show_custom_fields as $c_label => $v_fields) {
                                                if (!empty($c_label)) {
                                                    ?>
                                                    <td><?= $v_fields ?> </td>
                                                <?php }
                                            }
                                        }
                                        ?>
                                        <td>
                                            <?= btn_view('admin/opportunities/opportunity_details/' . $v_opportunity->opportunities_id) ?>
                                            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                <?= btn_edit('admin/opportunities/index/' . $v_opportunity->opportunities_id) ?>
                                            <?php }
                                            if (!empty($can_delete) && !empty($deleted)) { ?>
                                                <?php echo ajax_anchor(base_url("admin/opportunities/delete_opportunity/" . $v_opportunity->opportunities_id), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table-opportunities-" . $v_opportunity->opportunities_id)); ?>
                                            <?php } ?>
                                            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                <div class="btn-group">
                                                    <button class="btn btn-outline-success dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"><?= lang('change_state') ?><i class="mdi mdi-chevron-down"></i></button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <?php
                                                        $all_opportunities_state = get_result('tbl_opportunities_state_reason');
                                                        if (!empty($all_opportunities_state)) {
                                                            foreach ($all_opportunities_state as $v_opportunities_state) {
                                                                ?>
                                                        <a class="dropdown-item" href="<?= base_url() ?>admin/opportunities/change_state/<?= $v_opportunity->opportunities_id ?>/<?= $v_opportunities_state->opportunities_state_reason_id ?>"><?= lang($v_opportunities_state->opportunities_state) . ' (' . $v_opportunities_state->opportunities_state_reason . ')' ?></a>
                                                    
                                                        <?php } } ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php
                                endforeach;
                                endif;
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (!empty($created) || !empty($edited)) {
                        if (!empty($opportunity_info)) {
                            $opportunities_id = $opportunity_info->opportunities_id;
                            $companies_id = $opportunity_info->companies_id;
                        } else {
                            $opportunities_id = null;
                            $companies_id = null;
                        }
                        ?>
                    <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                        <?php echo form_open(base_url('admin/opportunities/saved_opportunity/' . $opportunities_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                        <div class="row">
                            <?php super_admin_form($companies_id, 2, 4) ?>
                            <div class="row mb-3">
                                <label class="col-lg-2 col-md-2 col-form-label "><?= lang('opportunity_name') ?> <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-4 col-md-4 ">
                                    <input type="text" class="form-control" value="<?php
                                    if (!empty($opportunity_info)) {
                                        echo $opportunity_info->opportunity_name;
                                    }
                                    ?>" name="opportunity_name" required="">
                                </div>
                                <label class="col-lg-2 col-md-2 col-form-label "><?= lang('stages') ?> </label>
                                <div class="col-lg-4 col-md-4 ">
                                    <select name="stages" class="form-control select_box" style="width: 100%;"
                                            required="">
                                        <option
                                            value="new" <?= (!empty($opportunity_info) && $opportunity_info->stages == 'new' ? 'selected' : '') ?>><?= lang('new') ?></option>
                                        <option
                                            value="qualification" <?= (!empty($opportunity_info) && $opportunity_info->stages == 'qualification' ? 'selected' : '') ?>><?= lang('qualification') ?></option>
                                        <option
                                            value="proposition" <?= (!empty($opportunity_info) && $opportunity_info->stages == 'proposition' ? 'selected' : '') ?>><?= lang('proposition') ?></option>
                                        <option
                                            value="won" <?= (!empty($opportunity_info) && $opportunity_info->stages == 'won' ? 'selected' : '') ?>><?= lang('won') ?></option>
                                        <option
                                            value="lost" <?= (!empty($opportunity_info) && $opportunity_info->stages == 'lost' ? 'selected' : '') ?>><?= lang('lost') ?></option>
                                        <option
                                            value="dead" <?= (!empty($opportunity_info) && $opportunity_info->stages == 'dead' ? 'selected' : '') ?>><?= lang('dead') ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-2 col-md-2 col-form-label "><?= lang('probability') ?> %</label>
                                <div class="col-lg-4 col-md-4 ">
                                    <input name="probability" data-ui-slider="" type="text"
                                           value="<?php if (!empty($opportunity_info->probability)) echo $opportunity_info->probability; ?>"
                                           data-slider-min="0" data-slider-max="100" data-slider-step="1"
                                           data-slider-value="<?php if (!empty($opportunity_info->probability)) echo $opportunity_info->probability; ?>"
                                           data-slider-orientation="horizontal" class="slider slider-horizontal"
                                           data-slider-id="red" id="range_01">
                                </div>
                                
                                <label class="col-lg-2 col-md-2 col-form-label "><?= lang('close_date') ?></label>
                                <?php
                                if (!empty($opportunity_info)) {
                                    $close_date = date('d-m-Y H-i', strtotime($opportunity_info->close_date));
                                    $next_action_date = date('d-m-Y H-i', strtotime($opportunity_info->next_action_date));
                                } else {
                                    $close_date = date('d-m-Y H-i');
                                    $next_action_date = date('d-m-Y H-i');
                                }
                                ?>
                                <div class="col-lg-4 col-md-4 ">
                                    <div class="input-group">
                                        <input class="form-control datepicker" type="text"
                                               value="<?= $close_date; ?>"
                                               name="close_date"
                                               data-date-format="<?= config_item('date_picker_format'); ?>">
                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3" id="border-none">
                                <label for="field-1" class="col-lg-2 col-md-2 col-form-label"><?= lang('current_state') ?> <span  class="required">*</span></label>
                                <div class="col-lg-4 col-md-4 ">
                                    <div class="input-group">
                                        <select name="opportunities_state_reason_id" style="width: 85%"
                                                class="select_box form-control"
                                                required="">
                                            <?php
                                            if (!empty($all_state)) {
                                                foreach ($all_state as $state => $opportunities_state) {
                                                    if (!empty($state)) {
                                                        ?>
                                                        <optgroup label="<?= lang($state) ?>">
                                                            <?php foreach ($opportunities_state as $v_state) { ?>
                                                                <option
                                                                    value="<?= $v_state->opportunities_state_reason_id ?>" <?php
                                                                if (!empty($opportunity_info->opportunities_state_reason_id)) {
                                                                    echo $v_state->opportunities_state_reason_id == $opportunity_info->opportunities_state_reason_id ? 'selected' : '';
                                                                }
                                                                ?>><?= $v_state->opportunities_state_reason ?></option>
                                                            <?php } ?>
                                                        </optgroup>
                                                        <?php
                                                    }
                                                }
                                            }
                                            $created = can_action('129', 'created');
                                            ?>
                                        </select>
                                        <?php if (!empty($created)) { ?>
                                        <span class="input-group-text" title="<?= lang('new') . ' ' . lang('opportunities_state_reason') ?>"  data-bs-toggle="tooltip" data-bs-placement="top">
                                            <a data-bs-toggle="modal" data-bs-target="#myModal"  href="<?= base_url() ?>admin/opportunities/opportunities_state_reason"><i  class="fa fa-plus"></i></a>
                                        </span>
                                        <?php } ?>
                                    </div>
                                </div>
                        
                                <label class="col-lg-2 col-md-2 col-form-label "><?= lang('expected_revenue') ?></label>
                                <div class="col-lg-4 col-md-4 ">

                                    <input type="text" data-parsley-type="number" min="0" class="form-control"
                                           value="<?php
                                           if (!empty($opportunity_info)) {
                                               echo $opportunity_info->expected_revenue;
                                           }
                                           ?>" name="expected_revenue">
                                </div>
                                

                            </div>
                            <!-- End discount Fields -->
                            <div class="row mb-3 terms">

                                <label class="col-lg-2 col-md-2 col-form-label "><?= lang('next_action') ?> </label>
                                <div class="col-lg-4 col-md-4 ">
                                    <input type="text" class="form-control" value="<?php
                                    if (!empty($opportunity_info)) {
                                        echo $opportunity_info->next_action;
                                    }
                                    ?>" name="next_action">
                                </div>
                                <label class="col-lg-2 col-md-2 col-form-label "><?= lang('next_action_date') ?></label>
                                <div class="col-lg-4 col-md-4 ">
                                    <div class="input-group">
                                        <input class="form-control datepicker" type="text"
                                               value="<?= $next_action_date; ?>"
                                               name="next_action_date"
                                               data-date-format="<?= config_item('date_picker_format'); ?>">
                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div>


                            </div>

                            <div class="row mb-3">
                                <label class="col-lg-2 col-md-2 col-form-label "><?= lang('new_link') ?></label>
                                <div class="col-lg-4 col-md-4 ">
                                    <input type="text" class="form-control" value="<?php
                                    if (!empty($opportunity_info)) {
                                        echo $opportunity_info->new_link;
                                    }
                                    ?>" name="new_link"/>
                                </div>

                            </div>

                            <?php
                            if (!empty($opportunity_info)) {
                                $opportunities_id = $opportunity_info->opportunities_id;
                            } else {
                                $opportunities_id = null;
                            }
                            ?>
                            <?= custom_form_Fields(8, $opportunities_id, true); ?>
                            <div class="row mb-3">
                                <label class="col-lg-2 col-md-2 col-form-label"><?= lang('short_note') ?> </label>
                                <div class="col-lg-10 col-md-10">
                                    <textarea name="notes" id="elm1" class="form-control textarea"><?php
                                        if (!empty($opportunity_info)) {
                                            echo $opportunity_info->notes;
                                        }
                                        ?></textarea>
                                </div>
                            </div>
                            <div class="row mb-3" id="border-none">
                                <label for="field-1" class="col-lg-2 col-md-2 col-form-label "><?= lang('who_responsible') ?> <span
                                        class="required">*</span></label>
                                <div class="col-lg-7 col-md-7 ">
                                    <div class="form-check form-radio-outline form-radio-primary mb-3">
                                        <input id="everyone" <?php
                                            if (!empty($opportunity_info->permission) && $opportunity_info->permission == 'all') {
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
                                            if (!empty($opportunity_info->permission) && $opportunity_info->permission != 'all') {
                                                echo 'checked';
                                            } elseif (empty($opportunity_info)) {
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
                                if (!empty($opportunity_info->permission) && $opportunity_info->permission != 'all') {
                                    echo 'show';
                                }
                                ?>" id="permission_user_1">
                                <label class="col-lg-2 col-md-2 col-form-label "><?= lang('select') . ' ' . lang('users') ?>
                                    <span class="required">*</span></label>
                                <div class="col-lg-5 col-md-5 ">
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
                                                    if (!empty($opportunity_info->permission) && $opportunity_info->permission != 'all') {
                                                        $get_permission = json_decode($opportunity_info->permission);
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
                                                if (!empty($opportunity_info->permission) && $opportunity_info->permission != 'all') {
                                                    $get_permission = json_decode($opportunity_info->permission);

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
                                                    <input <?php if (!empty($disable)) { echo 'disabled' . ' ' . 'checked'; } ?> id="<?= $v_user->user_id ?>"
                                                        <?php

                                                        if (!empty($opportunity_info->permission) && $opportunity_info->permission != 'all') {
                                                            $get_permission = json_decode($opportunity_info->permission);

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
                                                    <input <?php if (!empty($disable)) { echo 'disabled' . ' ' . 'checked'; } ?> id="<?= $v_user->user_id ?>"
                                                        <?php

                                                        if (!empty($opportunity_info->permission) && $opportunity_info->permission != 'all') {
                                                            $get_permission = json_decode($opportunity_info->permission);
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
                                            <?php
                                        } 
                                        ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-lg-2 col-md-2 col-form-label "></label>
                                <div class="col-lg-5 col-md-5">
                                    <button type="submit" class="btn btn-sm btn-primary"><?= lang('updates') ?></button>
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
<script type="text/javascript">
    $(document).ready(function () {
        $("#range_01").ionRangeSlider({
            skin: "square",
            min: 0,
            max: 100,
            hide_min_max: true,
        });
    });
</script>