<?php
/**
 * User: vaso
 * Date: 30.03.15
 * Time: 0:02
 */
$_ = $this->get('_');
$data = $this->get('data');
?>

<?php echo $this->render('/app/view/common/search.php'); ?>

<div class="movies">
    <h2><?php echo $_['Found movies'] ?><?php echo $data['total'] ?></h2>
</div>


<?php echo $this->render('/app/view/common/paginator.php'); ?>
<div class="container">
    <div class="row">
        <?php echo $this->render('/app/view/common/movies.php'); ?>
    </div>
</div>
<?php echo $this->render('/app/view/common/paginator.php'); ?>


