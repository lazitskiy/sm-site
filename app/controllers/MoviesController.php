<?php

/**
 * User: vaso
 * Date: 29.03.15
 * Time: 23:59
 */
class MoviesController extends BaseController
{

    public function indexAction($page = 1)
    {
        /**
         * СЕО
         */
        $this->set('title', $this->get('_')['movies']['index']['title']);

        $data = MovieModel::getIds($this, 'movies', ['page' => $page]);
        $movies = MovieModel::getPreviewByIds($data['ids'], $data['order_by']);

        $data['total'] = $data['total'];
        $data['movies'] = $movies;
        $data['paginator'] = $data['paginator'];
        $data['genres'] = $this->genres['movies']['items'];
        $data['url'] = 'movies';

        $this->set('data', $data);

        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');

    }

    public function genreAction()
    {
        $genre_url = $this->get('PARAMS.genre_url');
        $page = $this->get('PARAMS.page');
        if (is_numeric($genre_url)) {
            return $this->indexAction($genre_url);
        }

        $allow_urls = $this->genres['movies']['urls'];

        if (!in_array($genre_url, $allow_urls)) {
            return $this->make404('хуй');
        }
        $genre = array_filter($this->genres['movies']['items'], function ($el) use ($genre_url) {
            return $el['url'] == $genre_url;
        });

        /**
         * СЕО
         */
        $this->set('title', sprintf($this->get('_')['movies']['genre']['title'], current($genre)['aka_ru']));


        $genre_id = array_search($genre_url, $allow_urls);
        $data = MovieModel::getIds($this, 'movies', ['genre_id' => $genre_id,'page'=>$page]);
        $movies = MovieModel::getPreviewByIds($data['ids'], $data['order_by']);


        $data['total'] = $data['total'];
        $data['movies'] = $movies;
        $data['paginator'] = $data['paginator'];
        $data['genres'] = $this->genres['movies']['items'];;
        $data['url'] = 'movies/'.$genre_url;


        $this->set('data', $data);



        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');
    }

    public function yearAction()
    {
        $year = $this->get('PARAMS.year');
        /**
         * СЕО
         */
        $this->set('title', sprintf($this->get('_')['movies']['year']['title'], $year));

        $data = MovieModel::getIds($this, 'movies', ['year' => $year]);
        $movies = MovieModel::getPreviewByIds($data['ids'], $data['order_by']);

        $data['total'] = $data['total'];
        $data['movies'] = $movies;
        $data['paginator'] = $data['paginator'];
        $data['genres'] = $this->genres['movies']['items'];;
        $data['controllerName'] = $this->controllerName;

        $this->set('data', $data);

        $this->set('disable', ['year']);
        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');
    }

    public function countryAction()
    {
        $country_code = $this->get('PARAMS.country_code');

        $data = MovieModel::getIds($this, 'movies', ['country_code' => $country_code]);
        $movies = MovieModel::getPreviewByIds($data['ids'], $data['order_by']);

        /**
         * СЕО
         */

        $data['total'] = $data['total'];
        $data['movies'] = $movies;
        $data['paginator'] = $data['paginator'];
        $data['genres'] = $this->genres['movies']['items'];;
        $data['controllerName'] = $this->controllerName;

        $this->set('data', $data);

        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');
    }

    public function bookmarkAction()
    {
        $bookmark = $this->get('PARAMS.bookmark');

        $data = MovieModel::getIds($this, 'movies', ['bookmark' => $bookmark]);
        $movies = MovieModel::getPreviewByIds($data['ids'], $data['order_by']);

        $data['total'] = $data['total'];
        $data['movies'] = $movies;
        $data['paginator'] = $data['paginator'];
        $data['genres'] = $this->genres['movies']['items'];;
        $data['controllerName'] = $this->controllerName;

        $this->set('data', $data);

        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');
    }

    public function actorAction()
    {
        $actor = $this->get('PARAMS.actor');

        $data = MovieModel::getIds($this, 'movies', ['actor' => $actor]);
        $movies = MovieModel::getPreviewByIds($data['ids'], $data['order_by']);


        $data['total'] = $data['total'];
        $data['movies'] = $movies;
        $data['paginator'] = $data['paginator'];
        $data['genres'] = $this->genres['movies']['items'];;
        $data['controllerName'] = $this->controllerName;

        $this->set('data', $data);

        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');
    }

}