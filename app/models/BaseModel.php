<?php

/**
 * User: vaso
 * Date: 28.03.15
 * Time: 21:05
 */
class BaseModel extends F3instance
{
    public static function filesize_formatted($size)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
    }

    /**
     * @param $_this
     * @param string $type
     * Надо передать контекст
     */
    public static function getIds($_this, $type = 'movies', $params)
    {
        $genre_ids = "'" . implode("','", $_this->genres[$type]['ids']) . "'";
        $limit = $_this->get('page_limit');


        $q = $_GET['q'] ? $_GET['q'] : '';
        $page = $params['page'] ? $params['page'] : 1;

        /**
         * Условия параметрах более приоритетные
         */
        if ($params['genre']) {
            $genre_id = current(array_filter($_this->genres[$type]['items'], function ($el) use ($params) {
                return $el['url_clean'] == $params['genre'];
            }))['id'];
        }

        if ($params['year']) {
            $year = $params['year'];
        }
        if ($params['country_code']) {
            $country_code = $params['country_code'];
        }
        if ($params['bookmark']) {
            $bookmark = $params['bookmark'];
        }
        if ($params['actor']) {
            $actor = $params['actor'];
        }
        if ($params['rating']) {
            $rating = $params['rating'];
        }


        $order_by = $_GET['order_by'] ? $_GET['order_by'] : 'f.id DESC';

        switch ($order_by) {
            case 'latest':
            default:
                $order = 'f.id DESC';
                break;
            case 'oldest':
                $order = 'f.id ASC';
                break;
            case 'alpha':
                $order = 'f.aka_ru ASC';
                break;
        }
        $return['order_by'] = $order;

        $sql_start = 'SELECT COUNT(DISTINCT f.id) total FROM film f
                LEFT JOIN film_category fc ON fc.film_id=f.id';
        if ($country_code) {
            $sql_join_country = ' LEFT JOIN film_country fco ON fco.film_id = f.id LEFT JOIN country co ON fco.country_id = co.id';
            $sql_start .= $sql_join_country;
        }
        if ($bookmark) {
            $sql_join_bookmark = ' LEFT JOIN film_tag ft ON ft.film_id = f.id LEFT JOIN tag t ON ft.tag_id = t.id';
            $sql_start .= $sql_join_bookmark;
        }
        if ($actor) {
            $sql_join_actor = ' LEFT JOIN film_actor fa ON fa.film_id = f.id LEFT JOIN actor a ON fa.actor_id = a.id';
            $sql_start .= $sql_join_actor;
        }


        $condition = ' WHERE f.uploaded=1';
        if ($q) {
            $condition .= ' AND f.aka_ru LIKE "%' . $q . '%"';
        }
        if ($genre_id) {
            $condition .= ' AND fc.category_id=' . $genre_id;
        } else {
            $condition .= ' AND fc.category_id IN(' . $genre_ids . ')';
        }
        if ($year) {
            $ex = explode('-', $year);
            if (array_key_exists(1, $ex)) {
                if ($ex[1]) {
                    $condition .= ' AND f.year>=' . (int)$ex[0] . ' AND f.year<=' . ((int)$ex[0] + (int)$ex[1]);
                } else {
                    $condition .= ' AND f.year<=' . (int)$ex[0];
                }
            } else {
                $condition .= ' AND f.year=' . (int)$ex[0];
            }
        }
        if ($country_code) {
            $condition .= ' AND co.code="' . $country_code . '"';
        }
        if ($bookmark) {
            $condition .= ' AND t.url="' . $bookmark . '"';
        }
        if ($actor) {
            $condition .= ' AND a.aka_en="' . $actor . '"';
        }
        if ($rating) {
            $condition .= ' AND f.kinopoisk_rating IS NOT NULL AND f.kinopoisk_rating>=' . $rating;
        }


        $sql = $sql_start . $condition;
        $count = $_this->db->sql($sql)[0]['total'];
        $return['total'] = $count;

        $paginator = (new pagination())->calculate_pages($count, $limit, $page);
        $return['paginator'] = $paginator;


        $sql_start = 'SELECT f.id, f.aka_ru FROM film f LEFT JOIN film_category fc ON fc.film_id=f.id';
        $sql_start .= $sql_join_country;
        $sql_start .= $sql_join_bookmark;
        $sql_start .= $sql_join_actor;

        $sql_end = ' GROUP BY f.id ORDER BY ' . $order . ' ' . $paginator['limit'];
        $sql = $sql_start . $condition . $sql_end;

        $_ids = $_this->db->sql($sql);

        $ids = array_map(function ($el) {
            return $el['id'];
        }, $_ids);
        $return['ids'] = $ids;

        return $return;
    }


    public static function resultsetToArray($rows)
    {
        $films = [];
        foreach ($rows as $row) {
            $films[$row['fid']]['id'] = $row['fid'];
            $films[$row['fid']]['name_ru'] = $row['fname'];
            $films[$row['fid']]['name_en'] = $row['fname_en'];
            $films[$row['fid']]['name_full'] = $row['fname'] . '<br/>' . $row['fname_en'];
            $films[$row['fid']]['poster'] = '/static/poster/' . $row['poster_from'];
            $films[$row['fid']]['year'] = $row['year'];

            $rating = round($row['kinopoisk_rating'], 2);
            if (!$rating) {
                $rating = round($row['imdb_rating'], 2);
            }
            if (!$rating) {
                $rating = 'n/a';
            } else {
                $rating_int = $rating;
                $rating .= ' / 10';
            }

            $films[$row['fid']]['rating'] = $rating;
            $films[$row['fid']]['rating_int'] = $rating_int;
            $films[$row['fid']]['url'] = '/movie/' . $row['aka_trans'] . '-' . $row['fid'];

            if ($row['cid']) {
                $films[$row['fid']]['genres'][$row['cid']]['name'] = $row['cname'];
                $films[$row['fid']]['genres'][$row['cid']]['url'] = $row['curl'];
            }
        }
        return $films;
    }

    public static function parseParams($params, $delete = true)
    {
        $exs = explode('/', $params[0]);
        unset($exs[0]);
        if ($delete) {
            unset($exs[1]);
        }else{
            $parameters['base']=$exs[1];
        }

        foreach ($exs as $ex) {
            if (is_numeric($ex)) {
                $parameters['page'] = $ex;
            } elseif (strpos($ex, 'year') !== false) {
                $parameters['year'] = str_replace('year', '', $ex);
            } elseif (strpos($ex, 'rating') !== false) {
                $parameters['rating'] = str_replace('rating', '', $ex);
            } else {
                $parameters['genre'] = $ex;
            }
        }
        return $parameters;
    }

    public static function setUrl($base = null, $params = array())
    {
        if (!$base) {
            $base = self::parseParams(F3::ref('PARAMS'), false)['base'];
        }

        $parameters = self::parseParams(F3::ref('PARAMS'));

        if (array_key_exists('genre', $params)) {
            if (is_null($params['genre'])) {
                unset($parameters['genre']);
            } else {
                $parameters['genre'] = $params['genre'];
            }
        }
        if (array_key_exists('year', $params)) {
            if (is_null($params['year'])) {
                unset($parameters['year']);
            } else {
                $parameters['year'] = $params['year'];
            }
        }
        if (array_key_exists('rating', $params)) {
            if (is_null($params['rating'])) {
                unset($parameters['rating']);
            } else {
                $parameters['rating'] = $params['rating'];
            }
        }

        $hui[] = $base;
        if ($parameters['genre']) {
            $hui[] = $parameters['genre'];
        }
        if ($parameters['year']) {
            $hui[] = 'year' . $parameters['year'];
        }
        if ($parameters['rating']) {
            $hui[] = 'rating' . $parameters['rating'];
        }

        $hui = implode('/', $hui);
        return $hui;

    }
}