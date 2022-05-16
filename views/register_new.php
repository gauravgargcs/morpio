<!DOCTYPE html>
<html style="font-size: 16px;">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <link rel="stylesheet" href="<?php echo base_url('assets/css/nicepage-client.css');?>" media="screen">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/Client-Signup.css');?>" media="screen">
    <!-- <script class="u-script" type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.9.1.min.js');?>" defer=""></script> -->
    <script class="u-script" type="text/javascript" src="<?php echo base_url('assets/js/nicepage.js');?>" defer=""></script>

    <link id="u-page-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Lato:100,100i,300,300i,400,400i,700,700i,900,900i|Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i">
    
    
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
    <meta property="og:title" content="Allbiz CRM Client Signup">
    <meta property="og:description" content="Allbiz CRM Client Signup and login page">
    <meta property="og:type" content="website">
  <script type="text/javascript" src="https://affiliates.merpio.com/integration/general_integration"></script>
<script type="text/javascript">
        AffTracker.setWebsiteUrl( "WebsiteUrl" );
        AffTracker.generalClick( "general_code" );
</script>
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
  </head>
  <body class="u-body">
    <section class="u-align-center u-clearfix u-grey-5 u-section-1" id="carousel_22bd">
      <div class="u-clearfix u-expanded-width u-gutter-0 u-layout-wrap u-layout-wrap-1">
        <div class="u-layout">
          <div class="u-layout-row">
            <div class="u-align-left u-container-style u-layout-cell u-palette-1-base u-right-cell u-size-30 u-layout-cell-1">
              <div class="u-container-layout u-container-layout-1">
                <h2 class="u-custom-font u-font-montserrat u-text u-text-body-alt-color u-text-1">
                  <span style="font-weight: 700;">Welcom<span style="font-weight: 700;"></span>e
                  </span>
                  <span style="font-weight: 700;"></span>
                </h2>
                <p class="u-text u-text-body-alt-color u-text-2">
                  <span style="font-weight: 400;">If you've been invited to this page, please go ahead and create a profile. <br>
                    <br>Not sure? <br>It's sometimes good to double-check the URL. example: <span style="text-decoration: underline !important;">clientdomain.allbizhub.com.</span>&nbsp;<br>
                    <br>If anything doesn't look right, it's possible you've stumbled onto this page by chance.&nbsp;If you still need help, visit our <a href="https://allbizhome.com/srv/" class="u-active-none u-border-2 u-border-active-palette-4-base u-border-hover-palette-4-base u-border-palette-4-base u-btn u-button-link u-button-style u-hover-none u-none u-text-active-white u-text-hover-white u-text-palette-4-base u-btn-1" target="_blank">help page</a>
                  </span>
                </p>
                <img class="u-image u-image-default u-image-1" src="<?=site_url('assets/images/10.png');?>" alt="" data-image-width="600" data-image-height="413">
              </div>
            </div>
            <div class="u-container-style u-layout-cell u-left-cell u-palette-1-dark-1 u-size-30 u-layout-cell-2">
              <div class="u-container-layout u-container-layout-2">
                <h1 class="u-align-center u-custom-font u-font-montserrat u-text u-title u-text-3">Create your&nbsp;<br>user profile
                </h1>
                <p class="u-align-center u-large-text u-text u-text-grey-50 u-text-variant u-text-4">You're about to join an existing team</p>
                <div class="u-form u-form-1">
                  
                    <form method="post" data-parsley-validate="" novalidate="" action="<?= base_url() ?>login/registered_user" class="u-clearfix u-form-spacing-18 u-form-vertical u-inner-form u-form-custom-backend ">
                   
                    <div class="u-form-group u-form-name u-form-group-1">
                      <label for="name-3c4e" class="u-label u-label-1" wfd-invisible="true">Business Name</label>
                      <input type="text" placeholder="Your Business Name" id="name-3c4e" name="name" class="u-border-2 u-border-no-left u-border-no-right u-border-no-top u-border-white u-input u-input-rectangle u-input-1" required="required">
                    </div>
                    <div class="u-form-email u-form-group">
                      <label for="email-f0d0" class="u-label u-label-2" wfd-invisible="true">Email</label>
                      <input type="email" placeholder="Your Email" id="email-f0d0" name="email" class="u-border-2 u-border-no-left u-border-no-right u-border-no-top u-border-white u-input u-input-rectangle u-input-2" required="">
                    </div>
                    <div class="u-form-group u-form-select u-form-group-3">
                      <label for="select-1a3a" class="u-form-control-hidden u-label u-label-3"></label>
                      <div class="u-form-select-wrapper">
                        <select id="select-1a3a" name="language" class="u-border-2 u-border-no-left u-border-no-right u-border-no-top u-border-white u-input u-input-rectangle u-input-3" autofocus="autofocus">
                          <?php
                          $languages = $this->db->where('active', 1)->order_by('name', 'ASC')->get('tbl_languages')->result();
                          if (!empty($languages)) {
                            foreach ($languages as $lang) : ?>
                              <option
                              value="<?= $lang->name ?>"<?php
                              if (!empty($client_info->language) && $client_info->language == $lang->name) {
                                echo 'selected';
                              } elseif (empty($client_info->language) && $this->config->item('language') == $lang->name) {
                                echo 'selected';
                              } ?>
                              ><?= ucfirst($lang->name) ?></option>
                            <?php endforeach;
                          } else {
                            ?>
                            <option
                            value="<?= $this->config->item('language') ?>"><?= ucfirst($this->config->item('language')) ?></option>
                            <?php
                          }
                          ?>
                        </select>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="12" version="1" class="u-caret"><path fill="currentColor" d="M4 8L0 4h8z"></path></svg>
                      </div>
                    </div>
                    <div class="u-form-group u-form-group-4">
                      <label for="text-fb14" class="u-label u-label-4">Username</label>
                      <input type="text" placeholder="Username" id="text-fb14" name="username" class="u-border-2 u-border-no-left u-border-no-right u-border-no-top u-border-white u-input u-input-rectangle u-input-4" required="required">
                    </div>
                    <div class="u-form-group u-form-group-5">
                      <label for="text-9055" class="u-label u-label-5">Password</label>
                      <input type="password" placeholder="Password" id="password" name="password" class="u-border-2 u-border-no-left u-border-no-right u-border-no-top u-border-white u-input u-input-rectangle u-input-5" required="required">
                    </div>
                    <div class="u-form-group u-form-group-6">
                      <label for="text-e9e4" class="u-label u-label-6">Confirm Password</label>
                      <input id="signupInputRePassword1" data-parsley-equalto="#password" type="password" placeholder="Confirm Password"  name="confirm_password" class="u-border-2 u-border-no-left u-border-no-right u-border-no-top u-border-white u-input u-input-rectangle u-input-6" required="required">
                    </div>
                    <div class="u-align-left u-form-group u-form-submit">
                      <a href="#" class="u-border-none u-btn u-btn-round u-btn-submit u-button-style u-radius-13 u-btn-2">CREATE PROFILE</a>
                      <input type="submit" value="submit" class="u-form-control-hidden" wfd-invisible="true">
                    </div>
                    <div class="u-form-send-message u-form-send-success" wfd-invisible="true"> Thank you! Your message has been sent. </div>
                    <div class="u-form-send-error u-form-send-message" wfd-invisible="true"> Unable to send your message. Please fix errors then try again. </div>
                    <input type="hidden" value="" name="recaptchaResponse" wfd-invisible="true">
                  </form>
                </div>
                <p class="u-align-center u-text u-text-5">
                  <a class="u-active-none u-border-none u-btn u-button-link u-button-style u-hover-none u-none u-text-body-alt-color u-btn-3" href="<?=site_url('login');?>">Already have an account? Login here.</a>
                </p>
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

  </body>
</html>