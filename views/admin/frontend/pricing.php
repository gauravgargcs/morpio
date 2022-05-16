<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
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

<div class="row mb-3">
<div class="mb-lg pull-left">
    <div class="pull-left pr-lg">
        <a href="<?=site_url('admin/frontend/pricing_add');?>" class="btn btn-xs btn-danger pull-right" ><i class="fa fa-plus"> </i>  New Pricing </a>
    </div>
   
</div>
</div>
<div class="row">
    <div class="col-lg-12">

        <div class="card">
            <div class="card-body">
            <h4>All Pricing</h4>
                <div class="table-responsive">
                        <table class="table " id="">
                            <thead>
                                <tr>
                                    <th><?= lang('name') ?></th>
                                    <th><?= lang('trail_period') ?></th>
                                    <th><?= lang('recommended') ?></th>
                                    <th><?= lang('published') ?></th>
                                    <th class="col-options no-sort"><?= lang('action') ?></th>
                                </tr>
                            </thead>
                            <tbody id="pricing">
                                <?php
                                $all_pricing = get_order_by('tbl_frontend_pricing', null, 'sort', true);
                                if (!empty($all_pricing)) {
                                    foreach ($all_pricing as $pricing) {
                                        ?>
                                        <tr class="pricing" data-id="<?= $pricing->id ?>"
                                            id="table_pricing_<?= $pricing->id ?>">
                                            <td class="dragger pl-lg"><?= $pricing->name ?></td>
                                            <td><?= $pricing->trial_period . ' ' . lang('days') ?></td>
                                            <td><?= lang($pricing->recommended) ?></td>
                                            <td class="text-center">
                                               <!--  <div class="checkbox ajax_change_status">
                                                    <input data-id="<?= $pricing->id ?>" data-bs-toggle="toggle"
                                                    name="status"
                                                    value="1" <?php if ($pricing->status == 1) {
                                                        echo 'checked';
                                                    } ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>"
                                                    data-onstyle="success btn-xs"
                                                    data-offstyle="danger btn-xs" type="checkbox">
                                                </div> -->
                                                <div class="form-check form-switch mx-3 ajax_change_status">
                                                    <input data-id="<?= $pricing->id ?>" class="form-check-input"  name="status" value="1" type="checkbox" id="flexSwitchCheckChecked" <?php if ($pricing->status == 1) {
                                                        echo 'checked';
                                                    } ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>">

                                                </div>
                                            </td>
                                            <td>

                                                <a href="<?= base_url() ?>admin/frontend/pricing_add/<?= $pricing->id ?>"
                                                 class="btn btn-outline-success btn-sm"
                                                 data-bs-toggle="tooltip" title="<?= lang('edit') ?>"
                                                 data-bs-placement="top">
                                                 <i class="fa fa-pencil-square-o"></i></a>

                                                 <a data-bs-toggle="modal" data-bs-target="#myModal"
                                                 href="<?= base_url("admin/global_controller/package_details/$pricing->id") ?>"
                                                 class="btn btn-outline-info btn-sm"><span class="fa fa-list-alt"></span></a> 

                                                 <?php echo ajax_anchor(base_url("admin/frontend/delete/tbl_frontend_pricing/$pricing->id"), "<i class='fa fa-trash-o'></i>", array("class" => "btn btn-outline-danger btn-sm", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child",  "data-fade-out-on-success" => "#table_pricing_" . $pricing->id)); ?>
                                             </td>
                                         </tr>
                                     <?php } ?>
                                 <?php } ?>
                             </tbody>
                         </table>
                     </div>
            </div>
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
    });
</script>
