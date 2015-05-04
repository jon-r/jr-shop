

<?php /* style for the individual items page */


if ($jr_safeArray[ss]) {
    $item = jrQ_item($jr_safeArray[rhc], 1);
    $shop_item = jr_itemComplile($item,'itemSS');
  } else {
    $item = jrQ_item($jr_safeArray[rhc]);
    $shop_item = jr_itemComplile($item,'item');
  }


?>



<article class="flex-container">

<!--  ------------------------------------------------------------------------------  -->

    <section class="item-gallery flex-2">
      <div class="item-main btn-icon <?php echo $shop_item[icon]; ?>" >
        <img src="<?php echo site_url(jr_imgResize($shop_item[imgFirst], 'tile')) ?>">
      </div>

      <ul class="item-thumbs flex-container">
        <?php foreach ($shop_item[imgAll] as $galleryImg) : ?>
        <li class=""><img src="<?php echo site_url(jr_imgResize($galleryImg, 'thumb')) ?>"></li>
        <?php endforeach ?>
      </ul>

    </section>

<!--  ------------------------------------------------------------------------------  -->

    <div class="item-info flex-2 flex-container">

      <header class="article-header flex-1">
        <h1><?php echo $shop_item[name]; ?></h1><br>
        <h2><?php echo $shop_item[price] ?></h2>
        <?php echo $shop_item[rhc] ?>
      </header>


      <div class="flex-2" >

        <button class="btn-red text-icon basket-w"><h3>Buy Today</h3></button>
        <button class="text-icon question"><h3>Need More Information</h3></button>
        <button class="text-icon list"><h3>Add To Shopping List</h3></button>

        <?php if ($shop_item[brand]) : ?>
          <?php echo $shop_item[brandImg] ?: "Brand $shop_item[brand]" ?>
          <a href="<?php echo site_url($shop_item[brandLink]) ?>"><em>More from <?php echo $shop_item[brand] ?></em></a>
        <?php endif ?>
        <?php echo $shop_item[model] ?>
      </div>

      <ul class="item-features flex-2">
        <?php if (!$jr_safeArray[ss]) : ?>
        <li class="text-icon tick">Photo of actual product</li>
        <li class="text-icon tick">Fully Tested &amp; Cleaned</li>
        <?php endif ?>
        <li class="text-icon tick">Competitive UK &amp; EU Delivery Quotes</li>
        <li class="text-icon tick">Finance Options On Request</li>
        <li class="text-icon tick">Viewing available at our showroom in Warrington, Cheshire</li>
        <li class="text-icon tick">Aftercare &amp; Warranty</li>
        <?php if ($shop_item[icon] == "natural-gas") : ?>
        <li class="text-icon tick"><em>Ask today about conversions to LPG</em></li>
        <?php endif ?>
      </ul>

      <div class="item-description flex-1">
        <?php echo $shop_item[desc] ?>
        <?php echo $shop_item[condition] ?>

      </div>



      <ul class="item-dimensions flex-1">
        <li><?php echo $shop_item[hFull] ?></li>
        <li><?php echo $shop_item[wFull] ?></li>
        <li><?php echo $shop_item[dFull] ?></li>
        <li><?php echo $shop_item[extra] ?></li>
      </ul>


    </div>


<!--  ------------------------------------------------------------------------------  -->

    <div class="">


        <!--
        <aside class="item-3d">
          <?php $box_xyz = jrx_box_3d($shop_item[height], $shop_item[width], $shop_item[depth]) ?>
          <div data-value="<?php echo $box_xyz[man] ?>" class="box-floor">
            <div class="box-x" data-value="<?php echo $box_xyz[width] ?>">x width: <?php echo $shop_item[width] ?></div>
            <div class="box-y" data-value="<?php echo $box_xyz[depth] ?>">y depth: <?php echo $shop_item[depth] ?></div>
            <div class="box-z" data-value="<?php echo $box_xyz[height] ?>">z height: <?php echo $shop_item[height] ?></div>
          </div>
        </aside> -->


    </div>

<!-- -------------------------------------------------------------------------------- -->

<!--  </section>-->
</article>
