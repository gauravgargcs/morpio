<?php echo message_box('success') ?>
<?php echo message_box('error') ?>
<?php
$cur = get_old_data('tbl_currencies', array('code' => $input_data['currency']));
$subs_info = get_old_data('tbl_subscriptions', array('subscriptions_id' => $input_data['subscriptions_id']));
$subs_hisroty = get_old_data('tbl_subscriptions_history', array('status' => $subs_info->status, 'subscriptions_id' => $input_data['subscriptions_id']));
?>
<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                class="sr-only">Close</span></button>
        <h4 class="modal-title">Paying <strong>
                <?= display_money($input_data['total'], $cur->symbol); ?>
            </strong> # <?= getDescription($input_data) ?> via <?= lang('ccavenue') ?></h4>
    </div>
    <div class="panel-body">
        <form method="post" name="customerData" class="form-horizontal"
              action="<?= base_url('payment/ccavenue/confirm/subs') ?>">

            <input type="hidden" name="tid" id="tid" value="<?= time() ?>"/>
            <input type="hidden" name="merchant_id" value="<?= getConfigItems('ccavenue_merchant_id') ?>"/>

            <input type="hidden" name="order_id" value="<?= $subs_hisroty->id ?>"/>
            <?php if (!empty($allow_customer_edit_amount) && $allow_customer_edit_amount == 'No') { ?>
                <input type="hidden" name="amount" value="<?= $input_data['total'] ?>"/>
            <?php } ?>
            <div class="form-group">
                <label class="col-lg-4 control-label"><?= lang('amount') ?> ( <?= $input_data['currency'] ?>) </label>
                <div class="col-lg-4">
                    <?php if (!empty($allow_customer_edit_amount) && $allow_customer_edit_amount == 'Yes') { ?>
                        <input type="text" id="amount" required name="amount" data-parsley-type="number"
                               data-parsley-max="<?= $input_data['total'] ?>" class="form-control"
                               value="<?= ($input_data['total']) ?>">
                    <?php } else { ?>
                        <input type="text" class="form-control" value="<?= display_money($input_data['total']) ?>"
                               readonly>
                    <?php } ?>
                </div>
            </div>
            <input type="hidden" name="currency" value="<?php echo $input_data['currency'] ?>"/>
            <input type="hidden" name="redirect_url"
                   value="<?php echo site_url('payment/ccavenue/subs_success'); ?>"/>
            <input type="hidden" name="cancel_url"
                   value="<?php echo site_url('payment/ccavenue/subs_failure'); ?>"/>
            <input type="hidden" name="language" value="EN"/>
            <?php
            if ($_POST) {
                $name = $_POST['billing_name'];
                $email = $_POST['billing_email'];
                $tel = $_POST['billing_tel'];
                $address = $_POST['billing_address'];
                $city = $_POST['billing_city'];
                $state = $_POST['billing_state'];
                $pcountry = $_POST['billing_country'];
                $zipcode = $_POST['billing_zip'];
            } else {
                $name = (!empty($subs_info->name) ? $subs_info->name : '');
                $email = (!empty($subs_info->email) ? $subs_info->email : '');
                $tel = (!empty($subs_info->mobile) ? $subs_info->mobile : '');
                $address = (!empty(config_item('company_address')) ? config_item('company_address') : '');
                $city = (!empty(config_item('company_city')) ? config_item('company_city') : '');
                $state = config_item('company_state');
                $pcountry = (!empty($subs_info->country) ? $subs_info->country : '');
                $zipcode = (!empty(config_item('company_zip_code')) ? config_item('company_zip_code') : '');
            }
            ?>
            <div class="form-group">
                <label class="col-lg-4 control-label"><?= lang('name') ?> </label>
                <div class="col-lg-5">
                    <input type="text" name="billing_name" class="form-control"
                           value="<?php echo !empty($name) ? $name : ''; ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label"><?= lang('email') ?> </label>
                <div class="col-lg-5">
                    <input type="text" name="billing_email" class="form-control"
                           value="<?php echo !empty($email) ? $email : ''; ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label"><?= lang('phone') ?> </label>
                <div class="col-lg-5">
                    <input type="text" name="billing_tel" class="form-control"
                           value="<?php echo !empty($tel) ? $tel : ''; ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label"><?= lang('address') ?> </label>
                <div class="col-lg-5">
                <textarea class="form-control"
                          name="billing_address"><?php echo !empty($address) ? $address : ''; ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">
                    <?php echo lang('city'); ?>
                </label>
                <div class="col-lg-6">
                    <input type="text" name="billing_city"
                           value="<?php echo !empty($city) ? $city : ''; ?>" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-4 control-label">
                    <?php echo lang('state'); ?>
                </label>
                <div class="col-lg-6">
                    <input type="text" name="billing_state" value="<?= (!empty($state) ? $state : '') ?>"
                           class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-4 control-label">
                    <?php echo lang('country'); ?>
                </label>
                <div class="col-lg-6">
                    <select name="billing_country" class="form-control">
                        <option value=""></option>
                        <?php
                        $countries = get_result('tbl_countries');
                        foreach ($countries as $country) {
                            $selected = '';
                            if (!empty($pcountry) && $pcountry == $country->value) {
                                $selected = 'selected';
                            }
                            echo '<option ' . $selected . ' value="' . $country->value . '">' . $country->value . '</option>';
                        } ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-4 control-label">
                    <?php echo lang('zip_code'); ?>
                </label>
                <div class="col-lg-6">
                    <input type="text" name="billing_zip"
                           value="<?php echo !empty($zipcode) ? $zipcode : ''; ?>"
                           class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <a href="<?= base_url('checkoutPayment') ?>" class="btn btn-default"
                   data-dismiss="modal"><?= lang('close') ?></a>
                <input type="submit" id="submit" value="<?= lang('submit') ?>" class="btn btn-success"/>
            </div>
        </form>
    </div>
</div>
