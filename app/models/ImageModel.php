<?php

/**
 * User: vaso
 * Date: 28.03.15
 * Time: 21:05
 */
class ImageModel extends BaseModel
{
    public static function download($images, $provider_id = false, $film_id = false)
    {

        if ($provider_id === false) {
            return;
        }
        if ($film_id === false) {
            return;
        }

        if ($provider_id == 1) {
            $prefix = 'http://media.fast-torrent.ru/media/files/';
        }
        foreach ($images as $image) {
            if ($image['uploaded'] == 1) {
                continue;
            }

            $image_urls_backup[] = strtolower($image['url']);
            $image_urls[] = $prefix . urlencode($image['url']);
        }

        if (!$image_urls) {
            return;
        }

        $mcurl = new MCurl();
        $mcurl->threads = 100;
        $mcurl->timeout = 50000;
        $mcurl->multiget($image_urls, $results);
        foreach ($results as $k => $image) {
            //  Типа картинка загрузилась
            $image_name = $image_urls_backup[$k];

            if (strlen($image) > 1024 * 5) {
                $file_path = dirname(__FILE__) . '/../../static/images/' . $image_urls_backup[$k];
                $dirname = dirname($file_path);

                if (!is_dir($dirname)) {
                    mkdir($dirname, 0777, true);
                }
                file_put_contents($file_path, $image);
                $status = 1;
            } else {
                $status = 2;
            }

            $image_model = new Axon('image');
            $image_model->load('aka="' . $image_name . '" AND film_id=' . $film_id);
            $image_model->uploaded = $status;
            $image_model->save();

        }
    }
}