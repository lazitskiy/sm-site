<?php
/**
 * Created by PhpStorm.
 * User: vas
 * Date: 31.03.2015
 * Time: 15:00
 */
$data = $this->get('data');
$q = $_GET['q'];
$quality = $_GET['quality'];
$genre_id = $_GET['genre'];

?>
<div id="search-form" class="content-dark">
    <div class="container">
        <div class="form">
            <form method="GET" action="">

                <div id="search-fields">
                    <p class="p">Поисковый запрос:</p>
                    <input name="q" autocomplete="off" type="search" value="<?php echo $q ?>">

                    <!--<div class="selects"><p>Качество:</p>
                        <select name="quality">
                            <option value="all">Все</option>
                            <option value="720p">720p</option>
                            <option value="1080p">1080p</option>
                            <option value="3d">3D</option>
                        </select>
                    </div>-->
                    <div class="selects"><p>Жанр:</p>
                        <select name="genre">
                            <option value="all">Все</option>
                            <?php foreach ($data['genres'] as $genre) { ?>
                                <option value="<?php echo $genre['id'] ?>" <?php if ($genre_id == $genre['id']) { ?> selected <? } ?>><?php echo $genre['aka_ru'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
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