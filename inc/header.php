<?php
/**
 * File: header.php
 */

?>
    <!DOCTYPE html>

    <html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta http-equiv="cleartype" content="on" />
        <meta name="handheldfriendly" content="true" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
        <meta name="description" content="<?php echo $site_desc; ?>" />
        <meta name="keywords" content="weather" />
        <title><?php echo $site_name; ?></title>

        <!-- CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet" />
        <link href="css/main.css" rel="stylesheet" />
        <link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="/css/datetimepicker.css" rel="stylesheet" type="text/css" />

        <!-- JS -->
        <script src="js/jquery-1.12.2.min.js" type="text/javascript"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="/js/datetimepicker.js" type="text/javascript"></script>
    </head>
    <body>

<?php

include 'inc/nav.php';