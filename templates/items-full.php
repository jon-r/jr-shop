

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
<section class="flex-container flex-1">
  <div class="item-gallery flex-2">
    <div class="item-main btn-icon-lrg <?php echo $shop_item[icon]; ?>">
      <img src="<?php echo site_url(jr_imgResize($shop_item[imgFirst], 'tile')) ?>">
    </div>

    <ul class="item-thumbs flex-container">
      <?php foreach ($shop_item[imgAll] as $galleryImg) : ?>
      <li class=""><img src="<?php echo site_url(jr_imgResize($galleryImg, 'thumb')) ?>">
      </li>
      <?php endforeach ?>
    </ul>

    <ul class="item-features flex-1">
      <?php if (!$jr_safeArray[ss]) : ?>
      <li class="text-icon-right tick-w">Photo of actual product</li>
      <li class="text-icon-right tick-w">Fully Tested &amp; Cleaned</li>
      <?php endif ?>
      <li class="text-icon-right tick-w">Competitive UK &amp; EU Delivery Quotes</li>
      <li class="text-icon-right tick-w">Finance Options On Request</li>
      <li class="text-icon-right tick-w">Viewing available at our showroom in Warrington, Cheshire</li>
      <li class="text-icon-right tick-w">Aftercare &amp; Warranty</li>
      <?php if ($shop_item[icon]=="natural-gas" ) : ?>
      <li class="text-icon-right tick-w"><em>Ask today about conversions to LPG</em>
      </li>
      <?php endif ?>
    </ul>

  </div>

<!--  ------------------------------------------------------------------------------  -->

  <div class="item-info flex-2 flex-container">

    <header class="article-header flex-1">
      <h1><?php echo $shop_item[name]; ?></h1>
      <br>
      <h2><?php echo $shop_item[price] ?></h2>
      <?php echo $shop_item[rhc] ?>
    </header>

    <div class="item-description flex-1">
      <h2>About</h2>
      <br>
      <?php echo $shop_item[desc] ?>
      <?php echo $shop_item[condition] ?>
    </div>

  <!--
          <button class="btn-red text-icon basket-w"><h3>Buy Today</h3></button>
          <button class="text-icon question"><h3>Need More Information</h3></button>
          <button class="text-icon list"><h3>Add To Shopping List</h3></button>
  -->
    <div class="item-specs flex-2">
      <h2>Specifications</h2>
      <br>

      <ul class="item-dimensions">
        <li><?php echo $shop_item[hFull] ?></li>
        <li><?php echo $shop_item[wFull] ?></li>
        <li><?php echo $shop_item[dFull] ?></li>
        <?php if ($shop_item[extra]) : ?>
        <li><?php echo $shop_item[extra] ?></li>
        <?php endif; if ($shop_item[watt]) : ?>
        <li><?php echo $shop_item[watt] ?></li>
        <?php endif; ?>
      </ul>

    </div>

    <div class="item-name flex-2">
      <?php if ($shop_item[brand]) : ?>
      <a href="<?php echo site_url($shop_item[brandLink]) ?>">
        <?php echo $shop_item[brandImg] ?: "Brand $shop_item[brand]" ?>
        <em>More from <?php echo $shop_item[brand] ?></em>
      </a>
      <br>
      <?php endif ?>
      <?php echo $shop_item[model] ?>
    </div>

    <?php
      //show the box sim, but only if not furnishings and valid height/width
      if (
      ($item['Height'] > 0 && $item['Width'] > 0) &&
      ($item['Category'] != 'Soft Furnishings' && $item['Category'] != 'Tables & Chairs' && $item['Category'] != 'Décor')
         ) {
      include( "item-boxSim.php");
      }
    ?>


  </div>
</section>
<!-- -------------------------------------------------------------------------------- -->

<!--  </section>-->
</article>
