<?
$cats = $this->get('cats');
$films = $this->get('films');

$_ = $this->get('_');

$populars = $this->get('populars');
$most_downloaded = $this->get('most_downloaded');
$soons = $this->get('soons');


?>

<!--
<div id="background-image" style="background-image: url(https://s.ynet.io/assets/images/movies/interstellar_2014/background.jpg)"></div>
<div id="background-overlay"></div>

-->
<div class="container home-content">
    <div class="hidden-xs hidden-sm">
        <h1><?= $_['Do slogan'] ?></h1>

        <p><?= $_['Intro'] ?></p>

        <!--<p class="featured-blog">
            Новое сообщение: <a href="/">link</a>
        </p>-->
    </div>
    <div id="popular-downloads">
        <div class="row">
            <h2>
                <span class="icon-star"></span>
                <?= $_['Popular movies'] ?>
            </h2>
        </div>
        <div class="row">
            <?php foreach ($populars as $popular) { ?>
                <div class="movie-list-big-item">
                    <a href="<?= $popular['url'] ?>" title="<?= $popular['name_ru'] ?><?= $_['index']['anchor'] ?>" class="browse-movie-link">
                        <div class="smooter">
                            <img class="img-responsive" src="<?= $popular['poster'] ?>">

                            <div class="smootercaption">
                                <span class="icon-star"><?= $_['Raiting'] ?></span>
                                <h4 class="rating"><?= $popular['rating'] ?></h4>
                                <?php
                                $genres = array_chunk(array_filter($popular['genres'], function ($v) {
                                    return (count(explode(' ', $v['name'])) == 1);
                                }), 3)[0];

                                foreach ($genres as $genre) { ?>
                                    <h4><?= $genre['name'] ?></h4>
                                <?php } ?>
                                <span class="button-1-download-big"><?= $_['More'] ?></span>
                            </div>
                        </div>
                    </a>

                    <div class="browse-movie-bottom">
                        <a href="<?= $popular['url'] ?>" class="browse-movie-title" title="<?= $popular['name_ru'] ?><?= $_['index']['anchor'] ?>">
                            <?= $popular['name_ru'] ?><?= $_['index']['anchor'] ?>
                        </a>

                        <div class="browse-movie-year"><?= $popular['year'] ?></div>
                    </div>
                </div>
            <?php } ?>

        </div>

        <div class="clear"></div>
    </div>
</div>


<div class="content-dark">
    <div class="container home-content">
        <div class="home-movies">
            <div class="row">
                <h2>
                    <?= $_['Most downloaded'] ?>
                    <a href="/movies"><?= $_['All'] ?></a>
                </h2>
            </div>
            <div class="row">

                <?php foreach ($most_downloaded as $popular) { ?>
                    <div class="movie-list-big-item">
                        <a href="<?= $popular['url'] ?>" title="<?= $popular['name_ru'] ?><?= $_['index']['anchor'] ?>" class="browse-movie-link">
                            <div class="smooter">
                                <img class="img-responsive" src="<?= $popular['poster'] ?>">

                                <div class="smootercaption">
                                    <span class="icon-star"><?= $_['Raiting'] ?></span>
                                    <h4 class="rating"><?= $popular['rating'] ?></h4>
                                    <?php
                                    $genres = array_chunk(array_filter($popular['genres'], function ($v) {
                                        return (count(explode(' ', $v['name'])) == 1);
                                    }), 3)[0];

                                    foreach ($genres as $genre) { ?>
                                        <h4><?= $genre['name'] ?></h4>
                                    <?php } ?>
                                    <span class="button-1-download-big"><?= $_['More'] ?></span>
                                </div>
                            </div>
                        </a>

                        <div class="browse-movie-bottom">
                            <a href="<?= $popular['url'] ?>" class="browse-movie-title" title="<?= $popular['name_ru'] ?><?= $_['index']['anchor'] ?>">
                                <?= $popular['name_ru'] ?><?= $_['index']['anchor'] ?>
                            </a>

                            <div class="browse-movie-year"><?= $popular['year'] ?></div>
                        </div>
                    </div>
                <?php } ?>

            </div>


            <div class="clear"></div>
        </div>
    </div>
</div>

<div class="container home-content">

    <div class="home-movies">
        <div class="row">
            <h2>
                <?= $_['Soon films'] ?>
                <a href="/requests"><?= $_['Report when release'] ?></a>
            </h2>
        </div>
        <div class="row">
            <?php foreach ($populars as $popular) { ?>
                <div class="movie-list-big-item">
                    <a href="<?= $popular['url'] ?>" title="<?= $popular['name_ru'] ?><?= $_['index']['anchor'] ?>" class="browse-movie-link">
                        <div class="smooter">
                            <img class="img-responsive" src="<?= $popular['poster'] ?>">

                            <div class="smootercaption">
                                <span class="icon-star"><?= $_['Raiting'] ?></span>
                                <h4 class="rating"><?= $popular['rating'] ?></h4>
                                <?php
                                $genres = array_chunk(array_filter($popular['genres'], function ($v) {
                                    return (count(explode(' ', $v['name'])) == 1);
                                }), 3)[0];

                                foreach ($genres as $genre) { ?>
                                    <h4><?= $genre['name'] ?></h4>
                                <?php } ?>
                                <span class="button-1-download-big"><?= $_['More'] ?></span>
                            </div>
                        </div>
                    </a>

                    <div class="browse-movie-bottom">
                        <a href="<?= $popular['url'] ?>" class="browse-movie-title" title="<?= $popular['name_ru'] ?><?= $_['index']['anchor'] ?>">
                            <?= $popular['name_ru'] ?><?= $_['index']['anchor'] ?>
                        </a>

                        <div class="browse-movie-year"><?= $popular['year'] ?></div>

                    </div>
                </div>
            <?php } ?>

        </div>

        <div class="clear"></div>
    </div>
</div>


