<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"><?php echo $this->breadcrumbs->build_breadcrumbs(); ?></h4>

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

            <div class="table-responsive panel-body">
                 <table id="contentTable" class="table table-striped dt-responsive nowrap w-100 DataTables">
                    <thead>
                        <tr>
                            <th><?= lang('name') ?></th>
                            <th><?= lang('email') ?></th>
                            <th><?= lang('phone') ?></th>            
                            <th><?= lang('subject') ?></th>
                            <th><?= lang('message') ?></th>
                            <th class="col-options no-sort"><?= lang('action') ?></th>
                        </tr>
                    </thead>
                    <?php /* ?><tbody id="pricing">
                        <?php
                        $all_quote_request = get_order_by('tbl_request_quote', null, 'id');
                        if (!empty($all_quote_request)) {
                            foreach ($all_quote_request as $quote_request) {
                                ?>
                                <tr class="pricing" id="table_pricing_<?= $quote_request->id ?>">
                                    <td class=""><?= $quote_request->name ?></td>
                                    <td><?= $quote_request->email ?></td>
                                    <td><?= $quote_request->phone ?></td>
                                    <td><?= $quote_request->subject	?></td>                        
                                    <td><?= $quote_request->message ?></td>
                                    <td>
                                        <?php echo ajax_anchor(base_url("admin/frontend/delete/tbl_request_quote/$quote_request->id"), "<i class='btn btn-outline-danger btn-sm fa fa-trash-o' style='height:26px;'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-child-on-success" => ".child", "data-fade-out-on-success" => "#table_pricing_" . $quote_request->id)); ?>
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
             'url':'<?=base_url()?>admin/datatable/quote_request'
          },
          'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
            $(nRow).attr("id", "table_pricing_"+iDisplayIndex);
            $(nRow).attr("data-id", aData['id']);
            $(nRow).addClass("pricing");
            $('td:eq(6)', nRow).css("display","none");
            return nRow;
          },
          'columns': [
             { data: 'name' },
             { data: 'email' },
             { data: 'phone' },
             { data: 'subject' },
             { data: 'message' },
             { data: 'action' },
          ]
        });
     });
 </script>