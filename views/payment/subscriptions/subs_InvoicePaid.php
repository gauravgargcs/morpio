<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head><title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=320, target-densitydpi=device-dpi">
</head>
<body>
<p><?= strftime("%b %d, %Y", time()); ?> </p>
<p>Hi <?= $client ?> <br>
    Thank you for your Payment for <?= $amount ?>. Your Payment has been applied to the Subscription Successfully.
</p>
--------------------------
<p>You can login to your Dashboard to view the Invoice <a href="<?= $login_url ?>">My Account</a></p><br>

------------------------------

<p>Regards <br>
    <?= getConfigItems('company_name') ?> Team <br>
    Copyright &copy; <?= getConfigItems('company_name') ?> <?= date('Y') ?></p>
</body>
</html>