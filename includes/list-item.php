<?php
$item = $items->pgList[$n];
$product = new compile;

$shopItem = $product->itemCompile($item,'tile',$jr_safeArray['ss'],$newItems);


/*if ( $jr_safeArray['ss'] ) {
  $shopItem = jr_itemComplile($item, 'listSS');
} else {
  $shopItem = jr_itemComplile($item, 'list', $itemsNew);
}*/
?>


<section class="tile-outer list-item btn-icon flex-4 <?php echo trim($shopItem['info'].' '.$shopItem['icon']); ?>">


  <a href="<?php echo home_url($shopItem['webLink']) ?>">
    <header class="tile-header red">
      <h2><?php echo $shopItem['name'] ?></h2>
    </header>

    <img src="<?php echo jr_imgResize($shopItem['imgFirst'], 'tile'); ?>"
         class="framed" alt="<?php echo $shopItem['name'] ?>">
    <button class="tile-float btn-grey text-icon-left search-w"><h3>More</h3></button>

  <?php if ($jr_safeArray['pgType'] == 'CategorySS' || $jr_safeArray['ss'] ) : ?>
    <span class="ss-length btn-red"><h2><?php echo $shopItem['widthFt'] ?></h2></span>
  <?php endif ?>

    <div class="tile-footer">
      <em class="greater"><?php echo $shopItem['price'] ?></em>
      <br>
      <strong><?php echo $shopItem['rhc'] ?></strong>
    </div>
  </a>

</section>


