<?php

/**
 * Created by PhpStorm.
 * User: vas
 * Date: 31.03.2015
 * Time: 14:15
 */
class SerialsController extends BaseController
{

    public function indexAction($page = 1)
    {
        $params = BaseModel::parseParams($this->get('PARAMS'));

        /**
         * СЕО
         */
        $this->set('title', $this->get('_')['serials']['title']);

        $data = MovieModel::getIds($this, 'serials', $params);
        $movies = MovieModel::getPreviewByIds($data['ids']);

        $data['total'] = $data['total'];
        $data['movies'] = $movies;
        $data['paginator'] = $data['paginator'];
        $data['genres'] = $this->genres['serials']['items'];

        $this->set('data', $data);

        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');
    }


    public function countryAction()
    {
        $country_code = $this->get('PARAMS.country_code');

        $data = MovieModel::getIds($this, 'serials', ['country_code' => $country_code]);
        $movies = MovieModel::getPreviewByIds($data['ids'], $data['order_by']);

        /**
         * СЕО
         */

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