<?php

/**
 * User: vaso
 * Date: 29.03.15
 * Time: 12:18
 */
class TorrentModel extends BaseModel
{
    public static function getInfo($path, $provider_id = false)
    {
        if ($provider_id === false) {
            return;
        }
        /* if ($film_id === false) {
             return;
         }*/

        if ($provider_id == 1) {
            $prefix = 'http://fast-torrent.ru/download/torrent/';
        }

        $path = $prefix . $path;


        $torrent = new Torrent($path);
        //vvd($torrent);

    }

    public static function download($torrents, $provider_id = false, $film_id = false)
    {

        if ($provider_id === false) {
            return;
        }
        if ($film_id === false) {
            return;
        }

        if ($provider_id == 1) {
            $prefix = 'http://fast-torrent.ru/download/torrent/';
        }
        foreach ($torrents as $torrent) {
            if ($torrent['uploaded'] == 1) {
                continue;
            }

            $torrent_urls_backup[] = $torrent['provider_torrent_id'];
            $torrent_urls[] = $prefix . urlencode($torrent['url']);
        }

        if (!$torrent_urls) {
            return;
        }

        $mcurl = new MCurl();
        $mcurl->threads = 10;
        $mcurl->timeout = 50000;
        //unset($results);
        //vvd($torrent_urls);
        $mcurl->multiget($torrent_urls, $results);
        foreach ($results as $k => $torrent) {
            //  Типа торрент загрузилась
            $provider_torrent_id = $torrent_urls_backup[$k];
            $t = new Torrent($torrent);
            $hash = $t->hash_info();

            if ($hash) {
                $file_path = dirname(__FILE__) . '/../../static/download/' . $film_id . '/' . $hash . '.torrent';
                $dirname = dirname($file_path);

                if (!is_dir($dirname)) {
                    mkdir($dirname, 0777, true);
                }
                file_put_contents($file_path, $torrent);
                $status = 1;
            } else {
                $status = 2;
            }

            $torrent_model = new Axon('torrent');
            $torrent_model->load('provider_torrent_id=' . $provider_torrent_id . ' AND film_id=' . $film_id);
            $torrent_model->uploaded = $status;
            $torrent_model->hash = $hash;
            $torrent_model->save();

        }
    }


}