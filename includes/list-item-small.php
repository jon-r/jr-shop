<?php
$product = new compile;
$shopItem = $product->itemCompile($itemTiny,'lite');
?>
<li class="item-inner-list tile-inner">
  <a href="<?php echo home_url($shopItem['webLink']) ?>">
    <img src="<?php echo jr_imgResize($shopItem['imgFirst'], 'thumb'); ?>"
         class="framed" alt="<?php echo $shopItem['name'] ?>">
    <strong class="greater"><?php echo $shopItem['price'] ?></strong>
  </a>
</li>
