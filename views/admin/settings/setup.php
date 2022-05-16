<!DOCTYPE html>

<html lang="en" class="bg-dark">

<head>

    <?php

    $base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';

    $base_url .= '://' . $_SERVER['HTTP_HOST'];

    $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

    $base_url = preg_replace('/install.*/', '', $base_url);

    ?>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title><?= config_item('company_name') ?></title>

    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/install/app.css" type="text/css"/>

    <!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">-->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">

    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/install/fuelux/fuelux.css" type="text/css"/>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->

    <!-- danger: Respond.js doesn't work if you view the page via file:// -->

    <!--[if lt IE 9]>

    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>

    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>



    <![endif]-->

</head>

<body>

<section id="content" class="m-t-lg wrapper-md animated fadeInUp">

    <div class="container" style="width:80%">

        <section class="panel panel-default bg-white m-t-lg">

            <header class="panel-heading text-center">

                <strong><?= lang('thank_you_for_interesting')  ?></strong>

            </header>

            <div class="panel-body wrapper-lg">

                <?php

                $step1 = $step2 = $step3 = $step4 = '';

                $badge1 = $badge2 = $badge3 = $badge4 = 'badge';

                if (isset($step)) {

                    switch ($step) {

                        case '2':

                            $step2 = 'active';

                            $badge2 = 'badge badge-success';

                            break;

                        case '3':

                            $step3 = 'active';

                            $badge3 = 'badge badge-success';

                            break;

                        case '4':

                            $step4 = 'active';

                            $badge4 = 'badge badge-success';

                            break;



                        default:

                            $step1 = 'active';

                            $badge1 = 'badge badge-success';

                            break;

                    }

                } else {

                    $step1 = 'active';

                    $badge1 = 'badge badge-success';

                }



                ?>

                <div class="panel panel-default wizard">

                    <div class="wizard-steps clearfix" id="form-wizard">

                        <ul class="steps">

                            <li class="<?= $step1 ?>"><span class="<?= $badge1 ?>">1</span> <?= lang('account') ?></li>

                            <li class="<?= $step2 ?>"><span

                                        class="<?= $badge2 ?>">2</span> <?= lang('company_settings') ?></li>

                            <li class="<?= $step3 ?>"><span class="<?= $badge3 ?>">3</span> <?= lang('mail_settings') ?></li>

                            <li class="<?= $step4 ?>"><span class="<?= $badge4 ?>">4</span> <?= lang('ready_to_go') ?></li>

                        </ul>

                    </div>

                    <div class="step-content clearfix">

                        <?php

                        if ($step == 1) { ?>

                            <div class="step-pane <?= $step1 ?>" id="step1">

                                <?php echo '<form action="" id="account_info" novalidate="novalidate" class="form-horizontal" method="post" accept-charset="utf-8">'; ?>

                                <?php echo '<input type="hidden" name="step" value="' . $step . '">'; ?>



                                <div class="form-group">

                                    <!-- <label class="col-lg-3 control-label"><?= lang('email') ?></label> -->

                                    <div class="col-lg-7">
                                        <input type="hidden" required  class="form-control" id="check_email"
                                               placeholder="enter your email address here"
                                               name="email">

                                        <small class="text-danger"

                                               id="email_error"><?= (!empty($email_error) ? $email_error : '') ?></small>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <label class="col-lg-3 control-label"><?= lang('activation_token') ?></label>

                                    <div class="col-lg-7">

                                        <input type="text" class="form-control" id="activation_token"

                                               placeholder="<?= lang('enter_placeholder', lang('activation_token')) ?>"

                                               name="activation_token">

                                        <small class="text-danger"

                                               id="activation_token_error"><?= (!empty($activation_token_error) ? $activation_token_error : '') ?></small>

                                    </div>

                                </div>



                                <div class="form-group">

                                    <label class="col-lg-3 control-label"><?= lang('username') ?></label>

                                    <div class="col-lg-7">

                                        <input type="text" class="form-control" id="user_name"

                                               placeholder="<?= lang('enter_placeholder', lang('username')) ?>"

                                               name="username">

                                        <small class="text-danger"

                                               id="username_error"><?= (!empty($username_error) ? $username_error : '') ?></small>

                                    </div>

                                </div>



                                <div class="form-group">

                                    <label class="col-lg-3 control-label"><?= lang('password') ?></label>

                                    <div class="col-lg-7">

                                        <input type="password" class="form-control" id="password"

                                               placeholder="<?= lang('enter_placeholder', lang('password')) ?>"

                                               name="password">

                                    </div>

                                </div>

                                <div class="form-group">

                                    <label class="col-lg-3 control-label"><?= lang('confirm_password') ?></label>

                                    <div class="col-lg-7">

                                        <input type="password" class="form-control"

                                               placeholder="<?= lang('enter_placeholder', lang('confirm_password')) ?>"

                                               name="confirm_password">

                                    </div>

                                </div>

                                <input type="hidden" class="form-control" id="full_name" placeholder="<?= lang('enter_placeholder', lang('full_name')) ?>" name="fullname">


                                <div class="form-group">

                                    <label class="col-lg-3 control-label"></label>

                                    <div class="col-lg-7">

                                        <button type="submit" id="next"

                                                class="btn btn-primary"><?= lang('next') ?></button>

                                    </div>

                                </div>

                                </form>

                                <script src="<?= base_url() ?>assets/plugins/install/jquery-2.2.4.min.js"></script>



                                <script type="text/javascript">

                                    $(document).on("change", function () {

//        alert('done');

                                        // var check_email = $('#check_email').val();

                                        var activation_token = $('#activation_token').val();

                                        var base_url = "<?= base_url()?>";
                                        var url = null;
                                        var value = null;
                                        // var value_2 = null;

                                        // if (check_email) {
                                        //     id = 'email_error';
                                        //     btn = 'next';
                                        //     url = 'check_subscription_email';
                                        //     value = check_email;
                                        // }

                                        if (activation_token) {
                                            id = 'activation_token_error';
                                            btn = 'next';
                                            url = 'check_existing_activation_token_new';
                                            value = activation_token;
                                            // value_2 = check_email;
                                        }

                                        if (url) {
                                            $.ajax({
                                                url: base_url + "setup/" + url,
                                                type: "POST",
                                                data: {
                                                    name: value,
                                                    // name_2: value_2,
                                                },

                                                dataType: 'json',
                                                success: function (res) {
                                                    console.log(res);
                                                    if (res.error) {
                                                        $("#" + id).html(res.error);
                                                        $("#" + btn).attr("disabled", "disabled");
                                                        return;
                                                    } else {
                                                        $("#full_name").val(res.name);
                                                        $("#check_email").val(res.email);
                                                        $("#" + id).empty();
                                                        $("#" + btn).removeAttr("disabled");
                                                        return;
                                                    }
                                                }
                                            });
                                        }
                                    });

                                </script>

                            </div>

                        <?php } ?>

                        <div class="clearfix"></div>



                        <?php if ($step == 2) { 

                            
                            ?>

                            <div class="step-pane <?= $step2 ?>" id="step2">

                                <?php echo '<form action="" id="company_info" novalidate="novalidate" class="form-horizontal" method="post" accept-charset="utf-8">'; ?>

                                <?php echo '<input type="hidden" name="subscriptions_id" value="' . (!empty($subscription_info) ? $subscription_info->subscriptions_id : '') . '">'; ?>

                                <?php echo '<input type="hidden" name="step" value="' . $step . '">'; ?>

                                <?php echo '<input type="hidden" name="email" value="' . $_POST['email'] . '">'; ?>
                                <?php echo '<input type="hidden" name="fullname" value="' . $_POST['fullname'] . '">'; ?>

                                <?php echo '<input type="hidden" name="activation_token" value="' . $_POST['activation_token'] . '">'; ?>

                                <?php echo '<input type="hidden" name="username" value="' . $_POST['username'] . '">'; ?>

                                <?php echo '<input type="hidden" name="password" value="' . $_POST['password'] . '">'; ?>



                                <div class="form-group col-md-6 col-sm-12">

                                    <label class="col-lg-4 control-label"><?= lang('company_name') ?></label>

                                    <div class="col-lg-8">

                                        <input type="text" class="form-control" required

                                               placeholder="<?= lang('enter_placeholder', lang('company_name')) ?>"

                                               name="company_name">

                                    </div>

                                </div>

                                <div class="form-group col-md-6 col-sm-12">

                                    <label class="col-lg-4 control-label"><?= lang('contact_person') ?></label>

                                    <div class="col-lg-8">

                                        <input type="text" class="form-control" required

                                               placeholder="<?= lang('enter_placeholder', lang('contact_person')) ?>"

                                               name="contact_person">

                                    </div>

                                </div>



                                <div class="form-group col-md-6 col-sm-12">

                                    <label class="col-lg-4 control-label"><?= lang('address') ?></label>

                                    <div class="col-lg-8">

                                        <textarea class="form-control" name="company_address"></textarea>

                                    </div>

                                </div>

                                <div class="form-group col-md-6 col-sm-12">

                                    <label class="col-lg-4 control-label"><?= lang('country') ?></label>

                                    <div class="col-lg-8">

                                        <select class="form-control select_box" style="width:100%"

                                                name="company_country">

                                            <optgroup label="<?= lang('selected_country') ?>">

                                                <option

                                                        value="<?= $this->config->item('company_country') ?>"><?= $this->config->item('company_country') ?></option>

                                            </optgroup>

                                            <optgroup label="<?= lang('other_countries') ?>">

                                                <?php

                                                $countries = get_result('tbl_countries');

                                                foreach ($countries as $country): ?>

                                                    <option

                                                            value="<?= $country->value ?>"><?= $country->value ?></option>

                                                <?php endforeach; ?>

                                            </optgroup>

                                        </select>

                                    </div>

                                </div>

                                <div class="form-group col-md-6 col-sm-12">

                                    <label class="col-lg-4 control-label"><?= lang('city') ?></label>

                                    <div class="col-lg-8">

                                        <input type="text" class="form-control" value="" name="company_city">

                                    </div>

                                </div>

                                <div class="form-group col-md-6 col-sm-12">

                                    <label class="col-lg-4 control-label"><?= lang('zip_code') ?> </label>

                                    <div class="col-lg-8">

                                        <input type="text" class="form-control" value="" name="company_zip_code">

                                    </div>

                                </div>

                                <div class="form-group col-md-6 col-sm-12">

                                    <label class="col-lg-4 control-label"><?= lang('phone') ?></label>

                                    <div class="col-lg-8">

                                        <input type="text" class="form-control" value="" name="company_phone">

                                    </div>

                                </div>

                                <div class="form-group col-md-6 col-sm-12">

                                    <label class="col-lg-4 control-label"><?= lang('timezone') ?> <span

                                                class="text-danger">*</span></label>

                                    <div class="col-lg-8">

                                        <select name="timezone" class="form-control select_box" required>

                                            <?php foreach ($timezones as $timezone => $description) : ?>

                                                <option

                                                        value="<?= $timezone ?>"<?= (config_item('timezone') == $timezone ? ' selected="selected"' : '') ?>><?= $description ?></option>

                                            <?php endforeach; ?>

                                        </select>

                                    </div>

                                </div>

                                <div class="col-lg-12 ">

                                    <button type="submit" id="next"

                                            class="pull-right col-sm-3 btn btn-primary"><?= lang('next') ?></button>

                                </div>

                                </form>

                            </div>

                        <?php } ?>

                        <?php if ($step == 3) { ?>



                            <div class="step-pane <?= $step3 ?>" id="step3">

                                <?php echo '<form action="" id="verify" novalidate="novalidate" class="form-horizontal" method="post" accept-charset="utf-8">'; ?>

                                <!-- Start step 1-->

                                <?php echo '<input type="hidden" name="step" value="' . $step . '">'; ?>

                                <?php echo '<input type="hidden" name="subscriptions_id" value="' . $_POST['subscriptions_id'] . '">'; ?>

                                <?php echo '<input type="hidden" name="email" value="' . $_POST['email'] . '">'; ?>
                                <?php echo '<input type="hidden" name="fullname" value="' . $_POST['fullname'] . '">'; ?>

                                <?php echo '<input type="hidden" name="activation_token" value="' . $_POST['activation_token'] . '">'; ?>

                                <?php echo '<input type="hidden" name="username" value="' . $_POST['username'] . '">'; ?>

                                <?php echo '<input type="hidden" name="password" value="' . $_POST['password'] . '">'; ?>

                                <!-- End step 1-->



                                <!-- Start step 2-->

                                <?php echo '<input type="hidden" name="company_name" value="' . $_POST['company_name'] . '">'; ?>

                                <?php echo '<input type="hidden" name="contact_person" value="' . $_POST['contact_person'] . '">'; ?>

                                <?php echo '<input type="hidden" name="company_address" value="' . $_POST['company_address'] . '">'; ?>

                                <?php echo '<input type="hidden" name="company_country" value="' . $_POST['company_country'] . '">'; ?>

                                <?php echo '<input type="hidden" name="company_city" value="' . $_POST['company_city'] . '">'; ?>

                                <?php echo '<input type="hidden" name="company_zip_code" value="' . $_POST['company_zip_code'] . '">'; ?>

                                <?php echo '<input type="hidden" name="company_phone" value="' . $_POST['company_phone'] . '">'; ?>

                                <?php echo '<input type="hidden" name="timezone" value="' . $_POST['timezone'] . '">'; ?>

                                <!-- End step 2-->
                                <div class="alert alert-info text-center">Not sure? No problem, skip and do this later. </div>
                                <div class="form-group">

                                    <label class="col-lg-3 control-label"><?= lang('use_postmark') ?></label>

                                    <div class="col-lg-6">

                                        <div class="checkbox c-checkbox">

                                            <label class="needsclick">

                                                <input type="hidden" value="off" name="use_postmark"/>

                                                <input type="checkbox" <?php

                                                if (!empty($_POST['use_postmark']) && $_POST['use_postmark'] == 'TRUE') {

                                                    echo "checked=\"checked\"";

                                                }

                                                ?> name="use_postmark" id="use_postmark">

                                            </label>

                                        </div>

                                    </div>

                                </div>



                                <div

                                        id="postmark_config" <?php echo (!empty($_POST['use_postmark']) && $_POST['use_postmark'] != 'TRUE') ? 'style="display:block"' : 'style="display:none"' ?>>

                                    <div class="form-group">

                                        <label class="col-lg-3 control-label"><?= lang('postmark_api_key') ?></label>

                                        <div class="col-lg-6">

                                            <input type="text" class="form-control" placeholder="xxxxx"

                                                   name="postmark_api_key"

                                                   value="<?= (!empty($_POST['postmark_api_key']) ? $_POST['postmark_api_key'] : '') ?>">

                                        </div>

                                    </div>

                                    <div class="form-group">

                                        <label

                                                class="col-lg-3 control-label"><?= lang('postmark_from_address') ?></label>

                                        <div class="col-lg-6">

                                            <input type="email" class="form-control" placeholder="xxxxx"

                                                   name="postmark_from_address"

                                                   value="<?= (!empty($_POST['postmark_from_address']) ? $_POST['postmark_from_address'] : '') ?>">

                                        </div>

                                    </div>

                                </div>





                                <div class="form-group">

                                    <label class="col-lg-3 control-label"><?= lang('email_protocol') ?> <span

                                                class="text-danger">*</span></label>

                                    <div class="col-lg-6">

                                        <select name="protocol" required="" class="form-control">

                                            <option

                                                    value="mail" <?= (!empty($_POST['protocol']) && $_POST['protocol'] == 'mail' ? 'selected' : '') ?>><?= lang('php_mail') ?></option>

                                            <option

                                                    value="smtp" <?= (!empty($_POST['protocol']) && $_POST['protocol'] == 'smtp' ? 'selected' : '') ?>><?= lang('smtp') ?></option>

                                            <option

                                                    value="sendmail" <?= (!empty($_POST['protocol']) && $_POST['protocol'] == 'sendmail' ? 'selected' : '') ?>><?= lang('sendmail') ?></option>

                                        </select>

                                    </div>

                                </div>

                                <div id="smtp_config">

                                    <div class="form-group">

                                        <label class="col-lg-3 control-label"><?= lang('smtp_host') ?> </label>

                                        <div class="col-lg-6">

                                            <input type="text" required=""

                                                   value="<?= (!empty($_POST['smtp_host']) ? $_POST['smtp_host'] : '') ?>"

                                                   class="form-control" name="smtp_host">

                                            <span class="help-block  ">SMTP Server Address</strong>.</span>

                                        </div>

                                    </div>

                                    <div class="form-group">

                                        <label class="col-lg-3 control-label"><?= lang('smtp_user') ?></label>

                                        <div class="col-lg-6">

                                            <input type="text" required=""

                                                   value="<?= (!empty($_POST['smtp_user']) ? $_POST['smtp_user'] : '') ?>"

                                                   class="form-control" name="smtp_user">

                                        </div>

                                    </div>

                                    <div class="form-group">

                                        <label class="col-lg-3 control-label"><?= lang('smtp_pass') ?></label>

                                        <div class="col-lg-6">

                                            <input type="password" name="smtp_pass"

                                                   value="<?= (!empty($_POST['smtp_pass']) ? $_POST['smtp_pass'] : '') ?>"

                                                   class="form-control">

                                        </div>

                                    </div>

                                    <div class="form-group">

                                        <label class="col-lg-3 control-label"><?= lang('smtp_port') ?></label>

                                        <div class="col-lg-6">

                                            <input type="text" required=""

                                                   value="<?= (!empty($_POST['smtp_port']) ? $_POST['smtp_port'] : '') ?>"

                                                   class="form-control" name="smtp_port">

                                        </div>

                                    </div>

                                    <div class="form-group">

                                        <label class="col-lg-3 control-label"><?= lang('email_encryption') ?></label>

                                        <div class="col-lg-3">

                                            <select name="smtp_encryption" class="form-control">

                                                <option

                                                        value=""><?= lang('none') ?></option>

                                                <option

                                                        value="ssl" <?= (!empty($_POST['smtp_encryption']) && $_POST['smtp_encryption'] == 'ssl' ? 'selected' : '') ?>>

                                                    <?= lang('SSL') ?>

                                                </option>

                                                <option

                                                        value="tls" <?= (!empty($_POST['smtp_encryption']) && $_POST['smtp_encryption'] == 'tls' ? 'selected' : '') ?>>

                                                    <?= lang('TLS') ?>

                                                </option>

                                            </select>

                                        </div>

                                    </div>

                                </div>



                                <div class="form-group">

                                    <label class="col-lg-3 control-label"></label>

                                    <div class="col-lg-7">

                                        <button type="submit"

                                                class="btn btn-primary"><?= lang('almost_done') ?></button>
                                                 <button type="submit"

                                                class="btn btn-info"><?= lang('Skip for Now') ?></button>

                                    </div>

                                </div>

                                </form>



                            </div>

                        <?php } ?>

                        <?php if ($step == 4) { ?>

                            <div class="step-pane <?= $step4 ?>" id="step4">

                                <?php echo '<form action="" id="complete" novalidate="novalidate" class="form-horizontal" method="post" accept-charset="utf-8">'; ?>



                                <!-- Start step 1-->

                                <?php echo '<input type="hidden" name="step" value="' . $step . '">'; ?>

                                <?php echo '<input type="hidden" name="subscriptions_id" value="' . $_POST['subscriptions_id'] . '">'; ?>

                                <?php echo '<input type="hidden" name="email" value="' . $_POST['email'] . '">'; ?>
                                <?php echo '<input type="hidden" name="fullname" value="' . $_POST['fullname'] . '">'; ?>

                                <?php echo '<input type="hidden" name="activation_token" value="' . $_POST['activation_token'] . '">'; ?>

                                <?php echo '<input type="hidden" name="username" value="' . $_POST['username'] . '">'; ?>

                                <?php echo '<input type="hidden" name="password" value="' . $_POST['password'] . '">'; ?>

                                <!-- End step 1-->



                                <!-- Start step 2-->

                                <?php echo '<input type="hidden" name="company_name" value="' . $_POST['company_name'] . '">'; ?>

                                <?php echo '<input type="hidden" name="contact_person" value="' . $_POST['contact_person'] . '">'; ?>

                                <?php echo '<input type="hidden" name="company_address" value="' . $_POST['company_address'] . '">'; ?>

                                <?php echo '<input type="hidden" name="company_country" value="' . $_POST['company_country'] . '">'; ?>

                                <?php echo '<input type="hidden" name="company_city" value="' . $_POST['company_city'] . '">'; ?>

                                <?php echo '<input type="hidden" name="company_zip_code" value="' . $_POST['company_zip_code'] . '">'; ?>

                                <?php echo '<input type="hidden" name="company_phone" value="' . $_POST['company_phone'] . '">'; ?>

                                <?php echo '<input type="hidden" name="timezone" value="' . $_POST['timezone'] . '">'; ?>

                                <!-- End step 2-->

                                <!-- Start step 3-->

                                <?php echo '<input type="hidden" name="use_postmark" value="' . $_POST['use_postmark'] . '">'; ?>

                                <?php echo '<input type="hidden" name="postmark_api_key" value="' . $_POST['postmark_api_key'] . '">'; ?>

                                <?php echo '<input type="hidden" name="postmark_from_address" value="' . $_POST['postmark_from_address'] . '">'; ?>

                                <?php echo '<input type="hidden" name="protocol" value="' . $_POST['protocol'] . '">'; ?>

                                <?php echo '<input type="hidden" name="smtp_host" value="' . $_POST['smtp_host'] . '">'; ?>

                                <?php echo '<input type="hidden" name="smtp_user" value="' . $_POST['smtp_user'] . '">'; ?>

                                <?php echo '<input type="hidden" name="smtp_pass" value="' . $_POST['smtp_pass'] . '">'; ?>

                                <?php echo '<input type="hidden" name="smtp_port" value="' . $_POST['smtp_port'] . '">'; ?>

                                <?php echo '<input type="hidden" name="smtp_encryption" value="' . $_POST['smtp_encryption'] . '">'; ?>

                                <!-- End step 3-->





                                <!-- <div class="form-group">

                                    <label class="col-lg-3 control-label"><?= lang('full_name') ?></label>

                                    <div class="col-lg-7">

                                        <input type="text" class="form-control" id="full_name"

                                               placeholder="<?= lang('enter_placeholder', lang('full_name')) ?>"

                                               name="fullname">

                                    </div>

                                </div> -->

                                <div class="form-group">

                                    <label class="col-lg-3 control-label"><?= lang('department') ?></label>

                                    <div class="col-lg-7">

                                        <input type="text" class="form-control"

                                               placeholder="<?= lang('enter_placeholder', lang('department')) ?>"

                                               name="department" value="Admin">

                                    </div>

                                </div>

                                <div class="form-group">

                                    <label class="col-lg-3 control-label"><?= lang('Your Role') ?></label>

                                    <div class="col-lg-7">

                                        <input type="text" class="form-control"

                                               placeholder="<?= lang('enter_placeholder', lang('Your Role')) ?>"

                                               name="designation" value="Admin Head">

                                    </div>

                                </div>
                                 <div class="form-group">

                                    <label class="col-lg-3 control-label"><?= lang('Industry Module') ?></label>

                                    <div class="col-lg-7">

                                       <select name="category" class="form-control">

                                                <option

                                                        value=""><?= lang('All Industry') ?></option>

                                                        <?php if($industries = get_industries()){
                                                            foreach ($industries as $key => $industry_name) {
                                                                ?>
                                                                <option value="<?=$industry_name;?>"><?=$industry_name;?></option>
                                                                <?php
                                                            }
                                                        } ?>
                                               

                                            </select>

                                    </div>

                                </div>



                                <div class="form-group">

                                    <label class="col-lg-3 control-label"></label>

                                    <div class="col-lg-3">

                                        <button type="submit" id="finish"

                                                class="btn-block btn btn-primary"><?= lang('COMPLETE SETUP & GO LIVE') ?></button>

                                    </div>

                                </div>

                                <?php echo form_close(); ?>

                                <div class="installation_guideline alert alert-info"

                                     style="display: none;font-size: 16px">

                                    <?= lang('installation_guideline') ?>

                                </div>

                            </div>

                        <?php } ?>

                        <?php if ($step == 5) {

                            $base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';

                            $base_url .= '://' . $_SERVER['HTTP_HOST'];

                            $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

                            $base_url = preg_replace('/install.*/', '', $base_url);

                            ?>

                            <h4 class="bold">Installation successful!</h4>

                            <p> login Link : <a

                                        href="<?php echo $base_url; ?>login"

                                        target="_blank"><?php echo $base_url; ?>login</a></p>


                            <hr/>

                        <?php } ?>



                    </div>

                </div>

            </div>

        </section>

    </div>

</section>

<!--main content end-->

<script src="<?= base_url() ?>assets/plugins/install/jquery-2.2.4.min.js"></script>

<script src="<?= base_url() ?>assets/plugins/install/app.js"></script>

<script src="<?= base_url() ?>assets/plugins/install/jquery.validate.min.js"></script>

<script type="text/javascript">

    $('input[id="use_postmark"]').click(function () {

        if (this.checked) {

            $('div#postmark_config').show();

        } else {

            $('div#postmark_config').hide();

        }

    });

</script>

<script>



    $(function () {

        setTimeout(function () {

            $('.sql-debug-alert').slideUp();

        }, 3000);

    });



    $('#finish').on('click', function () {

        var result = $("#complete").valid();

        if (result == true) {

            $('.installation_guideline').css("display", "block");

            var ubtn = $(this);

            ubtn.html('<?= lang('wait_text')?>...');

            ubtn.addClass('disabled');

        }

    });

</script>

<script>

    $(function () {

        $("#account_info").validate({

            rules: {

                email: {

                    required: true,

                    email: true

                },

                activation_token: "required",

                username: "required",

                password: {

                    required: true,

                    minlength: 5

                },

                confirm_password: {

                    minlength: 5,

                    required: true,

                    equalTo: "#password"

                }

            },



            // Specify the validation error messages

            messages: {

                hostname: "Please enter your email address which is you registered",

                activation_token: "Please enter your activation token which is you received from email",

                username: "Enter your login username"

            },



            submitHandler: function (form) {

//                alert(form);

                form.submit();

            }

        });



        $("#company_info").validate({

            rules: {

                timezone: "required",

                company_name: "required",

                contact_person: "required",

                company_address: "required",

                company_city: "required",

                company_zip_code: "required",

                company_country: "required",

            },



            // Specify the validation error messages

            messages: {

                envato_user: "We need your envato username to verify The purchase",

                purchase_key: "Enter your envato purchase code here"

            },



            submitHandler: function (form) {

                form.submit();

            }

        });



        $("#complete").validate({

            rules: {

                fullname: "required",

                department: "required",

                designation: "required",

            },

            // Specify the validation error messages

            messages: {

                fullname: "Set your full name",

            },

            submitHandler: function (form) {

                form.submit();

            }

        });





    });

</script>

</body>

</html>

