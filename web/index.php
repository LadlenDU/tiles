<?php

use app\core\Web;
use app\core\Config;
use app\core\Csrf;
use app\core\Router;
use app\helpers\Helper;

if (version_compare(phpversion(), '5.4.0', '<') == true)
{
    die('Please use version of PHP not less than 5.4.');
}

ini_set('log_errors', 1);

define('WEB_DIR', __DIR__ . '/');
define('APP_DIR', realpath(__DIR__ . '/..') . '/');

$loader = require_once APP_DIR . 'vendor/autoload.php';

if (Config::inst()->debug)
{
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
else
{
    error_reporting(0);
    ini_set('display_errors', 0);
}

assert(
    Config::inst()->image['max_file_size'] <= Helper::shorthandNotationToBytes(ini_get('upload_max_filesize')),
    'Uploaded image size is bigger than php.ini upload_max_filesize.'
);

try
{
    Router::inst()->run();
}
catch (\Exception $e)
{
    if (Config::inst()->debug)
    {
        $displayMsg = Helper::log($e, 'error');
    }
    else
    {
        $msg = sprintf(_('Server error, code %s. Please contact administrator.'), $e->getCode());
        $displayMsg = Helper::log($msg, 'error');
    }

    if (Web::ifAjax())
    {
        Web::sendJsonResponse('error', $displayMsg);
    }
    else
    {
        Web::sendTextResponse($displayMsg);
    }
}
