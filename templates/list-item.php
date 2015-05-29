<?php
$shopItem = ( $jr_safeArray[pgType] == 'CategorySS' || $jr_safeArray[ss] ) ? jr_itemComplile($item, 'listSS') : jr_itemComplile($item, 'list'); ?>


<section class="shop-tile item btn-icon flex-4 <?php echo trim($shopItem[info].' '.$shopItem[icon]); ?>">

  <a href="<?php echo site_url($shopItem[webLink]) ?>">

    <div>
      <h3><?php echo $shopItem[name] ?></h3>
    </div>

    <img src="<?php echo site_url(jr_imgResize($shopItem[imgFirst], 'tile')); ?>" alt="<?php echo $shopItem[name] ?>">

    <?php if ($jr_safeArray[pgType] == 'CategorySS' || $jr_safeArray[ss] ) : ?>
    <span class="ss-length btn-red"><h4>Length: </h4><h2><?php echo $shopItem[width] ?></h2></span>
    <?php endif ?>

    <div>
      <em><?php echo $shopItem[price] ?></em>
      <br>
      <?php echo $shopItem[rhc] ?>
    </div>

  </a>

</section>

