<footer>
    <div class="cps-footer-upper">
        <div class="container">
            <div class="cps-footer-widget-area">
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="cps-widget about-widget">
                            <a class="cps-footer-logo" href="#" style="display:none;">
                                <img src="<?= base_url(config_item('company_logo')) ?>"
                                     alt="<?= $title ?> Logo">
                            </a>
                            <p><?= config_item('company_description') ?></p>
                            <div class="cps-socials">
                                <?php
                                if (!empty(config_item('office_facebook'))) {
                                    ?>
                                    <a href="<?= config_item('office_facebook') ?>"><i
                                                class="fa fa-facebook"></i></a>
                                <?php }
                                ?>
                                <?php
                                if (!empty(config_item('office_instagram'))) {
                                    ?>
                                    <a href="<?= config_item('office_instagram') ?>"><i
                                                class="fa fa-instagram"></i></a>
                                <?php }
                                ?>
                                
                                <?php
                                if (!empty(config_item('office_twitter'))) {
                                    ?>
                                    <a href="<?= config_item('office_twitter') ?>"><i
                                                class="fa fa-twitter"></i></a>
                                <?php }
                                ?><?php
                                if (!empty(config_item('office_google_plus'))) {
                                    ?>
                                    
                                    <a href="<?= config_item('office_google_plus') ?>"><i
                                                class="fa fa-google-plus"></i></a>
                                <?php }
                                ?>
                                
                                <?php
                                if (!empty(config_item('office_linkedin'))) {
                                    ?>
                                    <a href="<?= config_item('office_linkedin') ?>"><i
                                                class="fa fa-linkedin"></i></a>
                                <?php }
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-md-offset-1 col-sm-6 col-xs-12 ">
                        <div class="cps-widget custom-menu-widget">
                            <h4 class="cps-widget-title"><?= lang('useful_links') ?></h4>
                            <ul class="widget-menu onepage-nav">
                                <li><a href="#banner"><?= lang('home') ?></a></li>
                                <li><a href="#features"><?= lang('features') ?></a></li>
                                <li><a href="#pricing"><?= lang('pricing') ?></a></li>
                                <li><a href="#resources"><?= lang('resources') ?></a></li>
                                <li><a href="#company"><?= lang('company') ?></a></li>
                                <li><a href="#contact_us"><?= lang('contact_us') ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="cps-widget custom-menu-widget">
                            <h4 class="cps-widget-title"><?= lang('contact_us') ?></h4>
                            <ul>
                                <li>
                                    <div class="continfo">
                                        <strong class="cps-footer-logo" style="margin: 0"><img
                                                    src="<?= base_url(config_item('company_logo')) ?>"
                                                    alt="<?= $title ?> Logo"></strong>
                                        <hr>
                                        <i class="fa fa-map-marker"></i> <?= config_item('office_address') ?>
                                    </div>
                                
                                </li>
                                <?php
                                if (!empty(config_item('office_contact_no'))) {
                                    ?>
                                    <li>
                                        
                                        <div class="continfo">
                                            <i class="fa fa-phone"></i>
                                            <a href="#"> <?= config_item('office_contact_no') ?></a>
                                        </div>
                                    </li>
                                <?php }
                                ?>
                                <?php
                                if (!empty(config_item('office_email'))) {
                                    ?>
                                    <li>
                                        
                                        <div class="continfo">
                                            <i class="fa fa-envelope-o"></i>
                                            <a href="#"><?= config_item('office_email') ?></a>
                                        </div>
                                    </li>
                                <?php }
                                ?>
                                <?php
                                if (!empty(config_item('office_time'))) {
                                    ?>
                                    <li>
                                        <div class="continfo">
                                            <i class="fa fa-clock-o"></i>
                                            <?= config_item('office_time') ?>
                                        </div>
                                    </li>
                                <?php }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="cps-footer-lower">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-xs-12 xs-text-center">
                    <p class="copyright">&copy; <a
                                href="<?= config_item('copyright_url') ?>"><?= config_item('copyright_name') ?></a>. All
                        Rights Reserved</p>
                </div>
                <div class="col-sm-6 col-xs-12 text-right xs-text-center">
                    <ul class="footer-menu">
                        <li><a href="javascript:" onclick="load_modal('privacy', 'Privacy Policy', 'lg')">
                                Privacy Policy</a></li>
                        <li><a href="javascript:"
                               onclick="load_modal('tos', 'Terms of Service, Cancellation Policy', 'lg')"> Terms of
                                Service, Cancellation Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<script type="text/javascript">

    function goTojobs() {
        location.href = '<?= base_url('frontend/jobs');?>';
    }

    function load_modal(control, title, modal_size) // control defines modal page content type , title defines modal heading title
    {
        var formData = {
            'control': control,
            'title': title
        };

        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: '<?= base_url()?>frontend/get_page_content/', // the url where we want to POST
            data: formData, // our data object
            success: function (res) {

                $('#modal-' + modal_size + '-con').html(res);
                $('#modal-' + modal_size).modal();
            }
        })
    }

    /*-----------------------------------
    Contact Form
    -----------------------------------*/
    // Function for email address validation
    function isValidEmail(emailAddress) {
        var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);

        return pattern.test(emailAddress);

    }

    $("#contactForm").on('submit', function (e) {
        e.preventDefault();
        var data = {
            name: $("#name").val(),
            email: $("#email").val(),
            phone: $("#phone").val(),
            subject: $("#subject").val(),
            message: $("#message").val()
        };

        if (isValidEmail(data['email']) && (data['message'].length > 1) && (data['name'].length > 1)
            && (data['phone'].length > 1)) {
            $.ajax({
                type: "POST",
                url: "<?= base_url()?>frontend/contact",
                data: data,
                success: function () {
                    $('#contactForm .input-success').delay(500).fadeIn(1000);
                    $('#contactForm .input-error').fadeOut(500);
                }
            });
        } else {
            $('#contactForm .input-error').delay(500).fadeIn(1000);
            $('#contactForm .input-success').fadeOut(500);
        }

        return false;
    });

    /*-----------------------------------
    Subscription
    -----------------------------------*/


    function subscribe() {

        var data = {
            email: $("#sub_email").val(),
        };

        if (isValidEmail(data['email'])) {

            $.ajax({
                type: "POST",
                url: "<?= base_url()?>frontend/subscribe",
                data: data,
                success: function (res) {
                    // alert('success');
                    $("#sub_email").val('');
                    $('#subscription .input-success').fadeIn(500);
                }
            });
        } else {
            $('#subscription .input-error').fadeIn(500);
        }

    }

</script>

<style>
    #feature {
        padding-bottom: 30px;
    }

    @media (min-width: 768px) {
        .modal-xl {
            width: 90%;
            max-width: 1200px;
        }
    }

</style>


<div class="modal fade bd-example-modal-lg" id="modal-lg" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="modal-lg-con">
            ...
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="modal-xl" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" id="modal-xl-con">
            ...
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-sm" id="modal-sm" tabindex="-1" role="dialog"
     aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" id="modal-sm-con">
            ...
        </div>
    </div>
</div>


<!-- Script -->
<script src="<?php echo base_url(); ?>assets/frontend/v2/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/v2/js/jquery-migrate-3.0.1.min.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/v2/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/v2/js/jquery.nav.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/v2/js/owl.carousel.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/v2/js/visible.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/v2/js/jquery.stellar.min.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/v2/js/jquery.countTo.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/v2/js/imagesloaded.pkgd.min.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/v2/js/isotope.pkgd.min.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/v2/js/jquery.magnific-popup.min.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/v2/js/jquery.ajaxchimp.min.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/v2/js/plyr.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/v2/js/swiper.min.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/v2/js/slick.min.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/v2/js/toastr.min.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/v2/js/custom.js"></script>


</body>
</html>