<?php echo message_box('success') ?>
<?php echo message_box('error') ?>
<?php
$cur = get_old_data('tbl_currencies', array('code' => config_item('default_currency')));
$subs_info = get_old_data('tbl_subscriptions', array('subscriptions_id' => $input_data['subscriptions_id']));
$subs_hisroty = get_old_data('tbl_subscriptions_history', array('status' => $subs_info->status, 'subscriptions_id' => $input_data['subscriptions_id']));
?>
<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                class="sr-only">Close</span></button>
        <h4 class="modal-title">Paying <strong>
                <?= display_money($input_data['total'], $cur->symbol); ?>
            </strong> # <?= getDescription($input_data) ?> via <?= lang('PayUmoney') ?></h4>
    </div>
    <div class="panel-body">
        <?php
        $attributes = array('id' => 'payUmoney', 'name' => 'payUmoney', 'data-parsley-validate' => "", 'novalidate' => "", 'class' => 'form-horizontal');
        echo form_open($action_url, $attributes);
        ?>
        <input type="hidden" name="key" value="<?php echo $key ?>"/>
        <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
        <input type="hidden" name="txnid" value="<?php echo $txnid ?>"/>
        <input type="hidden" name="amount" value="<?php echo $input_data['total'] ?>"/>
        <input type="hidden" name="surl"
               value="<?php echo site_url('payment/payumoney/subs_success'); ?>"/>
        <input type="hidden" name="furl"
               value="<?php echo site_url('payment/payumoney/subs_failure'); ?>"/>
        <input type="hidden" name="service_provider" value="payu_paisa" size="64"/>
        <input type="hidden" name="productinfo" value="<?= getDescription($input_data, true) ?>"/>

        <input name="input_data" type="hidden" value='<?= json_encode($input_data) ?>'/>
        <div class="form-group">
            <label class="col-lg-4 control-label"><?= lang('amount') ?> ( <?= $cur->symbol ?>) </label>
            <div class="col-lg-4">
                <input type="text" class="form-control" value="<?= display_money($input_data['total']) ?>"
                       readonly>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-4 control-label"><?= lang('name') ?> </label>
            <div class="col-lg-5">
                <input type="text" required name="firstname" class="form-control"
                       value="<?= $firstname ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label"><?= lang('email') ?> </label>
            <div class="col-lg-5">
                <input type="text" required name="email" class="form-control"
                       value="<?= $email ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label"><?= lang('phone') ?> </label>
            <div class="col-lg-5">
                <input type="text" required name="phone" class="form-control"
                       value="<?= $phonenumber ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label"><?= lang('address') ?> </label>
            <div class="col-lg-5">
                <textarea class="form-control" name="address"><?= $address ?></textarea>
            </div>
        </div>

        <div class="modal-footer">
            <a href="<?= base_url('checkoutPayment') ?>" class="btn btn-default"
               data-dismiss="modal"><?= lang('close') ?></a>
            <input type="submit" value="<?= lang('submit') ?>" class="btn btn-success"/>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<script>
    $(function () {
        $('#payUmoney').validate({
            submitHandler: function (form) {
                $('input[type="submit"]').prop('disabled', true);
                return true;
            }
        });
    });
    $(window).on("load", function () {
        var hash = '<?php echo $hash; ?>';
        if (hash == '') {
            return;
        }
        var payUmoney = document.forms.payUmoney;
        payUmoney.submit();
    });
</script>

