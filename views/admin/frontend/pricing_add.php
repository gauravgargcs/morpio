<!-- start page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4>

         <?php $user_id = $this->session->userdata('user_id');
         $attendance_info = $this->db->where('user_id', $user_id)->get('tbl_attendance')->result();

         foreach ($attendance_info as $v_info) {
            $all_clocking[] = $this->admin_model->check_by(array('attendance_id' => $v_info->attendance_id, 'clocking_status' => 1), 'tbl_clock');
        }
        if (!empty($all_clocking)) {
            foreach ($all_clocking as $v_clocking) {
               if (!empty($v_clocking)) {
                  $clocking = $v_clocking;
              }
          }
      }
      $plan_info = get_active_subs();
      if (!empty($plan_info)) {
        $running_plan = get_running_plan();
        if (!empty($running_plan)) {
           if (!empty($running_plan['trial'])) {
              $trial_period = $running_plan['trial'];
              $type = 'trial';
              $b_text = lang('you_are_using_trial_version', plan_name($plan_info->pricing_id)) . ' ' . $trial_period . ' ' . lang('days');
          } else {
              $trial_period = $running_plan['running'];
              $type = 'running';
              $b_text = lang('your_pricing_plan_will_expired', plan_name($plan_info->pricing_id)) . ' ' . $trial_period . ' ' . lang('days');
          }
          if (is_numeric($trial_period)) {
              if ($trial_period <= 0) {
                 redirect('checkout');
             }
             if ($type == 'trial' || $trial_period < 3) {
                 ?>
                 <span
                 class="text-sm text-danger"><?= $b_text ?><a href="<?= base_url('upgradePlan/' . $plan_info->subscriptions_id) ?>"> <?= lang('upgrade') ?></a></span>

             <?php }
         }
     }
 }
 ?>

     <div class="page-title-right">
            <form method="post" action="<?php echo base_url() ?>admin/dashboard/set_clocking/<?php
            if (!empty($clocking)) {
               echo $clocking->clock_id;
           }
        ?>">
            <div>
                <small class="text-sm">
                   &nbsp;<?php echo lang(date('l')) . ' ' . lang(date('jS')) . ' ' . lang(date('F')) . ' ' . date('\- Y,'); ?>
                   &nbsp;<?= lang('time') ?>
                   &nbsp;<span id="txt"></span></small>
                   <input type="hidden" name="clock_date" value="" id="date">
                   <input type="hidden" name="clock_time" value="" id="clock_time">
                   <?php if (!empty($clocking->clock_id)): ?>
                      <button name="clocktime" type="submit" id="sbtn" value="2"
                      class="btn btn-primary waves-effect waves-light btn-sm clock_in_button"><i
                      class="mdi mdi-logout"></i> <?= lang('clock_out') ?>
                  </button>
              <?php else: ?>
               <button name="clocktime" type="submit" id="sbtn" value="1"
               class="btn btn-primary waves-effect waves-light btn-sm clock_in_button"><i
               class="mdi mdi-login"></i> <?= lang('clock_in') ?>
            </button>
            <?php endif; ?>
            </div>
        </form>
    </div>

        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">

        <div class="card">
            <div class="card-body">
                <h4>Plan details</h4>
                
                    <?php
                    if (!empty($pricing_info)) {
                        $id = $pricing_info->id;
                    } else {
                        $id = null;
                    }
                    echo form_open(base_url('admin/frontend/save_pricing/' . $id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>

                    <div class="row ">
                        <div class="col-lg-6 col-md-6 br pv">
                           
                                <div class="row mb-2">
                                    <label for="field-1" class="col-lg-3 col-md-4 col-sm-4 col-form-label"><?= lang('name') ?>
                                    <span class="required">*</span></label>

                                    <div class="col-lg-8 col-md-8 col-sm-6">
                                        <input required type="text" name="name"
                                        placeholder="<?= lang('enter') . ' ' . lang('name') ?>"
                                        class="form-control" value="<?php
                                        if (!empty($pricing_info->name)) {
                                            echo $pricing_info->name;
                                        }
                                    ?>"/>
                                </div>
                            </div>                        
                            <div id="add_new">
                                <?php
                                if (!empty($pricing_info)) {
                                    $all_currency_price = get_result('tbl_currencywise_price', array('frontend_pricing_id' => $pricing_info->id));
                                    if (!empty($all_currency_price)) {
                                        foreach ($all_currency_price as $currency_price) { ?>
                                            <input type="hidden" value="<?= $currency_price->currencywise_price_id ?>"
                                            name="currencywise_price_id[]"/>

                                            <div id="div_<?= '00' . $currency_price->currencywise_price_id ?>">
                                                <div class="row">
                                                    <label for="discount_type"
                                                    class="col-lg-3 col-md-4 col-sm-4 col-form-label"><?= lang('change') . ' ' . lang('currency') ?></label>
                                                    <div class="col-lg-8 col-md-8 col-sm-6">
                                                        <select name="currency[]" class="select_box" data-width="100%">
                                                            <?php $cur = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row(); ?>

                                                            <?php
                                                            $currencies = get_result('tbl_currencies');
                                                            foreach ($currencies as $cur) : ?>
                                                                <option
                                                                value="<?= $cur->code ?>"<?= ($currency_price->currency == $cur->code ? ' selected="selected"' : '') ?>><?= $cur->name ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <label class="col-lg-3 col-md-4 col-sm-4 col-form-label"></label>
                                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                                        <label for="field-1"><?= lang('monthly') ?></label>
                                                        <div class="">
                                                            <input required data-parsley-type="number" type="text"
                                                            name="monthly[]"
                                                            placeholder="<?= lang('monthly') ?>"
                                                            class="form-control"
                                                            value="<?= $currency_price->monthly ?>"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label for="field-1"><?= lang('yearly') ?></label>
                                                        <div class="">
                                                            <input required data-parsley-type="number" type="text"
                                                            name="yearly[]"
                                                            placeholder="<?= lang('yearly') ?>"
                                                            class="form-control"
                                                            value="<?= $currency_price->yearly ?>"/>
                                                        </div>
                                                        <div class="margin pull-right">
                                                            <strong><a href="javascript:void(0);"
                                                             id="remove_<?= '00' . $currency_price->currencywise_price_id ?>"
                                                             class="remCF_deduc"> <i
                                                             class="fa fa-times text-danger"></i>
                                                         </a></strong>
                                                     </div>
                                                 </div>
                                             </div>
                                         </div>
                                     <?php }
                                 }
                             }
                             ?>
                         </div>
                         <div class="row">
                            <label for="discount_type"
                            class="col-lg-3 col-md-4 col-sm-4 col-form-label"><?= lang('select') . ' ' . lang('currency') ?><span
                            class="required"> *</span></label>
                            <div class="col-lg-8 col-md-8 col-sm-6">
                                <select name="currency[]" class="form-control select_box" data-width="100%">
                                    <?php $cur = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row(); ?>
                                    
                                    <?php
                                    $currencies = get_result('tbl_currencies');
                                    foreach ($currencies as $cur) : ?>
                                        <option
                                        value="<?= $cur->code ?>"<?= (config_item('default_currency') == $cur->code ? ' selected="selected"' : '') ?>><?= $cur->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-lg-3 col-md-4 col-sm-4 col-form-label"></label>
                            <div class="col-lg-4 col-md-4 col-sm-3">
                                <label for="field-1"><?= lang('monthly') ?></label>
                                <div class="">
                                    <input data-parsley-type="number" type="text" name="monthly[]"
                                    placeholder="<?= lang('monthly') ?>"
                                    class="form-control" value="<?php
                                    if (isset($pricing_info->interval_value)) {
                                        echo $pricing_info->interval_value;
                                    } ?>"/>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-3">
                                <label for="field-1"><?= lang('yearly') ?></label>
                                <div class="">
                                    <input data-parsley-type="number" type="text" name="yearly[]"
                                    placeholder="<?= lang('yearly') ?>"
                                    class="form-control" value="<?php
                                    if (isset($pricing_info->interval_value)) {
                                        echo $pricing_info->interval_value;
                                    } ?>"/>
                                </div>
                                <div class="margin pull-right">
                                    <strong><a href="javascript:void(0);" id="add_more" class="addCF "><i
                                        class="fa fa-plus"></i>&nbsp;<?= lang('mores') ?></a></strong>
                                    </div>
                                </div>

                            </div>
                           

                            <script type="text/javascript">
                                $(document).ready(function () {
                                   
                                 
                                // Init bootstrap select picker
                                var nextindex = 0;
                                $("#add_more").click(function () {
                                    var add_new = $('<div id=div_' + nextindex + '><div class="row"  >\n\
                                        <label for="discount_type" class="col-lg-3 col-md-4 col-sm-4 col-form-label"><?= lang('select') . ' ' . lang('currency') ?><span class="required"> *</span></label>\n\
                                        <div class="col-lg-8 col-md-8 col-sm-6"><select name="currency[]" class="select_box form-control" data-width="100%">\n\
                                        <?php $currencies = get_result('tbl_currencies'); foreach ($currencies as $cur) { ?><option value="<?= $cur->code ?>"><?= $cur->name ?></option>\n\<?php }; ?>
                                        </select></div></div><div class="row"><label class="col-lg-3 col-md-4 col-sm-4"></label>\n\
                                        <div class="col-lg-4 col-md-3 col-sm-3 col-xs-4"><label for="field-1"><?= lang('monthly') ?></label>\n\
                                        <div class=""> <input required data-parsley-type="number" type="text" name="monthly[]" placeholder="<?= lang('monthly') ?>" class="form-control" /></div></div>\n\
                                        <div class="col-lg-4 col-md-3 col-sm-3 col-xs-4"><label for="field-1"><?= lang('yearly') ?></label>\n\
                                        <div class=""> <input required data-parsley-type="number" type="text" name="yearly[]" placeholder="<?= lang('yearly') ?>" class="form-control" /></div>\n\
                                        <strong><a href="javascript:void(0);" id=remove_' + nextindex + ' class="remCF_deduc pull-right"><i class="fa fa-times text-danger"></i></a></strong></div></div>');
                                        nextindex++;
                                        $("#add_new").append(add_new);
                                        $(".select_box").select2({});

                                    });

                                    $("#add_new").on('click', '.remCF_deduc', function () {
                                        var id = this.id;
                                        var split_id = id.split("_");
                                        var deleteindex = split_id[1];
                                        $("#div_" + deleteindex).remove();
                                    });
                                });
                            </script>

                            <div class="row">
                                <label for="field-1" class="col-lg-3 col-md-4 col-sm-4 col-form-label"><?= lang('trail_period') ?><span
                                    class="required">*</span></label>
                                    <div class="col-lg-8 col-md-8 col-sm-6">
                                        <input required data-parsley-type="number" type="text" name="trial_period"
                                        placeholder="<?= lang('enter') . ' ' . lang('trial_period') ?>"
                                        class="form-control" value="<?php
                                        if (isset($pricing_info->trial_period)) {
                                            echo $pricing_info->trial_period;
                                        }
                                    ?>"/>
                                    <small class="text-danger "><?= lang('trail_period_help_text') ?></small>
                                </div>
                            </div>
                            <div class="row">
                                <label for="field-1"
                                class="col-lg-3 col-md-4 col-sm-4 col-form-label"><?= lang('Users') ?></label>
                                <div class="col-lg-8 col-md-8 col-sm-6">
                                    <input data-parsley-type="number" type="text" name="employee_no"
                                    placeholder="<?= lang('Enter numbers of user')  ?>"
                                    class="form-control" value="<?php
                                    if (isset($pricing_info->employee_no)) {
                                        echo $pricing_info->employee_no;
                                    }
                                ?>"/>
                                <small class="text-danger "><?= lang('pricing_help_text') ?></small>
                            </div>
                        </div>
                        <div class="row">
                            <label for="field-1"
                            class="col-lg-3 col-md-4 col-sm-4 col-form-label"><?= lang('Contacts') ?></label>
                            <div class="col-lg-8 col-md-8 col-sm-6">
                                <input data-parsley-type="number" type="text" name="client_no"
                                placeholder="<?= lang('Enter numbers of contact') ?>"
                                class="form-control" value="<?php
                                if (isset($pricing_info->client_no)) {
                                    echo $pricing_info->client_no;
                                }
                            ?>"/>
                            <small class="text-danger "><?= lang('pricing_help_text') ?></small>
                        </div>
                    </div>
                    <div class="row">
                        <label for="field-1"
                        class="col-lg-3 col-md-4 col-sm-4 col-form-label"><?= lang('leads') . ' ' . lang('manager') ?></label>
                        <div class="col-lg-8 col-md-8 col-sm-6">
                            <input data-parsley-type="number" type="text" name="leads"
                            placeholder="<?= lang('enter') . ' ' . lang('leads') . ' ' . lang('no') ?>"
                            class="form-control" value="<?php
                            if (isset($pricing_info->leads)) {
                                echo $pricing_info->leads;
                            }
                        ?>"/>
                        <small class="text-danger "><?= lang('pricing_help_text') ?></small>
                    </div>
                </div>
                <div class="row">
                    <label for="field-1"
                    class="col-lg-3 col-md-4 col-sm-4 col-form-label"><?= lang('accounting') ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-6">
                        <input data-parsley-type="number" type="text" name="accounting"
                        placeholder="<?= lang('enter') . ' ' . lang('expense') . ' & ' . lang('deposit') ?>"
                        class="form-control" value="<?php
                        if (isset($pricing_info->accounting)) {
                            echo $pricing_info->accounting;
                        }
                    ?>"/>
                    <small class="text-danger "><?= lang('pricing_help_text') ?></small>
                </div>
            </div>

            <div class="row">
                <label for="field-1"
                class="col-lg-3 col-md-4 col-sm-4 col-form-label"><?= lang('bank') . ' ' . lang('account') ?></label>
                <div class="col-lg-8 col-md-8 col-sm-6">
                    <input data-parsley-type="number" type="text" name="bank_account"
                    placeholder="<?= lang('enter') . ' ' . lang('bank') . ' ' . lang('account') . ' ' . lang('no') ?>"
                    class="form-control" value="<?php
                    if (isset($pricing_info->bank_account)) {
                        echo $pricing_info->bank_account;
                    }
                ?>"/>
                <small class="text-danger "><?= lang('pricing_help_text') ?></small>
            </div>
        </div>

        <div class="row">
            <label for="field-1"
            class="col-lg-3 col-md-4 col-sm-4 col-form-label"><?= lang('tasks') . ' ' . lang('manager') ?></label>
            <div class="col-lg-8 col-md-8 col-sm-6">
                <input data-parsley-type="number" type="text" name="tasks"
                placeholder="<?= lang('enter') . ' ' . lang('tasks') . ' ' . lang('no') ?>"
                class="form-control" value="<?php
                if (isset($pricing_info->tasks)) {
                    echo $pricing_info->tasks;
                }
            ?>"/>
            <small class="text-danger "><?= lang('pricing_help_text') ?></small>
        </div>
    </div>
    <div class="row">
        <label for="field-1"
        class="col-lg-3 col-md-4 col-sm-4 col-form-label"><?= lang('Project Management')  ?></label>
        <div class="col-lg-8 col-md-8 col-sm-6">
            <input data-parsley-type="number" type="text" name="project_no"
            placeholder="<?= lang('enter') . ' ' . lang('project') . ' ' . lang('no') ?>"
            class="form-control" value="<?php
            if (isset($pricing_info->project_no)) {
                echo $pricing_info->project_no;
            }
        ?>"/>
        <small class="text-danger "><?= lang('pricing_help_text') ?></small>
    </div>
</div>
<div class="row">
    <label for="field-1"
    class="col-lg-3 col-md-4 col-sm-4 col-form-label"><?= lang('invoice') ?></label>
    <div class="col-lg-8 col-md-8 col-sm-6">
        <input data-parsley-type="number" type="text" name="invoice_no"
        placeholder="<?= lang('enter') . ' ' . lang('invoice') . ' ' . lang('no') ?>"
        class="form-control" value="<?php
        if (isset($pricing_info->invoice_no)) {
            echo $pricing_info->invoice_no;
        }
    ?>"/>
    <small class="text-danger "><?= lang('pricing_help_text') ?></small>
</div>
</div>
<div class="row">
    <label for="field-1"
    class="col-lg-3 col-md-4 col-sm-4 col-form-label"><?= lang('disk_space') ?></label>
    <div class="col-lg-8 col-md-8 col-sm-6">
        <input type="text" name="disk_space"
        placeholder="<?= lang('enter') . ' ' . lang('disk_space') ?>"
        class="form-control" value="<?php
        if (isset($pricing_info->disk_space)) {
            echo $pricing_info->disk_space;
        }
    ?>"/>
    <small class="text-danger "><?= lang('disk_space_help_text') ?></small>
</div>
</div>



</div>
<div class="col-lg-6 col-md-6 border-start module-checkboxes">
    <div class="row">
       
            <label for="field-1"
            class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('multi_branch') ?></label>
            <div class="col-lg-8 col-md-6 col-sm-6">
                
                        <input type="checkbox" value="Yes"
                        <?php if (!empty($pricing_info) && $pricing_info->multi_branch == 'Yes') {
                            echo 'checked';
                        } ?> name="multi_branch">
                       
            </div>
        </div>
      
        <?php
        $all_module = get_old_data('tbl_modules', array('active' => 1, 'module_name !=' => 'mailbox'),true);

        if (!empty($all_module)) {
            foreach ($all_module as $v_module) {
                $name = 'allow_' . $v_module->module_name;

                ?>
                <div class="row">
                    <label for="field-1"
                    class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang($v_module->module_name) ?></label>
                    <div class="col-lg-8 col-md-6 col-sm-6">
                       
                                <input type="checkbox" value="Yes"
                                <?php if (!empty($pricing_info) && isset($pricing_info->$name) && $pricing_info->$name == 'Yes') {
                                    echo 'checked';
                                } ?> name="<?= $name ?>">
                               
                    </div>
                </div>
            <?php }
        }
        ?>
        <div class="row">
            <label for="field-1"
            class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('Online payments') ?></label>
            <div class="col-lg-8 col-md-6 col-sm-6">
                
                        <input type="checkbox" value="Yes"
                        <?php if (!empty($pricing_info) && $pricing_info->online_payment == 'Yes') {
                            echo 'checked';
                        } ?> name="online_payment">
                       
            </div>
        </div>
        <div class="row">
            <label for="field-1"
            class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('calendar') ?></label>
            <div class="col-lg-8 col-md-6 col-sm-6">
                
                        <input type="checkbox" value="Yes"
                        <?php if (!empty($pricing_info) && $pricing_info->calendar == 'Yes') {
                            echo 'checked';
                        } ?> name="calendar">
                       
            </div>
        </div>
        <div class="row">
            <label for="field-1"
            class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('mailbox') ?></label>
            <div class="col-lg-8 col-md-6 col-sm-6">
                
                        <input type="checkbox" value="Yes"
                        <?php if (!empty($pricing_info) && $pricing_info->mailbox == 'Yes') {
                            echo 'checked';
                        } ?> name="mailbox">
                       
            </div>
        </div>
        <div class="row">
            <label for="field-1"
            class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('Team Chat') ?></label>
            <div class="col-lg-8 col-md-6 col-sm-6">
                
                        <input type="checkbox" value="Yes"
                        <?php if (!empty($pricing_info) && $pricing_info->live_chat == 'Yes') {
                            echo 'checked';
                        } ?> name="live_chat">
                       
            </div>
        </div>

        <div class="row">
            <label for="field-1"
            class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('tickets') ?></label>
            <div class="col-lg-8 col-md-6 col-sm-6">
                
                        <input type="checkbox" value="Yes"
                        <?php if (!empty($pricing_info) && $pricing_info->tickets == 'Yes') {
                            echo 'checked';
                        } ?> name="tickets">
                       
            </div>
        </div>
        <div class="row">
            <label for="field-1"
            class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('filemanager') ?></label>
            <div class="col-lg-8 col-md-6 col-sm-6">
                
                        <input type="checkbox" value="Yes"
                        <?php if (!empty($pricing_info) && $pricing_info->filemanager == 'Yes') {
                            echo 'checked';
                        } ?> name="filemanager">
                       
            </div>
        </div>
        <div class="row">
            <label for="field-1"
            class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('job_circular') . ' ' . lang('manager') ?></label>
            <div class="col-lg-8 col-md-6 col-sm-6">
                
                        <input type="checkbox" value="Yes"
                        <?php if (!empty($pricing_info) && $pricing_info->recruitment == 'Yes') {
                            echo 'checked';
                        } ?> name="recruitment">
                       
            </div>
        </div>
        <div class="row">
            <label for="field-1"
            class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('attendance') . ' ' . lang('manager') ?></label>
            <div class="col-lg-8 col-md-6 col-sm-6">
                
                        <input type="checkbox" value="Yes"
                        <?php if (!empty($pricing_info) && $pricing_info->attendance == 'Yes') {
                            echo 'checked';
                        } ?> name="attendance">
                       
            </div>
        </div>
        <div class="row">
            <label for="field-1"
            class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('payroll') . ' ' . lang('manager') ?></label>
            <div class="col-lg-8 col-md-6 col-sm-6">
                
                        <input type="checkbox" value="Yes"
                        <?php if (!empty($pricing_info) && $pricing_info->payroll == 'Yes') {
                            echo 'checked';
                        } ?> name="payroll">
                       
            </div>
        </div>
        <div class="row">
            <label for="field-1"
            class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('leave') . ' ' . lang('manager') ?></label>
            <div class="col-lg-8 col-md-6 col-sm-6">
                
                        <input type="checkbox" value="Yes"
                        <?php if (!empty($pricing_info) && $pricing_info->leave_management == 'Yes') {
                            echo 'checked';
                        } ?> name="leave_management">
                       
            </div>
        </div>
        <div class="row">
            <label for="field-1"
            class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('Performance Tracking') ?></label>
            <div class="col-lg-8 col-md-6 col-sm-6">
                
                        <input type="checkbox" value="Yes"
                        <?php if (!empty($pricing_info) && $pricing_info->performance == 'Yes') {
                            echo 'checked';
                        } ?> name="performance">
                       
            </div>
        </div>
        <div class="row">
            <label for="field-1"
            class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('training') . ' ' . lang('manager') ?></label>
            <div class="col-lg-8 col-md-6 col-sm-6">
                
                        <input type="checkbox" value="Yes"
                        <?php if (!empty($pricing_info) && $pricing_info->training == 'Yes') {
                            echo 'checked';
                        } ?> name="training">
                       
            </div>
        </div>
        <div class="row">
            <label for="field-1"
            class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('report') . ' ' . lang('manager') ?></label>
            <div class="col-lg-8 col-md-6 col-sm-6">
                
                        <input type="checkbox" value="Yes"
                        <?php if (!empty($pricing_info) && $pricing_info->reports == 'Yes') {
                            echo 'checked';
                        } ?> name="reports">
                       
            </div>
        </div>

        <div class="row">
            <label for="field-1"
            class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('recommended') ?></label>
            <div class="col-lg-8 col-md-6 col-sm-6">
                
                        <input type="checkbox" value="Yes"
                        <?php if (!empty($pricing_info) && $pricing_info->recommended == 'Yes') {
                            echo 'checked';
                        } ?> name="recommended">
                       
            </div>
        </div>
        <?php if (config_item('paypal_status') == 'active'): ?>
            <div class="row">
                <label for="field-1"
                class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('allow_paypal') ?></label>
                <div class="col-lg-8 col-md-6 col-sm-6">
                    
                            <input type="checkbox" value="Yes"
                            <?php if (!empty($pricing_info) && $pricing_info->allow_paypal == 'Yes') {
                                echo 'checked';
                            } ?> name="allow_paypal">
                           
                </div>
            </div>
        <?php endif ?>
        <?php if (config_item('stripe_status') == 'active'): ?>
            <div class="row">
                <label for="field-1"
                class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('allow_stripe') ?></label>
                <div class="col-lg-8 col-md-6 col-sm-6">
                    
                            <input type="checkbox" value="Yes"
                            <?php if (!empty($pricing_info) && $pricing_info->allow_stripe == 'Yes') {
                                echo 'checked';
                            } ?>
                            name="allow_stripe">
                </div>
            </div>
        <?php endif; ?>
        <?php if (config_item('2checkout_status') == 'active'): ?>
            <div class="row">
                <label for="field-1"
                class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('allow_2checkout') ?></label>

                <div class="col-lg-8 col-md-6 col-sm-6">

                    
                            <input type="checkbox" value="Yes"
                            <?php if (!empty($pricing_info) && $pricing_info->allow_2checkout == 'Yes') {
                                echo 'checked';
                            } ?>
                            name="allow_2checkout">
                </div>
            </div>
        <?php endif; ?>
        <?php if (config_item('authorize_status') == 'active'): ?>
            <div class="row">
                <label for="field-1"
                class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('allow_authorize.net') ?></label>

                <div class="col-lg-8 col-md-6 col-sm-6">
                    
                            <input type="checkbox" value="Yes"
                            <?php if (!empty($pricing_info) && $pricing_info->allow_authorize == 'Yes') {
                                echo 'checked';
                            } ?>
                            name="allow_authorize">

                </div>
            </div>
        <?php endif; ?>
        <?php if (config_item('ccavenue_status') == 'active'): ?>
            <div class="row">
                <label for="field-1"
                class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('allow_ccavenue') ?></label>

                <div class="col-lg-8 col-md-6 col-sm-6">
                    
                            <input type="checkbox" value="Yes"
                            <?php if (!empty($pricing_info) && $pricing_info->allow_ccavenue == 'Yes') {
                                echo 'checked';
                            } ?>
                            name="allow_ccavenue">

                </div>
            </div>
        <?php endif; ?>
        <?php if (config_item('braintree_status') == 'active'): ?>
            <div class="row">
                <label for="field-1"
                class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('allow_braintree') ?></label>

                <div class="col-lg-8 col-md-6 col-sm-6">
                    
                            <input type="checkbox" value="Yes"
                            <?php if (!empty($pricing_info) && $pricing_info->allow_braintree == 'Yes') {
                                echo 'checked';
                            } ?>
                            name="allow_braintree">
                </div>
            </div>
        <?php endif; ?>
        <?php if (config_item('mollie_status') == 'active'): ?>
            <div class="row">
                <label for="field-1"
                class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('allow_mollie') ?></label>

                <div class="col-lg-8 col-md-6 col-sm-6">
                    
                            <input type="checkbox" value="Yes"
                            <?php if (!empty($pricing_info) && $pricing_info->allow_mollie == 'Yes') {
                                echo 'checked';
                            } ?>
                            name="allow_mollie">
                </div>
            </div>
        <?php endif; ?>
        <?php if (config_item('payumoney_status') == 'active'): ?>
            <div class="row">
                <label for="field-1"
                class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('allow_payumoney') ?></label>

                <div class="col-lg-8 col-md-6 col-sm-6">
                    
                            <input type="checkbox" value="Yes"
                            <?php if (!empty($pricing_info) && $pricing_info->allow_payumoney == 'Yes') {
                                echo 'checked';
                            } ?>
                            name="allow_payumoney">
                </div>
            </div>
        <?php endif; ?>
        <div class="row">
            <label for="field-1" class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?= lang('status') ?><span
                class="required">*</span></label>

                <div class="col-lg-8 col-md-6 col-sm-6 row">
                    <div class="col-lg-6 ">
                        <div class="checkbox-inline c-checkbox">
                            <label>
                                <input <?= (!empty($pricing_info->status) && $pricing_info->status == '1' ? 'checked' : ''); ?>
                                class="select_one" type="checkbox" name="status" value="1">
                                 <?= lang('published') ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-6 ">
                        <div class="checkbox-inline c-checkbox">
                            <label>
                                <input <?= (!empty($pricing_info->status) && $pricing_info->status == '0' ? 'checked' : ''); ?>
                                class="select_one" type="checkbox" name="status" value="0">
                                 <?= lang('unpublished') ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-xs-12 ">
        <div class="row">
            <label class="col-lg-12 col-form-label"><?= lang('description') ?> </label>
            <div class="col-lg-12">
                <textarea name="description" id="elm1" class="textarea form-control"><?php
                if (!empty($pricing_info)) {
                    echo $pricing_info->description;
                }
            ?></textarea>
        </div>
    </div>
</div>
</div>
<div class="p-3">
        <button type="submit" id="sbtn" name="sbtn" value="1"
        class="btn btn-block btn-success w-md  float-end"><?= lang('save') ?></button>
    <a href="<?=site_url('admin/frontend/pricing');?>" 
        class="btn btn-block btn-secondary w-md mx-2 float-end"><?= lang('Cancel') ?></a>
    </div>
</div>
<?php echo form_close(); ?>

</div>
</div>
</div>
<style type="text/css">
.dragger {
    background: url(../../skote_assets/images/dragger.png) 0px 15px no-repeat;
    cursor: pointer;
}
</style>

<script src="<?= base_url() ?>assets/plugins/jquery-ui/jquery-u.js"></script>
<script type="text/javascript">

    $(document).ready(function () {
        $('.ajax_change_status input[type="checkbox"]').change(function () {
            var id = $(this).data().id;
            var status = $(this).is(":checked");
            if (status == true) {
                status = 1;
            } else {
                status = 0;
            }
            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: '<?= base_url()?>admin/frontend/change_status/tbl_frontend_pricing/' + id + '/' + status, // the url where we want to POST
                dataType: 'json', // what type of data do we expect back from the server
                encode: true,
                success: function (res) {
                    console.log(res);
                    if (res) {
//                        toastr[res.status](res.message);
} else {
    alert('There was a problem with AJAX');
}
}
})
        });
    })

    $(function () {
        $('tbody[id^="pricing"]').sortable({
            connectWith: ".pricing",
            placeholder: 'ui-state-highlight',
            forcePlaceholderSize: true,
            stop: function (event, ui) {
                var id = JSON.stringify(
                    $('tbody[id^="pricing"]').sortable(
                        'toArray',
                        {
                            attribute: 'data-id'
                        }
                        )
                    );
                var formData = {
                    'pricing': id
                };
                console.log(formData);
                $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: '<?= base_url()?>admin/frontend/save_pricing/', // the url where we want to POST
                    data: formData, // our data object
                    dataType: 'json', // what type of data do we expect back from the server
                    encode: true,
                    success: function (res) {
                        if (res) {
//                            toastr[res.status](res.message);
} else {
    alert('There was a problem with AJAX');
}
}
})

            }
        });
                                    $(".select_box").select2();

    });
</script>

<!--tinymce js-->
<script src="<?php echo base_url(); ?>skote_assets/libs/tinymce/tinymce.min.js"></script>

<!-- init js -->
<script src="<?php echo base_url(); ?>skote_assets/js/pages/form-editor.init.js"></script>