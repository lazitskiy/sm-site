<?php
//date_default_timezone_set('Asia/Irkutsk');
ini_set('display_errors', 'on');
//DEFINE('__ERROR_REPORTING__',"E_ALL & ~E_USER_WARNING & ~E_USER_ERROR");
DEFINE('__ERROR_REPORTING__', "E_ALL");
DEFINE('__DISPLAY_ERRORS__', 'on');


function vvtr($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

function vvd($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    exit();
}

function hl($str)
{
    return str_replace('.html', '', $str);
}

$app = require_once 'lib/base.php';
//$app_cache = require_once 'lib/base.3.4.0.php';
require_once 'lib/paginator.php';


$languages = [
    'ru-RU' => 'ru-RU'
];
$browser_language = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];

if ($languages[$browser_language]) {
    $lang = $browser_language;
} else {
    $lang = 'en-GB';
    $lang = 'ru-RU';
}
$lang = 'ru-RU';
$lang = require_once 'app/language/' . $lang . '.php';
$app->set('_', $lang);


$app->config('app/config/config.cfg');
$app->config('app/config/routes.cfg');


$app->set('DB', new DB($app->get('DSN'), $app->get('us'), $app->get('pwd')));
//$app->route('GET /@controller/@action','{{@PARAMS.controller}}->{{@PARAMS.action}}');

$app->run();

?>                                                              
