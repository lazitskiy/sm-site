<?php
ini_set('memory_limit', '3200M');

function myflush($timee = null)
{
    if (ob_get_contents()) {
        ob_flush();
        ob_clean();
        flush();
        if ($timee) {
            sleep($timee);
        }
    }
}

//phpinfo();
class Fasttorrent extends ParserBase
{

    function __construct()
    {
        parent::__construct();

        $this->provider_id = 1;

        $this->toimg = $_SERVER['DOCUMENT_ROOT'] = 'E:/Zsrv/Apache2/htdocs/sites/torrent';
        //$this->toimg = $_SERVER['DOCUMENT_ROOT'] = 'E:/Zsrv/Apache2/htdocs/torrent';
        //$this->toimg = $_SERVER['DOCUMENT_ROOT'] = '/var/www/teletorrent.ru/public/www';


        /**
         * Xpath до нужных значений
         */
        $this->url_base = 'http://fast-torrent.ru';

        // Карточка. Постер
        $this->film_poster = 'a.slideshow1';
        // Карточка. Ссылка на фильм
        $this->film_href = 'meta[property=og:url]';
        // Карточка. Кадры из фильма
        $this->film_images = '#tab_scene a.slideshow';

    }


    /**
     * Получаем все ссылки и сохраняем в базу без парсинга
     */
    public function parseLinks()
    {
        $film_main_pages = $this->getFilmLinks(10);
        $kolvo = $this->storeNewFilmLinks($film_main_pages, $this->provider_id);

        echo "\n" . 'Save ' . $kolvo . ' new links';
    }


    /**
     * Парсим кино по айдишнику
     */
    public function parseFilm()
    {
        $film_id = $this->get('param');
        $provider_id = $this->provider_id;
        $film_model = new Axon('film');
        $film = $film_model->afindone('id=' . $film_id . ' AND provider_id=' . $provider_id);

        if (!$film) {
            echo "This film ID not fount. Abort\n";
            return;
        }
        $parsed = $this->parseFilmsByUrls(array($film['url']));

        $this->storeFilm($parsed[0], $this->provider_id);
        echo "Saved \n";
        myflush();
        //file_put_contents(dirname(__FILE__) . '/../tmp/film' . $film_id . '.txt', print_r($parsed, true));

    }


    /**
     * Парсим кино по айдишнику
     */
    public function parseFilms()
    {
        $limit = $this->get('param');
        $provider_id = $this->provider_id;

        $film_model = new Axon('film');
        $films = $film_model->afind('provider_id=' . $provider_id . ' AND uploaded="0"', 'id DESC', $limit);

        if (!$films) {
            echo "There is not unparsed films. Abort\n";
            return;
        }

        $urls = array_map(function ($el) {
            return $el['url'];
        }, $films);

        $parsed = $this->parseFilmsByUrls($urls);

        $total = count($parsed);
        $i = 1;

        foreach ($parsed as $film) {
            $this->storeFilm($film, $this->provider_id);
            echo "Saved " . $i . " / " . $total . "\n";
            myflush();
            $i++;
        }


        //file_put_contents(dirname(__FILE__) . '/../tmp/film' . $film_id . '.txt', print_r($parsed, true));
        vvd('ok');

    }


    /**
     * Это главная хрень. Дать массив с УРЛами на фильмы
     * @param array $film_main_pages
     * @return array
     *
     */
    public function parseFilmsByUrls(array $film_main_pages)
    {
        while (count($results) < count($film_main_pages)) {
            $mcurl = new MCurl;
            $mcurl->threads = 40;
            $mcurl->timeout = 50000;
            unset($results);
            $mcurl->multiget($film_main_pages, $results);
            $total_res = count($results);
            echo 'stranic ' . count($film_main_pages) . ' iz ' . count($results) . "\n";
            myflush();
        }

        foreach ($results as $res) {
            $z++;
            unset($film_current);
            echo "---------- " . $z . " of " . $total_res . "----------\n";
            myflush();
            //vvtr($res);
            $film_main = new nokogiri($res);

            unset($this->errors);
            //Название фильма, постер и ссылка
            $film_current = $this->titles_film_get($film_main);
            //Торренты скачки
            $film_current['torrents'] = $this->torrents_film_get($film_main);

            if ($this->errors['general']) {
                $film_current['errors'] = $this->errors['general'];
            }

            $films[] = $film_current;

        }

        return $films;

    }


    /**
     * Список ссылок на фильмы
     * http://fast-torrent.ru/new-torrent/1.html
     * @param mixed $results массив страниц по 15 фильмов
     */
    public function films_link_get($results)
    {
        $film_main_pages = array();
        unset($film_main_pages);

        foreach ($results as $res) {
            $films_page = new nokogiri($res);
            $films_list = $films_page->get('div.film-list div.film-item')->toArray();

            foreach ($films_list as $film_row) {
                $film_main_pages[] = [
                    'url' => $this->url_base . $film_row['div'][1]['div'][0]['a']['href'],
                    'provider_film_id' => $film_row['div'][0]['div']['obj_id']
                ];
            }
        }
        return $film_main_pages;
    }

    /**
     * Массив торрента картинок
     *
     * @param mixed $film_main - ХТМЛ код страницы
     */
    public function torrents_film_get($film_main)
    {


        $downloads = $film_main->get('.torrent-row')->toArray();

        //Костыль если один торрент фаил скачки
        if (!$downloads[0]) {
            $temp = $downloads;
            unset($downloads);
            $downloads[0] = $temp;
        }

        unset($torrent);
        /*echo "   --START torrents&images\n";
        myflush();*/

        if (!$downloads) {
            $this->errors['general'][] = 'Torrents not set';
        }

        foreach ($downloads as $d) {

            $imgs = new nokogiri($d['code']);
            $images = $imgs->get('a')->toArray();
            if (!$images[0]) {
                $temp_ima = $images;
                unset($images);
                $images[0] = $temp_ima;
            }

            if ($images) {
                $_folder_name = $d['obj'];
                unset($torrent_images);

                foreach ($images as $img) {
                    $url = $img['href'];

                    /*echo "       --".$filename."--\n";
                    myflush();*/
                    $torrent_images[] = strtolower(preg_replace('/.*files\//', '', $url));

                }

            }


            //Торрент фаил
            //$torrName = urldecode($d['table'][0]['tr'][0]['td'][6]['a']['href']);

            $delta = 0;
            if (count($d['div'][0]['div']) == 8) {
                $delta = 1;
                $sezon = preg_replace('/\s{2,}/u', '', trim($d['div'][0]['div'][1]['#text']));
            }

            $url = preg_replace('/.*download\/torrent\//', '', urldecode($d['div'][0]['div'][6 + $delta]['a']['href']));

            $text = $d['div'][0]['div'][0]['em'][0]['title'];
            if (!$text) {
                $text = $d['div'][0]['div'][0]['em'][1]['title'];
            }
            $_q = explode('::', $text);


            $torrent[] = array(
                'id' => $d['obj'],
                'quality' => array(
                    'id' => $d['quality'],
                    'short' => trim($_q[0]),
                    'text' => trim($_q[1]),
                    /*'img' => array(
                        'alt' => $d['table'][0]['tr'][0]['td'][0]['div'][1]['img']['alt'],
                        'src' => $d['table'][0]['tr'][0]['td'][0]['div'][1]['img']['src']
                    )*/
                ),
                'sezon' => $sezon,
                'perevod' => $d['div'][0]['div'][1 + $delta]['#text'],
                'size' => $d['size'],
                'date_add' => $d['date'],
                'downloads' => $d['div'][0]['div'][4 + $delta]['#text'],
                'seaders' => preg_replace('#\D#', '', $d['div'][0]['div'][5 + $delta]['font'][0]['#text']),
                'leachers' => preg_replace('#\D#', '', $d['div'][0]['div'][5 + $delta]['font'][1]['#text']),
                'url' => $url,
                'images' => $torrent_images

            );

        }
        return $torrent;
    }


    /**
     * Название фильма, постер и сслыка сразу с присваиванием в общий массив фильма
     *
     * @param mixed $film_main
     * @param mixed $film_current
     */
    public function titles_film_get($film_main)
    {
        /**
         * Название, ID фильма
         */
        $poster = $film_main->get($this->film_poster)->toArray();
        $film_current['poster_from'] = strtolower(preg_replace('/.*files\//', '', $poster['href']));
        if (!$poster['href']) {
            $this->errors['general'][] = 'Poster not set';
        }

        $content = $film_main->toXml();
        preg_match('/film_controll.+?obj_id="(\d+?)"/sui', $content, $id_match);

        $film_current['provider_film_id'] = $id_match[1];
        if (!$id_match[1]) {
            $this->errors['general'][] = 'ID not set';
        }

        preg_match('/<h1>[^"]+itemprop.+?">+(.+?)<\//sui', $content, $aka_ru_match);
        $film_current['aka_rus'] = $aka_ru_match[1];

        preg_match('/<h1>[^|]+alternativeHeadli.+?">(.+?)<\//sui', $content, $aka_en_match);
        $film_current['aka_en'] = $aka_en_match[1];


        $film_current['aka_en_row'] = Transliterator::transliterate($film_current['aka_rus']);

        if (!$film_current['aka_rus']) {
            $this->errors['general'][] = 'Name rus not set';
        }
        if (!$film_current['aka_en']) {
            $this->errors['general'][] = 'Name en not set';
        }

        /////////////////
        //echo "--START-- ".strtr($film_current['aka_rus'], $this->trans)." ".$cat['href']."\n";
        //myflush();
        //Ссылка
        $film_current['url'] = $this->url_base . $film_main->get($this->film_href)->toArray()['content'];
        if (!$film_current['url']) {
            $this->errors['general'][] = 'Film url not set';
        }

        /**
         * Остальное инфо
         */
        $content = $film_main->toxml();
        //Дата выхода
        if (preg_match_all('/(дата выхода[^\<]+)[^"]+(\d{2}\.\d{2}\.\d{4})/sui', $content, $date_relises, PREG_SET_ORDER)) {
            foreach ($date_relises as $date_relise) {
                $relises[] = [
                    'name' => $date_relise[1],
                    'date' => $date_relise[2]
                ];
            }
        } else {
            $this->errors['general'][] = 'Relises not set';
        }
        $film_current['date_relises'] = $relises;

        //Рейтинги
        if (preg_match('/user_vote1[^}]+\/(\d+)\.gif/sui', $content, $kinopoisk_match)) {
            $kinopoisk_id = $kinopoisk_match[1];
        }
        $film_current['kinopoisk_id'] = $kinopoisk_id;

        if (preg_match('/user_vote1[^}]+\/(\d+)\.png/sui', $content, $imdb_match)) {
            $imdb_id = $imdb_match[1];
        }
        $film_current['imdb_id'] = $imdb_id;


        // Жанр
        if (preg_match('/<p>[^"]+жанр(.+?)<\/p>/sui', $content, $genre_match)) {
            preg_match_all('/href="\/([^"]+)?\/"[^>]*>([^"]+)?<\/a>/siu', $genre_match[1], $genres, PREG_SET_ORDER);
            foreach ($genres as $genre) {
                $arr_genr[] = [
                    'name' => $genre[2],
                    'url' => $genre[1]
                ];
            }
        } else {
            $this->errors['general'][] = 'Genres not set';
        }
        $film_current['genres'] = $arr_genr;


        // Страна
        if (preg_match_all('/cn-icon[^>]+title="(.+?)">(.+?)</sui', $content, $countrie_match, PREG_SET_ORDER)) {
            foreach ($countrie_match as $country) {
                $arr_country[] = [
                    'name' => $country[1],
                    'url' => $country[2]
                ];
            }
        } else {
            $this->errors['general'][] = 'Countries not set';
        }
        $film_current['countries'] = $arr_country;

        //Теги
        if (preg_match('/<p>(.+?)\/tag(.+?)<\/p>/sui', $content, $tag_match)) {
            preg_match_all('/[video|tv]\/tag\/(.+?)\/(.+?)em>(.+?)<\/a/siu', $tag_match[0], $tags, PREG_SET_ORDER);
            foreach ($tags as $tag) {
                $arr_tag[] = [
                    'name' => $tag[3],
                    'url' => $tag[1]
                ];
            }
        } else {
            $this->errors['general'][] = 'Tags not set';
        }
        $film_current['tags'] = $arr_tag;


        //Продолжительноситб
        if (preg_match('/продолжительность[^"]+\:[^\d](.+?)[^:\d]+/sui', $content, $last)) {
            $last = $last[1];
        } else {
            $this->errors['general'][] = 'Last not set';
        }
        $film_current['last'] = $last;

        // Компания
        if (preg_match('/info[^}]+<p[^"]+компания(.+?)<\/p>/sui', $content, $company_match)) {
            preg_match_all('/href="\/company\/([^"]+)?\/"[^>]*>([^"]+)?<\/a>/siu', $company_match[1], $companies, PREG_SET_ORDER);
            foreach ($companies as $company) {
                $arr_company[] = [
                    'name' => $company[2],
                    'url' => $company[1]
                ];
            }
        } else {
            $this->errors['general'][] = 'Company not set';
        }
        $film_current['companies'] = $arr_company;

        // Канал
        if (preg_match('/info[^}]+<p[^"]+канал(.+?)<\/p>/sui', $content, $channel_match)) {
            preg_match_all('/href="\/channel\/([^"]+)?\/"[^>]*>([^"]+)?<\/a>/siu', $channel_match[1], $channels, PREG_SET_ORDER);
            foreach ($channels as $channel) {
                $arr_channel[] = [
                    'name' => $channel[2],
                    'url' => $channel[1]
                ];
            }
        } else {
            $this->errors['general'][] = 'Channel not set';
        }
        $film_current['channels'] = $arr_channel;


        //  ДОп
        if (preg_match('/<p align.+(Экранизация по.+?)<.*?actor\/(.+?)\/.+?m>(.+?)<\/a>/sui', $content, $ekran)) {
            $info[] = [
                'name' => $ekran[1],
                'url' => $ekran[2],
                'url_actor' => $ekran[3]
            ];
        }
        $film_current['info'] = $info;

        // Режиссеры
        if (preg_match('/info[^}]+<p[^}]+режиссер(.+?)<\/p>/sui', $content, $people_match)) {
            preg_match_all('/href="\/video\/actor\/([^"]+)?\/"[^>]*>([^"]+)?<\/a>/siu', $people_match[1], $peoples, PREG_SET_ORDER);
            foreach ($peoples as $people) {
                $arr_people[] = [
                    'name' => $people[2],
                    'url' => Transliterator::transliterateActor($people[1])
                ];
            }
        } else {
            $this->errors['general'][] = 'Directors not set';
        }
        $film_current['directors'] = $arr_people;

        unset($arr_people);
        unset($peoples);
        unset($people_match);
        // Продюсеры
        if (preg_match('/info[^}]+<p[^}]+продюсер(.+?)<\/p>/sui', $content, $people_match)) {
            preg_match_all('/href="\/video\/actor\/([^"]+)?\/"[^>]*>([^"]+)?<\/a>/siu', $people_match[1], $peoples, PREG_SET_ORDER);
            foreach ($peoples as $people) {
                $arr_people[] = [
                    'name' => $people[2],
                    'url' => Transliterator::transliterateActor($people[1])
                ];
            }
        } else {
            $this->errors['general'][] = 'Producer not set';
        }
        $film_current['producers'] = $arr_people;

        unset($arr_people);
        unset($peoples);
        unset($people_match);
        // В ролях
        if (preg_match('/info[^}]+<p[^}]+в ролях(.+?)<\/p>/sui', $content, $people_match)) {
            preg_match_all('/href="\/video\/actor\/([^"]+)?\/"[^>]*>([^"]+)?<\/a>/siu', $people_match[1], $peoples, PREG_SET_ORDER);
            foreach ($peoples as $people) {
                $arr_people[] = [
                    'name' => $people[2],
                    'url' => Transliterator::transliterateActor($people[1])
                ];
            }
        } else {
            $this->errors['general'][] = 'Roles not set';
        }
        $film_current['roles'] = $arr_people;


        //Описание
        if (preg_match('/<p item.+?description[^>]+>(.*?)<\/p>/sui', $content, $description)) {
            $description = strip_tags($description[1]);
            if (!$description[1]) {
                preg_match('/<p item.+?description[^>]+>(.*?)<\/p><p>(.*?)<\/p>/sui', $content, $description);
                $description = strip_tags($description[2]);
            }

            $film_current['description'] = trim(str_replace(array("\r", "\n"), "", html_entity_decode($description)));
        } else {
            $this->errors['general'][] = 'Description not set';
        }


        $spoilers = $film_main->get('.spoiler-wrap li')->toArray();
        if (!$spoilers) {
            $this->errors['general'][] = 'Spoilers not set';
        }

        if ($spoilers['#text']) {
            $r = $spoilers;
            unset($spoilers);
            $spoilers[] = $r;
        }
        if ($spoilers) {
            $spoilers = implode('', array_map(function ($el) {
                return '<p>' . $el['#text'] . '</p>';
            }, $spoilers));
        }
        $film_current['spoilers'] = $spoilers;


        /**
         * Картинки
         */
        $images = $film_main->get($this->film_images)->toArray();
        if (!$images) {
            $this->errors['general'][] = 'Images not set';
        }
        if ($images['#text']) {
            $temp_am = $images;
            unset($images);
            $images[] = $temp_am;
        }

        /*echo "   --START IMAGES--\n";
        myflush();*/
        foreach ($images as $img) {
            $arr_fimages[] = strtolower(preg_replace('/.*files\//', '', $img['href']));
        }

        $film_current['images'] = $arr_fimages;

        return $film_current;
    }


    /**
     * Выкачка постеров
     *
     */
    public function poster_download()
    {
        $films_poster = $this->db->sql('SELECT poster_from,id FROM films WHERE date="' . date('Y-m-d') . '"');

        unset($from_poster);
        foreach ($films_poster as $poster) {
            $from_poster[] = $poster['poster_from'];
        }

        $mcurl = new MCurl;
        $mcurl->threads = 100;
        $mcurl->timeout = 50000;
        unset($results);
        $mcurl->multiget($from_poster, $results);
        foreach ($results as $k => $v) {

            $dir = $this->toimg . '/images/film-' . $films_poster[$k]['id'];

            if (!is_dir($dir)) {
                mkdir($dir);
            }
            $f_name = $dir . '/img-poster.jpg';
            if (file_put_contents($f_name, $v)) {
                $this->db->sql('UPDATE films SET filesize=' . filesize($f_name) . ' WHERE id="' . $films_poster[$k]['id'] . '"');
                echo "OK --- " . $dir . "\n";
                myflush();
            } else {
                $this->db->sql("INSERT INTO _log(type,text,date) VALUES('ima_download','/film-" . $films_poster[$k]['id'] . "','" . date('Y-m-d') . "')");
            }
        }
    }

    /**
     * Выкачка картинок к фильму
     *
     */
    public function images_download()
    {

        $films_images = $this->db->sql('SELECT * FROM film_images WHERE filesize<=218 AND  date="' . date('Y-m-d') . '"');

        unset($from);
        foreach ($films_images as $img) {
            $from[] = $img['from'];
        }
        $urls = $pages;
        $mcurl = new MCurl;
        $mcurl->threads = 100;
        $mcurl->timeout = 50000; //
        unset($results); // очищаем массив $results (если он использовался раньше где-то в коде)
        $mcurl->multiget($from, $results);
        foreach ($results as $k => $v) {
            $dir = $this->toimg . '/images/film-' . $films_images[$k]['film_id'];
            if (is_dir(!$dir)) {
                mkdir($dir);
            }
            $f_name = $dir . $films_images[$k]['aka'];

            if (file_put_contents($f_name, $v)) {
                $this->db->sql('UPDATE film_images SET filesize=' . filesize($f_name) . ' WHERE id="' . $films_images[$k]['id'] . '"');
                echo "OK --- " . $f_name . "\n";
                myflush();
            } else {
                $this->db->sql("INSERT INTO _log(type,text,date) VALUES('ima_film_download','" . $films_images[$k]['aka'] . "','" . date('Y-m-d') . "')");
                myflush();

            }
        }
    }

    /**
     * Выкачка тарентов
     *
     */
    public function torrents_download()
    {

        $torrents = $this->db->sql("SELECT * FROM torrents WHERE filesize<=218 AND date='" . date('Y-m-d') . "'");
        $iteration = 0;
        while ($torrents) {
            if ($iteration > 20) break;
            $iteration++;
            unset($from_tor);
            foreach ($torrents as $tor) {
                if ($tor['filesize'] <= 218) {
                    $from_tor[] = str_replace(' ', '%20', $tor['from']);
                }
                if (!is_dir($this->toimg . '/download/torrent/film-' . $tor['film_id']))
                    mkdir($this->toimg . '/download/torrent/film-' . $tor['film_id']);

                if (!is_dir($this->toimg . '/download/torrent/film-' . $tor['film_id'] . '/' . $tor['id']))
                    mkdir($this->toimg . '/download/torrent/film-' . $tor['film_id'] . '/' . $tor['id']);

            }
            echo 'torrent -' . count($from_tor) . "\n";
            myflush();

            $mcurl = new MCurl;
            $mcurl->threads = 100;
            $mcurl->timeout = 50000;
            unset($results);

            $mcurl->multiget($from_tor, $results);
            foreach ($results as $k => $v) {

                $t_name = $this->toimg . '/download/torrent/film-' . $torrents[$k]['film_id'] . '/' . $torrents[$k]['id'] . '/' . $torrents[$k]['aka'];
                if (file_put_contents($t_name, $v)) {
                    $this->db->sql('UPDATE torrents SET  filesize=' . filesize($t_name) . ' WHERE id="' . $torrents[$k]['id'] . '"');

                    echo "OK --- " . $torrents[$k]['aka'] . "\n";
                    myflush();

                }
            }
            $torrents = $this->db->sql("SELECT * FROM torrents WHERE filesize<=218 AND date='" . date('Y-m-d') . "'");
        }
    }

    /**
     * Выкачка картинок торрента
     *
     */
    public function torrents_images_download()
    {

        $tims = $this->db->sql("SELECT i.id, i.from, i.path, i.aka, t.film_id, t.id tor_id FROM torrent_images i
                LEFT JOIN torrents t ON t.id=i.torrent_id
                WHERE i.filesize<=218 AND i.date='" . date('Y-m-d') . "' ORDER BY i.id");

        unset($from_tim);
        foreach ($tims as $tim) {
            $from_tim[] = $tim['from'];
            if (!is_dir($this->toimg . '/images/film-' . $tim['film_id'] . '/' . $tim['tor_id']))
                mkdir($this->toimg . '/images/film-' . $tim['film_id'] . '/' . $tim['tor_id']);
        }

        $mcurl = new MCurl;
        $mcurl->threads = 100;
        $mcurl->timeout = 50000; //
        unset($results); // очищаем массив $results (если он использовался раньше где-то в коде)
        $mcurl->multiget($from_tim, $results);
        $y = 0;
        foreach ($results as $k => $v) {
            $f_name = $this->toimg . '/images/film-' . $tims[$k]['film_id'] . '/' . $tims[$k]['tor_id'] . '/img-' . $tims[$k]['id'] . '.jpg';
            if (file_put_contents($f_name, $v)) {
                $this->db->sql('UPDATE torrent_images SET filesize=' . filesize($f_name) . ' WHERE id="' . $tims[$k]['id'] . '"');
                echo "OK --- " . '/images/film-' . $tims[$k]['film_id'] . '/' . $tims[$k]['tor_id'] . '/img-' . $tims[$k]['id'] . '.jpg' . "\n";
                myflush();
            } else {
                $this->db->sql("INSERT INTO _log(type,text,date) VALUES('torr_imag','" . $f_name . "','" . date('Y-m-d') . "')");
            }
            $y++;
        }

    }


    /**
     * Получаем ссылки на фильльмы
     */
    public function getFilmLinks($pages)
    {
        $html = file_get_contents('http://fast-torrent.ru/new/all/1.html');
        $saw = new nokogiri($html);
        $paginator_ul = $saw->get('ul.paginator a')->toArray();
        $last_page = $paginator_ul[2]['#text'] ? $paginator_ul[2]['#text'] : 1;

        $end = $last_page;
        //Мультискачка  ######################################
        if (is_numeric($pages)) {
            $end = $pages;
        }

        $ho = 0;

        unset($pages);
        for ($i = $ho * $end + 1; $i <= $end * $ho + $end; $i++) {
            $pages[] = 'http://fast-torrent.ru/new/all/' . $i . '.html';
            echo "http://fast-torrent.ru/new/all/" . $i . ".html\n";
            myflush();
        }
        $ho++;


        while (count($results) < count($pages)) {
            $mcurl = new MCurl;
            $mcurl->threads = 50;
            $mcurl->timeout = 50000;
            unset($results);
            $mcurl->multiget($pages, $results);
            echo 'stranic - ' . count($pages) . ' iz -' . count($results);
            myflush();
        }

        //Это страницы с фльмами по 15 штук
        //Список ссылок на фильмы
        return $this->films_link_get($results);
    }

    public function parseMedia()
    {
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

    public function run($film_main_pages)
    {

        //Конфигггггг

        $set_films_by_category = true;
        $parse_images = true;


        //Мультискачка фильма
        //http://fasttorrent.ru/film/127-chasov.html
        //$film_main_pages = ['http://www.fast-torrent.ru/film/nachalo-1.html', 'http://www.fast-torrent.ru/film/nachalo-1.html'];

        $direct_urls = array_map(function ($el) {
            return $el['url'];
        }, $film_main_pages);


        $parsed = $this->parseFilmsByUrls($direct_urls);

        //file_put_contents(__DIR__ . '/../../temp/film.html', print_r($parsed, true));

        $total = count($parsed);
        $i = 1;
        foreach ($parsed as $film) {


            $this->storeFilm($film, $this->provider_id);
            echo "Saved " . $i . " / " . $total . "\n";
            myflush();

            $i++;
        }


        if ($parse_images) {
            $this->parseMedia();
        }
    }


    function __destruct()
    {

    }

}

?>