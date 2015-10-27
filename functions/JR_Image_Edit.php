<?php

// ----------------------image-manipulation----------------------------------------------
// generates resized images.
// stores the resized images as a mini cache
// this also (conveniently) used to dump the "coming soon"
function jr_imgResize ($src, $size) {
  //wipes the file relativity, to be redadded later
  $relSrc = str_replace(site_url('/'),'',$src);

  $newSrc = str_replace("gallery", "gallery-$size", $relSrc);
  $reSize = jr_imgSize($size);
  if (jr_imgSizeCheck($relSrc,$newSrc)) {
    $out = site_url($newSrc);
  } elseif (file_exists(ABSPATH.$relSrc)) {
    $test = wp_image_editor_supports(['methods' => ['rotate']]) ? 'works' : 'nope';

    //$var_dump($src);
    $img = wp_get_image_editor( ABSPATH.$relSrc );
    $img->resize( $reSize, $reSize, false );
    $img->set_quality( 80 );
    $img->save(ABSPATH.$newSrc);
    $out = site_url($newSrc);
    //$out = $test;
  } else {
    $out = jr_siteImg('icons/ComingSoon.jpg#'.$relSrc);

  }
  return $out;
}
// checks if the resized files exist, and if they are up to date.
function jr_imgSizeCheck($src,$newSrc) {
  //$newSrc = str_replace("gallery", "gallery-$size", $src);

  if (file_exists($newSrc) && file_exists($src)) {
    //$fileCheck = true;
    $fileCheck = filectime($newSrc) > filectime($src);
  } else {
    $fileCheck = false;
  }
  return $fileCheck;
}
//ajax hook
function jr_resizeAjax() {
  $src = $_GET['src'];
  $size = $_GET['size'];

  $out = jr_imgResize ($src, $size);
  //echo $src;
  echo json_encode($out);
  wp_die();
}
add_action('wp_ajax_jr_resize', 'jr_resizeAjax');
add_action('wp_ajax_nopriv_jr_resize', 'jr_resizeAjax');


?>
