<?php
/**
 * User: vaso
 * Date: 30.03.15
 * Time: 0:02
 */
$_ = $this->get('_');
?>

<div id="search-form" class="content-dark">
    <div class="container">
        <div class="form">
            <form method="POST" action="">

                <div id="search-fields">
                    <p class="p">Поисковый запрос:</p>
                    <input name="keyword" autocomplete="off" type="search">

                    <div class="selects"><p>Качество:</p>
                        <select name="quality">
                            <option value="all" selected="selected">Все</option>
                            <option value="720p">720p</option>
                            <option value="1080p">1080p</option>
                            <option value="3d">3D</option>
                        </select></div>
                    <div class="selects"><p>Жанр:</p>
                        <select name="genre">
                            <option value="all">Все</option>
                            <option value="action">Экшн</option>
                            <option value="adventure">Приключения</option>
                            <option value="animation">Мультфильм</option>
                            <option value="biography">Биография</option>
                            <option value="comedy">Комедия</option>
                            <option value="crime" selected="selected">Криминал</option>
                            <option value="documentary">Документальный</option>
                            <option value="drama">Драма</option>
                            <option value="family">Семейный</option>
                            <option value="fantasy">Фэнтези</option>
                            <option value="filmnoir">Фильм-нуар</option>
                            <option value="history">История</option>
                            <option value="horror">Ужасы</option>
                            <option value="music">Музыка</option>
                            <option value="musical">Мьюзикл</option>
                            <option value="mystery">Детектив</option>
                            <option value="news">Новости</option>
                            <option value="romance">Мелодрама</option>
                            <option value="scifi">Фантастика</option>
                            <option value="short">Короткометражный</option>
                            <option value="sport">Спорт</option>
                            <option value="thriller">Триллер</option>
                            <option value="war">Военный</option>
                            <option value="western">Вестерн</option>
                        </select></div>
                    <div class="selects"><p>Рейтингу:</p>
                        <select name="rating">
                            <option value="0" selected="selected">Все</option>
                            <option value="9">9+</option>
                            <option value="8">8+</option>
                            <option value="7">7+</option>
                            <option value="6">6+</option>
                            <option value="5">5+</option>
                            <option value="4">4+</option>
                            <option value="3">3+</option>
                            <option value="2">2+</option>
                            <option value="1">1+</option>
                        </select></div>
                    <div class="selects">
                        <p>Сортировать по:</p> <select name="order_by">
                            <option value="latest" selected="selected">Сначала новые</option>
                            <option value="oldest">Сначала старые</option>
                            <option value="seeds">Количеству сидов</option>
                            <option value="peers">Количеству пиров</option>
                            <option value="year">Году</option>
                            <option value="rating">Рейтингу</option>
                            <option value="likes">Понравилось</option>
                            <option value="alphabetical">Алфавиту</option>
                            <option value="downloads">Скачиваниям</option>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
                <div>
                    <input class="button-1-download-big" type="submit" value="Найти">
                </div>

            </form>
        </div>
    </div>
    <div class="clear"></div>
</div>

<div class="movies">
    <h2><?php echo $_['Found movies'] ?><?php echo $this->get('total') ?></h2>
</div>


<?php echo $this->render('/app/view/common/paginator.php'); ?>
<div class="container">
    <div class="row">
        <?php echo $this->render('/app/view/common/movies.php'); ?>
    </div>
</div>
<?php echo $this->render('/app/view/common/paginator.php'); ?>


