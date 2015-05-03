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
  global $getCategory;
  return array_filter ($getCategory, jr_isGroup($group));
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
