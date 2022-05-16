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
            if ($trial_period < 3) {
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