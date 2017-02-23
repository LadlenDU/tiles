<?php

namespace app\helpers;

use app\core\Config;

class Image
{
    const THUMBNAIL_PREFIX = "_thumb";

    /**
     * Создает путь к thumbnail файлу заданного изображения.
     *
     * @param string $path путь к файлу
     * @return string
     */
    public static function getThumbName($path)
    {
        $thumbPath = '';

        $info = pathinfo($path);
        $thumbPath .= (isset($info['dirname']) && $info['dirname'] != '' && $info['dirname'] != '.')
            ? ($info['dirname'] . '/') : '';
        $thumbPath .= $info['filename'] . self::THUMBNAIL_PREFIX;
        $thumbPath .= isset($info['extension']) ? ('.' . $info['extension']) : '';

        return $thumbPath;
    }

    /**
     * Уменьшить изображение до его допустимо максимальных размеров и разместить в соответствующей директории.
     *
     * @param string $imagePath путь к оригинальному изображению
     * @param bool $thumb [optional] надо ли генерировать тумбнейл
     * @param bool $temporary [optional] надо ли генерировать файлы во временную директорию
     * @return array [[new][, new_thumb]] пути к сгенерированному изображению и тумбнейлу
     * @throws \Exception
     */
    /**
     * @param $imagePath
     * @param bool $thumb [optional]
     * @param bool $temporary [optional]
     * @return array
     * @throws \Exception
     */
    public static function reduceImageToMaxDimensions($imagePath, $thumb = true, $temporary = false)
    {
        $ret = [];

        $newPath = Config::inst()->webDir . 'images/comments/';
        $newPath .= $temporary ? 'images_temp' : 'images';
        $newPath .= '/' . str_replace('.', '', uniqid('images', true));

        $maxSize = Config::inst()->site['comments']['creation_settings']['image']['max_size'];
        if ($new = self::resizeImageReduce($imagePath, $newPath, $maxSize))
        {
            $ret['new'] = $new;
        }

        if ($thumb)
        {
            $newThumbPath = self::getThumbName($newPath);
            $maxThumbSize = Config::inst()->site['comments']['creation_settings']['image']['max_thumb_size'];
            if ($newThumb = self::resizeImageReduce($imagePath, $newThumbPath, $maxThumbSize))
            {
                $ret['new_thumb'] = $newThumb;
            }
        }

        return $ret;
    }

    /**
     * Уменьшить пропорционально изображение (если требуется).
     *
     * @param string $currentPath путь к оригинальному изображению
     * @param string $newPath путь к новому изображению (без расширения файла,
     * расширение добавляется автоматически к названию)
     * @param array $maxSize [] максимальный размер изображения
     * @param int $maxSize ['width'] Максимальная ширина.
     * @param int $maxSize ['height'] Максимальная высота.
     * @return bool|array false в случае неудачи, массив ['name', 'width', 'height'] в случае удачи
     * @throws \Exception
     */
    //TODO: описание
    public static function resizeImageReduce($currentPath, $newPath, $maxSize)
    {
        $ret = false;

        if ($size = getimagesize($currentPath))
        {
            if (!$extension = self::getMimeExtension($size['mime']))
            {
                throw new \Exception('MIME type is not supported: ' . $size['mime']);
            }

            $scale = min($maxSize['width'] / $size[0], $maxSize['height'] / $size[1]);

            if ($scale >= 1)
            {
                if (copy($currentPath, $newPath))
                {
                    $ret = ['width' => $size[0], 'height' => $size[1]];
                }
            }
            else
            {
                $width = ceil($scale * $size[0]);
                $height = ceil($scale * $size[1]);

                $src = imagecreatefromstring(file_get_contents($currentPath));
                $dst = imagecreatetruecolor($width, $height);

                imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
                imagedestroy($src);

                switch ($extension)
                {
                    case 'jpg':
                    {
                        $ret = imagejpeg($dst, $newPath);
                    }
                        break;
                    case 'gif':
                    {
                        $ret = imagegif($dst, $newPath);
                    }
                        break;
                    case 'png':
                    {
                        $ret = imagepng($dst, $newPath, 9, PNG_ALL_FILTERS);
                    }
                        break;
                }

                imagedestroy($dst);

                if ($ret)
                {
                    $ret = ['width' => $width, 'height' => $height];
                }
            }
        }

        return $ret;
    }

    /**
     * Вернуть расширение для файла по MIME типу.
     *
     * @param string $mime
     * @return bool|string расширения для файла или false если MIME тип не поддерживается
     */
    protected static function getMimeExtension($mime)
    {
        $ret = false;

        switch ($mime)
        {
            case 'image/jpeg':
            case 'image/pjpeg':
            {
                $ret = 'jpg';
            }
                break;
            case 'image/gif':
            {
                $ret = 'gif';
            }
                break;
            case 'image/png':
            case 'image/x-png':
            {
                $ret = 'png';
            }
                break;
            default:
                break;
        }

        return $ret;
    }

    public static function getExtension($file)
    {
        $ret = 'unknown';

        if ($type = exif_imagetype($file))
        {
            if ($ext = self::getMimeExtension(image_type_to_mime_type($type)))
            {
                $ret = $ext;
            }
        }

        return $ret;
    }

    /**
     * Проверка на соответствие изображения к комментарию заданным в настройках параметрам.
     *
     * @param string $file путь к файлу
     * @return array пустой массив в случае удачи, или содержит элемент ['error'] с описанием ошибки в случае ошибки
     */
    public static function validateCommentImage($file)
    {
        $ret = [];

        if ($file)
        {
            $size = getimagesize($file);
            if (!empty($size['mime']))
            {
                if (!in_array(
                    $size['mime'],
                    Config::inst()->site['comments']['creation_settings']['image']['types_allowed_mime']
                )
                )
                {
                    //$ret = true;
                    $ret['error'] = 'Не допустимый тип изображения: ' . $size['mime'];
                }
            }
            else
            {
                $ret['error'] = 'Не удалось определить тип изображения';
            }
        }
//        else
//        {
//            $ret = true;    // Отсутствие изображения не есть ошибка, т. к. опционно
//        }

        return $ret;
    }
}