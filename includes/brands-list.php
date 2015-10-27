<?php
$brands = jr_featuredBrands();
$keyBrands = $brands['images'];
$otherBrands = $brands['text'];
?>

<article class="flex-container">

  <header class="article-header flex-1" >
    <h1>Browse Popular Brands</h1>
  </header>

  <?php foreach ($keyBrands as $brand) :
    $link = home_url('products/brand/'.$brand['RefName']);
    $imgUrl = jr_siteImg('brands/square/'.$brand['RefName'].'.jpg');
  ?>
  <section class="tile-outer list-category flex-6">
    <a href="<?php echo $link ?>" >
      <header class="tile-header red">
        <h2><?php echo $brand['Name'] ?></h2>
      </header>
      <img class="framed" src="<?php echo $imgUrl ?>" />
    </a>
  </section>
  <?php endforeach ?>

</article>

<article class="extra-brands">
  <header class="article-header flex-1">
    <h1>Other Brands</h1>
  </header>

  <?php foreach ($otherBrands as $brand) : ?>
  <a href="<?php echo home_url('products/brand/'.$brand['RefName']); ?>">
    <?php echo $brand['Name']; ?>
  </a>
  <?php endforeach; ?>
</article>

