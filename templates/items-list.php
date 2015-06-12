<?php /* Filtered items list */
global $itemCountMin;
$pageNumber = isset($_GET['pg']) ? $_GET['pg'] : 1;
$items = jr_itemsList($jr_safeArray, $pageNumber);
?>

<article class="flex-container">
  <header class="article-header flex-1">
    <h1><?php echo $jr_safeArray['pgName']; ?></h1>
    <p><?php echo $jr_safeArray['description'] ?></p>
  </header>

  <?php
  foreach ($items['list'] as $item) {
    include( "list-item.php");
  }
  ?>
  <?php if(count($items['list']) < $itemCountMin) : ?>

  <section class="flex-1 form-contact wider white-block">
    <header >
      <h2>More in store</h2>
      <span>Sometimes the equipment you need is going through the workshop right now. If interested, call <?php echo jr_linkTo('phone') ?> today and will see if we can get hold of what you need.</span>

    </header>
    <?php echo do_shortcode('[contact-form-7 id="141" title="Contact Form Wide"]'); ?>
  </section>
<?php endif ?>

</article>

<?php if ($items['paginate']) : ?>

<nav class="flex-container ">
  <section class="nav-paginate white-block">
    <?php if ($pageNumber > 1) : ?>
    <a href="<?php  echo jr_pgSet(1) ?>"><h3>&laquo;</h3></a>
    <a href="<?php  echo jr_pgSet('minus') ?>"><h3>&lsaquo;</h3></a>
    <?php endif ?>

    <?php for ($i=1 ; $i <= $items['paginate']; $i++) : ?>
    <a <?php echo jr_isPg($i) ? 'class="active"' : null ?> href="<?php  echo jr_pgSet($i) ?>" ><h3><?php  echo $i ?></h3></a>
    <?php endfor ?>

    <?php if ($pageNumber < $items['paginate']) : ?>
    <a href="<?php  echo jr_pgSet('plus') ?>"><h3>&rsaquo;</h3></a>
    <a href="<?php  echo jr_pgSet($items['paginate']) ?>"><h3>&raquo;</h3></a>
    <?php endif ?>
  </section>
</nav>

<?php endif ?>

