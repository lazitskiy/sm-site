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

        $popular_month = MovieModel::getPopular();
        $this->set('populars', $popular_month);


        $most_downloaded = MovieModel::mostDownloaded();
        $this->set('most_downloaded', $most_downloaded);


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
