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

        $params = BaseModel::parseParams($this->get('PARAMS'));

        /**
         * СЕО
         */
        $this->set('title', $this->get('_')['movies']['index']['title']);

        $data = MovieModel::getIds($this, 'movies', $params);
        $movies = MovieModel::getPreviewByIds($data['ids'], $data['order_by']);

        $data['total'] = $data['total'];
        $data['movies'] = $movies;
        $data['paginator'] = $data['paginator'];
        $data['genres'] = $this->genres['movies']['items'];

        $this->set('data', $data);

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