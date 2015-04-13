<?php

/**
 * User: vaso
 * Date: 23.03.15
 * Time: 0:20
 */
class BaseController extends F3instance
{

    public $db;
    protected $trans;
    protected $space;

    function __construct()
    {

        $this->db = $this->get('DB');

        $video_types = [
            '' => 'movies',//important
            'multfilm' => 'cartoons',
            'tv' => 'serials',
            'music' => 'music',
            'documentary' => 'documentary'
        ];
        $_cats = $this->db->sql('SELECT * FROM category ORDER BY aka_ru');
        foreach ($_cats as $cat) {
            $url = explode('/', $cat['url']);
            $genres[$video_types[$url[1]]]['items'][] = $cat;
            $genres[$video_types[$url[1]]]['ids'][] = $cat['id'];
            $genres[$video_types[$url[1]]]['urls'][$cat['id']] = current(explode('/', $cat['url']));
        }

        $this->video_types = $video_types;
        $this->genres = $genres;

        $this->trans = array(" " => "-", "а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ё" => "yo", "ж" => "j", "з" => "z", "и" => "i", "й" => "i", "к" => "k", "л" => "l", "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "у" => "y", "ф" => "f", "х" => "h", "ц" => "c", "ч" => "ch", "ш" => "sh", "щ" => "sh", "ы" => "i", "э" => "e", "ю" => "u", "я" => "ya", "А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Д" => "D", "Е" => "E", "Ё" => "Yo", "Ж" => "J", "З" => "Z", "И" => "I", "Й" => "I", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N", "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T", "У" => "Y", "Ф" => "F", "Х" => "H", "Ц" => "C", "Ч" => "Ch", "Ш" => "Sh", "Щ" => "Sh", "Ы" => "I", "Э" => "E", "Ю" => "U", "Я" => "Ya", "ь" => "", "Ь" => "", "ъ" => ""
        , "Ъ" => "", "/" => "", ":" => "");
        $this->space = array(" " => "-", "/" => "", ":" => "");

    }

    public function make404($msg)
    {
        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/common/error_404.php');
        //header_remove();
        http_response_code(404);
    }

    function __destruct()
    {
        echo $this->render($this->get('_footer'));
    }


    public static function buildTree($categories)
    {
        $nodeList = array();
        $tree = array();
        foreach ($categories as $item) {
            $nodeList[$item['node_id']] = array_merge($item, array('children' => array()));
        }

        foreach ($nodeList as $nodeId => &$node) {
            if (!$node['parent_id'] || !array_key_exists($node['parent_id'], $nodeList)) {
                $tree[] = &$node;
            } else {
                $nodeList[$node['parent_id']]['children'][] = &$node;
            }
        }
        unset($node);
        unset($nodeList);
        return $tree;
    }


}