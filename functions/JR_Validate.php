<?php
/* SECURITY:
  > Since the Back-End is Based on in house PCs, theres a fairly limited amount that the customer can access.
  > no personal details are to be keeped on internet databases
  > Light security whitlelist sanitises the input to prevent injection just in case.
*/

/*--------------------------------------------------------------------------------------*/


//idea of this function is to act as a wall between input and output.
//the user can input whatever they want, but only strings on this function are used in sql queries
function jr_validate_urls($url) {
  $slashedParams = str_replace(site_url(), '', $url);
  $params = explode('/',$slashedParams);
  $out = null;

  if ($params[1] == '') {
    $out[pgName] = $out[pgType] = 'Home';

  } elseif ($params[1]  == 'departments') {
    $out[pgType] = 'Group';
    if ($params[2] == 'all') {
      $out[pgName] = 'All Categories';
      $out[group] = 'all';
    } else {
      $out[pgName] = $out[group] = jr_urlToTitle($params[2],'grp');
      $out[imgUrl] = jr_imgSrc('icons',$params[2],'jpg');
    }
  } elseif ($params[1] == 'brands') {
    $out[pgType] = 'Group';
    $out[pgName] = 'Browse Brands';
    $out[group] = 'brand';

  } elseif ($params[1] == 'products') {

    $out[pgName] = $out[cat] = jr_urlToTitle($params[2],'cat');
    $out[imgUrl] = jr_imgSrc('thumbnails',$out[pgName],'jpg');

    $categoryDetails = jrQ_categoryRow( $out[pgName] );
    $categoryStainless = in_array($out[pgName], jrQ_keywords('stainless'));



    if ($params[2] == 'all') {
      $out[pgType] = 'All';
      $out[pgName] = $out[cat] = 'All Products'; //everything
      $out[description] = jr_categoryInfo($out[pgType]);

    } elseif ($params[2] == 'search') {
      $out[pgType] = 'Search';
      $out[search] = str_replace(' ', '|', $_GET[q]);
    //  $readableSearch = esc_url($params[3]);
      $out[pgName] = $out[cat] = 'Search Results for \''.$_GET[q].'\'';
      $out[description] = jr_categoryInfo($out[pgType]);
    } elseif ($categoryStainless) {
      $out[pgType] = 'CategorySS'; //category stainless
      $out[description] = $categoryDetails[CategoryDescription];
    } else {
       //category
      $out[description] = $categoryDetails[CategoryDescription];
      $out[pgType] = 'Category';
    }

  } elseif ($params[1] == 'arrivals') { //new in
    $out[pgType] = 'New';
    $out[pgName] = 'Just In';
    $out[description] = jr_categoryInfo($out[pgType]);
  } elseif ($params[1] == 'coming-soon') { //soon
    $out[pgType] = 'Soon';
    $out[pgName] = 'Coming Soon';
    $out[description] = jr_categoryInfo($out[pgType]);
  } elseif ($params[1] == 'sold') { //sold
    $out[pgName] = $out[pgType] = 'Sold';
    $out[description] = jr_categoryInfo($out[pgType]);
  } elseif ($params[1] == 'special-offers') { //sale
    $out[pgType] = 'Sale';
    $out[pgName] = 'Special Offers';
    $out[saleNum] = $params[2];
    $out[description] = jr_categoryInfo($out[pgType]);
  } elseif ($params[1] == 'brand') { //brand
    $out[pgType] = 'Brand';
    $out[brand] =  jr_urlToTitle($params[2],'brand');
    $out[pgName] = 'Products from '.$out[brand];
    $brandIconLocation = jr_imgSrc('icons',$out[brand],'jpg');
    if (file_exists ($brandIconLocation)) {
      $out[imgUrl] = $brandIconLocation;
    };

  } elseif ($params[1] == 'rhc') { //product
    if (jrQ_rhc($params[2])) {
      $getItem = jrQ_titles($params[2]);
      $out[rhc] = $params[2];
      $out[pgName] = $getItem[ProductName];
      $out[cat] = $getItem[Category];
      $out[ss] = false;
    } else {
      $out[rhc] = 'Not Found';
    }

    $out[pgType] = 'Item';

  } elseif ($params[1] == 'rhcs') { //product-ss
    if (jrQ_rhcs($params[2])) {
      $getItem = jrQ_titles($params[2], $SS = true);
      $out[rhc] = $params[2];
      $out[ss] = true;
      $out[pgName] = $getItem[ProductName];
      $out[cat] = $getItem[Category];
    } else {
      $out[rhc] = 'Not Found';
    }
    $out[pgType] = 'Item';
  } else {
    $out[pgType] = $out[pgName] = get_the_title();//get the page title
  };

  return $out;
};


?>
