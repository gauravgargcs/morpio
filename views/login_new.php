<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php echo $title; ?></title>
    <?php if (config_item('favicon') != '') : ?>
        <link rel="icon" href="<?php echo base_url() . config_item('favicon'); ?>" type="image/png">
    <?php else: ?>
        <link rel="icon" href="<?php echo base_url('assets/img/favicon.ico'); ?>" type="image/png">
    <?php endif; ?>
     <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" id="bscss">
        <!-- Toastr-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/toastr.min.css">
    <!-- =============== BOOTSTRAP STYLES ===============-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" id="bscss">
    <!-- =============== APP STYLES ===============-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" id="maincss">
      <script src="<?php echo base_url(); ?>assets/plugins/jquery/dist/jquery.min.js"></script>

    <link rel="stylesheet" href="<?php echo base_url('assets/css/nicepage.css');?>" media="screen">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/Page-1.css');?>" media="screen">
    <!-- <script class="u-script" type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.9.1.min.js');?>" defer=""></script> -->
    <script class="u-script" type="text/javascript" src="<?php echo base_url('assets/js/nicepage.js');?>" defer=""></script>

   
    <link id="u-theme-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i|Tauri:400">
    <link id="u-page-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i|Lato:100,100i,300,300i,400,400i,700,700i,900,900i|Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i">
    
    
    <script type="application/ld+json">{
        "@context": "http://schema.org",
        "@type": "Organization",
        "name": "",
        "sameAs": [
                "https://www.facebook.com/allbizdealroom",
                "https://www.instagram.com/allbizdealroom/",
                "https://www.linkedin.com/company/allbizdealroom?originalSubdomain=au",
                "https://www.youtube.com/allbizsales"
        ]
}</script>
    <meta name="theme-color" content="#0f227e">
    <meta property="og:title" content="<?php echo $title; ?>">
    <meta property="og:type" content="website">

   <script type="text/javascript" src="https://affiliates.merpio.com/integration/general_integration"></script>
<script type="text/javascript">
        AffTracker.setWebsiteUrl( "WebsiteUrl" );
        AffTracker.generalClick( "general_code" );
</script>
<?php //if(!is_subdomain()) { ?>
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
<?php //} ?>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/61d7c07ab84f7301d329c680/1fopcfnse';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
<script>
  (function(g,u,i,d,e,s){g[e]=g[e]||[];var f=u.getElementsByTagName(i)[0];var k=u.createElement(i);k.async=true;k.src='https://static.userguiding.com/media/user-guiding-'+s+'-embedded.js';f.parentNode.insertBefore(k,f);if(g[d])return;var ug=g[d]={q:[]};ug.c=function(n){return function(){ug.q.push([n,arguments])};};var m=['previewGuide','finishPreview','track','identify','triggerNps','hideChecklist','launchChecklist'];for(var j=0;j<m.length;j+=1){ug[m[j]]=ug.c(m[j]);}})(window,document,'script','userGuiding','userGuidingLayer','342153174ID'); 
</script>
  </head>
  <body class="u-body">
    <?php 
    $back_img ='';
   
    $login_background = config_item('login_background');
    if (!empty($login_background)) {
        $back_img = base_url() . '/' . config_item('login_background');
    }

$brand_logo = config_item('company_logo');
if(!$brand_logo) {
    $brand_logo =  base_url('assets/images/ScreenShot2021-09-16at6.05.30pm.png');
}
if($back_img){
    ?> 
    <style type="text/css">
        .u-section-1 .u-image-1{
            background-image: url('<?= $back_img;?>');
        }
    </style>
    <?php } ?>
    
    <section class="u-align-center u-clearfix u-grey-5 u-section-1" id="sec-120b">
      <div class="u-clearfix u-sheet u-sheet-1">
        <div class="u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-1">
          <div class="u-layout">
            <div class="u-layout-row">
              <div class="u-align-left u-container-style u-image u-layout-cell u-size-31 u-image-1" data-image-width="1080" data-image-height="1080">
                <div class="u-container-layout u-container-layout-1"></div>
              </div>
              <div class="u-align-center-sm u-align-center-xs u-container-style u-layout-cell u-shape-rectangle u-size-29 u-white u-layout-cell-2">
                 
                <div class="u-container-layout u-valign-top u-container-layout-2">
                  <div>
                    
                  <?php if (config_item('enable_languages') == 'TRUE') { ?>
        <ul class="nav navbar-right ">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-flag"></i> <?= lang('languages') ?>
                </a>
                <ul class="dropdown-menu">
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
             <li>
                 <a href="<?= base_url() ?>frontend/jobs" class="u-btn u-btn-round u-button-style u-hover-palette-1-light-1 u-palette-1-base u-radius-6 u-btn-1 " ><?= lang('apply_jobs') ?></a>
             </li>
        </ul>
    <?php } ?>

                  </div>
                
                  <h3 class="u-align-center-lg u-align-center-md u-align-center-xl u-text u-text-default u-text-palette-1-base u-text-1"><?=lang('welcome');?></h3>
                  <a href="<?=base_url();?>">
                  <img class="u-image u-image-default u-image-2" src="<?php echo $brand_logo;?>" alt="" data-image-width="828" data-image-height="326">
                  </a>
                  <div class="u-expanded-width-xs u-form u-login-control u-form-1">
                     <?= message_box('success'); ?>
            <?= message_box('error'); ?>
            <div class="error_login">
                <?php
                $validation_errors = validation_errors();
                if (!empty($validation_errors)) { ?>
                    <div class="alert alert-danger"><?php echo $validation_errors; ?></div>
                    <?php
                }
                $error = $this->session->flashdata('error');
                $success = $this->session->flashdata('success');
                if (!empty($error)) {
                    ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php } ?>
                <?php if (!empty($success)) { ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php } ?>
            </div>
                      <!--  --><div class="u-social-icons">
                        <div id="oa_social_login_container" ></div>
                        <script type="text/javascript">
                            /* Replace #your_callback_uri# with the url to your own callback script */
                            var your_callback_script = '<?php echo base_url();?>Oneall_callback';

                            /* Embeds the buttons into the container oa_social_login_container */
                            var _oneall = _oneall || [];
                            _oneall.push(['social_login', 'set_providers', ['facebook', 'google']]);
                            _oneall.push(['social_login', 'set_callback_uri', your_callback_script]);
                            _oneall.push(['social_login', 'do_render_ui', 'oa_social_login_container']);

                        </script>
                    </div>
                    <form data-parsley-validate="" novalidate="" action="<?php echo base_url() ?>login" method="post"class="u-clearfix u-form-custom-backend u-form-spacing-15 u-form-vertical u-inner-form" source="custom" name="form-3" style="padding: 0px;">
                      <div class="u-form-group u-form-name" data-toggle="tooltip" data-html="true" data-placement="bottom" title='Having a login issues? <br> <br> Your unique login is<br> "yourdomain.allbizhub.com" '>
                        <label for="username-5b0a" class="u-form-control-hidden u-label"></label>
                        <input type="text" placeholder="<?= lang('username') ?>" id="username-5b0a" name="user_name"  required="" class="u-border-2 u-border-black u-border-no-left u-border-no-right u-border-no-top u-input u-input-rectangle u-input-1" autocomplete="off">
                      </div>
                      <div class="u-form-group u-form-password">
                        <label for="password-5b0a" class="u-form-control-hidden u-label"></label>
                        <input type="password" placeholder="<?= lang('password') ?>" id="password-5b0a" name="password" class="u-border-2 u-border-black u-border-no-left u-border-no-right u-border-no-top u-input u-input-rectangle u-input-2" required="" autocomplete="off">
                      </div>
                      <div class="u-form-checkbox u-form-group">
                        <input type="checkbox" id="checkbox-5b0a"  name="remember" value=""s>
                        <label for="checkbox-5b0a" class="u-label"><?= lang('remember_me') ?></label>
                      </div>
                      <?php if (config_item('recaptcha_secret_key') != '' && config_item('recaptcha_site_key') != '') { ?>
                        <div class="g-recaptcha mb-lg mt-lg" data-sitekey="<?php echo config_item('recaptcha_site_key'); ?>"></div>
                      <?php }
                      $mark_attendance_from_login = config_item('mark_attendance_from_login');
                      if (!empty($mark_attendance_from_login) && $mark_attendance_from_login == 'Yes') {
                        $class = null;
                      } else {
                        $class = 'btn-block';
                      }
                      ?>
                      <div class="u-align-center u-form-group u-form-submit">
                       <!--  <a href="#" class="u-active-palette-1-light-1 u-border-none u-btn u-btn-round u-btn-submit u-button-style u-hover-palette-1-light-1 u-palette-1-base u-radius-50 u-text-body-alt-color u-btn-2"><?= lang('sign_in') ?></a> -->
                        <input type="submit" value="<?= lang('sign_in') ?>" class="u-active-palette-1-light-1 u-border-none u-btn u-btn-round u-btn-submit u-button-style u-hover-palette-1-light-1 u-palette-1-base u-radius-50 u-text-body-alt-color u-btn-2">
                      </div>
                    
                    </form>
                  </div>
                 <!--  <a href="https://allbizhub.com/#pricing" class="u-border-1 u-border-active-palette-1-base u-border-hover-palette-1-base u-btn u-button-style u-login-control u-login-forgot-password u-none u-text-palette-1-base u-btn-3" target="_blank">Don't have an account? Start a free trial</a> -->


                 <?php if (config_item('allow_client_registration') == 'TRUE') { ?>
                  <a href="<?= base_url() ?>login/register"
                   class="u-border-1 u-border-active-palette-1-base u-border-hover-palette-1-base u-btn u-button-style u-login-control u-login-forgot-password u-none u-text-palette-1-base u-btn-3"><i
                   class="fa fa-sign-in"></i> <?= lang('get_your_account') ?></a>
                 <?php } ?>
                 <?php $sub_domain = is_subdomain($_SERVER['HTTP_HOST']); 
                 if ( $sub_domain=='' ||  $sub_domain=='www') { ?>
                  <a href="<?= base_url() ?>#pricing"
                   class="u-border-1 u-border-active-palette-1-base u-border-hover-palette-1-base u-btn u-button-style u-login-control u-login-forgot-password u-none u-text-palette-1-base u-btn-3"><i
                   class="fa fa-sign-in"></i> <?= lang('do_not_have_an_account') ?> <?= lang('start_a_free_trial') ?>.</a>
                 <?php } ?>

                  <a href="<?= base_url() ?>login/forgot_password" class="u-border-1 u-border-active-palette-1-base u-border-hover-palette-1-base u-btn u-button-style u-login-control u-login-forgot-password u-none u-text-palette-1-base u-btn-4" ><?= lang('forgot_password') ?></a>
                <div class="text-center mrgT-10">
                <span>&copy;</span>

                <span><?= date('Y') ?></span>
                <span>-</span>
                <span><a href="<?= getConfigItems('copyright_url') ?>"> <?= getConfigItems('copyright_name') ?></a></span>
               
                
            </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- =============== Toastr ===============-->
<script src="<?= base_url() ?>assets/js/toastr.min.js"></script>
<!-- BOOTSTRAP-->
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- STORAGE API-->
<script src="<?php echo base_url(); ?>assets/plugins/jQuery-Storage-API/jquery.storageapi.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/parsleyjs/parsley.min.js"></script>
   <script type="text/javascript">
        $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })
    </script>
<!-- 19 Nov 21 code by jaraware infosoft  -->
    <script type="text/javascript">
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
        /* Dynamically replace #sso_session_token# by the token generated in your backend. */
        var sso_session_token = '<?php echo isset($_SESSION["sso_token"]) ? $_SESSION["sso_token"] : ""?>';

        /* Replace #callback_uri# by the URL to your own callback script */
        var callback_uri = '<?php echo site_url('oneall_callback/index')?>';

        /* Initiates the OneAll asynchronous queue */
        var _oneall = window._oneall || [];

        var session_identity_token = '<?php echo isset( $_SESSION['session_identity_token'] ) ?  $_SESSION['session_identity_token']  : ""?>';
        
        var event = function(data) {
           if(session_identity_token != data.identity_token){
             _oneall.push(['single_sign_on', 'do_check_for_sso_session', callback_uri]);
           }
          }
        /* ===== This part is for users that are logged in */
        if (typeof sso_session_token === 'string' && sso_session_token.length > 0)
        {
            /* Attaches the SSO session token to the user */
            _oneall.push(['single_sign_on', 'do_register_sso_session', sso_session_token,true]); 
        }

        /* ===== This part is for user that are NOT logged in */
        else
        {
            /* Redirects the user to the callback_uri if he is logged in on another of your websites */
            // _oneall.push(['single_sign_on', 'do_check_for_sso_session', callback_uri]);
            _oneall.push(['single_sign_on', 'do_event_for_sso_session', event ]);
     
/* 
  <event> : JavaScript function, required
  The function that will be executed. 
 
  Example - the session data is available as first argument: */
  
        }
    </script>
   
   
    <!-- end code by jaraware infosoft-->
    <style type="text/css">
       .u-social-icons {
    height: 110px;
    min-height: 16px;
    width: 142px;
    min-width: 42px;
    margin: 10px auto 0;
}
    </style>
  </body>
</html>
