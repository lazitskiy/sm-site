<?php

/**
 * User: vaso
 * Date: 29.03.15
 * Time: 23:59
 */
class MoviesController extends F3instance
{
    public function indexAction()
    {
        $this->set('title', 'Хуй');


        $sql='SELECT COUNT(*) FROM film'


        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movies/index.php');

    }

}