<?php
    include 'lib/yandex.php';
    //header('Content-Type: image/png');
    class Ajax extends F3instance {   

        function yandex_map(){
            $lpu_id = f3::get('PARAMS.lpu_id');
            $lan = f3::get('PARAMS.lan');
            $lot = f3::get('PARAMS.lot');
            $key = $this->get('key_ymaps');





            if( !is_file($path.$lpu_id.'.png') ){
                $map = Yandex::staticmap(
                $lan.','.$lot,
                16,'map',
                '650,450',
                $key,
                $lan.','.$lot.',pmgnl'
                );
                file_put_contents($path.$lpu_id.'.png', $map); 
            }
            echo '/temp/maps/'.$lpu_id.'.png';
        }

        function tor_images(){
            $torrent_id = f3::get('PARAMS.tor_id');
            $film_id = f3::get('PARAMS.film_id');
            $torrents = DB::sql('SELECT i.path img_path, i.aka img_aka, i.torrent_id, i.id
            FROM torrent_images as i 
            WHERE i.torrent_id='.$torrent_id);
        ?>
        <div>
            <?
                DB::sql('UPDATE torrents SET downloads=downloads+1 WHERE id='.$torrent_id);
                $k=1;
                foreach($torrents as $i ){?>
                <a href="/images/film-<?=$film_id?>/<?=$i['torrent_id']?>/img-<?=$i['id']?>.jpg" title="<?=$film['aka_ru']?> фото <?=$k?>">
                    <img src="/images/film-<?=$film_id?>/<?=$i['torrent_id']?>/img-<?=$i['id']?>-157.jpg" style="border: 0;" width="157" alt=""></a>
                <?$k++;}?> 
        </div>  
        <?}
    }
?>
