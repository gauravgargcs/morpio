 <?php if (!empty($coupon_info)) {
                $id = $coupon_info->id;
            } else {
                $id = null;
            }

            ?>
<!-- start page title -->
<div class="row mt-2">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18"><?php if($id) { echo lang('edit'); } else{ echo lang('New');} ?> <?php echo $title; ?></h4>

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


<div class="row mt-2">
    <div class="col-lg-12">

  
            <div class="card">
            <div class="card-body">
                <h4>   <?= lang('Coupon Details') ?></h4>
           
        
            <?php
           
            echo form_open(base_url('admin/frontend/save_coupon/' . $id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>


            <div class="row mt-2">
                <label for="field-1" class="col-sm-3 col-form-label"><?= lang('name') ?>
                    <span class="required">*</span></label>

                <div class="col-sm-5">
                    <input required type="text" name="name"
                           placeholder="<?= lang('enter') . ' ' . lang('name') ?>"
                           class="form-control" value="<?php
                    if (!empty($coupon_info->name)) {
                        echo $coupon_info->name;
                    }
                    ?>"/>
                </div>
            </div>
            <div class="row mt-2">
                <label for="field-1" class="col-sm-3 col-form-label"><?= lang('code') ?>
                    <span class="required">*</span></label>

                <div class="col-sm-5">
                    <input required type="text" name="code"
                           placeholder="<?= lang('enter') . ' ' . lang('code') ?>"
                           class="form-control" value="<?php
                    $this->load->helper('string');
                    if (!empty($coupon_info)) {
                        echo $coupon_info->code;
                    } else {
                        echo strtoupper(random_string('alnum', 8));
                    }
                    ?>"/>
                </div>
            </div>
            <div class="row mt-2">
                <label for="field-1" class="col-sm-3 col-form-label"><?= lang('amount') ?>
                    <span class="required">*</span></label>

                <div class="col-sm-5">
                    <div class="input-group">
                        <input required data-parsley-type="number" type="text" name="amount"
                               placeholder="<?= lang('enter') . ' ' . lang('amount') ?>"
                               class="form-control br0" value="<?php
                        if (isset($coupon_info->amount)) {
                            echo $coupon_info->amount;
                        }
                        ?>"/>
                        <div class="input-group-addon p0 b0">
                            <select name="type" class="form-select" data-width="100%">
                                <option value="1" <?php
                                if (isset($coupon_info)) {
                                    if ($coupon_info->type == '1') {
                                        echo 'selected';
                                    }
                                } ?>><?php echo lang('percentage'); ?></option>
                                <option value="0" <?php if (isset($coupon_info)) {
                                    if ($coupon_info->type == '0') {
                                        echo 'selected';
                                    }
                                } ?>><?php echo lang('flat'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <label
                        class="col-lg-3 col-md-3 col-sm-3 col-form-label"><?= lang('end_date') ?></label>
                <div class="col-lg-5 col-md-5 col-sm-5">
                    
                        <input required type="date" name="end_date"
                               class="form-control "
                               value="<?php
                               if (!empty($coupon_info->end_date)) {
                                   echo date('d-m-Y H-i', strtotime($coupon_info->end_date));
                               } 
                               ?>"
                               data-date-format="<?= config_item('date_picker_format'); ?>">
                    
                </div>
            </div>
            <div class="row mt-2">
                <label for="discount_type"
                       class="col-form-label col-sm-3"><?= lang('select') . ' ' . lang('package') ?><span
                            class="required">*</span></label>
                <div class="col-sm-5">
                    <select name="pricing_id" class="form-control  form-select " data-width="100%">
                        <option value="0"><?= lang('all') . ' ' . lang('package') ?></option>
                        <?php
                        $all_pricing = get_order_by('tbl_frontend_pricing', null, 'sort', true);
                        if (!empty($all_pricing)) {
                            foreach ($all_pricing as $pricing) {
                                ?>
                                <option value="<?php echo $pricing->id; ?>" <?php
                                if (isset($coupon_info)) {
                                    if ($coupon_info->pricing_id == $pricing->id) {
                                        echo 'selected';
                                    }
                                } ?>><?php echo $pricing->name; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="row mt-2">
                <label for="field-1"
                       class="col-sm-3 col-form-label"><?= lang('showing_on_pricing') ?></label>

                <div class="col-sm-5">
                 
                    <div class="form-check form-switch ">
                        <input class="form-check-input" name="show_on_pricing" value="Yes" type="checkbox" id="flexSwitchCheckChecked"  <?php if (!empty($coupon_info->show_on_pricing) && $coupon_info->show_on_pricing == 'Yes') {
                            echo 'checked';
                        } ?> data-on="Yes" data-off="No">

                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <label for="discount_type" class="col-form-label col-sm-3"></label>
                <div class="col-sm-4">
                    <button type="submit" id="sbtn" name="sbtn" value="1"
                            class="btn btn-block btn-success"><?= lang('save') ?></button>
                </div>
            </div>

        </div>
    </div>
</div>
</div>


<?php echo form_close(); ?>

