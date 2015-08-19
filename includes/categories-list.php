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
  <header class="article-header flex-1" >
    <h1><?php echo $jr_safeArray['title'] ?></h1>
  </header>

<?php foreach ($getCategories as $title => $filteredCategories) : ?>

  <?php if ($grpFilter == 'all') : ?>
  <header class="sub-header flex-1" >
    <h3><?php echo $title ?></h3>
  </header>
  <?php endif; foreach ($filteredCategories as $category) :
    if ($grpFilter  == 'brand') {
      $link = site_url('products/brand/'.$category['RefName']);
      $imgUrl = jr_siteImg('brands/square/'.$category['RefName'].'.jpg');
    } else {
      $link = site_url('/products/category/'.$category['RefName']);
      $imgUrl = jr_siteImg('thumbnails/'.$category['RefName'].'.jpg');
    }
  ?>

  <section class="tile-outer list-category flex-5">
    <a href="<?php echo $link ?>" >
      <header class="tile-header red">
        <h2><?php echo $category['Name'] ?></h2>
      </header>
      <img class="framed" src="<?php echo site_url($imgUrl) ?>" />
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

  <?php foreach ($otherBrands as $brand) : ?>
  <a href="<?php echo site_url('products/brand/'.$brand['RefName']); ?>">
    <?php echo $brand['Name']; ?>
  </a>
  <?php endforeach; ?>
</article>

<?php endif ?>


