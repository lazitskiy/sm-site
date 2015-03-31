<?php
/**
 * User: vaso
 * Date: 31.03.15
 * Time: 5:45
 */
$paginator = $this->get('paginator');
$_ = $this->get('_');
$last = array_slice($paginator['pages'], 0, array_search($paginator['current'], $paginator['pages']));
$next = array_slice($paginator['pages'], array_search($paginator['current'], $paginator['pages']) + 1);
?>

<ul class="paginator">
    <?php if ($paginator['current'] > $paginator['first']) { ?>
        <li><a href="?"><?php echo $_['First'] ?></a></li>
    <?php } ?>
    <?php if ($paginator['current'] > $paginator['first'] + 1) { ?>
        <li><a href="?page=<?php echo $paginator['previous'] ?>"><?php echo $_['Previous'] ?></a></li>
    <?php } ?>

    <?php foreach ($last as $page) { ?>
        <li><a href="?page=<?php echo $page ?>"><?php echo $page ?></a></li>
    <?php } ?>

    <li><a href="javascipt:#;" class="active"><?php echo $paginator['current'] ?></a></li>

    <?php foreach ($next as $page) { ?>
        <li><a href="?page=<?php echo $page ?>"><?php echo $page ?></a></li>
    <?php } ?>


    <?php if ($paginator['current'] < $paginator['last'] - 1) { ?>
        <li><a href="?page=<?php echo $paginator['next'] ?>"><?php echo $_['Next'] ?></a></li>
    <?php } ?>
    <?php if ($paginator['current'] < $paginator['last']) { ?>
        <li><a href="?page=<?php echo $paginator['last'] ?>"><?php echo $_['Last'] ?></a></li>
    <?php } ?>
</ul>