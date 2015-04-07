<?php
/**
 * User: vaso
 * Date: 25.03.15
 * Time: 3:30
 */
$film = $this->get('film');
$film_id = $this->get('film')['id'];
$_ = $this->get('_');
?>

<!--
<div id="background-image" style="background: url(https://s.ynet.io/assets/images/movies/kidnapping_mr_heineken_2015/background.jpg)"></div>
-->
<!--<div id="background-overlay"></div>-->


<div class="container" id="movie-content">

    <div class="row">

        <div class="w25">
            <div class="movie-poster">

                <div id="movie-poster">
                    <img class="img-responsive" src="<?php echo $film['poster'] ?>" alt="<?php echo $film['name_ru'] ?> <?php echo $_['Download'] ?>">
                </div>
                <div>
                    <a class="button-1-download-big" href="#download">
                        <?php echo $_['Download'] ?>
                    </a>
                </div>
                <script>
                    $(document).ready(function () {
                        $('.button-1-download-big').click(function () {
                            var yakor = $(this).attr('href');
                            $('html, body').animate({
                                scrollTop: $(yakor).offset().top - 40
                            }, 600);
                        })
                    });
                </script>
                <?php if ($film['kinopoisk_id']) { ?>

                <?php } ?>
            </div>

        </div>
        <div id="movie-info" class="movie-info w75">


            <h1><?php echo $film['name_full'] ?></h1>

            <div class="p">
                <h2>
                    <?php echo $_['Release date'] ?>
                    <a class="theme" href="/<?php echo $film['reliz_year'] ?>">
                        <?php echo $film['reliz'] ?>
                    </a>
                </h2>

                <h2>
                    <?php echo $_['Genres'] ?>
                    <?php foreach ($film['genres'] as $genre) { ?>
                        <a class="theme" href="/<?php echo $genre['url'] ?>"><?php echo $genre['name'] ?></a>
                    <?php } ?>
                </h2>
                <?php echo $_['Lasted'] ?><?php echo $film['last'] ?>
            </div>

            <div class="p">
                <?php echo $_['Countries'] ?>
                <?php foreach ($film['countries'] as $country) { ?>
                    <a class="theme" href="/<?php echo $country['url'] ?>"><?php echo $country['name'] ?></a>
                <?php } ?>
            </div>

            <?php if ($film['tags']) { ?>
                <div class="p">
                    <?php echo $_['Tags'] ?>
                    <?php foreach ($film['tags'] as $tag) { ?>
                        <a class="theme" href="/<?php echo $tag['url'] ?>"><?php echo $tag['name'] ?></a>
                    <?php } ?>
                </div>
            <?php } ?>

            <?php if ($film['actors']['director']) { ?>
                <div class="p">
                    <?php echo $_['Directors'] ?>
                    <?php foreach ($film['actors']['director'] as $people) { ?>
                        <a class="theme" href="/<?php echo $people['url'] ?>"><?php echo $people['name'] ?></a>
                    <?php } ?>
                </div>
            <?php } ?>

            <?php if ($film['actors']['producer']) { ?>
                <div class="p">
                    <?php echo $_['Producers'] ?>
                    <?php foreach ($film['actors']['producer'] as $people) { ?>
                        <a class="theme" href="/<?php echo $people['url'] ?>"><?php echo $people['name'] ?></a>
                    <?php } ?>
                </div>
            <?php } ?>

            <?php if ($film['actors']['actor']) { ?>
                <div class="p">
                    <?php echo $_['Actors'] ?>
                    <?php foreach ($film['actors']['actor'] as $people) { ?>
                        <a class="theme" href="/<?php echo $people['url'] ?>"><?php echo $people['name'] ?></a>
                    <?php } ?>
                </div>
            <?php } ?>


        </div>
        <div class="clear"></div>


        <?php if ($film['images']) { ?>
            <div class="movie-images pb">
                <h3><?php echo $_['Screenshots'] ?></h3>
                <?php foreach ($film['images'] as $image) { ?>
                    <img height="140" src="/static/images/<?php echo $image['url'] ?>">
                <?php } ?>
                <div class="clear"></div>
            </div>
        <?php } ?>


        <div class="pb">
            <h3><?php echo $_['Description'] ?></h3>
            <?php echo $film['description'] ?>
        </div>


        <?php if ($film['spoiler']) { ?>
            <div class="pb">
                <h3><?php echo $_['Spoiler'] ?></h3>
                <?php echo $film['spoiler'] ?>
            </div>
        <?php } ?>
    </div>

</div>

<span id="download"></span>
<div class="content-dark">
    <div class="container home-content">

        <h3><?php echo $_['Download'] ?></h3>
        <!--
                <div class="control">
                    <div class="sort" data-sort="default">Default</div>
                    <div class="sort" data-sort="myorder:asc">Ascending</div>
                    <div class="sort" data-sort="myorder:desc">Descending</div>
                    <div class="sort" data-sort="random">Random</div>
                </div>

        -->
        <div class="table">
            <div class="tr odd head">
                <div class="th w6">
                    <?php echo $_['Quality'] ?>
                </div>
                <div class="th w6">
                    <?php echo $_['Size'] ?>
                </div>
                <div class="th w6">
                    <?php echo $_['Perevod'] ?>
                </div>


                <div class="th right w5">
                    &nbsp;
                </div>
                <div class="th right w5">
                    <?php echo $_['Downloads'] ?>
                </div>
                <div class="th right w5">
                    <?php echo $_['Leachers'] ?>
                </div>
                <div class="th right w5">
                    <?php echo $_['Seaders'] ?>
                </div>

                <div class="clear"></div>
            </div>

            <div id="mixin">
                <?php
                $i = 0;
                foreach ($film['torrents'] as $torrent) { ?>
                    <div class="tr body <?php echo $i % 2 == 0 ? 'even' : 'odd' ?> mix" data-myorder="<?php echo $i ?>">
                        <div class="td w6">
                            <?php echo $torrent['quality'] ?>
                        </div>
                        <div class="td w6">
                            <?php echo $torrent['size'] ?>
                        </div>
                        <div class="td">
                            <?php echo $torrent['perevod'] ?>
                        </div>


                        <div class="td right w5">
                            <a href="/static/download/<?php echo $film_id ?>/<?php echo $torrent['name'] ?>" class="theme download">
                                <?php echo $_['Download'] ?>
                            </a>
                        </div>
                        <div class="td right w5">
                            <?php echo $torrent['downloads'] ?>
                        </div>
                        <div class="td right w5">
                            <?php echo $torrent['leachers'] ?>
                        </div>
                        <div class="td right w5">
                            <?php echo $torrent['seaders'] ?>
                        </div>


                        <div class="clear"></div>
                    </div>
                    <?php $i++;
                } ?>
            </div>

        </div>
        <div class="clear"></div>
    </div>


    <div id="debates">
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
    </div>

</div>


<script src="http://cdn.jsdelivr.net/jquery.mixitup/latest/jquery.mixitup.min.js"></script>

<script>
    $(document).ready(function () {

        $('.download').click(function () {
            var film_id = $(this).attr('href').split('/')[3];
            var hash = $(this).attr('href').split('/')[4].split('.')[0];

            var _this = this;
            $.getJSON('/api/countup/' + hash, function (data) {
                if (data.status == 'ok') {
                    $(_this).parent().next().html(data.data.downloads);
                }
            });
        });

        $('#mixin').mixItUp();

    })
</script>
