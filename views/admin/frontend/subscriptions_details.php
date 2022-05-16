<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4>

            <?php $this->load->view('admin/skote_layouts/title'); ?>
            

        </div>
    </div>
</div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                 <div class="col-lg-4">
                   
                    <h4 class="mb-4"><?= lang('subscription_details') ?></h4>
             
                    
                <?php

                $super_admin = super_admin();
                $plan_info = get_old_data('tbl_subscriptions_history', array('status' => $subscription_info->status, 'subscriptions_id' => $subscription_info->subscriptions_id));

                if (!empty($plan_info)) {
                    $currency = get_old_data('tbl_currencies', array('code' => $plan_info->currency));
                    if ($plan_info->frequency == 'monthly') {
                        $frequency = lang('mo');
                    } else {
                        $frequency = lang('yr');
                    }
                    $plan_name = '<a data-bs-toggle="modal" data-bs-target="#myModal" href="' . base_url('admin/global_controller/subs_package_details/' . $plan_info->id) . '">' . $plan_info->name . ' ' . display_money($plan_info->amount, $currency->symbol) . ' /' . $frequency . ' ' . '</a>';
                } else {
                    $plan_name = '-';
                }
                $activation_code = null;
                if ($subscription_info->db_id != 0) {
                    $db_info = get_old_data('tbl_subscriptions_database', array('id' => $subscription_info->db_id));
                }
                $update = true;
                if ($subscription_info->status == 'pending') {
                    $label = 'exclamation-triangle text-info';
                    $activation_code = $subscription_info->activation_tocken;
                    $update = null;
                } else if ($subscription_info->status == 'running') {
                    $label = 'check-circle text-success';
                } else if ($subscription_info->status == 'expired') {
                    $label = 'lock text-danger';
                } else if ($subscription_info->status == 'suspended') {
                    $label = 'ban text-warning';
                } else {
                    $label = 'times-circle text-danger';
                }
                $trial = null;
                $till_date = null;
                $validity_date = null;
                if ($subscription_info->status != 'pending') {
                    if ($subscription_info->trial_period != 0) {
                        $till_date = trial_period($subscription_info->subscriptions_id);
                        $trial = '<small class="badge badge-danger text-sm mt0">' . lang('trial') . '</small>';
                    } else {
                        $till_date = running_period($subscription_info->subscriptions_id);
                    }
                    $validity_date = date("Y-m-d", strtotime($till_date . "day"));
                }
                ?>
                <div class="bb pb pt" style="overflow: hidden">
                    <i class="pull-left fa fa-3x fa-<?= $label ?>"></i>
                    <h2 class="mt0 pull-left"><?= lang($subscription_info->status) . ' ' . $trial ?>
                        <?php if (!empty($till_date)) { ?>
                            <small
                                    class="block text-sm ml-sm"><?= lang('till') . ' ' . $validity_date; ?></small>
                        <?php } ?>
                    </h2>
                </div>
                <div class="bb pb-sm pt-sm">
                    <label class="col-form-label"><?= lang('name') ?></label>
                    <span class="pull-right"><?= $subscription_info->name ?></span>
                </div>
                <div class="bb pb-sm pt-sm">
                    <label class="col-form-label"><?= lang('email') ?></label>
                    <span class="pull-right"><?= $subscription_info->email ?></span>
                </div>
                <div class="bb pb-sm pt-sm">
                    <label class="col-form-label"><?= lang('domain') ?></label>
                    <span class="pull-right"><?= $subscription_info->domain ?></span>
                </div>

                <div class="bb pb-sm pt-sm">
                    <label class="col-form-label"><?= lang('plan') ?></label>
                    <span class="pull-right"><?= $plan_name ?></span>
                </div>
                <?php if (!empty($activation_code)) { ?>
                    <div class="bb pb-sm pt-sm">
                        <label class="col-form-label"><?= lang('activation_token') ?></label>
                        <span class="pull-right" id="hosting_password ">
                        <a data-bs-toggle="modal" data-bs-target="#myModal"
                           href="<?= base_url('admin/client/see_password/activationtoken_' . $subscription_info->subscriptions_id) ?>"
                           id="see_password"><?= lang('see') . ' ' . lang('token') ?></a>
                            </span>
                    </div>
                        <span id="show_password" class="required block"></span>
                <?php }
                if (!empty($db_info) && !empty($super_admin)) { ?>
                    <div class="bb pb-sm pt-sm">
                        <label class="col-form-label"><?= lang('database') ?> </label>
                        <span class="pull-right" id="hosting_password ">
                        <a data-bs-toggle="modal" data-bs-target="#myModal"
                           href="<?= base_url('admin/client/see_password/database_' . $db_info->database_name) ?>"
                           id="see_password"><?= lang('see') . ' ' . lang('database') ?></a>
                            </span>
                    </div>
                        <span id="show_password" class="required block"></span>
                <?php } ?>
                <div class="pb-sm pt-sm">
                    <label class="col-form-label"><?= lang('created_date') ?></label>
                    <span class="pull-right"><?= display_datetime($subscription_info->created_date) ?></span>
                </div>
                <?php
                if (empty($super_admin)) {
                    ?>
                    <div class="pb-sm pt-sm pull-right">
                        <label class="col-form-label"></label>
                        <a href="<?= base_url('upgradePlan/' . $subscription_info->subscriptions_id) ?>"
                           class="btn btn-sm btn-info"><i
                                    class="fa fa-redo"></i><?= lang('upgrade') . ' ' . lang('package') ?></a>
                    </div>
                <?php }
                ?>

           
      
        <?php if (!empty($update) && !empty($super_admin)) { ?>
    <?php echo form_open(base_url('admin/frontend/update_sub_validity/' . $subscription_info->subscriptions_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
        <div class="panel panel-custom">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong><?= lang('update') ?></strong>
                </div>
            </div>
            <div class="panel-body">
                <div class="mt0 pt0">
                    <div class="pb-sm pt-sm">
                        <label class="col-form-label"><?= lang('status') ?></label>
                        <select name="status" class="form-control select_box"
                                style="width: 100%">
                            <?php
                            $subs_status = array('running', 'expired', 'suspended', 'terminated');
                            if (!empty($subs_status)): foreach ($subs_status as $sb_status): ?>
                                <option
                                        value="<?= $sb_status ?>" <?= (!empty($subscription_info->status) && $subscription_info->status == $sb_status ? 'selected' : NULL) ?>><?= lang($sb_status) ?>
                                </option>
                            <?php
                            endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="pb-sm pt-sm">
                    <label class="col-form-label"><?= lang('validity') ?></label>
                    <div class="input-group">
                        <input required type="text" name="validity" id=""
                               placeholder="<?= lang('enter') . ' ' . lang('validity') ?>"
                               class="form-control datepicker" value="<?php
                        if (!empty($validity_date)) {
                            echo date('d-m-Y H-i', strtotime($validity_date));
                        }
                        ?>">
                       <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                        
                    </div>
                </div>
                <div class="pb-sm pt-sm">
                    <label class="col-form-label"><?= lang('remarks') ?></label>
                    <textarea class="form-control" name="remarks"
                              required><?php if (!empty($subscription_info->remarks)) {
                            echo $subscription_info->remarks;
                        } ?></textarea>
                </div>
                <div class="pb-sm pt-sm">
                    <label class="col-form-label"><?= lang('put_on_maintenance') ?></label>
                    <div class="form-check form-check-primary mb-3 mt-sm">
                        <input type="checkbox" <?php if ($subscription_info->maintenance_mode == 'Yes') { echo "checked=\"checked\""; } ?> name="maintenance_mode" id="use_postmark" class="form-check-input">
                            
                    </div>
                </div>
                <div class="pb-sm pt-sm mb-4"
                     id="postmark_config" <?php echo ($subscription_info->maintenance_mode != 'Yes') ? 'style="display:none"' : '' ?>>
                    <label class="col-form-label"><?= lang('maintenance_mode_message') ?></label>
                    <textarea class="form-control"
                              name="maintenance_mode_message"><?php if (!empty($subscription_info->maintenance_mode_message)) {
                            echo $subscription_info->maintenance_mode_message;
                        } ?></textarea>
                </div>
                <div class="pb-sm pt-sm pull-right">
                    <label class="col-form-label"></label>
                    <button type="submit" class="btn btn-sm btn-primary "><?= lang('update') ?></button>
                </div>
                <?php }
                if (!empty($super_admin)) {
                    ?>
                    <div class="pb-sm pt-sm pull-left">
                        
                        <a href="<?= base_url('admin/frontend/subscriptions') ?>" class="btn btn-sm btn-warning"><?= lang('back') ?></a>
                    </div>
                    
                    <?php
                } else {
                }
                if (!empty($update) && !empty($super_admin)) { ?>
            </div>
        </div>
        
    <?php echo form_close(); ?>
    <?php } ?>
    </div>


<div class="col-md-8">
  
                    <h4 class="mb-4"><?= lang('subscriptions') . ' ' . lang('histories') ?></h4>
                  
                      


            <!-- Table -->
            <div id="payment_history" class="table-responsive">
                <table class="table table-striped DataTables" id="">
                    <thead>
                    <tr>
                        <th><?= lang('name') ?></th>
                        <th><?= lang('amount') ?></th>
                        <th><?= lang('created_date') ?></th>
                        <th><?= lang('validity') ?></th>
                        <th><?= lang('method') ?></th>
                        <th><?= lang('status') ?></th>
                    </tr>
                    </thead>
                    <tbody id="pricing">
                    <?php
                    $all_subscriptions_history = get_old_order_by('tbl_subscriptions_history', array('subscriptions_id' => $subscription_info->subscriptions_id), 'created_at');
                    if (!empty($all_subscriptions_history)) {
                        foreach ($all_subscriptions_history as $v_s_history) {
                            if (empty($v_s_history->currency)) {
                                $currency_code = config_item('default_currency');
                            } else {
                                $currency_code = $v_s_history->currency;
                            }
                            $currency = get_old_data('tbl_currencies', array('code' => $plan_info->currency));

                            if ($v_s_history->status == 'pending') {
                                $label = 'primary';
                            } else if ($v_s_history->status == 'running') {
                                $label = 'success';
                            } else if ($v_s_history->status == 'expired') {
                                $label = 'danger';
                            } else if ($v_s_history->status == 'suspended') {
                                $label = 'danger';
                            } else {
                                $label = 'warning';
                            }
                            if ($v_s_history->frequency == 'monthly') {
                                $frequency = lang('mo');
                            } else {
                                $frequency = lang('yr');
                            }
                            ?>
                            <tr class="pricing" id="table_pricing_<?= $v_s_history->subscriptions_id ?>">
                                <td>
                                    <a data-bs-toggle='modal' data-bs-target='#myModal' href="<?= base_url('admin/global_controller/subs_package_details/' . $v_s_history->id) ?>" class="text-center"><?= $v_s_history->name ?></a>
                                </td>
                                <td><?= display_money($v_s_history->amount, $currency->symbol) . ' /' . $frequency . ' ' ?></td>
                                <td><?= display_datetime($v_s_history->created_at) ?></td>
                                <td><?= (!empty($v_s_history->validity) ? $v_s_history->validity : '-') ?></td>
                                <td><?= (!empty($v_s_history->payment_method) ? $v_s_history->payment_method : '-') ?></td>
                                <td><span class="badge badge-<?= $label ?>"> <?= lang($v_s_history->status) ?></span>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4"><?=lang('import').' '.lang('database');?></h4>
                    <?php echo form_open(base_url('admin/frontend/import_db/' . $subscription_info->subscriptions_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                    <div class="row">
                        <div class="col-lg-8 mb-3">
                            <label class="col-lg-3 form-label"><?= lang('upload'). ' ' . lang('zipped_file') ?></label>
                            <input class="form-control" type="file" id="formFile" name="upload_file"/>
                        </div>
                    
                        <div class="pb-sm pt-sm pull-right">
                            <button type="submit" class="btn btn-danger"><?= lang('activate_database') ?></button>
                        </div>

                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div><!--************ Payment History End***********-->
    </div>
</div>
</div>
</div>