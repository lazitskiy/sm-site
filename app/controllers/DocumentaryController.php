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
        $params = BaseModel::parseParams($this->get('PARAMS'));

        /**
         * СЕО
         */
        $this->set('title', $this->get('_')['documentary']['title']);

        $data = MovieModel::getIds($this, 'documentary', $params);
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
        $data['genres'] = $this->genres['documentary']['items'];

        $this->set('data', $data);

        $this->set('disable', ['genre']);
        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');
    }

    public function yearAction()
    {
        $year = $this->get('PARAMS.year');

        $data = MovieModel::getIds($this, 'documentary', ['year' => $year]);
        $movies = MovieModel::getPreviewByIds($data['ids'], $data['order_by']);

        $data['total'] = $data['total'];
        $data['movies'] = $movies;
        $data['paginator'] = $data['paginator'];
        $data['genres'] = $this->genres['documentary']['items'];

        $this->set('data', $data);

        $this->set('disable', ['year']);
        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');
    }

    public function countryAction()
    {
        $country_code = $this->get('PARAMS.country_code');

        $data = MovieModel::getIds($this, 'documentary', ['country_code' => $country_code]);
        $movies = MovieModel::getPreviewByIds($data['ids'], $data['order_by']);

        $data['total'] = $data['total'];
        $data['movies'] = $movies;
        $data['paginator'] = $data['paginator'];
        $data['genres'] = $this->genres['documentary']['items'];

        $this->set('data', $data);

        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');
    }

    public function bookmarkAction()
    {
        $bookmark = $this->get('PARAMS.bookmark');

        $data = MovieModel::getIds($this, 'movies', ['documentary' => $bookmark]);
        $movies = MovieModel::getPreviewByIds($data['ids'], $data['order_by']);

        $data['total'] = $data['total'];
        $data['movies'] = $movies;
        $data['paginator'] = $data['paginator'];
        $data['genres'] = $this->genres['documentary']['items'];

        $this->set('data', $data);

        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');
    }

    public function actorAction()
    {
        $actor = $this->get('PARAMS.actor');

        $data = MovieModel::getIds($this, 'documentary', ['actor' => $actor]);
        $movies = MovieModel::getPreviewByIds($data['ids'], $data['order_by']);

        $data['total'] = $data['total'];
        $data['movies'] = $movies;
        $data['paginator'] = $data['paginator'];
        $data['genres'] = $this->genres['documentary']['items'];

        $this->set('data', $data);

        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');
    }
}