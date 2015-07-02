<?php
if ( $jr_safeArray['ss'] ) {
  $shopItem = jr_itemComplile($item, 'listSS');
} else {
  $shopItem = jr_itemComplile($item, 'list', $itemsNew);
}
?>
<section class="shop-tile item btn-icon flex-4 <?php echo trim($shopItem['info'].' '.$shopItem['icon']); ?>">

  <a href="<?php echo site_url($shopItem['webLink']) ?>">
    <div class="shop-tile-header">
      <h3><?php echo $shopItem['name'] ?></h3>
    </div>

    <img src="<?php echo site_url(jr_imgResize($shopItem['imgFirst'], 'tile')); ?>"
         alt="<?php echo $shopItem['name'] ?>">
    <button class="tile-hover more dark-block text-icon-left search-w"></button>
    <?php if ($jr_safeArray['pgType'] == 'CategorySS' || $jr_safeArray['ss'] ) : ?>
    <span class="ss-length btn-red"><h4>Length: </h4><h2><?php echo $shopItem['widthFt'] ?></h2></span>
    <?php endif ?>

    <div class="shop-tile-header">
      <em class="greater"><?php echo $shopItem['price'] ?></em>
      <br>
      <strong><?php echo $shopItem['rhc'] ?></strong>
    </div>
  </a>

</section>

