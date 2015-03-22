<?php
    $params = $this->get('params');
    //    /vvtr($params);
    $film = $params['film'][0];    
    $torrents = $params['film']['torrents'];
    $genres = $params['film']['genres'];
    $companies = $params['film']['companies'];
    $channels = $params['film']['channels'];
    $peoples = $params['film']['peoples'];



?>        
<div >
    <div class="title-left-top" title="">
        <div class="title-right-top" title="">
            <div class="title-right-bottom" title="">
                <h1><?=$film['aka_ru']?></h1>
            </div>
        </div>
    </div>

    <div class="main-news-category" title="">
        <b>Категория:</b> <a href="/<?=$params['cat_alias']?>.html"><?=$params['cat_alias']?></a>. <b>Дата выхода:</b><?=$film['reliz']?>. <b>Продолжительность:</b><?=$film['past']?>
        <div id="kinipoisk" title="">
            <div class="kinorating" title="">
                <?if($film['rating_kinopoisk']){?>
                    <div class="kp-rating1">
                        <span>Fix</span>
                    </div>
                    <?}?>
                <div class="kp-rating2">
                    <span>Fix</span>
                </div>
            </div>

        </div>
    </div>

    <div>

        <table>
            <tr>
                <td>
                    <div style="width: 479px;">
                        <img  alt="" src="/images/film-<?=$film['id']?>/img-poster.jpg" title="">
                        <br /><br />
                    </div>
                    <div class="gall">
                        <?        
                            $images = $params['film'];
                            foreach($images as $k=>$v){
                                if($v['id']){?>
                                <a href="/images/film-<?=$film['id']?>/img-<?=$k+1?>.jpg" title="<?=$film['aka_ru'].' фото '.($k+1)?>"><img src="/images/film-<?=$film['id']?>/img-<?=$k+1?>-157.jpg" alt=""></a>
                                <?}
                            }
                        ?>
                    </div>
                </td>
                <td style="padding-left: 9px;">
                    <b>Описание:</b><br />
                    <?=$film['descr']?>
                    <br /><br />
                    <?if($genres){?>
                        <b>Жанр:</b><br />
                        <?foreach($genres as $g){?>
                            <a class="orange" href="/<?=$g['aka_en']?>.html" title="<?=$g['aka_ru']?>"><?=$g['aka_ru']?></a>
                            <?}?>
                        <br /><br />
                        <?}?>

                    <?if($peoples[0]){?>
                        <b>В ролях:</b><br />
                        <?foreach($peoples[0] as $role){?>
                            <a class="orange" href="/people/<?=$role['aka_en']?>.html" title="<?=$role['aka_ru']?>" ><?=$role['aka_ru']?></a>
                            <?}?>
                        <br /><br />
                        <?}?>

                    <?if($peoples[1]){?>
                        <b>Режиссер:</b><br />
                        <?foreach($peoples[1] as $role){?>
                            <a class="orange" href="/people/<?=$role['aka_en']?>.html" title="<?=$role['aka_ru']?>" ><?=$role['aka_ru']?></a>
                            <?}?>
                        <br /><br />
                        <?}?>

                    <?if($peoples[2]){?>
                        <b>Продюссер:</b><br />
                        <?foreach($peoples[2] as $role){?>
                            <a class="orange" href="/people/<?=$role['aka_en']?>.html" title="<?=$role['aka_ru']?>" ><?=$role['aka_ru']?></a>
                            <?}?>
                        <br /><br />
                        <?}?>

                    <?if($channels){?>
                        <b>Канал:</b><br />
                        <?foreach($channels as $c){?>
                            <a class="orange" href="<?=$c['ft_href']?>.html" title="<?=$c['aka']?>"><?=$c['aka']?></a>
                            <?}?>
                        <br /><br />
                        <?}?>

                    <?if($companies){?>
                        <b>Компания:</b><br />
                        <?foreach($companies as $co){?>
                            <a class="orange" href="/<?=$co['ft_href']?>.html" title="<?=$co['aka']?>"><?=$co['aka']?></a>
                            <?}?>
                        <br /><br />
                        <?}?>



                    <!--Торренты-->
                    <div class="title-left-top" title="">
                        <div class="title-right-top" title="">
                            <div class="title-right-bottom" title="">
                                <h1><?=$film['aka_ru']?> скачать торрент</h1>
                            </div>
                        </div>
                    </div>

                    <table class="table">
                        <tr class="pink">
                            <td>Качество</td>
                            <td>Перевод</td>
                            <td>Размер</td>
                            <td>Добавлено</td>
                            <td>Скачки</td>
                            <td>Сиды</td>
                            <td>Пиры</td>
                        </tr>

                        <?foreach($torrents as $tor){?>
                            <tr class="pad">
                                <td><a href="javascript:#" title="<?=$tor['tor']['qd']?>"><?=$tor['tor']['quality']?>
                                    <a class="ima_link" href="javascript:#" title="<?=$film['aka_ru']?> торрент скачать" film_id="<?=$film['id']?>" id="<?=$tor['tor']['id']?>"> Скачать</a>
                                </td>
                                <td><?=$tor['tor']['perevod']?></td>
                                <td><b><?=$tor['tor']['size']?></b></td>
                                <td><?=$tor['tor']['date_add']?></td>
                                <td><span id="down_<?=$tor['tor']['id']?>"><?=$tor['tor']['downloads']?></span></td>
                                <td><?=$tor['tor']['seaders']?></td>
                                <td><?=$tor['tor']['leachers']?></td>

                            </tr>
                            <tr>
                                <td colspan="7">
                                    <div style="display: none;" id="cont_<?=$tor['tor']['id']?>">
                                        <div id="img_<?=$tor['tor']['id']?>"></div>
                                        <br />
                                        torrent: <a class="pink" title="<?=$film['aka_ru']?> скачать торрент" href="/download/torrent/film-<?=$film['id']?>/<?=$tor['tor']['id']?><?=$tor['tor']['aka']?>"><?=$film['aka_ru']?></a>
                                        <br /><br />
                                    </div>
                                </td>
                            </tr>
                            <?}?>
                    </table>

                </td>
            </tr>
        </table>

        <div style="clear: both;"></div>
    </div>

    <script>
        $(document).ready(function(){
            $('.gall a').lightBox({
                imageLoading: '/images/lightbox-ico-loading.gif',
                imageBtnPrev: '/images/lightbox-btn-prev.gif',
                imageBtnNext: '/images/lightbox-btn-next.gif',
                imageBtnClose: '/images/lightbox-btn-close.gif',
                imageBlank: '/images/lightbox-blank.gif',
            });

            $('.ima_link').click(function(){
                var tid = $(this).attr('id');

                if($('#cont_'+tid).css('display')=='none'){
                    if( $('#img_'+tid).html().length>20 ){ 
                        $('#cont_'+tid).css('display','block');
                    }else{                   
                        $.ajax({
                            url: "/ajax/get/torrent/images/"+tid+'/'+$(this).attr('film_id')+'/',
                            context: document.body,
                            success: function(data){ 
                                $('#down_'+tid).html( parseInt($('#down_'+tid).html())+1 );
                                $('#cont_'+tid).fadeIn('slow');
                                $('#img_'+tid).html(data);  
                                $('#img_'+tid+' a').lightBox({
                                    imageLoading: '/images/lightbox-ico-loading.gif',
                                    imageBtnPrev: '/images/lightbox-btn-prev.gif',
                                    imageBtnNext: '/images/lightbox-btn-next.gif',
                                    imageBtnClose: '/images/lightbox-btn-close.gif',
                                    imageBlank: '/images/lightbox-blank.gif',
                                });
                            }
                        });
                    }
                }else{
                    $('#cont_'+tid).css('display','none');
                }
            })
        })
    </script>


    <div class="main-news-more" title="">
        <div id="ratig-layer" title="">
            <div id="ratig-layer" title="">
                <div style="float: left;" class="rating" title="">
                    <ul class="unit-rating">
                        <li style="width: 0px;" class="current-rating">0</li>
                        <li><a onclick="doRate('1', '1'); return false;" class="r1-unit" title="Плохо" href="#">1</a></li>
                        <li><a onclick="doRate('2', '1'); return false;" class="r2-unit" title="Приемлемо" href="#">2</a></li>
                        <li><a onclick="doRate('3', '1'); return false;" class="r3-unit" title="Средне" href="#">3</a></li>
                        <li><a onclick="doRate('4', '1'); return false;" class="r4-unit" title="Хорошо" href="#">4</a></li>
                        <li><a onclick="doRate('5', '1'); return false;" class="r5-unit" title="Отлично" href="#">5</a></li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

    <script type="text/javascript" src="/media/lightbox/lb.js"></script>  
    <link rel="stylesheet" type="text/css" href="/media/lightbox/lb.css" media="all">
</div>