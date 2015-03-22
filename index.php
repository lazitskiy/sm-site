<?php
    //date_default_timezone_set('Asia/Irkutsk');
ini_set('display_errors','on');
    //DEFINE('__ERROR_REPORTING__',"E_ALL & ~E_USER_WARNING & ~E_USER_ERROR");
    DEFINE('__ERROR_REPORTING__',"E_ALL");
    DEFINE('__DISPLAY_ERRORS__','on');
    

    function vvtr($data){echo '<pre>';print_r($data);echo '</pre>';}
    function vvd($data){echo '<pre>';print_r($data);echo '</pre>';exit();}
    function hl($str){return str_replace('.html','',$str);}

    $app = require_once 'lib/base.php';
    require_once 'lib/geo.php';
    require_once 'lib/paginator.php';


    $app->config('conf/config.cfg');
    $app->config('conf/routes.cfg');


    $app->set('DB',new DB( $app->get('DSN'),$app->get('us'),$app->get('pwd') ));

    //$app->route('GET /@controller/@action','{{@PARAMS.controller}}->{{@PARAMS.action}}');
    
    $app->run();
    
?>                                                              
