<?php
/* this is all the (easily) adjustable variables */
global $jr_config, $itemCountMax, $itemCountMin, $itemSoldDuration;

/* adding options to WP_Options table */
add_option('jr_shop_contact_phone','','','yes');
add_option('jr_shop_contact_email','','','yes');
add_option('jr_shop_contact_address','','','yes');
add_option('jr_shop_contact_facebook','','','yes');
add_option('jr_shop_contact_twitter_id','','','yes');
add_option('jr_shop_contact_linkedin','','','yes');
add_option('jr_shop_itemCountMax','','','yes');
add_option('jr_shop_itemCountMin','','','yes');
add_option('jr_shop_itemSoldDuration','','','yes');
add_option('jr_shop_page_text_arrivals','','','yes');
add_option('jr_shop_page_text_sold','','','yes');
add_option('jr_shop_page_text_sale','','','yes');
add_option('jr_shop_page_text_search','','','yes');
add_option('jr_shop_page_text_all','','','yes');
add_option('jr_shop_Local_Image_Location','','','yes');
add_option('jr_shop_opening_Times_Week','','','yes');
add_option('jr_shop_opening_Times_Sat','','','yes');


//hooks the old settings on this page with the new db settings
//function jr_settings_hook() {
//  $settings = jrQ_settings();
//  foreach ($settings as $x) {
//      $out[$x['option_name']] = $x['option_value'] ;
//    }
//  return $out;
//}


//settings are cached
//$jr_config = jrCached_Settings();

function jr_linkTo($target) {
  global $jr_config;
  $linkArr = [
    'facebook'  => get_option('jr_shop_contact_facebook'),
    'twitter'   => 'https://twitter.com/'.get_option('jr_shop_contact_twitter_id'),
    'linkedin'  => get_option('jr_shop_contact_linkedin'),
    'email'     => get_option('jr_shop_contact_email'),
    'eLink'     => '<a href="mailto:'.get_option('jr_shop_contact_email').'">'.get_option('jr_shop_contact_email').'</a>',
    'phone'     => get_option('jr_shop_contact_phone'),
    'address'   => str_replace(';', '<br>', get_option('jr_shop_contact_address')),
    //internal links
    'all categories' => site_url('departments/all/'),
    'all items'      => site_url('products/all/'),
    'all brands'     => site_url('brands/'),
    'sold items'     => site_url('products/sold/'),
    'new items'      => site_url('new-items/'),
    //'soon items'     => site_url('products/coming-soon/'),
    'arrivals'       => site_url('products/arrivals/')
  ];

  return $linkArr[$target];
}
//how many items before pagination. Also limits some pages.
$itemCountMax = get_option('jr_shop_itemCountMax');
//How many items before the "try elsewhere" kicks in.
$itemCountMin = get_option('jr_shop_itemCountMin');
//how long to leave sold items searchable (days)
$itemSoldDuration = get_option('jr_shop_itemSoldDuration');
/*Category Text \
\ phrases for the category page */
function jr_categoryInfo($catType) {
  global $jr_config;
  $categoryFilterArr = [
    'new'   => get_option('jr_shop_pageInfo_arrivals'),
    'sold'  => get_option('jr_shop_pageInfo_sold'),
    'sale'  => get_option('jr_shop_pageInfo_sale'),
    'search' => get_option('jr_shop_pageInfo_search'),
    'all'   =>  get_option('jr_shop_pageInfo_all')
  ];
  return jr_format($categoryFilterArr[$catType]);
}

function jr_openingTimes($day = 'weekday') {
  global $jr_config;
  return ($day == 'saturday') ? get_option('jr_shop_openingTimes_Sat') : get_option('jr_shop_openingTimes_Week');
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