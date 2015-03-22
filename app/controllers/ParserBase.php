<?php

/**
 * User: vaso
 * Date: 07.03.15
 * Time: 22:04
 */
class ParserBase extends F3instance
{

    public $errors;

    public $provider_id;

    public $db;

    /**
     * Инсерт фильм или возврат ИД
     *
     * @param mixed $f
     */

    public function __construct()
    {
        header('Content-type: text/html; charset=utf-8');
        set_time_limit(90000);
        ini_set('output_buffering', 0);
        set_time_limit(0);
        ob_implicit_flush(1);

        $this->db = $this->get('DB');
    }

    public function film_store($f, $provider_id)
    {
        $provider_film_id = $f['provider_film_id'];

        $film_model = new Axon('film');
        $film_model->load('provider_id=' . $provider_id . ' AND provider_film_id=' . $provider_film_id);
        if ($film_model->id) {
            return $film_model->id;
        }

        $film_model->provider_id = $provider_id;
        $film_model->provider_film_id = $provider_film_id;
        $film_model->aka_ru = str_replace('"', '\'', $f['aka_rus']);
        $film_model->aka_en = str_replace('"', '\'', $f['aka_en']);
        $film_model->aka_trans = str_replace('"', '\'', $f['aka_en_row']);
        $film_model->url = $f['url'];
        $film_model->poster_from = $f['poster_from'];
        $film_model->uploaded = 0;
        $film_model->reliz = $f['date_relises'][0]['date'];
        $film_model->last = $f['last'];
        $film_model->description = $f['description'];
        $film_model->spoiler = $f['spoilers'];
        $film_model->save();

        return $film_model->_id;
    }

    /**
     * Инсерт изображений
     *
     * @param mixed $f
     */
    public function images_store($images, $film_id)
    {
        $image_model = new Axon('image');
        foreach ($images as $img) {
            $image_model->load('aka="' . $img . '"');
            if ($image_model->id) {
                continue;
            }
            $image_model->aka = $img;
            $image_model->uploaded = 0;
            $image_model->film_id = $film_id;
            $image_model->save();
        }
    }

    /**
     * Инсерт категорий жанров
     *
     * @param mixed $f
     */
    public function genres_store($genres, $film_id)
    {
        $category_model = new Axon('category');
        $film_category = new Axon('film_category');

        foreach ($genres as $genre) {
            $category_model->load('url="' . $genre['url'] . '"');
            if ($category_model->id) {
                $category_id = $category_model->id;
            } else {
                $category_model->aka_ru = $genre['name'];
                $category_model->url = $genre['url'];
                $category_model->save();

                $category_id = $category_model->_id;
            }

            $film_category->load('film_id=' . $film_id . ' AND category_id=' . $category_id);
            if ($film_category->id) {
                continue;
            }

            $film_category->film_id = $film_id;
            $film_category->category_id = $category_id;
            $film_category->save();
        }
    }

    public function countries_store($countries, $film_id)
    {
        $country_model = new Axon('country');
        $film_country = new Axon('film_country');

        foreach ($countries as $country) {

            $country_model->load('code="' . $country['url'] . '"');
            if ($country_model->id) {
                $country_id = $country_model->id;
            } else {
                $country_model->aka = $country['name'];
                $country_model->code = $country['url'];
                $country_model->save();

                $country_id = $country_model->_id;
            }


            $film_country->load('film_id=' . $film_id . ' AND country_id=' . $country_id);
            if ($film_country->id) {
                continue;
            }

            $film_country->film_id = $film_id;
            $film_country->country_id = $country_id;
            $film_country->save();
        }
    }

    /**
     * Инсерт категорий жанров
     *
     * @param mixed $f
     */

    public function companies_store($f, $last_id)
    {
        if ($f['companies']) {
            foreach ($f['companies'] as $company) {
                $is = $this->db->sql('SELECT * FROM companies WHERE aka="' . trim($company['aka']) . '"');
                $obj_id = $is[0]['id'];
                if (!$is) {
                    $str = 'INSERT INTO companies VALUES(NULL,"' . $company['aka'] . '","' . str_replace('/company', '', $company['href']) . '")';
                    $comp = $this->db->sql($str);
                    if (!$comp) {
                        $this->db->sql("INSERT INTO _log(type,text) VALUES('company','" . $str . "')");
                    }
                    $obj = $this->db->sql('SELECT MAX(id) id FROM companies ');
                    $obj_id = $obj[0]['id'];
                }
                //Если нет связи
                $is_xref = $this->db->sql('SELECT * FROM film_company_xref WHERE film_id=' . $last_id . ' AND company_id=' . $obj_id);
                if (!$is_xref) {
                    $str = 'INSERT INTO film_company_xref VALUES(NULL,' . $last_id . ',' . $obj_id . ')';
                    $xref = $this->db->sql();
                    if (!$xref) {
                        $this->db->sql("INSERT INTO _log(type,text) VALUES('company_xref','" . $str . "')");
                    }
                }
            }
        }
    }

    /**
     * Инсерт каналов
     *
     * @param mixed $f
     * @param mixed $last_id
     */
    public function channels_store($f, $last_id)
    {
        if ($f['channels']) {
            foreach ($f['channels'] as $company) {
                $is = $this->db->sql('SELECT * FROM channels WHERE aka="' . trim($company['aka']) . '"');
                $obj_id = $is[0]['id'];
                if (!$is) {
                    $str = ' INSERT INTO channels VALUES(NULL,"' . $company['aka'] . '","' . str_replace('/company', '', $company['href']) . '")';
                    $chan = $this->db->sql($str);
                    if (!$chan) {
                        $this->db->sql("INSERT INTO _log(type,text,date) VALUES('channel','" . $str . "','" . date('Y-m-d') . "')");;
                    }
                    $obj = $this->db->sql('SELECT MAX(id) id FROM channels ');
                    $obj_id = $obj[0]['id'];
                }
                //Если нет связи
                $is_xref = $this->db->sql('SELECT * FROM film_channel_xref WHERE film_id=' . $last_id . ' AND company_id=' . $obj_id);
                if (!$is_xref) {
                    $str = 'INSERT INTO film_channel_xref VALUES(NULL,' . $last_id . ',' . $obj_id . ')';
                    $chan = $this->db->sql($str);
                    if (!$chan) {
                        $this->db->sql("INSERT INTO _log(type,text,date) VALUES('channel_xref','" . $str . "','" . date('Y-m-d') . "')");;
                    }
                }
            }
        }
    }

    /**
     * Режиссеры прод.серы и в ролях
     *
     * @param mixed $f
     * @param mixed $last_id
     * @param mixed $type
     * @param mixed $type_str
     */
    public function peoples_store($peoples, $film_id, $type = 'actor')
    {
        $actor_model = new Axon('actor');
        $film_actor = new Axon('film_actor');

        foreach ($peoples as $actor) {

            $actor_model->load('aka_en="' . $actor['url'] . '"');
            if ($actor_model->id) {
                $actor_id = $actor_model->id;
            } else {
                $actor_model->aka_ru = $actor['name'];
                $actor_model->aka_en = $actor['url'];
                $actor_model->save();

                $actor_id = $actor_model->_id;
            }


            $film_actor->load('film_id=' . $film_id . ' AND actor_id=' . $actor_id);
            if ($film_actor->id) {
                continue;
            }

            $film_actor->film_id = $film_id;
            $film_actor->actor_id = $actor_id;
            $film_actor->type = $type;
            $film_actor->save();
        }

    }

    /**
     * Сохраняем в базу таренты и картинки
     *
     * @param mixed $f
     * @param mixed $last_id
     */
    public function torrents_images_store($torrents, $film_id, $provider_id)
    {
        $quality_model = new Axon('quality');
        $torrent_model = new Axon('torrent');
        $image_model = new Axon('torrent_image');

        foreach ($torrents as $torrent) {
            $provider_torrent_id = $torrent['id'];


            $quality_model->load('aka="' . $torrent['quality']['short'] . '"');
            if ($quality_model->id) {
                $quality_id = $quality_model->id;
            } else {
                $quality_model->aka = $torrent['quality']['short'];
                $quality_model->description = $torrent['quality']['text'];
                $quality_model->save();
                $quality_id = $quality_model->_id;
            }


            $torrent_model->load('provider_id=' . $provider_id . ' AND provider_torrent_id=' . $provider_torrent_id);
            if ($torrent_model->id) {
                $torrent_id = $torrent_model->id;
            } else {
                $torrent_model->film_id = $film_id;
                $torrent_model->provider_id = $provider_id;
                $torrent_model->provider_torrent_id = $provider_torrent_id;
                $torrent_model->quality_id = $quality_id;
                $torrent_model->perevod = $torrent['perevod'];
                $torrent_model->size = $torrent['size'];
                $torrent_model->date_add = $torrent['date_add'];
                $torrent_model->downloads = $torrent['downloads'];
                $torrent_model->seaders = $torrent['seaders'];
                $torrent_model->leachers = $torrent['leachers'];
                $torrent_model->url = $torrent['url'];
                $torrent_model->uploaded = 0;

                $torrent_model->save();
                $torrent_id = $torrent_model->_id;
            }

            // Картинки к торрентам
            foreach ($torrent['images'] as $image) {
                //Если есть что качать


                $image_model->load('aka="' . $image . '"');
                if ($image_model->id) {
                    continue;
                }
                $image_model->aka = $image;
                $image_model->uploaded = 0;
                $image_model->torrent_id = $torrent_id;
                $image_model->save();

            }
        }
    }


    /**
     * Формат $f в /temp/film_template.html
     * @param $data
     */
    public function storeFilm($f, $provider_id)
    {


        //Сохраняем новый фильм или выбираем если есть возврат ИД
        $last_id = $this->film_store($f, $provider_id);

        $this->images_store($f['images'], $last_id);
        $this->genres_store($f['genres'], $last_id);

        // хз че это
        //$this->companies_store($f, $last_id);
        ################### Каналы ################
        //  и это
        //$this->channels_store($f, $last_id);
        $this->countries_store($f['countries'], $last_id);

        $this->peoples_store($f['directors'], $last_id, 'director');
        $this->peoples_store($f['roles'], $last_id, 'actor');

        $this->torrents_images_store($f['torrents'], $last_id, $provider_id);
    }

    public function storeNewFilmLinks($links, $provider_id)
    {
        $film_model = new Axon('film');
        $films = $film_model->afind('provider_id=' . $provider_id);

        $film_ids = array_map(function ($el) {
            return $el['provider_film_id'];
        }, $films);

        $i = 0;
        foreach ($links as $link) {
            $provider_film_id = $link['provider_film_id'];
            if (!in_array($provider_film_id, $film_ids)) {
                $film_model->provider_id = $provider_id;
                $film_model->provider_film_id = $provider_film_id;
                $film_model->url = $link['url'];
                $film_model->uploaded = 0;
                $film_model->save();
                $i++;
                $film_ids[] = $provider_film_id;
            }
        }
        return $i;
    }


}

class Transliterator
{
    public static function transliterate($str)
    {
        $trans = array(" " => "-", "а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ё" => "yo", "ж" => "j", "з" => "z", "и" => "i", "й" => "i", "к" => "k", "л" => "l", "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "у" => "y", "ф" => "f", "х" => "h", "ц" => "c", "ч" => "ch", "ш" => "sh", "щ" => "sh", "ы" => "i", "э" => "e", "ю" => "u", "я" => "ya", "А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Д" => "D", "Е" => "E", "Ё" => "Yo", "Ж" => "J", "З" => "Z", "И" => "I", "Й" => "I", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N", "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T", "У" => "Y", "Ф" => "F", "Х" => "H", "Ц" => "C", "Ч" => "Ch", "Ш" => "Sh", "Щ" => "Sh", "Ы" => "I", "Э" => "E", "Ю" => "U", "Я" => "Ya", "ь" => "", "Ь" => "", "ъ" => "", "Ъ" => "", "/" => "", ":" => "", "|" => "", "---" => "-", "--" => "-", "'" => "", "\"" => "");
        $space = array(" " => "-", "/" => "", ":" => "");

        return strtolower(preg_replace('#[^A-Za-z0-9\/\-\.]#', '', strtr(strtr($str, $trans), $trans)));
    }


    public static function transliterateActor($str)
    {
        $trans = array("а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ё" => "yo", "ж" => "j", "з" => "z", "и" => "i", "й" => "i", "к" => "k", "л" => "l", "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "у" => "y", "ф" => "f", "х" => "h", "ц" => "c", "ч" => "ch", "ш" => "sh", "щ" => "sh", "ы" => "i", "э" => "e", "ю" => "u", "я" => "ya", "А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Д" => "D", "Е" => "E", "Ё" => "Yo", "Ж" => "J", "З" => "Z", "И" => "I", "Й" => "I", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N", "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T", "У" => "Y", "Ф" => "F", "Х" => "H", "Ц" => "C", "Ч" => "Ch", "Ш" => "Sh", "Щ" => "Sh", "Ы" => "I", "Э" => "E", "Ю" => "U", "Я" => "Ya", "ь" => "", "Ь" => "", "ъ" => "", "Ъ" => "", "/" => "", ":" => "", "|" => "", "---" => "-", "--" => "-", "'" => "", "\"" => "");
        return strtr($str, $trans);
    }
}


class nokogiri implements IteratorAggregate
{
    protected $_source = '';
    /**
     * @var DOMDocument
     */
    protected $_dom = null;
    /**
     * @var DOMXpath
     * */
    protected $_xpath = null;

    public function __construct($htmlString = '')
    {
        $this->loadHtml($htmlString);
    }

    public static function fromHtml($htmlString)
    {
        $me = new self();
        $me->loadHtml($htmlString);
        return $me;
    }

    public static function fromDom($dom)
    {
        $me = new self();
        $me->loadDom($dom);
        return $me;
    }

    public function loadDom($dom)
    {
        $this->_dom = $dom;
        $this->_xpath = new DOMXpath($this->_dom);
    }

    public function loadHtml($htmlString = '')
    {
        //$dom = new DOMDocument('1.0', 'UTF-8');
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->preserveWhiteSpace = false;
        if (strlen($htmlString)) {
            libxml_use_internal_errors(TRUE);
            $dom->loadHTML('<?xml encoding="UTF-8">' . $htmlString);
            $dom->encoding = 'utf-8';
            libxml_clear_errors();
        }
        $this->loadDom($dom);
    }

    function __invoke($expression)
    {
        return $this->get($expression);
    }

    public function get($expression)
    {
        if (strpos($expression, ' ') !== false) {
            $a = explode(' ', $expression);
            foreach ($a as $k => $sub) {
                $a[$k] = $this->getXpathSubquery($sub);
            }
            return $this->getElements(implode('', $a));
        }
        return $this->getElements($this->getXpathSubquery($expression));
    }

    protected function getXpathSubquery($expression)
    {
        $query = '';
        if (preg_match("/(?P<tag>[a-z0-9]+)?(\[(?P<attr>\S+)=(?P<value>\S+)\])?(#(?P<id>\S+))?(\.(?P<class>\S+))?/ims", $expression, $subs)) {
            $tag = $subs['tag'];
            $id = $subs['id'];
            $attr = $subs['attr'];
            $attrValue = $subs['value'];
            $class = $subs['class'];
            if (!strlen($tag))
                $tag = '*';
            $query = '//' . $tag;
            if (strlen($id)) {
                $query .= "[@id='" . $id . "']";
            }
            if (strlen($attr)) {
                $query .= "[@" . $attr . "='" . $attrValue . "']";
            }
            if (strlen($class)) {
                //$query .= "[@class='".$class."']";
                $query .= '[contains(concat(" ", normalize-space(@class), " "), " ' . $class . ' ")]';
            }
        }
        return $query;
    }

    protected function getElements($xpathQuery)
    {
        //$newDom = new DOMDocument('1.0', 'UTF-8');
        $newDom = new DOMDocument('1.0', 'utf-8');
        $root = $newDom->createElement('root');
        $newDom->appendChild($root);
        if (strlen($xpathQuery)) {
            $nodeList = $this->_xpath->query($xpathQuery);
            if ($nodeList === false) {
                throw new Exception('Malformed xpath');
            }
            foreach ($nodeList as $domElement) {
                $domNode = $newDom->importNode($domElement, true);
                $root->appendChild($domNode);
            }
            return self::fromDom($newDom);
        }
    }

    public function toXml()
    {
        return $this->_dom->saveXML();
    }

    public function toArray($xnode = null)
    {
        $array = array();
        if ($xnode === null) {
            $node = $this->_dom;
        } else {
            $node = $xnode;
        }
        if ($node->nodeType == XML_TEXT_NODE) {
            return $node->nodeValue;
        }
        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attr) {
                $array[$attr->nodeName] = $attr->nodeValue;
            }
        }
        if ($node->hasChildNodes()) {
            if ($node->childNodes->length == 1) {
                $array[$node->firstChild->nodeName] = $this->toArray($node->firstChild);
            } else {
                foreach ($node->childNodes as $childNode) {
                    if ($childNode->nodeType != XML_TEXT_NODE) {
                        $array[$childNode->nodeName][] = $this->toArray($childNode);
                    }
                }
            }
        }
        if ($xnode === null) {
            return reset(reset($array)); // first child
        }
        return $array;
    }

    public function getIterator()
    {
        $a = $this->toArray();
        return new ArrayIterator($a);
    }
}


class MCurl
{

    var $timeout = 20; // максимальное время загрузки страницы в секундах
    var $threads = 10; // количество потоков

    var $all_useragents = array(
        "Opera/9.23 (Windows NT 5.1; U; ru)",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.8.1.8) Gecko/20071008 Firefox/2.0.0.4;MEGAUPLOAD 1.0",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; Alexa Toolbar; MEGAUPLOAD 2.0; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7;MEGAUPLOAD 1.0",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; MyIE2; Maxthon)",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; MyIE2; Maxthon)",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; MyIE2; Maxthon)",
        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; WOW64; Maxthon; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; Media Center PC 5.0; InfoPath.1)",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; MyIE2; Maxthon)",
        "Opera/9.10 (Windows NT 5.1; U; ru)",
        "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.2.1; aggregator:Tailrank; http://tailrank.com/robot) Gecko/20021130",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.8) Gecko/20071008 Firefox/2.0.0.8",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; MyIE2; Maxthon)",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.8.1.8) Gecko/20071008 Firefox/2.0.0.8",
        "Opera/9.22 (Windows NT 6.0; U; ru)",
        "Opera/9.22 (Windows NT 6.0; U; ru)",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.8.1.8) Gecko/20071008 Firefox/2.0.0.8",
        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; MRSPUTNIK 1, 8, 0, 17 HW; MRA 4.10 (build 01952); .NET CLR 1.1.4322; .NET CLR 2.0.50727)",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9"
    );

    function multiget($urls, &$result)
    {
        $threads = $this->threads;
        $useragent = $this->all_useragents[array_rand($this->all_useragents)];
        $p = 1;
        $i = 0;
        for ($i = 0; $i < count($urls); $i = $i + $threads) {
            $urls_pack[] = array_slice($urls, $i, $threads);
        }
        foreach ($urls_pack as $pack) {
            echo count($urls) - $p * ($threads) . "\n";
            myflush();
            $mh = curl_multi_init();
            unset($conn);
            foreach ($pack as $i => $url) {
                $conn[$i] = curl_init(trim($url));
                curl_setopt($conn[$i], CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($conn[$i], CURLOPT_TIMEOUT, $this->timeout);

                curl_setopt($conn[$i], CURLOPT_USERAGENT, $useragent);
                curl_multi_add_handle($mh, $conn[$i]);
            }
            do {
                $n = curl_multi_exec($mh, $active);
                usleep(100);
            } while ($active);
            foreach ($pack as $i => $url) {
                $result[] = curl_multi_getcontent($conn[$i]);
                curl_close($conn[$i]);

            }
            curl_multi_close($mh);
            $p++;
        }

    }
}
