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
        <link rel="icon" href="<?php echo base_url('skote_assets/images/favicon.ico'); ?>" type="image/png">
    <?php endif; ?>

    
    <!-- Icons Css -->
    <link href="<?php echo base_url('skote_assets/css/icons.min.css'); ?>" rel="stylesheet" type="text/css" />



    <!-- =============== APP STYLES ===============-->
    <?php $direction = $this->session->userdata('direction');
    if (!empty($direction) && $direction == 'rtl') {
        $RTL = 'on';
    } else {
        $RTL = config_item('RTL');
    }

    ?>
    
    <?php switch (config_item('sidebar_theme')) {
        case 'light-mode-switch': ?>
          <!-- Bootstrap Css -->
    <link href="<?php echo base_url('skote_assets/css/bootstrap.min.css'); ?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?php echo base_url('skote_assets/css/app.css'); ?>" id="app-style" rel="stylesheet" type="text/css" />
           <?php 
            break;
              case 'dark-mode-switch': ?>
          <!-- Bootstrap Css -->
    <link href="<?php echo base_url('skote_assets/css/bootstrap-dark.min.css'); ?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?php echo base_url('skote_assets/css/app-dark.min.css'); ?>" id="app-style" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .select2-dropdown,.select2-container--default .select2-selection--single{
    background-color: #2a3042 !important;

}
.select2-container--default .select2-selection--single .select2-selection__rendered{
    color: #bfc8e2 !important;
}
    </style>

           <?php 
            break;
              case 'rtl-mode-switch': ?>
          <!-- Bootstrap Css -->
    <link href="<?php echo base_url('skote_assets/css/bootstrap-rtl.min.css'); ?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?php echo base_url('skote_assets/css/app-rtl.min.css'); ?>" id="app-style" rel="stylesheet" type="text/css" />
     <script type="text/javascript">
           document.getElementsByTagName("html")[0].setAttribute("dir", "rtl");
        </script>
           <?php 
            break;
               case 'dark-rtl-mode-switch': ?>
          <!-- Bootstrap Css -->
    <link href="<?php echo base_url('skote_assets/css/bootstrap-dark-rtl.min.css'); ?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?php echo base_url('skote_assets/css/app-dark-rtl.min.css'); ?>" id="app-style" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .select2-dropdown,.select2-container--default .select2-selection--single{
    background-color: #2a3042 !important;
   
}
.select2-container--default .select2-selection--single .select2-selection__rendered{
    color: #bfc8e2 !important;
}
    </style>
     <script type="text/javascript">
       document.getElementsByTagName("html")[0].setAttribute("dir", "rtl");

        </script>
           <?php 
            break;
        
        default:
           ?>
                 <!-- Bootstrap Css -->
    <link href="<?php echo base_url('skote_assets/css/bootstrap.min.css'); ?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?php echo base_url('skote_assets/css/app.css'); ?>" id="app-style" rel="stylesheet" type="text/css" />
           <?php
            break;
    } ?>
     
    <?php 
    $custom_color = config_item('active_custom_color');
    if (!empty($custom_color) && $custom_color == 1) {
        include_once 'skote_assets/css/bg-custom.php';
  
        }
    ?>


    <link href="<?php echo base_url('skote_assets/css/custom_app.css'); ?>" id="app-style" rel="stylesheet" type="text/css" />

    <link href="<?php echo base_url(); ?>skote_assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>skote_assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>skote_assets/libs/spectrum-colorpicker2/spectrum.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>skote_assets/libs/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>skote_assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>skote_assets/libs/@chenfengyuan/datepicker/datepicker.min.css">

    <!-- =============== VENDOR STYLES ===============-->
    <!-- FONT AWESOME-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>skote_assets/plugins/fontawesome/css/font-awesome.min.css">
    <!-- SIMPLE LINE ICONS-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>skote_assets/plugins/simple-line-icons/css/simple-line-icons.css">
    <!-- ANIMATE.CSS-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>skote_assets/plugins/animate.css/animate.min.css">
    
    <!-- dragula css -->
    <link href="<?php echo base_url(); ?>skote_assets/libs/dragula/dragula.min.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="<?=base_url();?>skote_assets/libs/toastr/build/toastr.min.css">

    <!-- JAVASCRIPT -->
    <script src="<?php echo base_url('skote_assets/libs/jquery/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('skote_assets/libs/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <!-- jquery-validation -->
    <script src="<?=base_url();?>skote_assets/libs/jquery-validation/jquery.validate.min.js"></script>
    <script src='<?= base_url() ?>skote_assets/plugins/jquery-validation/jquery.form.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.js"></script>

    <!-- DataTables -->
    <link href="<?=base_url();?>skote_assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="<?=base_url();?>skote_assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="<?=base_url();?>skote_assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />


    <!-- Sweet Alert-->
    <link href="<?=base_url();?>skote_assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

    <link href="<?=base_url();?>skote_assets/calentim-date-time-range-picker/build/css/calentim.min.css" rel="stylesheet"/>

    <link href="<?php echo base_url() ?>skote_assets/plugins/chat/chat.min.css" rel="stylesheet">

    <?php
    if (empty($unread_notifications)) {
        $unread_notifications = 0;
    }
    ?>
    <?php
    $gcal_api_key = config_item('gcal_api_key');
    $gcal_id = config_item('gcal_id');
    ?>
<script>
    var total_unread_notifications = <?php echo $unread_notifications; ?>,
        autocheck_notifications_timer_id = 0,
        list = null,
        bulk_url = null,
        date_format = '<?=config_item('date_picker_format'); ?>',
        time_format = <?= (config_item('time_format') == 'H:i' ? 'false' : true) ?>,
        ttable = null,
        base_url = "<?php echo base_url(); ?>",
        new_notification = "<?php lang('new_notification'); ?>",
        credit_amount_bigger_then_remaining_credit = "<?= lang('credit_amount_bigger_then_remaining_credit'); ?>",
        credit_amount_bigger_then_invoice_due = "<?= lang('credit_amount_bigger_then_due_amount'); ?>",
        auto_check_for_new_notifications = <?php echo config_item('auto_check_for_new_notifications'); ?>,
        file_upload_instruction = "<?php echo lang('file_upload_instruction_js'); ?>",
        filename_too_long = "<?php echo lang('filename_too_long'); ?>",
    desktop_notifications = "<?php echo config_item('desktop_notifications'); ?>",
    lsetting = "<?php echo lang('settings'); ?>",
    lfull_conversation = "<?php echo lang('full_conversation'); ?>",
    ledit_name = "<?php echo lang('edit') . ' ' . lang('name') ?>",
    ldelete_conversation = "<?php echo lang('delete_conversation') ?>",
    lminimize = "<?php echo lang('minimize') ?>",
    lclose = "<?php echo lang('close') ?>",
    lnew = "<?php echo lang('new') ?>",
    ldelete_confirm = "<?php echo lang('delete_alert') ?>",
    are_you_sure = "<?php echo lang('are_you_sure') ?>",
    Yes_delete_it = "<?php echo lang('Yes_delete_it') ?>",
    deleted = "<?php echo lang('deleted') ?>",
    data_deleted = "<?php echo lang('data_deleted') ?>",
    
    no_result_found = "<?php echo lang('no_result_found') ?>",
    gcal_api_key="<?=$gcal_api_key?>",
    payments_color='<?= config_item('payments_color') ?>', 
    invoice_color='<?= config_item('invoice_color') ?>', 
    estimate_color='<?= config_item('estimate_color') ?>', 
    project_color='<?= config_item('project_color') ?>',
    tasks_color='<?= config_item('tasks_color') ?>',
    bugs_color='<?= config_item('bugs_color') ?>',
    goal_tracking_color='<?= config_item('goal_tracking_color') ?>',
    absent_color='<?= config_item('absent_color') ?>',
    on_leave_color='<?= config_item('on_leave_color') ?>',
    opportunities_color='<?= config_item('opportunities_color') ?>', 
    milestone_color='<?= config_item('milestone_color') ?>';

</script>
<!-- 19 Nov 21 code by jaraware infosoft -->
<script type="text/javascript">
    console.log('mmm'); 
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
    console.log('m1'); 
    /* Dynamically replace #sso_session_token# by the token generated in your backend. */
    var sso_session_token = '<?php echo isset($_SESSION["sso_token"])?$_SESSION["sso_token"] : ""?>';

    /* Replace #callback_uri# by the URL to your own callback script */
    var callback_uri = '<?php echo site_url('oneall_callback/index');?>';

    /* Initiates the OneAll asynchronous queue */
    var _oneall = window._oneall || [];

    /* ===== This part is for users that are logged in */
    if (typeof sso_session_token === 'string' && sso_session_token.length > 0)
    {
        console.log('m2'); 
        /* Attaches the SSO session token to the user */
        _oneall.push(['single_sign_on', 'do_register_sso_session', sso_session_token,true]); 
        // Enables or disables the debugging mode.
        // _oneall.push(['single_sign_on', 'set_debug', true]);
    }

    /* ===== This part is for user that are NOT logged in */
    else
    {
        console.log('m3'); 
        /* Redirects the user to the callback_uri if he is logged in on another of your websites */
        // _oneall.push(['single_sign_on', 'do_check_for_sso_session', callback_uri]);
        // _oneall.push(['single_sign_on', 'set_debug', true]);
    }
</script>

<!-- end code by jaraware infosoft-->
<style>
    .note-editor .note-editable {
        height: 150px;
    }

    a:hover {
        text-decoration: none;
    }
    .tbl-action{
        padding-bottom: 15px;
    }
   
     .action_1{
        display: inline-flex;
    }
</style>

<script type="text/javascript" src="https://affiliates.merpio.com/integration/general_integration"></script>
<script type="text/javascript">
        AffTracker.setWebsiteUrl( "WebsiteUrl" );
        AffTracker.generalClick( "general_code" );
</script>
<script>
  (function(g,u,i,d,e,s){g[e]=g[e]||[];var f=u.getElementsByTagName(i)[0];var k=u.createElement(i);k.async=true;k.src='https://static.userguiding.com/media/user-guiding-'+s+'-embedded.js';f.parentNode.insertBefore(k,f);if(g[d])return;var ug=g[d]={q:[]};ug.c=function(n){return function(){ug.q.push([n,arguments])};};var m=['previewGuide','finishPreview','track','identify','triggerNps','hideChecklist','launchChecklist'];for(var j=0;j<m.length;j+=1){ug[m[j]]=ug.c(m[j]);}})(window,document,'script','userGuiding','userGuidingLayer','342153174ID'); 
</script>
<?php // if(!is_subdomain()) { ?>
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
<?php // } ?>
</head>

