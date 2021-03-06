<?php $getCategories = jr_categoryFilter(); ?>

<article class="flex-container">

<?php foreach ($getCategories as $title => $filteredCategories) : ?>

  <header class="article-header flex-1" >
    <h1><?php echo $title ?></h1>
    <a href="#" class="text-right" ><h2>&uarr; Scroll to Top</h2></a>
  </header>
  <?php foreach ($filteredCategories as $category) :
      $link = home_url('/products/category/'.$category['ID'].'/'.$category['RefName']);
      $imgUrl = jr_siteImg('thumbnails/'.$category['RefName'].'.jpg');
  ?>

  <section class="tile-outer list-category flex-4">
    <a href="<?php echo $link ?>" >
      <header class="tile-header red">
        <h2><?php echo $category['Name'] ?></h2>
      </header>
      <img class="framed" src="<?php echo $imgUrl ?>" />
    </a>
  </section>
  <?php endforeach ?>

<?php endforeach ?>

</article>


