<?php /* Filtered items list */
$itemCountMin = get_option('jr_shop_itemCountMin');

$pgCategory = new itemList;
$pgCategory->get();
?>

<article class="flex-container">
  <header class="article-header flex-1">
    <h1><?php echo $jr_page->title ?></h1>
    <p><?php echo $jr_page->desc ?></p>
  </header>


<?php
  for ($n = 0; $n < $pgCategory->pgCount; $n++) :
    include( "list-item.php"); if ($n % 8 == 7) :

  ?>
  <section class="flex-1">
    <a href="#" class="text-right" ><h2>&uarr; Scroll to Top</h2></a>
  </section>
<?php endif; endfor; ?>
<?php if($pgCategory->pgCount < $itemCountMin) : ?>
  <?php if (in_array($pgCategory->pgCount, [1,2,5,6])) : ?>

  <section class="flex-2 tile-outer hide-mobile" style="background-image:url(<?php echo jr_siteImg('icons/more-stock.jpg'); ?>);background-size:cover">
    <h1>More in Store</h1>
    <p >The team at Red Hot Chilli keep the site up to date as fast as we can.
    </p>
    <p style="margin-bottom: 10%">However, sometimes the equipment you need is going through the workshop right now. If interested, call <?php echo jr_linkTo('phone') ?> today and will see if we can get hold of what you need.</p>
    <a href="#contact" >
      <button  class="btn-red full-width" >
        <h2 class="text-icon arrow-w">Contact Us</h2>
      </button>
    </a>
  </section>
  <?php endif ?>
  <section id="contact" class="flex-1 form-contact wider tile-outer dark">
    <header class="tile-header lined">
      <h2>Ask us about <?php echo $jr_page->title ?></h2>
    </header>

    <?php include('form-base.php') ?>
  </section>
<?php endif ?>
</article>

<?php if ($pgCategory->paginate) :
  $pageNumber = isset($_GET['pg']) ? $_GET['pg'] : 1; ?>

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
