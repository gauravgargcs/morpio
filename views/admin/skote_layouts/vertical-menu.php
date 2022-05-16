<?php
$user_id = $this->session->userdata('user_id');
$profile_info = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
$user_info = $this->db->where('user_id', $user_id)->get('tbl_users')->row();
$display = config_item('logo_or_icon');
$user_role_id= $this->session->userdata('user_type');
$lang_info = $this->db->where('name', $this->session->userdata('lang'))->get('tbl_languages')->row();
$icon="us";
if(!empty($lang_info)){
    $icon = $lang_info->icon;
}

 ?>
<style type="text/css">
    .dropdown-menu{
        min-width: 10rem !important;
        padding: 0.5rem 10px !important;
    }
    .user-block-picture, .user-block-info{
        text-align: center;
    }
    .timer_badge{
        margin-top: 0px !important;
        padding: 0px !important ;
    }
    .dropdown-icon-item img{
        height: 60px !important;
    }
</style>
<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <?php if ($display == 'logo' || $display == 'logo_title') { ?>
                <a href="<?php echo base_url('admin/dashboard'); ?>" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="<?php echo base_url() . config_item('favicon'); ?>" alt="<?=config_item('company_name');?>" height="40">
                    </span>
                    <span class="logo-lg">
                        <img src="<?= base_url() . config_item('company_logo') ?>" alt="<?=config_item('company_name');?>" height="40">
                    </span>
                </a>

                <a href="<?php echo base_url('admin/dashboard'); ?>" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="<?php echo base_url() . config_item('favicon'); ?>" alt="<?=config_item('company_name');?>" height="40">
                    </span>
                    <span class="logo-lg">
                        <img src="<?= base_url() . config_item('company_logo') ?>" alt="<?=config_item('company_name');?>" height="40">
                    </span>
                </a>
                <?php } ?>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <!-- App Search-->
            <form class="app-search d-none d-lg-block">
                <div class="position-relative">
                    <input type="text" id="search_menu" class="form-control" placeholder="<?= lang('search_menu') ?>">
                    <span class="bx bx-search-alt"></span>
                </div>
            </form>

            <div class="dropdown dropdown-mega d-none d-lg-block ms-2">
                <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown" aria-haspopup="false" aria-expanded="false">
                    <span key="t-megamenu"><?php echo lang('Mega_Menu'); ?></span>
                    <i class="mdi mdi-chevron-down"></i>
                </button>
                <div class="dropdown-menu dropdown-megamenu">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <h5 class="font-size-14 mt-0" key="t-extra-pages"><?php echo lang('quick_links'); ?></h5>
                                    <ul class="list-unstyled megamenu-list">
                                        <li>
                                <a href="<?=site_url("admin/tasks/new_tasks");?>" title="<?=lang('new_task');?>">
                                   <?=lang('new_task');?></a>
                            </li>
                              <li>
                                <a href="<?=site_url("admin/client/manage_client/new");?>" title="<?=lang('new_client');?>">
                                   <?=lang('new_client');?></a>
                            </li>
                             <li>
                                <a href="<?=site_url("admin/leads/index/new");?>" title="<?=lang('new_lead');?>">
                                   <?=lang('new_lead');?></a>
                            </li>
                             <li>
                                <a href="<?=site_url("admin/projects/new_project");?>" title=" <?=lang('new_project');?>">
                                   <?=lang('new_project');?></a>
                            </li> <li>
                                <a href="<?=site_url("admin/dashboard/all_todo/new");?>" title="<?=lang('new_todo');?>">
                                   <?=lang('new_todo');?></a>
                            </li>
                             <li>
                                <a href="<?=site_url("admin/calendar/index/new");?>" title="<?=lang('new_calendar_event');?>">
                                   <?=lang('new_calendar_event');?></a>
                            </li>
                                    </ul>
                                </div>
                                <?php $super_admin = super_admin();
                                if (!empty($super_admin)) {
                                    $companies_id = $this->session->userdata('companies_id');
                                    $company_logo = $this->db->where(array('companies_id' => null, 'config_key' => 'company_logo'))->get('tbl_config')->row();
                                ?>
                                <div class="col-md-4">
                                    <h5 class="font-size-14 mt-0" key="t-extra-pages">
                                        <?php
                                        if (!empty($companies_id)) {
                                            echo company_name($companies_id);
                                        } else {
                                            echo lang('companies');
                                        }
                                        ?>
                                    </h5>
                                    <ul class="list-unstyled megamenu-list">
                                        <li>
                                            <a href="<?= base_url() ?>admin/global_controller/set_companies"
                                               title="<?= lang('all') . ' ' . lang('companies') ?>">
                                                <?= lang('all') . ' ' . lang('companies') ?>
                                            </a>
                                        </li>

                                        <?php
                                        $all_companies = $this->db->where('status', 1)->get('tbl_companies')->result();
                                        foreach ($all_companies as $companies) :
                                            ?>
                                            <li class="<?= (!empty($companies_id) && $companies_id == $companies->companies_id ? 'mm-active' : '') ?>">
                                                <a href="<?= base_url() ?>admin/global_controller/set_companies/<?= $companies->companies_id ?>"
                                                   title="<?= $companies->name ?>">
                                                     <?= $companies->name ?>
                                                </a>
                                            </li>
                                            <?php
                                        endforeach;
                                        ?>
                                    </ul>
                                </div>
                                <?php } ?>
                                <div class="col-md-4">
                                    <div>
                                        <img src="<?php echo base_url('skote_assets/images/megamenu-img.png'); ?>" alt="" class="img-fluid mx-auto d-block" width="50%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (config_item('enable_languages') == 'TRUE') { ?>

        <div class="d-flex">
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="<?php echo base_url('skote_assets/images/flags/'.$icon.'.gif'); ?>" alt="Header Language" height="16">
                  
                       <!--  <img src="<?php echo base_url('skote_assets/images/flags/spain.jpg'); ?>" alt="Header Language" height="16">
                   
                        <img src="<?php echo base_url('skote_assets/images/flags/germany.jpg'); ?>" alt="Header Language" height="16">
                    
                        <img src="<?php echo base_url('skote_assets/images/flags/italy.jpg'); ?>" alt="Header Language" height="16">
                   
                        <img src="<?php echo base_url('skote_assets/images/flags/russia.jpg'); ?>" alt="Header Language" height="16"> -->
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <ul class="list-unstyled">
                        <?php
                        $languages = $this->db->where('active', 1)->order_by('name', 'ASC')->get('tbl_languages')->result();
                        foreach ($languages as $lang) :
                            ?>
                            <li>
                                <!-- item-->
                                <a href="<?= base_url() ?>admin/global_controller/set_language/<?= $lang->name ?>"
                                               title="<?= ucwords(str_replace("_", " ", $lang->name)) ?>" class="dropdown-item notify-item language" data-lang="<?= $lang->name ?>">
                                    <img src="<?php echo base_url(); ?>skote_assets/images/flags/<?= $lang->icon ?>.gif" alt="<?= ucwords(str_replace("_", " ", $lang->name)) ?>" class="me-1" height="12"> <span class="align-middle"> <?= ucwords(str_replace("_", " ", $lang->name)) ?></span>
                                </a>
                                <!-- item-->
                            </li>
                            <?php
                        endforeach;
                        ?>
                    </ul>
                </div>
            </div>

            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bx bx-customize"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end" style="width:550px !important">
                    <div class="px-lg-2">
                        <div class="row g-0">
                            <div class="col">
                                <a class="dropdown-icon-item" href="https://allbizvideo.com/" target="_blank">
                                    <img src="<?php echo base_url('skote_assets/images/brands/video.png'); ?>" alt="Github">
                                    <span><strong><?=lang('Video_Meetings')?></strong></span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="https://campaigns.merpio.com/" target="_blank">
                                    <img src="<?php echo base_url('skote_assets/images/brands/email.png'); ?>" alt="bitbucket">
                                    <span><strong><?=lang('Email_Campaigns')?></strong></span>
                                </a>
                            </div>
                           
                        </div>

                        <div class="row g-0">
                             <div class="col">
                                <a class="dropdown-icon-item" href="https://socials.merpio.com/" target="_blank">
                                    <img src="<?php echo base_url('skote_assets/images/brands/social.png'); ?>" alt="dribbble">
                                    <span><strong><?=lang('Social_Media_Campaigns')?></strong></span>
                                </a>
                            </div>
                          <!--   <div class="col">
                                <a class="dropdown-icon-item" href="https://allbizproposals.com/" target="_blank">
                                    <img src="<?php echo base_url('skote_assets/images/brands/proposal.png'); ?>" alt="dropbox">
                                    <span><strong><?=lang('Proposal_Software')?></strong></span>
                                </a>
                            </div> -->
                            <div class="col">
                                <a class="dropdown-icon-item" href="https://www.thedocroom.com" target="_blank">
                                    <img src="<?php echo base_url('skote_assets/images/brands/doc.png'); ?>" alt="mail_chimp">
                                    <span><strong><?=lang('Document_Management')?></strong></span>
                                </a>
                            </div>
                            <!-- <div class="col">
                                <a class="dropdown-icon-item" href="https://allbizwebsites.com/" target="_blank">
                                    <img src="<?php echo base_url('skote_assets/images/brands/website.png'); ?>" alt="slack">
                                    <span><strong><?=lang('Website_Builder')?></strong></span>
                                </a>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>


            <div class="dropdown d-inline-block">
                <?php $this->load->view('admin/skote_layouts/notifications'); ?>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="<?= base_url() . $profile_info->avatar ?>" alt="User Image">
                    <span class="d-none d-xl-inline-block ms-1" key="t-henry"><?= $profile_info->fullname ?></span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <ul class="list-unstyled">
                        <li>
                            <p>
                                <?= $profile_info->fullname ?>
                                <br>
                                <small><?= lang('last_login') . ':' ?>
                                    <?php
                                    if ($user_info->last_login == '0000-00-00 00:00:00') {
                                        $login_time = "-";
                                    } else {
                                        $login_time = display_datetime($user_info->last_login);
                                    }
                                    echo $login_time;
                                    ?>
                                </small>
                            </p>
                        </li>
                        <div class="dropdown-divider"></div>
                        
                        <!-- Menu Body -->
                        <li>
                            <a class="dropdown-item" href="<?= base_url() ?>admin/settings/activities"><i class="bx bx-wallet font-size-16 align-middle me-1"></i> <span key="t-profile"><?php echo lang('activities'); ?></span></a>
                        </li>

                        <?php
                        $admin = admin();
                        $subdomain = is_subdomain();
                        if (!empty($admin) && !empty($subdomain)) {
                            ?>
                            <li>
                                <a class="dropdown-item" href="<?= base_url('subscription_details') ?>"><i class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile"><?php echo lang('subscriptions'); ?></span></a>
                            </li>
                        <?php } else {
                            ?>
                            <li>
                                <a class="dropdown-item" href="<?= base_url('admin/user/user_details/' . $user_id) ?>"><i class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile"><?php echo lang('my_details'); ?></span></a>
                            </li>
                        <?php } ?>
                        <li>
                            <a class="dropdown-item" href="<?= base_url() ?>locked/lock_screen"><i class="bx bx-lock-open font-size-16 align-middle me-1"></i> <span key="t-profile"><?php echo lang('lock_screen'); ?></span></a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="<?= base_url() ?>admin/settings/update_profile"><i class="bx bx-receipt font-size-16 align-middle me-1"></i> <span key="t-profile"><?php echo lang('update_profile'); ?></span></a>
                        </li>
                      
                        <div class="dropdown-divider"></div>

                        <!-- Menu Footer-->
                        <li class="dropdown-item">
                            
                            <form method="post" action="<?= base_url() ?>login/logout"
                                  class="form-horizontal">

                                <input type="hidden" name="clock_time" value="" id="time">
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-danger btn-sm btn-rounded waves-effect waves-light"><i class="bx bx-power-off font-size-16 align-middle me-1"></i> <span key="t-logout"><?php echo lang('Logout'); ?></span></button>
                                </div>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="dropdown d-none">
                <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                    <i class="bx bx-cog bx-spin"></i>
                </button>
            </div>
        </div>

        <?php } ?>

    </div>
</header>

<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
             <!-- START sidebar nav-->
            <ul class="list-unstyled">
                <!-- START user info-->
                <li class="has-user-block">
                    <a href="<?= base_url('admin/user/user_details/' . $user_id) ?>">
                        <div id="user-block" class="block">
                            <div class="item user-block">
                                <!-- User picture-->
                                <div class="user-block-picture">
                                    <div class="user-block-status">
                                        <?php 
                                        $img = base_url() . $profile_info->avatar;
                                        if ($profile_info->avatar): ?>
                                            <img src="<?php echo base_url() . $profile_info->avatar; ?>"  alt="Employee Image" width="60" height="60" class="img-thumbnail img-circle">
                                        <?php else: ?>
                                            <img src="<?php echo base_url() ?>skote_assets/images/users/01.png" alt="Avatar" width="60" height="60"  class="img-thumbnail img-circle">
                                        <?php endif; ?>
                                        <div class="circle circle-success circle-lg"></div>
                                    </div>
                                </div>
                                <br/>
                                <!-- Name and Job-->
                                <div class="user-block-info">
                                    <span class="user-block-name"><?= $profile_info->fullname ?></span><br>
                                    <span class="user-block-role text-success"></i> <?= lang('online') ?></span>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
       
            <!-- END user info-->
            
            <ul class="metismenu list-unstyled myUL" id="side-menu">
            <?php $super_admin = super_admin();
            if (!empty($super_admin)) { ?>
                <!-- Left Menu Start -->
                <?php if ($super_admin === 'owner') { ?>
                <li class="menu-title" key="t-menu"><?= lang('frontend') . ' ' . lang('management') ?></li>
                <li>
                    <a href="javascript://" class="has-arrow waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards"><?php echo lang('frontend'); ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        
                        <li class="<?= ($this->uri->segment(3) == 'quote_request') ? 'mm-active' : '' ?>">
                            <a title="<?= lang('header') ?>" href="<?= base_url('admin/frontend/quote_request') ?>">
                                <i class="fa fa-circle-o"></i><span><?= lang('quote_request') ?></span>
                            </a>
                        </li>
                       
                        
                        <li class="<?= ($this->uri->segment(3) == 'pricing'  || $this->uri->segment(3) == 'pricing_add') ? 'mm-active' : '' ?>">
                            <a title="<?= lang('pricing') ?>"  href="<?= base_url('admin/frontend/pricing') ?>">
                                <i class="fa fa-circle-o"></i><span><?= lang('pricing') ?></span>
                            </a>
                        </li>
                        <li class="<?= ($this->uri->segment(1) == 'NewPlan') ? 'mm-active' : '' ?>">
                            <a title="<?= lang('assign_plan') ?>" href="<?= base_url('NewPlan') ?>">
                                <i class="fa fa-circle-o"></i><span><?= lang('assign_plan') ?></span>
                            </a>
                        </li>
                        <li class="<?= ($this->uri->segment(3) == 'coupon') ? 'mm-active' : '' ?>">
                            <a title="<?= lang('coupon') ?>" href="<?= base_url('admin/frontend/coupon') ?>">
                                <i class="fa fa-circle-o"></i><span><?= lang('coupon') ?></span>
                            </a>
                        </li>
                        <li class="<?= ($this->uri->segment(3) == 'subscriptions') ? 'mm-active' : '' ?>">
                            <a title="<?= lang('resources') ?>" href="<?= base_url('admin/frontend/subscriptions') ?>">
                                <i class="fa fa-circle-o"></i><span><?= lang('subscriptions') ?></span>
                            </a>
                        </li>
                        <li class="<?= ($this->uri->segment(3) == 'subscriber') ? 'mm-active' : '' ?>">
                            <a title="<?= lang('resources') ?>" href="<?= base_url('admin/frontend/subscriber') ?>">
                                <i class="fa fa-circle-o"></i><span><?= lang('subscriber') ?></span>
                            </a>
                        </li>
                      
                        <li class="<?= ($this->uri->segment(3) == 'team') ? 'mm-active' : '' ?>">
                            <a title="<?= lang('team') ?>" href="<?= base_url('admin/frontend/team') ?>">
                                <i class="fa fa-circle-o"></i><span><?= lang('our_team') ?></span>
                            </a>
                        </li>

                        <li class="<?= ($this->uri->segment(2) == 'version') ? 'mm-active' : '' ?>">
                            <a title="<?= lang('version_logs') ?>" href="<?= base_url('admin/version/index') ?>">
                                <i class="fa fa-circle-o"></i><span><?= lang('version_logs') ?></span>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php } ?>
            
                <li>
                    <a title="<?= lang('manage') . ' ' . lang('company') ?>" href="<?= base_url() ?>admin/companies" class="waves-effect">
                        <i class="bx bx-detail"></i>
                        <span key="t-dashboards"><?= lang('manage') . ' ' . lang('branches') ?></span>
                    </a>
                </li>
              
            <?php } 
                $super_admin = super_admin();
                 $is_admin = admin();
                  $is_subdomain = is_subdomain();
        if (!$is_subdomain && (!empty($super_admin) || !empty($is_admin) ) ) { ?>
                <li>
                   
                    <a title="<?= lang('manage') . ' ' . lang('tenant') ?>" href="<?= base_url() ?>admin/frontend/tenant" class="waves-effect">
                        <i class="bx bx-detail"></i>
                        <span key="t-dashboards"><?= lang('manage') . ' ' . lang('tenant') ?></span>
                    </a>
                </li>
           
        <?php } ?>
          <li>
                   
                    <a title="<?= lang('Listings'); ?>" href="<?= base_url() ?>admin/listings" class="waves-effect">
                        <i class="bx bx-detail"></i>
                        <span key="t-dashboards"><?=  lang('Listings'); ?></span>
                    </a>
                </li>
            <?php

            echo $this->menu->dynamicMenu(1);

            $all_pinned_details = $this->db->where('user_id', $this->session->userdata('user_id'))->get('tbl_pinaction')->result();
            if (!empty($all_pinned_details)) {
                foreach ($all_pinned_details as $v_pinned_details) {
                    $pinned_details[$v_pinned_details->module_name] = $this->db->where('pinaction_id', $v_pinned_details->pinaction_id)->get('tbl_pinaction')->result();
                }
            }
            if (!empty($pinned_details)) { ?>
                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="fas fa-map-pin"></i><span class="badge rounded-pill bg-info float-end"><?= count($all_pinned_details) ?></span>
                        <span key="t-dashboards"><?= lang('pinned') . ' ' . lang('list') ?></span>
                    </a>
                <?php foreach ($pinned_details as $module => $v_module_info) {
                    if (!empty($v_module_info)) { ?>
                    <ul class="sub-menu" aria-expanded="false">

                    <?php foreach ($v_module_info as $v_module) { ?>
                            <?php if ($v_module->module_name == 'project') {
                                $project_info = $this->db->where('project_id', $v_module->module_id)->get('tbl_project')->row();
                                if (!empty($project_info)) {
                                    $progress = $this->items_model->get_project_progress($project_info->project_id);
                                    ?>
                                    <li class="pinned_list">
                                        <a title="<?= lang('pinned') . ' ' . lang($module) ?>" href="<?= base_url() ?>admin/projects/project_details/<?= $project_info->project_id ?>"><span style="font-size: 12px;"><?= $project_info->project_name ?></span>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar <?php echo ($progress >= 100) ? 'bg-success' : ''; ?>" role="progressbar" style="width: <?= $progress ?>%;" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </a>

                                    </li>
                                <?php } ?>
                            <?php } ?>
                            <?php if ($v_module->module_name == 'tasks') {
                                $task_info = $this->db->where('task_id', $v_module->module_id)->get('tbl_task')->row();
                                if (!empty($task_info)) {
                                    ?>
                                    <li class="pinned_list mb">
                                        <a title="<?= lang('pinned') . ' ' . lang($module) ?>" href="<?= base_url() ?>admin/tasks/view_task_details/<?= $task_info->task_id ?>"><span style="font-size: 12px;"><?= $task_info->task_name ?></span>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar <?php echo ($task_info->task_progress >= 100) ? 'bg-success' : ''; ?>" role="progressbar" style="width: <?= $task_info->task_progress ?>%;" aria-valuenow="<?= $task_info->task_progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </a>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                            <?php if ($v_module->module_name == 'invoice') {
                                $invoice_info = $this->db->where('invoices_id', $v_module->module_id)->get('tbl_invoices')->row();
                                if (!empty($invoice_info)) {
                                    ?>
                                    <li class="pinned_list mb">
                                        <a title="<?= lang('pinned') . ' ' . lang($module) ?>" href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $invoice_info->invoices_id ?>"><?= $invoice_info->reference_no ?>
                                        <?php
                                        $payment_status = $this->invoice_model->get_payment_status($invoice_info->invoices_id);
                                        if (strtotime($invoice_info->due_date) < time() AND $payment_status != lang('fully_paid')) {
                                            $text = 'text-danger';
                                        } else {
                                            $text = '';
                                        } ?>
                                            <div style="font-size: 8px;margin-top: -3px">
                                                <?= lang('overdue') ?> : <span class="<?= $text ?>"><?= display_datetime($invoice_info->due_date) ?></span>          
                                            </div>
                                        </a>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                            <?php if ($v_module->module_name == 'estimates') {
                                $estimates_info = $this->db->where('estimates_id', $v_module->module_id)->get('tbl_estimates')->row();
                                if (!empty($estimates_info)) {
                                    ?>
                                    <li class="pinned_list mb">
                                        <a title="<?= lang('pinned') . ' ' . lang($module) ?>" href="<?= base_url() ?>admin/estimates/index/estimates_details/<?= $estimates_info->estimates_id ?>"><span style="font-size: 12px;"><?= $estimates_info->reference_no ?></span>

                                            <?php
                                            if (strtotime($estimates_info->due_date) < time() AND $estimates_info->status == 'Pending') {
                                                $text = 'text-danger';
                                            } else {
                                                $text = '';
                                            } ?>
                                            <div style="font-size: 8px;margin-top: -3px">
                                                <?= lang('expired') ?> : <span class="<?= $text ?>"><?= display_datetime($estimates_info->due_date) ?></span>          
                                            </div>
                                        </a>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                            <?php if ($v_module->module_name == 'tickets') {
                                $tickets_info = $this->db->where('tickets_id', $v_module->module_id)->get('tbl_tickets')->row();
                                if (!empty($tickets_info)) {
                                    if ($tickets_info->status == 'open') {
                                        $s_label = 'danger';
                                    } elseif ($tickets_info->status == 'closed') {
                                        $s_label = 'success';
                                    } else {
                                        $s_label = 'default';
                                    }
                                    ?>
                                    <li class="pinned_list mb">
                                        <a title="<?= lang('pinned') . ' ' . lang($module) ?>" href="<?= base_url() ?>admin/tickets/index/tickets_details/<?= $tickets_info->tickets_id ?>"><span style="font-size: 12px;"><?= $tickets_info->subject ?></span>
                                            <div style="font-size: 8px;margin-top: -3px">
                                                <?= lang('status') ?> : <span class="text-<?= $s_label ?>"><?= lang($tickets_info->status) ?></span>          
                                            </div>
                                        </a>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                            <?php if ($v_module->module_name == 'leads') {
                                $leads_info = $this->db->where('leads_id', $v_module->module_id)->get('tbl_leads')->row();
                                if (!empty($leads_info)) {
                                    $lead_status = $this->db->where('lead_status_id', $leads_info->lead_status_id)->get('tbl_lead_status')->row();
                                    ?>
                                    <li class="pinned_list mb">
                                        <a title="<?= lang('pinned') . ' ' . lang($module) ?>" href="<?= base_url() ?>admin/leads/leads_details/<?= $leads_info->leads_id ?>"><span style="font-size: 12px;"><?= $leads_info->lead_name ?></span>
                                            <div style="font-size: 8px;margin-top: -3px">
                                                <?= lang('status') ?> : <span><?= $lead_status->lead_status ?></span>         
                                            </div>
                                        </a>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                            <?php if ($v_module->module_name == 'bugs') {
                                $bugs_info = $this->db->where('bug_id', $v_module->module_id)->get('tbl_bug')->row();
                                if (!empty($bugs_info)) {
                                    $reporter = $this->db->where('user_id', $bugs_info->reporter)->get('tbl_users')->row();
                                    if ($bugs_info->bug_status == 'unconfirmed') {
                                        $b_label = 'warning';
                                    } elseif ($bugs_info->bug_status == 'confirmed') {
                                        $b_label = 'info';
                                    } elseif ($bugs_info->bug_status == 'in_progress') {
                                        $b_label = 'primary';
                                    } else {
                                        $b_label = 'success';
                                    }
                                    ?>
                                    <li class="pinned_list mb">
                                        <a title="<?= lang('pinned') . ' ' . lang($module) ?>" href="<?= base_url() ?>admin/bugs/view_bug_details/<?= $bugs_info->bug_id ?>"><span style="font-size: 12px;"><?= $bugs_info->bug_title ?></span>
                                            <div style="font-size: 8px;margin-top: -3px">
                                                <?= lang('status') ?> : <span class="text-<?= $b_label ?>"><?= lang("$bugs_info->bug_status") ?></span>          
                                            </div>
                                        </a>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                    <?php }
                }
            } ?>

            
            <!-- Iterates over all sidebar items-->
            <?php
            $this->db->select("tbl_project.*", FALSE);
            $this->db->select("tbl_users.*", FALSE);
            $this->db->select("tbl_account_details.*", FALSE);
            $this->db->join('tbl_users', 'tbl_users.user_id = tbl_project.timer_started_by');
            $this->db->join('tbl_account_details', 'tbl_account_details.user_id = tbl_project.timer_started_by');
            $this->db->where(array('timer_status' => 'on'));
            $project_timers = $this->db->get('tbl_project')->result_array();

            $this->db->select("tbl_task.*", FALSE);
            $this->db->select("tbl_users.*", FALSE);
            $this->db->select("tbl_account_details.*", FALSE);
            $this->db->join('tbl_users', 'tbl_users.user_id = tbl_task.timer_started_by');
            $this->db->join('tbl_account_details', 'tbl_account_details.user_id = tbl_task.timer_started_by');
            $this->db->where(array('timer_status' => 'on'));
            $task_timers = $this->db->get('tbl_task')->result_array();

            $user_id = $this->session->userdata('user_id');
            $role = get_staff_details($user_id);
            //$this->admin_model->check_by(array('user_id' => $user_id), 'tbl_users');
            ?>

            <?php if (!empty($project_timers)): ?>
                <li class="menu-title" key="t-menu"><?= lang('project') . ' ' . lang('start') ?></li>
                <?php foreach ($project_timers as $p_timer) : if ($role->role_id == 1 || ($role->role_id == 2 && $user_id == $p_timer['user_id'])) : ?>

                    <li class="active mb-sm" start="<?php echo $p_timer['timer_status']; ?>">
                        
                        <a title="<?php echo $p_timer['project_name'] . " (" . $p_timer['username'] . ")"; ?>" href="<?= base_url() ?>admin/projects/project_details/<?= $p_timer['project_id'] ?>" class="waves-effect">
                            <img src="<?= base_url() . $p_timer['avatar'] ?>" width="30" height="30" class="img-thumbnail img-circle">
                            <span class="timer_badge badge rounded-pill bg-danger float-end"><i class="fas fa-clock fa-pulse fa-spin"></i></span>
                            <span id="project_hour_timer_<?= $p_timer['project_id'] ?>"> 0 </span>
                            <!-- SEPARATOR -->
                            :
                            <!-- MINUTE TIMER -->
                            <span id="project_minute_timer_<?= $p_timer['project_id'] ?>"> 0 </span>
                            <!-- SEPARATOR -->
                            :
                            <!-- SECOND TIMER -->
                            <span id="project_second_timer_<?= $p_timer['project_id'] ?>"> 0 </span>
                        </a>
                    </li>
                <?php
                //RUNS THE TIMER IF ONLY TIMER_STATUS = 1
                if ($p_timer['timer_status'] == 'on') :

                $project_current_moment_timestamp = strtotime(date("H:i:s"));
                $project_timer_starting_moment_timestamp = $this->db->get_where('tbl_project', array('project_id' => $p_timer['project_id']))->row()->start_time;
                $project_total_duration = $project_current_moment_timestamp - $project_timer_starting_moment_timestamp;

                $project_total_hour = intval($project_total_duration / 3600);
                $project_total_duration -= $project_total_hour * 3600;
                $project_total_minute = intval($project_total_duration / 60);
                $project_total_second = intval($project_total_duration % 60);
                ?>

                    <script type="text/javascript">
                        // SET THE INITIAL VALUES TO TIMER PLACES
                        var timer_starting_hour = <?php echo $project_total_hour; ?>;
                        document.getElementById("project_hour_timer_<?= $p_timer['project_id'] ?>").innerHTML = timer_starting_hour;
                        var timer_starting_minute = <?php echo $project_total_minute; ?>;
                        document.getElementById("project_minute_timer_<?= $p_timer['project_id'] ?>").innerHTML = timer_starting_minute;
                        var timer_starting_second = <?php echo $project_total_second; ?>;
                        document.getElementById("project_second_timer_<?= $p_timer['project_id'] ?>").innerHTML = timer_starting_second;

                        // INITIALIZE THE TIMER WITH SECOND DELAY
                        var timer = timer_starting_second;
                        var mytimer = setInterval(function () {
                            task_run_timer()
                        }, 1000);

                        function task_run_timer() {
                            timer++;

                            if (timer > 59) {
                                timer = 0;
                                timer_starting_minute++;
                                document.getElementById("project_minute_timer_<?= $p_timer['project_id'] ?>").innerHTML = timer_starting_minute;
                            }

                            if (timer_starting_minute > 59) {
                                timer_starting_minute = 0;
                                timer_starting_hour++;
                                document.getElementById("project_hour_timer_<?= $p_timer['project_id'] ?>").innerHTML = timer_starting_hour;
                            }

                            document.getElementById("project_second_timer_<?= $p_timer['project_id'] ?>").innerHTML = timer;
                        }
                    </script>

                <?php endif; ?>
                <?php endif; ?>
                <?php endforeach; ?>
                <?php endif; ?>

                <?php   if (!empty($task_timers)):   ?>
                    <li class="menu-title" key="t-menu"><?= lang('tasks') . ' ' . lang('start') ?></li>

                <?php
                foreach ($task_timers as $v_task_timer):
                if ($role->role_id == 1 || ($role->role_id == 2 && $user_id == $v_task_timer['user_id'])) :
                ?>
                    <li class="mb-sm active" start="<?php echo $v_task_timer['timer_status']; ?>">
                        <a title="<?php echo $v_task_timer['task_name'] . " (" . $v_task_timer['username'] . ")"; ?>" href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_task_timer['task_id'] ?>" class="waves-effect">
                            <img src="<?= base_url() . $v_task_timer['avatar'] ?>" width="30" height="30" class="img-thumbnail img-circle">
                            <span class="timer_badge badge rounded-pill bg-danger float-end"><i class="fas fa-clock fa-pulse fa-spin"></i></span>

                            <span id="tasks_hour_timer_<?= $v_task_timer['task_id'] ?>"> 0 </span>
                            <!-- SEPARATOR -->
                            :
                            <!-- MINUTE TIMER -->
                            <span id="tasks_minute_timer_<?= $v_task_timer['task_id'] ?>"> 0 </span>
                            <!-- SEPARATOR -->
                            :
                            <!-- SECOND TIMER -->
                            <span id="tasks_second_timer_<?= $v_task_timer['task_id'] ?>"> 0 </span>
                        </a>
                    </li>
                <?php
                //RUNS THE TIMER IF ONLY TIMER_STATUS = 1
                if ($v_task_timer['timer_status'] == 'on') :

                $task_current_moment_timestamp = strtotime(date("H:i:s"));
                $task_timer_starting_moment_timestamp = $this->db->get_where('tbl_task', array('task_id' => $v_task_timer['task_id']))->row()->start_time;
                $task_total_duration = $task_current_moment_timestamp - $task_timer_starting_moment_timestamp;

                $task_total_hour = intval($task_total_duration / 3600);
                $task_total_duration -= $task_total_hour * 3600;
                $task_total_minute = intval($task_total_duration / 60);
                $task_total_second = intval($task_total_duration % 60);
                ?>

                    <script type="text/javascript">
                        // SET THE INITIAL VALUES TO TIMER PLACES
                        var timer_starting_hour = <?php echo $task_total_hour; ?>;
                        document.getElementById("tasks_hour_timer_<?= $v_task_timer['task_id'] ?>").innerHTML = timer_starting_hour;
                        var timer_starting_minute = <?php echo $task_total_minute; ?>;
                        document.getElementById("tasks_minute_timer_<?= $v_task_timer['task_id'] ?>").innerHTML = timer_starting_minute;
                        var timer_starting_second = <?php echo $task_total_second; ?>;
                        document.getElementById("tasks_second_timer_<?= $v_task_timer['task_id'] ?>").innerHTML = timer_starting_second;

                        // INITIALIZE THE TIMER WITH SECOND DELAY
                        var timer = timer_starting_second;
                        var mytimer = setInterval(function () {
                            task_run_timer()
                        }, 1000);

                        function task_run_timer() {
                            timer++;

                            if (timer > 59) {
                                timer = 0;
                                timer_starting_minute++;
                                document.getElementById("tasks_minute_timer_<?= $v_task_timer['task_id'] ?>").innerHTML = timer_starting_minute;
                            }

                            if (timer_starting_minute > 59) {
                                timer_starting_minute = 0;
                                timer_starting_hour++;
                                document.getElementById("tasks_hour_timer_<?= $v_task_timer['task_id'] ?>").innerHTML = timer_starting_hour;
                            }

                            document.getElementById("tasks_second_timer_<?= $v_task_timer['task_id'] ?>").innerHTML = timer;
                        }
                    </script>

                <?php endif; ?>
                <?php endif; ?>
                <?php endforeach; ?>
                <?php endif; ?>

            </ul> 
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
<?php if($subdomain && super_admin() && $update = is_any_version_update()){
    ?>
<div class="modal fade" id="update_log" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= lang('whats_new') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body wrap-modal wrap">
                <div class="row mb-3">
                    <h4><?=lang('version');?> <?=$update['version'];?> : <?=$update['title'];?></h4>
                    <div class="px-3"><?=$update['description'];?></div>
                </div>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#update_log').modal('show');
    })
</script>

<?php } ?>