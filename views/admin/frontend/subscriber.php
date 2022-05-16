<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<!-- start page title -->
<div class="row">
 <div class="col-12">
  <div class="page-title-box d-sm-flex align-items-center justify-content-between">
   <h4 class="mb-sm-0 font-size-18"><?php echo lang('Subscriptions'); ?></h4>

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
                <?= lang('subscriptions') . ' ' . lang('list') ?>

                <!-- ************** general *************-->
                <div class="table-responsive">
                    <table class="table table-striped DataTables" id="contentTable">
                        <thead>
                            <tr>
                                <th><?= lang('email') ?></th>
                                <th><?= lang('status') ?></th>
                                <th><?= lang('ip_address') ?></th>
                                <th><?= lang('user_agent') ?></th>
                                <th><?= lang('created_at') ?></th>
                                <th><?= lang('action') ?></th>
                            </tr>
                        </thead>
                        <?php /* ?><tbody id="pricing">
                            <?php
                            $all_subscriber = get_order_by('tbl_subscribers');
                            if (!empty($all_subscriber)) {
                                foreach ($all_subscriber as $subscriber) {
                                    if ($subscriber->status == '1') {
                                        $label = 'success';
                                        $status = lang('subscribed');
                                        $type = 0;
                                        $type_label = 'warning';
                                        $type_text = lang('un_subscribed');
                                    } else {
                                        $label = 'warning';
                                        $status = lang('un_subscribed');
                                        $type = 1;
                                        $type_label = 'success';
                                        $type_text = lang('subscribed');
                                    }
                                    ?>
                                    <tr class="pricing" id="table_pricing_<?= $subscriber->subscribers_id ?>">
                                        <td><?= $subscriber->email ?></td>
                                        <td><span class="label label-<?= $label ?>"> <?= $status ?></span></td>
                                        <td><?= $subscriber->ip ?></td>
                                        <td><?= $subscriber->user_agent ?></td>
                                        <td><?= display_datetime($subscriber->created_at) ?></td>
                                        <td>
                                            <a data-bs-toggle="tooltip" data-bs-placement="top"
                                            class="btn btn-outline-<?= $type_label ?> btn-sm"
                                            title="Click to <?= $type_text ?> "
                                            href="<?php echo base_url('admin/frontend/subscriber_action/' . $type . '/' . $subscriber->subscribers_id) ?>"><span
                                            class="fa fa-<?= ($subscriber->status == 0) ? 'check' : 'times' ?>"></span></a>
                                            <?php echo ajax_anchor(base_url("admin/frontend/delete/tbl_subscribers/$subscriber->subscribers_id"), "<i class=' fa fa-trash-o'></i>", array("class" => "btn btn-sm btn-outline-danger", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_pricing_" . $subscriber->subscribers_id)); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody><?php */ ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Script -->
 <script type="text/javascript">
     $(document).ready(function(){
        $('#contentTable').DataTable({
          'processing': true,
          'serverSide': true,
          'serverMethod': 'post',
          'ajax': {
             'url':'<?=base_url()?>admin/datatable/subscriber'
          },
          'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
            $(nRow).attr("id", "table_pricing_"+iDisplayIndex);
            $(nRow).addClass("pricing");
            $('td:eq(6)', nRow).css("display","none");
            return nRow;
          },
          'columns': [
             { data: 'email' },
             { data: 'status' },
             { data: 'ip' },
             { data: 'user_agent' },
             { data: 'created_at' },
             { data: 'action' },
          ]
        });
     });
 </script>