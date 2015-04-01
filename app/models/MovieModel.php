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
    public static function getPreviewByIds(array $film_id)
    {

        $ids = "'" . implode("','", $film_id) . "'";

        $sql = '
        SELECT f.id fid, f.aka_ru fname, f.aka_en fname_en, f.aka_trans, f.kinopoisk_id, f.imdb_id, f.poster_from, f.year,f.kinopoisk_rating,f.imdb_rating,
        c.id cid, c.url curl, c.aka_ru cname
        FROM film f
        LEFT JOIN film_category fc ON fc.film_id = f.id
        LEFT JOIN category c ON fc.category_id = c.id
        WHERE f.id IN(' . $ids . ') ORDER BY f.id DESC';
        $rows = F3::ref('DB')->sql($sql);

        $films = [];
        foreach ($rows as $row) {
            $films[$row['fid']]['id'] = $row['fid'];
            $films[$row['fid']]['name_ru'] = $row['fname'];
            $films[$row['fid']]['name_en'] = $row['fname_en'];
            $films[$row['fid']]['name_full'] = $row['fname'] . '<br/>' . $row['fname_en'];
            $films[$row['fid']]['poster'] = '/static/poster/' . $row['poster_from'];
            $films[$row['fid']]['year'] = $row['year'];

            $raiting = round($row['kinopoisk_rating'], 2);
            if (!$raiting) {
                $raiting = round($row['imdb_rating'], 2);
            }
            if (!$raiting) {
                $raiting = 'n/a';
                $raiting_int = $raiting;
            } else {
                $raiting .= ' / 10';
            }

            $films[$row['fid']]['rating'] = $raiting;
            $films[$row['fid']]['rating_int'] = $raiting_int;
            $films[$row['fid']]['url'] = '/movie/' . $row['aka_trans'] . '-' . $row['fid'];

            if ($row['cid']) {
                $films[$row['fid']]['genres'][$row['cid']]['name'] = $row['cname'];
                $films[$row['fid']]['genres'][$row['cid']]['url'] = $row['curl'];
            }
        }

        return $films;
    }

}