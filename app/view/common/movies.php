<?php
/**
 * User: vaso
 * Date: 31.03.15
 * Time: 5:26
 */
$movies = $this->get('data')['movies'];
$_ = $this->get('_');
?>
<div class="movie-container">
    <?php foreach ($movies as $movie) { ?>
        <div class="movie-list-small-item">
            <a href="<?= $movie['url'] ?>" title="<?= $movie['aka_ru'] ?>" class="browse-movie-link">
                <div class="smooter">
                    <img class="img-responsive" src="<?= $movie['poster'] ?>">

                    <div class="smootercaption">
                        <span class="icon-star"><?= $_['Raiting'] ?></span>
                        <h4 class="rating"><?= $movie['rating'] ?></h4>
                        <?php
                        $genres = array_chunk(array_filter($movie['genres'], function ($v) {
                            return (count(explode(' ', $v['name'])) == 1);
                        }), 3)[0];

                        foreach ($genres as $genre) { ?>
                            <h4><?= $genre['name'] ?></h4>
                        <?php } ?>
                        <span class="button-1-download-big">Подробнее</span>
                    </div>
                </div>
            </a>

            <div class="browse-movie-bottom">
                <a href="<?= $movie['url'] ?>" class="browse-movie-title"><?= $movie['name_ru'] ?></a>

                <div class="browse-movie-year"><?= $movie['year'] ?></div>
                <div class="browse-movie-tags">
                    <a href="<?= $movie['url'] ?>">720p</a>
                    <a href="<?= $movie['url'] ?>">1080p</a>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="clear"></div>
</div>