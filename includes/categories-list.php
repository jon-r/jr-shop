<?php
$grpFilter = $jr_safeArray['filterVal'];
if ($grpFilter == 'all') {
  $getCategories = jrCached_Categories_Sorted();
} elseif ($grpFilter == 'brand') {
  $brands = jrCached_Brands();
  $getCategories[$jr_safeArray['title']] = $brands['images'];
  $otherBrands = $brands['text'];

} else {
  $allCategories = jrCached_Categories_Sorted();
  $getCategories[$jr_safeArray['title']] = $allCategories[$grpFilter];
}
?>

<article class="flex-container">

<?php foreach ($getCategories as $title => $filteredCategories) : ?>
  <header class="article-header flex-1" >
    <h1><?php echo $title ?></h1>
  </header>

  <?php foreach ($filteredCategories as $category) :
    if ($grpFilter  == 'brand') {
      $link = site_url('products/brand/'.$category['RefName']);
      $imgUrl = jr_siteImg('brands/square/'.$category['RefName'].'.jpg');
    } else {
      $link = site_url('/products/category/'.$category['RefName']);
      $imgUrl = jr_siteImg('thumbnails/'.$category['RefName'].'.jpg');
    }
  ?>

  <section class="shop-tile category flex-6">
    <a href="<?php echo $link ?>" >
      <div class="shop-tile-header"><h2><?php echo $category['Name'] ?></h2></div>
      <img src="<?php echo site_url($imgUrl) ?>" />
    </a>
  </section>
  <?php endforeach ?>

<?php endforeach ?>

</article>

<?php if ($grpFilter == 'brand') : ?>

<article class="extra-brands">
  <header class="article-header flex-1">
    <h1>Other Brands</h1>
  </header>

  <?php foreach ($otherBrands as $brand) : if ($brand['Name'] != '0' && $brand['Name'] != null) : ?>
  <a href="<?php echo site_url('products/brand/'.$brand['RefName']); ?>">
    <?php echo $brand['Name']; ?>
  </a>
  <?php endif; endforeach; ?>
</article>

<?php endif ?>


