<?php

/**
 * User: vaso
 * Date: 29.03.15
 * Time: 16:39
 */
class Api extends F3instance
{
    /**
     * Увеличиваем счетчик скачивания торрента
     */
    public function countup($d)
    {
        $hash = $this->get('PARAMS.hash');
        if (!$hash) {
            $return['status'] = 'error';
            $return['error'] = 'No hash';
            echo json_encode($return);
            return;
        }

        $torrent_model = new Axon('torrent');
        $torrent_model->load('hash="' . $hash . '"');
        $torrent_model->downloads = $torrent_model->downloads + 1;
        $torrent_model->save();
        $return['status'] = 'ok';
        $return['data']['downloads'] = $torrent_model->downloads;

        echo json_encode($return);
        return;
    }
}