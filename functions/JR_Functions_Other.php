<?php
/*
Misc (non shop specific) functions
*/

// -------------------- sitelinks + other url functions ----------------------------------------

function jr_getUrl() {
  $url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] : 'https://'.$_SERVER["SERVER_NAME"];
  $url .= $_SERVER["REQUEST_URI"];
  return $url;
}

// img location
function jr_imgSrc($itemType,$itemName,$filetype) {
  return 'images/'.$itemType.'/'.$itemName.'.'.$filetype;
}

// turns a title into an array of [Name, URL]
function jr_titleToUrl($in) {
  $out[Name] = $in;
  $out[RefName] = sanitize_title($in);
  return $out;
}

// turns url string into a proper title
function jr_urlToTitle($url,$type) {
  global $jr_getGroup;
  $out = "Not Found";
  $getCategoryColumn = jrQ_categoryColumn();
  if ($type == 'cat') {
    $catUrls = array_map('sanitize_title', $getCategoryColumn);
    if (in_array($url,$catUrls)) {
      $cats = array_combine($getCategoryColumn, $catUrls);
      $out = array_search($url, $cats);
    }
  } elseif ($type == 'grp') {
    $grpUrls = array_map('sanitize_title', $jr_getGroup);
    if (in_array($url,$grpUrls)) {
      $grps = array_combine($jr_getGroup, $grpUrls);
      $out = array_search($url, $grps);
    }
  } elseif ($type == 'brand') {
    $getBrands = jrQ_brandUnique();
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

/* ---- module generator --------------------------------------------------------------*/
//turns 'jr-shop' shortcode into templates on the page
add_shortcode("jr-shop", "jr_modules");

function jr_modules($atts) {
  global $jr_groupArray, $jr_safeArray, $jr_getCategory;
  $a = shortcode_atts([
    'id' => '404',
    'dark' => false
  ], $atts);

  $file = "wp-content/plugins/jr-shop/templates/$atts[id].php";

  echo ($atts[dark]) ? '<div class="dark-block flex-2" >' : null;

  if (file_exists($file)) {
    include($file);
  } else {
    echo "[check $file]";
  }

  echo ($atts[dark]) ? '</div>' : null;

}

/* ---- debug arrays ------------------------------------------------------------------*/
//for testing purposes. will probably keep adding different options
add_shortcode("jr-debug", "jr_debugger");

function jr_debugger() {
  global $jr_safeArray;
  var_dump($jr_safeArray);

//  var_dump( jr_pageCrumbles ($jr_safeArray));

}


// ----------------------breadcrumb builder----------------------------------------------
// Makes the breadcrumbs
function jr_pageCrumbles ($safeArr) {
  $crumbs[0] = ['Home' => home_url()];

  if ($safeArr[rhc] == 'Not Found' || $safeArr[cat] == 'Not Found' || $safeArr[group] == 'Not Found' || is_404()) {

    $crumbs[1] = ['Page Not Found' => home_url()];

  } else {

    if ($safeArr[pgType] == 'Item') {
      $crumbs[1] = [$safeArr[cat] => site_url('/products/'.sanitize_title($safeArr[cat]))];
      $crumbs[2] = [$safeArr[pgName] => jr_getUrl()];
    } else {
      $crumbs[1] = [get_the_title() => jr_pgSet()];
      //page set instead of getURL to reset to page1 on paginated output
    };

  }

  return $crumbs;
}

// ----------------------pg-clips--------------------------------------------------------
// tweaks the 'pg' number from page urls. specifically for category page navigation
// can produce numbers outside range (eg page 0) paginate links should be hidden on front end if at max/min
function jr_pgSet ($pgSet = null, $pgCap = 1) {
  $url = strtok(jr_getUrl(), '?');
  $arrParams = $_GET;
  if (is_int($pgSet)) {
    $arrParams['pg'] = $pgSet;
  } elseif ($pgSet == 'plus') {
    $arrParams['pg'] ? $arrParams['pg']++ : $arrParams['pg'] = 2;
  } elseif ($pgSet == 'minus') {
    $arrParams['pg'] > 1 ? $arrParams['pg']-- : $arrParams['pg'];
  } else {
    unset($arrParams['pg']);
  }

  $urlQueries = http_build_query($arrParams);
  return $url.'?'.$urlQueries;
}

// gets the current page, taking into account no pg value = 1
function jr_isPg($pgNum) {
  $getPg = $_GET['pg'] ? $_GET['pg'] : 1;
  return $getPg == $pgNum ? true : false;
}
