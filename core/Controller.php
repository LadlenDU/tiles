<?php

namespace app\core;

use app\core\View;
use app\core\Container;
use app\core\Config;

/**
 * Controller - базовый класс для контроллеров.
 *
 * @package app\base
 */
abstract class Controller
{
    /** @var string макет используемый при генерации, можно переопределить в производном классе. */
    protected $layout = 'main.php';

    /** @var View используется для генерации кода */
    protected $view;

    /**
     * Действие по умолчанию.
     */
    abstract function actionIndex();

    public function __construct()
    {
        $this->view = new View;
    }

    /**
     * Генерация содержимого документа и вызов генерации всего документа.
     *
     * @param View $view Вид для генерации.
     * @param Container|null $params Параметры.
     * @return string Сгенерированный документ.
     */
    protected function render($view, Container $params = null)
    {
        $file = Config::inst()->appDir . 'views/content/' . $view . '.php';
        $content = $this->view->render($file, $params);
        return $this->renderLayout($content);
    }

    /**
     * Генерация всего документа.
     *
     * @param string $content Основное содержимое документа, подставляемое в шаблон.
     * @return string Сгенерированный документ.
     */
    protected function renderLayout($content)
    {
        $file = Config::inst()->appDir . 'views/layouts/' . $this->layout;
        return $this->view->renderLayout($file, $content);
    }

    public function beforeAction()
    {
        return true;
    }
}
