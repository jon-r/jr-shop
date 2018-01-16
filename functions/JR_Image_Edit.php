<?php

// ----------------------image-manipulation----------------------------------------------
/**
 * generates resized images.
 * stores the resized images as a mini cache
 * this also (conveniently) used to dump the "coming soon"
 * @param  string $src  the image file location
 * @param  string $size required new file size
 * @return string absolute ref for the new image
 */
function jr_imgResize ($src, $size) {
  //wipes the file relativity, to be redadded later
  $src = str_replace(site_url('/'),'',$src);
  $src = str_replace('rhc/','',$src);
  $newSrc = str_replace("gallery", "gallery-$size", $src);
  $reSize = jr_imgSize($size);

  if (jr_imgSizeCheck($src,$newSrc)) {

    $out = site_url($newSrc);

  } elseif (file_exists(ABSPATH.$src)) {

    $img = wp_get_image_editor(ABSPATH.$src );
    $img->resize( $reSize, $reSize, false );
    $img->set_quality( 80 );
    $img->save(ABSPATH.$newSrc);
    $out = site_url($newSrc);

  } else {

    $out = jr_siteImg('icons/ComingSoon.jpg#'.$src);

  }

  return $out;
}

/**
 * Checks if the resized files exist, and if they are up to date
 * @param  string  $src    full sized image
 * @param  string  $newSrc resezed image
 * @return boolean checks if the files exist before taking time maing new ones.
 */
function jr_imgSizeCheck($src,$newSrc) {

  if (file_exists($newSrc) && file_exists($src)) {

    $fileCheck = filectime($newSrc) > filectime($src);

  } else {

    $fileCheck = false;

  }

  return $fileCheck;
}
/**
 * ajax hook on to the resize plugin
 */
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
