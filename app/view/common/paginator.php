<?php
/**
 * User: vaso
 * Date: 31.03.15
 * Time: 5:45
 */
$paginator = $this->get('data')['paginator'];

parse_str($_SERVER['QUERY_STRING'], $query_params);

unset($query_params['page']);
if ($query_params) {
    $additional_url = http_build_query($query_params) . '&';
}


if ($paginator['last'] > 1) {
    $_ = $this->get('_');
    $last = array_slice($paginator['pages'], 0, array_search($paginator['current'], $paginator['pages']));
    $next = array_slice($paginator['pages'], array_search($paginator['current'], $paginator['pages']) + 1);
    ?>

    <ul class="paginator">
        <?php if ($paginator['current'] > 1) { ?>
            <li><a href="?<?php echo $additional_url ?>"><?php echo $_['First'] ?></a></li>
        <?php } ?>
        <?php if ($paginator['current'] > 2) { ?>
            <li><a href="?<?php echo $additional_url ?>page=<?php echo $paginator['previous'] ?>"><?php echo $_['Previous'] ?></a></li>
        <?php } ?>

        <?php foreach ($last as $page) { ?>
            <li><a href="?<?php echo $additional_url ?>page=<?php echo $page ?>"><?php echo $page ?></a></li>
        <?php } ?>

        <li><a href="javascipt:#;" class="active"><?php echo $paginator['current'] ?></a></li>

        <?php foreach ($next as $page) { ?>
            <li><a href="?<?php echo $additional_url ?>page=<?php echo $page ?>"><?php echo $page ?></a></li>
        <?php } ?>


        <?php if ($paginator['current'] < $paginator['last'] - 1) { ?>
            <li><a href="?<?php echo $additional_url ?>page=<?php echo $paginator['next'] ?>"><?php echo $_['Next'] ?></a></li>
        <?php } ?>
        <?php if ($paginator['current'] < $paginator['last']) { ?>
            <li><a href="?<?php echo $additional_url ?>page=<?php echo $paginator['last'] ?>"><?php echo $_['Last'] ?></a></li>
        <?php } ?>
    </ul>
<?php } ?>