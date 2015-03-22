<?php
    $params = $this->get('params');
    $paginator = $this->get('pagination'); 


    //Список фильмов
    echo Common::film_block($params['films']);

    if($paginator['last']>0){?>

    <div class="paginator">
        <? foreach($paginator['pages'] as $k =>$v){
                if($v==$paginator['current']){?>
                <span><?=$v?></span>
                <?}else{?>
                <a href="/<?=$params['cat_alias']?>/page-<?=$v?>.html"><?=$v?></a>  
                <?}
        }?>
    </div>
    <?}?>
