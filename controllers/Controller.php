<?php

namespace app\controllers;

use app\core\Container;
use app\core\Web;
use app\models\Language;

class Controller extends \app\core\Controller
{
    public function actionIndex()
    {
        $images[] = 'https://tile.expert/img_lb/Dune/Megalos-Ceramic/per_sito/ambienti/z_Megalos%20Ceramic-Dune-7.jpg';
        $images[] = 'https://tile.expert/img_lb/Dune/Megalos-Ceramic/per_sito/ambienti/z_Megalos%20Ceramic-Dune-18.jpg';
        $images[] = 'https://tile.expert/img_lb/Dune/Megalos-Ceramic/per_sito/ambienti/z_Megalos%20Ceramic-Dune-45.jpg';
        $images[] = 'https://tile.expert/img_lb/Dune/Megalos-Ceramic/per_sito/ambienti/z_Megalos%20Ceramic-Dune-29.jpg';
        $images[] = 'https://tile.expert/img_lb/Dune/Megalos-Ceramic/per_sito/ambienti/z_Megalos%20Ceramic-Dune-26.jpg';
        $images[] = 'https://tile.expert/img_lb/Dune/Megalos-Ceramic/per_sito/ambienti/z_Megalos%20Ceramic-Dune-2.jpg';
        $images[] = 'https://tile.expert/img_lb/Dune/Megalos-Ceramic/per_sito/ambienti/z_Megalos%20Ceramic-Dune-12.jpg';
        $images[] = 'https://tile.expert/img_lb/Dune/Megalos-Ceramic/per_sito/ambienti/z_Megalos%20Ceramic-Dune-37.jpg';
        $images[] = 'https://tile.expert/img_lb/Dune/Megalos-Ceramic/per_sito/ambienti/z_Megalos%20Ceramic-Dune-33.jpg';
        $images[] = 'https://tile.expert/img_lb/Dune/Megalos-Ceramic/per_sito/ambienti/z_Megalos%20Ceramic-Dune-24.jpg';
        $images[] = 'https://tile.expert/img_lb/Dune/Megalos-Ceramic/per_sito/ambienti/z_Megalos%20Ceramic-Dune-4.jpg';
        $images[] = 'https://tile.expert/img_lb/Dune/Megalos-Ceramic/per_sito/ambienti/z_Megalos%20Ceramic-Dune-8.jpg';
        $images[] = 'https://tile.expert/img_lb/Dune/Megalos-Ceramic/per_sito/ambienti/z_Megalos%20Ceramic-Dune-48.jpg';

        return $this->render('index', new Container(['images' => $images]));
    }

    public function actionImage()
    {
        echo file_get_contents($_GET['src']);
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
