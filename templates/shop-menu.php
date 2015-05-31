<?php // the list parts generated by the shop ?>

<li>
  <a class="text-icon arrow-r" href="<?php echo jr_linkTo('all categories') ?>">View All Categories</a>
</li>

<?php foreach($jr_groupArray as $grpName => $grpList) : ?>
  <?php $menuHeaderImg = site_url(jr_siteImg('icons/menu-'.strtok($grpName, ' ').'.jpg')); ?>
<li>
  <?php echo $grpName ?>
  <ul class="sub-menu" style="background-image:url(<?php echo $menuHeaderImg; ?>)">
    <h3 class="touch-toggle btn-red text-icon close-w">Back</h3>

    <?php foreach ($grpList as $category) : $link=s ite_url( '/products/'.sanitize_title($category[Name])); ?>
    <li><a class="text-icon arrow-r" href="<?php echo $link ?>">
        <?php echo $category[Name] ?>
    </a></li>
    <?php endforeach ?>

  </ul>
</li>

<?php endforeach ?>
