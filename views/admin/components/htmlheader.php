<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description"
          content="attendance, client management, finance, freelance, freelancer, goal tracking, Income Managment, lead management, payroll, project management, project manager, support ticket, task management, timecard">
    <meta name="keywords"
          content="	attendance, client management, finance, freelance, freelancer, goal tracking, Income Managment, lead management, payroll, project management, project manager, support ticket, task management, timecard">
    <title><?php echo $title; ?></title>
    <?php if (config_item('favicon') != '') : ?>
        <link rel="icon" href="<?php echo base_url() . config_item('favicon'); ?>" type="image/png">
    <?php else: ?>
        <link rel="icon" href="<?php echo base_url('assets/img/favicon.ico'); ?>" type="image/png">
    <?php endif; ?>

    <!-- =============== VENDOR STYLES ===============-->
    <!-- FONT AWESOME-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/fontawesome/css/font-awesome.min.css">
    <!-- SIMPLE LINE ICONS-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/simple-line-icons/css/simple-line-icons.css">
    <!-- ANIMATE.CSS-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/animate.css/animate.min.css">
    <!-- =============== PAGE VENDOR STYLES ===============-->

    <!-- =============== APP STYLES ===============-->
    <?php $direction = $this->session->userdata('direction');
    if (!empty($direction) && $direction == 'rtl') {
        $RTL = 'on';
    } else {
        $RTL = config_item('RTL');
    }

    ?>
    <?php
    
    if (!empty($RTL) && config_item('RTL')) {
        ?>
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-rtl.min.css" id="bscss">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app-rtl.min.css" id="maincss">
    <?php } else {
        ?>
        <!-- =============== BOOTSTRAP STYLES ===============-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" id="bscss">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.css" id="maincss">
    <?php } ?>
<?php 
        if(config_item('sidebar_theme')== "light-mode-switch" || config_item('sidebar_theme')== "rtl-mode-switch"){


        ?>
         <style type="text/css">
    /* ========================================================================
       Component: layout
     ========================================================================== */
    body,
    .wrapper>section {
        background-color: #ffffff!important;
        ;
    }

    #loader-wrapper {
        background-color: #ffffff!important;
        ;
    }

    .wrapper>.aside {
        background-color: #3a3f51;
    }

    /* ========================================================================
       Component: top-navbar
     ========================================================================== */
    .topnavbar {
        background-color: #fff;
    }

    .topnavbar .navbar-header {
        background: #586ce4!important;
    }

    @media only screen and (min-width: 768px) {
        .topnavbar .navbar-header {
            background-image: none;
        }
    }

    .topnavbar .navbar-nav>li>a,
    .topnavbar .navbar-nav>.open>a {
        color: #8e7979!important;
    }

    .topnavbar .navbar-nav>li>a:hover,
    .topnavbar .navbar-nav>.open>a:hover,
    .topnavbar .navbar-nav>li>a:focus,
    .topnavbar .navbar-nav>.open>a:focus {
        color: #8e7979!important;
    }

    .topnavbar .navbar-nav>.active>a,
    .topnavbar .navbar-nav>.open>a,
    .topnavbar .navbar-nav>.active>a:hover,
    .topnavbar .navbar-nav>.open>a:hover,
    .topnavbar .navbar-nav>.active>a:focus,
    .topnavbar .navbar-nav>.open>a:focus {
        background-color: transparent;
    }

    .topnavbar .navbar-nav>li>[data-toggle='navbar-search'] {
        color: #ffffff;
    }

    .topnavbar .nav-wrapper {
        background: #ffffff!important;
    }

    @media only screen and (min-width: 768px) {
        .topnavbar {
            background: #ffffff!important;
        }

        .topnavbar .navbar-nav>.open>a,
        .topnavbar .navbar-nav>.open>a:hover,
        .topnavbar .navbar-nav>.open>a:focus {
            box-shadow: 0 -3px 0 rgba(255, 255, 255, 0.5) inset;
        }

        .topnavbar .navbar-nav>li>a,
        .topnavbar .navbar-nav>.open>a {
            color: #8e7979!important;
        }

        .topnavbar .navbar-nav>li>a:hover,
        .topnavbar .navbar-nav>.open>a:hover,
        .topnavbar .navbar-nav>li>a:focus,
        .topnavbar .navbar-nav>.open>a:focus {
            color: #8e7979!important;
        }
    }

    /* ========================================================================
       Component: sidebar
     ========================================================================== */
    .sidebar {
        background: #586ce4!important;
    }

    .sidebar .nav-heading {
        color: #f9f9f9 !important;
        ;
    }

    .sidebar .nav>li>a,
    .sidebar .nav>li>.nav-item,
    .user-block .user-block-info .user-block-name,
    .user-block .user-block-info .user-block-role {
        color: rgba(255, 255, 255, 0.6);
    }

    .sidebar .nav>li>a:focus,
    .sidebar .nav>li>.nav-item:focus,
    .sidebar .nav>li>a:hover,
    .sidebar .nav>li>.nav-item:hover {
        color: #ffffff!important;
    }

    .sidebar .nav>li>a>em,
    .sidebar .nav>li>.nav-item>em {
        color: inherits;
    }

    .sidebar .nav>li.active,
    .sidebar .nav>li.open,
    .sidebar .nav>li.active>a,
    .sidebar .nav>li.open>a,
    .sidebar .nav>li.active .nav,
    .sidebar .nav>li.open .nav {
        background-color: transparent;
        color: #ffffff !important;
    }

    .sidebar .nav>li.active>a>em,
    .sidebar .nav>li.open>a>em {
        color: #ffffff!important;
    }

    .sidebar .nav>li.active {
        border-left-color: unset;
    }

    .sidebar-subnav {
        background-color: #586ce4!important;
    }

    .sidebar-subnav>.sidebar-subnav-header {
        color: #e1e2e3;
    }

    .sidebar-subnav>li>a,
    .sidebar-subnav>li>.nav-item {
        color: #e1e2e3;
    }

    .sidebar-subnav>li>a:focus,
    .sidebar-subnav>li>.nav-item:focus,
    .sidebar-subnav>li>a:hover,
    .sidebar-subnav>li>.nav-item:hover {
        color: #fdb738!important;
    }

    .sidebar-subnav>li.active>a,
    .sidebar-subnav>li.active>.nav-item {
        color: #fdb738!important;
    }

    .sidebar-subnav>li.active>a:after,
    .sidebar-subnav>li.active>.nav-item:after {
        border-color: #fdb738!important;
        /*background-color: #2b957a;*/
    }

    /* ========================================================================
       Component: offsidebar
     ========================================================================== */
    .offsidebar {
        border-left: 1px solid #cccccc;
        background-color: #ffffff;
        color: #515253;
    }

    .nav-pills>li.active>a,
    .nav-pills>li.active>a:hover,
    .nav-pills>li.active>a:focus,
    li.user-header {
        background-color: #ffffff!important;
        color: #fdb738!important;
    }

    .pinned li.nav-heading .badge {
        font-size: 11px;
        padding: 3px 6px;
        margin-top: 2px;
        border-radius: 10px;
        background-color: #ffffff!important;
    }

    .panel-custom .panel-heading {
        border-bottom: 2px solid #ffffff;
    }

    .custom-bg,
    .fc-state-default {
        background: #ffffff;
        color: #8e7979!important;
    }

    .nav-tabs>li.active>a {
        background-color: #586ce4!important;
        color: #f9f9f9!important;
    }

    .timeline-2,
    .timeline-2 .time-item:after,
    .time-item,
    .time-item:after {
        border-color: #ffffff;
        ;
    }

    .sub-active {
        border-left-color: #ffffff;
    }
</style>
    <?php 

}else{
    ?>
    <style>
    /* ========================================================================
       Component: layout
     ========================================================================== */
     :root {
    --bs-blue: #556ee6;
    --bs-indigo: #564ab1;
    --bs-purple: #6f42c1;
    --bs-pink: #e83e8c;
    --bs-red: #f46a6a;
    --bs-orange: #f1734f;
    --bs-yellow: #f1b44c;
    --bs-green: #34c38f;
    --bs-teal: #050505;
    --bs-cyan: #50a5f1;
    --bs-white: #fff;
    --bs-gray: #c3cbe4;
    --bs-gray-dark: #eff2f7;
    --bs-gray-100: #212529;
    --bs-gray-200: #2a3042;
    --bs-gray-300: #32394e;
    --bs-gray-400: #a6b0cf;
    --bs-gray-500: #bfc8e2;
    --bs-gray-600: #c3cbe4;
    --bs-gray-700: #f6f6f6;
    --bs-gray-800: #eff2f7;
    --bs-gray-900: #f8f9fa;
    --bs-primary: #556ee6;
    --bs-secondary: #c3cbe4;
    --bs-success: #34c38f;
    --bs-info: #50a5f1;
    --bs-warning: #f1b44c;
    --bs-danger: #f46a6a;
    --bs-pink: #e83e8c;
    --bs-light: #32394e;
    --bs-dark: #eff2f7;
    --bs-primary-rgb: 85,110,230;
    --bs-secondary-rgb: 195,203,228;
    --bs-success-rgb: 52,195,143;
    --bs-info-rgb: 80,165,241;
    --bs-warning-rgb: 241,180,76;
    --bs-danger-rgb: 244,106,106;
    --bs-pink-rgb: 232,62,140;
    --bs-light-rgb: 50,57,78;
    --bs-dark-rgb: 239,242,247;
    --bs-white-rgb: 255,255,255;
    --bs-black-rgb: 0,0,0;
    --bs-body-rgb: 166,176,207;
    --bs-font-sans-serif: "Poppins",sans-serif;
    --bs-font-monospace: SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;
    --bs-gradient: linear-gradient(180deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0));
    --bs-body-font-family: var(--bs-font-sans-serif);
    --bs-body-font-size: 0.8125rem;
    --bs-body-font-weight: 400;
    --bs-body-line-height: 1.5;
    --bs-body-color: #a6b0cf;
    --bs-body-bg: #222736;
}
    body,
    .wrapper>section {
        background-color: #222736!important;
        ;
    }

    #loader-wrapper {
        background-color: #222736!important;
        ;
    }

    .wrapper>.aside {
        background-color: #3a3f51;
    }

    /* ========================================================================
       Component: top-navbar
     ========================================================================== */
    .topnavbar {
        background-color: #fff;
    }

    .topnavbar .navbar-header {
        background: #556ee6!important;
    }

    @media only screen and (min-width: 768px) {
        .topnavbar .navbar-header {
            background-image: none;
        }
    }

    .topnavbar .navbar-nav>li>a,
    .topnavbar .navbar-nav>.open>a {
        color: #a6b0cf!important;
    }

    .topnavbar .navbar-nav>li>a:hover,
    .topnavbar .navbar-nav>.open>a:hover,
    .topnavbar .navbar-nav>li>a:focus,
    .topnavbar .navbar-nav>.open>a:focus {
        color: #a6b0cf!important;
    }

    .topnavbar .navbar-nav>.active>a,
    .topnavbar .navbar-nav>.open>a,
    .topnavbar .navbar-nav>.active>a:hover,
    .topnavbar .navbar-nav>.open>a:hover,
    .topnavbar .navbar-nav>.active>a:focus,
    .topnavbar .navbar-nav>.open>a:focus {
        background-color: transparent;
    }

    .topnavbar .navbar-nav>li>[data-toggle='navbar-search'] {
        color: #ffffff;
    }

    .topnavbar .nav-wrapper {
        background: #262b3c!important;
    }

    @media only screen and (min-width: 768px) {
        .topnavbar {
            background: #262b3c!important;
        }

        .topnavbar .navbar-nav>.open>a,
        .topnavbar .navbar-nav>.open>a:hover,
        .topnavbar .navbar-nav>.open>a:focus {
            box-shadow: 0 -3px 0 rgba(255, 255, 255, 0.5) inset;
        }

        .topnavbar .navbar-nav>li>a,
        .topnavbar .navbar-nav>.open>a {
            color: #a6b0cf!important;
        }

        .topnavbar .navbar-nav>li>a:hover,
        .topnavbar .navbar-nav>.open>a:hover,
        .topnavbar .navbar-nav>li>a:focus,
        .topnavbar .navbar-nav>.open>a:focus {
            color: #a6b0cf!important;
        }
    }

    /* ========================================================================
       Component: sidebar
     ========================================================================== */
    .sidebar {
        background: #556ee6!important;
    }

    .sidebar .nav-heading {
        color: rgba(255,255,255,0.69) !important;
        ;
    }

    .sidebar .nav>li>a,
    .sidebar .nav>li>.nav-item,
    .user-block .user-block-info .user-block-name,
    .user-block .user-block-info .user-block-role {
        color: rgba(255,255,255,0.69)!important;
    }

    .sidebar .nav>li>a:focus,
    .sidebar .nav>li>.nav-item:focus,
    .sidebar .nav>li>a:hover,
    .sidebar .nav>li>.nav-item:hover {
        color: #ffffff!important;
    }

    .sidebar .nav>li>a>em,
    .sidebar .nav>li>.nav-item>em {
        color: inherits;
    }

    .sidebar .nav>li.active,
    .sidebar .nav>li.open,
    .sidebar .nav>li.active>a,
    .sidebar .nav>li.open>a,
    .sidebar .nav>li.active .nav,
    .sidebar .nav>li.open .nav {
       background: #556ee6!important;
        color: #ffffff!important;
    }

    .sidebar .nav>li.active>a>em,
    .sidebar .nav>li.open>a>em {
        color: #ffffff!important;
    }

    .sidebar .nav>li.active {
        border-left-color: #ffffff!important;
    }

    .sidebar-subnav {
        background-color: #556ee6!important;
    }

    .sidebar-subnav>.sidebar-subnav-header {
        color: #e1e2e3;
    }

    .sidebar-subnav>li>a,
    .sidebar-subnav>li>.nav-item {
        color: #e1e2e3;
    }

    .sidebar-subnav>li>a:focus,
    .sidebar-subnav>li>.nav-item:focus,
    .sidebar-subnav>li>a:hover,
    .sidebar-subnav>li>.nav-item:hover {
        color: #7382cc!important;
    }

    .sidebar-subnav>li.active>a,
    .sidebar-subnav>li.active>.nav-item {
        color: #7382cc!important;
    }

    .sidebar-subnav>li.active>a:after,
    .sidebar-subnav>li.active>.nav-item:after {
        border-color: #7382cc!important;
        /*background-color: #2b957a;*/
    }

    /* ========================================================================
       Component: offsidebar
     ========================================================================== */
    .offsidebar {
        border-left: 1px solid #cccccc;
        background-color: #ffffff;
        color: #515253;
    }

    .nav-pills>li.active>a,
    .nav-pills>li.active>a:hover,
    .nav-pills>li.active>a:focus
  {
        background-color: #556ee6!important;
        color: #ffffff!important;
    }
    .nav-pills>li a{
        color: #ffffff;
    }

    .pinned li.nav-heading .badge {
        font-size: 11px;
        padding: 3px 6px;
        margin-top: 2px;
        border-radius: 10px;
        background-color: #556ee6!important;
    }

    .panel-custom .panel-heading {
        border-bottom: 2px solid #262b3c;
    }

    .custom-bg,
    .fc-state-default {
        background: #262b3c;
        color: #a6b0cf!important;
    }

    .nav-tabs>li.active>a {
        background-color: rgba(92,100,143,0.69)!important;
        color: rgba(255,255,255,0.69)!important;
    }

    .timeline-2,
    .timeline-2 .time-item:after,
    .time-item,
    .time-item:after {
        border-color: #262b3c;
        ;
    }

    .sub-active {
        border-left-color: #262b3c;
    }
    .panel, .navbar-custom-nav  {
 
    background-color: #2a3042;
    background-clip: border-box;
    border: 0 solid #32394e;
    border-radius: 0.25rem;
}
    .btn-file, .select2-selection, .select2-dropdown,.select2-container--default .select2-selection--single{
    background-color: #2a3042 !important;

}
.btn-file,.select2-container--default .select2-selection--single .select2-selection__rendered{
    color: #bfc8e2 !important;
}
.input-group-addon,.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #334d8f!important;

}

.navbar-custom-nav a {
 
    border-top-left-radius:  0.25rem;;
    border-top-right-radius: 0.25rem;
}
body {
   
    color: var(--bs-body-color);
  
    background-color: var(--bs-body-bg);
    
    -webkit-tap-highlight-color: transparent;
}
.form-control{
    color: #bfc8e2;
    background-color: #2e3446;
    background-clip: padding-box;
    border: 1px solid #32394e;
}
.table {
    --bs-table-bg: transparent;
    --bs-table-accent-bg: transparent;
    --bs-table-striped-color: #a6b0cf;
    --bs-table-striped-bg: rgba(191, 200, 226, 0.05);
    --bs-table-active-color: #a6b0cf;
    --bs-table-active-bg: rgba(0, 0, 0, 0.075);
    --bs-table-hover-color: #a6b0cf;
    --bs-table-hover-bg: rgba(191, 200, 226, 0.05);
    width: 100%;
    margin-bottom: 1rem
;
    color: #a6b0cf;
    vertical-align: top;
    border-color: #32394e;
}
.table-hover>tbody>tr:hover, .table-striped>tbody>tr:nth-of-type(odd), .table>tbody>tr.active>td, .table>tbody>tr.active>th, .table>tbody>tr>td.active, .table>tbody>tr>th.active, .table>tfoot>tr.active>td, .table>tfoot>tr.active>th, .table>tfoot>tr>td.active, .table>tfoot>tr>th.active, .table>thead>tr.active>td, .table>thead>tr.active>th, .table>thead>tr>td.active, .table>thead>tr>th.active {
           --bs-table-accent-bg: var(--bs-table-striped-bg);
            color: var(--bs-table-striped-color);
            background-color: unset;
}
 .nav-tabs-custom .nav-tabs{
        --bs-bg-opacity: 1;
    background-color: rgba(var(--bs-light-rgb),var(--bs-bg-opacity))!important;
 }
 .bg-white{

    background-color: #2a3042;
 border: 0 solid #32394e;
 }
 .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_processing, .dataTables_wrapper .dataTables_paginate{
    color: unset !important;
 }
 .nav-tabs>li>a {
    background: #32394e !important;
     color: #a6b0cf;
}
.nav-tabs>li {
    border-left: unset;
    border-right: 1px solid #a6b0cf;
}
.select2 {
        color: #bfc8e2;
    background-color: #2e3446;

    background-repeat: no-repeat;
    background-position: right 0.75rem center;
        background-size: 16px 12px;
        border: 1px solid #32394e;
        border-radius: 0.25rem;

}
.nav-wrapper .navbar-nav.navbar-right .open .dropdown-menu,.dropdown-menu>li>a{
        color: #a6b0cf!important;
    background-color: #2a3042;
    border: 0;
}
.dropdown-menu>li>a:focus, .dropdown-menu>li>a:hover{
  
        background-color: #2f3648;
}
}
</style>
<?php } ?>

    <?php
    $custom_color = config_item('active_custom_color');
    if (!empty($custom_color) && $custom_color == 1) {
        include_once 'assets/css/bg-custom.php';
    } else { ?> 

<?php

}
    ?>


    <!-- SELECT2-->

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/dist/css/select2.min.css">
    <link rel="stylesheet"
          href="<?php echo base_url(); ?>assets/plugins/select2/dist/css/select2-bootstrap.min.css">

    <!-- Datepicker-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/datepicker.min.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/timepicker.min.css">

    <!-- Toastr-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/toastr.min.css">
    <!-- Data Table  CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/dataTables/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/dataTables/css/dataTables.colVis.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/dataTables/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/dataTables/css/responsive.dataTables.min.css">
    <!-- summernote Editor -->

    <link href="<?php echo base_url(); ?>assets/plugins/summernote/summernote.min.css" rel="stylesheet"
          type="text/css">

    <!-- bootstrap-slider -->
    <link href="<?php echo base_url() ?>assets/plugins/bootstrap-slider/bootstrap-slider.min.css" rel="stylesheet">
    <!-- chartist -->
    <link href="<?php echo base_url() ?>assets/plugins/morris/morris.min.css" rel="stylesheet">

    <!--- bootstrap-select ---->
    <link href="<?php echo base_url() ?>assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/plugins/chat/chat.min.css" rel="stylesheet">

    <!-- JQUERY-->
    <script src="<?= base_url() ?>assets/js/jquery.min.js"></script>

    <link href="<?php echo base_url() ?>asset/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/responsive.css" id="responsive">

    <script src="<?php echo base_url() ?>asset/js/bootstrap-toggle.min.js"></script>
    <?php
    if (empty($unread_notifications)) {
        $unread_notifications = 0;
    }
    ?>
    <script>
        var total_unread_notifications = <?php echo $unread_notifications; ?>,
            autocheck_notifications_timer_id = 0,
            list = null,
            bulk_url = null,
            time_format = <?= (config_item('time_format') == 'H:i' ? 'false' : true) ?>,
            ttable = null,
            base_url = "<?php echo base_url(); ?>",
            new_notification = "<?php lang('new_notification'); ?>",
            credit_amount_bigger_then_remaining_credit = "<?= lang('credit_amount_bigger_then_remaining_credit'); ?>",
            credit_amount_bigger_then_invoice_due = "<?= lang('credit_amount_bigger_then_due_amount'); ?>",
            auto_check_for_new_notifications = <?php echo config_item('auto_check_for_new_notifications'); ?>,
            file_upload_instruction = "<?php echo lang('file_upload_instruction_js'); ?>",
            filename_too_long = "<?php echo lang('filename_too_long'); ?>";
        desktop_notifications = "<?php echo config_item('desktop_notifications'); ?>";
        lsetting = "<?php echo lang('settings'); ?>";
        lfull_conversation = "<?php echo lang('full_conversation'); ?>";
        ledit_name = "<?php echo lang('edit') . ' ' . lang('name') ?>";
        ldelete_conversation = "<?php echo lang('delete_conversation') ?>";
        lminimize = "<?php echo lang('minimize') ?>";
        lclose = "<?php echo lang('close') ?>";
        lnew = "<?php echo lang('new') ?>";
        ldelete_confirm = "<?php echo lang('delete_alert') ?>";
        no_result_found = "<?php echo lang('no_result_found') ?>";
    </script>
 <!-- 25 Nov 21 code by jaraware infosoft -->
<script type="text/javascript">
    console.log('qq'); 
    /* Replace #your_subdomain# by the subdomain of a Site in your OneAll account */        
    var oneall_subdomain = 'rwbsales';

    /* The library is loaded asynchronously */
    var oa = document.createElement('script');
    oa.type = 'text/javascript'; oa.async = true;
    oa.src = '//' + oneall_subdomain + '.api.oneall.com/socialize/library.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(oa, s);
</script>
<script type="text/javascript">
    console.log('q1'); 
    /* Dynamically replace #sso_session_token# by the token generated in your backend. */
    var sso_session_token = '<?php echo isset($_SESSION["sso_token"])?$_SESSION["sso_token"] : ""?>';

    /* Replace #callback_uri# by the URL to your own callback script */
    var callback_uri = '<?php echo site_url('oneall_callback/index');?>';

    /* Initiates the OneAll asynchronous queue */
    var _oneall = window._oneall || [];

    /* ===== This part is for users that are logged in */
    if (typeof sso_session_token === 'string' && sso_session_token.length > 0)
    {
        console.log('q2'); 
        /* Attaches the SSO session token to the user */
        _oneall.push(['single_sign_on', 'do_register_sso_session', sso_session_token,true]); 
        // Enables or disables the debugging mode.
        // _oneall.push(['single_sign_on', 'set_debug', true]);
    }

    /* ===== This part is for user that are NOT logged in */
    else
    {
        console.log('q3'); 
        /* Redirects the user to the callback_uri if he is logged in on another of your websites */
        // _oneall.push(['single_sign_on', 'do_check_for_sso_session', callback_uri]);
        // _oneall.push(['single_sign_on', 'set_debug', true]);
    }
</script>

<!-- end code by jaraware infosoft-->
<script type="text/javascript" src="https://affiliates.merpio.com/integration/general_integration"></script>
<script type="text/javascript">
        AffTracker.setWebsiteUrl( "WebsiteUrl" );
        AffTracker.generalClick( "general_code" );
</script>
<script>
  (function(g,u,i,d,e,s){g[e]=g[e]||[];var f=u.getElementsByTagName(i)[0];var k=u.createElement(i);k.async=true;k.src='https://static.userguiding.com/media/user-guiding-'+s+'-embedded.js';f.parentNode.insertBefore(k,f);if(g[d])return;var ug=g[d]={q:[]};ug.c=function(n){return function(){ug.q.push([n,arguments])};};var m=['previewGuide','finishPreview','track','identify','triggerNps','hideChecklist','launchChecklist'];for(var j=0;j<m.length;j+=1){ug[m[j]]=ug.c(m[j]);}})(window,document,'script','userGuiding','userGuidingLayer','342153174ID'); 
</script>
<?php if(!is_subdomain()) { ?>
  <link rel="manifest" href="<?php echo base_url(); ?>manifest.json">

<script type="text/javascript">

 if ('serviceWorker' in navigator) {
          window.addEventListener('load', function () {
            navigator.serviceWorker.register('<?php echo base_url(); ?>pwa-sw.js').then(function (registration) {
            }, function (err) {
              console.log('ServiceWorker registration failed: ', err);
            }).catch(function (err) {
              console.log(err);
            });
          });
        } else {
          console.log('service worker is not supported');
        }
</script>
<?php } ?>
</head>
