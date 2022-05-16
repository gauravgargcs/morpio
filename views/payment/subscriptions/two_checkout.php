<?php echo message_box('success') ?>
<?php echo message_box('error') ?>
<?php
$cur = get_old_data('tbl_currencies', array('code' => $input_data['currency']));
$subs_info = get_old_data('tbl_subscriptions', array('subscriptions_id' => $input_data['subscriptions_id']));
$subs_hisroty = get_old_data('tbl_subscriptions_history', array('status' => $subs_info->status, 'subscriptions_id' => $input_data['subscriptions_id']));
?>
<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Paying <strong>
                <?= display_money($input_data['total'], $cur->symbol); ?>
            </strong> # <?= getDescription($input_data) ?> via 2Checkout</h4>
    </div>
    <div class="panel-body">
        <p class="text-info text-center"><?php echo lang('2checkout_notice_payment'); ?></p>
        <?php
        $attributes = array('id' => '2checkout_form', 'name' => '2checkout', 'data-parsley-validate' => "", 'novalidate' => "", 'class' => 'form-horizontal');
        echo form_open('payment/checkout/complete_purchase', $attributes);
        ?>

        <input name="token" type="hidden" value="" />
        <input name="input_data" type="hidden" value='<?= json_encode($input_data) ?>' />
        <div class="form-group">
            <label class="col-lg-4 control-label"><?= lang('amount') ?> ( <?= $cur->symbol ?>) </label>
            <div class="col-lg-4">
                <input type="text" class="form-control" value="<?= display_money($input_data['total']) ?>" readonly>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-4 control-label"><?= lang('card_number') ?></label>
            <div class="col-lg-5">
                <input type="text" id="ccNo" class="form-control card-number input-medium" autocomplete="off" placeholder="" required>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-4 control-label"><?= lang('CVC') ?></label>
            <div class="col-lg-2">
                <input type="text" id="cvv" size="4" class="form-control card-cvc input-mini" autocomplete="off" placeholder="123" required>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-4 control-label"><?= lang('expiration_card') ?></label>
            <div class="col-lg-2">
                <input type="text" maxlength="2" id="expMonth" class="form-control input-mini" autocomplete="off" placeholder="MM" required>

            </div>
            <div class="col-lg-2">
                <input type="text" maxlength="4" id="expYear" autocomplete="off" class="form-control input-mini" placeholder="YYYY" required>
            </div>
        </div>
        <div class="form-group mt-lg">
            <label class="col-lg-4 control-label" style="visibility: hidden"><?= lang('expiration_card') ?></label>
            <div class="col-lg-2">
                <strong class="bold bb strong"><?php echo lang('billing_address'); ?></strong>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">
                <?php echo lang('email'); ?>
            </label>
            <div class="col-lg-6">
                <input type="email" name="email" class="form-control" required value="<?php echo $subs_info->email; ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">
                <?php echo lang('cardholder_name'); ?>
            </label>
            <div class="col-lg-6">
                <input type="text" name="billingName" class="form-control" value="<?= $subs_info->name ?>" required>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">
                <?php echo lang('billing_address'); ?>
            </label>
            <div class="col-lg-6">
                <input type="text" name="billingAddress1" class="form-control" required>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-4 control-label">
                <?php echo lang('billing_address'); ?> 2
            </label>
            <div class="col-lg-6">
                <input type="text" name="billingAddress2" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-4 control-label">
                <?php echo lang('city'); ?>
            </label>
            <div class="col-lg-6">
                <input type="text" name="billingCity" class="form-control" required>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-4 control-label">
                <?php echo lang('state'); ?>
            </label>
            <div class="col-lg-6">
                <input type="text" name="billingState" class="form-control" required>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-4 control-label">
                <?php echo lang('country'); ?>
            </label>
            <div class="col-lg-6">
                <select name="billingCountry" class="form-control" required>
                    <option value=""></option>
                    <?php
                    $countries = get_result('tbl_countries');
                    foreach ($countries as $country) {
                        $selected = '';
                        if ($subs_info->country == $country->value) {
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
                <input type="text" name="billingPostcode" class="form-control" required>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <a href="#" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></a>
        <input type="submit" value="<?= lang('submit') ?>" class="btn btn-success" />
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="https://www.2checkout.com/checkout/api/2co.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/jquery-validation/jquery.validate.min.js"></script>
<script>
    $.validator.setDefaults({
        errorElement: 'span',
        errorClass: 'text-danger',
    });

    // Called when token created successfully.
    var successCallback = function(data) {
        var myForm = document.getElementById('2checkout_form');
        // Set the token as the value for the token input
        myForm.token.value = data.response.token.token;
        // IMPORTANT: Here we call `submit()` on the form element directly instead of using jQuery to prevent and infinite token request loop.
        $('#2checkout_form').find('input[type="submit"]').addClass('disabled');
        myForm.submit();
    };
    // Called when token creation fails.
    var errorCallback = function(data) {
        // Retry the token request if ajax call fails
        if (data.errorCode === 200) {
            tokenRequest();
            // This error code indicates that the ajax call failed. We recommend that you retry the token request.
        } else {
            alert(data.errorMsg);
        }
    };
    var tokenRequest = function() {
        // Setup token request arguments
        var args = {
            sellerId: "<?php echo getConfigItems('2checkout_seller_id'); ?>",
            publishableKey: "<?php echo getConfigItems('2checkout_publishable_key'); ?>",
            ccNo: $("#ccNo").val(),
            cvv: $("#cvv").val(),
            expMonth: $("#expMonth").val(),
            expYear: $("#expYear").val()
        };
        // Make the token request
        TCO.requestToken(successCallback, errorCallback, args);
    };
    $(function() {
        TCO.loadPubKey('<?php echo (getConfigItems('two_checkout_live') == 1 ? 'production' : 'sandbox'); ?>');
        $("#2checkout_form").submit(function(e) {
            if ($("#2checkout_form").valid() == false) {
                return;
            }
            // Call our token request function
            tokenRequest();
            // Prevent form from submitting
            return false;
        });
    });
</script>