<?php /* Filtered items list */
global $itemCountMin;

$pgCategory = new itemList;
$pgCategory->get();
?>

<article class="flex-container">
  <header class="article-header flex-1">
    <h1><?php echo $pgCategory->title ?></h1>
    <p><?php echo $pgCategory->description ?></p>
  </header>


  <?php for ($n = 0; $n < $pgCategory->pgCount; $n++) : include( "list-item.php"); if ($n % 8 == 7) : ?>
    <section class="flex-1">
      <a href="#" class="text-right" ><h2>&uarr; Scroll to Top</h2></a>
    </section>
    <?php endif; endfor; ?>
  <?php if($pgCategory->pgCount < $itemCountMin) : ?>

  <section class="flex-1 form-contact wider tile-outer dark">
    <header class="tile-header lined">
      <h2>More in store</h2>
    </header>
    <p>Sometimes the equipment you need is going through the workshop right now. If interested, call <?php echo jr_linkTo('phone') ?> today and will see if we can get hold of what you need.</p>
    <?php include('form-base.php') ?>
  </section>
<?php endif ?>
</article>

<?php if ($pgCategory->paginate) : $pageNumber = isset($_GET['pg']) ? $_GET['pg'] : 1; ?>

<nav class="flex-container centre">
  <section class="nav-paginate tile-outer">
    <?php if ($pageNumber > 1) : ?>
    <a href="<?php  echo jr_pgSet(1) ?>"><h3>&laquo;</h3></a>
    <a href="<?php  echo jr_pgSet('minus') ?>"><h3>&lsaquo;</h3></a>
    <?php endif ?>

    <?php for ($i=1 ; $i <= $pgCategory->paginate; $i++) : ?>
    <a <?php echo jr_isPg($i) ? 'class="active"' : null ?> href="<?php  echo jr_pgSet($i) ?>" ><h3><?php  echo $i ?></h3></a>
    <?php endfor ?>

    <?php if ($pageNumber < $pgCategory->paginate) : ?>
    <a href="<?php  echo jr_pgSet('plus') ?>"><h3>&rsaquo;</h3></a>
    <a href="<?php  echo jr_pgSet($pgCategory->paginate) ?>"><h3>&raquo;</h3></a>
    <?php endif ?>
  </section>
</nav>

<?php endif ?>
