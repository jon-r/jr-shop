<?php
/*
Misc Functions that are used throughout the plugin itself. Not called directly in output
*/

function jr_getUrl() {
  $url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] : 'https://'.$_SERVER["SERVER_NAME"];
  $url .= $_SERVER["REQUEST_URI"];
  return $url;
}

// img location
function jr_imgSrc($itemType,$itemName,$filetype) {
  return 'images/'.$itemType.'/'.$itemName.'.'.$filetype;
}

//returns if item in group. one level deeper than the normal IN_ARRAY
function jr_isGroup($group) {
  return function ($category) use ($group) {
    return ($category[CategoryGroup] == $group);
  };
}

function jr_groupFilter($group) {
  global $jr_getCategory;
  return array_filter ($jr_getCategory, jr_isGroup($group));
}

//list of major brands, from keywords_db;
function jr_brandsList() {
  $getKeyBrands = jrQ_keywords('brand');

  $out = array_map('jr_brandArrayGen', $getKeyBrands);

  return $out;
}

function jr_brandArrayGen($brand) {
  $out[Name] = $brand;
  $out[RefName] = sanitize_title($brand);
  return $out;
}

//-- readable titles --------------------------------------------------------------------
function jr_urlToTitle($url,$type) {
  global $getGroup, $getCategory;
  $out = "Not Found";
  $getCategoryColumn = jrQ_categoryColumn();
  if ($type == 'cat') {
    $catUrls = array_map('sanitize_title', $getCategoryColumn);
    if (in_array($url,$catUrls)) {
      $cats = array_combine($getCategoryColumn, $catUrls);
      $out = array_search($url, $cats);
    }
  } elseif ($type == 'grp') {
    $grpUrls = array_map('sanitize_title', $getGroup);
    if (in_array($url,$grpUrls)) {
      $grps = array_combine($getGroup, $grpUrls);
      $out = array_search($url, $grps);
    }
  } elseif ($type == 'brand') {
    $getBrands = jr_query_col_unique('Brand', 'networked db');
    $brandUrls = array_map('sanitize_title', $getBrands);

    if (in_array($url,$brandUrls)) {
      $brands = array_combine($getBrands, $brandUrls);
      $out = array_search($url, $brands);
    }
  }
  return $out;
}

// ----------------------image-manipulation----------------------------------------------
// generates resized images.
// to be load on first requirement, does nothing once the file has been made.
// this also (conveniently) used to dump the "coming soon"
function jr_imgResize ($src, $size) {
  $img = wp_get_image_editor( $src );
  $newSrc = str_replace("gallery", "gallery-$size", $src);
  $reSize = jr_imgSize($size);
  $out = $newSrc;
  if (file_exists($newSrc) && file_exists($src)) {
    $dateCheck = filemtime($newSrc) < filemtime($src);
    if ($dateCheck) {
      $img->resize( $reSize, $reSize, false );
      $img->set_quality( 80 );
      $img->save($newSrc);
    }
  } elseif (file_exists($src)) {
    $img->resize( $reSize, $reSize, false );
    $img->set_quality( 80 );
    $img->save($newSrc);
  } else {
    $out = jr_imgSrc(icons,ComingSoon,jpg);
  }

  return $out;
}
