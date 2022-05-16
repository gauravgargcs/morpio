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
            </strong> # <?= getDescription($input_data) ?> via <?= lang('braintree') ?></h4>
    </div>
    <div class="panel-body">
        <?php
        $attributes = array('id' => 'braintree_form', 'name' => 'braintree', 'data-parsley-validate' => "", 'novalidate' => "", 'class' => 'form-horizontal');
        echo form_open('payment/braintree/complete_purchase', $attributes);
        ?>
        <section>
            <div class="bt-drop-in-wrapper">
                <div id="bt-dropin"></div>
            </div>
            <input id="amount" name="amount" type="hidden"
                   value="<?php echo number_format($input_data['total'], 2, '.', ''); ?>">
            <input name="input_data" type="hidden" value='<?= json_encode($input_data) ?>'/>
        </section>
        <div class="text-center" style="margin-top:15px;">
            <button class="btn btn-info" type="submit"><?php echo lang('submit'); ?></button>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>
<script src="https://js.braintreegateway.com/js/braintree-2.30.0.min.js"></script>
<script>
    braintree.setup('<?php echo !empty($client_token) ? $client_token : ''; ?>', 'dropin', {
        container: 'bt-dropin'
    });
</script>
