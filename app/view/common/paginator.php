<?php
/**
 * User: vaso
 * Date: 31.03.15
 * Time: 5:45
 */
$paginator = $this->get('data')['paginator'];

$additional_url=BaseModel::setUrl();

if ($paginator['last'] > 1) {
    $_ = $this->get('_');
    $last = array_slice($paginator['pages'], 0, array_search($paginator['current'], $paginator['pages']));
    $next = array_slice($paginator['pages'], array_search($paginator['current'], $paginator['pages']) + 1);
    ?>

    <ul class="paginator">
        <?php if ($paginator['current'] > 1) { ?>
            <li><a href="/<?= $additional_url ?>"><?= $_['First'] ?></a></li>
        <?php } ?>
        <?php if ($paginator['current'] > 2) { ?>
            <li><a href="/<?= $additional_url ?>/<?= $paginator['previous'] ?>"><?= $_['Previous'] ?></a></li>
        <?php } ?>

        <?php foreach ($last as $page) { ?>
            <?php if ($page == 1) { ?>
                <li><a href="/<?= $additional_url ?>"><?= $page ?></a></li>
            <?php } else { ?>
                <li><a href="/<?= $additional_url ?>/<?= $page ?>"><?= $page ?></a></li>
            <?php } ?>
        <?php } ?>

        <li><a href="javascipt:#;" class="active"><?= $paginator['current'] ?></a></li>

        <?php foreach ($next as $page) { ?>
            <li><a href="/<?= $additional_url ?>/<?= $page ?>"><?= $page ?></a></li>
        <?php } ?>


        <?php if ($paginator['current'] < $paginator['last'] - 1) { ?>
            <li><a href="/<?= $additional_url ?>/<?= $paginator['next'] ?>"><?= $_['Next'] ?></a></li>
        <?php } ?>
        <?php if ($paginator['current'] < $paginator['last']) { ?>
            <li><a href="/<?= $additional_url ?>/<?= $paginator['last'] ?>"><?= $_['Last'] ?></a></li>
        <?php } ?>
    </ul>
<?php } ?>