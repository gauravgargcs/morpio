<?php if ($plan_info->allow_stripe == 'Yes') { ?>
    <!-- START STRIPE PAYMENT -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://checkout.stripe.com/checkout.js"></script>
    <script>
        var handler = StripeCheckout.configure({
            key: '<?=config_item('stripe_public_key')?>',
            image: '<?=base_url() . config_item('company_logo')?>',
            locale: 'auto',
            token: function (token) {
                // Use the token to create the charge with a server-side script.
                // You can access the token ID with `token.id`
                $("#stripeToken").val(token.id);
                $("#stripeEmail").val(token.email);
                $("#stripeForm").submit();
            }
        });
        var counter = 0;
        $('#customButton').on('click', function (e) {
            // Open Checkout with further options
            var amount = $('#amount').val();
            if (counter <= 0) {
                $('#set_amount').append('<input name="amount" value="' + amount + '" type="hidden">');
                counter++;
            }
            handler.open({
                name: '<?=config_item('company_name')?>',
                description: 'Payment For : #<?=$invoice_info['item_name']?>',
                amount: amount * 100,
                currency: '<?=$invoice_info['currency']?>'
            });
            e.preventDefault();
        });
        // Close Checkout on page navigation
        //        $(window).on('popstate', function () {
        //            handler.close();
        //        });
    </script>
    <?php
    $attributes = array('id' => 'stripeForm');
    echo form_open(base_url() . 'payment/stripe/plan_authenticate', $attributes); ?>
    <input type="hidden" id="stripeToken" name="stripeToken"/>
    <input type="hidden" id="stripeEmail" name="stripeEmail"/>
    <input type="hidden" name="id" value="<?= $invoice_info['item_number'] ?>"/>
    <input type="hidden" name="name" value="<?= $invoice_info['item_name'] ?>"/>
    </form>
    <!-- END STRIPE CHECKOUT -->
<?php } ?>

<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                class="sr-only">Close</span></button>
        <h4 class="modal-title">Paying
            <strong><?= display_money($invoice_info['amount'], $invoice_info['currency']); ?></strong> for
            #<?= $invoice_info['item_name'] ?> via Stripe</h4>
    </div>
    <div class="modal-body">
        <?php

        // Show PHP errors, if they exist:
        if (isset($errors) && !empty($errors) && is_array($errors)) {
            echo '<div class="alert alert-error"><h4>Error!</h4>The following error(s) occurred:<ul>';
            foreach ($errors as $e) {
                echo "<li>$e</li>";
            }
            echo '</ul></div>';
        }
        ?>

        <div id="payment-errors"></div>
        <input type="hidden" name="invoice_id" value="<?= $invoice_info['item_number'] ?>">
        <input name="amount" id="amount" value="<?= $invoice_info['amount'] ?>" type="hidden">
        <div class="form-group">
            <label class="col-lg-4 control-label"><?= lang('amount') ?> ( <?= $invoice_info['currency'] ?>) </label>
            <div class="col-lg-4">
                <input type="text" class="form-control" value="<?= display_money($invoice_info['amount']) ?>"
                       readonly>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></a>
        <input type="submit" id="customButton" value="Submit Payment" class="btn btn-success"/>
    </div>
</div>

