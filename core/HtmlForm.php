<?php

namespace app\core;

use app\helpers\Html;

/**
 * HtmlForm содержит функции для формирования элементов формы.
 *
 * @package app\helpers
 */
class HtmlForm
{
    /** @var \app\core\Container Значения, передаваемые в форму. */
    protected $values;

    /**
     * Имя элемента формы, который используется для хранения URL для редиректа после сабмита формы.
     * Может использоваться, например, при логине для возвращения на текущую страницу.
     *
     * @var string
     */
    const REDIRECT_URL_NAME = 'redirectUrl';

    public function __construct(\app\core\ContainerHSC $values)
    {
        $this->values = $values;
    }

    /**
     * @return ContainerHSC
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Возвращает абсолютный путь к шаблону поля input.
     *
     * @return string
     */
    public static function getInputTemplatePath()
    {
        return Config::inst()->appDir . 'views/content/user/_input_text.php';
    }

    /**
     * Возвращает абсолютный путь к шаблону изображения пользователя.
     *
     * @return string
     */
    public static function getUserImageTemplatePath()
    {
        return Config::inst()->appDir . 'views/content/user/_image.php';
    }

    /**
     * Генерирует параметры для валидатора для <input> поля.
     *
     * @param string $name Имя поля.
     * @param array $validators Валидаторы, применяемые к элементу.
     * @return string
     */
    public function validatorParams($name, $validators)
    {
        // Параметры <input>, вычисляемые на основе передаваемых значений
        // и предназначенные для валидатора.
        $validatorParams = '';

        $conf = Config::inst()->validator_messages;

        $label = $this->values->formLabels->c($name);

        foreach ($validators as $vName => $vVal)
        {
            switch ($vName)
            {
                case 'notEmpty':
                {
                    $validatorParams .= ' required data-v-notEmpty-text="' . Html::_hs(
                            $conf['notEmpty']['err'],
                            $label,
                            false
                        ) . '" ';
                }
                    break;
                case 'lengthNotLess':
                {
                    $vVal = (int)$vVal;
                    $validatorParams .= ' data-v-lengthNotLess-text="' . Html::_nhs(
                            $conf['lengthNotLess']['err_n']['s'],
                            $conf['lengthNotLess']['err_n']['p'],
                            $vVal,
                            [$label, $vVal],
                            false
                        ) . '" ';
                }
                    break;
                case 'equalStrings':
                {
                    $labelCompare = $this->values->formLabels->c($vVal);
                    $validatorParams .= ' data-v-equalStrings-text="' . Html::_hs(
                            $conf['equalStrings']['err'],
                            [$labelCompare, $label],
                            false
                        ) . '" ';
                }
                    break;
                case 'email':
                {
                    $validatorParams .= ' data-v-email-text="' . Html::_h($conf['email']['err'], false) . '" ';
                }
                    break;
                case 'phone':
                {
                    $validatorParams .= ' data-v-phone-text="' . Html::_h($conf['phone']['err'], false) . '" '
                        . 'data-v-phone-request-text="' . Html::_h(
                            $conf['phone']['err_server'],
                            false
                        ) . '"';
                }
                    break;
                default:
                    break;
            }
        }

        // в $params конструктора - поместить: type, placeholder, id
        return $validatorParams;
    }

    /**
     * Вывести класс ошибки если надо для элемента.
     *
     * @param string $name Название элемента.
     */
    public function errorClass($name)
    {
        echo $this->values->saveErrors->c($name)['last_error'] ? ' has-error ' : '';
    }

    /**
     * Вывести если надо сообщение описывающее ошибку для элемента.
     *
     * @param string $name Название элемента.
     */
    public function errorMsg($name)
    {
        Html::h($this->values->saveErrors->c($name)['last_error']);
    }

    /**
     * Вывести параметр максимальной длины если нужно.
     *
     * @param string $name Название элемента.
     */
    public function maxlengthParam($name)
    {
        $maxlength = $this->values->fieldMaxSizes->c($name);
        if ($maxlength)
        {
            echo " maxlength='$maxlength' ";
        }
    }

    /**
     * Создать элемент input для формы со значением от CSRF.
     *
     * @param bool|true $show Выводить ли результат.
     * @return string|void
     */
    public static function inputCSRF($show = true)
    {
        $s = '<input type="hidden" name="' . Html::h(Csrf::inst()->getCsrfTokenName(), false)
            . '" value="' . Html::h(Csrf::inst()->getCsrfToken(), false) . '">' . "\n";

        if ($show)
        {
            echo $s;
        }
        else
        {
            return $s;
        }
    }

    /**
     * Создать элемент input для формы со значением текущего URL.
     *
     * @param \app\core\View $view Может содержать в своих значениях URL, которое надо подставить вместо текущего URL.
     * @param bool|true $show Выводить ли результат.
     * @return string|void
     */
    public static function inputRedirectUrl(View $view = null, $show = true)
    {
        $redirectUrl = ($view && $view->values->c(self::REDIRECT_URL_NAME))
            ? $view->values->c(self::REDIRECT_URL_NAME)
            : $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $s = '<input type="hidden" name="' . self::REDIRECT_URL_NAME . '" value="' . Html::h($redirectUrl, false) . '">' . "\n";

        if ($show)
        {
            echo $s;
        }
        else
        {
            return $s;
        }
    }
}