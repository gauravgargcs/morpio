<div class="column col-md-5 col-sm-6 col-xs-12">
    <div class="panel panel-info border-0">
        <div class="panel-heading u-radius-10 custom-bg">
            <strong><?= $plan_info->name ?></strong>
            <small style="display: block"><?= $plan_info->description ?></small>
        </div>
        <div class="panel-body">
            <div class="contact-info" style="padding-left: 0px;margin-bottom: 20px;">
                <ul>
                    <?= pricing_format_register_YN($plan_info->multi_branch, lang('multi_branch')) ?>
                    <?php
                    $all_module = get_old_data('tbl_modules', array('active' => 1, 'module_name !=' => 'mailbox'), true);
                    if (!empty($all_module)) {
                        foreach ($all_module as $v_module) {
                            $name = 'allow_' . $v_module->module_name;
                            echo pricing_format_register_YN($plan_info->$name, lang($v_module->module_name));
                        }
                    }?>
                    <?= pricing_format_register($plan_info->employee_no, lang('Users')) ?>
                    <?= pricing_format_register($plan_info->disk_space, lang('disk_space')) ?>
                    <?php if($plan_info->trial_period>0){ ?>
                    <?= pricing_format_register($plan_info->trial_period, lang('days') . ' ' . lang('trail_period')) ?>
                      <?php } ?>
                    <?= pricing_format_register($plan_info->client_no, lang('Contacts')) ?>
                    <?= pricing_format_register($plan_info->project_no, lang('Projects')) ?>
                    <?= pricing_format_register($plan_info->invoice_no, lang('Invoices')) ?>
                    <?= pricing_format_register($plan_info->leads, lang('leads')) ?>
                    <?= pricing_format_register($plan_info->accounting, lang('accounting')) ?>
                    <?= pricing_format_register($plan_info->bank_account, lang('bank') . ' ' . lang('account')) ?>
                    <?= pricing_format_register($plan_info->tasks, lang('tasks')) ?>
                    <?= pricing_format_register_YN($plan_info->online_payment, lang('Online payments')) ?>
                    <?= pricing_format_register_YN($plan_info->mailbox, lang('mailbox')) ?>
                    <?= pricing_format_register_YN($plan_info->live_chat, lang('live_chat')) ?>
                    <?= pricing_format_register_YN($plan_info->tickets, lang('tickets')) ?>
                    <?= pricing_format_register_YN($plan_info->recruitment, lang('job_circular')) ?>
                    <?= pricing_format_register_YN($plan_info->attendance, lang('attendance')) ?>
                    <?= pricing_format_register_YN($plan_info->payroll, lang('payroll')) ?>
                    <?= pricing_format_register_YN($plan_info->leave_management, lang('leave_management')) ?>
                    <?= pricing_format_register_YN($plan_info->performance, lang('Performance Tracking')) ?>
                    <?= pricing_format_register_YN($plan_info->training, lang('training')) ?>
                    <?= pricing_format_register_YN($plan_info->reports, lang('report')) ?>
                </ul>
            </div>
        </div>
    </div>
</div>
