<?php
  // featured items on front page
?>
<article>
  <ul class="menu-featured flex-container" >
    <li class="flex-4">
      <a href="<?php echo site_url('products/arrivals/') ?>" >
        <img src="<?php echo jr_siteImg('icons/btn-arrivals.jpg') ?>" alt="Equipment just in" >
        <h2>Latest Arrivals</h2>
      </a>
    </li>
    <li class="flex-4">
      <a href="<?php echo site_url('brands/') ?>" >
        <img src="<?php echo jr_siteImg('icons/btn-brands.jpg') ?>" alt="Shop By Brands" >
        <h2>Brands</h2>
      </a>
    </li>
    <li class="flex-4">
      <a href="<?php echo site_url('services/') ?>" >
        <img src="<?php echo jr_siteImg('icons/btn-services.jpg') ?>" alt="Our Other Services" >
        <h2>Other Services</h2>
      </a>
    </li>
    <li class="flex-4">
      <a href="<?php echo site_url('about/') ?>" >
        <img src="<?php echo jr_siteImg('icons/btn-about.jpg') ?>" alt="About Us" >
        <h2>About Red Hot Chilli</h2>
      </a>
    </li>
  </ul>

  <?php /*wp_nav_menu(array(
    'container' => '',                           // remove nav container
    'menu' => __( 'Front Page Links', 'bonestheme' ),  // nav name
    'menu_class' => 'menu-featured flex-container',                             // adding custom nav class
    'theme_location' => 'front-page-list',          // where it's located in the theme
    'items_wrap'      => '<ul class="%2$s">%3$s</ul>',
    'link_before' => '<h2>',                            // before each link
    'link_after' => '</h2>',                             // after each link
    'fallback_cb' => ''                             // fallback function (if there is one)
  )); */?>

</article>