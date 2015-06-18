<?php
if ($jr_safeArray['group'] == 'all') {
  $filteredCategories = jrQ_categories();
} elseif ($jr_safeArray['group'] == 'brand') {
  $brands = jrCached_Brands();
  $filteredCategories = $brands['images'];
  $otherBrands = $brands['text'];
} else {
  $filteredCategories = $jr_groupArray[$jr_safeArray['group']];
}
?>

<article class="flex-container">
  <header class="article-header flex-1" >
    <h1><?php echo $jr_safeArray['pgName'] ?></h1>
  </header>

<?php foreach ($filteredCategories as $category) :
  if ($jr_safeArray['group'] == 'brand') {
    $link = site_url('/brand/'.$category['RefName']);
    $imgUrl = jr_siteImg('brands/square/'.$category['RefName'].'.jpg');
  } else {
    $category = jr_titleToUrl($category['Name']);
    $link = site_url('/products/'.$category['RefName']);
    $imgUrl = jr_siteImg('thumbnails/'.$category['RefName'].'.jpg');
  }
?>
  <section class="shop-tile category flex-4">
    <a href="<?php echo $link ?>" >
      <div class="shop-tile-header"><h2><?php echo $category['Name'] ?></h2></div>
      <img src="<?php echo site_url($imgUrl) ?>" />
    </a>
  </section>
<?php endforeach ?>

</article>

<?php if ($jr_safeArray['group']=='brand' ) : ?>

<article class="extra-brands">
  <header class="article-header flex-1">
    <h1>Other Brands</h1>
  </header>

  <?php foreach ($otherBrands as $brand) : if ($brand['Name'] != '0' && $brand['Name'] != null) : ?>
  <a href="<?php echo site_url('/brand/'.$brand['RefName']); ?>">
    <?php echo $brand['Name']; ?>
  </a>
  <?php endif; endforeach; ?>
</article>

<?php endif ?>


