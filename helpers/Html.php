<?php

namespace app\helpers;

use app\core\Config;

/**
 * Html содержит вспомогательные функции для работы с html.
 *
 * @package app\helpers
 */
class Html
{
    /**
     * Версия Html::hs(), но предварительно переводит строку.
     *
     * @param string $str Строка.
     * @param array $subs См. Html::hs().
     * @param bool|true $echo См. Html::hs().
     * @return string
     */
    public static function _hs($str, $subs = [], $echo = true)
    {
        $s = self::hs(_($str), $subs, $echo);
        return $s;
    }

    /**
     * Перевод строки + конвертация в специальные views символы.
     *
     * @param string $str Строка.
     * @param bool $echo [optional] См. Html::h().
     * @return string
     */
    public static function _h($str, $echo = true)
    {
        $s = self::h(_($str), $echo);
        return $s;
    }

    /**
     * То же что Html::h(), но предварительно заменяет в строке символы %s
     * на содержимое массива $subs функцией vsprintf().
     *
     * @param string $str Строка.
     * @param array $subs [optional] Скалярные знчения для подстановки в текст функцией vsprintf().
     * @param bool $echo См. Html::h().
     * @return string
     */
    public static function hs($str, $subs = [], $echo = true)
    {
        if ($subs)
        {
            $subs = (array)$subs;
            $str = vsprintf($str, $subs);
        }

        $s = self::h($str, $echo);
        return $s;
    }

    /**
     * Конвертация строки в специальные views символы.
     *
     * @param string $str Строка.
     * @param bool $echo [optional] Выводить ли строку.
     * @return string
     */
    public static function h($str, $echo = true)
    {
        $s = htmlspecialchars($str, ENT_QUOTES, Config::inst()->globalEncoding);
        if ($echo)
        {
            echo $s;
        }
        return $s;
    }

    /**
     * Версия Html::_hs() для сообщений во множественном числе.
     *
     * @param string $singular Текст в единственном числе.
     * @param string $plural Текст во множественном числе.
     * @param int $n Число, описываемое текстом.
     * @param array $subs [optional] @see Html::hs().
     * @param bool $echo [optional] @see Html::hs().
     * @return string
     */
    public static function _nhs($singular, $plural, $n, $subs = [], $echo = true)
    {
        $str = ngettext($singular, $plural, $n);
        $s = self::hs($str, $subs, $echo);
        return $s;
    }

    /**
     * Перевод строки + конвертация для вставки в JS код.
     *
     * @param string $str Строка.
     * @param bool $echo [optional] Выводить ли строку.
     * @return string
     */
    public static function _j($str, $echo = true)
    {
        return self::j(_($str), $echo);
    }

    /**
     * Конвертация строки для вставки в JS код.
     *
     * @param string $str Строка.
     * @param bool $echo [optional] Выводить ли строку.
     * @return string
     */
    public static function j($str, $echo = true)
    {
        $s = json_encode($str);
        if ($echo)
        {
            echo $s;
        }
        return $s;
    }

    /**
     * Создание заголовка <title>.
     *
     * @param string $part Вторая часть заголовка.
     * @param bool $forHtml Подготовить ли заголовок для вывода в html код.
     * @return string
     */
    public static function createTitle($part = '', $forHtml = true)
    {
        $leftPart = _(Config::inst()->site['name']);
        $rightPart = $part ?: '';

        if ($forHtml)
        {
            $leftPart = self::h($leftPart, false);
            $rightPart = self::h($rightPart, false);
        }

        return $rightPart ? ($leftPart . ' - ' . $rightPart) : $leftPart;
    }

    /**
     * Создать тег для добавления файла CSS в <head>.
     *
     * @param string $file Путь к файлу.
     */
    public static function createCssLink($file)
    {
        $lnk = '<link rel="stylesheet" href="' . self::mkLnk($file, false) . '">' . "\n";
        return $lnk;
    }

    /**
     * Создание тега для добавления файла JS.
     *
     * @param string $file Путь к файлу.
     */
    public static function createJsLink($file)
    {
        $lnk = '<script src="' . self::mkLnk($file, false) . '"></script>' . "\n";
        return $lnk;
    }

    /**
     * Формирует URL на основе абсолютного URL‐пути.
     * Первоначально просто выводит (возвращает, в зависимости от $show) $path, может корректироваться при помещении
     * сайта в не-корневую директорию, может быть добавлен явно хост и т. п.
     *
     * @param string $path Абсолютный URL‐путь (без хоста) относительно корня проекта, и всё за ним (параметры и прочее).
     * @param bool|true $show @see Html::h()
     * @return string
     */
    public static function mkLnk($path, $show = true)
    {
        return self::h($path, $show);
    }

    /**
     * Возвращает строку параметров для вставки в html тег.
     *
     * @param array $params список [параметр => значение[, ...]]
     * @return string
     */
    public static function mkHtmlTagParams($params = [])
    {
        $s = '';

        $params = (array)$params;
        foreach ($params as $pName => $pVal)
        {
            $s .= ' ' . self::h($pName, false) . '="' . self::h($pVal, false) . '" ';
        }

        return $s;
    }
}