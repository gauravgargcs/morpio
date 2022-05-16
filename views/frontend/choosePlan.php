<!-- contect-page-section -->
<style type="text/css">
   .border-0{
    border: 0;
   }

    .contact-info ul {
        list-style: none;
        padding-left: 0px;
    }

    .contact-section .contact-info li {
        padding-left: 0px;
        line-height: inherit;
        padding-top: 0px;
    }

    .contact-section .column .inner-box .text {
        margin-bottom: 10px;
    }

    .contact-section h2 {
        margin-bottom: 0;
    }

    .form-group {
        margin-right: 0 !important;
        margin-bottom: 15px !important;;
    }

    .new_error {
        display: block;
        line-height: 24px;
        padding-top: 5px;
        font-size: 13px;
        /*text-transform: capitalize;*/
        font-weight: 500;
        color: #ff0000;
    }

    

    .rate {
        margin-top: 15px;
        margin-bottom: 15px;
        overflow: hidden;
    }

    .contact-info li:last-child {
        border: 0px !important;
    }
   .btn.custom-bg:hover{
     background: #6539f3 !important; 
   }
    .btn-primary{
            background-color: #FDB739 !important;
    }
     .btn.btn-primary:hover {
            background-color: #bb842e  !important;
    }
    .help_btn,.help_btn:hover,.help_btn:focus, .help_btn:visited, .help_btn:active, .help_btn:active:focus {
        background: #76bb40 !important;
    }
    .monthly-btn {
        margin-left: 17px;
    }
     .currencywise_price {
        margin-right: 17px;
    }

</style>
<script>
    $('html,body').animate({
//            scrollTop: 750
        },
        'slow');
</script>
<?php $default_url = preg_replace('#^https?://#', '', rtrim(config_item('default_url'), '/')); ?>
<?php echo form_open(base_url('signed_up'), array('class' => 'form-horizontal', 'id' => 'contact-form', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
<div class="auto-container">
    <div class="rate row">
        <div class="interval_type col-md-4">
    <span
            class="monthly-btn set_price btn  btn-main btn-xs u-radius-10 <?= (!empty($interval_type) && $interval_type == 'monthly' || empty($interval_type) ? 'custom-bg active' : 'btn-main') ?> by_type"
            result="by_type"
            type="monthly"><?= lang('monthly') ?></span>
            <span
                    class="set_price btn btn-main btn-xs u-radius-10 <?= (!empty($interval_type) && $interval_type == 'annually' ? 'custom-bg active' : 'btn-main') ?> by_type"
                    type="annually"
                    result="by_type"><?= lang('annually') ?></span>
                   
        </div>
        <div class=" text-center currency_type col-md-4">
              <a href="javascript://"
                    class="help_btn btn btn-main btn-xs u-radius-10 " onclick="window.open('<?=site_url('frontend/help');?>', 
                         'newwindow', 
                         'width=1200,height=600')" ><?= lang('NEED HELP? FREE SETUP') ?></a>
        </div>

        <div class=" text-right currency_type col-md-4">
            <?php
            $all_currency = $this->db->select('currency')->distinct()->get('tbl_currencywise_price')->result();
            if (!empty($all_currency)) {
                foreach ($all_currency as $v_currency) {
                    $c_name = get_row('tbl_currencies', array('code' => $v_currency->currency));
                    ?>
                    <span currency="<?= $v_currency->currency ?>" result="by_currency"
                          class="set_price btn btn-xs currencywise_price u-radius-10 <?= (!empty($currency_type) && $currency_type == $v_currency->currency || (empty($currency_type) && $v_currency->currency == config_item('default_currency')) ? 'btn-main active' : 'btn-primary') ?>"><?= $c_name->name . '(' . $c_name->symbol . ')' ?></span>
                <?php }
            }
            ?>
        </div>
    </div>

    <div class=" clearfix">
        <div class="column col-md-7 col-sm-6 col-xs-12">
            <div class="panel panel-info border-0">
                <div class="panel-heading u-radius-10 custom-bg">
                    <?= lang('sign_up') . ' ' . lang('for') . ' <span id="package_name">' . $plan_info->name ?></span></strong>
                </div>
                <div class="panel-body">
                    <div class="contact-form default-form">

                        <div class="">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input name="domain" required class="main_domain form-control" min="3" id="domain" value=""
                                           placeholder="<?= lang('choose_a_domain') ?> *" type="text">
                                   
                                    <span class="help-block domain_showed"
                                          style=""><?= lang('example') ?>: <strong
                                                id="sub_domain" class="">https://yourbusinessdomain.<?=$default_url;?></strong></span>
                                                 <small class="new_error" id="new_error"></small>
                                </div>
                            </div>

                            <input id="new_interval_type" name="interval_type"
                                   value="<?= (!empty($interval_type) ? $interval_type : 'monthly') ?>" type="hidden">
                            <input id="new_currency_type" name="currency_type"
                                   value="<?= (!empty($currency_type) ? $currency_type : config_item('default_currency')) ?>"
                                   type="hidden">

                            <?php 
                            // var_dump( $c_pricing_info);
                            $this->load->view('frontend/pricing_dropdown', array('c_pricing_info' => $c_pricing_info)) ?>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input name="name" required value="" placeholder="<?= lang('name') ?> *" class="form-control"
                                           type="text">
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input name="email" id="check_email" required placeholder="<?= lang('email') ?> *"
                                           type="email" class="form-control">
                                    <small class="new_error" id="email_error"></small>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <input name="mobile" class="form-control" value="" required placeholder="<?= lang('mobile') ?> *"
                                           type="text">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <select name="country" class="form-control select_box"
                                            style="width: 100%">
                                        <optgroup label="Default Country">
                                            <option
                                                    value="<?= $this->config->item('company_country') ?>"><?= $this->config->item('company_country') ?></option>
                                        </optgroup>
                                        <optgroup label="<?= lang('other_countries') ?>">
                                            <?php if (!empty($countries)): foreach ($countries as $country): ?>
                                                <option
                                                        value="<?= $country->value ?>" <?= (!empty($company_info->country) && $company_info->country == $country->value ? 'selected' : NULL) ?>><?= $country->value ?>
                                                </option>
                                            <?php
                                            endforeach;
                                            endif;
                                            ?>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <div class="pull-left">
                                       <?= lang('already_have_an_account') ?>
                                      
                                            <a href="<?= base_url() ?>login"
                                               class="text-warning"><?= lang('sign_in') ?></a>
                                        
                                    </div>
                                    <div class="pull-right">
                                        <button type="submit" id="new_company"
                                                class="btn btn-main"><?= lang('sign_up') ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
        <div id="package_details">
            <?php $this->load->view('frontend/package_details', array('plan_info' => $plan_info)) ?>
        </div>
    </div>
</div>


<!-- contect-page-section end -->
<script type="text/javascript">
    $(document).ready(function () {

        $(".set_price").click(function () {
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
                url: '<?= base_url()?>frontend/get_currencywise_price/', // the url where we want to POST
                data: formData, // our data object
                dataType: 'json', // what type of data do we expect back from the server
                encode: true,
                success: function (res) {
                    if (res) {
                        $('select[name="pricing_id"]').find('option').remove();
                        $.ajax({
                            url: "<?= base_url()?>admin/global_controller/get_package_details/" + res[0].frontend_pricing_id,
                            type: "GET",
                            dataType: 'json',
                            success: function (result) {
                                document.getElementById('package_name').innerHTML = result.package_name;
                                document.getElementById('package_details').innerHTML = result.package_details;
                            }
                        });
                        $.each(res, function (index, item) {
                            if (interval_type == 'annually') {
                                var amount = item.yearly + ' /' + '<?= lang('yr')?>';
                            } else {
                                var amount = item.monthly + ' /' + '<?= lang('mo')?>';
                            }
                            $('select[name="pricing_id"]').append('<option value="' + item.frontend_pricing_id + '">' + item.name + ' ' + item.currency + amount + '</option>');
                        });
                    } else {
                        alert('There was a problem with AJAX');
                    }
                }
            })

        });
    })
   
    $(".main_domain").keyup(function () {
        var sub_domain = $(this).val();
        var main_domain = "<?= $default_url?>";

        sub_domain = sub_domain.replace('.'+main_domain, "");
        sub_domain = sub_domain.replace('https://', "");
        sub_domain = sub_domain.replace('http://', "");
        sub_domain = sub_domain.replace('.', "");
        sub_domain = sub_domain.replace('/', "");
        $(this).val(sub_domain);
        var http = "<?= (isset($_SERVER['HTTPS']) ? "https://" : "http://")?>";
        $('#sub_domain').html(http + sub_domain + '.' + main_domain);
        var domainDiv = $('.domain_showed');
        if ($(this).val() == "") {
            sub_domain = 'yourbusinessdomain';
             $('#sub_domain').html(http + sub_domain + '.' + main_domain);
            // domainDiv.css("display", "none");
        } else {
            domainDiv.css("display", "block");
        }
    });

    $(document).on("change", function () {
//        alert('done');
        var check_email = $('#check_email').val();
        var check_domain = $('#domain').val();
        var base_url = "<?= base_url()?>";
        var url = null;

        if (check_domain) {
            id = 'new_error';
            btn = 'new_company';
            url = 'check_existing_domain';
            value = check_domain;
        }
        if (check_email) {
            id = 'email_error';
            btn = 'new_company';
            url = 'check_existing_subscription_email';
            value = check_email;
        }
        if (url) {
//            alert(url);
            $.ajax({
                url: base_url + "admin/global_controller/" + url,
                type: "POST",
                data: {
                    name: value,
                },
                dataType: 'json',
                success: function (res) {
                    console.log(id);
                    if (res.error) {
                        $("#" + id).html(res.error);
                        $("#" + btn).attr("disabled", "disabled");
                        return;
                    } else {
                        $("#" + id).empty();
                        $("#" + btn).removeAttr("disabled");
                        return;
                    }
                }
            });
        }
    });
</script>