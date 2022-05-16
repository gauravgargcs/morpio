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

    <script type="text/javascript" src="https://partners-affiliates.allbizhome.com/integration/general_integration"></script>
<script type="text/javascript">
    AffTracker.setWebsiteUrl( "WebsiteUrl" );
    AffTracker.generalClick( "general_code" );
</script>
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
                   <div class="u-align-center">
                       <p>Please wait...!</p>
                       <p>Once user will approved than you can login the system.</p>
                   </div>
                  
                  </div>
              
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <script type="text/javascript">
        $(document).ready(function(){

           setTimeout(function(){ window.location.reload() }, 3000);

        })

    </script>
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
