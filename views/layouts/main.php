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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-param" content="<?php echo Csrf::inst()->getCsrfTokenName() ?>">
    <meta name="csrf-token" content="<?php echo Csrf::inst()->getCsrfToken() ?>">

    <title><?php echo $this->title ?></title>

    <link href="/favicon.ico" rel="icon" type="image/x-icon"/>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link media="all" type="text/css" rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Datatable -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs-3.3.7/dt-1.10.13/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs-3.3.7/dt-1.10.13/datatables.min.js"></script>

    <link rel="stylesheet" href="/css/index.css">

    <?php echo $this->css ?>

    <script type="text/javascript">
        // <![CDATA[

        /** @type {namespace} - Общий объект приложения. */
        var app = {};

        // ]]>
    </script>

</head>
<body>

<noscript><div class="container noscript"><h2>Включите, пожалуйста, JavaScript</h2></div></noscript>

<div id="content_wrapper" style="display: none">
<div id="main_background"></div>
<div id="main_container" class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-sm-4 sidebar3">

            <div class="fa fa-book logo"></div>

            <div class="name">
                <h3>Библиотека</h3>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-sm-4 sidebar3">
            <div class="left-navigation">
                <ul>
                    <li><i class="fa fa-book" aria-hidden="true"></i>Книги</li>
                    <!--<li><i class="fa fa-bookmark-o" aria-hidden="true"></i>Active Books <span
                            class="activebooks pull-right">3</span></li>-->
                    <li><i class="fa fa-print" aria-hidden="true"></i>Издательства</li>
                    <li><i class="fa fa-users" aria-hidden="true"></i>Авторы</li>
                </ul>
                <ul>
                    <li><i class="fa fa-search" aria-hidden="true"></i>Поиск</li>
                </ul>
                <!--<li class="list">
                    <div class="dropdown">
                        <i class="fa fa-list" aria-hidden="true"></i>My Wishlist <i class="fa fa-plus pull-right"
                                                                                    aria-hidden="true"></i>
            </div>
            <ul class="submenu hide">
                <li>The Sealed Nectar</li>
                <li>Pride and Prejudice</li>
                <li>HTML5 for Web Designers</li>
                <li>The 100, Michael H Heart</li>
            </ul>
            </li>
            </ul>-->
                <!-- <ul class="category">
                     <li><i class="fa fa-circle-thin" aria-hidden="true"></i>Family Reading</li>
                     <li><i class="fa fa-circle-thin" aria-hidden="true"></i>Education</li>
                     <li><i class="fa fa-circle-thin" aria-hidden="true"></i>Business</li>
                 </ul>
                 <ul>
                     <li><i class="fa fa-cog" aria-hidden="true"></i>Settings</li>
                     <li><i class="fa fa-power-off" aria-hidden="true"></i>Logout</li>
                 </ul>-->
            </div>
        </div>
        <div class="col-md-8 main-content">

            <?php echo $values->content ?>

        </div>

    </div>
</div>
</div>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>

<script src="<?php Html::mkLnk('/js/helper.js') ?>"></script>
<!--<script src="/js/Form.js"></script>
<script src="/js/FormValidation.js"></script>
<script src="/js/user/LoginForm.js"></script>-->

<?php #echo $this->js ?>

<script type="text/javascript">
    // <![CDATA[

    $(function () {
        /*$('.dropdown').click(function () {
            $(this).siblings(".submenu").toggleClass('hide');
        });*/

        $('#content_wrapper').show();
    });

    /*app.helper.extend(app.user.LoginForm, app.Form);
     var loginForm = new app.user.LoginForm($(".login_form"));*/

    // ]]>
</script>

</body>
</html>
