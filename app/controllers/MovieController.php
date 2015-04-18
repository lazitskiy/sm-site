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
        if (!$film_id) {
            return;
        }

        $sql = '
        SELECT f.id fid, f.aka_ru fname, f.aka_en fname_en, f.reliz, f.last, f.description, f.spoiler, f.kinopoisk_id, f.imdb_id, f.poster_from,
        i.id iid, i.aka iurl, i.uploaded iuploaded,
        c.id cid, c.url curl, c.aka_ru cname,
        co.id coid, co.code, co.aka coname,
        t.id tid, t.url turl, t.aka_ru tname,
        a.id aid, fa.type atype, a.aka_en aurl, a.aka_ru aname
        FROM film f
        LEFT JOIN image i ON i.film_id = f.id
        LEFT JOIN film_category fc ON fc.film_id = f.id
        LEFT JOIN category c ON fc.category_id = c.id
        LEFT JOIN film_country fco ON fco.film_id = f.id
        LEFT JOIN country co ON fco.country_id = co.id
        LEFT JOIN film_tag ft ON ft.film_id = f.id
        LEFT JOIN tag t ON ft.tag_id = t.id
        LEFT JOIN film_actor fa ON fa.film_id = f.id
        LEFT JOIN actor a ON fa.actor_id = a.id
        WHERE f.id=' . $film_id;
        $rows = $this->db->sql($sql);


        $this->set('title', sprintf($this->get('_')['movie']['title'], $rows[0]['fname']));


        $images = [];
        $genres = [];
        $countries = [];
        $tags = [];
        $actors = [];


        $e = explode('/', $rows[0]['curl']);
        $video_type = $this->video_types[$e[1]];


        foreach ($rows as $row) {
            $film['id'] = $row['fid'];
            $film['name_ru'] = $row['fname'];
            $film['name_en'] = $row['fname_en'];
            $film['name_full'] = $row['fname'] . '<br/>' . $row['fname_en'];
            $film['poster'] = '/static/poster/' . $row['poster_from'];

            $film['reliz'] = $row['reliz'];
            $film['last'] = $row['last'];
            $film['kinopoisk_id'] = $row['kinopoisk_id'];
            $film['imdb_id'] = $row['imdb_id'];
            $film['description'] = $row['description'];
            $film['spoiler'] = $row['spoiler'];

            if ($row['iid']) {
                if ($row['iuploaded'] == 2) {
                    continue;
                }
                $images[$row['iid']]['url'] = $row['iurl'];
                $images[$row['iid']]['uploaded'] = $row['iuploaded'];
            }

            if ($row['cid']) {
                $genres[$row['cid']]['name'] = $row['cname'];

                $genre_url = current(explode('/', $row['curl']));
                $url = $video_type . '/genre/' . $genre_url;
                $genres[$row['cid']]['url'] = $url;
            }

            if ($row['coid']) {
                $countries[$row['coid']]['name'] = $row['coname'];
                $countries[$row['coid']]['url'] = $video_type . '/country/' . $row['code'];
            }

            if ($row['tid']) {
                $tags[$row['tid']]['name'] = $row['tname'];
                $tags[$row['tid']]['url'] = $video_type . '/bookmark/' . $row['turl'];
            }

            if ($row['aid']) {
                $type = $row['atype'];

                $actors[$type][$row['aid']]['name'] = $row['aname'];
                $actors[$type][$row['aid']]['url'] = $video_type . '/actor/' . $row['aurl'];
            }
        }

        $film['reliz_year'] = $video_type . '/year/' . array_pop(explode('.', $film['reliz']));


        $film['images'] = $images;
        $film['genres'] = $genres;
        $film['countries'] = $countries;
        $film['tags'] = $tags;
        $film['actors'] = $actors;


        if ($images) {
            ImageModel::download($images, 1, $film_id);
        }


        $sql = '
          SELECT q.aka qname, q.description qdescription, t.sezon, t.perevod, t.size, t.date_add, t.downloads, t.seaders, t.leachers, t.name, t.uploaded, t.provider_torrent_id,t.hash
          FROM torrent t
          LEFT JOIN quality q ON q.id=t.quality_id
          WHERE t.film_id=' . $film_id;
        $rows = $this->db->sql($sql);

        foreach ($rows as $row) {
            if ($row['uploaded'] == 2) {
                continue;
            }
            $torrents[] = [
                'quality' => $row['qname'],
                'quality_desc' => $row['qdescription'],
                'sezon' => $row['sezon'],
                'perevod' => $row['perevod'],
                'size' => BaseModel::filesize_formatted($row['size']),
                'date_add' => date('d.m.Y', $row['date_add']),
                'downloads' => $row['downloads'],
                'seaders' => $row['seaders'],
                'leachers' => $row['leachers'],
                'name' => $row['name'],
                'provider_torrent_id' => $row['provider_torrent_id'],
                'uploaded' => $row['uploaded'],
                'hash' => $row['hash']

            ];
        }

        if ($torrents) {
            TorrentModel::download($torrents, 1, $film_id);
        }
        $film['torrents'] = $torrents;

        //TorrentModel::getInfo($torrents[0]['url'], 1);


        $this->set('film', $film);

        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/movie/index.php');

    }


}