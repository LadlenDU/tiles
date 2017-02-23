<?php

namespace app\core;

#use app\core\Singleton;

/**
 * Config служит для работы с конфигурацией.
 *
 * @package app\core
 */
class Config extends Singleton
{
    protected static $config;

    public function __get($name)
    {
        return self::$config[$name];
    }

    /**
     * Возвращает данные конфигурации.
     *
     * @return array Массив с данными конфигурации.
     */
    public static function inst()
    {
        if (!self::$config)
        {
            self::$config = (php_sapi_name() == 'cli')
                ? require_once(APP_DIR . 'config/console.php')
                : require_once(APP_DIR . 'config/web.php');
        }

        return parent::inst();
    }
}

