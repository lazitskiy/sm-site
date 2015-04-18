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
        <h1><?php echo $_['Do slogan'] ?></h1>

        <p><?php echo $_['Intro'] ?></p>

        <!--<p class="featured-blog">
            Новое сообщение: <a href="/">link</a>
        </p>-->
    </div>
    <div id="popular-downloads">
        <div class="row">
            <h2>
                <span class="icon-star"></span>
                <?php echo $_['Popular movies'] ?>
            </h2>
        </div>
        <div class="row">
            <?php foreach ($populars as $popular) { ?>
                <div class="movie-list-big-item">
                    <a href="<?php echo $popular['url'] ?>" title="<?php echo $popular['aka_ru'] ?>" class="browse-movie-link">
                        <div class="smooter">
                            <img class="img-responsive" src="<?php echo $popular['poster'] ?>">

                            <div class="smootercaption">
                                <span class="icon-star"><?= $_['Raiting'] ?></span>
                                <h4 class="rating"><?php echo $popular['rating'] ?></h4>
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
                        <a href="<?php echo $popular['url'] ?>" class="browse-movie-title"><?php echo $popular['name_ru'] ?></a>

                        <div class="browse-movie-year"><?php echo $popular['year'] ?></div>
                        <div class="browse-movie-tags">
                            <a href="<?php echo $popular['url'] ?>">720p</a>
                            <a href="<?php echo $popular['url'] ?>">1080p</a>
                        </div>
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
                    <?php echo $_['Most downloaded'] ?>
                    <a href="/movies"><?php echo $_['All'] ?></a>
                </h2>
            </div>
            <div class="row">

                <?php foreach ($most_downloaded as $popular) { ?>
                    <div class="movie-list-big-item">
                        <a href="<?php echo $popular['url'] ?>" title="<?php echo $popular['aka_ru'] ?>" class="browse-movie-link">
                            <div class="smooter">
                                <img class="img-responsive" src="<?php echo $popular['poster'] ?>">

                                <div class="smootercaption">
                                    <span class="icon-star"><?= $_['Raiting'] ?></span>
                                    <h4 class="rating"><?php echo $popular['rating'] ?></h4>
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
                            <a href="<?php echo $popular['url'] ?>" class="browse-movie-title"><?php echo $popular['name_ru'] ?></a>

                            <div class="browse-movie-year"><?php echo $popular['year'] ?></div>
                            <div class="browse-movie-tags">
                                <a href="<?php echo $popular['url'] ?>">720p</a>
                                <a href="<?php echo $popular['url'] ?>">1080p</a>
                            </div>
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
                <?php echo $_['Soon films'] ?>
                <a href="/requests"><?php echo $_['Report when release'] ?></a>
            </h2>
        </div>
        <div class="row">
            <?php foreach ($populars as $popular) { ?>
                <div class="movie-list-big-item">
                    <a href="<?php echo $popular['url'] ?>" title="<?php echo $popular['aka_ru'] ?>" class="browse-movie-link">
                        <div class="smooter">
                            <img class="img-responsive" src="<?php echo $popular['poster'] ?>">

                            <div class="smootercaption">
                                <span class="icon-star"><?= $_['Raiting'] ?></span>
                                <h4 class="rating"><?php echo $popular['rating'] ?></h4>
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
                        <a href="<?php echo $popular['url'] ?>" class="browse-movie-title"><?php echo $popular['aka_ru'] ?></a>

                        <div class="browse-movie-year">2014</div>
                        <div class="browse-movie-tags">
                            <a href="<?php echo $popular['url'] ?>">720p</a>
                            <a href="<?php echo $popular['url'] ?>">1080p</a>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>

        <div class="clear"></div>
    </div>
</div>


