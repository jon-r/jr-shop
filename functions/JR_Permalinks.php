<?php
/* Setting up url_rewrite names. The ones that arent automagically made by wordpress */
function jr_pageID($type) {
  $pageNum = [
    'grp' =>  '24',
    'cat' =>  '16',
    'item' => '21',
    'srch' => '30'
  ];
  return $pageNum[$type];
}
function jr_setPermalinks() {
  $permalinks = [
    //depts
    '^brands/?'               => jr_pageID('grp'),
    '^departments/([^/]*)/?'  => jr_pageID('grp'),
    //cats
    '^products/special-offers/?'  => jr_pageID('cat'),
    '^products/all/?'             => jr_pageID('cat'),
    '^products/sold/?'            => jr_pageID('cat'),
    //'^products/coming-soon/?'     => jr_pageID('cat'),
    '^products/arrivals/?'        => jr_pageID('cat'),
    '^products/category/([^/]*)/?'=> jr_pageID('cat'),
    '^products/brand/([^/]*)/?'   => jr_pageID('cat'),
    '^products/search-results/?'  => jr_pageID('cat'),
    //items
    '^rhc/([^/]*)/?'  => jr_pageID('item'),
    '^rhcs/([^/]*)/?' => jr_pageID('item')
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
function jr_siteImg($fileName,$relative = false) {
  $image = 'images/'.$fileName;
  $out = $relative ? $image : site_url($image);
  return $out;
}
// turns a title into an array of [Name, URL]
function jr_titleToUrl($in) {
  $out['Name'] = $in;
  $out['RefName'] = sanitize_title($in);
  return $out;
}

// turns url string into a proper brand name.
function jr_urlToBrand($url) {

  $getBrands = jrQ_brands();
  $brandUrls = array_map('sanitize_title', $getBrands);

  if (in_array($url,$brandUrls)) {
    $brands = array_combine($getBrands, $brandUrls);
    $out = array_search($url, $brands);
  }

  return $out;
}

?>
