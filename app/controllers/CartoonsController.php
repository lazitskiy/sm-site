<?php

/**
 * User: vaso
 * Date: 01.04.15
 * Time: 0:14
 */
class CartoonsController extends BaseController
{
    public function indexAction()
    {
        $this->set('title', 'Хуй');

        $data = MovieModel::getIds($this, 'cartoons');
        $movies = MovieModel::getPreviewByIds($data['ids']);

        $data['total'] = $data['total'];
        $data['movies'] = $movies;
        $data['paginator'] = $data['paginator'];
        $data['genres'] = $this->genres['cartoons']['items'];

        $this->set('data', $data);

        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');
    }
}