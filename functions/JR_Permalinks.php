<?php
/* Setting up url_rewrite names. The ones that arent automagically made by wordpress */
function jr_page($pgtype) {
  $pageNum = [
    'grp' =>  '24',
    'cat' =>  '16',
    'item' => '21',
    'srch' => '30'
  ];
  return $pageNum[$pgtype];
}
function jr_setPermalinks() {
  $permalinks = [
    //depts
    '^brands/?'               => jr_page('grp'),
    '^departments/([^/]*)/?'  => jr_page('grp'),
    //cats
    '^products/special-offers/?'  => jr_page('cat'),
    '^products/all/?'             => jr_page('cat'),
    '^products/sold/?'            => jr_page('cat'),
    '^products/coming-soon/?'     => jr_page('cat'),
    '^products/arrivals/?'        => jr_page('cat'),
    '^products/category/([^/]*)/?'=> jr_page('cat'),
    '^products/brand/([^/]*)/?'   => jr_page('cat'),
    '^products/search/([^/]*)/?'  => jr_page('cat'),
    //items
    '^rhc/([^/]*)/?'  => jr_page('item'),
    '^rhcs/([^/]*)/?' => jr_page('item')
  ];

  foreach ($permalinks as $find => $replace) {
    add_rewrite_rule($find, 'index.php?page_id='.$replace, 'top');
  }
}
add_action('init', 'jr_setPermalinks');

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
function jr_siteImg($fileName) {
  return 'images/'.$fileName;
}
// turns a title into an array of [Name, URL]
function jr_titleToUrl($in) {
  $out['Name'] = $in;
  $out['RefName'] = sanitize_title($in);
  return $out;
}

// turns url string into a proper title.
//only works on category, brand, group, as they are databased
function jr_urlToTitle($url,$type) {
  $getGroup = jrCached_Groups();
  $out = "Not Found";
  $getCategories = jrCached_Categories_Full();

  if ($type == 'cat') {
    $catUrls = array_column($getCategories, 'RefName');

    if (in_array($url,$catUrls)) {
      $cat = array_search($url, $catUrls);
      $out = $getCategories[$cat]['Name'];
    }
  } elseif ($type == 'grp') {

    $grpUrls = array_map('sanitize_title', $getGroup);

    if (in_array($url,$grpUrls)) {
      $grps = array_combine($getGroup, $grpUrls);
      $out = array_search($url, $grps);
    }
  } elseif ($type == 'brand') {
    $getBrands = jrQ_brands();
    $brandUrls = array_map('sanitize_title', $getBrands);

    if (in_array($url,$brandUrls)) {
      $brands = array_combine($getBrands, $brandUrls);
      $out = array_search($url, $brands);
    }
  }
  return $out;
}

?>
