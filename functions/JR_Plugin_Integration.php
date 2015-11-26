<?php
/* Linking the shop with other peoples plugins */
//*-- wordpress-------------------------------------------------------------*/
// remove emoji stuff
function disable_wp_emojicons() {

  // all actions related to emojis
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

  // filter to remove TinyMCE emojis
  add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}
add_action( 'init', 'disable_wp_emojicons' );

//fix title for custom shop pages
function jr_wp_title($title, $sep)  {
  global $jr_page;
  $t = $jr_page->title;

  if (is_null($t)) {
    return $title;
  }

  // Add the blog description for the home/front page.
  $site_description = get_bloginfo( 'description', 'display' );
  if ( $site_description && ( is_home() || is_front_page() ) ) {
    $title = "";
  } else {
    $title = " $sep $t";
  }

  return $title;
}
add_filter( 'wp_title', 'jr_wp_title', 10, 2 );

?>
