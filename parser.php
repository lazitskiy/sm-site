<?php
date_default_timezone_set('Asia/Irkutsk');
DEFINE('__ERROR_REPORTING__', "E_ALL");
DEFINE('__DISPLAY_ERRORS__', 'on');

/*$name = iconv('UTF-8', 'WINDOWS-1251', 'National Geographic:Чудища морей: Доисторическое Приключение - National Geographic:Sea Monsters: A Prehistoric Adventure (2007) BDRip');
$h = fopen(($_SERVER['DOCUMENT_ROOT']. '/download/torrent/53335/'.$name.'.torrent'),'w');
fwrite($h,'выафывафыва');
fclose($h);
die('sd');*/
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

$app = require_once 'lib/base.php';
require 'lib/db.php';


$app->config('app/config/config.cfg');
$app->config('app/config/routes.cfg');

$app->set('DB', new DB($app->get('DSN'), $app->get('us'), $app->get('pwd')));

$privoders = [
    'fasttorrent'
];


if ($argv[1]) {
    $provider = $argv[1];

    if (in_array($provider, $privoders)) {
        $provider_low = $provider;
        $provider = ucfirst($provider);

        $all = $argv[2];
        if ($argv[3]) {
            $app->set('param', $argv[3]);
        }


        $app->route('GET parser.php' . $provider_low, $provider . '->parse' . $all);

    }

    $app->run();
} else {
    echo "\n\n You should use console.\n Example -  php parser.php fasttorrent links \n\n Available providers: fasttorrent";
}





?>                                                              
