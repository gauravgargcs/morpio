<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?= lang('new_payment') ?></h4>
    </div>
    <div class="modal-body">
        <p><?= lang('paypal_redirection_alert') ?></p>
<script type="text/javascript"></script>
        <?php
        $attributes = array('name' => 'paypal_form', 'data-parsley-validate' => "", 'novalidate' => "", 'class' => 'bs-example form-horizontal');
        echo form_open($paypal_url, $attributes);
        $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
        ?>
        <input name="rm" value="2" type="hidden">
        <input name="cmd" value="_xclick" type="hidden">
        <input name="currency_code" value="<?= config_item('default_currency') ?>" type="hidden">
        <input name="quantity" value="1" type="hidden">
        <input name="business" value="<?= $this->config->item('paypal_email') ?>" type="hidden">
        <input name="return" value="<?= base_url() ?><?= $this->config->item('paypal_success_url') ?>" type="hidden">
        <input name="cancel_return" value="<?= base_url() ?><?= $this->config->item('paypal_cancel_url') ?>"
               type="hidden">
        <input name="notify_url" value="<?= base_url() ?><?= $this->config->item('paypal_ipn_url') ?>" type="hidden">
        <input name="custom" value="<?= $this->session->userdata('client_id') ?>" type="hidden">
        <input name="item_name" value="<?= $plan_info->name ?>" type="hidden">
        <input name="item_number" value="<?= $plan_info->id ?>" type="hidden">
        <input name="amount" value="<?= $plan_info->amount ?>" type="hidden">

        <div class="form-group">
            <label class="col-lg-4 control-label"><?= lang('amount') ?> ( <?= $currency->symbol ?>) </label>
            <div class="col-lg-4">
                <input type="text" class="form-control" value="<?= display_money($plan_info->amount) ?>"
                       readonly>
            </div>
        </div>
        <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></a>
            <button type="submit" class="btn btn-success"><?= lang('pay') ?></button>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<!-- /.modal-content -->