<?php

namespace app\controllers;

use app\core\Container;
use app\core\Config;

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

    public function actionLoadImages()
    {
        if (!empty($_FILES['file']['tmp_name']))
        {
            $validLinks = [];

            $content = file_get_contents($_FILES['file']['tmp_name']);
            $content = preg_split('/\r\n|\r|\n/', $content);
            $content = array_filter($content);
            foreach ($content as $str)
            {
                #$headers = @get_headers($str);
                #if (strpos($headers[0], '200') !== false)
                if (1)
                {
                    $validLinks[] = $str;
                }
            }

            header('Content-Type: application/json');
            die(json_encode($validLinks));
        }
    }

    public function actionImage()
    {
        if ($imgHandle = imagecreatefromstring(file_get_contents($_GET['src'])))
        {
            //$width = imagesx($imgHandle);
            //$height = imagesy($imgHandle);

            $textcolor = imagecolorallocatealpha($imgHandle, 255, 255, 255, 30);
            $font_file = Config::inst()->appDir . 'data/font.ttf';
            imagefttext($imgHandle, 40, 0, 100, 100, $textcolor, $font_file, 'Watermark');

            header('Content-type: image/jpeg');
            imagejpeg($imgHandle);

            imagedestroy($imgHandle);
        }
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
