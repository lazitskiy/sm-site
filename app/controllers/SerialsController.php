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

}