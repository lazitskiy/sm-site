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
    public static function getIds($_this, $type = 'movies')
    {
        $genre_ids = "'" . implode("','", $_this->genres[$type]['ids']) . "'";
        $limit = $_this->get('page_limit');

        $q = $_GET['q'] ? $_GET['q'] : '';

        $page = $_GET['page'] ? (int)$_GET['page'] : 1;
        $genre_id = $_GET['genre'] ? (int)$_GET['genre'] : 0;
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


        $sql = 'SELECT COUNT(DISTINCT f.id) total FROM film f
                LEFT JOIN film_category fc ON fc.film_id=f.id
                WHERE f.uploaded=1';

        if ($genre_id) {
            $sql .= ' AND fc.category_id=' . $genre_id;
        } else {
            $sql .= ' AND fc.category_id IN(' . $genre_ids . ')';
        }

        if ($q) {
            $sql .= ' AND f.aka_ru LIKE "%' . $q . '%"';
        }

        $count = $_this->db->sql($sql)[0]['total'];
        $return['total'] = $count;

        $paginator = (new pagination())->calculate_pages($count, $limit, $page);
        $return['paginator'] = $paginator;

        $sql = 'SELECT f.id FROM film f
                LEFT JOIN film_category fc ON fc.film_id=f.id
                WHERE uploaded=1';
        if ($genre_id) {
            $sql .= ' AND fc.category_id=' . $genre_id;
        } else {
            $sql .= ' AND fc.category_id IN(' . $genre_ids . ')';
        }
        if ($q) {
            $sql .= ' AND f.aka_ru LIKE "%' . $q . '%"';
        }
        $sql .= ' GROUP BY f.id
                ORDER BY ' . $order . ' ' . $paginator['limit'];
        $_ids = $_this->db->sql($sql);

        $ids = array_map(function ($el) {
            return $el['id'];
        }, $_ids);
        $return['ids'] = $ids;

        return $return;
    }
}