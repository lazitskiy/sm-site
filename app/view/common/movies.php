<?php
/**
 * User: vaso
 * Date: 31.03.15
 * Time: 5:26
 */
$movies = $this->get('data')['movies'];
?>
<div class="movie-container">
    <?php foreach ($movies as $movie) { ?>
        <div class="movie-list-small-item">
            <a href="<?php echo $movie['url'] ?>" title="<?php echo $movie['aka_ru'] ?>" class="browse-movie-link">
                <div class="smooter">
                    <img class="img-responsive" src="<?php echo $movie['poster'] ?>">

                    <div class="smootercaption">
                        <span class="icon-star">d</span>
                        <h4 class="rating">8.8 / 10</h4>
                        <h4>Приключения</h4>
                        <h4>Фантастика</h4>
                        <span class="button-1-download-big">Подробнее</span>
                    </div>
                </div>
            </a>

            <div class="browse-movie-bottom">
                <a href="<?php echo $movie['url'] ?>" class="browse-movie-title"><?php echo $movie['name_ru'] ?></a>

                <div class="browse-movie-year">2014</div>
                <div class="browse-movie-tags">
                    <a href="<?php echo $movie['url'] ?>">720p</a>
                    <a href="<?php echo $movie['url'] ?>">1080p</a>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="clear"></div>
</div>