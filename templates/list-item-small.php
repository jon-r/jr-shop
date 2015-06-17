<?php
$shopItem = ( $jr_safeArray['pgType'] == 'CategorySS' || $jr_safeArray['ss'] ) ?
  jr_itemComplile($item, 'listSS') :
  jr_itemComplile($item, 'list');
?>
<li class="item-tile-inner" >
  <a href="<?php echo site_url($shopItem['webLink']) ?>">
    <h4><?php echo $shopItem['name'] ?></h4>
    <img src="<?php echo site_url(jr_imgResize($shopItem['imgFirst'], 'thumb')); ?>" alt="<?php echo $shopItem['name'] ?>">

    <em class="greater"><?php echo $shopItem['price'] ?></em>
  </a>
</li>
