<?php

/**
 * Created by PhpStorm.
 * User: vas
 * Date: 31.03.2015
 * Time: 14:15
 */
class SerialsController extends BaseController
{

    public function indexAction()
    {
        $this->set('title', 'Хуй');

        $data = MovieModel::getIds($this, 'serials');
        $movies = MovieModel::getPreviewByIds($data['ids']);

        $data['total'] = $data['total'];
        $data['movies'] = $movies;
        $data['paginator'] = $data['paginator'];
        $data['genres'] = $this->genres['serials']['items'];

        $this->set('data', $data);

        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');
    }

    public function genreAction()
    {
        $genre_url = $this->get('PARAMS.genre_url');
        $allow_urls = $this->genres['serials']['urls'];

        if (!in_array($genre_url, $allow_urls)) {
            return $this->make404('хуй');
        }
        $genre_id = array_search($genre_url, $allow_urls);

        $data = MovieModel::getIds($this, 'serials', ['genre_id' => $genre_id]);
        $movies = MovieModel::getPreviewByIds($data['ids'], $data['order_by']);

        $data['total'] = $data['total'];
        $data['movies'] = $movies;
        $data['paginator'] = $data['paginator'];
        $data['genres'] = $this->genres['serials']['items'];

        $this->set('data', $data);

        $this->set('disable', ['genre']);
        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');

    }


    public function yearAction()
    {
        $year = $this->get('PARAMS.year');

        $data = MovieModel::getIds($this, 'serials', ['year' => $year]);
        $movies = MovieModel::getPreviewByIds($data['ids'], $data['order_by']);

        $data['total'] = $data['total'];
        $data['movies'] = $movies;
        $data['paginator'] = $data['paginator'];
        $data['genres'] = $this->genres['serials']['items'];

        $this->set('data', $data);

        $this->set('disable', ['year']);
        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');
    }

    public function countryAction()
    {
        $country_code = $this->get('PARAMS.country_code');

        $data = MovieModel::getIds($this, 'serials', ['country_code' => $country_code]);
        $movies = MovieModel::getPreviewByIds($data['ids'], $data['order_by']);

        $data['total'] = $data['total'];
        $data['movies'] = $movies;
        $data['paginator'] = $data['paginator'];
        $data['genres'] = $this->genres['serials']['items'];

        $this->set('data', $data);

        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');
    }

    public function bookmarkAction()
    {
        $bookmark = $this->get('PARAMS.bookmark');

        $data = MovieModel::getIds($this, 'serials', ['bookmark' => $bookmark]);
        $movies = MovieModel::getPreviewByIds($data['ids'], $data['order_by']);

        $data['total'] = $data['total'];
        $data['movies'] = $movies;
        $data['paginator'] = $data['paginator'];
        $data['genres'] = $this->genres['serials']['items'];

        $this->set('data', $data);

        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');
    }

    public function actorAction()
    {
        $actor = $this->get('PARAMS.actor');

        $data = MovieModel::getIds($this, 'serials', ['actor' => $actor]);
        $movies = MovieModel::getPreviewByIds($data['ids'], $data['order_by']);

        $data['total'] = $data['total'];
        $data['movies'] = $movies;
        $data['paginator'] = $data['paginator'];
        $data['genres'] = $this->genres['serials']['items'];

        $this->set('data', $data);

        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');
    }

}