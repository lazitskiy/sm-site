<?php

/**
 * Created by PhpStorm.
 * User: vas
 * Date: 19.04.2015
 * Time: 16:44
 */
class ApiMovies extends Api
{

    public function __construct()
    {
        parent::__construct();
        header('Content-Type:application/json');
    }

    public function getListAction()
    {

        $popular = MovieModel::getPopular(30, 40);
        $movies = $this->prepareForOutput($popular);

        $data['status'] = 'ok';
        $data['status_text'] = 'Succesful';
        $data['data']['movies'] = $movies;
        echo json_encode($data);
    }

}