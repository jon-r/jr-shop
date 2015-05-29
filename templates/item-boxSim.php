
<section class="item-tile item-scale flex-2">
  <header>
  <h2>Scale</h2>
  </header>
  <em class="lesser">(For size only, shape is not accurate)</em>

  <svg class="item-tile-inner" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 500 500">
  <?php $box = jr_boxGen($item) ; ?>
    <rect id="floor" width="490" height="5" x="5" y="490" fill="#5A6372"  />
    <image id="item" xlink:href="<?php echo site_url(jr_imgSrc('icons', $box[itemImg], 'png'))?>"
           preserveAspectRatio="none"
           x="<?php echo $box[itemX] ?>" y="<?php echo $box[itemY] ?>"
           height="<?php echo $box[itemH]?>" width="<?php echo $box[itemW] ?>"  >
    </image>
    <?php if ($box[tableY]) : ?>
    <image id="table" xlink:href="<?php echo site_url(jr_imgSrc('icons', $box[tableImg], 'png'))?>"
           preserveAspectRatio="none"
           x="<?php echo $box[tableX] ?>" y="<?php echo $box[tableY] ?>"
           height="<?php echo $box[tableH]?>" width="<?php echo $box[tableW] ?>"  >
    </image>
    <?php endif ?>
    <image id="man" xlink:href="<?php echo site_url(jr_imgSrc('icons', 'man', 'png'))?>"
           preserveAspectRatio="none" opacity="0.5"
           x="<?php echo $box[manX] ?>" y="<?php echo $box[manY] ?>"
           height="<?php echo $box[manH]?>" width="<?php echo $box[manW] ?>"  >
    </image>

  </svg>


</section>
