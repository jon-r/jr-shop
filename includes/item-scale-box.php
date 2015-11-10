<?php //show the box sim, if not furnishings and valid height/width
$badCats = [
  'Soft Furnishings',
  'Tables & Chairs',
  'Decor & Lighting',
  'Dishwashers',
  'Other Stainless Steel'
];

if ($item['Height'] > 0
    && $item['Width'] > 0
    && !in_array($item['Category'], $badCats)
   ) :
?>

<?php $box = jr_boxGen($item) ; ?>
<section class="tile-outer flex-2 item-scale">
  <header class="tile-header lined">
    <h2>Scale</h2>
  </header>

  <div class="tile-inner" >
    <em>(For size only, shape is not accurate)</em>
    <svg  xmlns="http://www.w3.org/2000/svg" height="90%" width="100%" viewBox="0 0 500 500">
      <defs>
        <marker id="Triangle" viewBox="0 0 10 10"
                refX="1" refY="5" orient="auto"
                markerWidth="6" markerHeight="6" >
          <path d="M0 0l10 2v4z" />
	    </marker>
      </defs>

      <g stroke="#5A6372" stroke-width="3">
        <path id="floor" d="M5 490h490z" />
        <path id="heightLine" d="<?php echo $box['pathA'] ?>"
              marker-start="url(#Triangle)" marker-end="url(#Triangle)"/>
        <path id="widthLine" d="<?php echo $box['pathB'] ?>"
              marker-start="url(#Triangle)" marker-end="url(#Triangle)"/>
      </g>

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
    </svg>
  </div>

  <div class="tab-toggle text-icon arrow"></div>
</section>
<?php endif ?>
