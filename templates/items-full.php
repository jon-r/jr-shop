

<?php /* style for the individual items page */

if ($jr_safeArray[ss]) {
    $item = jrQ_item($jr_safeArray[rhc], 1);
    $shop_item = jr_itemComplile($item,'itemSS');
  } else {
    $item = jrQ_item($jr_safeArray[rhc]);
    $shop_item = jr_itemComplile($item,'item');
  }

?>

<article class="flex-column">

  <!--  ------------------------------------------------------------------------------  -->
  <section class="item-tile flex-1">

    <div id="js-gallery-primary" class="item-tile-inner btn-icon-lrg <?php echo $shop_item[icon]; ?>">
      <img src="<?php echo site_url(jr_imgResize($shop_item[imgFirst], 'tile')) ?>"
           alt="<?php echo $shop_item[name] ?>">
      <button class="item-main-zoom dark-block text-icon-left expand-w"></button>

      <?php if (count($shop_item[imgAll])> 1) : ?>
      <button id="js-gallery-prev" class="gallery-nav text-icon-left arrow-l"></button>
      <button id="js-gallery-next" class="gallery-nav text-icon arrow-r"></button>
      <?php endif ?>
    </div>
    <div id="js-gallery-modal" class="modal image dark-block">
      <div class="modal-close btn-icon close-w"></div>
    </div>

    <?php if (count($shop_item[imgAll])> 1) : ?>
    <ul id="js-gallery-thumbs" class="item-thumbs flex-container">
      <?php foreach ($shop_item[imgAll] as $galleryImg) : ?>
      <li class="item-tile-inner">
        <img src="<?php echo site_url(jr_imgResize($galleryImg, 'thumb')) ?>"
             alt="<?php echo $shop_item[name] ?>"
             data-tile="<?php echo jr_imgSizeCheck($galleryImg, 'tile') ? 1 : 0 ?>">
      </li>
      <?php endforeach ?>
    </ul>
    <?php endif ?>

  </section>
</article>
<article class="flex-column">
  <!--  ------------------------------------------------------------------------------  -->
  <section class="item-tile item-info flex-1 ">
    <header>
      <h1><?php echo $shop_item[name]; ?></h1>
      <br>
      <h2><?php echo $shop_item[price] ?></h2>
      <em class="lesser"><?php echo $shop_item[rhc] ?></em>
    </header>

    <p>
      <?php echo $shop_item[desc] ?>
      <?php echo $shop_item[condition] ?>
    </p>
    <?php if ($shop_item[icon]=="natural-gas" ) : ?>
    <em>Ask today about conversions to LPG</em>
    <?php endif ?>

    <?php // purchasing options not quite ready yet
  //include("items-full-popouts.php"); ?>

  </section>

  <ul class="item-features dark-block flex-1">
    <?php if (!$jr_safeArray[ss]) : ?>
    <li class="text-icon-left tick-w">Photos of actual product</li>
    <li class="text-icon-left tick-w">Fully Tested &amp; Cleaned</li>
    <?php endif ?>
    <li class="text-icon-left tick-w">Competitive UK &amp; EU Delivery Quotes</li>
    <li class="text-icon-left tick-w">Finance Options On Request</li>
    <li class="text-icon-left tick-w">Viewing available at our showroom in Warrington, Cheshire</li>
    <li class="text-icon-left tick-w">Aftercare &amp; Warranty</li>
    <aside class="social-shares">
      <a class="text-icon-left facebook" target="_blank"
         href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(jr_getUrl()); ?>" >
        Share on Facebook</a>
      <a class="text-icon-left twitter"
         href="https://twitter.com/intent/tweet/?url=<?php echo urlencode(jr_getUrl()); ?>&via=RHC_Catering&hashtags=RHC,Catering">
        Share on Twitter</a>
    </aside>
  </ul>

  <section class="item-tile flex-2 item-specs">
    <header>
      <h2>Specs</h2>
    </header>

    <ul class="item-dimensions item-tile-inner">
    <?php foreach ([
        $shop_item[brandName], $shop_item[brandLink],
        $shop_item[model], $shop_item[hFull],
        $shop_item[wFull], $shop_item[dFull],
        $shop_item[power], $shop_item[extra]
      ] as $spec) {
        echo $spec ? "<li>$spec</li>" : null;
      } ?>
    </ul>

  </section>

  <?php
    //show the box sim, but only if not furnishings and valid height/width
    if (
    ($item['Height'] > 0 && $item['Width'] > 0) &&
    ($item['Category'] != 'Soft Furnishings' && $item['Category'] != 'Tables & Chairs' && $item['Category'] != 'Decor & Lighting')
       ) {
    include( "item-boxSim.php");
    }
  ?>
  <?php $related = jrQ_iremsRelated($jr_safeArray); ?>

  <?php if (count($related) > 0) : ?>
  <section class="item-tile item-related flex-1">

    <header >
      <h2>More <?php echo $jr_safeArray[cat]; ?></h2>
    </header>
    <ul id="js-gallery-thumbs" class="item-thumbs flex-container">
       <?php foreach ($related as $item) { include( "list-item-small.php"); } ?>
    </ul>

  </section>
  <?php endif ?>

<!-- -------------------------------------------------------------------------------- -->
</article>
