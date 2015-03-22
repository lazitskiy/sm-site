<?php

/**
 * User: vaso
 * Date: 23.03.15
 * Time: 0:20
 */
class BaseController extends F3instance
{

    protected $db;
    protected $trans;
    protected $space;

    function __construct()
    {

        $this->db = $this->get('DB');

        //$this->toimg = $_SERVER['DOCUMENT_ROOT'] = 'E:/Zsrv/Apache2/htdocs/torrent';
        //$this->toimg = $_SERVER['DOCUMENT_ROOT'] = 'C:/site_torrent';


        $this->trans = array(" " => "-", "а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ё" => "yo", "ж" => "j", "з" => "z", "и" => "i", "й" => "i", "к" => "k", "л" => "l", "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "у" => "y", "ф" => "f", "х" => "h", "ц" => "c", "ч" => "ch", "ш" => "sh", "щ" => "sh", "ы" => "i", "э" => "e", "ю" => "u", "я" => "ya", "А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Д" => "D", "Е" => "E", "Ё" => "Yo", "Ж" => "J", "З" => "Z", "И" => "I", "Й" => "I", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N", "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T", "У" => "Y", "Ф" => "F", "Х" => "H", "Ц" => "C", "Ч" => "Ch", "Ш" => "Sh", "Щ" => "Sh", "Ы" => "I", "Э" => "E", "Ю" => "U", "Я" => "Ya", "ь" => "", "Ь" => "", "ъ" => ""
        , "Ъ" => "", "/" => "", ":" => "");
        $this->space = array(" " => "-", "/" => "", ":" => "");

    }

    function __destruct()
    {
        echo $this->render($this->get('_footer'));
    }


}