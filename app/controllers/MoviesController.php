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

        // config.cfg
        $limit = $this->get('page_limit');
        $page = $_GET['page'] ? (int)$_GET['page'] : 1;

        $sql = 'SELECT COUNT(*) total FROM film WHERE uploaded=1';
        $count = $this->db->sql($sql)[0]['total'];

        $paginator = (new pagination())->calculate_pages($count, $limit, $page);

        $sql = 'SELECT id FROM film WHERE uploaded=1  ORDER BY id DESC ' . $paginator['limit'];
        $_ids = $this->db->sql($sql);

        $ids = array_map(function ($el) {
            return $el['id'];
        }, $_ids);
        $movies = MovieModel::getPreviewByIds($ids);

        $this->set('total', $count);
        $this->set('movies', $movies);
        $this->set('paginator', $paginator);

        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');

    }

}