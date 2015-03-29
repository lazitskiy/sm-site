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
            $film['poster'] = '/static/poster/' . $film['poster'];
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
