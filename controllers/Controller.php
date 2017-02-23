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

    public function actionSessionExpired()
    {
        return $this->render('session_expired');
    }

    public function actionNoCookies()
    {
        return $this->render('no_cookies');
    }

    public function actionAccessDenied()
    {
        return $this->render('access_denied');
    }

    /**
     * Проверка работают ли cookies.
     *
     * @return string|void
     */
    public function actionCheckCookies()
    {
        if (empty($_COOKIE[ini_get('session.name')]))
        {
            return $this->render('no_cookies');
        }
        else
        {
            Web::redirect($_GET['ret_path']);
        }
    }
}
