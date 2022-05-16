<?php

$user_id = $this->session->userdata('user_id');
$profile_info = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
$user_info = $this->db->where('user_id', $user_id)->get('tbl_users')->row();
?>
<header class="topnavbar-wrapper">
    <!-- START Top Navbar-->
    <nav role="navigation" class="navbar topnavbar">
        <!-- START navbar header-->
        <?php $display = config_item('logo_or_icon'); ?>
        <div class="navbar-header">
            <?php if ($display == 'logo' || $display == 'logo_title') { ?>
                <a href="<?php echo base_url('admin/dashboard'); ?>" class="navbar-brand">
                    <div class="brand-logo">
                        <img style="max-height: 42px;"
                             src="<?= base_url() . config_item('company_logo') ?>" alt="App Logo"
                             class="img-responsive">
                    </div>
                    <div class="brand-logo-collapsed">
                        <img style="width: 48px;height: 48px;border-radius: 50px"
                             src="<?= base_url() . config_item('company_logo') ?>" alt="App Logo"
                             class="img-responsive">
                    </div>
                </a>
            <?php }
            ?>
        </div>
        <!-- END navbar header-->
        <!-- START Nav wrapper-->
        <div class="nav-wrapper">
            <!-- START Left navbar-->
            <ul class="nav navbar-nav">
                <li>
                    <!-- Button used to collapse the left sidebar. Only visible on tablet and desktops-->
                    <a href="#" data-toggle-state="aside-collapsed" class="hidden-xs">
                        <em class="fa fa-navicon"></em>
                    </a>
                    <!-- Button to show/hide the sidebar on mobile. Visible on mobile only.-->
                    <a href="#" data-toggle-state="aside-toggled" data-no-persist="true"
                       class="visible-xs sidebar-toggle">
                        <em class="fa fa-navicon"></em>
                    </a>
                </li>
                <!-- END User avatar toggle-->
                <!-- START lock screen-->
                <li class="hidden-xs">
                    <a href="" class="text-center" style="vertical-align: middle;font-size: 20px;"><?php
                        if ($display == 'logo_title' || $display == 'icon_title') {
                            if (config_item('website_name') == '') {
                                echo config_item('company_name');
                            } else {
                                echo config_item('website_name');
                            }
                        }
                        ?></a>
                </li>
                <!-- END lock screen-->
            </ul>
            <!-- END Left navbar-->
            <!-- START Right Navbar-->
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-plus"></i></a>
                        <ul class="dropdown-menu animated zoomIn">
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
                    </li>
                <?php $super_admin = super_admin();
                if (!empty($super_admin)) {
                    $companies_id = $this->session->userdata('companies_id');
                    $company_logo = $this->db->where(array('companies_id' => null, 'config_key' => 'company_logo'))->get('tbl_config')->row();
                    ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-building"></i>
                            <?php
                            if (!empty($companies_id)) {
                                echo company_name($companies_id);
                            } else {
                                echo lang('companies');
                            }
                            ?>
                        </a>
                        <ul class="dropdown-menu animated zoomIn" style="width: 300px;">
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
                                <li class="<?= (!empty($companies_id) && $companies_id == $companies->companies_id ? 'active' : '') ?>">
                                    <a href="<?= base_url() ?>admin/global_controller/set_companies/<?= $companies->companies_id ?>"
                                       title="<?= $companies->name ?>">
                                         <?= $companies->name ?>
                                    </a>
                                </li>
                                <?php
                            endforeach;
                            ?>
                        </ul>
                    </li>
                <?php } ?>
                <?php if (config_item('enable_languages') == 'TRUE') { 
                    $lang_info = $this->db->where('name', $this->session->userdata('lang'))->get('tbl_languages')->row();
                    $icon="us";
                    if(!empty($lang_info)){
                        $icon = $lang_info->icon;
                    }
                    ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo base_url('skote_assets/images/flags/'.$icon.'.gif'); ?>" alt="Header Language" height="16">
                        </a>
                        <ul class="dropdown-menu animated zoomIn">
                            <?php
                            $languages = $this->db->where('active', 1)->order_by('name', 'ASC')->get('tbl_languages')->result();
                            foreach ($languages as $lang) :
                                ?>
                                <li>
                                    <a href="<?= base_url() ?>admin/global_controller/set_language/<?= $lang->name ?>"
                                       title="<?= ucwords(str_replace("_", " ", $lang->name)) ?>">
                                        <img src="<?= base_url() ?>asset/images/flags/<?= $lang->icon ?>.gif"
                                             alt="<?= ucwords(str_replace("_", " ", $lang->name)) ?>"/> <?= ucwords(str_replace("_", " ", $lang->name)) ?>
                                    </a>
                                </li>
                                <?php
                            endforeach;
                            ?>
                        </ul>
                    </li>
                <?php } ?>
                <!-- START Alert menu-->
                <li class="dropdown dropdown-list notifications">
                    <?php $this->load->view('admin/components/notifications'); ?>
                </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                        <img src="<?= base_url() . $profile_info->avatar ?>" class="img-xs user-image"
                             alt="User Image"/>
                        <span class="hidden-xs"><?= $profile_info->fullname ?></span>
                    </a>
                    <ul class="dropdown-menu animated zoomIn">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= base_url() . $profile_info->avatar ?>" class="img-circle" alt="User Image"/>
                            <p>
                                <?= $profile_info->fullname ?>
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
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="col-xs-3 text-center">
                                <a href="<?= base_url() ?>admin/settings/activities"><?= lang('activities') ?></a>
                            </div>
                            <?php
                            $admin = admin();
                            $subdomain = is_subdomain();
                            if (!empty($admin) && !empty($subdomain)) {
                                ?>
                                <div class="col-xs-6 text-center">
                                    <a href="<?= base_url('subscription_details') ?>"><?= lang('subscriptions') ?></a>
                                </div>
                            <?php } else {
                                ?>
                                <div class="col-xs-6 text-center">
                                    <a href="<?= base_url('admin/user/user_details/' . $user_id) ?>"><?= lang('my_details') ?></a>
                                </div>
                            <?php } ?>
                            <div class="col-xs-3 text-center">
                                <a href="<?= base_url() ?>locked/lock_screen"><?= lang('lock_screen') ?></a>
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?= base_url() ?>admin/settings/update_profile"
                                   class="btn btn-default btn-flat"><?= lang('update_profile') ?></a>
                            </div>
                            <form method="post" action="<?= base_url() ?>login/logout"
                                  class="form-horizontal">

                                <input type="hidden" name="clock_time" value="" id="time">
                                <div class="pull-right">
                                    <button type="submit"
                                            class="btn btn-default btn-flat"><?= lang('logout') ?></button>
                                </div>
                            </form>
                        </li>
                    </ul>
                </li>
               
            </ul>
            <!-- END Right Navbar-->
        </div>
        <!-- END Nav wrapper-->
    </nav>
    <!-- END Top Navbar-->
</header>