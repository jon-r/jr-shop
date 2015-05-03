      <nav class="container flex-container">

          <?php wp_nav_menu(array(
            'container' => '',                              // remove nav container
            'menu' => __( 'Header Links', 'bonestheme' ),   // nav name
            'menu_class' => 'menu-header flex-4',                             // adding custom nav class
            'theme_location' => 'header-links',             // where it's located in the theme
            'fallback_cb' => ''                             // fallback function
          )); ?>

          <?php wp_nav_menu(array(
            'container' => '',                              // remove nav container
            'menu' => __( 'Footer Links', 'bonestheme' ),   // nav name
            'menu_class' => 'menu-footer flex-4',                  // adding custom nav class
            'theme_location' => 'footer-links',             // where it's located in the theme
            'fallback_cb' => ''                             // fallback function
          )); ?>

        <p class="legal flex-1">
          &copy; <?php echo date( 'Y'); ?>
          Red Hot Chilli Northwest Ltd. Company Reg. 08244972. VAT Reg. 878 3946 55. Reg Office: St Georges Court, Northwich, Cheshire CW8 4EE
          <br>
          Tel: <?php echo link_to(phone) ?>. Email: <a href="mailto:<?php echo link_to(email) ?>"><?php echo link_to(email) ?></a>

        </p>
      </nav>
