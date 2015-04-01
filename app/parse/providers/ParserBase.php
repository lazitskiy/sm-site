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
        if (!$provider_film_id) {
            return false;
        }

        $film_model = new Axon('film');
        $film_model->load('provider_id=' . $provider_id . ' AND provider_film_id=' . $provider_film_id);


        $film_model->provider_id = $provider_id;
        $film_model->provider_film_id = $provider_film_id;
        $film_model->aka_ru = str_replace('"', '\'', $f['aka_rus']);
        $film_model->aka_en = str_replace('"', '\'', $f['aka_en']);
        $film_model->aka_trans = str_replace('"', '\'', $f['aka_en_row']);
        $film_model->url = $f['url'];
        $film_model->poster_from = $f['poster_from'];
        $film_model->uploaded = 0;
        $film_model->reliz = $f['date_relises'][0]['date'];
        $film_model->year = array_pop(explode('.', $film_model->reliz));
        $film_model->last = $f['last'];
        $film_model->description = $f['description'];
        $film_model->spoiler = $f['spoilers'];
        $film_model->kinopoisk_id = $f['kinopoisk_id'];
        $film_model->imdb_id = $f['imdb_id'];
        $film_model->info = serialize($f['info']);
        $film_model->warning = serialize($f['errors']);


        $film_model->save();


        return $film_model->id;
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

    public function tags_store($tags, $film_id)
    {
        $tag_model = new Axon('tag');
        $film_tag = new Axon('film_tag');

        foreach ($tags as $tag) {

            $tag_model->load('url="' . $tag['url'] . '"');
            if ($tag_model->id) {
                $tag_id = $tag_model->id;
            } else {
                $tag_model->aka_ru = $tag['name'];
                $tag_model->url = $tag['url'];
                $tag_model->save();
                $tag_id = $tag_model->_id;
            }

            $film_tag->load('film_id=' . $film_id . ' AND tag_id=' . $tag_id);
            if ($film_tag->id) {
                continue;
            }

            $film_tag->film_id = $film_id;
            $film_tag->tag_id = $tag_id;
            $film_tag->save();
        }
    }

    /**
     * Инсерт категорий жанров
     *
     * @param mixed $f
     */

    public function companies_store($companies, $film_id)
    {
        $company_model = new Axon('company');
        $film_company = new Axon('film_company');

        foreach ($companies as $company) {

            $company_model->load('url="' . $company['url'] . '"');
            if ($company_model->id) {
                $company_id = $company_model->id;
            } else {
                $company_model->aka_en = $company['name'];
                $company_model->url = $company['url'];
                $company_model->save();
                $company_id = $company_model->_id;
            }

            $film_company->load('film_id=' . $film_id . ' AND company_id=' . $company_id);
            if ($film_company->id) {
                continue;
            }

            $film_company->film_id = $film_id;
            $film_company->company_id = $company_id;
            $film_company->save();
        }
    }

    /**
     * Инсерт каналов
     *
     * @param mixed $f
     * @param mixed $last_id
     */
    public function channels_store($channels, $film_id)
    {
        $channel_model = new Axon('channel');
        $film_channel = new Axon('film_channel');

        foreach ($channels as $channel) {

            $channel_model->load('url="' . $channel['url'] . '"');
            if ($channel_model->id) {
                $channel_id = $channel_model->id;
            } else {
                $channel_model->aka_en = $channel['name'];
                $channel_model->url = $channel['url'];
                $channel_model->save();
                $channel_id = $channel_model->_id;
            }

            $film_channel->load('film_id=' . $film_id . ' AND channel_id=' . $channel_id);
            if ($film_channel->id) {
                continue;
            }

            $film_channel->film_id = $film_id;
            $film_channel->channel_id = $channel_id;
            $film_channel->save();
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
                $torrent_model->sezon = $torrent['sezon'];
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

                if (!$image) {

                }

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

        if ($last_id === false) {
            return;
        }

        $this->genres_store($f['genres'], $last_id);
        if ($f['countries']) {
            $this->countries_store($f['countries'], $last_id);
        }
        if ($f['tags']) {
            $this->tags_store($f['tags'], $last_id);
        }
        if ($f['companies']) {
            $this->companies_store($f['companies'], $last_id);
        }
        if ($f['channels']) {
            $this->channels_store($f['channels'], $last_id);
        }
        if ($f['directors']) {
            $this->peoples_store($f['directors'], $last_id, 'director');
        }
        if ($f['producers']) {
            $this->peoples_store($f['producers'], $last_id, 'producer');
        }
        if ($f['roles']) {
            $this->peoples_store($f['roles'], $last_id, 'actor');
        }

        if ($f['images']) {
            $this->images_store($f['images'], $last_id);
        }


        if ($f['torrents']) {
            $this->torrents_images_store($f['torrents'], $last_id, $provider_id);
            $film_model = new Axon('film');
            $film_model->load('id=' . $last_id);
            $film_model->uploaded = 1;
            $film_model->save();
        }

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

