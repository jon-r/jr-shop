<?php /* list of groups on front page */ ?>

<article class="flex-container">

  <header class="article-header flex-1" >
    <h1>Catering Equipment For Sale</h1>
    <a href="<?php echo jr_linkTo('all items'); ?>">Click here to View All</a>
  </header>

  <?php foreach($jr_groupArray as $grpName => $grpList) :
      $link = site_url('/departments/'.sanitize_title($grpName));
      $grpHeaderImg = site_url(jr_imgSrc('icons','header-'.strtok($grpName, ' '),'jpg'));
  ?>

  <section class="shop-tile group flex-3">

    <a href="<?php echo $link ?>" >
      <img src="<?php echo $grpHeaderImg ?>" alt="<?php echo $grpName ?>"/>
    </a>

    <ul class="flex-container">
      <?php foreach ($grpList as $category) :
          $link = site_url('/products/'.sanitize_title($category[Name]));
      ?>
      <li><a href="<?php echo $link ?>" ><?php echo $category[Name] ?></a></li>
      <?php endforeach ?>
    </ul>

  </section>

  <?php endforeach ?>

</article>
