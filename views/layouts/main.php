<?php

/* @var $this app\core\View */
/* @var $values app\core\Container */

use app\helpers\Html;
use app\core\Config;
use app\core\Csrf;

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="<?php echo Config::inst()->globalEncoding ?>"/>
    <title><?php echo $this->title ?></title>

    <link href="/favicon.ico" rel="icon" type="image/x-icon"/>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
        }

        html, body {
            width: 100%;
        }

        .container {
            width: 90%;
            margin: 20px auto;
            position: relative;
        }

        .image {
            overflow: hidden;
            position: absolute;
            top: 0;
            left: 0;
        }

        #img_container {
            position: relative;
            /*background-color: red;*/
            overflow: visible;
            width: 100%;
        }

        .image img {
            max-height: <?php echo Config::inst()->gallery['image_max_height'] ?>px;
            position: relative;
            top: 0;
            left: 50%;
            border: 0;
        }

        .shrink-wrap {
            float: right;
            position: relative;
            left: -50%;
        }

        .upload_file {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <?php echo $values->content ?>
</div>
</body>
</html>
