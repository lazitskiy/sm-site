<?php

/**
 * User: vaso
 * Date: 25.03.15
 * Time: 3:27
 */
class MovieController extends BaseController
{

    function __construct()
    {
        parent::__construct();
    }

    public function indexAction()
    {
        $film_id = array_pop(explode('-', $this->get('PARAMS.film_trans_id')));

        $this->set('title', 'Хуй');

        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movie/index.php');

    }


}