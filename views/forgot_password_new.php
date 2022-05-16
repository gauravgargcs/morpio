<!DOCTYPE html>
<html style="font-size: 16px;">
 
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
    <link rel="stylesheet" href="<?php echo base_url('assets/css/Page-3.css');?>" media="screen">
    <!-- <script class="u-script" type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.9.1.min.js');?>" defer=""></script> -->
    <script class="u-script" type="text/javascript" src="<?php echo base_url('assets/js/nicepage.js');?>" defer=""></script>
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
    <meta property="og:title" content="Allbiz Hub Forgot Password">
    <meta property="og:description" content="Allbiz Hub forgot password, CRM and Business Management Software">
    <meta property="og:type" content="website">
  </head>
 <body class="u-body">
    
    <section class="u-align-center u-clearfix u-palette-1-dark-1 u-section-1" id="carousel_5ae6">
      <div class="u-clearfix u-sheet u-sheet-1">
        <div class="u-border-14 u-border-white u-image u-image-circle u-image-1" alt="" data-image-width="768" data-image-height="500"></div>
        <h1 class="u-text u-text-1"> Password Reset</h1>
        <p class="u-large-text u-text u-text-font u-text-variant u-text-2"> If you're experiencing problems logging in, keep in mind that you must use your unique URL, such as<span style="font-weight: 700;"> Yourdomain.merpio.com </span>
        </p>
        <div class="u-expanded-width-xs u-form u-form-1">
             <?= message_box('success'); ?>
                <?= message_box('error'); ?>
          <form action="<?php echo base_url() ?>login/forgot_password" method="POST" class="u-clearfix u-form-horizontal u-form-spacing-10 u-inner-form  u-form-custom-backend" source="email" name="form" style="padding: 0px;">
          
            <div class="u-form-email u-form-group">
              <label for="email-2bf1" class="u-form-control-hidden u-label"></label>
              <input type="text" placeholder="Enter a valid email address" id="email-2bf1" name="email_or_username" class="u-border-1 u-border-grey-30 u-input u-input-rectangle u-white" required="">
            </div>
            <div class="u-align-left u-form-group u-form-submit">
              <a href="#" class="u-border-none u-btn u-btn-submit u-button-style u-palette-2-base u-btn-1">Reset Password</a>
              <input type="submit" value="submit" class="u-form-control-hidden">
            </div>
            <div class="u-form-send-message u-form-send-success"> Thank you! Your message has been sent. </div>
            <div class="u-form-send-error u-form-send-message"> Unable to send your message. Please fix errors then try again. </div>
            <!-- <input type="hidden" value="" name="recaptchaResponse"> -->
             <input type="submit"  name="flag" value="1" class="u-form-control-hidden">
          </form>
        </div>
        <p class="u-text u-text-3">
          <a href="https://merpio.com/" class="u-active-none u-border-none u-btn u-button-link u-button-style u-hover-none u-none u-text-body-alt-color u-btn-2" target="_blank">No, get me out of here.</a>
        </p>
      </div>
    </section>

  </body>
  <script src="<?php echo base_url() ?>assets/plugins/parsleyjs/parsley.min.js"></script>
<script src="<?= base_url() ?>assets/js/toastr.min.js"></script>

</html>