<?php

/**
 * User: vaso
 * Date: 29.03.15
 * Time: 23:59
 */
class MoviesController extends BaseController
{
    public function indexAction()
    {
        $this->set('title', 'Хуй');

        $genre_ids = "'" . implode("','", $this->genres['movies']['ids']) . "'";


        
        $limit = $this->get('page_limit');
        $page = $_GET['page'] ? (int)$_GET['page'] : 1;
        $genre_id = $_GET['genre'] ? (int)$_GET['genre'] : 0;

        $sql = 'SELECT COUNT(DISTINCT f.id) total FROM film f
                LEFT JOIN film_category fc ON fc.film_id=f.id
                WHERE f.uploaded=1';

        if ($genre_id) {
            $sql .= ' AND fc.category_id=' . $genre_id;
        } else {
            $sql .= ' AND fc.category_id IN(' . $genre_ids . ')';
        }

        $count = $this->db->sql($sql)[0]['total'];

        $paginator = (new pagination())->calculate_pages($count, $limit, $page);
        $sql = 'SELECT f.id FROM film f
                LEFT JOIN film_category fc ON fc.film_id=f.id
                WHERE uploaded=1';
        if ($genre_id) {
            $sql .= ' AND fc.category_id=' . $genre_id;
        } else {
            $sql .= ' AND fc.category_id IN(' . $genre_ids . ')';
        }
        $sql .= ' GROUP BY f.id
                ORDER BY f.id DESC ' . $paginator['limit'];

        $_ids = $this->db->sql($sql);

        $ids = array_map(function ($el) {
            return $el['id'];
        }, $_ids);


        $movies = MovieModel::getPreviewByIds($ids);


        $data['total'] = $count;
        $data['movies'] = $movies;
        $data['paginator'] = $paginator;
        $data['genres'] = $this->genres['movies']['items'];

        $this->set('data', $data);

        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');

    }

}