<?php //show the box sim, if not furnishings and valid height/width
$badCats = [
  'Soft Furnishings',
  'Tables & Chairs',
  'Decor & Lighting',
  'Dishwashers',
  'Other Stainless Steel'
];

if ($shopItem['height'] > 0
    && $shopItem['width'] > 0
    && !in_array($shopItem['category'], $badCats)
   ) :
?>

<?php $box = jr_boxGen($shopItem) ; ?>
<section class="tile-outer flex-2 item-scale">
  <header class="tile-header lined">
    <h2>Scale</h2>
  </header>

  <div class="tile-inner" >
    <em>(For size only, shape is not accurate)</em>
    <svg xmlns="http://www.w3.org/2000/svg" height="90%" width="100%" viewBox="0 0 500 500">
      <defs>
        <marker id="Tri" viewBox="0 0 8 4" refX="0" refY="2" orient="auto" markerWidth="8" markerHeight="4" >
          <path d="M0 2l8 2v-4z" />
        </marker>
        <marker id="TriEnd" viewBox="0 0 8 4" refX="8" refY="2" orient="auto" markerWidth="8" markerHeight="4" >
          <path d="M8 2l-8 2v-4z" />
        </marker>
      </defs>

      <g stroke="#5A6372" stroke-width="3">
        <path id="floor" d="M5 490h490z" />
        <path id="heightLine" d="<?php echo $box['pathA'] ?>"
              marker-start="url(#Tri)" marker-end="url(#TriEnd)"/>
        <path id="widthLine" d="<?php echo $box['pathB'] ?>"
              marker-start="url(#Tri)" marker-end="url(#TriEnd)"/>
      </g>
      <g>
        <image id="item" xlink:href="<?php echo jr_siteImg('icons/'.$box['itemImg'].'.png')?>"
               preserveAspectRatio="none"
               x="<?php echo $box['itemX'] ?>" y="<?php echo $box['itemY'] ?>"
               height="<?php echo $box['itemH']?>" width="<?php echo $box['itemW'] ?>"  />
        <?php if (isset($box['tableY'])) : ?>
        <image id="table" xlink:href="<?php echo jr_siteImg('icons/'.$box['tableImg'].'.png')?>"
               preserveAspectRatio="none"
               x="<?php echo $box['tableX'] ?>" y="<?php echo $box['tableY'] ?>"
               height="<?php echo $box['tableH']?>" width="<?php echo $box['tableW'] ?>" />
        <?php endif ?>
        <image id="man" xlink:href="<?php echo jr_siteImg('icons/man.png')?>"
               preserveAspectRatio="none" opacity="0.5"
               x="<?php echo $box['manX'] ?>" y="<?php echo $box['manY'] ?>"
               height="<?php echo $box['manH']?>" width="<?php echo $box['manW'] ?>"  />
      </g>
      <g fill="black" font-size="20">
        <text x="<?php echo $box['heightTextX'] ?>" y="<?php echo $box['heightTextY'] ?>" >
          <?php echo $box['heightText'] ?>
        </text>
        <text text-anchor="middle" x="<?php echo $box['widthTextX'] ?>" y="<?php echo $box['widthTextY'] ?>" >
          <?php echo $box['widthText'] ?>
        </text>
        <text x="<?php echo $box['manX'] ?>" y="<?php echo ($box['manY'] + 20) ?>" >man=1750mm</text>
      </g>
    </svg>
  </div>

  <div class="tab-toggle text-icon arrow"></div>
</section>
<?php endif ?>
