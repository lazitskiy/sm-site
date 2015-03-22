<?
    $cats = $this->get('cats');
    $films = $this->get('films');
?>


<table class="main-table" cellpadding="0" cellspacing="0">
    <tbody><tr>
            <td class="td-for-content" valign="top">
               
                <?      
                    //Список фильмов
                    echo Common::film_block($films);
                ?>
            </td>

            <td class="td-for-blocks" valign="top">

                <div title="" class="anons-block">
                    <div class="top-films">
                        <a href="#" class="rss-link"></a>
                        <div style="padding: 104px 0pt 0pt 25px;">
                            <div class="top-films-links">
                                <ul>
                                    <li><a href="/main/1-post1.html">Добро пожаловать</a></li>

                                </ul>
                            </div>
                        </div>
                    </div>                 
                    <div title="" style="padding: 35px 0pt 0pt 80px;">

                        <div style="clear: both;"></div>
                    </div>
                </div>

                <div class="right-block cats">
                    <div class="right-block-title menu-title"></div>
                    <div class="right-block-content menu-link">
                        <?
                            foreach($cats as $cat){?>
                            <a href="/<?=$cat['aka_en']?>.html"><span><?=$cat['aka_ru']?></span></a>
                            <?}?>
                    </div>
                </div>

            </td>
        </tr>
    </tbody></table>