<?php /* style for the individual items page */
if ( $jr_safeArray['ss'] ) {
  $item = jrQ_item($jr_safeArray['filterVal'], 1);
  $shop_item = jr_itemComplile($item,'itemSS');
} else {
  $item = jrQ_item($jr_safeArray['filterVal']);
  $itemsNew = jrQ_ItemsNew();
  $shop_item = jr_itemComplile($item,'item', $itemsNew);
}
?>

<article class="flex-column photos-frame">

  <section class="tile-outer flex-1 item-gallery">
    <header class="tile-header lined">
      <h1><?php echo $shop_item['name']; ?></h1>
    </header>

      <div id="js-gallery-primary" class="tile-inner btn-icon-lrg <?php echo $shop_item['icon']; ?>">
        <img src="<?php echo jr_imgResize($shop_item['imgFirst'], 'tile') ?>"
             class="framed" alt="<?php echo $shop_item['name'] ?>">
        <button id="js-gallery-zoom" class="tile-float btn-grey text-icon expand-w"><h3>Zoom in</h3></button>
        <?php if (count($shop_item['imgAll'])> 1) : ?>
        <button id="js-gallery-prev" class="tile-button btn-light text-icon-left arrow-l"></button>
        <button id="js-gallery-next" class="tile-button btn-light text-icon arrow-r"></button>
        <?php endif ?>
      </div>

      <div id="js-gallery-modal" class="tile-outer dark modal-frame">
        <div class="modal-close btn-icon close-w"></div>
      </div>

      <?php if (count($shop_item['imgAll'])> 1) : ?>
      <ul id="js-gallery-thumbs" class="flex-container">
        <?php foreach ($shop_item['imgAll'] as $galleryImg) : ?>
        <li class="tile-inner item-thumb">
          <img src="<?php echo jr_imgResize(home_url($galleryImg), 'thumb') ?>"
               class="framed" alt="<?php echo $shop_item['name'] ?>"
               data-tile="<?php echo jr_imgSizeCheck($galleryImg, 'tile') ? 1 : 0 ?>">
        </li>
        <?php endforeach ?>
      </ul>
      <?php endif ?>
  </section>

  <?php if (count($shop_item['imgAll']) < 5) : ?>


  <?php
  /*--------- CLASS TESTS BEGIN HERE --------------------------------------------------\
  \-----------------------------------------------------------------------------------*/
  $rhc = 'RHC'.$jr_safeArray['filterVal'];
  $temp = new productSingle;
  $temp->setRef($rhc);
  $out = $temp->compiler('full');
  var_dump($out);

  ?>
    <div class="hot-chilli-filling flex-1">
      <img src="<?php echo jr_siteImg('rhc/chilli_filling.jpg'); ?>"
         class="framed" alt="Red Hot Chilli - Used Catering Equipment"/>
    </div>
  <?php endif ?>
</article>
<article id="js-tabs-frame" class="flex-column tabs-frame">

  <section class="tile-outer item-info flex-1 active">
    <header class="tile-header lined">
      <h2><?php echo $shop_item['price'] ?> <span class='text-right'><?php echo $shop_item['quantity'] ?></span></h2>
      <h3><?php echo $shop_item['rhc'] ?></h3>
    </header>

    <?php echo $shop_item['desc'] ?>

    <?php if ($shop_item['icon']=="natural-gas") : ?>
    <p><em class="greater">Ask today about conversions to LPG</em></p>
    <?php endif ?>

    <div class="tab-toggle text-icon arrow"></div>
  </section>

<?php include("items-full-popouts.php"); ?>
<?php //hide the specs on the rare occasion of no specs
if (count($shop_item['specs']) != 0): ?>
  <section class="tile-outer flex-2 item-specs">
      <header class="tile-header lined">
        <h2>Specs</h2>
        <span id="js-specs-copy" class="specs-hover">(Copy as Text)</span>
      </header>

      <ul id="js-specs-list" class="item-dimensions tile-inner">

      <?php
        foreach ($shop_item['specs'] as $key => $value) {
          $key = is_int($key) ? "" : "<b>$key:</b>";
          echo "<li>$key $value</li>";
        }
      ?>

      </ul>

      <div class="tab-toggle text-icon arrow"></div>
    </section>
<?php endif ?>

  <?php include('item-scale-box.php') ?>

  <section class="item-features tile-outer dark flex-1">
    <header class="tile-header lined">
      <h2>Features</h2>
    </header>
    <ul >
      <?php if (!$jr_safeArray[ 'ss']) : ?>
      <li class="text-icon-left tick-w">Photos of actual product</li>
      <li class="text-icon-left tick-w">Fully Tested &amp; Cleaned</li>
      <?php endif ?>
      <li class="text-icon-left tick-w">Competitive UK &amp; EU Delivery Quotes</li>
      <li class="text-icon-left tick-w">Finance Options On Request</li>
      <li class="text-icon-left tick-w">Viewing available at our showroom in Warrington, Cheshire</li>
      <li class="text-icon-left tick-w">Aftercare &amp; Warranty</li>
    </ul>
    <div class="social-shares">
      <a class="text-icon-left facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(jr_getUrl()); ?>"><h3>Share on Facebook</h3></a>
      <a class="text-icon-left twitter" href="https://twitter.com/intent/tweet/?url=<?php echo urlencode(jr_getUrl()); ?>&via=RHC_Catering&hashtags=RHC,Catering"><h3>Tweet on Twitter</h3></a>
    </div>

    <div class="tab-toggle text-icon arrow-w"></div>
  </section>

  <?php $related = jrQ_itemsRelated($jr_safeArray); ?>
  <?php if (count($related) > 0) : ?>
    <section class="tile-outer item-related flex-1">
      <header class="tile-header lined">
        <h2>More <?php echo $jr_safeArray['filterVal2']; ?></h2>
      </header>

      <ul class="item-thumbs flex-container">
         <?php foreach ($related as $itemTiny) { include( "list-item-small.php"); } ?>
      </ul>

      <div class="tab-toggle text-icon arrow"></div>
    </section>
  <?php endif ?>
</article>
