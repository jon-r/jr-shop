<nav class="container flex-container centre">

  <div class="flex-6">
    <h3>Shop Today</h3>
    <?php wp_nav_menu(array(
      'container' => '',                              // remove nav container
      'menu' => __( 'Footer Shop Links', 'bonestheme' ),   // nav name
      'menu_class' => '',                             // adding custom nav class
      'theme_location' => 'footer-shop',             // where it's located in the theme
      'fallback_cb' => ''                             // fallback function
    )); ?>
  </div>

  <div class="flex-6">
    <h3>About Red Hot Chilli</h3>
    <?php wp_nav_menu(array(
      'container' => '',                              // remove nav container
      'menu' => __( 'Footer Other Links', 'bonestheme' ),   // nav name
      'menu_class' => '',                  // adding custom nav class
      'theme_location' => 'footer-other',             // where it's located in the theme
      'fallback_cb' => ''                             // fallback function
    )); ?>
  </div>

  <div class="flex-6">
    <h3>Visit Us</h3>
    <br>
    <?php echo jr_linkTo('address') ?>
  </div>

  <div class="flex-3">
    <img src="<?php echo jr_siteImg('rhc/web-logo.png') ?>"
         class="framed" alt="Red Hot Chilli" >
  </div>

  <div class="flex-6">
    <img src="<?php echo jr_siteImg('icons/fsb.png') ?>"
         class="framed" alt="Federation of Small Businesses" >
  </div>

  <span class="copyright">© Copyright 2018 - Red Hot Chilli Northwest Ltd. All rights reserved</span>

</nav>
