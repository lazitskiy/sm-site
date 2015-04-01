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
$quality = $_GET['quality'];
$genre_id = $_GET['genre'];
$order_by = $_GET['order_by'];

?>
<div id="search-form" class="content-dark">
    <div class="container">
        <div class="form">
            <form method="GET" action="">

                <div id="search-fields">
                    <p class="p"><?php echo $_['Query'] ?></p>
                    <input name="q" autocomplete="off" type="search" value="<?php echo $q ?>">

                    <!--<div class="selects"><p>Качество:</p>
                        <select name="quality">
                            <option value="all">Все</option>
                            <option value="720p">720p</option>
                            <option value="1080p">1080p</option>
                            <option value="3d">3D</option>
                        </select>
                    </div>-->
                    <div class="selects">
                        <p><?php echo $_['Genre'] ?></p>
                        <select name="genre">
                            <option value=""><?php echo $_['Any'] ?></option>
                            <?php foreach ($data['genres'] as $genre) { ?>
                                <option value="<?php echo $genre['id'] ?>" <?php if ($genre_id == $genre['id']) { ?> selected <? } ?>><?php echo $genre['aka_ru'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="selects">
                        <p><?php echo $_['Raiting'] ?></p>
                        <select name="rating">
                            <option value=""><?php echo $_['Any'] ?></option>
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
                        <p><?php echo $_['Order by'] ?></p>
                        <select name="order_by">
                            <option value="latest" <?php if ($order_by == 'latest') { ?> selected <?php } ?>><?php echo $_['OrderLatest'] ?></option>
                            <option value="oldest" <?php if ($order_by == 'oldest') { ?> selected <?php } ?>><?php echo $_['OrderOldest'] ?></option>
                            <!--<option value="seeds">Количеству сидов</option>
                            <option value="peers">Количеству пиров</option>
                            <option value="year">Году</option>
                            <option value="rating">Рейтингу</option>
                            <option value="likes">Понравилось</option>-->
                            <option value="alpha" <?php if ($order_by == 'alpha') { ?> selected <?php } ?>><?php echo $_['OrderAlpha'] ?></option>
                            <!--<option value="downloads">Скачиваниям</option>-->
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
                <div>
                    <input class="button-1-download-big" type="submit" value="<?php echo $_['Find'] ?>">
                </div>

            </form>
        </div>
    </div>
    <div class="clear"></div>
</div>