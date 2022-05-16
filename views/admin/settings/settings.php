
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
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <?php
                    $can_do = can_do(111);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 settings <?php echo ($load_setting == 'general') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings">
                                <i class="fa fa-fw fa-info-circle"></i>
                                <?php echo lang('company_details') ?>
                        </a>
                    <?php }
                    $can_do = can_do(112);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 system <?php echo ($load_setting == 'system') || ($load_setting == 'all_currency') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/system">
                                <i class="fa fa-fw fa-desktop"></i>
                                <?php echo lang('system_settings') ?>
                        </a>
                    <?php }
                    $can_do = can_do(113);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 email <?php echo ($load_setting == 'email_settings') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/email">
                                <i class="fa fa-fw fa-envelope"></i>
                                <?php echo lang('email_settings') ?>
                        </a>
                    <?php }
                    $can_do = can_do(114);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 templates <?php echo ($load_setting == 'templates') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/templates">
                                <i class="fa fa-fw fa-pencil-square"></i>
                                <?php echo lang('email_templates') ?>
                        </a>
                    <?php }
                    $can_do = can_do(115);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 email_integration <?php echo ($load_setting == 'email_integration') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/email_integration">
                                <i class="fa fa-fw fa-envelope-o"></i>
                                <?php echo lang('email_integration') ?>
                            </a>
                    <?php }
                    $can_do = can_do(116);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 payments <?php echo ($load_setting == 'payments') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/payments">
                                <i class="fa fa-fw fa-dollar"></i>
                                <?php echo lang('payment_settings') ?>
                            </a>
                    <?php }
                    $can_do = can_do(117);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 invoice <?php echo ($load_setting == 'invoice') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/invoice">
                                <i class="fa fa-fw fa-money"></i>
                                <?php echo lang('invoice_settings') ?>
                            </a>
                    <?php }
                    $can_do = can_do(141);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 estimate <?php echo ($load_setting == 'estimate') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/estimate">
                                <i class="fa fa-fw fa-file-o"></i>
                                <?php echo lang('estimate_settings') ?>
                            </a>
                    <?php }
                    $can_do = can_do(151);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 proposals <?php echo ($load_setting == 'proposals') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/proposals">
                                <i class="fa fa-fw fa-leaf"></i>
                                <?php echo lang('proposals_settings') ?>
                            </a>
                    <?php }
                    $can_do = can_do(152);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 purchase <?php echo ($load_setting == 'purchase') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/purchase">
                                <i class="fa fa-fw fa-truck"></i>
                                <?php echo lang('purchase_settings') ?>
                            </a>
                    <?php }
                    $can_do = can_do(153);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 manufacturer <?php echo ($load_setting == 'manufacturer') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/manufacturer">
                                <i class="fa fa-fw fa-empire"></i>
                                <?php echo lang('manufacturer') ?>
                            </a>
                    <?php }
                    $can_do = can_do(119);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 tickets <?php echo ($load_setting == 'tickets') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/tickets">
                                <i class="fa fa-fw fa-ticket"></i>
                                <?php echo lang('tickets_leads_settings') ?>
                            </a>
                    <?php }
                    $can_do = can_do(120);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 theme <?php echo ($load_setting == 'theme') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/theme">
                                <i class="fa fa-fw fa-code"></i>
                                <?php echo lang('theme_settings') ?>
                            </a>
                    <?php }
                    $can_do = can_do(145);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 dashboard <?php echo ($load_setting == 'dashboard') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/dashboard">
                                <i class="fa fa-fw fa-dashboard"></i>
                                <?php echo lang('dashboard_settings') ?>
                            </a>
                    <?php }
                    $can_do = can_do(121);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 working_days <?php echo ($load_setting == 'working_days') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/working_days">
                                <i class="fa fa-fw fa-calendar"></i>
                                <?php echo lang('working_days') ?>
                            </a>
                    <?php }
                    $can_do = can_do(122);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 leave_category <?php echo ($load_setting == 'leave_category') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/leave_category">
                                <i class="fa fa-fw fa-pagelines"></i>
                                <?php echo lang('leave_category') ?>
                            </a>
                    <?php }
                    $can_do = can_do(123);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 income_category <?php echo ($load_setting == 'income_category') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/income_category">
                                <i class="fa fa-fw fa-certificate"></i>
                                <?php echo lang('income_category') ?>
                            </a>
                    <?php }
                    $can_do = can_do(124);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 expense_category <?php echo ($load_setting == 'expense_category') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/expense_category">
                                <i class="fa fa-fw fa-tasks"></i>
                                <?php echo lang('expense_category') ?>
                            </a>
                    <?php }
                    $can_do = can_do(125);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 customer_group <?php echo ($load_setting == 'customer_group') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/customer_group">
                                <i class="fa fa-fw fa-users"></i>
                                <?php echo lang('customer_group') ?>
                            </a>
                    <?php }
                    $can_do = can_do(127);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 lead_status <?php echo ($load_setting == 'lead_status') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/lead_status">
                                <i class="fa fa-fw fa-list-ul"></i>
                                <?php echo lang('lead_status') ?>
                            </a>
                    <?php }
                    $can_do = can_do(128);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 lead_source <?php echo ($load_setting == 'lead_source') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/lead_source">
                                <i class="fa fa-fw fa-arrow-down"></i>
                                <?php echo lang('lead_source') ?>
                            </a>
                    <?php }
                    $can_do = can_do(129);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 opportunities_state_reason <?php echo ($load_setting == 'opportunities_state_reason') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/opportunities_state_reason">
                                <i class="fa fa-fw fa-dot-circle-o"></i>
                                <?php echo lang('opportunities_state_reason') ?>
                            </a>
                    <?php } ?>
                     <a class="nav-link mb-2 allowed_ip <?php echo ($load_setting == 'allowed_ip') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/allowed_ip">
                                <i class="fa fa-fw fa-server"></i>
                                <?php echo lang('allowed_ip') ?>
                            </a>
                    <?php $can_do = can_do(130);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 custom_field <?php echo ($load_setting == 'custom_field') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/custom_field">
                                <i class="fa fa-fw fa-star-o "></i>
                                <?php echo lang('custom_field') ?>
                            </a>
                    <?php }
                    $can_do = can_do(131);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 payment_method <?php echo ($load_setting == 'payment_method') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/payment_method">
                                <i class="fa fa-fw fa-money"></i>
                                <?php echo lang('payment_method') ?>
                            </a>
                    <?php }
                    $can_do = can_do(132);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 cronjob <?php echo ($load_setting == 'cronjob') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/cronjob">
                                <i class="fa fa-fw fa-contao"></i>
                                <?php echo lang('cronjob') ?>
                            </a>
                    <?php }
                    $can_do = can_do(133);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 menu_allocation <?php echo ($load_setting == 'menu_allocation') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/menu_allocation">
                                <i class="fa fa-fw fa fa-compass"></i>
                                <?php echo lang('menu_allocation') ?>
                            </a>
                    <?php }
                    $can_do = can_do(134);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 notification <?php echo ($load_setting == 'notification') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/notification">
                                <i class="fa fa-fw fa-bell-o"></i>
                                <?php echo lang('notification') ?>
                            </a>
                    <?php }
                    $can_do = can_do(135);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 email_notification <?php echo ($load_setting == 'email_notification') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/email_notification">
                                <i class="fa fa-fw fa-bell-o"></i>
                                <?php echo lang('email_notification') ?>
                            </a>
                    <?php }
                    $can_do = can_do(136);
                    if (!empty($can_do)) { ?>
                        <a class="nav-link mb-2 database_backup <?php echo ($load_setting == 'database_backup') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/database_backup">
                                <i class="fa fa-fw fa-database"></i>
                                <?php echo lang('database_backup') ?>
                            </a>
                    <?php }
                    $can_do = super_admin();
                    $is_subdomain = is_subdomain();
                    if (!empty($can_do && !$is_subdomain)) { ?>
                        <a class="nav-link mb-2 translations <?php echo ($load_setting == 'translations') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/translations">
                                <i class="fa fa-fw fa-language"></i>
                                <?php echo lang('translations') ?>
                            </a>
                            <a class="nav-link mb-2 translations <?php echo ($load_setting == 'translations_industry') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/translations_industry">
                                <i class="fa fa-fw fa-language"></i>
                                <?php echo lang('industry wise translations') ?>
                            </a>
                    <?php }
                    $can_do = super_admin();
                    if (!empty($can_do) && $can_do == 'owner') { ?>
                        <a class="nav-link mb-2 system_update <?php echo ($load_setting == 'system_update') ? 'active' : ''; ?>" href="<?= base_url() ?>admin/settings/system_update">
                                <i class="fa fa-fw fa-pencil-square-o"></i>
                                <?php echo lang('system_update') ?>
                            </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <!-- Tabs within a box -->
        <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
            <?php if ($load_setting == 'email') { ?>
            <div style="margin-bottom: 10px;margin-left: -15px" class="<?php
                if ($load_setting != 'email') {
                    echo 'hidden';
                }
                ?>">
                <div class="card">
                    <div class="card-body">    
                        <a href="<?= base_url() ?>admin/settings/email&view=alerts" class="btn btn-info"><i class="fa fa fa-inbox text"></i>
                            <span class="text"><?php echo lang('alert_settings') ?></span>
                        </a>
                    </div>
                </div>
            </div>
            <?php } ?>

            <!-- Load the settings form in views -->
            <div class="row <?=$load_setting;?>">
                <?php $this->load->view('admin/settings/' . $load_setting) ?>
            </div>
            <!-- End of settings Form -->
        </div>
    </div>
</div>
