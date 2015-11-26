<?php
$product = new compile;
$shopItem = $product->itemCompile($itemTiny,'lite');
?>
<li class="item-thumb">
  <a href="<?php echo home_url($shopItem['webLink']) ?>">
    <img src="<?php echo jr_imgResize($shopItem['imgFirst'], 'thumb'); ?>"
         class="framed" alt="<?php echo $shopItem['name'] ?>">
    <em class="greater"><?php echo $shopItem['price'] ?></em>
  </a>
</li>
