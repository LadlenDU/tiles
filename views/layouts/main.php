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

    <link rel="stylesheet" href="<?php Html::mkLnk('/css/index.css') ?>">
</head>
<body>
<div class="container">
    <?php echo $values->content ?>
</div>
<script src="<?php Html::mkLnk('/js/helper.js') ?>"></script>
</body>
</html>
