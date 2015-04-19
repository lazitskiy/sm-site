<?php

/**
 * User: vaso
 * Date: 29.03.15
 * Time: 16:39
 */
class Api extends F3instance
{

    public function __construct()
    {
        $this->apiServer = 'http://smotrach.ru';
    }

    /**
     * Добавим абслютные пути и пожмем массивы
     */
    public function prepareForOutput($lists)
    {
        foreach ($lists as &$row) {
            $row['poster'] = $this->apiServer . $row['poster'];
            $row['url'] = $this->apiServer . $row['url'];
        }

        return $lists;
    }

    /**
     * Увеличиваем счетчик скачивания торрента
     */
    public function countup()
    {
        $torrent_id = $this->get('PARAMS.torrent_id');

        if (!$torrent_id) {
            $return['status'] = 'error';
            $return['error'] = 'No hash';
            echo json_encode($return);
            return;
        }

        $torrent_model = new Axon('torrent');
        $torrent_model->load('id=' . $torrent_id);
        $torrent_model->downloads = $torrent_model->downloads + 1;
        $torrent_model->save();

        $film_model = new Axon('film');
        $film_model->load('id=' . $torrent_model->film_id);
        $film_model->downloads = $film_model->downloads + 1;
        $film_model->save();

        $return['status'] = 'ok';
        $return['data']['downloads'] = $torrent_model->downloads;

        echo json_encode($return);
        return;
    }
}