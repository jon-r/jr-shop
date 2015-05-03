<?php
  // featured items on front page
?>
<article>

  <?php wp_nav_menu(array(
    'container' => '',                           // remove nav container
    'menu' => __( 'Front Page Links', 'bonestheme' ),  // nav name
    'menu_class' => 'menu-featured flex-container',                             // adding custom nav class
    'theme_location' => 'front-page-list',          // where it's located in the theme
    'items_wrap'      => '<ul class="%2$s">%3$s</ul>',
    'link_before' => '<h3>',                            // before each link
    'link_after' => '</h3>',                             // after each link
    'fallback_cb' => ''                             // fallback function (if there is one)
  )); ?>

</article>
