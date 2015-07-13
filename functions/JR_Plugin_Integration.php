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
  $t = $jr_safeArray['title'];

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

/*function jr_dequeue_menuImage() {
  wp_dequeue_style('menu-image');
}

add_action( 'wp_enqueue_scripts', 'jr_dequeue_menuImage', 11 );*/
//* -- contact form 7---------------------------------------*/
//setting up a custom form text input
//loosely based on the 'official' text shortcode.
// allows $safeArray variables as defaults

function jr_wpcf7_form_elements( $form ) {
  $form = do_shortcode( $form );
  return $form;
}
add_filter( 'wpcf7_form_elements', 'jr_wpcf7_form_elements' );

//add the jr_shop page references
function jr_getSubject($atts) {
  global $jr_safeArray;
  $a = shortcode_atts([
    'name' => 'form-custom',
    'class' => '',
    'default' => 'pgRef'
  ], $atts);

  $inputClass = 'wpcf7-form-control wpcf7-text '.$a['class'];
  $inputDefault = $jr_safeArray[$a['default']];
  $inputVals = ' class="'.$inputClass.'" type="text" aria-invalid="false" value="'.$inputDefault.'" name="'.$a['name'].'"';
  $html = sprintf('<span class="wpcf7-form-control-wrap %1$s"><input %2$s /></span>', $a['name'], $inputVals);
  return $html;
}
add_shortcode("jr-custom-input", "jr_getSubject");

//add the question select list
function jr_getQuestions() {
  $questions = jrCached_FAQ();

  $select1 = '<select id="js-question-in" class="wpcf7-form-control wpcf7-select wpcf7-validates-as-required text-input" aria-invalid="false" aria-required="true" name="question">';
  $select2 = '</select>';
  $option = '<option value="">---</option>';
  $output = '<span id="js-question-out" class="text-output" >Hello<span>';
  foreach ($questions as $faq) {
    $option .= '<option value="'.$faq['question'].'">'.$faq['question'].'</option>';
  }
  $html = sprintf('<span class="wpcf7-form-control-wrap question">%1$s%2$s%3$s</span>%4$s',
                  $select1, $option, $select2, $output);
  return $html;
}
add_shortcode("jr-custom-questions", "jr_getQuestions");

//the answer ajax
function jr_getAnswers() {
  global $jr_safeArray;
  $getQuery = $_GET['keyword'];
  $questions = jrCached_FAQ();


  if ($getQuery != "") {
    $answer = array_search($getQuery, array_column($questions, 'question'));
    $out['answer'] = jr_format($questions[$answer]['answer']);


    $out['next'] = '<div class="btn-red text-icon arrow-r-w">'.$questions[$answer]['next'].'</div>';
  } else {
    $out['answer'] = $out['next'] = "";
  }

  echo json_encode($out);
  wp_die();
}
add_action('wp_ajax_jr_getAnswers', 'jr_getAnswers');
add_action('wp_ajax_nopriv_jr_getAnswers', 'jr_getAnswers');

//unqueueing custom CSS
function jr_dequeue_wpcf7() {
  wp_dequeue_style('contact-form-7');
}
add_action( 'wp_enqueue_scripts', 'jr_dequeue_wpcf7', 11 );


?>
