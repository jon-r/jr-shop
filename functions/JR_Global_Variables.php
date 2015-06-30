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


//settings are cached
$jr_config = jrCached_Settings();

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
    'sold items'     => site_url('products/sold/'),
    'new items'      => site_url('new-items/'),
    'soon items'     => site_url('products/coming-soon/'),
    'arrivals'       => site_url('products/arrivals/')
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
    'new'   => $jr_config['pageInfo_arrivals'],
    'soon'  => $jr_config['pageInfo_soon'],
    'sold'  => $jr_config['pageInfo_sold'],
    'sale'  => $jr_config['pageInfo_sale'],
    'search' => $jr_config['pageInfo_search'],
    'all'   =>  $jr_config['pageInfo_all']
  ];
  return jr_format($categoryFilterArr[$catType]);
}

function jr_openingTimes($day = 'weekday') {
  global $jr_config;
  return ($day == 'saturday') ? $jr_config['openingTimes_Sat'] : $jr_config['openingTimes_Week'];
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
