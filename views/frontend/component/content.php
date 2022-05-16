<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<div class="cps-main-wrap">
    <!-- Feature Box -->
    <div class="cps-section cps-section-padding-50 cps-bottom-0" id="feature" style="padding: 50px 0;">
        <?php $this->load->view('frontend/component/features'); ?>
    </div>
    <!-- Features Tab End -->

    <!-- Fun fact -->
    <div class="cps-section cps-image-bg-1 parallax" id="fun-fact" data-stellar-background-ratio="0.5">
        <div class="cps-overlay cps-section-padding">
            <div class="container">
                <div class="row">
                    <div class="cps-counter-items">
                        <div class="col-sm-3 col-xs-6">
                            <div class="cps-counter-item">
                                <div class="cps-counter-icon">
                                    <i class="fa fa-smile-o" aria-hidden="true"></i>
                                </div>
                                <h3 class="cps-fact-number"><span class="cps-count" data-form="0" data-to="<?= config_item('happy_clients') ?>"></span>
                                </h3>
                                <p class="cps-fact-name"><?= lang('happy_clients') ?></p>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <div class="cps-counter-item">
                                <div class="cps-counter-icon">
                                    <i class="fa  fa-trophy" aria-hidden="true"></i>
                                </div>
                                <h3 class="cps-fact-number"><span class="cps-count" data-form="0" data-to="<?= config_item('awards_win') ?>"></span>
                                </h3>
                                <p class="cps-fact-name"><?= lang('awards_win') ?></p>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <div class="cps-counter-item">
                                <div class="cps-counter-icon">
                                    <i class="fa fa-retweet" aria-hidden="true"></i>
                                </div>
                                <h3 class="cps-fact-number"><span class="cps-count" data-form="0" data-to=" <?= config_item('finished_work') ?>"></span>
                                </h3>
                                <p class="cps-fact-name"><?= lang('finished_work') ?></p>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <div class="cps-counter-item">
                                <div class="cps-counter-icon">
                                    <i class="fa fa-users" aria-hidden="true"></i>
                                </div>
                                <h3 class="cps-fact-number"><span class="cps-count" data-form="0" data-to="<?= config_item('team_members') ?>"></span>
                                </h3>
                                <p class="cps-fact-name"><?= lang('team_members') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fun fact end -->

    <!-- pricing us -->
    <div class="cps-section cps-section-padding-50 pt-20" id="pricing" style="background-color: #f0eded;">
        <div class="container">

            <div class="cps-section-header-20 text-center">
                <h3 class="cps-section-title"><?= lang('package_details') ?></h3>

            </div>
            <?php
            $error = $this->session->flashdata('error');
            $success = $this->session->flashdata('success');
            if (!empty($error)) {
            ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php } ?>
            <?php if (!empty($success)) { ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php } ?>

            <?php $this->load->view('frontend/component/pricing'); ?>
        </div>
    </div>
    <!-- About us end -->
    <?php

    $qry_testi = $this->db->get_where('tbl_customer_ratings', array('status' => 1)); 
    $customer_testimonials = $qry_testi->result();
    if($customer_testimonials){ 
        ?>
    <div class="cps-main-wrap">

        <!-- Testimonial -->
        <div class="cps-section cps-section-padding-50 cps-gray-bg cps-testimonial style-3">
            <div class="container">

                <div class="cps-section-header text-center">
                    <h3 class="cps-section-title"><?= lang('customer_testimonials') ?></h3>

                </div>

                <div class="cps-testimonials-wrap">
                    <div class="owl-carousel testimonial-carousel" id="testimonial-carousel-2">

                        <?php

                        foreach ($customer_testimonials as $qry_testi_res) {
                        ?>

                            <div class="cps-testimonial-item">
                                <div class="cps-commenter-pic">
                                    <img src="<?= $qry_testi_res->image; ?>" alt="<?= $qry_testi_res->ratings; ?>">
                                </div>
                                <div style="font-style: italic;">
                                    <i class="fa fa-quote-left" style="color: #2e39bf"></i> <?= $qry_testi_res->description; ?> <i class="fa fa-quote-right" style="color: #2e39bf"></i>
                                </div>
                                <h5 class="cps-reviewer-name"><?= $qry_testi_res->name; ?></h5>
                                <p class="cps-reviewer-position"><?= $qry_testi_res->position; ?></p>

                            </div>

                        <?php
                        }


                        ?>


                    </div>
                </div>
            </div>
        </div>
        <!-- Testimonial End -->

    </div>
<?php } ?>

    <!-- Contact -->
    <div class="cps-section cps-section-padding-50 contact_us parallax" id="contact_us" data-stellar-background-ratio="0.5">
        <div class="cps-overlay">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2 col-xs-12">
                        <div class="cps-section-header text-center">
                            <h3 class="cps-section-title"><?= lang('get_in_touch') ?></h3>
                            <p class="cps-section-text"></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <form id="contactForm" class="cps-contact-form" action="#" method="post">
                            <div class="row">
                                <div class="col-md-4">
                                    <input id="name" type="text" name="name" placeholder="Your Name">
                                    <input id="email" type="email" name="email" placeholder="Email">
                                    <input id="phone" type="tel" name="phone" placeholder="Phone">
                                </div>
                                <div class="col-md-8">
                                    <input id="subject" type="text" name="subject" placeholder="Subject">
                                    <textarea id="message" name="message" placeholder="Your Message Here"></textarea>
                                    <button type="submit" class="btn btn-blue">Send</button>
                                </div>
                            </div>
                            <p class="input-success">Your Message Sent. Thanks for Contacting. We will Contact You
                                Soon.</p>
                            <p class="input-error">Sorry, Something Went Wrong. Fill The Form Correctly and Try Again
                                Later.</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->

    <!-- Subscription -->
    <div class="cps-section cps-section-padding-50 cps-theme-bg" id="subscription-area">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="cps-section-header text-center">
                        <h3 class="cps-section-title"><?= lang('subscriptions') ?></h3>
                        <p class="cps-section-text"></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <form id="subscription" class="cps-subscription" action="#" method="post" novalidate="true">
                        <input type="email" name="sub_email" placeholder="Enter your email here" id="sub_email">
                        <button type="button" onclick="subscribe()"><i class="fa fa-paper-plane"></i></button>
                        <p class="input-success">Subscribed Successfully</p>
                        <p class="input-error">Subscription Error</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Subscription End -->

</div>