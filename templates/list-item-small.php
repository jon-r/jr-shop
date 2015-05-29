<?php
$shopItem = ( $jr_safeArray[pgType] == 'CategorySS' || $jr_safeArray[ss] ) ? jr_itemComplile($item, 'listSS') : jr_itemComplile($item, 'list'); ?>
<li class="item-tile-inner" >
  <a href="<?php echo site_url($shopItem[webLink]) ?>">

    <h4><?php echo $shopItem[name] ?></h4>


    <img src="<?php echo site_url(jr_imgResize($shopItem[imgFirst], 'thumb')); ?>" alt="<?php echo $shopItem[name] ?>">

    <?php if ($jr_safeArray[pgType] == 'CategorySS' || $jr_safeArray[ss] ) : ?>
    <span class="ss-length btn-red"><h4>Length: </h4><h2><?php echo $shopItem[width] ?></h2></span>
    <?php endif ?>


    <em><?php echo $shopItem[price] ?></em>

  </a>
</li>
