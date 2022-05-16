<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            font-family: Verdana, Geneva, sans-serif;
            font-size: 14px;
        }

        .wrapper {
            margin: 0 auto;
            display: block;
            background: #f0f0f0;
            width: 600px;
            border: 1px solid #e4e4e4;
            padding: 20px;
            border-radius: 4px;
            margin-top: 50px;
            text-align: center;
        }

        .wrapper h1 {
            text-align: center;
            font-size: 24px;
            color: red;
            margin-top: 0px;
        }

        .wrapper .upgrade_now {
            text-transform: uppercase;
            background: #82b440;
            color: #fff;
            padding: 15px 25px;
            border-radius: 3px;
            text-decoration: none;
            text-align: center;
            border: 0px;
            outline: 0px;
            cursor: pointer;
            font-size: 15px;
        }

        .wrapper .upgrade_now:hover, .wrapper .upgrade_now:active {
            background: #73a92d;
        }

        .upgrade_now_wrapper {
            margin: 0 auto;
            width: 100%;
            text-align: center;
            margin-top: 40px;
            margin-bottom: 40px;
        }
        .note {
            color: #636363;
        }
        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <h1>
        Your domain did not registered
    </h1>
    <p>Your domain did not registered.You need to register new domain from <a
            href="<?= config_item('default_url') ?>">here</a></p>
    <p class="bold" ><a style="text-decoration: none;color: #000;" href="<?= config_item('default_url') ?>">Dont worry about payment.You can trial free </a></p>
</div>
</body>
</html>
