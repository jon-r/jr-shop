<?php
/* this is all the (easily) adjustable variables */

/* adding options to WP_Options table */
add_option('jr_shop_contact_phone','','','yes');
add_option('jr_shop_contact_email','','','yes');
add_option('jr_shop_contact_boss','','','yes');
add_option('jr_shop_contact_address','','','yes');
add_option('jr_shop_contact_facebook','','','yes');
add_option('jr_shop_contact_twitter_id','','','yes');
add_option('jr_shop_contact_linkedin','','','yes');
add_option('jr_shop_itemCountMax','24','','yes');
add_option('jr_shop_itemCountMin','5','','yes');
add_option('jr_shop_itemSoldDuration','90','','yes');
add_option('jr_shop_page_text_arrivals','','','yes');
add_option('jr_shop_page_text_sold','','','yes');
add_option('jr_shop_page_text_sale','','','yes');
add_option('jr_shop_page_text_search','','','yes');
add_option('jr_shop_page_text_all','','','yes');
add_option('jr_shop_Local_Image_Location','','','yes');
add_option('jr_shop_opening_Times_Week','','','yes');
add_option('jr_shop_opening_Times_Sat','','','yes');
add_option('jr_shop_steel_keywords','','','yes');
add_option('jr_shop_hide_scale','','','yes');


function jr_linkTo($target) {

  $linkArr = [
    'facebook'  => get_option('jr_shop_contact_facebook'),
    'twitter'   => 'https://twitter.com/'.get_option('jr_shop_contact_twitter_id'),
    'linkedin'  => get_option('jr_shop_contact_linkedin'),
    'email'     => get_option('jr_shop_contact_email'),
    'eLink'     => '<a href="mailto:'.get_option('jr_shop_contact_email').'">'.get_option('jr_shop_contact_email').'</a>',
    'phone'     => get_option('jr_shop_contact_phone'),
    'address'   => str_replace(';', '<br>', get_option('jr_shop_contact_address')),
    //internal links
    'all categories' => home_url('departments/all/'),
    'all items'      => home_url('products/all/'),
    'all brands'     => home_url('brands/'),
    'sold items'     => home_url('products/sold/'),
    'new items'      => home_url('new-items/'),
    //'soon items'     => home_url('products/coming-soon/'),
    'arrivals'       => home_url('products/arrivals/')
  ];

  return $linkArr[$target];
}


//how long to leave sold items searchable (days)
$itemSoldDuration = get_option('jr_shop_itemSoldDuration');

function jr_openingTimes($day = 'weekday') {

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
