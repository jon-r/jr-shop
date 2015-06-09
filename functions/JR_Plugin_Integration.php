<?php
/* Linking the shop with other peoples plugins */
//* wordpress


//*-- menu-image -----------------------------------------------------------*/
//unqueueing custom CSS
function jr_dequeue_menuImage() {
  wp_dequeue_style('menu-image');
}
add_action( 'wp_enqueue_scripts', 'jr_dequeue_menuImage', 11 );
//* -- contact form 7---------------------------------------*/
//setting up a custom form text input
add_filter( 'wpcf7_form_elements', 'mycustom_wpcf7_form_elements' );

function mycustom_wpcf7_form_elements( $form ) {
  $form = do_shortcode( $form );
  return $form;
}

add_shortcode("jr-custom-input", "jr_getSubject");
//loosely based on the 'official' text shortcode.
//no validation, but allows $safeArray variables as defaults
function jr_getSubject($atts) {
  global $jr_safeArray;
  $a = shortcode_atts([
    'name' => 'form-custom',
    'class' => '',
    'default' => 'pgName'
  ], $atts);

  $inputClass = 'wpcf7-form-control wpcf7-text '.$a['class'];
  $inputDefault = $jr_safeArray[$a['default']];
  $inputVals = ' class="'.$inputClass.'" type="text" aria-invalid="false" value="'.$inputDefault.'" name="'.$a['name'].'"';
  $html = sprintf('<span class="wpcf7-form-control-wrap %1$s"><input %2$s /></span>', $a['name'], $inputVals);
  return $html;
}

//unqueueing custom CSS
function jr_dequeue_wpcf7() {
  wp_dequeue_style('contact-form-7');
}
add_action( 'wp_enqueue_scripts', 'jr_dequeue_wpcf7', 11 );


?>
