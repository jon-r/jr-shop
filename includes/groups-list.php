<?php /* list of groups on front page */ ?>
<?php
$jr_groupArray = jrCached_Categories_Sorted();
?>

<article class="flex-container">

<!--
  <header class="article-header flex-1" >
    <h1>Catering Equipment For Sale</h1>
  </header>
-->


<?php foreach($jr_groupArray as $group => $grpList) :
    $grpLink = site_url('/departments/'.sanitize_title($group));
    $grpHeaderImg = site_url(jr_siteImg('icons/Header-'.strtok($group, ' ').'.jpg'));
?>

  <section class="shop-tile group flex-3">
    <img class="framed" src="<?php echo $grpHeaderImg ?>" alt="<?php echo $group ?>"/>
    <ul>
      <?php foreach ($grpList as $category) :
          $link = site_url('/products/category/'.$category['RefName']);
      ?>
      <li>
        <a class="nav-btn" href="<?php echo $link ?>" ><?php echo $category['Name'] ?></a>
      </li>
      <?php endforeach ?>
    </ul>
  </section>

<?php endforeach ?>

</article>

<?php

?>
