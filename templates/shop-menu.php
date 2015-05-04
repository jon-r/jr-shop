<?php // the list parts generated by the shop ?>

  <li>
    <a class="text-icon arrow-r" href="<?php echo jr_linkTo('all categories') ?>">View Categories</a>
  </li>

<?php foreach($jr_groupArray as $grpName => $grpList) : ?>

  <li><?php echo $grpName ?>
    <ul>
      <h3 class="touch-toggle btn-red text-icon close-w">Back</h3>
      <?php foreach ($grpList as $category) :
          $link = site_url('/products/'.sanitize_title($category[Name]));
      ?>
      <li ><a class="text-icon arrow-r" href="<?php echo $link ?>" ><?php echo $category[Name] ?></a></li>
      <?php endforeach ?>
    </ul>

  </li>

<?php endforeach ?>
