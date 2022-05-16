<?php
$currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
?>
<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                class="sr-only">Close</span></button>
        <h4 class="modal-title"
            id="myModalLabel"><?= lang('buy') . ' ' . lang('Plan') . '-' . plan_name($plan_info->id) ?>
            <?= display_money($plan_info->amount, $currency->symbol); ?>
        </h4>
    </div>
    <div class="modal-body wrap-modal wrap">
        <div class="panel panel-custom">
            <div class="panel-heading">
                <?= lang('select') . ' ' . lang('payment_method') ?>
            </div>
        </div>
        <ul>
            <?php if ($plan_info->allow_paypal == 'Yes') {
                ?>
                <li>
                    <a href="<?= base_url() ?>payment/paypal/payPlan/<?= $plan_info->id . '/' . $sub_info->subscriptions_id ?>"
                       title="<?= lang('paypal') ?>"><?= lang('paypal') ?></a></li>
                <?php
            }
            if ($plan_info->allow_2checkout == 'Yes') {
                ?>
                <li>
                    <a href="<?= base_url() ?>payment/checkout/payPlan/<?= $plan_info->id . '/' . $sub_info->subscriptions_id ?>"
                       title="<?= lang('2checkout') ?>"><?= lang('2checkout') ?></a></li>
            <?php }
            if ($plan_info->allow_stripe == 'Yes') {
                ?>
                <li>
                    <a href="<?= base_url() ?>payment/stripe/payPlan/<?= $plan_info->id . '/' . $sub_info->subscriptions_id ?>"><?= lang('stripe') ?></a>
                </li>
            <?php }
            if ($plan_info->allow_authorize == 'Yes') { ?>
                <li>
                    <a href="<?= base_url() ?>payment/authorize/payPlan/<?= $plan_info->id . '/' . $sub_info->subscriptions_id ?>"
                       title="<?= lang('authorize') ?>"><?= lang('authorize') ?></a></li>
            <?php }
            if ($plan_info->allow_ccavenue == 'Yes') { ?>
                <li>
                    <a href="<?= base_url() ?>payment/ccavenue/payPlan/<?= $plan_info->id . '/' . $sub_info->subscriptions_id ?>"
                       title="<?= lang('ccavenue') ?>"><?= lang('ccavenue') ?></a></li>
            <?php }
            if ($plan_info->allow_braintree == 'Yes') { ?>
                <li>
                    <a href="<?= base_url() ?>payment/braintree/payPlan/<?= $plan_info->id . '/' . $sub_info->subscriptions_id ?>"
                       title="<?= lang('braintree') ?>"><?= lang('braintree') ?></a></li>
            <?php }
            if ($plan_info->allow_mollie == 'Yes') { ?>

                <li>
                    <a href="<?= base_url() ?>payment/mollie/pay/payPlan/<?= $plan_info->id . '/' . $sub_info->subscriptions_id ?>"
                       title="<?= lang('mollie') ?>"><?= lang('mollie') ?></a></li>
            <?php }
            if ($plan_info->allow_payumoney == 'Yes') { ?>
                <li>
                    <a href="<?= base_url() ?>payment/payumoney/payPlan/<?= $plan_info->id . '/' . $sub_info->subscriptions_id ?>"
                           title="<?= lang('PayUmoney') ?>"><?= lang('PayUmoney') ?></a></li>
            <?php } ?>
        </ul>
    </div>
</div>
