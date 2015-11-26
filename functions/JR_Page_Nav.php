<?php
// ----------------------breadcrumb builder----------------------------------------------
// Makes the breadcrumbs
function jr_pageCrumbles () {
  global $jr_page;
  $crumbs[0] = ['Home' => home_url()];

  if ($jr_page->title == 'Not Found'  || is_404()) {
    $crumbs[1] = ['Page Not Found' => home_url()];
  } else {
    if (isset($jr_page->args['ref'])) {
      $categoryID = jrQ_categoryID($jr_page->args['category']);
      $crumbs[1] = [
        $jr_page->args['category'] => home_url('/products/category/'.$categoryID.'/'.sanitize_title($jr_page->args['category']))
      ];
      $crumbs[2] = [$jr_page->title => $jr_page->url];
    } elseif (isset($jr_page->title)) {
      $crumbs[1] = [$jr_page->title => jr_pgSet()];
      //page set instead of getURL to reset to page1 on paginated output
    } else {
      $crumbs[1] = [get_the_title() => $jr_page->url];
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
    isset($arrParams['pg']) ? $arrParams['pg']++ : $arrParams['pg'] = 2;
  } elseif ($pgSet == 'minus') {
    $arrParams['pg'] > 1 ? $arrParams['pg']-- : $arrParams['pg'];
  } else {
    unset($arrParams['pg']);
  }
  $urlQueries = http_build_query($arrParams);
  $out = $url;
  $out .= !empty($urlQueries) ? '?'.$urlQueries : null;
  return $out;
}
// gets the current page, taking into account no pg value = 1
function jr_isPg($pgNum) {
  $getPg = isset($_GET['pg']) ? $_GET['pg'] : 1;
  return $getPg == $pgNum ? true : false;
}
?>
