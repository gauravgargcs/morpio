<?php
$currecy_wise_price = get_old_data('tbl_currencywise_price', array('frontend_pricing_id' => $package_info->id, 'currency' => $currency_type));
if (!empty($currency_type)) {
    $currency_code = $currency_type;
} else {
    $currency_code = config_item('default_currency');
}
$currency = get_old_data('tbl_currencies', array('code' => $currency_code));
if ($interval_type == 'monthly') {
    $renew_date = '1 month';
} else {
    $renew_date = '1 year';
}
?>
<div class="col-sm-8 ">
    <div class="row">
        <label for="discount_type" class="control-label col-sm-3"><?= lang('billing_cycle') ?><span class="required">*</span></label>
        <div class="col-sm-5">
            <?php
            $int_val = 1;
            $int_type = 1;
            $options = array(
                $int_val . lang($int_type) => $int_val . ' ' . lang($int_type),
                $int_val * 3 . lang($int_type) => $int_val * 3 . ' ' . lang($int_type),
                $int_val * 6 . lang($int_type) => $int_val * 6 . ' ' . lang($int_type),
                $int_val * 12 . lang($int_type) => $int_val * 12 . ' ' . lang($int_type),
                $int_val * 24 . lang($int_type) => $int_val * 24 . ' ' . lang($int_type),
                $int_val * 36 . lang($int_type) => $int_val * 36 . ' ' . lang($int_type),
            );

            $options = array(
                'monthly' => lang('monthly'),
                'annually' => lang('yearly'),
            );
            if ($interval_type == 'monthly') {
                $c_amount = $currecy_wise_price->monthly;
            } else {
                $c_amount = $currecy_wise_price->yearly;
            }
            echo form_dropdown('billing_cycle', $options, $interval_type, 'style="width:100%" id="billing_cycle"  class="form-control form-select"'); ?>
            <small><?= lang('plan_renews') ?> <span class="plan_renews"><?= date("Y-m-d", strtotime("+ " . $renew_date)); ?></span>
                @ <?= display_money($c_amount, $currency->symbol) ?>/<?= lang($interval_type) ?></small>
        </div>
        <input type="hidden" class="form-control" id="pricewise_currency" name="currency" value="<?= $currency_code ?>">
        <input type="hidden" class="form-control" id="interval_type" name="interval_type" value="<?= $interval_type ?>">

        <script type="text/javascript">
            $(document).ready(function() {
                $('select[name="billing_cycle"]').change(function() {
                    var pricing_id = $('#pricing_id').val();
                    var pricewise_currency = $('#pricewise_currency').val();
                    var interval_type = $(this).val();
                    $.ajax({
                        type: 'GET', // define the type of HTTP verb we want to use (POST for our form)
                        url: '<?= base_url() ?>admin/global_controller/pricing_change_data/' + pricing_id + '/' + pricewise_currency + '/' + interval_type, // the url where we want to POST
                        dataType: 'json', // what type of data do we expect back from the server
                        encode: true,
                        success: function(res) {
                            if (res) {
                                $('#checkout_payment').html(res.set_merge_info);
                            } else {
                                alert('There was a problem with AJAX');
                            }
                        }
                    })
                });

            });
        </script>
        <div class="col-sm-4">
            <div class="checkbox c-checkbox">
                <label class="mt-2">
                    <input type="checkbox" id="is_coupon">
                   
                    <?= lang('i_have_coupon_code') ?>
                </label>
            </div>
        </div>
    </div>
    <div class="row" id="coupon_code_area" style="display: none">
        <label for="discount_type" class="control-label col-sm-3"><?= lang('enter_coupon_code') ?><span class="required">*</span></label>
        <div class="col-sm-5">
            <input type="text" class="form-control" id="coupon_code" name="coupon_code" value="" placeholder="<?= lang('enter_coupon_code') ?>">
            <span class="required" id="discount_error"></span>
        </div>
        <div class="col-sm-3">
            <button id="btn_coupon_code" type="button" class="btn btn-primary btnSubmit"><?= lang('apply') ?></button>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#is_coupon').change(function() {
                if (this.checked)
                    $('#coupon_code_area').show();
                else
                    $('#coupon_code_area').hide();
            });

            $('#coupon_code_area').hide();

            var is_coupon = $('#is_coupon').is(':checked');

            $('#discount_area').hide();
            $('button[id="btn_coupon_code"]').click(function() {
                var formData = {
                    'coupon_code': $('#coupon_code').val(),
                    'billing_cycle': $('#billing_cycle').val(),
                    'pricing_id': $('#pricing_id').val(),
                    'pricewise_currency': $('#pricewise_currency').val(),
                    'subscriptions_id': $('#subscriptions_id').val(),
                };
                $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: '<?= base_url() ?>admin/global_controller/get_coupon_data/', // the url where we want to POST
                    data: formData, // our data object
                    dataType: 'json', // what type of data do we expect back from the server
                    encode: true,
                    success: function(res) {
                        if (res.success) {
                            $('#discount_area').show();
                            $('.discount_amount_text').html(res.discount_amount_text);
                            $('.discount_amount_input').val(res.discount_amount_input);
                            $('#discount_percent').val(res.discount_percentage);
                            $('.discount_percent').html(res.discount_percentage);
                            $('.sub_total_text').html(res.sub_total_text);
                            $('.sub_total_input').val(res.sub_total_input);
                            $('.total_text').html(res.total_text);
                            $('.total_input').val(res.total_input);
                            $('#coupon_code_input').val(res.coupon_code_input);
                            $('#discount_error').html(null);

                        } else if (res.error) {
                            $('#discount_area').hide();
                            $('.discount_amount_text').html(null);
                            $('.discount_amount_input').val(null);
                            $('#discount_percent').val(null);
                            $('.discount_percent').html(null);
                            $('#discount_error').html(res.message);
                            $('.sub_total_text').html(res.sub_total_text);
                            $('.sub_total_input').val(res.sub_total_input);
                            $('.total_text').html(res.total_text);
                            $('.total_input').val(res.total_input);
                            $('#coupon_code_input').val(res.coupon_code_input);
                           
                        } else {
                            alert('There was a problem with AJAX');
                        }
                           if(res.total_input==0){
                                $('.nopad').hide();
                                $('input:radio[value="manually_active"]').prop('checked',true);
                            }else{
                                 $('.nopad').show();
                                 $('input:radio[value="manually_active"]').prop('checked',false);
                            }
                    }
                })

            });
             if($('.total_input').val()==0){
                                $('.nopad').hide();
                                $('input:radio[value="manually_active"]').prop('checked',true);
                            }else{
                                 $('.nopad').show();
                                 $('input:radio[value="manually_active"]').prop('checked',false);
                            }

        });
    </script>
    <table class="table text-right">
        <tbody>
            <tr id="">
                <td><span class="bold"><?= lang('247368_phone_livechat_email') ?> :</span>
                </td>
                <td>
                    <h4 class="m0"><?= lang('free') ?></h4>
                </td>
            </tr>
            <tr id="">
                <td><span class="bold"><?= lang('instant_account_activation') ?> :</span>
                </td>
                <td>
                    <h4 class="m0"><?= lang('free') ?></h4>
                </td>
            </tr>
            <tr id="">
                <td><span class="bold"><?= lang('money_back_guarantee') ?> :</span>
                </td>
                <td>
                    <h4 class="m0"><?= $package_info->trial_period + 1 . ' ' . lang('days') ?></h4>
                </td>
            </tr>
            <tr>
                <td><span class="bold"><?= lang('sub_total') ?> :</span>
                </td>
                <td><strong class="sub_total_text"><?= display_money($c_amount, $currency->symbol) ?></strong>
                    <input type="hidden" class="sub_total_input" name="subtotal" value="<?= $c_amount ?>">
                </td>
                <input type="hidden" name="renew_date" id="renew_date" value="<?= date("Y-m-d", strtotime("+ " . $renew_date)); ?>"></td>
            </tr>
            <tr id="discount_area">
                <td>
                    <span class="bold"><?= lang('discount') ?>(<span class="discount_percent"></span>) :</span>
                </td>
                <td><strong class="discount_amount_text"></strong>
                    <input type="hidden" name="discount_amount" value="" id="discount_amount_input" class="discount_amount_input">
                    <input type="hidden" id="discount_percent" name="discount_percent" value="">
                    <input type="hidden" id="coupon_code_input" name="coupon_code_input">
                </td>
            </tr>
            <tr>
                <td>
                    <h4 class="bold"><?= lang('total') ?> :</h4>
                </td>
                <td>
                    <h4 class="m0"><span class="total_text"> <?= display_money($c_amount, $currency->symbol) ?></span>
                    </h4>
                    <input type="hidden" name="total" class="total_input" value="<?= ($c_amount) ?>">
                </td>
            </tr>
        </tbody>
    </table>
    <script type="text/javascript">
        $(document).ready(function() {
            $('select[name="billing_cycle"]').change(function() {
                var billing_cycle = $(this).val();
                var formData = {
                    'billing_cycle': billing_cycle,
                    'pricing_id': $('#pricing_id').val(),
                    'discount_amount': $('#discount_amount_input').val(),
                };
                $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: '<?= base_url() ?>admin/global_controller/package_renews_date/', // the url where we want to POST
                    data: formData, // our data object
                    dataType: 'json', // what type of data do we expect back from the server
                    encode: true,
                    success: function(res) {
                        if (res) {
                            $('.plan_renews').html(res.renew_date);
                            $('#renew_date').val(res.renew_date);
                            $('.sub_total_text').html(res.sub_total_text);
                            $('.sub_total_input').val(res.sub_total_input);
                            $('.total_text').html(res.total_text);
                            $('.total_input').val(res.total_input);
                        } else {
                            alert('There was a problem with AJAX');
                        }
                    }
                })
            });

        });
    </script>

    <style type="text/css">
        .image-radio {
            cursor: pointer;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            border: 4px solid transparent;
            margin-bottom: 0;
            outline: 0;
            /*border: 1px solid red;*/
            overflow: hidden;
        }

        .image-radio .thumnail {
            width: 100%;
            height: 50px;
        }

        .image-radio input[type="radio"] {
            display: none;
        }

        .image-radio-checked {
            border-color: #556ee6;
        }

        .image-radio .fa {
            position: absolute;
            color: #fff;
            background: #564aa38c;
            padding: 6px 17px 10px 14px;
            top: 7%;
            font-size: 34px;
            /* right: 17%; */
            /*padding: 10px;*/
            /*top: 0;*/
            /*right: 0;*/
        }

        .image-radio-checked .fa {
            display: block !important;
        }
    </style>


    <script type="text/javascript">
        $(document).ready(function() {
            // add/remove checked class
            $(".image-radio").each(function() {
                if ($(this).find('input[type="radio"]').first().attr("checked")) {
                    $(this).addClass('image-radio-checked');
                } else {
                    $(this).removeClass('image-radio-checked');
                }
            });

            // sync the input state
            $(".image-radio").on("click", function(e) {
                $(".image-radio").removeClass('image-radio-checked');
                $(this).addClass('image-radio-checked');
                var $radio = $(this).find('input[type="radio"]');
                $radio.prop("checked", !$radio.prop("checked"));

                e.preventDefault();
            });
        });
    </script>


    <div class="row" style="box-shadow: 0 0px 0px 0 rgba(0,0,0,.15);">
        <!-- Default panel contents -->
        <div class="nopad">
            <h4><?= lang('select') . ' ' . lang('payment_method') ?></h4>
        </div>
        <?php if ($package_info->allow_paypal == 'Yes') {
        ?>
            <div class="col-md-2 nopad text-center" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('paypal') ?>">
                <label class="image-radio">
                    <img class="thumnail" src="<?= base_url('asset/images/payment_logo/paypal.png') ?>" />
                    <input type="radio" required name="payment_method" value="paypal" />
                    
                </label>
            </div>
        <?php
        }
        if ($package_info->allow_2checkout == 'Yes') {
        ?>
            <div class="col-md-2 nopad text-center" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('2checkout') ?>">
                <label class="image-radio">
                    <img class="thumnail" style="width: 66px" src="<?= base_url('asset/images/payment_logo/2checkout.jpg') ?>" />
                    <input type="radio" required name="payment_method" value="two_checkout" />
                    
                </label>
            </div>
        <?php }
        if ($package_info->allow_stripe == 'Yes') {
        ?>
            <div class="col-md-2 nopad text-center" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('stripe') ?>">
                <label class="image-radio">
                    <img class="thumnail" src="<?= base_url('asset/images/payment_logo/stripe.jpg') ?>" />
                    <input type="radio" required name="payment_method" value="stripe" />
                    
                </label>
            </div>
        <?php }
        if ($package_info->allow_authorize == 'Yes') { ?>
            <div class="col-md-2 nopad text-center" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('authorize') ?>">
                <label class="image-radio">
                    <img class="thumnail" src="<?= base_url('asset/images/payment_logo/Authorizenet.png') ?>" />
                    <input type="radio" required name="payment_method" value="authorize" />
                    
                </label>
            </div>
        <?php }
        if ($package_info->allow_ccavenue == 'Yes') { ?>
            <div class="col-md-2 nopad text-center" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('ccavenue') ?>">
                <label class="image-radio">
                    <img class="thumnail" src="<?= base_url('asset/images/payment_logo/CCAvenue.jpg') ?>" />
                    <input type="radio" required name="payment_method" value="ccavenue" />
                    
                </label>
            </div>
        <?php }
        if ($package_info->allow_braintree == 'Yes') { ?>
            <div class="col-md-2 nopad text-center" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('braintree') ?>">
                <label class="image-radio">
                    <img class="thumnail" src="<?= base_url('asset/images/payment_logo/Braintree.png') ?>" />
                    <input type="radio" required name="payment_method" value="braintree" />
                    
                </label>
            </div>
        <?php }
        if ($package_info->allow_mollie == 'Yes') { ?>
            <div class="col-md-2 nopad text-center" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('mollie') ?>">
                <label class="image-radio">
                    <img class="thumnail" src="<?= base_url('asset/images/payment_logo/ideal_mollie.png') ?>" />
                    <input type="radio" required name="payment_method" value="mollie" />
                    
                </label>
            </div>
        <?php }
        if ($package_info->allow_payumoney == 'Yes') { ?>
            <div class="col-md-2 nopad text-center" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('PayUmoney') ?>">
                <label class="image-radio">
                    <img class="thumnail" src="<?= base_url('asset/images/payment_logo/payumoney.png') ?>" />
                    <input type="radio" required name="payment_method" value="payu_money" />
                    
                </label>
            </div>
        <?php }
      $sub_domain = is_subdomain($_SERVER['HTTP_HOST']);
        if (!empty($super_admin) && !$sub_domain) { 
        
            if (!empty($companies_id)) {
                $c_id = '/' . $companies_id;
            } else {
                $c_id = null;
            }
        ?>
            <div class="col-md-2 nopad text-center" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('manually_active') ?>">
                <label class="image-radio">
                    <img class="thumnail" src="<?= base_url('asset/images/payment_logo/cash_payment.png') ?>" />
                    <input type="radio" required name="payment_method" value="manually_active" />
                    
                </label>
            </div>
        <?php }else{ ?>
              <input type="radio" class="hide" required name="payment_method" value="manually_active" />
       <?php } ?>
    </div>
    <div class="col-md-12">
        <div class="checkbox c-checkbox mt-lg mb-lg pull-left">
            <label class="needsclick">
                <input type="checkbox" required name="i_have_read_agree">
                
                <strong class="required"><?= lang('i_have_read_agree') ?></strong>
                <a target="_blank" href="<?= base_url('tos') ?>"><?= lang('tos') ?></a>
                <strong class="required"><?= lang('and') ?></strong>
                <a target="_blank" href="<?= base_url('privacy') ?>"><?= lang('privacy') ?></a>
            </label>
        </div>
        <div class="col-md-3 pull-right ">
            <button type="submit" class="btn btn-success btn-block btn-lg"><?= lang('checkout') ?></button>
        </div>
    </div>
</div>

<div class="col-md-4">
    <style type="text/css">
        section.package-section {
            background: #fff;
            color: #7a90ff;
            /*padding: 2em 0 8em;*/
            min-height: 100vh;
            position: relative;
            -webkit-font-smoothing: antialiased;
            /*margin-top: 30px;*/
        }

        .packaging {
            display: -webkit-flex;
            display: flex;
            -webkit-flex-wrap: wrap;
            flex-wrap: wrap;
            -webkit-justify-content: center;
            justify-content: center;
            width: 100%;
            margin: 0 auto 3em;
        }

        .packaging-item {
            position: relative;
            display: -webkit-flex;
            display: flex;
            -webkit-flex-direction: column;
            flex-direction: column;
            -webkit-align-items: stretch;
            align-items: stretch;
            text-align: center;
            -webkit-flex: 0 1 550px;
            flex: 0 1 550px;
            margin-bottom: 2em !important;
        }

        .packaging-action {
            color: inherit;
            border: none;
            background: none;
        }

        .packaging-action:focus {
            outline: none;
        }

        .packaging-feature-list {
            text-align: left;
        }

        .packaging-palden .packaging-item {
            font-family: 'Open Sans', sans-serif;
            cursor: default;
            color: #84697c;
            background: #fff;
            box-shadow: 0 0 10px rgba(46, 59, 125, 0.23);
            /*border-radius: 20px 20px 10px 10px;*/
            margin: 1em;
        }

        @media screen and (min-width: 66.25em) {
            .packaging-palden .packaging-item {
                margin: 1em -0.5em;
            }

            .packaging-palden .packaging__item--featured {
                margin: 0;
                z-index: 10;
                box-shadow: 0 0 20px rgba(46, 59, 125, 0.23);
            }
        }

        .packaging-palden .packaging-deco {
            /*border-radius: 10px 10px 0 0;*/
            padding: 4em 0 9em;
            position: relative;
        }

        .packaging-palden .packaging-deco-img {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 160px;
        }

        .packaging-palden .packaging-title {
            font-size: 0.75em;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 5px;
            color: #fff;
        }

        .packaging-palden .deco-layer {
            -webkit-transition: -webkit-transform 0.5s;
            transition: transform 0.5s;
        }

        .packaging-palden .packaging-item:hover .deco-layer--1 {
            -webkit-transform: translate3d(15px, 0, 0);
            transform: translate3d(15px, 0, 0);
        }

        .packaging-palden .packaging-item:hover .deco-layer--2 {
            -webkit-transform: translate3d(-15px, 0, 0);
            transform: translate3d(-15px, 0, 0);
        }

        .packaging-palden .icon {
            font-size: 2.5em;
        }

        .packaging-palden .packaging-package {
            font-size: 5em;
            font-weight: bold;
            padding: 0;
            color: #fff;
            margin: 0 0 0.25em 0;
            line-height: 0.75;
        }

        .packaging-palden .packaging-currency {
            font-size: 0.15em;
            vertical-align: top;
        }

        .packaging-palden .packaging-period {
            font-size: 0.15em;
            padding: 0 0 0 0.5em;
            font-style: italic;
        }

        .packaging-palden .packaging__sentence {
            font-weight: bold;
            margin: 0 0 1em 0;
            padding: 0 0 0.5em;
        }

        .packaging-palden .packaging-feature-list {
            margin: 0;
            padding: 0.25em 0 15px;
            list-style: none;
            /*text-align: center;*/
        }

        .packaging-palden .packaging-feature {
            padding: 2px 0;
        }

        .packaging-palden li.packaging-feature {
            border-bottom: 1px dashed #564aa3;
            margin-right: 33px;
            margin-left: 20px;
        }

        .packaging-palden .packaging-action {
            font-weight: bold;
            margin: auto 3em 2em 3em;
            padding: 1em 2em;
            color: #fff;
            border-radius: 30px;
            -webkit-transition: background-color 0.3s;
            transition: background-color 0.3s;
        }

        .packaging-palden .packaging-action:hover,
        .packaging-palden .packaging-action:focus {
            background-color: #3378ff;
        }

        .packaging-palden .packaging-item--featured .packaging-deco {
            padding: 5em 0 8.885em 0;
        }

        .packaging-feature i {
            font-size: 15px;
            float: left;
            margin: 0px 8px 0px 0px;
            ;
        }

        .custom_ul {
            list-style: none;
            padding: 0.25em 13px 0px;
            ;
        }

        .custom_ul li {
            border-bottom: 1px dashed #564aa3;
            padding: 4px 0px;
        }

        .custom_ul li a {
            padding: 0.25em 0 2.5em;
        }

        .custom_ul i {
            font-size: 15px;
            /*float: left;*/
            margin: 0px 8px 0px 0px;
            ;
        }
        .custom-bg-2{
            background: #556ee6;
        }
    </style>

    <section class="package-section" id="currency_wise_price_details">
        <div class='packaging packaging-palden'>
            <div class='packaging-item'>
                <div class='packaging-deco custom-bg-2'>
                    <svg class='packaging-deco-img' enable-background='new 0 0 300 100' height='100px' id='Layer_1' preserveAspectRatio='none' version='1.1' viewBox='0 0 300 100' width='300px' x='0px' xml:space='preserve' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns='http://www.w3.org/2000/svg' y='0px'>
                        <path class='deco-layer deco-layer--1' d='M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729&#x000A;	c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z' fill='#FFFFFF' opacity='0.6'></path>
                        <path class='deco-layer deco-layer--2' d='M-35.667,44.628c0,0,42.91-34.463,87.51-14.191c77.31,35.141,113.304-1.952,146.639-4.729&#x000A;	c48.653-4.055,69.939,16.218,69.939,16.218v54.396H-35.667V44.628z' fill='#FFFFFF' opacity='0.6'></path>
                        <path class='deco-layer deco-layer--3' d='M43.415,98.342c0,0,48.283-68.927,109.133-68.927c65.886,0,97.983,67.914,97.983,67.914v3.716&#x000A;	H42.401L43.415,98.342z' fill='#FFFFFF' opacity='0.7'></path>
                        <path class='deco-layer deco-layer--4' d='M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428&#x000A;	c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z' fill='#FFFFFF'></path>
                    </svg>

                    <div class='packaging-package'><span class='packaging-currency'> <?= $currency->symbol ?></span><?= ($interval_type == 'monthly' ? $currecy_wise_price->monthly : $currecy_wise_price->yearly) ?>
                        <span class='packaging-period'>/ <?= lang($interval_type) ?></span>
                    </div>
                    <h3 class='packaging-title'><?= $package_info->name ?></h3>
                </div>
                <ul class='packaging-feature-list'>
                    <li class='packaging-feature'><?= pricing_format_admin_YN($package_info->multi_branch, lang('multi_branch')) ?></li>
                    <?php
                    $all_module = get_old_data('tbl_modules', array('active' => 1, 'module_name !=' => 'mailbox'), true);
                    if (!empty($all_module)) {
                        foreach ($all_module as $v_module) {
                            $name = 'allow_' . $v_module->module_name; ?>
                            <li class='packaging-feature'> <?= pricing_format_admin_YN($package_info->$name, lang($v_module->module_name)); ?></li>
                        <?php }
                    } ?>
                    <li class='packaging-feature'><?= pricing_format_admin($package_info->employee_no, lang('employee')) ?></li>
                    <li class='packaging-feature'><?= pricing_format_admin($package_info->disk_space, lang('disk_space')) ?></li>
                    <li class='packaging-feature'><?= pricing_format_admin($package_info->trial_period, lang('days') . ' ' . lang('trail_period')) ?></li>
                    <li class='packaging-feature'><?= pricing_format_admin($package_info->client_no, lang('client')) ?></li>
                    <li class='packaging-feature'><?= pricing_format_admin($package_info->project_no, lang('project')) ?></li>
                    <li class='packaging-feature'><?= pricing_format_admin($package_info->invoice_no, lang('invoice')) ?></li>
                    <li class='packaging-feature'><?= pricing_format_admin($package_info->leads, lang('leads')) ?></li>
                    <li class='packaging-feature'><?= pricing_format_admin($package_info->accounting, lang('accounting')) ?></li>
                    <li class='packaging-feature'><?= pricing_format_admin($package_info->bank_account, lang('bank') . ' ' . lang('account')) ?></li>
                    <li class='packaging-feature'><?= pricing_format_admin($package_info->tasks, lang('tasks')) ?></li>
                </ul>
                <a data-bs-toggle='modal' data-bs-target='#myModal' href="<?= base_url('admin/global_controller/package_details/' . $package_info->id) ?>" class="text-center mb-lg"><?= lang('see') . ' ' . lang('details') ?></a>
            </div>
        </div>
    </section>
</div>
