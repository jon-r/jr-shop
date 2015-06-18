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
  global $jr_safeArray;
  $t = $jr_safeArray['pgTitle'];

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


//*-- menu-image -----------------------------------------------------------*/
//unqueueing custom CSS, relinking to jquery.form to CDN

function jr_dequeue_menuImage() {
  wp_dequeue_style('menu-image');
}

add_action( 'wp_enqueue_scripts', 'jr_dequeue_menuImage', 11 );
//* -- contact form 7---------------------------------------*/
//setting up a custom form text input
//loosely based on the 'official' text shortcode.
// allows $safeArray variables as defaults

function jr_wpcf7_form_elements( $form ) {
  $form = do_shortcode( $form );
  return $form;
}
add_filter( 'wpcf7_form_elements', 'jr_wpcf7_form_elements' );

function jr_getSubject($atts) {
  global $jr_safeArray;
  $a = shortcode_atts([
    'name' => 'form-custom',
    'class' => '',
    'default' => 'pgTitle'
  ], $atts);

  $inputClass = 'wpcf7-form-control wpcf7-text '.$a['class'];
  $inputDefault = $jr_safeArray[$a['default']];
  $inputVals = ' class="'.$inputClass.'" type="text" aria-invalid="false" value="'.$inputDefault.'" name="'.$a['name'].'"';
  $html = sprintf('<span class="wpcf7-form-control-wrap %1$s"><input %2$s /></span>', $a['name'], $inputVals);
  return $html;
}
add_shortcode("jr-custom-input", "jr_getSubject");

//unqueueing custom CSS
function jr_dequeue_wpcf7() {
  wp_dequeue_style('contact-form-7');
}
add_action( 'wp_enqueue_scripts', 'jr_dequeue_wpcf7', 11 );


?>
