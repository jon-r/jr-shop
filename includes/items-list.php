<?php /* Filtered items list */
global $itemCountMin;
$newItems = jrQ_ItemsNew();
$pageNumber = isset($_GET['pg']) ? $_GET['pg'] : 1;
$items = new itemList;
$items->get($jr_safeArray);
?>

<article class="flex-container">
  <header class="article-header flex-1">
    <h1><?php echo $jr_safeArray['title'] ?></h1>
    <p><?php echo $jr_safeArray['pageText'] ?></p>
  </header>


  <?php for ($n = 0; $n < $items->pgCount; $n++) : include( "list-item.php"); if ($n % 8 == 7) : ?>
    <section class="flex-1">
      <a href="#" class="text-right" ><h2>&uarr; Scroll to Top</h2></a>
    </section>
    <?php endif; endfor; ?>
  <?php if($items->pgCount < $itemCountMin) : ?>

  <section class="flex-1 form-contact wider tile-outer dark">
    <header class="tile-header lined">
      <h2>More in store</h2>
    </header>
    <p>Sometimes the equipment you need is going through the workshop right now. If interested, call <?php echo jr_linkTo('phone') ?> today and will see if we can get hold of what you need.</p>
    <?php include('form-base.php') ?>
  </section>
<?php endif ?>
</article>

<?php if ($items->paginate) : ?>

<nav class="flex-container centre">
  <section class="nav-paginate tile-outer">
    <?php if ($pageNumber > 1) : ?>
    <a href="<?php  echo jr_pgSet(1) ?>"><h3>&laquo;</h3></a>
    <a href="<?php  echo jr_pgSet('minus') ?>"><h3>&lsaquo;</h3></a>
    <?php endif ?>

    <?php for ($i=1 ; $i <= $items->paginate; $i++) : ?>
    <a <?php echo jr_isPg($i) ? 'class="active"' : null ?> href="<?php  echo jr_pgSet($i) ?>" ><h3><?php  echo $i ?></h3></a>
    <?php endfor ?>

    <?php if ($pageNumber < $items->paginate) : ?>
    <a href="<?php  echo jr_pgSet('plus') ?>"><h3>&rsaquo;</h3></a>
    <a href="<?php  echo jr_pgSet($items->paginate) ?>"><h3>&raquo;</h3></a>
    <?php endif ?>
  </section>
</nav>

<?php endif ?>
