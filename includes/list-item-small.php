<?php
if ( $jr_safeArray['ss'] ) {
  $shopItem = jr_itemComplile($itemTiny, 'tinySS');
} else {
  $shopItem = jr_itemComplile($itemTiny, 'tiny');
}
?>
<li class="tile-inner item-thumb">
  <a href="<?php echo site_url($shopItem['webLink']) ?>">
    <em class="greater"><?php echo $shopItem['price'] ?></em>
    <img src="<?php echo site_url(jr_imgResize($shopItem['imgFirst'], 'thumb')); ?>"
         class="framed" alt="<?php echo $shopItem['name'] ?>">
  </a>
</li>
