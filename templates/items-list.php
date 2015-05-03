<?php /* Filtered items list
*
*
*/
global $jr_safeArray;

$pageNumber = $_GET['pg'] ?: 1;
$items = jr_itemsList($jr_safeArray, $pageNumber);

?>

<article class="flex-container">

  <header class="article-header flex-1">
    <h1><?php echo $jr_safeArray[pgName]; ?></h1>
    <p><?php echo $jr_safeArray[description] ?></p>
    <?php echo $jr_safeArray[imgURL] ?>
  </header>

  <?php
  foreach ($items['list'] as $item) {
    include( "list-item.php");
  }
  ?>

</article>

<?php if ($items['paginate']) : ?>

<nav class="flex-container nav-paginate">
  <section>
    <?php if ($pageNumber > 1) : ?>
    <a href="<?php  echo jr_pgSet(1) ?>"><h4>&laquo;</h4></a>
    <a href="<?php  echo jr_pgSet(minus) ?>"><h4>&lsaquo;</h4></a>
    <?php endif ?>

    <?php for ($i=1 ; $i <= $items['paginate']; $i++) : ?>
    <a <?php echo jr_isPg($i) ? 'class="active"' : null ?> href="<?php  echo jr_pgSet($i) ?>" ><h4><?php  echo $i ?></h4></a>
    <?php endfor ?>

    <?php if ($pageNumber < $items['paginate']) : ?>
    <a href="<?php  echo jr_pgSet(plus) ?>"><h4>&rsaquo;</h4></a>
    <a href="<?php  echo jr_pgSet($items['paginate']) ?>"><h4>&raquo;</h4></a>
    <?php endif ?>
  </section>
</nav>

<?php endif ?>

