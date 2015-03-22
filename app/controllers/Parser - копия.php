<?php
    ini_set('memory_limit', '3200M');

    function myflush($timee) { 
        if(ob_get_contents()) 
        { 
            ob_flush(); 
            ob_clean(); 
            flush();
            //sleep(2);
        } 
    }           

    //phpinfo();
    class Parser extends F3instance {

        private $db;
        private $trans;
        private $space;

        function __construct() { 
            header ('Content-type: text/html; charset=utf-8');  
            set_time_limit(90000);
            ini_set('output_buffering',0); 
            set_time_limit(0); 
            ob_implicit_flush(1); 
            $this->set('title','Хуй пизда Джигурда');
            $this->db = $this->get('DB');

            $this->toimg = $_SERVER['DOCUMENT_ROOT'] = 'E:/Zsrv/Apache2/htdocs/sites/torrent';
            //$this->toimg = $_SERVER['DOCUMENT_ROOT'] = 'E:/Zsrv/Apache2/htdocs/torrent';
            //$this->toimg = $_SERVER['DOCUMENT_ROOT'] = '/var/www/teletorrent.ru/public/www';



            $this->trans = array(" "=>"-","а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d","е"=>"e", "ё"=>"yo","ж"=>"j","з"=>"z","и"=>"i","й"=>"i","к"=>"k","л"=>"l", "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r","с"=>"s","т"=>"t", "у"=>"y","ф"=>"f","х"=>"h","ц"=>"c","ч"=>"ch", "ш"=>"sh","щ"=>"sh","ы"=>"i","э"=>"e","ю"=>"u","я"=>"ya","А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D","Е"=>"E", "Ё"=>"Yo","Ж"=>"J","З"=>"Z","И"=>"I","Й"=>"I","К"=>"K", "Л"=>"L","М"=>"M","Н"=>"N","О"=>"O","П"=>"P", "Р"=>"R","С"=>"S","Т"=>"T","У"=>"Y","Ф"=>"F", "Х"=>"H","Ц"=>"C","Ч"=>"Ch","Ш"=>"Sh","Щ"=>"Sh", "Ы"=>"I","Э"=>"E","Ю"=>"U","Я"=>"Ya","ь"=>"","Ь"=>"","ъ"=>""
            ,"Ъ"=>"","/"=>"",":"=>"","|"=>"","---"=>"-","--"=>"-","'"=>"","\""=>"");
            $this->space = array(" "=>"-","/"=>"",":"=>"");
            // echo $this->render($this->get('_header'));
        }

        /**
        * Список ссылок на фильмы
        * 
        * @param mixed $results массив страниц по 15 фильмов
        */
        public function films_link_get($results){
            $film_main_pages=array();
            unset($film_main_pages);            
            foreach($results as $res){

                $films_page  = new nokogiri($res);    
                $films_list = $films_page->get('table.list tr[valign=top] td a[target=_blank]')->toArray();

                if(!$films_list[0]){
                    $temp_fi = $films_list;
                    unset($films_list);
                    $films_list[0]=$temp_fi;
                }

                foreach($films_list as $film){
                    //Ссылки на фильмы
                    echo $film['href']."\n";
                    myflush();
                    $film_main_pages[]='http://fast-torrent.ru'.$film['href'];
                }

            }
            return $film_main_pages;
        }

        /**
        * Массив торрента картинок
        * 
        * @param mixed $film_main  - ХТМЛ код страницы
        */
        public function torrents_film_get($film_main){
            $str = $film_main->get('div.ordering')->toXml();
            $hui = new nokogiri($str);
            $downloads = $hui->get('div.torrent-row')->toArray();
            //Костыль если один торрент фаил скачки
            if(!$downloads[0]){
                $temp=$downloads;
                unset($downloads);
                $downloads[0]=$temp;
            }

            unset($torrent);
            /*echo "   --START torrents&images\n"; 
            myflush();*/

            foreach($downloads as $d){
                $imgs = new nokogiri($d['code']);
                $images = $imgs->get('a')->toArray();
                if(!$images[0]){
                    $temp_ima = $images;
                    unset($images);
                    $images[0]=$temp_ima;
                }

                if($images){
                    $_folder_name = $d['obj'];
                    unset($torrent_images);
                    $k=1;
                    $path = '/images/'.$folder_name;

                    unset($arr_timages);
                    foreach($images as $img){
                        $url = $img['href'];
                        $filename = '/'.$folder_name.'-'.$d['obj'].'-'.$k.'.jpg';


                        /*echo "       --".$filename."--\n"; 
                        myflush();*/
                        $arr_timages[]=array(
                        'from'=>$url,
                        'to'=>$path,
                        'fname'=>'/'.$_folder_name.'/'.$folder_name.'-'.$d['obj'].'-'.$k.'.jpg'
                        );

                        $k++;                                             
                    }
                    $torrent_images=$arr_timages;

                }


                //Торрент фаил
                //$torrName = urldecode($d['table'][0]['tr'][0]['td'][6]['a']['href']);

                $torrName = urldecode(($d['table'][0]['tr'][0]['td'][6]['a']['href']));

                $url = 'http://fast-torrent.ru'.$torrName;
                $local = '/download/torrent/'.$folder_name.'/'.$d['obj'];
                $torentFileName = '/'.preg_replace('#[^A-Za-z0-9\/\-\.]#','',strtr( strtr(urldecode(preg_replace('#.*'.$d['obj'].'#','',$torrName)),$this->trans ),$this->trans) );



                $torrent[] = array(
                'id'=>$d['obj'],
                'quality'=>array(
                'id'=>$d['table'][0]['tr'][0]['td'][0]['div'][1],
                'img'=>array(
                'alt'=>$d['table'][0]['tr'][0]['td'][0]['div'][1]['img']['alt'],
                'src'=>$d['table'][0]['tr'][0]['td'][0]['div'][1]['img']['src']
                )
                ),
                'perevod'=>$d['table'][0]['tr'][0]['td'][1]['#text'],
                'size'=>$d['table'][0]['tr'][0]['td'][2]['#text'],
                'date_add'=>$d['table'][0]['tr'][0]['td'][3]['#text'],
                'downloads'=>$d['table'][0]['tr'][0]['td'][4]['#text'],
                'seaders'=>$d['table'][0]['tr'][0]['td'][5]['font'][0]['#text'] ? preg_replace('#\D#','',$d['table'][0]['tr'][0]['td'][5]['font'][0]['#text']): 'NULL',
                'leachers'=>$d['table'][0]['tr'][0]['td'][5]['font'][1]['#text'] ? preg_replace('#\D#','',$d['table'][0]['tr'][0]['td'][5]['font'][1]['#text']) : 'NULL',
                'from'=>$url,
                'to'=>$local,
                'tname'=>$torentFileName,
                'images'=>$torrent_images

                ); 

            }
            return $torrent;
        }

        /**
        * Картинки на фильм
        * 
        * @param mixed $film_main - ХТМЛ код фильма
        */
        public function images_film_get($film_main){
            $images = $film_main->get('#tabs1 #tab_scene a')->toArray();
            if($images){
                if(!$images[0]){
                    $temp_am = $images;
                    unset($images);
                    $images[]=$temp_am;
                }
                /*echo "   --START IMAGES--\n"; 
                myflush();*/
                unset($film_current['images']);
                $folder_name = 'film';
                $path = '/images/fiml';

                $k=1;
                unset($arr_fimages);

                foreach($images as $img){

                    if($img['img']['src']){
                        $url = $img['href'];
                        $filename = '/img-'.$k.'.jpg';
                        /*echo "       --".$filename."--\n"; 
                        myflush();*/
                        $arr_fimages[]=array(
                        'from'=>$url,
                        'to'=>$path,
                        'fname'=>$filename
                        );                 

                        $k++;
                    }
                }
                return $arr_fimages;
            }
            return false;
        }
        /**
        * Параметры фильма, описание, релиз, продолжительность, в роля х и т.д. сразу с присваиванием в общий массив фильма
        * 
        * @param mixed $film_main
        */

        public function params_film_get($film_main, &$film_current){

            $content_film = $film_main->get('td[width=440px] td[colspan=2]')->toxml();
            $content_film = str_replace('</span>:','</span>:<span>',$content_film);
            $content_film = str_replace('<br/>','</span>',$content_film);

            $parse = new nokogiri($content_film);
            $table = $parse->get('td')->toArray();

            //Дата выхода

            $film_current['date_reliz'] = $table[0]['span'][1]['#text'];

            //Жанр
            $genres = $table[1]['span'][1]['a'];
            unset($arr_genr);
            foreach($genres as $g){
                $arr_genr[]    =$g['href'];
            }
            $film_current['genres'] = $arr_genr;

            //Продолжительноситб

            $film_current['past']=$table[3]['span'][1]['#text'];

            unset($arr_company);
            unset($arr_channel);
            unset($arr_director);
            unset($arr_producer);
            //vvtr($table[4]['p']);
            for($p=0 ; $p<count($table[4]['p']) ; $p++){
                //Компания
                if( $table[4]['p'][$p]['span'][0]['#text'] == 'Компания' ){
                    foreach( $table[4]['p'][$p]['span'][1]['a'] as $company ){
                        $arr_company[] = array('aka'=>$company['#text'], 'href'=>$company['href']);
                    }
                    $film_current['companies'] = $arr_company;
                }
                //Канал
                if( $table[4]['p'][$p]['span'][0]['#text'] == 'Канал' ){
                    foreach( $table[4]['p'][$p]['span'][1]['a'] as $channel ){
                        $arr_channel[] = array('aka'=>$channel['#text'], 'href'=>$channel['href']);
                    }
                    $film_current['channels'] = $arr_channel;
                }
                //Режиссер
                if( strpos($table[4]['p'][$p]['span'][0]['#text'],'ежисс') ){
                    foreach( $table[4]['p'][$p]['span'][1]['a'] as $director ){
                        $arr_director[] = array('aka'=>$director['#text'], 'href'=>$director['href']);
                    }
                    $film_current['directors'] = $arr_director;
                }
                //Продюссер
                if( $table[4]['p'][$p]['span'][0]['#text'] == 'Продюсер' ){
                    foreach( $table[4]['p'][$p]['span'][1]['a'] as $producer ){
                        $arr_producer[] = array('aka'=>$producer['#text'], 'href'=>$producer['href']);
                    }
                    $film_current['producers'] = $arr_producer;
                }
            }


            //В ролях
            $roles = $table[5]['span'][1]['a'];
            unset($arr_role);
            foreach($roles as $role){
                $arr_role[] = array('href'=>$role['href'],'aka'=>$role['#text']);
            }
            $film_current['roles'] = $arr_role;

            //Описание
            $film_current['descr'] = $table[6]['p'][0]['#text'];

            //Рейтинг
            $rating = $film_main->get('table.info td[width=160px] center div')->toArray();

            preg_match("#\(\"(.*)\"\)#",$rating[2]['style'],$return_kino);
            preg_match("#\(\"(.*)\"\)#",$rating[3]['style'],$return_imdb);

            $film_current['rating'] =array(
            'fasttorrent'=>$rating[0]['#text'],
            'kinopoisk'=>$return_kino[1],
            'imdb'=>$return_imdb[1]
            ) ;
        }

        /**
        * Название фильма, постер и сслыка сразу с присваиванием в общий массив фильма
        * 
        * @param mixed $film_main
        * @param mixed $film_current
        */
        public function titles_film_get($film_main, &$film_current){
            $poster = $film_main->get('td[width=160px] a')->toArray();
            $film_current['poster_from'] = $poster['href'];


            $magic = (str_replace('<br/>','</th><th>',$film_main->get('td[width=440px] table.info')->toXml()));
            //Название//////////
            $nok = new nokogiri($magic);
            $a_link = $nok->get('tr')->toArray();
            if(count($a_link['th'])==1){
                $temp = $a_link['th'];
                unset($a_link);
                $a_link['th'][]=$temp;
            }

            $film_current['aka_rus'] = trim($a_link['th'][0]['#text']);

            if($a_link['th'][1]){
                $film_current['aka_en'] = trim(str_replace(array(')','('),'',$a_link['th'][1]['#text']));
            }else{
                $film_current['aka_en'] = strtr($a_link['th'][0]['#text'], $this->trans);    
            }
            $film_current['aka_en_row'] = preg_replace('#[^A-Za-z0-9\/\-\.]#','',strtr( strtr($film_current['aka_rus'],$this->trans), $this->trans) ) ;
            /////////////////          
            //echo "--START-- ".strtr($film_current['aka_rus'], $this->trans)." ".$cat['href']."\n"; 
            //myflush();
            //Ссылка
            $film_link = $film_main->get('div.do_login input[name=redirect_to]')->toArray();

            $film_current['href']= $film_link[0]['value'];
        }

        /**
        * Инсерт фильм или возврат ИД 
        * 
        * @param mixed $f
        */
        public function film_store($f){
            //Если есть фильм то сосем
            $ft_href = $f['href'];
            $is_film = $this->db->sql('SELECT id FROM films WHERE ft_href="'.$ft_href.'"');
            if($is_film){
                $last_id =     $is_film[0]['id'];
            }else{

                $sql_f = 'INSERT INTO films(aka_ru, aka_trans, aka_en, ft_href, poster_from, filesize, reliz, past, descr, rating_ft, rating_kinopoisk, rating_imdb,date)
                VALUES("'.str_replace('"','\'',$f['aka_rus']).'",
                "'.$f['aka_en_row'].'",
                "'.str_replace('"','\'',$f['aka_en']).'",
                "'.$ft_href.'",
                "'.$f['poster_from'].'",
                "218",
                "'.$f['date_reliz'].'",
                "'.$f['past'].'", 
                "'.str_replace('"','\'',$f['descr']).'", 
                '.($f['rating']['fasttorrent'] ? $f['rating']['fasttorrent'] : "NULL").', 
                '.($f['rating']['kinopoisk'] ? preg_replace('#\D#','',$f['rating']['kinopoisk']) : "NULL").', 
                '.($f['rating']['imdb'] ? preg_replace('#\D#','',$f['rating']['imdb']) : "NULL").',
                "'.date('Y-m-d').'"
                )';

                $ok = $this->db->sql($sql_f);
                if(!$ok ){
                    $this->db->sql("INSERT INTO _log(type,text,date) VALUES('film','".$sql_f."','".date('Y-m-d')."')");
                    return false;
                }
                $_last_id = $this->db->sql('SELECT MAX(id) id FROM films');
                $last_id=$_last_id[0]['id'];

            }
            return $last_id;
        }

        /**
        * Инсерт изображений
        * 
        * @param mixed $f
        */
        public function images_store($f,$last_id){
            foreach($f['images'] as $img){
                $is_image = $this->db->sql('SELECT * FROM film_images WHERE aka="'.$img['fname'].'" AND film_id='.$last_id);
                if(!$is_image){        
                    $_str_im='INSERT INTO film_images VALUES(NULL,"'.$img['from'].'",218,"'.$img['to'].'","'.$img['fname'].'",'.$last_id.',"'.date('Y-m-d').'")';
                    $hui = $this->db->sql($_str_im);
                    if(!$hui){
                        $this->db->sql("INSERT INTO _log(type,text,date) VALUES('film_images','".$_str_im."','".date('Y-m-d')."')");
                    }
                }              
            }
        }        

        /**
        * Инсерт категорий жанров
        * 
        * @param mixed $f
        */
        public function genres_store($f,$last_id){
            foreach($f['genres'] as $k=>$v){

                $is_cat = $this->db->sql('SELECT * FROM category WHERE href_site="'.$v.'"');
                $cat_id = $is_cat ? $is_cat[0]['id']:-1;
                $_xref=$this->db->sql('SELECT * FROM film_category_xref WHERE film_id='.$last_id.' AND category_id='.$cat_id);
                if(!$_xref){
                    $str = 'INSERT INTO film_category_xref VALUES(NULL,'.$last_id.','.$cat_id.')';
                    $is_image = $this->db->sql($str);
                    if(!$is_image){
                        $this->db->sql("INSERT INTO _log(type,text,date) VALUES('film_images','".$str."','".date('Y-m-d')."')");    
                    }
                }
            }
        } 
        /**
        * Инсерт категорий жанров
        * 
        * @param mixed $f
        */

        public function companies_store($f,$last_id){
            if($f['companies']){
                foreach($f['companies'] as $company){
                    $is = $this->db->sql('SELECT * FROM companies WHERE aka="'.trim($company['aka']).'"');
                    $obj_id = $is[0]['id'];
                    if(!$is){
                        $str = 'INSERT INTO companies VALUES(NULL,"'.$company['aka'].'","'.str_replace('/company','',$company['href'] ).'")';
                        $comp =  $this->db->sql($str);
                        if(!$comp){
                            $this->db->sql("INSERT INTO _log(type,text) VALUES('company','".$str."')");        
                        }
                        $obj =  $this->db->sql('SELECT MAX(id) id FROM companies ');
                        $obj_id=$obj[0]['id'];
                    }
                    //Если нет связи
                    $is_xref = $this->db->sql('SELECT * FROM film_company_xref WHERE film_id='.$last_id.' AND company_id='.$obj_id);
                    if(!$is_xref){
                        $str = 'INSERT INTO film_company_xref VALUES(NULL,'.$last_id.','.$obj_id.')';
                        $xref = $this->db->sql();    
                        if(!$xref){
                            $this->db->sql("INSERT INTO _log(type,text) VALUES('company_xref','".$str."')");        
                        }
                    }
                }
            }
        }

        /**
        * Инсерт каналов
        * 
        * @param mixed $f
        * @param mixed $last_id
        */
        public function channels_store($f, $last_id){
            if($f['channels']){
                foreach($f['channels'] as $company){
                    $is = $this->db->sql('SELECT * FROM channels WHERE aka="'.trim($company['aka']).'"');
                    $obj_id = $is[0]['id'];
                    if(!$is){
                        $str =' INSERT INTO channels VALUES(NULL,"'.$company['aka'].'","'.str_replace('/company','',$company['href'] ).'")';
                        $chan = $this->db->sql($str);
                        if(!$chan){
                            $this->db->sql("INSERT INTO _log(type,text,date) VALUES('channel','".$str."','".date('Y-m-d')."')");;         
                        }
                        $obj =  $this->db->sql('SELECT MAX(id) id FROM channels ');
                        $obj_id=$obj[0]['id'];
                    }
                    //Если нет связи
                    $is_xref = $this->db->sql('SELECT * FROM film_channel_xref WHERE film_id='.$last_id.' AND company_id='.$obj_id);
                    if(!$is_xref){ 
                        $str = 'INSERT INTO film_channel_xref VALUES(NULL,'.$last_id.','.$obj_id.')';
                        $chan = $this->db->sql($str);    
                        if(!$chan){
                            $this->db->sql("INSERT INTO _log(type,text,date) VALUES('channel_xref','".$str."','".date('Y-m-d')."')");;         
                        }
                    }
                }
            }
        }

        /**
        * Режиссеры прод.серы и в ролях
        * 
        * @param mixed $f
        * @param mixed $last_id
        * @param mixed $type
        * @param mixed $type_str
        */
        public function peoples_store($f, $last_id, $type, $type_str){
            if($f[$type_str]){
                foreach($f[$type_str] as $company){
                    $is = $this->db->sql('SELECT * FROM actors WHERE aka_ru="'.trim($company['aka']).'"');
                    $obj_id = $is[0]['id'];
                    if(!$is){
                        $str = 'INSERT INTO actors VALUES(NULL,"'.$company['aka'].'","'.strtr($company['aka'],$this->trans).'", "'.str_replace('/video/actor','',$company['href'] ).'")';
                        $actor = $this->db->sql($str);
                        if(!$actor){
                            $this->db->sql("INSERT INTO _log(type,text,date) VALUES('actor_".$type_str."','".$str."','".date('Y-m-d')."')");;         
                        }

                        $obj =  $this->db->sql('SELECT MAX(id) id FROM actors ');
                        $obj_id=$obj[0]['id'];
                    }
                    //Если нет связи
                    $is_xref = $this->db->sql('SELECT * FROM film_actor_xref WHERE film_id='.$last_id.' AND actor_id='.$obj_id.' AND is_dir='.$type);
                    if(!$is_xref){
                        $str = 'INSERT INTO film_actor_xref VALUES(NULL,'.$last_id.','.$obj_id.','.$type.')';
                        $actor = $this->db->sql($str); 
                        if(!$actor){
                            $this->db->sql("INSERT INTO _log(type,text,date) VALUES('actor_".$type_str."_xref','".$str."','".date('Y-m-d')."')");;         
                        }   
                    }
                }
            }
        }

        /**
        * Сохраняем в базу таренты и картинки
        * 
        * @param mixed $f
        * @param mixed $last_id
        */
        public function torrents_images_store($f, $last_id){
            foreach($f['torrents'] as $tor){
                $is_tor = $this->db->sql('SELECT * FROM torrents WHERE ft_id='.trim($tor['id']) );
                $tor_id = $is_tor[0]['id'];
                if(!$is_tor){
                    //Качество  ////////////////
                    $is_quality = $this->db->sql('SELECT * FROM quality WHERE aka="'.$tor['quality']['id']['img']['alt'].'"');
                    $quality_id = $is_quality[0]['id'];
                    if(!$is_quality){
                        $this->db->sql('INSERT INTO quality VALUES(NULL,"'.$tor['quality']['id']['img']['alt'].'","'.$tor['quality']['id']['title'].'")');
                        $qu =  $this->db->sql('SELECT MAX(id) id FROM quality ');    
                        $quality_id=    $qu[0]['id'];
                    }
                    ////////////
                    $str='INSERT INTO torrents VALUES(NULL,'.$last_id.','.$tor['id'].', "'.$tor['quality']['id']['img']['alt'].'", '.$quality_id.',"'.$tor['perevod'].'","'.$tor['size'].'",218, "'.$tor['date_add'].'",'.$tor['downloads'].','.$tor['seaders'].','.$tor['leachers'].',"'.$tor['from'].'","0","'.$tor['to'].'","'.$tor['tname'].'","'.date('Y-m-d').'")';
                    $_tor = $this->db->sql($str);
                    if(!$_tor){
                        $this->db->sql("INSERT INTO _log(type,text,date) VALUES('torrent','".$str."','".date('Y-m-d')."')");         
                        continue;
                    }
                    $_tor =  $this->db->sql('SELECT MAX(id) id FROM torrents ');
                    $tor_id=$_tor[0]['id'];

                }
                // Картинки к торрентам
                foreach($tor['images'] as $img){
                    //Если есть что качать

                    if($img['from']){
                        $is_img=$this->db->sql('SELECT * FROM torrent_images WHERE aka="'.$img['fname'].'"');
                        if(!$is_img){
                            $str = 'INSERT INTO torrent_images VALUES(NULL,"'.$img['from'].'","218", "'.$img['to'].'","'.$img['fname'].'", '.$tor_id.','.$tor['id'].',"'.date('Y-m-d').'")';
                            $im = $this->db->sql($str);
                            if(!$im){
                                $this->db->sql("INSERT INTO _log(type,text,date) VALUES('torrent_ima','".$str."','".date('Y-m-d')."')");             
                            }
                        }
                    }
                }
            }
        }

        /**
        * Выкачка постеров
        * 
        */
        public function poster_download(){
            $films_poster = $this->db->sql('SELECT poster_from,id FROM films WHERE date="'.date('Y-m-d').'"');

            unset($from_poster);
            foreach($films_poster as $poster){
                $from_poster[]=$poster['poster_from'];
            }

            $mcurl = new MCurl;
            $mcurl->threads = 100;
            $mcurl->timeout = 50000; 
            unset($results);
            $mcurl->multiget($from_poster, $results);
            foreach($results as $k=>$v){

                $dir = $this->toimg.'/images/film-'.$films_poster[$k]['id'];

                if(!is_dir($dir)){
                    mkdir($dir);
                }
                $f_name = $dir.'/img-poster.jpg';
                if( file_put_contents( $f_name, $v ) ){
                    $this->db->sql('UPDATE films SET filesize='.filesize($f_name).' WHERE id="'.$films_poster[$k]['id'].'"');
                    echo "OK --- ".$dir."\n";
                    myflush();
                }else{
                    $this->db->sql("INSERT INTO _log(type,text,date) VALUES('ima_download','/film-".$films_poster[$k]['id']."','".date('Y-m-d')."')");         
                }   
            }
        }

        /**
        * Выкачка картинок к фильму
        * 
        */
        public function images_download(){

            $films_images = $this->db->sql('SELECT * FROM film_images WHERE filesize<=218 AND  date="'.date('Y-m-d').'"');

            unset($from);
            foreach($films_images as $img){
                $from[]=$img['from'];
            }
            $urls = $pages;
            $mcurl = new MCurl;
            $mcurl->threads = 100;
            $mcurl->timeout = 50000; // 
            unset($results); // очищаем массив $results (если он использовался раньше где-то в коде)
            $mcurl->multiget($from, $results);
            foreach($results as $k=>$v){
                $dir = $this->toimg.'/images/film-'.$films_images[$k]['film_id'];
                if(is_dir(!$dir)){
                    mkdir($dir);
                }
                $f_name = $dir.$films_images[$k]['aka'];     

                if( file_put_contents( $f_name, $v ) ){
                    $this->db->sql('UPDATE film_images SET filesize='.filesize($f_name).' WHERE id="'.$films_images[$k]['id'].'"');
                    echo "OK --- ".$f_name."\n";
                    myflush();
                }else{
                    $this->db->sql("INSERT INTO _log(type,text,date) VALUES('ima_film_download','".$films_images[$k]['aka']."','".date('Y-m-d')."')");         
                    myflush();

                }
            }
        }

        /**
        * Выкачка тарентов
        * 
        */
        public function torrents_download(){

            $torrents = $this->db->sql("SELECT * FROM torrents WHERE filesize<=218 AND date='".date('Y-m-d')."'");
            $iteration=0;
            while($torrents){
                if($iteration>20) break;
                $iteration++;
                unset($from_tor);
                foreach($torrents as $tor){
                    if($tor['filesize']<=218){
                        $from_tor[]=str_replace(' ','%20',$tor['from']);
                    }
                    if(!is_dir($this->toimg.'/download/torrent/film-'.$tor['film_id']))
                        mkdir($this->toimg.'/download/torrent/film-'.$tor['film_id']);

                    if(!is_dir($this->toimg.'/download/torrent/film-'.$tor['film_id'].'/'.$tor['id']))
                        mkdir($this->toimg.'/download/torrent/film-'.$tor['film_id'].'/'.$tor['id']);

                }
                echo 'torrent -'.count($from_tor)."\n";
                myflush();

                $mcurl = new MCurl;
                $mcurl->threads = 100;
                $mcurl->timeout = 50000; 
                unset($results); 

                $mcurl->multiget($from_tor, $results);
                foreach($results as $k=>$v){

                    $t_name = $this->toimg.'/download/torrent/film-'.$torrents[$k]['film_id'].'/'.$torrents[$k]['id'].'/'.$torrents[$k]['aka'];
                    if( file_put_contents($t_name , $v ) ){
                        $this->db->sql('UPDATE torrents SET  filesize='.filesize($t_name).' WHERE id="'.$torrents[$k]['id'].'"');

                        echo "OK --- ".$torrents[$k]['aka']."\n";
                        myflush();

                    }   
                }
                $torrents = $this->db->sql("SELECT * FROM torrents WHERE filesize<=218 AND date='".date('Y-m-d')."'");
            }
        }

        /**
        * Выкачка картинок торрента
        * 
        */
        public function torrents_images_download(){

            $tims = $this->db->sql("SELECT i.id, i.from, i.path, i.aka, t.film_id, t.id tor_id FROM torrent_images i
            LEFT JOIN torrents t ON t.id=i.torrent_id
            WHERE i.filesize<=218 AND i.date='".date('Y-m-d')."' ORDER BY i.id");

            unset($from_tim);
            foreach($tims as $tim){
                $from_tim[]=$tim['from'];
                if(!is_dir($this->toimg.'/images/film-'.$tim['film_id'].'/'.$tim['tor_id'] ))
                    mkdir($this->toimg.'/images/film-'.$tim['film_id'].'/'.$tim['tor_id'] );
            }

            $mcurl = new MCurl;
            $mcurl->threads = 100;
            $mcurl->timeout = 50000; // 
            unset($results); // очищаем массив $results (если он использовался раньше где-то в коде)
            $mcurl->multiget($from_tim, $results);
            $y=0;
            foreach($results as $k=>$v){
                $f_name = $this->toimg.'/images/film-'.$tims[$k]['film_id'].'/'.$tims[$k]['tor_id'].'/img-'.$tims[$k]['id'].'.jpg';
                if( file_put_contents($f_name , $v ) ){
                    $this->db->sql('UPDATE torrent_images SET filesize='.filesize($f_name).' WHERE id="'.$tims[$k]['id'].'"');
                    echo "OK --- ".'/images/film-'.$tims[$k]['film_id'].'/'.$tims[$k]['tor_id'].'/img-'.$tims[$k]['id'].'.jpg'."\n";
                    myflush();
                }else{
                    $this->db->sql("INSERT INTO _log(type,text,date) VALUES('torr_imag','".$f_name."','".date('Y-m-d')."')");         
                }   
                $y++;
            } 

        }


        public function index() {

            //Конфигггггг

            $set_films_by_category = true;
            $parse_images = true;


            ############### ФильМЫ ###################################
            if($set_films_by_category){                  

                $html = file_get_contents('http://fast-torrent.ru/new-torrent/1.html');
                $saw = new nokogiri($html);
                $paginator_ul = $saw->get('ul.paginator a')->toArray();
                $last_page = $paginator_ul[2]['#text']?$paginator_ul[2]['#text']:1;

                //Мультискачка  ######################################
                $end=1;
                $ho=0;

                unset($pages);
                for($i=$ho*$end+1;$i<=$end*$ho+$end;$i++){
                    $pages[] = 'http://fast-torrent.ru/new-torrent/'.$i.'.html';
                    echo "http://fast-torrent.ru/new-torrent/".$i.".html\n";
                    myflush();
                }               
                $ho++;       

                while(count($results)<count($pages)){
                    $mcurl = new MCurl;
                    $mcurl->threads = 25;
                    $mcurl->timeout = 50000;
                    unset($results); 
                    $mcurl->multiget($pages, $results);
                    echo 'stranic - '.count($pages).' iz -'.count($results);
                    myflush();
                }             

                //Это страницы с фльмами по 15 штук
                //Список ссылок на фильмы
                $film_main_pages = $this->films_link_get($results) ;


                //Мультискачка фильма
                //http://fasttorrent.ru/film/127-chasov.html
                while(count($results)<count($film_main_pages)){
                    $mcurl = new MCurl;
                    $mcurl->threads = 50;
                    $mcurl->timeout = 50000; 
                    unset($results);
                    $mcurl->multiget($film_main_pages, $results);
                    $total_res = count($results);
                    echo 'stranic '.count($film_main_pages).' iz '.count($results)."\n";
                    myflush();
                }
                
                foreach($results as $res){
                    $z++;
                    unset($film_current);
                    echo "---------- ".$z." of ".$total_res."----------\n";
                    myflush();
                    //vvtr($res);
                    $film_main = new nokogiri($res);

                    ##############################################################################
                    //Название фильма, постер и ссылка
                    $this->titles_film_get($film_main, &$film_current);
                    ##############################################################################
                    //Картинки к фильму
                    $film_current['images'] = $this->images_film_get($film_main);
                    ##############################################################################
                    //Параметры там описание, резил, продолжительность в ролях режиссер и т.д.
                    $this->params_film_get($film_main, &$film_current);
                    ##############################################################################
                    //Торренты скачки
                    $film_current['torrents'] = $this->torrents_film_get($film_main);
                    ##############################################################################
                     
                    $films[] = $film_current;

                }
                $data['films']=$films;

                //Пишем в базку#########################################

                foreach($data['films'] as $f){
                     
                    //Сохраняем новый фильм или выбираем если есть возврат ИД
                    $film_store_and_return_id = $this->film_store($f);
                    
                    //Если фильм заинсертился 
                    if($film_store_and_return_id){
                        $last_id = $film_store_and_return_id;
                        ###################картинки################
                        $this->images_store($f, $last_id);
                        ################### жанры ################
                        $this->genres_store($f, $last_id);
                        ################### Компании ################
                        $this->companies_store($f, $last_id);
                        ################### Каналы ################
                        $this->channels_store($f, $last_id);
                        ################### Люди ################
                        // 1 - режиссер
                        $this->peoples_store($f, $last_id, 1 ,'directors');
                        // 2 - продюссер
                        $this->peoples_store($f, $last_id, 2 ,'producers');
                        // 0 - в ролях
                        $this->peoples_store($f, $last_id, 0 ,'roles');
                        ###################### Торренты ##############
                        $this->torrents_images_store($f, $last_id);

                        //Если нет торрентов
                        if(!$f['torrents']){
                            $this->db->sql('INSERT INTO torrents VALUES(NULL,'.$last_id.',-1,"-1",-1,"-1","-1","-1",-1,-1,-1,"-1","-1")');
                        }
                    }
                }   

            }

            if($parse_images){
                #################################################
                //Постеры
                $this->poster_download();
                #################################################
                //Картинки к фильму
                $this->images_download();
                #################################################
                //Торренты
                $this->torrents_download();
                #################################################
                //Картинки к торрентам
                $this->torrents_images_download();

            }
        }



        function __destruct() {
            echo('ok');
        }

    }

    class nokogiri implements IteratorAggregate{
        protected $_source = '';
        /**
        * @var DOMDocument
        */
        protected $_dom = null;
        /**
        * @var DOMXpath
        * */
        protected $_xpath = null;
        public function __construct($htmlString = ''){
            $this->loadHtml($htmlString);
        }
        public static function fromHtml($htmlString){
            $me = new self();
            $me->loadHtml($htmlString);
            return $me;
        }
        public static function fromDom($dom){
            $me = new self();
            $me->loadDom($dom);
            return $me;
        }
        public function loadDom($dom){
            $this->_dom = $dom;
            $this->_xpath = new DOMXpath($this->_dom);
        }
        public function loadHtml($htmlString = ''){
            //$dom = new DOMDocument('1.0', 'UTF-8');
            $dom = new DOMDocument('1.0', 'utf-8'); 
            $dom->preserveWhiteSpace = false;
            if (strlen($htmlString)){
                libxml_use_internal_errors(TRUE);
                $dom->loadHTML('<?xml encoding="UTF-8">' .$htmlString);
                $dom->encoding = 'utf-8';
                libxml_clear_errors();
            }
            $this->loadDom($dom);
        }
        function __invoke($expression){
            return $this->get($expression);
        }
        public function get($expression){
            if (strpos($expression, ' ') !== false){
                $a = explode(' ', $expression);
                foreach ($a as $k => $sub){
                    $a[$k] = $this->getXpathSubquery($sub);
                }
                return $this->getElements(implode('', $a));
            }
            return $this->getElements($this->getXpathSubquery($expression));
        }
        protected function getXpathSubquery($expression){
            $query = '';
            if (preg_match("/(?P<tag>[a-z0-9]+)?(\[(?P<attr>\S+)=(?P<value>\S+)\])?(#(?P<id>\S+))?(\.(?P<class>\S+))?/ims", $expression, $subs)){
                $tag = $subs['tag'];
                $id = $subs['id'];
                $attr = $subs['attr'];
                $attrValue = $subs['value'];
                $class = $subs['class'];
                if (!strlen($tag))
                    $tag = '*';
                $query = '//'.$tag;
                if (strlen($id)){
                    $query .= "[@id='".$id."']";
                }
                if (strlen($attr)){
                    $query .= "[@".$attr."='".$attrValue."']";
                }
                if (strlen($class)){
                    //$query .= "[@class='".$class."']";
                    $query .= '[contains(concat(" ", normalize-space(@class), " "), " '.$class.' ")]';
                }
            }
            return $query;
        }
        protected function getElements($xpathQuery){
            //$newDom = new DOMDocument('1.0', 'UTF-8');
            $newDom = new DOMDocument('1.0', 'utf-8'); 
            $root = $newDom->createElement('root');
            $newDom->appendChild($root);
            if (strlen($xpathQuery)){
                $nodeList = $this->_xpath->query($xpathQuery);
                if ($nodeList === false){
                    throw new Exception('Malformed xpath');
                }
                foreach ($nodeList as $domElement){
                    $domNode = $newDom->importNode($domElement, true);
                    $root->appendChild($domNode);
                }
                return self::fromDom($newDom);
            }
        }
        public function toXml(){
            return $this->_dom->saveXML();
        }
        public function toArray($xnode = null){
            $array = array(); 
            if ($xnode === null){
                $node = $this->_dom;
            }else{
                $node = $xnode;
            }
            if ($node->nodeType == XML_TEXT_NODE){
                return $node->nodeValue;
            }
            if ($node->hasAttributes()){
                foreach ($node->attributes as $attr){
                    $array[$attr->nodeName] = $attr->nodeValue;
                }
            }
            if ($node->hasChildNodes()){
                if ($node->childNodes->length == 1){
                    $array[$node->firstChild->nodeName] = $this->toArray($node->firstChild);
                }else{
                    foreach ($node->childNodes as $childNode){
                        if ($childNode->nodeType != XML_TEXT_NODE){
                            $array[$childNode->nodeName][] = $this->toArray($childNode);
                        }
                    }
                }
            }
            if ($xnode === null){
                return reset(reset($array)); // first child
            }
            return $array;
        }
        public function getIterator(){
            $a = $this->toArray();
            return new ArrayIterator($a);
        }
    }


    class MCurl
    {

        var $timeout = 20; // максимальное время загрузки страницы в секундах
        var $threads = 10; // количество потоков 

        var $all_useragents = array(
        "Opera/9.23 (Windows NT 5.1; U; ru)",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.8.1.8) Gecko/20071008 Firefox/2.0.0.4;MEGAUPLOAD 1.0",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; Alexa Toolbar; MEGAUPLOAD 2.0; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7;MEGAUPLOAD 1.0",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; MyIE2; Maxthon)",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; MyIE2; Maxthon)",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; MyIE2; Maxthon)",
        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; WOW64; Maxthon; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; Media Center PC 5.0; InfoPath.1)",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; MyIE2; Maxthon)",
        "Opera/9.10 (Windows NT 5.1; U; ru)",
        "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.2.1; aggregator:Tailrank; http://tailrank.com/robot) Gecko/20021130",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.8) Gecko/20071008 Firefox/2.0.0.8",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; MyIE2; Maxthon)",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.8.1.8) Gecko/20071008 Firefox/2.0.0.8",
        "Opera/9.22 (Windows NT 6.0; U; ru)",
        "Opera/9.22 (Windows NT 6.0; U; ru)",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.8.1.8) Gecko/20071008 Firefox/2.0.0.8",
        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; MRSPUTNIK 1, 8, 0, 17 HW; MRA 4.10 (build 01952); .NET CLR 1.1.4322; .NET CLR 2.0.50727)",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9"
        );

        function multiget($urls, &$result)
        {
            $threads = $this->threads;
            $useragent = $this->all_useragents[array_rand($this->all_useragents)];
            $p=1;
            $i = 0;
            for($i=0;$i<count($urls);$i=$i+$threads)
            {
                $urls_pack[] = array_slice($urls, $i, $threads);
            }
            foreach($urls_pack as $pack)
            {                                                     echo count($urls)-$p*($threads)."\n"; myflush();
                $mh = curl_multi_init(); unset($conn);
                foreach ($pack as $i => $url)
                {
                    $conn[$i]=curl_init(trim($url));
                    curl_setopt($conn[$i],CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($conn[$i],CURLOPT_TIMEOUT, $this->timeout);

                    curl_setopt($conn[$i],CURLOPT_USERAGENT, $useragent);
                    curl_multi_add_handle ($mh,$conn[$i]);        
                }
                do { $n=curl_multi_exec($mh,$active); usleep(100);  } while ($active);
                foreach ($pack as $i => $url)
                {                                                 
                    $result[]=curl_multi_getcontent($conn[$i]);
                    curl_close($conn[$i]);

                }
                curl_multi_close($mh);  $p++;
            }

        }
    }

?>