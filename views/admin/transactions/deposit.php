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
$mdate = date('Y-m-d H:i');
$last_7_days = date('Y-m-d H:i', strtotime('today - 7 days'));
$all_goal_tracking = $this->transactions_model->get_permission('tbl_goal_tracking');

$all_goal = 0;
$bank_goal = 0;
$complete_achivement = 0;
if (!empty($all_goal_tracking)) {
    foreach ($all_goal_tracking as $v_goal_track) {
        $goal_achieve = $this->transactions_model->get_progress($v_goal_track, true);
        if ($v_goal_track->goal_type_id == 1) {
            if ($v_goal_track->end_date <= $mdate) { // check today is last date or not

                if ($v_goal_track->email_send == 'no') {// check mail are send or not
                    if ($v_goal_track->achievement <= $goal_achieve['achievement']) {
                        if ($v_goal_track->notify_goal_achive == 'on') {// check is notify is checked or not check
                            $this->transactions_model->send_goal_mail('goal_achieve', $v_goal_track);
                        }
                    } else {
                        if ($v_goal_track->notify_goal_not_achive == 'on') {// check is notify is checked or not check
                            $this->transactions_model->send_goal_mail('goal_not_achieve', $v_goal_track);
                        }
                    }
                }
            }
            $all_goal += $v_goal_track->achievement;
            $complete_achivement += $goal_achieve['achievement'];
        }
        if ($v_goal_track->goal_type_id == 2) {
            if ($v_goal_track->end_date <= $mdate) { // check today is last date or not

                if ($v_goal_track->email_send == 'no') {// check mail are send or not
                    if ($v_goal_track->achievement <= $goal_achieve['achievement']) {
                        if ($v_goal_track->notify_goal_achive == 'on') {// check is notify is checked or not check
                            $this->transactions_model->send_goal_mail('goal_achieve', $v_goal_track);
                        }
                    } else {
                        if ($v_goal_track->notify_goal_not_achive == 'on') {// check is notify is checked or not check
                            $this->transactions_model->send_goal_mail('goal_not_achieve', $v_goal_track);
                        }
                    }
                }
            }

            $bank_goal += $v_goal_track->achievement;
            $complete_achivement += $goal_achieve['achievement'];
        }

    }
}
// 30 days before

for ($iDay = 7; $iDay >= 0; $iDay--) {
    $date = date('Y-m-d', strtotime('today - ' . $iDay . 'days'));
    $this_7_days_deposit[$date] = $this->db->select_sum('amount')->where(array('type' => 'Income', 'date >=' => $date, 'date <=' => $date))->get('tbl_transactions')->result();
}

$this_7_days_all = get_result('tbl_goal_tracking', array('goal_type_id' => 1, 'start_date >=' => $last_7_days, 'end_date <=' => $mdate));

$this_7_days_bank = get_result('tbl_goal_tracking', array('goal_type_id' => 2, 'start_date >=' => $last_7_days, 'end_date <=' => $mdate));

if (!empty($this_7_days_all)) {
    $this_7_days_all = $this_7_days_all;
} else {
    $this_7_days_all = array();
}
if (!empty($this_7_days_bank)) {
    $this_7_days_bank = $this_7_days_bank;
} else {
    $this_7_days_bank = array();
}


$terget_achievement = array_merge($this_7_days_all, $this_7_days_bank);
$total_terget = 0;
if (!empty($terget_achievement)) {
    foreach ($terget_achievement as $v_terget) {
        $total_terget += $v_terget->achievement;
    }
}
$tolal_goal = $all_goal + $bank_goal;
$curency = $this->transactions_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');

if ($this->session->userdata('user_type') == 1) {
 $margin = 'margin-bottom:30px';
?>
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                            <p class="text-muted text-truncate mb-2"><?= lang('achievement') ?></p>
                            <h5 class="mb-0"><?= display_money($tolal_goal, $curency->symbol) ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                            <p class="text-muted text-truncate mb-2"><?= lang('last_weeks') . ' ' . lang('created') ?></p>
                            <h5 class="mb-0"><?= display_money($total_terget, $curency->symbol) ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                            <p class="text-muted text-truncate mb-2"><?= lang('completed') . ' ' . lang('achievements') ?></p>
                            <h5 class="mb-0"><?= display_money($complete_achivement, $curency->symbol) ?></h5>
                    </div>
                </div>
            </div>


            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                        <div class="col-xs-6 pt">
                            <div id="sparkline2">
                            </div>
                            <p class="m0">
                                <small>
                                   <?php
                                    if (!empty($this_7_days_deposit)) {
                                        foreach ($this_7_days_deposit as $date => $v_last_deposit) {
                                            echo date('d', strtotime($date)) . ' ';
                                        }
                                    }
                                    ?>
                                </small>
                            </p>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                            <p class="text-muted text-truncate mb-2"><?= lang('pending') . ' ' . lang('achievements') ?></p>
                            <h5 class="mb-0"><?php
                                if ($tolal_goal < $complete_achivement) {
                                    $pending_goal = 0;
                                } else {
                                    $pending_goal = $tolal_goal - $complete_achivement;
                                } ?>
                                <?= display_money($pending_goal, $curency->symbol) ?>        
                            </h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="card">
                    <div class="card-body">
                        <?php
                        if (!empty($tolal_goal)) {
                            if ($tolal_goal <= $complete_achivement) {
                                $total_progress = 100;
                            } else {
                                $progress = ($complete_achivement / $tolal_goal) * 100;
                                $total_progress = round($progress);
                            }
                        } else {
                            $total_progress = 0;
                        }
                        ?>
                        <div class="inline ">
                            <div class="easypiechart text-success"
                                 data-percent="<?= $total_progress ?>"
                                 data-line-width="5" data-track-Color="#f0f0f0"
                                 data-bar-color="#<?php
                                 if ($total_progress == 100) {
                                     echo '8ec165';
                                 } elseif ($total_progress >= 40 && $total_progress <= 50) {
                                     echo '5d9cec';
                                 } elseif ($total_progress >= 51 && $total_progress <= 99) {
                                     echo '7266ba';
                                 } else {
                                     echo 'fb6b5b';
                                 }
                                 ?>" data-rotate="270" data-scale-Color="false"
                                 data-size="50"
                                 data-animate="2000">
                                                            <span class="small "><?= $total_progress ?>
                                                                %</span>
                                <span class="easypie-text"><strong><?= lang('done') ?></strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }
$created = can_action('30', 'created');
$edited = can_action('30', 'edited');
$deleted = can_action('30', 'deleted');
$income_category = get_result('tbl_income_category');
$id = $this->uri->segment(5);
if (!empty($deposit_info)) {
    $deposit_id = $deposit_info->transactions_id;
    $companies_id = $deposit_info->companies_id;
} else {
    $deposit_id = null;
    $companies_id = null;
}
?>
<div class="row mb-3">
    <div class="mb-lg pull-left">
        <div class="float-end">
            <?php if (!empty($created) || !empty($edited)) { ?>
            <a class="btn btn-xs btn-danger" href="<?= base_url() ?>admin/transactions/import/Income"><?= lang('import') . ' ' . lang('deposit') ?></a>
            <?php } ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body bordered">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs bg-light rounded" role="tablist">
                    <li class="nav-item waves-light">
                        <a class="nav-link <?= $active == 1 ? 'active' : ''; ?>" href="<?=base_url('admin/transactions/deposit')?>"><?= lang('all_deposit') ?></a>
                    </li>
                    <?php if (!empty($created) || !empty($edited)){ ?>
                    <li class="nav-item waves-light">
                        <a class="nav-link <?= $active == 2 ? 'active' : ''; ?>" href="#create" data-bs-toggle="tab"><?php if($deposit_id){ echo lang('edit').' '.lang('deposit'); }else{ echo lang('new_deposit'); } ?></a>
                    </li>
                    <?php } ?>
                </ul>
                <div class="tab-content p-3 text-muted">
                    <!-- ************** general *************-->
                    <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                        <div class="btn-group pull-right btn-with-tooltip-group _filter_data dropdown tbl-action" data-bs-toggle="tooltip" data-bs-title="<?php echo lang('filter_by'); ?>">
                            <button class="btn btn-sm btn-success dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-filter" aria-hidden="true"></i></button>
                                
                            <div class="dropdown-menu dropdown-menu-end" style="" aria-labelledby="dropdownMenuButton">
                                <a class="<?php if (empty($search_by)) { echo 'active'; } ?> dropdown-item"  href="<?= base_url() ?>admin/transactions/deposit"><?php echo lang('all'); ?></a>
                                
                                <div class="dropdown-divider"></div>
                                <?php if (count($income_category) > 0) { ?>
                                        <div class="dropdown-submenu dropdown-menu-left"
                                            style="<?php if (!empty($search_by) && $search_by == 'category') {
                                                echo 'display:block';
                                            } ?>">
                                            <?php foreach ($income_category as $v_category) { ?>
                                                <a class="<?php if (!empty($id)) { if ($search_by == 'category') { if ($id == $v_category->income_category_id) { echo 'active';  } } } ?> dropdown-item" href="<?= base_url() ?>admin/transactions/deposit/category/<?php echo $v_category->income_category_id; ?>"><?php echo $v_category->income_category; ?></a>
                                            <?php } ?>
                                        </div>
                                    <div class="clearfix"></div>
                                    <div class="dropdown-divider"></div>
                                <?php } ?>
                            </div>
                        </div>
                        <h4 class="card-title mb-4"><?= lang('all_deposit') ?></h4>
       
                        <!-- <div class="table-responsive"> -->
                            <table class="table table-striped dt-responsive nowrap w-100" id="contentTable">
                                <thead>
                                <tr>
                                    <?php super_admin_opt_th() ?>
                                    <th class="col-sm-1"><?= lang('name') . '/' . lang('title') ?></th>
                                    <th><?= lang('date') ?></th>
                                    <th><?= lang('account') ?></th>
                                    <th><?= lang('paid_by') ?></th>
                                    <th><?= lang('amount') ?></th>
                                    <th><?= lang('balance') ?></th>
                                    <?php $show_custom_fields = custom_form_table(1, null);
                                    if (!empty($show_custom_fields)) {
                                        foreach ($show_custom_fields as $c_label => $v_fields) {
                                            if (!empty($c_label)) {
                                                ?>
                                                <th><?= $c_label ?> </th>
                                            <?php }
                                        }
                                    }
                                    ?>
                                    <th><?= lang('attachment') ?></th>
                                    <?php if (!empty($edited) || !empty($deleted)) { ?>
                                        <th><?= lang('action') ?></th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <?php /* ?><tbody>
                                <?php
                                $curency = $this->transactions_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                                $total_amount = 0;
                                $total_credit = 0;
                                $total_balance = 0;
                                if (!empty($all_deposit_info)):
                                    foreach ($all_deposit_info as $v_deposit) :
                                        if ($v_deposit->type == 'Income'):
                                            $can_edit = $this->transactions_model->can_action('tbl_transactions', 'edit', array('transactions_id' => $v_deposit->transactions_id));
                                            $can_delete = $this->transactions_model->can_action('tbl_transactions', 'delete', array('transactions_id' => $v_deposit->transactions_id));

                                            $account_info = $this->transactions_model->check_by(array('account_id' => $v_deposit->account_id), 'tbl_accounts');
                                            $client_info = $this->transactions_model->check_by(array('client_id' => $v_deposit->paid_by), 'tbl_client');
                                            $category_info = $this->transactions_model->check_by(array('income_category_id' => $v_deposit->category_id), 'tbl_income_category');
                                            if (!empty($client_info)) {
                                                $client_name = $client_info->name;
                                            } else {
                                                $client_name = '-';
                                            }
                                            ?>
                                            <tr id="table_deposit_<?= $v_deposit->transactions_id ?>">
                                                <?php super_admin_opt_td($v_deposit->companies_id) ?>
                                                <td>
                                                    <a data-bs-toggle="modal" data-bs-target="#myModal"
                                                       href="<?= base_url() ?>admin/transactions/view_expense/<?= $v_deposit->transactions_id ?>">
                                                        <?= ($v_deposit->name ? $v_deposit->name : '-'); ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a data-bs-toggle="modal" data-bs-target="#myModal"
                                                       href="<?= base_url() ?>admin/transactions/view_expense/<?= $v_deposit->transactions_id ?>">
                                                        <?= display_datetime($v_deposit->date); ?>
                                                    </a>
                                                </td>
                                                <td class="vertical-td"><?php
                                                    if (!empty($account_info->account_name)) {
                                                        echo $account_info->account_name;
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?></td>

                                                <td><?= $client_name ?></td>
                                                <td><?= display_money($v_deposit->amount, $curency->symbol) ?></td>
                                                <td><?= display_money($v_deposit->total_balance, $curency->symbol) ?></td>
                                                <?php $show_custom_fields = custom_form_table(1, $v_deposit->transactions_id);
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
                                                    <?php
                                                    $attachement_info = json_decode($v_deposit->attachement);
                                                    if (!empty($attachement_info)) { ?>
                                                        <a href="<?= base_url() ?>admin/transactions/download/<?= $v_deposit->transactions_id ?>"><?= lang('download') ?></a>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <a data-bs-toggle="modal" data-bs-target="#myModal"
                                                       class="btn btn-info btn-sm"
                                                       href="<?= base_url() ?>admin/transactions/view_expense/<?= $v_deposit->transactions_id ?>">
                                                        <span class="fa fa-list-alt"></span>
                                                    </a>
                                                    <?php if (!empty($edited) || !empty($deleted)) { ?>
                                                        <?php if (!empty($edited) && !empty($can_edit)) { ?>
                                                            <?= btn_edit('admin/transactions/deposit/' . $v_deposit->transactions_id) ?>
                                                        <?php }
                                                        if (!empty($deleted) && !empty($can_delete)) {
                                                            ?>
                                                            <?php echo ajax_anchor(base_url("admin/transactions/delete_deposit/$v_deposit->transactions_id"), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_deposit_" . $v_deposit->transactions_id)); ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $total_amount += $v_deposit->amount;
                                            $total_credit += $v_deposit->credit;
                                            $total_balance = $total_credit;
                                            ?>
                                            <?php
                                        endif;
                                    endforeach;
                                endif;
                                ?>

                                </tbody><?php */ ?>
                            </table>
                        <!-- </div> -->
                    </div>

                    <?php if (!empty($created) || !empty($edited)) {  ?>
                    <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                        <?php echo form_open(base_url('admin/transactions/save_deposit/' . $deposit_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                        <div class="card-body">
                            <h4 class="card-title mb-4"><?php if($deposit_id){ echo lang('edit').' '.lang('deposit'); }else{ echo lang('new_deposit'); } ?></h4>
                            <?php super_admin_form($companies_id, 3, 5) ?>
                            <div class="row mb-3">
                                <label
                                    class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('name') . '/' . lang('title') ?></label>
                                <div class="col-lg-5 col-md-7 col-sm-7">
                                    <input type="text" required
                                           placeholder="<?= lang('enter') . ' ' . lang('name') . '/' . lang('title') . ' ' . lang('for_personal') ?>"
                                           name="name" class="form-control" value="<?php
                                    if (!empty($deposit_info->name)) {
                                        echo strip_tags($deposit_info->name);
                                    } ?>">
                                </div>
                            </div>

                            <div class="row mb-3 ">
                                <label class="col-lg-3 col-md-3 col-sm-3 col-form-label mt-lg"><?= lang('account') ?> <span
                                        class="text-danger">*</span> </label>
                                <div class="col-lg-5 col-md-7 col-sm-7 mt-lg">
                                    <div class="input-group">
                                        <select class="form-control select_box" style="width: 87%"
                                                name="account_id"
                                                <?php
                                        if (!empty($deposit_info) && $deposit_info->account_id != '') {
                                            echo ' disabled';
                                        }else{
                                            echo  'required';
                                        }
                                        ?>>
                                            <option value=""><?= lang('select') . ' ' . lang('account') ?></option>
                                            <?php
                                            $account_info = by_company('tbl_accounts', 'account_id', null, $companies_id);
                                            if (!empty($account_info)) {
                                                foreach ($account_info as $v_account) {
                                                    ?>
                                                    <option value="<?= $v_account->account_id ?>"
                                                        <?php
                                                        if (!empty($deposit_info->account_id)) {
                                                            echo $deposit_info->account_id == $v_account->account_id ? 'selected' : '';
                                                        }
                                                        ?>
                                                    ><?= $v_account->account_name ?></option>
                                                    <?php
                                                }
                                            }
                                            $acreated = can_action('36', 'created');
                                            ?>
                                        </select>
                                        <?php if (!empty($acreated)) { ?>
                                            <div class="input-group-text"
                                                 title="<?= lang('new') . ' ' . lang('account') ?>"
                                                 data-bs-toggle="tooltip" data-bs-placement="top">
                                                <a data-bs-toggle="modal" data-bs-target="#myModal"
                                                   href="<?= base_url() ?>admin/account/new_account"><i
                                                        class="fa fa-plus"></i></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $('select[name="companies_id"]').on('change', function () {
                                        var companies_id = $(this).val();
                                        if (companies_id) {
                                            $.ajax({
                                                url: '<?= base_url('admin/global_controller/json_by_company/tbl_accounts/')?>' + companies_id,
                                                type: "GET",
                                                dataType: "json",
                                                success: function (data) {
                                                    $('select[name="account_id"]').find('option').not(':first').remove();
                                                    $.each(data, function (key, value) {
                                                        $('select[name="account_id"]').append('<option value="' + value.account_id + '">' + value.account_name + '</option>');
                                                    });
                                                }
                                            });

                                               $.ajax({
                                                    url: '<?= base_url('admin/global_controller/json_by_company/tbl_income_category/')?>' + companies_id,
                                                    type: "GET",
                                                    dataType: "json",
                                                    success: function (data) {
                                                        console.log();

                                                        $('select[name="category_id"]').html('');
                                                        $.each(data, function (key, value) {
                                                            $('select[name="category_id"]').append('<option value="' + value.income_category_id + '">' + value.income_category + '</option>');
                                                        });
                                                          $('select[name="category_id"]').select2({});
                                                    }
                                                });


                                                 $.ajax({
                                                    url: '<?= base_url('admin/global_controller/json_by_company/tbl_client/')?>' + companies_id,
                                                    type: "GET",
                                                    dataType: "json",
                                                    success: function (data) {

                                                        $('select[name="paid_by"]').html('');
                                                        $.each(data, function (key, value) {
                                                            $('select[name="paid_by"]').append('<option value="' + value.client_id + '">' + value.name + '</option>');
                                                        });
                                                          $('select[name="paid_by"]').select2({});
                                                    }
                                                });

                                          

                                        } else {
                                            // $('select[name="account_id"]').find('option').not(':first').remove();
                                        }
                                    });
                                });
                            </script>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('date') ?></label>
                                <div class="col-lg-5 col-md-7 col-sm-7">
                                    <div class="input-group">
                                        <input type="text" name="date" class="form-control datepicker" value="<?php
                                        if (!empty($deposit_info->date)) {
                                            echo date('d-m-Y H-i', strtotime($deposit_info->date));
                                        } else {
                                            echo date('d-m-Y H:i');
                                        }
                                        ?>" data-date-format="<?= config_item('date_picker_format'); ?>">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3 terms">
                                <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('notes') ?> </label>
                                <div class="col-lg-5 col-md-7 col-sm-7">
                                <textarea name="notes" id="elm1" class="form-control"><?php
                                if (!empty($deposit_info)) {
                                    echo strip_tags($deposit_info->notes);
                                }
                                ?></textarea>
                                    </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('amount') ?> <span
                                        class="text-danger">*</span>
                                </label>
                                <div class="col-lg-5 col-md-7 col-sm-7">
                                    <div class="input-group  ">
                                        <input class="form-control " data-parsley-type="number" type="text"
                                               value="<?php
                                               if (!empty($deposit_info)) {
                                                   echo $deposit_info->amount;
                                               }
                                               ?>" name="amount" required="" <?php
                                        if (!empty($deposit_info)) {
                                            echo 'disabled';
                                        }
                                        ?>>
                                    </div>
                                </div>
                            </div>
                            <?php if (!empty($deposit_info)) { ?>
                                <input class="form-control " type="hidden"
                                       value="<?php echo $deposit_info->amount; ?>"
                                       name="amount">
                                <input class="form-control " type="hidden" value="<?php
                                if (!empty($deposit_info)) {
                                    echo $deposit_info->account_id;
                                }
                                ?>" name="old_account_id">
                            <?php } ?>
                            <div class="more_option">
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('deposit_category') ?> </label>
                                    <div class="col-lg-5 col-md-7 col-sm-7">
                                        <div class="input-group">
                                            <select class="form-control select_box" style="width: 87%"
                                                    name="category_id">
                                                <option value="0"><?= lang('none') ?></option>
                                                <?php
                                                $category_info = by_company('tbl_income_category', 'income_category_id', null, $companies_id);
                                                if (!empty($category_info)) {
                                                    foreach ($category_info as $v_category) {
                                                        ?>
                                                        <option value="<?= $v_category->income_category_id ?>"
                                                            <?php
                                                            if (!empty($deposit_info->category_id)) {
                                                                echo $deposit_info->category_id == $v_category->income_category_id ? 'selected' : '';
                                                            }
                                                            ?>
                                                        ><?= $v_category->income_category ?></option>
                                                        <?php
                                                    }
                                                }
                                                $created = can_action('125', 'created');
                                                ?>
                                            </select>
                                            <?php if (!empty($created)) { ?>
                        
                                                <div class="input-group-text"
                                                     title="<?= lang('new') . ' ' . lang('deposit_category') ?>"
                                                     data-bs-toggle="tooltip" data-bs-placement="top">
                                                    <a data-bs-toggle="modal" data-bs-target="#myModal"
                                                       href="<?= base_url() ?>admin/transactions/categories/income"><i
                                                            class="fa fa-plus"></i></a>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('paid_by') ?> </label>
                                    <div class="col-lg-5 col-md-7 col-sm-7">
                                        <select class="form-control select_box" style="width: 100%"
                                                name="paid_by" required>
                                            <option value="0"><?= lang('select_payer') ?></option>
                                            <?php
                                            $all_client = by_company('tbl_client', 'client_id', null, $companies_id);
                                            if (!empty($all_client)) {
                                                foreach ($all_client as $v_client) {
                                                    ?>
                                                    <option value="<?= $v_client->client_id ?>"
                                                        <?php
                                                        if (!empty($deposit_info)) {
                                                            echo $deposit_info->paid_by == $v_client->client_id ? 'selected' : '';
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
                                    <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('payment_method') ?> </label>
                                    <div class="col-lg-5 col-md-7 col-sm-7">
                                        <div class="input-group">
                                            <select class="form-control select_box" style="width: 87%"
                                                    name="payment_methods_id">
                                                <option value="0"><?= lang('select_payment_method') ?></option>
                                                <?php
                                                $payment_methods = $this->db->order_by('payment_methods_id', 'DESC')->get('tbl_payment_methods')->result();
                                                if (!empty($payment_methods)) {
                                                    foreach ($payment_methods as $p_method) {
                                                        ?>
                                                        <option value="<?= $p_method->payment_methods_id ?>" <?php
                                                        if (!empty($expense_info)) {
                                                            echo $expense_info->payment_methods_id == $p_method->payment_methods_id ? 'selected' : '';
                                                        }
                                                        ?>><?= $p_method->method_name ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <div class="input-group-text"
                                                 title="<?= lang('new') . ' ' . lang('payment_method') ?>"
                                                 data-bs-toggle="tooltip" data-bs-placement="top">
                                                <a data-bs-toggle="modal" data-bs-target="#myModal"
                                                   href="<?= base_url() ?>admin/settings/inline_payment_method"><i
                                                        class="fa fa-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('reference') ?> </label>
                                    <div class="col-lg-5 col-md-7 col-sm-7">

                                        <input class="form-control " type="text" value="<?php
                                        if (!empty($deposit_info)) {
                                            echo $deposit_info->reference;
                                        }
                                        ?>" name="reference">

                                        <input class="form-control " type="hidden" value="<?php
                                        if (!empty($deposit_info)) {
                                            echo $deposit_info->account_id;
                                        }
                                        ?>" name="old_account_id">

                                        <span class="help-block"><?= lang('reference_example') ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3" style="margin-bottom: 0px">
                                <label class="col-sm-3 col-form-label"><?= lang('attachment') ?></label>

                                <div class="col-sm-5">
                                    <div id="comments_file-dropzone" class="dropzone mb15"></div>
                                    <div data-simplebar style="max-height: 280px;">  
                                        <div id="comments_file-dropzone-scrollbar">
                                            <div id="comments_file-previews" class="row">
                                                <div id="file-upload-row" class="col-sm-6 mt file-upload-row">
                                                    <div class="preview box-content pr-lg" style="width:100px;">
                                                        <img data-dz-thumbnail class="upload-thumbnail-sm"/>
                                                        <div class="mb progress progress-striped upload-progress-sm active mt-sm"
                                                             role="progressbar" aria-valuemin="0" aria-valuemax="100"
                                                             aria-valuenow="0">
                                                            <div class="progress-bar progress-bar-success" style="width:0%;"
                                                                 data-dz-uploadprogress></div>
                                                        </div>
                                                    </div>
                                                    <div class="box-content">
                                                        <p class="clearfix mb0 p0">
                                                            <span class="name pull-left" data-dz-name></span>
                                                    <span data-dz-remove class="pull-right" style="cursor: pointer">
                                                    <i class="fa fa-times"></i>
                                                </span>
                                                        </p>
                                                        <p class="clearfix mb0 p0">
                                                            <span class="size" data-dz-size></span>
                                                        </p>
                                                        <strong class="error text-danger" data-dz-errormessage></strong>
                                                        <input class="file-count-field" type="hidden" name="files[]" value=""/>
                                                        <div class="mb progress progress-striped upload-progress-sm active mt-sm" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                                            <div class="progress-bar progress-bar-success w-0" data-dz-uploadprogress></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    if (!empty($deposit_info->attachement)) {
                                        $uploaded_file = json_decode($deposit_info->attachement);
                                    }
                                    if (!empty($uploaded_file)) {
                                        foreach ($uploaded_file as $v_files_image) { ?>
                                            <div class="pull-left mt pr-lg mb" style="width:100px;">
                                                <span data-dz-remove class="pull-right existing_image" style="cursor: pointer"><i class="fa fa-times"></i></span>
                                                <?php if ($v_files_image->is_image == 1) { ?>
                                                <img data-dz-thumbnail src="<?php echo base_url() . $v_files_image->path ?>" class="upload-thumbnail-sm img-fluid"/>
                                                <?php } else { ?>
                                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $v_files_image->fileName ?>" class="mailbox-attachment-icon"><i class="fa fa-file-text-o"></i></span>
                                                <?php } ?>

                                                <input type="hidden" name="path[]"
                                                       value="<?php echo $v_files_image->path ?>">
                                                <input type="hidden" name="fileName[]"
                                                       value="<?php echo $v_files_image->fileName ?>">
                                                <input type="hidden" name="fullPath[]"
                                                       value="<?php echo $v_files_image->fullPath ?>">
                                                <input type="hidden" name="size[]"
                                                       value="<?php echo $v_files_image->size ?>">
                                                <input type="hidden" name="is_image[]"
                                                       value="<?php echo $v_files_image->is_image ?>">
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                            $(".existing_image").click(function () {
                                                $(this).parent().remove();
                                            });

                                            fileSerial = 0;
                                            // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
                                            var previewNode = document.querySelector("#file-upload-row");
                                            previewNode.id = "";
                                            var previewTemplate = previewNode.parentNode.innerHTML;
                                            previewNode.parentNode.removeChild(previewNode);
                                            Dropzone.autoDiscover = false;
                                            var projectFilesDropzone = new Dropzone("#comments_file-dropzone", {
                                                url: "<?= base_url() ?>admin/global_controller/upload_file",
                                                thumbnailWidth: 80,
                                                thumbnailHeight: 80,
                                                parallelUploads: 20,
                                                previewTemplate: previewTemplate,
                                                dictDefaultMessage: '<div class="mb-3"><i class="display-4 text-muted bx bxs-cloud-upload"></i></div><h4>Drop files here or click to upload.</h4>',
                                                autoQueue: true,
                                                previewsContainer: "#comments_file-previews",
                                                clickable: true,
                                                accept: function (file, done) {
                                                    if (file.name.length > 200) {
                                                        done("Filename is too long.");
                                                        $(file.previewTemplate).find(".description-field").remove();
                                                    }
                                                    $.ajax({
                                                        url: "<?= base_url() ?>admin/global_controller/validate_project_file",
                                                        data: {
                                                            file_name: file.name,
                                                            file_size: file.size
                                                        },
                                                        cache: false,
                                                        type: 'POST',
                                                        dataType: "json",
                                                        success: function (response) {
                                                            if (response.success) {
                                                                fileSerial++;
                                                                $(file.previewTemplate).find(".description-field").attr("name", "comment_" + fileSerial);
                                                                $(file.previewTemplate).append("<input type='hidden' name='file_name_" + fileSerial + "' value='" + file.name + "' />\n\
                                                                                        <input type='hidden' name='file_size_" + fileSerial + "' value='" + file.size + "' />");
                                                                $(file.previewTemplate).find(".file-count-field").val(fileSerial);
                                                                done();
                                                            } else {
                                                                $(file.previewTemplate).find("input").remove();
                                                                done(response.message);
                                                            }
                                                        }
                                                    });
                                                },
                                                processing: function () {
                                                    $("#file-save-button").prop("disabled", true);
                                                },
                                                queuecomplete: function () {
                                                    $("#file-save-button").prop("disabled", false);
                                                },
                                                fallback: function () {
                                                    $("body").addClass("dropzone-disabled");
                                                    $('.modal-dialog').find('[type="submit"]').removeAttr('disabled');

                                                    $("#comments_file-dropzone").hide();

                                                    $("#file-modal-footer").prepend("<button id='add-more-file-button' type='button' class='btn  btn-default pull-left'><i class='fa fa-plus-circle'></i> " + "<?php echo lang("add_more"); ?>" + "</button>");

                                                    $("#file-modal-footer").on("click", "#add-more-file-button", function () {
                                                        var newFileRow = "<div class='file-row pb pt10 b-b mb10'>" +
                                                            "<div class='pb clearfix '><button type='button' class='btn btn-sm btn-danger pull-left mr remove-file'><i class='fa fa-times'></i></button> <input class='pull-left' type='file' name='manualFiles[]' /></div>" +
                                                            "<div class='mb5 pb5'><input class='form-control description-field cursor-auto'  name='comment[]'  type='text' placeholder='<?php echo lang("comment") ?>' /></div>" +
                                                            "</div>";
                                                        $("#comments_file-previews").prepend(newFileRow);
                                                    });
                                                    $("#add-more-file-button").trigger("click");
                                                    $("#comments_file-previews").on("click", ".remove-file", function () {
                                                        $(this).closest(".file-row").remove();
                                                    });
                                                },
                                                success: function (file,response) {
                                                    var res=JSON.parse(response);
                                                    if(res['error'] && res.length != 0){
                                                        toastr['error'](res['error']);
                                                        toastr['error']('<?=lang('docroom_connect_msg');?>');
                                                        $(file.previewElement).closest(".file-upload-row").remove();
                                                    }else{
                                                        var docroom_file_id=res['uploaded_file']['data'][0]['file_id'];
                                                        var docroom_file_id_html="<input class='form-control'  name='docroom_file_id[]'  type='hidden' value='"+docroom_file_id+"' />";
                                                        $("#comments_file-previews").prepend(docroom_file_id_html);
                                                        setTimeout(function () {
                                                            $(file.previewElement).find(".progress-striped").removeClass("progress-striped").addClass("progress-bar-success");
                                                        }, 1000);
                                                    }
                                                }
                                            });
                                        })
                                    </script>
                                </div>
                            </div>

                            <?php
                            if (!empty($deposit_info)) {
                                $transactions_id = $deposit_info->transactions_id;
                            } else {
                                $transactions_id = null;
                            }
                            ?>
                            <?= custom_form_Fields(1, $transactions_id); ?>

                            <div class="row mb-3" id="border-none">
                                <label class="col-md-3 col-form-label"><?= lang('assined_to') ?> <span class="required">*</span></label>
                                <div class="col-md-8">
                                    <div class="form-check form-radio-outline form-radio-primary mb-3">
                                        <input id="everyone" <?php
                                            if (!empty($deposit_info->permission) && $deposit_info->permission == 'all') {
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
                                            if (!empty($deposit_info->permission) && $deposit_info->permission != 'all') {
                                                echo 'checked';
                                            } elseif (empty($deposit_info)) {
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
                                if (!empty($deposit_info->permission) && $deposit_info->permission != 'all') {
                                    echo 'show';
                                }
                                ?>" id="permission_user_1">
                                <label class="col-md-3 col-form-label"><?= lang('select') . ' ' . lang('users') ?> <span class="required">*</span></label>
                                <div class="col-md-5">
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
                                            if (!empty($deposit_info->permission) && $deposit_info->permission != 'all') {
                                                $get_permission = json_decode($deposit_info->permission);
                                                foreach ($get_permission as $user_id => $v_permission) {
                                                    if ($user_id == $v_user->user_id) {
                                                        echo 'checked';
                                                    }
                                                }

                                            }
                                            ?>  value="<?= $v_user->user_id ?>" name="assigned_to[]" data-name="<?= $v_user->username;?>"  class="form-check-input" id ="user_<?= $v_user->user_id ?>">
                                        <label class="form-check-label" for="user_<?= $v_user->user_id ?>"><?= $v_user->username . ' ' . $role ?>
                                        </label>
                                    </div>
                                    <div class="action_1 p
                                        <?php

                                        if (!empty($deposit_info->permission) && $deposit_info->permission != 'all') {
                                            $get_permission = json_decode($deposit_info->permission);

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

                                                if (!empty($deposit_info->permission) && $deposit_info->permission != 'all') {
                                                    $get_permission = json_decode($deposit_info->permission);

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

                                                if (!empty($deposit_info->permission) && $deposit_info->permission != 'all') {
                                                    $get_permission = json_decode($deposit_info->permission);
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
                            <div class="row mb-3">
                                <label class="col-lg-3 col-md-3 col-sm-3 col-form-label"></label>
                                <div class="col-lg-5 col-md-7 col-sm-7">
                                    <button type="submit" id="file-save-button" class="btn btn-sm btn-primary"> <i class="fa fa-check"></i> <?= lang('submit') ?></button>
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
        var maxAppend = 0;
        $("#add_more").click(function () {
            if (maxAppend >= 4) {
                alert("Maximum 5 File is allowed");
            } else {
                var add_new = $('<div class="row mb-3" style="margin-bottom: 0px">\n\
                    <label for="field-1" class="col-lg-3 col-md-3 col-sm-3 col-sm-3 col-form-label"><?= lang('attachment') ?></label>\n\
                            <div class="col-lg-4 col-sm-4">\n\
                            <div class="fileinput fileinput-new" data-provides="fileinput">\n\
                    <span class="btn btn-default btn-file"><span class="fileinput-new" >Select file</span><span class="fileinput-exists" >Change</span><input type="file" name="attachement[]" ></span> <span class="fileinput-filename"></span><a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none;">&times;</a></div></div>\n\<div class="col-sm-2">\n\<strong>\n\
                    <a href="javascript:void(0);" class="remCF"><i class="fa fa-times"></i>&nbsp;Remove</a></strong></div>');
                maxAppend++;
                $("#add_new").append(add_new);
            }
        });

        $("#add_new").on('click', '.remCF', function () {
            $(this).parent().parent().parent().remove();
        });
        $('a.RCF').click(function () {
            $(this).parent().parent().remove();
        });
    });

    $(document).ready(function(){
        $("#sparkline2").sparkline([<?php if (!empty($this_7_days_deposit)) {
                foreach ($this_7_days_deposit as $v_last_deposit) {
                    echo $v_last_deposit[0]->amount . ',';
                }
            } ?>], {
            type: 'bar',
            height: '20',
            barWidth: 8,
            barSpacing: 6,
            barColor: '#23b7e5'
       });
    });
</script>

<!-- Script -->
 <script type="text/javascript">
     $(document).ready(function(){
        $('#contentTable').DataTable({
          'processing': true,
          'serverSide': true,
          'serverMethod': 'post',
          'ajax': {
             'url':'<?=base_url()?>admin/datatable/deposit'
          },
          'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
            $(nRow).attr("id", "table_deposit_"+iDisplayIndex);
            return nRow;
          },
          'columns': [
		  <?php if (is_company_column_ag()) { ?>
             { data: 'companies_id' },
		  <?php } ?>
             { data: 'name' },
             { data: 'date' },
             { data: 'account' },
             { data: 'paid_by' },
             { data: 'amount' },
             { data: 'balance' },
             { data: 'attachment' },
             // { data: 'label' },
             { data: 'action' },
          ]
        });
     });
 </script>