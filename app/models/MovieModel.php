<?php

/**
 * User: vaso
 * Date: 31.03.15
 * Time: 4:44
 */
class MovieModel extends BaseModel
{

    /**
     * @param $film_id
     * return filb by id or array
     */
    public static function getPreviewByIds(array $film_id, $order_by = 'f.id DESC')
    {

        $ids = "'" . implode("','", $film_id) . "'";

        $sql = '
        SELECT f.id fid, f.aka_ru fname, f.aka_en fname_en, f.aka_trans, f.kinopoisk_id, f.imdb_id, f.poster_from, f.year,f.kinopoisk_rating,f.imdb_rating,
        c.id cid, c.url curl, c.aka_ru cname
        FROM film f
        LEFT JOIN film_category fc ON fc.film_id = f.id
        LEFT JOIN category c ON fc.category_id = c.id
        WHERE f.id IN(' . $ids . ') ORDER BY ' . $order_by;
        $rows = F3::ref('DB')->sql($sql);
        $films = MovieModel::resultsetToArray($rows);

        return $films;
    }


    public static function getPopular($days = 15, $limit = 4)
    {
        $sql = '
        SELECT f.id fid, f.aka_ru fname, f.aka_en fname_en, f.aka_trans, f.kinopoisk_id, f.imdb_id, f.poster_from, f.year,f.kinopoisk_rating,f.imdb_rating, f.downloads,
        c.id cid, c.url curl, c.aka_ru cname
        FROM film f
        LEFT JOIN film_category fc ON fc.film_id = f.id
        LEFT JOIN category c ON fc.category_id = c.id
        WHERE UNIX_TIMESTAMP(NOW())-3600*24*' . $days . ' < date_added
        AND kinopoisk_rating>0
        ORDER BY f.kinopoisk_rating DESC LIMIT ' . $limit * 6;
        $rows = F3::ref('DB')->sql($sql);

        $films = MovieModel::resultsetToArray($rows);
        return array_chunk($films, $limit)[0];

    }

    public static function mostDownloaded($days = 120, $limit = 8)
    {
        $sql = '
        SELECT f.id fid, f.aka_ru fname, f.aka_en fname_en, f.aka_trans, f.kinopoisk_id, f.imdb_id, f.poster_from, f.year,f.kinopoisk_rating,f.imdb_rating, f.downloads,
        c.id cid, c.url curl, c.aka_ru cname
        FROM film f
        LEFT JOIN film_category fc ON fc.film_id = f.id
        LEFT JOIN category c ON fc.category_id = c.id
        WHERE UNIX_TIMESTAMP(NOW())-3600*24*' . $days . ' < date_added
        ORDER BY f.downloads DESC LIMIT ' . $limit * 6;
        $rows = F3::ref('DB')->sql($sql);
        $films = MovieModel::resultsetToArray($rows);
        return array_chunk($films, $limit)[0];

    }

}