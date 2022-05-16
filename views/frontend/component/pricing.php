<div class="full-body">
    <style type="text/css">
        #generic_price_table {
            /*background-color: #f0eded;*/
        }

        .full-body {
            overflow: hidden
        }

        .interval_type,
        .currency_type {
            margin-top: 15px;
            margin-left: 30px;
            margin-right: 30px;

        }

        /*PRICE COLOR CODE START*/
        #generic_price_table .generic_content {
            background-color: #fff;
        }

        #generic_price_table .generic_content .generic_head_price {
            background-color: #f6f6f6;
        }

        #generic_price_table .generic_content .generic_head_price .generic_head_content .head_bg {
            border-color: #e4e4e4 rgba(0, 0, 0, 0) rgba(0, 0, 0, 0) #e4e4e4;
        }

        #generic_price_table .generic_content .generic_head_price .generic_head_content .head span {
            color: #525252;
        }

        #generic_price_table .generic_content .generic_head_price .generic_price_tag .price .sign {
            color: #414141;
        }

        #generic_price_table .generic_content .generic_head_price .generic_price_tag .price .currency {
            color: #414141;
        }

        #generic_price_table .generic_content .generic_head_price .generic_price_tag .price .cent {
            color: #414141;
        }

        #generic_price_table .generic_content .generic_head_price .generic_price_tag .month {
            color: #414141;
        }

        #generic_price_table .generic_content .generic_feature_list ul li {
            color: #a7a7a7;
        }

        #generic_price_table .generic_content .generic_feature_list ul li span {
            color: #414141;
        }

        #generic_price_table .generic_content .generic_feature_list ul li:hover {
            background-color: #E4E4E4;
            border-left: 5px solid #2e39bf;
        }

        #generic_price_table .generic_content .generic_price_btn button {
            border: 1px solid #2e39bf;
            color: #2e39bf;
            background: #ffffff;

        }

        #generic_price_table .generic_content.active .generic_head_price .generic_head_content .head_bg,
        #generic_price_table .generic_content:hover .generic_head_price .generic_head_content .head_bg {
            border-color: #2e39bf rgba(0, 0, 0, 0) rgba(0, 0, 0, 0) #2e39bf;
            color: #fff;
        }

        #generic_price_table .generic_content:hover .generic_head_price .generic_head_content .head span,
        #generic_price_table .generic_content.active .generic_head_price .generic_head_content .head span {
            color: #fff;
        }

        #generic_price_table .generic_content:hover .generic_price_btn button,
        #generic_price_table .generic_content.active .generic_price_btn button {
            background-color: #2e39bf;
            color: #fff;
        }

        #generic_price_table {
            /*margin: 50px 0 0 0;*/
            font-family: 'Raleway', sans-serif;
        }

        .row .table {
            padding: 28px 0;
        }

        /*PRICE BODY CODE START*/

        #generic_price_table .generic_content {
            overflow: hidden;
            position: relative;
            text-align: center;
        }

        #generic_price_table .generic_content .generic_head_price {
            margin: 0 0 20px 0;
        }

        #generic_price_table .generic_content .generic_head_price .generic_head_content {
            margin: 0 0 50px 0;
        }

        #generic_price_table .generic_content .generic_head_price .generic_head_content .head_bg {
            border-style: solid;
            border-width: 90px 1411px 23px 399px;
            position: absolute;
        }

        #generic_price_table .generic_content .generic_head_price .generic_head_content .head {
            padding-top: 40px;
            position: relative;
            z-index: 1;
        }

        #generic_price_table .generic_content .generic_head_price .generic_head_content .head span {
            font-family: "Raleway", sans-serif;
            font-size: 28px;
            font-weight: 400;
            letter-spacing: 2px;
            margin: 0;
            padding: 0;
            text-transform: uppercase;
        }

        #generic_price_table .generic_content .generic_head_price .generic_price_tag {
            padding: 0 0 20px;
        }

        #generic_price_table .generic_content .generic_head_price .generic_price_tag .price {
            display: block;
        }

        #generic_price_table .generic_content .generic_head_price .generic_price_tag .price .sign {
            display: inline-block;
            font-family: "Lato", sans-serif;
            font-size: 28px;
            font-weight: 400;
            vertical-align: middle;
        }

        #generic_price_table .generic_content .generic_head_price .generic_price_tag .price .currency {
            font-family: "Lato", sans-serif;
            font-size: 60px;
            font-weight: 300;
            letter-spacing: -2px;
            line-height: 60px;
            padding: 0;
            vertical-align: middle;
        }

        #generic_price_table .generic_content .generic_head_price .generic_price_tag .price .cent {
            display: inline-block;
            font-family: "Lato", sans-serif;
            font-size: 24px;
            font-weight: 400;
            vertical-align: bottom;
        }

        #generic_price_table .generic_content .generic_head_price .generic_price_tag .month {
            font-family: "Lato", sans-serif;
            font-size: 18px;
            font-weight: 400;
            letter-spacing: 3px;
            vertical-align: bottom;
        }

        #generic_price_table .generic_content .generic_feature_list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #generic_price_table .generic_content .generic_feature_list ul li {
            font-family: "Lato", sans-serif;
            font-size: 18px;
            padding: 5px 0;
            transition: all 0.3s ease-in-out 0s;
            /*float: left;*/
        }

        #generic_price_table .generic_content .generic_feature_list ul li:hover {
            transition: all 0.3s ease-in-out 0s;
            -moz-transition: all 0.3s ease-in-out 0s;
            -ms-transition: all 0.3s ease-in-out 0s;
            -o-transition: all 0.3s ease-in-out 0s;
            -webkit-transition: all 0.3s ease-in-out 0s;

        }

        #generic_price_table .generic_content .generic_feature_list ul li .fa {
            padding: 0 10px;
        }

        #generic_price_table .generic_content .generic_price_btn {
            margin: 20px 0 32px;
        }

        #generic_price_table .generic_content .generic_price_btn button {
            border-radius: 50px;
            -moz-border-radius: 50px;
            -ms-border-radius: 50px;
            -o-border-radius: 50px;
            -webkit-border-radius: 50px;
            display: inline-block;
            font-family: "Lato", sans-serif;
            font-size: 18px;
            outline: medium none;
            padding: 12px 30px;
            text-decoration: none;
            text-transform: uppercase;
        }

        #generic_price_table .generic_content,
        #generic_price_table .generic_content:hover,
        #generic_price_table .generic_content .generic_head_price .generic_head_content .head_bg,
        #generic_price_table .generic_content:hover .generic_head_price .generic_head_content .head_bg,
        #generic_price_table .generic_content .generic_head_price .generic_head_content .head h2,
        #generic_price_table .generic_content:hover .generic_head_price .generic_head_content .head h2,
        #generic_price_table .generic_content .price,
        #generic_price_table .generic_content:hover .price,
        #generic_price_table .generic_content .generic_price_btn button,
        #generic_price_table .generic_content:hover .generic_price_btn button {
            transition: all 0.3s ease-in-out 0s;
            -moz-transition: all 0.3s ease-in-out 0s;
            -ms-transition: all 0.3s ease-in-out 0s;
            -o-transition: all 0.3s ease-in-out 0s;
            -webkit-transition: all 0.3s ease-in-out 0s;
        }

        @media (max-width: 320px) {}

        @media (max-width: 767px) {
            #generic_price_table .generic_content {
                margin-bottom: 75px;
            }
        }

        @media (min-width: 768px) and (max-width: 991px) {
            #generic_price_table .col-md-3 {
                float: left;
                width: 50%;
            }

            #generic_price_table .col-md-4 {
                float: left;
                width: 50%;
            }

            #generic_price_table .generic_content {
                margin-bottom: 75px;
            }
        }

        @media (min-width: 992px) and (max-width: 1199px) {}

        @media (min-width: 1200px) {}

        #generic_price_table_home {
            font-family: 'Raleway', sans-serif;
        }

        .text-center h1,
        .text-center h1 a {
            color: #7885CB;
            font-size: 30px;
            font-weight: 300;
            text-decoration: none;
        }

        .demo-pic {
            margin: 0 auto;
        }

        .demo-pic:hover {
            opacity: 0.7;
        }

        #generic_price_table_home ul {
            margin: 0 auto;
            padding: 0;
            list-style: none;
            display: table;
        }

        #generic_price_table_home li {
            float: left;
        }

        #generic_price_table_home li+li {
            margin-left: 10px;
            padding-bottom: 10px;
        }

        #generic_price_table_home li a {
            display: block;
            width: 50px;
            height: 50px;
            font-size: 0px;
        }

        #generic_price_table_home .blue {
            background: #3498DB;
            transition: all 0.3s ease-in-out 0s;
        }

        #generic_price_table_home .emerald {
            background: #2e39bf;
            transition: all 0.3s ease-in-out 0s;
        }

        #generic_price_table_home .grey {
            background: #7F8C8D;
            transition: all 0.3s ease-in-out 0s;
        }

        #generic_price_table_home .midnight {
            background: #34495E;
            transition: all 0.3s ease-in-out 0s;
        }

        #generic_price_table_home .orange {
            background: #E67E22;
            transition: all 0.3s ease-in-out 0s;
        }

        #generic_price_table_home .purple {
            background: #9B59B6;
            transition: all 0.3s ease-in-out 0s;
        }

        #generic_price_table_home .red {
            background: #E74C3C;
            transition: all 0.3s ease-in-out 0s;
        }

        #generic_price_table_home .turquoise {
            background: #1ABC9C;
            transition: all 0.3s ease-in-out 0s;
        }

        #generic_price_table_home .blue:hover,
        #generic_price_table_home .emerald:hover,
        #generic_price_table_home .grey:hover,
        #generic_price_table_home .midnight:hover,
        #generic_price_table_home .orange:hover,
        #generic_price_table_home .purple:hover,
        #generic_price_table_home .red:hover,
        #generic_price_table_home .turquoise:hover {
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
            border-top-left-radius: 50px;
            border-top-right-radius: 50px;
            transition: all 0.3s ease-in-out 0s;
        }

        #generic_price_table_home .divider {
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
            padding: 20px;
        }

        #generic_price_table_home .divider span {
            width: 100%;
            display: table;
            height: 2px;
            background: #ddd;
            margin: 50px auto;
            line-height: 2px;
        }

        #generic_price_table_home .itemname {
            text-align: center;
            font-size: 50px;
            padding: 50px 0 20px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 40px;
            text-decoration: none;
            font-weight: 300;
        }

        #generic_price_table_home .itemnametext {
            text-align: center;
            font-size: 20px;
            padding-top: 5px;
            text-transform: uppercase;
            display: inline-block;
        }

        #generic_price_table_home .footer {
            padding: 40px 0;
        }

        .price-heading {
            text-align: center;
        }

        .price-heading h1 {
            color: #666;
            margin: 0;
            padding: 0 0 50px 0;
        }

        .demo-button {
            background-color: #333333;
            color: #ffffff;
            display: table;
            font-size: 20px;
            margin-left: auto;
            margin-right: auto;
            margin-top: 20px;
            margin-bottom: 50px;
            outline-color: -moz-use-text-color;
            outline-style: none;
            outline-width: medium;
            padding: 10px;
            text-align: center;
            text-transform: uppercase;
        }

        .bottom_btn {
            background-color: #333333;
            color: #ffffff;
            display: table;
            font-size: 28px;
            margin: 60px auto 20px;
            padding: 10px 25px;
            text-align: center;
            text-transform: uppercase;
        }

        .demo-button:hover {
            background-color: #666;
            color: #FFF;
            text-decoration: none;

        }

        .bottom_btn:hover {
            background-color: #666;
            color: #FFF;
            text-decoration: none;
        }

        .pricing-feature {
            /*float: left;*/
        }

        li.pricing-feature {
            /*border-bottom: 1px dashed #564aa3;*/
            text-align: initial;
        }

        .pricing-feature i {
            font-size: 20px;
            float: left;
            margin: 0px 15px 0px 15px;
        }

        .btn-primary {
            border-color: #3378ff !important;
            background: #fff !important;
            border-radius: 0 !important;
            color: initial !important;
        }

        .ribbon-wrapper-red {
            width: 85px;
            height: 88px;
            overflow: hidden;
            position: absolute;
            top: -3px;
            right: -3px;
            z-index: 1;
        }

        .ribbon-red {
            font: 700 15px Sans-Serif;
            text-align: center;
            text-shadow: hsla(0, 0%, 100%, .5) 0 1px 0;
            -webkit-transform: rotate(45deg);
            -moz-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            -o-transform: rotate(45deg);
            position: relative;
            padding: 7px 0;
            left: -5px;
            top: 15px;
            width: 120px;
            background-color: #ea181e;
            background-image: -o-linear-gradient(top, #bfdc7a, #8ebf45);
            color: #fff;
            -webkit-box-shadow: 0 0 3px rgba(0, 0, 0, .3);
            box-shadow: 0 0 3px rgba(0, 0, 0, .3);
        }

        .custom-bg {
            background: #3378ff;
            color: #fff;
        }

        .pricing-item {
            /*width: 33%;*/
            margin-top: 25px;
            margin-bottom: 25px;
            /*margin-left: 15px;*/

        }

        .type {
            display: block;
            overflow: hidden;
        }
    </style>
    <?= form_open(base_url('sign-up'), array('class' => 'form-horizontal', 'id' => 'contact-form', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>

    <input id="new_interval_type" name="interval_type" value="monthly" type="hidden">
    <input id="new_currency_type" name="currency_type" value="<?= config_item('default_currency') ?>" type="hidden">
    <div class="type">
        <div class="pull-left interval_type">
            <span class="set_price btn btn-xs custom-bg active by_type" result="by_type" type="monthly"><?= lang('monthly') ?></span>
            <span class="set_price btn btn-xs btn-primary by_type" type="annually" result="by_type"><?= lang('annually') ?></span>
        </div>

        <div class="pull-right currency_type">
            <?php
            $all_currency = $this->db->select('currency')->distinct()->get('tbl_currencywise_price')->result();
            if (!empty($all_currency)) {
                foreach ($all_currency as $v_currency) {
                    $c_name = get_row('tbl_currencies', array('code' => $v_currency->currency));
            ?>
                    <span currency="<?= $v_currency->currency ?>" result="by_currency" class="set_price btn btn-xs currencywise_price <?= (config_item('default_currency') == $v_currency->currency ? 'custom-bg active' : 'btn-primary') ?>"><?= $c_name->name . '(' . $c_name->symbol . ')' ?></span>
            <?php }
            }
            ?>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {

            //$('.pricing-item').addClass('hidden');
            var interval_type = $('.interval_type >  .active').attr('type');

            var new_result = [];
            <?php if (!empty($currency_wise_price)) {
                foreach ($currency_wise_price as $v_c_price) { ?>
                    var result = {
                        frontend_pricing_id: "<?= $v_c_price->frontend_pricing_id ?>",
                        currency: "<?= $v_c_price->currency ?>",
                        yearly: "<?= $v_c_price->yearly ?>",
                        monthly: "<?= $v_c_price->monthly ?>"
                    };
                    new_result.push(result)
            <?php }
            } ?>

            $.each(new_result, function(index, item) {
                $('.pricing-currency').html(item.currency);
                if (interval_type == 'annually') {
                    $('.table_list_' + item.frontend_pricing_id).removeClass('hidden');
                    $('.type_amount_' + item.frontend_pricing_id).html(item.yearly);
                    $('.pricing-period').html('/<?= lang('yr') ?>');
                } else {
                    $('.table_list_' + item.frontend_pricing_id).removeClass('hidden');
                    $('.type_amount_' + item.frontend_pricing_id).html(item.monthly);
                    $('.pricing-period').html('/<?= lang('mo') ?>');
                }

            });

            $(".set_price").click(function() {


                var result = $(this).attr('result');

                if (result == 'by_currency') {
                    $('.currencywise_price').removeClass('custom-bg active');
                    $('.currencywise_price').addClass('btn-primary');
                }
                if (result == 'by_type') {
                    $('.by_type').removeClass('custom-bg active');
                    $('.by_type').addClass('btn-primary');
                }
                $(this).removeClass('btn-primary');
                $(this).addClass('custom-bg active');

                var interval_type = $('.interval_type >  .active').attr('type');
                var currencywise_price = $('.currency_type >  .active').attr('currency');

                $('#new_interval_type').val(interval_type);
                $('#new_currency_type').val(currencywise_price);

                var formData = {
                    'interval_type': interval_type,
                    'currencywise_price': currencywise_price,
                };
                $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: '<?= base_url() ?>frontend/get_currencywise_price/', // the url where we want to POST
                    data: formData, // our data object
                    dataType: 'json', // what type of data do we expect back from the server
                    encode: true,
                    success: function(res) {
                        if (res) {
                            $('.pricing-item').addClass('hidden');
                            $.each(res, function(index, item) {
                                $('.pricing-currency').html(item.currency);
                                if (interval_type == 'annually') {
                                    $('.table_list_' + item.frontend_pricing_id).removeClass('hidden');
                                    $('.type_amount_' + item.frontend_pricing_id).html(item.yearly);
                                    $('.pricing-period').html('/<?= lang('yr') ?>');
                                } else {
                                    $('.table_list_' + item.frontend_pricing_id).removeClass('hidden');
                                    $('.type_amount_' + item.frontend_pricing_id).html(item.monthly);
                                    $('.pricing-period').html('/<?= lang('mo') ?>');
                                }

                            });
                        } else {
                            alert('There was a problem with AJAX');
                        }
                    }
                })

            });

        });


        function choosePlan(p_id) {
            formData = {
                'pricing_id': p_id,
                'interval_type': $('#new_interval_type').val(),
                'currency_type': $('#new_currency_type').val()
            };

            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: '<?= base_url() ?>frontend/choosePlan_/', // the url where we want to POST
                data: formData, // our data object
                success: function(res) {
                    $('#modal-xl-con').html(res);
                    $('#modal-xl').modal();

                }
            })
        }
    </script>

<!-- class="owl-carousel testimonial-carousel" -->
    <div id="generic_price_table" class="row">
        <?php
        $all_pricing = get_order_by('tbl_frontend_pricing', array('status' => 1), 'sort', true);
        $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
        if (!empty($all_pricing)) {
            foreach ($all_pricing as $key => $pricing) {
                $coupon_info = get_row('tbl_frontend_coupon', array('pricing_id' => $pricing->id, 'show_on_pricing' => 'Yes'));
        ?>
                <div class=" col-lg-4 cps-testimonial-item pricing-item  table_list_<?= $pricing->id ?>" >
                    <!--PRICE CONTENT START-->
                    <div class="generic_content clearfix <?= ($pricing->recommended == 'Yes' ? 'active' : '') ?>">
                        <!--HEAD PRICE DETAIL START-->
                        <div class="generic_head_price clearfix">
                            <!--HEAD CONTENT START-->
                            <div class="generic_head_content clearfix">
                                <!--HEAD START-->
                                <?php if ($pricing->recommended == 'Yes') { ?>
                                    <div class="ribbon-wrapper-red">
                                        <div class="ribbon-red"><?= lang('featured') ?></div>
                                    </div>
                                <?php } ?>
                                <div class="head_bg"></div>
                                <div class="head">
                                    <span><?= $pricing->name ?></span>
                                </div>
                                <!--//HEAD END-->

                            </div>
                            <!--//HEAD CONTENT END-->

                            <!--PRICE START-->
                            <div class="generic_price_tag clearfix">
                                <span class="price">
                                    <span class="pricing-currency">$</span>
                                    <span class="currency type_amount_<?= $pricing->id ?>"><?= $pricing->amount ?></span>
                                    <span class="pricing-period month">/ <?= lang('mo') ?></span>
                                </span>
                            </div>
                            <!--//PRICE END-->

                        </div>
                        <!--//HEAD PRICE DETAIL END-->

                        <!--FEATURE LIST START-->
                        <div class="generic_feature_list">
                            <ul>
                                <li class='pricing-feature'><?= pricing_format_YN($pricing->multi_branch, lang('multi_branch')) ?></li>
                                <li class='pricing-feature'><?= pricing_format($pricing->employee_no, lang('Users')) ?></li>
                                <li class='pricing-feature'><?= pricing_format($pricing->disk_space, lang('disk_space')) ?></li>
                                <li class='pricing-feature'><?= pricing_format($pricing->trial_period, lang('days') . ' ' . lang('trail_period')) ?></li>
                                <li class='pricing-feature'><?= pricing_format($pricing->client_no, lang('Contacts')) ?></li>
                                <li class='pricing-feature'><?= pricing_format($pricing->project_no, lang('Projects')) ?></li>
                                <li class='pricing-feature'><?= pricing_format($pricing->invoice_no, lang('Invoices')) ?></li>
                                <li class='pricing-feature'><?= pricing_format($pricing->leads, lang('leads')) ?></li>
                                <li class='pricing-feature'><?= pricing_format($pricing->accounting, lang('accounting')) ?></li>
                                <li class='pricing-feature'><?= pricing_format($pricing->bank_account, lang('bank') . ' ' . lang('account')) ?></li>
                                <li class='pricing-feature'><?= pricing_format($pricing->tasks, lang('tasks')) ?></li>
                                <li class='pricing-feature'><?= pricing_format_YN($pricing->online_payment, lang('Online payments')) ?></li>
                                <li class='pricing-feature'><?= pricing_format_YN($pricing->mailbox, lang('mailbox')) ?></li>
                                <li class='pricing-feature'><?= pricing_format_YN($pricing->live_chat, lang('Team Chat')) ?></li>
                                <li class='pricing-feature'><?= pricing_format_YN($pricing->tickets, lang('tickets')) ?></li>
                                <li class='pricing-feature'><?= pricing_format_YN($pricing->recruitment, lang('job_circular')) ?></li>
                                <li class='pricing-feature'><?= pricing_format_YN($pricing->attendance, lang('attendance')) ?></li>
                                <li class='pricing-feature'><?= pricing_format_YN($pricing->payroll, lang('payroll')) ?></li>
                                <li class='pricing-feature'><?= pricing_format_YN($pricing->leave_management, lang('leave_management')) ?></li>
                                <li class='pricing-feature'><?= pricing_format_YN($pricing->performance, lang('Performance Tracking')) ?></li>
                                <li class='pricing-feature'><?= pricing_format_YN($pricing->training, lang('training')) ?></li>
                                <li class='pricing-feature'><?= pricing_format_YN($pricing->reports, lang('report')) ?></li>

                            </ul>
                        </div>
                        <!--//FEATURE LIST END-->

                        <!--BUTTON START-->
                        <div class="generic_price_btn clearfix">
                            <button type="button" value="<?= $pricing->id ?>" name="pricing_id" class="set_link" onclick="choosePlan(<?= $pricing->id ?>)">
                                <?= lang('star_trial_days', $pricing->trial_period) ?>
                            </button>
                        </div>
                        <!--//BUTTON END-->
                    </div>
                    <!--//PRICE CONTENT END-->
                </div>
        <?php }
        } ?>

    </div>
    <?php echo form_close(); ?>
</div>