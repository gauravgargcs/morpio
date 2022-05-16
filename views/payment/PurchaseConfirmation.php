<p><?= strftime("%b %d, %Y", time()); ?> </p>
<p>Hi <?= $name ?> <br>
    Thank you for your Payment (<?= $amount ?>) for <?= $plan_name ?>. Your Payment has been applied to the Invoice
    Successfully. </p>
--------------------------
<p>You can login to your Dashboard to view the Invoice <a href="<?= $login_url ?>">My Account</a></p><br>

------------------------------

<p>Regards <br>
    <?= $this->config->item('company_name') ?> Team <br>
    Copyright &copy; <?= $this->config->item('company_name') ?> <?= date('Y') ?></p>
