<?php

class IndexController extends BaseController
{
    function __construct()
    {
        parent::__construct();
    }


    public function indexAction()
    {
        $this->set('title', 'Хуй');


        $cats = $this->db->sql('SELECT * FROM category ORDER BY aka_ru');
        $this->set('cats', $cats);

        $sql = 'SELECT f.id,
                f.poster_from poster,
                f.aka_ru,
                f.aka_en,
                f.aka_trans
                FROM film f
                WHERE uploaded=1
                ORDER BY RAND()
                LIMIT 4
            ';
        $films = $this->db->sql($sql);

        foreach ($films as $film) {
            $film['id'] = $film['id'];
            $film['poster'] = 'http://fast-torrent.ru/media/files/' . $film['poster'];
            $film['aka_ru'] = $film['aka_ru'];
            $film['aka_en'] = $film['aka_en'];
            $film['aka_trans'] = $film['aka_trans'];
            $film['url'] = '/movie/' . $film['aka_trans'] . '-' . $film['id'];
            $arr_films[] = $film;
        }
        $this->set('populars', $arr_films);

        $this->set('last_uploads', $arr_films);
        $this->set('soons', $arr_films);



        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/index/index.php');
    }

    //Категория
    public function cats()
    {
        $this->set('title', 'Кино торрент');


        $cat_alias = $this->get('PARAMS.cat_alias');
        $page = $this->get('PARAMS.page') ?: 1;
        $limit = $this->get('page_limit');
        $limitstart = $page ? ($page - 1) * $limit : 0;

        $params['cat_alias'] = hl($cat_alias);
        $sql = 'SELECT f.id, CONCAT("/images/film","-",f.id,"/img") as poster, f.aka_ru, f.aka_en, aka_trans, f.reliz, f.past, f.descr, c.film_count
            FROM category c
            LEFT JOIN film_category_xref xref ON xref.category_id = c.id
            LEFT JOIN films f ON f.id=xref.film_id
            WHERE c.aka_en="' . hl($cat_alias) . '"
            LIMIT ' . $limitstart . ',' . $limit . '
            ';

        $films = $this->db->sql($sql);
        foreach ($films as $k => $v) {
            $genres = $this->db->sql('SELECT aka_ru, LOWER(aka_en) aka_en FROM category c LEFT JOIN film_category_xref x ON x.category_id=c.id WHERE x.film_id=' . $v['id']);
            $films[$k]['genres'] = $genres;
        }
        $params['films'] = $films;


        $p = new pagination();
        $paginator = $p->calculate_pages($films[0]['film_count'], $limit, $page);
        $this->set('pagination', $paginator);
        $this->set('params', $params);
        echo $this->render('/app/view/index/cat.php');

    }

    //Фильм
    public function film()
    {
        $params['cat_alias'] = $this->get('PARAMS.cat_alias');
        $film_data = explode('-', $this->get('PARAMS.film_data'));
        $params['film_id'] = $film_data[0];
        $params['film_alias'] = str_replace($film_data[0] . '-', '', $this->get('PARAMS.film_data'));

        $sql = 'SELECT f.id, f.aka_ru, f.aka_en, f.aka_trans, f.reliz, f.past, f.descr, f.rating_kinopoisk, f.rating_imdb, i.path, i.aka
            FROM films f
            LEFT JOIN film_images i ON i.film_id=f.id
            WHERE f.id=' . $params['film_id'];

        $film = $this->db->sql($sql);
        $params['film'] = $film;

        //Титле страницы
        $this->set('title', $film[0]['aka_ru']);

        //Жанры
        $genres = $this->db->sql('SELECT g.aka_ru, LOWER(g.aka_en) aka_en FROM category g
            LEFT JOIN film_category_xref x ON x.category_id = g.id
            WHERE x.film_id=' . $params['film_id']);
        if ($genres) {
            $params['film']['genres'] = $genres;
        }
        //Компании
        $company = $this->db->sql('SELECT c.aka, c.ft_href FROM companies c
            LEFT JOIN film_company_xref x ON x.company_id = c.id
            WHERE x.film_id=' . $params['film_id']);
        if ($company) {
            $params['film']['companies'] = $company;
        }
        //Каналы
        $channels = $this->db->sql('SELECT c.aka, c.ft_href FROM channels c
            LEFT JOIN film_channel_xref x ON x.channel_id = c.id
            WHERE x.film_id=' . $params['film_id']);
        if ($channels) {
            $params['film']['channels'] = $channels;
        }

        //Актеры продюсеры режиссеры
        $peoples = $this->db->sql('SELECT a.aka_ru, LOWER(a.aka_en) aka_en, x.is_dir FROM actors a
            LEFT JOIN film_actor_xref x ON x.actor_id = a.id
            WHERE x.film_id=' . $params['film_id']);
        if ($peoples) {
            foreach ($peoples as $p) {
                $humans[$p['is_dir']][] = array('aka_ru' => $p['aka_ru'], 'aka_en' => $p['aka_en']);
            }
            $params['film']['peoples'] = $humans;
        }

        //Торренты
        $torrents = $this->db->sql('SELECT t.id, t.quality, q.descr qd, t.perevod, t.size, t.date_add, t.downloads, t.seaders, t.leachers, t.to, t.aka
            FROM torrents t
            LEFT JOIN quality q ON q.id = t.quality_id
            WHERE t.film_id=' . $params['film_id'] . ' ORDER BY t.downloads DESC
            ');


        foreach ($torrents as $tor) {
            if ($id != $tor['id']) unset($temp_imgs[$id]);
            $temp_imgs[$tor['id']][] = array('img_aka' => $tor['img_aka'], 'img_path' => $tor['img_path']);
            $temp[$tor['id']] = array('tor' => $tor, 'images' => $temp_imgs);
            $id = $tor['id'];
        }

        $params['film']['torrents'] = $temp;

        $this->set('params', $params);


        echo $this->render('/app/view/index/film.php');
    }


    public function search()
    {
        $q = $this->get('PARAMS.0');
        $q = preg_replace('#(.+\?)(.+)(\.html)#', '$2', $q);

        $this->set('title', 'Поиск ' . $q);
        echo $this->render($this->get('_header'));

        if (strlen($q) < 5) {
            die('короткий запрос');
        } else {

            $sql = 'SELECT f.id, CONCAT("/images/film","-",f.id,"/img") as poster, f.aka_ru, f.aka_en, f.aka_trans, f.reliz, f.past, f.descr
                FROM films f 
                WHERE f.aka_ru LIKE "%' . $q . '%"
                ORDER BY f.id DESC
                ';
            $films = $this->db->sql($sql);

            foreach ($films as $k => $v) {
                $genres = $this->db->sql('SELECT * FROM category c LEFT JOIN film_category_xref x ON x.category_id=c.id WHERE x.film_id=' . $v['id']);
                $films[$k]['genres'] = $genres;
            }
            $this->set('films', $films);
        }


        echo $this->render('/app/view/index/index.php');
    }


}