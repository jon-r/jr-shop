<?php // the list parts generated by the shop
$jr_groupArray = jrCached_Categories_Sorted();
?>
<div class="nav-top flex-container" >

  <div id="js-sticky-left" class="is-fixed left">
    <label class="top-btn left" for="menu-toggle">
      <h2 class="text-icon-left basket-w" >Browse our Store</h2>
    </label>
    <input type="checkbox" class="toggle-check" id="menu-toggle">

    <menu class="nav-left-menu" >
      <ul class="main-menu" id="js-main-list" >
        <?php foreach($jr_groupArray as $grpName => $grpList) : ?>
        <?php $menuHeaderImg = site_url(jr_siteImg('icons/menu-'.strtolower(strtok($grpName, ' ')).'.jpg')); ?>

        <li>
          <h3 class="nav-btn"><?php echo $grpName ?></h3>
          <ul class="sub-menu" style="background-image:url(<?php echo $menuHeaderImg; ?>)">
            <h3 class="touch-toggle text-icon close-w">Back</h3>

            <?php foreach ($grpList as $category) :
              $link=site_url( '/products/category/'.$category['RefName']);
            ?>
            <li><a class="text-icon nav-btn arrow-r" href="<?php echo $link ?>">
                <?php echo $category['Name'] ?>
            </a></li>
            <?php endforeach ?>

          </ul>
        </li>

        <?php endforeach ?>
        <?php // wp menus start here. admin for setup --> ?>

        <li><h3 class="nav-btn">Featured</h3>
          <?php wp_nav_menu(array(
              'container' => '',                           // remove nav container
              'menu' => __( 'Featured Menu Links', 'bonestheme' ),  // nav name
              'before' => '<span class="nav-btn text-icon arrow-r">',
              'after' => '</span>',
              'items_wrap'      => '<ul class="sub-menu" ><h3 class="touch-toggle text-icon close-w">Back</h3>%3$s</ul>',
              'theme_location' => 'featured-menu',         // where it's located in the theme
              'fallback_cb' => ''                          // fallback function (if there is one)
          )); ?>
        </li>

        <li><h3 class="nav-btn">Other Services</h3>
          <?php wp_nav_menu(array(
              'container' => '',
              'menu' => __( 'Services Menu links', 'bonestheme' ),  // nav name
              'before' => '<span class="nav-btn text-icon arrow-r">',
              'after' => '</span>',
              'items_wrap'      => '<ul class="sub-menu" ><h3 class="touch-toggle text-icon close-w">Back</h3>%3$s</ul>',
              'theme_location' => 'services-menu',         // where it's located in the theme
              'fallback_cb' => ''                          // fallback function (if there is one)
          )); ?>
        </li>

      </ul>
    </menu>
  </div>

<?php
  wp_nav_menu(array(
    'container' => '',                           // remove nav container
    'menu' => __( 'Header Bar Links', 'bonestheme' ),  // nav name
    'items_wrap'      => '<ul class="nav-right-menu" >%3$s</ul>',
    'before' => '<h3 class="top-btn">',
    'after' => '</h3>',
    'theme_location' => 'header-bar',         // where it's located in the theme
    'fallback_cb' => ''                          // fallback function (if there is one)
  ));
?>
</div>
