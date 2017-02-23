<?php

namespace app\controllers;

use app\core\Web;
use app\models\Language;

class Controller extends \app\core\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function action404()
    {
        header('HTTP/1.0 404 Not Found');
        return $this->render('404');
    }

    public function actionNoAction()
    {
        return $this->render('no_action');
    }
}
