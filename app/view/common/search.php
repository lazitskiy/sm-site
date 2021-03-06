<?php
/**
 * Created by PhpStorm.
 * User: vas
 * Date: 31.03.2015
 * Time: 15:00
 */
$_ = $this->get('_');
$data = $this->get('data');
$q = $_GET['q'];

$url_base = $this->get('data')['url_base'];

?>
<div id="search-form" class="content-dark">
    <div class="container">
        <div class="form">
            <form method="GET" action="">

                <div id="search-fields">
                    <p class="p"><?= $_['Query'] ?></p>
                    <input name="q" autocomplete="off" type="search" value="<?= $q ?>">
                    <br/>
                    <br/>
                </div>
                <div>
                    <input class="button-1-download-big" type="submit" value="<?= $_['Find'] ?>">
                    <br/>

                </div>
            </form>
            <div class="clear"></div>


            <div class="selects" style="width: 220px">
                <div class="inner">
                    <p class="browse-movie-title"><?= $_['Genre'] ?></p>

                    <?php
                    $url = BaseModel::setUrl($url_base, ['genre' => null]);
                    ?>
                    <div>
                        <a class="theme" href="/<?= $url ?>"><?= $_['Any'] ?></a>
                    </div>
                    <?php foreach ($data['genres'] as $genre) { ?>
                        <div>
                            <?php
                            $url = BaseModel::setUrl($url_base, ['genre' => $genre['url_clean']]);
                            ?>
                            <a class="theme" href="/<?= $url ?>"><?= $genre['aka_ru'] ?></a>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php
            for ($year = date('Y'); $year >= 2010; $year--) {
                $years[$year] = $year;
            }
            $years['2000-10'] = '2000-ые';
            $years['1990-10'] = '1990-ые';
            $years['1980-10'] = '1980-ые';
            $years['1970-10'] = '1970-ые';
            $years['1970-'] = '1970 и ранее';

            ?>
            <div class="selects" style="width: 180px">
                <div class="inner">
                    <p class="browse-movie-title"><?= $_['Year'] ?></p>
                    <?php
                    $url = BaseModel::setUrl($url_base, ['year' => null]);
                    ?>
                    <div>
                        <a class="theme" href="/<?= $url ?>"><?= $_['Any'] ?></a>
                    </div>
                    <?php foreach ($years as $k => $year) { ?>
                        <div>
                            <?php
                            $url = BaseModel::setUrl($url_base, ['year' => $k]);
                            ?>
                            <a class="theme" href="/<?= $url ?>"><?= $year ?></a>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <?php $ratings = [
                '9' => '9+',
                '8' => '8+',
                '7' => '7+',
                '6' => '6+',
                '5' => '5+',
                '4' => '4+',
                '3' => '3+',
                '2' => '2+',
                '1' => '1+',
            ] ?>
            <div class="selects">
                <div class="inner">
                    <p class="browse-movie-title"><?= $_['Raiting'] ?></p>
                    <?php
                    $url = BaseModel::setUrl($url_base, ['rating' => null]);
                    ?>
                    <div>
                        <a class="theme" href="/<?= $url ?>"><?= $_['Any'] ?></a>
                    </div>
                    <?php foreach ($ratings as $k => $v) { ?>
                        <div>
                            <?php
                            $url = BaseModel::setUrl($url_base, ['rating' => $k]);;
                            ?>
                            <a class="theme" href="/<?= $url ?>"><?= $v ?></a>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>