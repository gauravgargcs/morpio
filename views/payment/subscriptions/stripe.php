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
            </strong> # <?= getDescription($input_data) ?> via <?= lang('stripe') ?></h4>
    </div>
    <div class="panel-body">
        <?php
        $form = '<form action="' . site_url('payment/stripe/complete_purchase/') . '" method="POST">
                                <script
                                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                data-key="' . getConfigItems('stripe_public_key') . '"
                                data-amount="' . ($input_data['total'] * 100) . '"
                                data-image="' . base_url() . config_item('company_logo') . '"
                                data-name="' . config_item('company_name') . '"
                                data-billing-address="true"
                                data-description=" ' . getDescription($input_data, true) . '";
                                data-locale="auto"
                                data-currency="' . $input_data['currency'] . '"
                                >
                            </script>
                            ' . form_hidden('input_data', json_encode($input_data)) . '
                        </form>';
        echo $form;
        ?>
    </div>

</div>