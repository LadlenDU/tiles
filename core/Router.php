<?php

namespace app\core;

use app\core\Web;
use app\core\Config;
use app\core\Singleton;


/**
 * Router вызывает метод контроллера анализируя путь URL, который указывается в параметре $_GET['route']
 * - это путь к контроллеру, и параметр $_GET['action'] - это название дейтсвия.
 *
 * @package app\core
 */
class Router extends Singleton
{
    /** @var string Название класса контроллера. */
    protected $class;

    /** @var string Название фукнции контроллера. */
    protected $action;

    protected function __construct()
    {
        $this->prepare();
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getAction()
    {
        return $this->action;
    }

    /**
     * Вычисляет название контроллера и функции по URL.
     */
    protected function prepare()
    {
        $class = '\\app\\controllers\\Controller';
        $action = 'action404';

        $path = 'controllers';

        if (!empty($_GET['route']))
        {
            $route = strtolower(trim($_GET['route'], '/\\'));
            $path .= '/' . $route;
        }

        $path .= '/Controller';

        $file = Config::inst()->appDir . $path . '.php';
        if (file_exists($file))
        {
            require($file);
            $classTest = '\\app\\' . str_replace('/', '\\', $path);
            if (class_exists($classTest, false))
            {
                $action = !empty($_GET['action']) ? $_GET['action'] : 'index';
                $action = 'action' . ucfirst($action);
                if (is_callable([$classTest, $action]))
                {
                    $class = $classTest;
                }
                else
                {
                    $class = '\\app\\controllers\\Controller';
                    $action = 'actionNoAction';
                }
            }
        }

        $this->class = $class;
        $this->action = $action;
    }

    /**
     * Вызывает действие, генерирующее страницу, и выводит страницу.
     */
    public function run()
    {
        $controller = new $this->class;
        if (is_callable([$controller, 'beforeAction']))
        {
            $controller->beforeAction();
        }
        echo $controller->{$this->action}();
    }
}