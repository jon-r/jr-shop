<?php

if ($jr_safeArray[group] == 'all') {
  $filteredCategories = $jr_getCategory;
} elseif ($jr_safeArray[group] == 'brand') {
  $filteredCategories = jr_brandsList();
} else {
  $filteredCategories = $jr_groupArray[$jr_safeArray[group]];
}
?>

  <header class="article-header flex-1" >
    <h1><?php echo $jr_safeArray[pgName] ?></h1>
    <?php echo do_shortcode( "[jr-debug]"); ?>
  </header>

<article class="flex-container">




  <?php foreach ($filteredCategories as $category) :

    if ($jr_safeArray[group] == 'brand') {
      $link = site_url('/brand/'.sanitize_title($category[Name]));
      $imgUrl = jr_imgSrc('brands/square',$category[RefName],'jpg');
    } else {
      $category = jr_titleToUrl($category[Name]);
      $link = site_url('/products/'.sanitize_title($category[Name]));
      $imgUrl = jr_imgSrc('thumbnails',$category[RefName],'jpg');
    }
  ?>

    <section class="shop-tile flex-4">
      <a href="<?php echo $link ?>" >
        <div><h3><?php echo $category[Name] ?></h3></div>
        <img src="<?php echo site_url($imgUrl) ?>" />
      </a>
    </section>

  <?php endforeach ?>

</article>

