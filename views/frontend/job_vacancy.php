<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Site information -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Job List</title>
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


<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<style>
    .cps-service-box {
        background-color: #ffffff;
        -webkit-box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        text-align: left;
        padding: 20px 10px;
        border-width: 0 0 5px 0;
        border-style: solid;
        border-image-slice: 1;
        margin-bottom: 30px;
        min-height: 300px;
    }
    .container .page-title ,.container .white-text{
        color: #fff !important;
    }
</style>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Job List</h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>" class ="white-text" tppabs="<?= base_url('frontend'); ?>"><?= lang('home') ?></a> 
                <span  class ="white-text">></span>
            </li>
            <li class="active white-text"><?= lang('career') ?></li>
        </ol>
    </div>
</div>
<div class="cps-main-wrap">

    <!-- Services -->
    <div class="cps-section cps-section-padding" id="cps-service">
        <div class="container">
            <div class="row">
                <?php
                $all_job_circular = get_order_by('tbl_job_circular', array('status' => 'published'), 'posted_date');
                if (!empty($all_job_circular)):foreach ($all_job_circular as $v_job_circular):
                    $last_date = $v_job_circular->last_date;
                    $current_time = date('Y-m-d');
                    if ($current_time > $last_date) {
                        $ribon = 'danger';
                        $text = lang('expired');
                    } elseif ($current_time == $last_date) {
                        $ribon = 'info';
                        $text = lang('last_date');
                    } else {
                        $lastdate = date('Y-m-d', strtotime($v_job_circular->last_date));
                        $today = date('Y-m-d');
                        $datetime1 = new DateTime($today);
                        $datetime2 = new DateTime($lastdate);
                        $interval = $datetime1->diff($datetime2);

                        $ribon = 'success';
                        $text = $interval->format('%R%a') . lang('days');
                    }

                    $design_info = $this->db->where('designations_id', $v_job_circular->designations_id)->get('tbl_designations')->row();
                    if (!empty($design_info->designations)) {
                        $designation = $design_info->designations;
                    } else {
                        $designation = '-';
                    }
                    ?>
                    <div class="col-lg-4 cps-service-box">
                        <!-- START widget-->
                        <div class="panel widget">
                            <div class="row row-table row-flush">

                                <div class="panel-body">
                                    <div class="invoice-ribbon">
                                        <div class="ribbon-inner label-<?= $ribon ?>"><?= $text ?></div>
                                    </div>
                                    <p>
                                        <a href="<?= base_url() ?>frontend/circular_details/<?= $v_job_circular->job_circular_id ?>">
                                            <strong
                                                    style="font-size: 17px;: "><?= $v_job_circular->job_title . ' ( ' . $designation . ' ) ' ?></strong>
                                        </a>
                                    </p>
                                    <hr class=" mt0 row"/>
                                   
                                    <p class="m0">
                                        <strong><?= lang('vacancy_no') ?>: <?= $v_job_circular->vacancy_no ?></strong>
                                        <strong class="pull-right"><?= lang('employment_type') ?>
                                            : <?= lang($v_job_circular->employment_type) ?></strong>
                                    </p>
                                    <p>
                                        <strong> <?= lang('posted_date') ?>
                                            : <?= strftime(config_item('date_format'), strtotime($v_job_circular->posted_date)) ?>
                                        </strong>
                                        <strong class="pull-right"> <?= lang('last_date') ?>
                                            : <?= strftime(config_item('date_format'), strtotime($v_job_circular->last_date)) ?>
                                        </strong>
                                    </p>
                                    <p>

                                        <?php
                                        $max_len = 600; // Only show 300 characters //
                                        $string = $v_job_circular->description;
                                        echo strip_tags(strlen($string) > $max_len ? mb_substr($string, 0, $max_len) . ' <strong> .....</strong><a href="' . base_url() . 'frontend/circular_details/' . $v_job_circular->job_circular_id . '">' . lang('more') . '</a>' : $string, '<strong><a>');
                                        ?>
                                    <p>
                                        <div>
                                            <a class="btn btn-primary" href="<?=base_url('frontend/circular_details/' . $v_job_circular->job_circular_id );?>">Apply Now</a>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <!-- END widget-->
                    </div>
                <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-lg-4">
                        <!-- START widget-->
                        <div class="panel widget">
                            <div class="row row-table row-flush">

                                <div class="panel-body">
                                    <?= lang('nothing_to_display') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>