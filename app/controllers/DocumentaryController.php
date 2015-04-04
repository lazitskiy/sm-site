<?php

/**
 * User: vaso
 * Date: 01.04.15
 * Time: 0:16
 */
class DocumentaryController extends BaseController
{

    public function indexAction()
    {
        $this->set('title', 'Хуй');

        $data = MovieModel::getIds($this, 'documentary');
        $movies = MovieModel::getPreviewByIds($data['ids']);

        $data['total'] = $data['total'];
        $data['movies'] = $movies;
        $data['paginator'] = $data['paginator'];
        $data['genres'] = $this->genres['documentary']['items'];

        $this->set('data', $data);

        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');
    }

    public function genreAction()
    {
        $genre_url = $this->get('PARAMS.genre_url');
        $allow_urls = $this->genres['documentary']['urls'];

        if (!in_array($genre_url, $allow_urls)) {
            return $this->make404('хуй');
        }
        $genre_id = array_search($genre_url, $allow_urls);

        $data = MovieModel::getIds($this, 'documentary', ['genre_id' => $genre_id]);
        $movies = MovieModel::getPreviewByIds($data['ids'], $data['order_by']);

        $data['total'] = $data['total'];
        $data['movies'] = $movies;
        $data['paginator'] = $data['paginator'];
        $data['genres'] = $this->genres['movies']['items'];

        $this->set('data', $data);

        $this->set('disable', ['genre']);
        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');

    }
}