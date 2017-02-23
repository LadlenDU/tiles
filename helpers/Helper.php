<?php

namespace app\helpers;

use app\core\Container;
use app\core\Config;

/**
 * Helper содержит общие вспомогательные функции.
 *
 * @package app\helpers
 */
class Helper
{
    /**
     * Запись логов.
     *
     * @param string|\Exception $message Сообщение или объект исключения.
     * @param string $type Тип сообщения (по этому типу из настроек выясняется нужно ли записывать лог).
     * @return string Текст, подготовленный для сохранения в лог.
     */
    public static function log($message, $type = 'undefined')
    {
        $errorStr = '';

        if (is_string($message))
        {
            $errorStr .= $message;
        }
        elseif ($message instanceof \Exception)
        {
            $errorStr .= sprintf(
                _("Error occured.\nCode: %s.\nMessage: %s.\nFile: %s.\nLine: %s.\nTrace: %s\n"),
                $message->getCode(),
                $message->getMessage(),
                $message->getFile(),
                $message->getLine(),
                $message->getTraceAsString()
            );
        }
        else
        {
            $errorStr .= _('An unrecognized error occured in the error log function.');
        }

        if (in_array('all', Config::inst()->log['types'])
            ||
            in_array($type, Config::inst()->log['types'])
        )
        {
            $msg = date(DATE_RFC822) . ":\n" . $errorStr . "\n-------------------------------------------\n\n";
            $dir = Config::inst()->appDir . "runtime/logs";
            if (!is_dir($dir))
            {
                mkdir($dir, 0755, true);
            }
            error_log($msg, 3, "$dir/$type.log");
        }

        return $errorStr;
    }

    /**
     * Пропорционально уменьшает размеры прямоугольника $size чтобы он вписывался в прямоугольник $maxSize (если надо).
     * Используетя для вычисления размеров миниатюр изображений.
     *
     * @param array $maxSize Максимальный размер прямоугольника.
     * @param array $size Текущий размер прямоугольника.
     * @return array Новые размеры прямоугольника.
     */
    public static function minifyRectangle($maxSize, $size)
    {
        $ret = $size;

        $scale = min($maxSize['width'] / $size['width'], $maxSize['height'] / $size['height']);

        if ($scale < 1)
        {
            $ret['width'] = ceil($scale * $size['width']);
            $ret['height'] = ceil($scale * $size['height']);
        }

        return $ret;
    }

    /**
     * Добавить к одной строке другую и перевести.
     *
     * @param string $str Увеличиваемая строка.
     * @param string $add Добавляемая строка.
     * @return string Результирующая переведенная строка.
     */
    public static function addStrToStr($str, $add = ':')
    {
        return _($str . $add);
    }

    /**
     * Конвертация в байты записи размера в краткой нотации (например значения upload_max_filesize из php.ini).
     *
     * @param string $val Строка, потенциально содержащая краткую нотацию.
     * @return int Значение в байтах
     */
    public static function shorthandNotationToBytes($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val) - 1]);
        switch ($last)
        {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return (int)$val;
    }

    /**
     * Установить значение выбранного элемента меню.
     *
     * @param Container $values Значения для вида.
     * @param string $menu Условное название меню в виде.
     */
    public static function setSelectedMenu(Container &$values, $menu)
    {
        if (!$values->c('selectedMenu'))
        {
            $values->selectedMenu = new Container;
        }
        $values->selectedMenu->{$menu} = true;
    }
}