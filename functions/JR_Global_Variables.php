<?php
/* this is all the (easily) adjustable variables */
global $jr_config, $itemCountMax, $itemCountMin, $itemSoldDuration;

//hooks the old settings on this page with the new db settings
function jr_settings_hook() {
  $settings = jrQ_settings();
  foreach ($settings as $x) {
      $out[$x['option_name']] = $x['option_value'] ;
    }
  return $out;
}

$jr_config = jr_settings_hook();

function jr_linkTo($target) {
  global $jr_config;
  $linkArr = [
    'facebook'  => $jr_config['contact_facebook'],
    'twitter'   => 'https://twitter.com/'.$jr_config['contact_twitter_id'],
    'linkedin'  => $jr_config['contact_linkedin'],
    'email'     => $jr_config['contact_email'],
    'eLink'     => '<a href="mailto:info@redhotchilli.catering">'.$jr_config['contact_email'].'</a>',
    'phone'     => $jr_config['contact_phone'],
    'address'   => str_replace(';', '<br>', $jr_config['contact_address']),
    //internal links
    'all categories' => site_url('departments/all/'),
    'all items'      => site_url('products/all/'),
    'all brands'     => site_url('brands/'),
    'sold items'     => site_url('/sold/'),
    'new items'      => site_url('/new-items/'),
    'soon items'     => site_url('/coming-soon/')
  ];

  return $linkArr[$target];
}
//how many items before pagination. Also limits some pages.
$itemCountMax = $jr_config['itemCountMax'];
//How many items before the "try elsewhere" kicks in.
$itemCountMin = $jr_config['itemCountMin'];
//how long to leave sold items searchable (days)
$itemSoldDuration = $jr_config['itemSoldDuration'];
/*Category Text \
\ phrases for the category page */
function jr_categoryInfo($catType) {
  global $jr_config;
  $categoryFilterArr = [
    'New'   => $jr_config['pageInfo_Arrivals'],
    'Soon'  => $jr_config['pageInfo_Soon'],
    'Sold'  => $jr_config['pageInfo_Sold'],
    'Sale'  => $jr_config['pageInfo_Sale'],
    'Search' => $jr_config['pageInfo_Search'],
    'All'   =>  $jr_config['pageInfo_All']
  ];
  return jr_format($categoryFilterArr[$catType]);
}
//Image sizes for generated. would need to wipe gallery-thumb/gallery-tile folders if these are changed. not putting into the variable table since changing these would be part of a larger change
function jr_imgSize($size) {
  $sizeArr = [
    'thumb' => 150,
    'tile'  => 560
  ];
  return $sizeArr[$size];
}
?>
