<nav class="container flex-container">

  <div class="flex-5">
    <h3>Shop With Us</h3>
    <?php wp_nav_menu(array(
      'container' => '',                              // remove nav container
      'menu' => __( 'Footer Shop Links', 'bonestheme' ),   // nav name
      'menu_class' => '',                             // adding custom nav class
      'theme_location' => 'footer-shop',             // where it's located in the theme
      'fallback_cb' => ''                             // fallback function
    )); ?>
  </div>

  <div class="flex-5">
    <h3>About Us</h3>
    <?php wp_nav_menu(array(
      'container' => '',                              // remove nav container
      'menu' => __( 'Footer Other Links', 'bonestheme' ),   // nav name
      'menu_class' => '',                  // adding custom nav class
      'theme_location' => 'footer-other',             // where it's located in the theme
      'fallback_cb' => ''                             // fallback function
    )); ?>
  </div>

  <div class="flex-5">
    <h3>Visit Us</h3>
    <br>
    <?php echo jr_linkTo('address') ?>
  </div>

  <div class="flex-5">
    <img src="<?php echo site_url(jr_siteImg('rhc/RHC-Web-Small.png')) ?>" alt="Red Hot Chilli" >
  </div>

  <div class="flex-5">
    <img src="<?php echo site_url(jr_siteImg('icons/fsb.png')) ?>" alt="Federation of Small Businesses" >
  </div>

  <p class="legal flex-1">
    &copy; <?php echo date( 'Y'); ?>
    Red Hot Chilli Northwest Ltd. Company Reg. 08244972. VAT Reg. 878 3946 55. Reg Office: St Georges Court, Northwich, Cheshire CW8 4EE
    <br>
    Tel: <?php echo jr_linkTo('phone') ?>. Email: <?php echo jr_linkTo('eLink') ?>

  </p>
</nav>
