<?php

// ----------------------image-manipulation----------------------------------------------
// generates resized images.
// stores the resized images as a mini cache
// this also (conveniently) used to dump the "coming soon"
function jr_imgResize ($src, $size) {
  $newSrc = str_replace("gallery", "gallery-$size", $src);
  $reSize = jr_imgSize($size);

  if (jr_imgSizeCheck($src,$size)) {
    $out = $newSrc;
  } elseif (file_exists($src)) {
    $img = wp_get_image_editor( $src );
    $img->resize( $reSize, $reSize, false );
    $img->set_quality( 80 );
    $img->save($newSrc);
    $out = $newSrc;
  } else {
    $out = jr_siteImg('icons/ComingSoon.jpg');
  }
  return $out;
}
// checks if the resized files exist, and if they are up to date.
function jr_imgSizeCheck($src,$size) {
  $newSrc = str_replace("gallery", "gallery-$size", $src);

  if (file_exists($newSrc) && file_exists($src)) {
    $fileCheck = filectime($newSrc) > filectime($src);
  } else {
    $fileCheck = false;
  }
  return $fileCheck;
}
//for ajax to create new carousel
function jr_resizeAjax() {
  $src = $_GET['src'];
  $size = $_GET['size'];
  $out = jr_imgResize ($src, $size);

  echo json_encode($out);
  wp_die();
}
add_action('wp_ajax_jr_resize', 'jr_resizeAjax');
add_action('wp_ajax_nopriv_jr_resize', 'jr_resizeAjax');


?>