<?php
    class Common extends F3instance{
        private $db;

        public static function film_block($films){
        
            foreach($films as $f){?>
            <div class="news-item hover-more" title="">
                <div class="news-item-content" title="">
                    <table>
                        <tbody><tr>
                                <td width="160">
                                    <?
                                        $poster = is_file($_SERVER['DOCUMENT_ROOT'].$f['poster'].'-poster-150.jpg') ?$f['poster'].'-poster-150.jpg' : $f['poster'].'-poster.jpg';
                                    ?>
                                    <img alt="<?=$f['aka_ru']?>" src="<?=$poster?>" width="150" >
                                </td>
                                <td>
                                    <div class="news-item-title" title="">

                                        <h2><a href="/film/<?=$f['id']?>-<?=$f['aka_trans']?>.html" title="<?=$f['aka_ru']?>"><?=$f['aka_ru']?></a></h2> 
                                        <a class="orange" href="/film/<?=$f['id']?>-<?=$f['aka_trans']?>.html" title="<?=$f['aka_ru']?>"><?=$f['aka_trans']?></a> <br>
                                        <h2><a href="/film/<?=$f['id']?>-<?=$f['aka_trans']?>.html" title="<?=$f['aka_ru']?>"><?=$f['aka_en']?></a></h2>
                                        <div class="news-item-title-rating" title=""> 
                                            <div id="ratig-layer-1" title="">
                                                <div style="float: left;" class="rating" title="">
                                                    <ul class="unit-rating">
                                                        <li style="width: 0px;" class="current-rating">0</li>
                                                        <li><a onclick="dleRate('1', '1'); return false;" class="r1-unit" title="Плохо" href="#">1</a></li>
                                                        <li><a onclick="dleRate('2', '1'); return false;" class="r2-unit" title="Приемлемо" href="#">2</a></li>
                                                        <li><a onclick="dleRate('3', '1'); return false;" class="r3-unit" title="Средне" href="#">3</a></li>
                                                        <li><a onclick="dleRate('4', '1'); return false;" class="r4-unit" title="Хорошо" href="#">4</a></li>
                                                        <li><a onclick="dleRate('5', '1'); return false;" class="r5-unit" title="Отлично" href="#">5</a></li>
                                                    </ul>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>

                                    <div class="news-item-category" title="">
                                        Релиз - <i> <?=$f['reliz']?></i>
                                    </div>
                                    <div class="news-item-category" title="">
                                        Длительнось - <i> <?=$f['past']?></i>
                                    </div>

                                    <div class="news-item-category" title="">
                                        Жанр - <i>
                                            <?foreach($f['genres']as $g){?>
                                                <a class="orange" href="/<?=$g['aka_en']?>.html" title="<?=$g['aka_ru']?>"><?=$g['aka_ru']?></a>
                                                <?}?>
                                        </i>
                                    </div>

                                    <?=$f['descr']?>

                                    <div class="news-item-more" title=""><a href="/film/<?=$f['id']?>-<?=$f['aka_trans']?>.html" title="<?=$f['aka_ru']?>"></a></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div> 

            <?}?>
        <div style="clear: both;"></div>
        <?
        }
    }
?>
