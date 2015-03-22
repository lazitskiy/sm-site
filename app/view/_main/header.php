<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?=$this->get("title");?></title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="robots" content="all">
    <meta name="revisit-after" content="1 days">

    <link rel="alternate" type="application/rss+xml" title="DataLife Engine Demo" href="/rss.xml">
    <script type="text/javascript" src="/media/js/jquery.js"></script>
    <script type="text/javascript" src="/media/js/easytooltip.js"></script>  
    <script type="text/javascript" src="/media/js/active.js"></script>  

    <link rel="stylesheet" type="text/css" href="/media/css/style.css" media="all">
</head>
<body>

<div id="loading-layer" style="display: none;"><div id="loading-layer-text">Загрузка. Пожалуйста, подождите...</div></div>

<div title="" class="main-center-block">
<div title="" class="top-block">
    <a href="/" class="logo" title="Скачать новые фильмы бесплатно"></a>
    <div title="" class="top-menu">
        <a title="" href="/" class="top-menu-link"><span>Главная</span></a>
        <!--<a href="#" class="top-menu-link"><span>Скоро в кино</span></a>
        <a href="#" class="top-menu-link"><span>Рецензии</span></a>
        <a title="" href="#" class="top-menu-link"><span>RSS 2.0</span></a>-->
        <a href="#" class="top-menu-link"><span>Контакты</span></a>
        <div style="clear: both;"></div>
    </div>
</div>
<div title="" class="banner">
    <img title="" src="/images/banner.png" alt="">
</div>
<div title="" class="search-block">   
    <?
        $q=$this->get('PARAMS.0');
        if(strpos($q,'search/?')){
            $q = preg_replace('#(.+\?)(.+)(\.html)#','$2',$q);
        }else{$q='';}

    ?>
    <form method="get" action="/search/">
        <input autocomplete="off" name="q" class="form-text" id="story" value="<?=$q ? $q :'Поиск фильма по названию ...'?>" onblur="if(this.value=='') this.value='Поиск фильма по названию ...';" onfocus="if(this.value=='Поиск фильма по названию ...') this.value='';" title="Поиск фильма по названию ..." type="text">
        <input src="/images/poisk.png" value="Найти!" id="quu" class="poisk" onclick="javascript:return false;" alt="Найти!" type="image" border="0">
    </form>
    <script>
        $(document).ready(function(){
            $('#quu').click(function(){
                location.href='/search/?'+$('#story').attr('value')+'.html';
            })
        })

    </script>

    <a href="#" class="advansed-search" title="Если обычный не подходит">Расширенный поиск</a>
    <div class="enter-to-site" title="Авторизация на сайте">Вход на сайт</div>

    <!--<div title="" class="google-links">
        <img title="" src="/images/banner3.png" alt="">
    </div>-->
            </div>
            <div title="" style="height: 7px; clear: both;"></div>
            <div class="content">
           