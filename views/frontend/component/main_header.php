<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Site information -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <meta name="description" content="">
    
    <!-- External CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/v2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/v2/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/v2/css/themify-icons.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/v2/css/magnific-popup.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/v2/css/owl.carousel.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/v2/css/owl.transitions.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/v2/css/plyr.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/v2/css/swiper.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/v2/css/slick.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/v2/css/primary.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/v2/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/v2/css/preloader.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/v2/css/responsive.css">
    <!-- flaticon css -->
    <link href="<?= base_url() ?>assets/frontend/v2/css/flaticon.css" rel="stylesheet">
    <!-- animate css -->
    <link href="<?= base_url() ?>assets/frontend/v2/css/animate.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans%7CLato:400,600,900" rel="stylesheet">
    
    <!-- Favicon -->
    <?php if (config_item('favicon') != '') : ?>
        <link rel="icon" href="<?php echo base_url() . config_item('favicon'); ?>" type="image/png">
    <?php else: ?>
        <link rel="icon" href="<?php echo base_url('assets/img/favicon.ico'); ?>" type="image/png">
    <?php endif; ?>
    
    <!--[if lt IE 9]>
    <script src="<?php echo base_url(); ?>assets/frontend/v2/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/frontend/v2/js/respond.min.js"></script>
    <![endif]-->
    
    <!-- jquery Liabrary -->
    <script src="<?= base_url() ?>assets/frontend/v2/js/jquery.min.js"></script>

</head>

<body>

<!-- Preloader -->
<div class="preloader-wrap" id="preloader-wrap" style="display: none;">
    <div class="preloader">
        <div class="preloader-round"></div>
        <div class="preloader-middle"></div>
        <div class="preloader-center"></div>
    </div>
</div>
<!-- Preloader End -->

<!-- Header -->
<nav class="navbar navbar-default affix" data-spy="affix" data-offset-top="60">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse"
                    aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo base_url(); ?>">
                <img src="<?= base_url(config_item('company_logo')) ?>"
                     alt="<?= $title ?> Logo"></a>
        </div>
        <?php
        $url = $this->uri->segment(2);
        ?>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right <?= !empty($url) && $url == 'jobs' ? ' ' : 'onepage-nav' ?> ">
                <li class="current"><a
                            href="<?= !empty($url) && $url == 'jobs' ? base_url() : '#home' ?>"><?= lang('home') ?></a>
                </li>
                <li>
                    <a href="<?= !empty($url) && $url == 'jobs' ? base_url() . '#feature' : '#feature' ?>"><?= lang('features') ?></a>
                </li>
                <li>
                    <a href="<?= !empty($url) && $url == 'jobs' ? base_url() . '#pricing' : '#pricing' ?>"><?= lang('pricing') ?></a>
                </li>
                
                <li class="dropdown">
                    <a href="#"><?= lang('company') ?></a>
                    <ul class="dropdown-menu">
                        <li><a href=""
                               onclick="load_modal('about_us', '<?= lang('about_us') ?>', 'xl')"><?= lang('about_us') ?></a>
                        </li>
                        <li><a href=""
                               onclick="load_modal('team', '<?= lang('our_team') ?>', 'xl')"><?= lang('our_team') ?></a>
                        </li>
                        <li><a href=""
                               onclick="load_modal('partners', '<?= lang('partners') ?>',  'xl')"><?= lang('partners') ?></a>
                        </li>
                        <li class="<?= (!empty($url) && $url == 'jobs' ? 'active' : '') ?>"><a href=""
                                                                                               onclick="goTojobs()"><?= lang('career') ?></a>
                        </li>
                    </ul>
                </li>
                
                <li><a href="<?= !empty($url) && $url == 'jobs' ? base_url() . '#contact_us' : '#contact_us' ?>"><?= lang('pre_sale_question') ?></a></li>
                
                <li style="margin-top: 5px ; margin-left: 15px;">
                    <div>
                        <a class="btn btn-main" href="<?= !empty($url) && $url == 'jobs' ? base_url() . '#pricing' : '#pricing' ?>"><?= lang('try_now') ?></a>
                        <a class="btn btn-main" href=""
                           onclick="window.location.href='<?= base_url('login') ?>'"><?= lang('login') ?></a>
                    </div>
                </li>
            
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
<!-- Header End -->