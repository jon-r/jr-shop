<?php
/* this is the first point of action. gets the baseline validateion/variables used on every page.
this stuff is on everypage (in the main menu at very least), so called straight away.
*/
global $jr_safeArr;
$jr_safeArray = jr_validate_urls(jr_getUrl());


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
  $out = [
    'title' => null, 'pgType' => null, 'pgRef' => null,
    'filterType' => null, 'filterVal' => null, 'ss' => false,
    'description' => null, 'saleNum' => null
   /* 'group' => null, 'brand' => null, 'rhc' => null, 'ss' => null */
  ];

  if ($params[1] == '') { // index
    $out['title'] = $out['pgType'] = 'Home';
    $out['pgRef'] = 'Hello';
  } elseif ($params[1]  == 'departments') {
    $out['pgType'] = 'Group';
    $out['filterType'] = 'categories';

    if ($params[2] == 'all') {
      $out['title'] = $out['pgRef'] = "Shop by Category";
      $out['filterVal'] = 'all';
    } else {
      $out['title'] = $out['filterVal'] = jr_urlToTitle($params[2],'grp');
      $out['pgRef'] = 'Browse our '.$out['title'];
    }

  } elseif ($params[1] == 'brands') {
    $out['title'] = $out['pgRef'] =  "Shop by Brand";
    $out['pgType'] = 'Group';
    $out['filterType'] = 'brand';

  } elseif ($params[1] == 'products') {
    $out['pgType'] = 'category';

    if ($params[2] == 'all') {
      $out['pgRef'] = $out['title'] = 'All Products'; //everything
      $out['filterType'] = 'all';
      $out['description'] = jr_categoryInfo('all');

    } elseif ($params[2] == 'search') {
      $out['pgRef'] = $out['title'] = 'Search Results for \''.$_GET['q'].'\'';
      $out['filterType'] = 'search';
      $out['filterVal'] = str_replace(' ', '|', $_GET['q']);
      $out['description'] = jr_categoryInfo('search');

    } elseif ($params[2] == 'category') {
      $out['pgRef'] = 'Browse our '.$out['title'];
      $out['filterType'] = 'items';
      $out['title'] = $out['filterVal'] = jr_urlToTitle($params[3],'cat');

      //should cache these two into the category transient. also maybe add stainless to that table.
      $categoryDetails = jrQ_categoryDesc( $out['title'] );
      $out['ss'] = in_array($out['title'], jrQ_keywords('stainless'));
      $out['description'] = $categoryDetails != '0' ? jr_format($categoryDetails) : null;

    } elseif ($params[2] == 'arrivals') { //new in
      $out['filterType'] = 'new';
      $out['pgRef'] = $out['title'] = 'Just In';
      $out['description'] = jr_categoryInfo('new');

    } elseif ($params[2] == 'coming-soon') { //soon
      $out['filterType'] = 'Soon';
      $out['pgRef'] = $out['title'] = 'Coming Soon';
      $out['description'] = jr_categoryInfo('soon');

    } elseif ($params[2] == 'sold') { //sold
      $out['filterType'] = 'Sold';
      $out['filterVal'] = $GLOBALS['itemCountMax'];
      $out['title'] = $out['pgRef'] = 'Recently Sold';
      $out['description'] = jr_categoryInfo('sold');

    } elseif ($params[2] == 'special-offers') { //sale
      $out['filterType'] = 'Sale';
      $out['filterVal'] = $params[3];
      $out['pgRef'] = $out['title'] = 'Special Offers';
      $out['description'] = jr_categoryInfo('sale');

    } elseif ($params[2] == 'brand') { //brand
      $out['filterType'] = 'Brand';
      $out['filterVal'] =  jr_urlToTitle($params[3],'brand');
      $out['pgRef'] = $out['title'] = 'Products from '.$out['brand'];
    }

  } elseif ($params[1] == 'rhc') { //product
    $out['pgType'] = 'item';

    if (jrQ_rhc($params[2])) {
      $getItem = jrQ_titles($params[2]);
      $out['filterVal'] = $params[2];
      $out['title'] = $getItem['ProductName'];
      $out['filterType'] = $getItem['Category'];
      $out['ss'] = false;
      $out['pgRef'] = 'RHC'.$out['rhc'].' - '.$out['title'];
    } else {
      $out['rhc'] = 'Not Found';
    }
    $out['pgType'] = 'Item';

  } elseif ($params[1] == 'rhcs') { //product-ss
    if (jrQ_rhcs($params[2])) {
      $getItem = jrQ_titles($params[2], $SS = true);
      $out['filterVal'] = $params[2];
      $out['ss'] = true;
      $out['title'] = $getItem['ProductName'];
      $out['filterType'] = $getItem['Category'];
      $out['pgRef'] = 'RHCs'.$out['rhc'].' - '.$out['title'];

    } else {
      $out['filterVal'] = 'Not Found';
    }

  } else { //get the page title if not part of the shop
    $out['pgType'] = $out['title'] = get_the_title();
  };

  return $out;
};

/*function jr_validate_urls($url) {
  $slashedParams = str_replace(site_url(), '', $url);
  $params = explode('/',$slashedParams);
  $out = [
    'pgName' => null, 'pgType' => null, 'pgTitle' => null,
    'group' => null, 'cat' => null,
    'description' => null, 'saleNum' => null,
    'brand' => null, 'rhc' => null, 'ss' => null
  ];

  if ($params[1] == '') {
    $out['pgName'] = $out['pgType'] = 'Home';
    $out['pgTitle'] = 'Hello';
  } elseif ($params[1]  == 'departments') {
    $out['pgType'] = 'Group';
    if ($params[2] == 'all') {
      $out['group'] = 'all';
      $out['pgTitle'] = $out['pgName'] = "Shop by Category";
    } else {
      $out['pgName'] = $out['group'] = jr_urlToTitle($params[2],'grp');
      $out['pgTitle'] = 'Browse our '.$out['pgName'];
    }

  } elseif ($params[1] == 'brands') {
    $out['pgType'] = 'Group';
    $out['group'] = 'brand';
    $out['pgName'] = $out['pgTitle'] = "Shop by Brand";
  } elseif ($params[1] == 'products') {
    $out['pgName'] = $out['cat'] = jr_urlToTitle($params[2],'cat');
    $out['pgTitle'] = 'Browse our '.$out['pgName'];
    $categoryDetails = jrQ_categoryDesc( $out['pgName'] );
    $categoryStainless = in_array($out['pgName'], jrQ_keywords('stainless'));
    $catDesc = $categoryDetails != '0' ? jr_format($categoryDetails) : null;

    if ($params[2] == 'all') {
      $out['pgType'] = 'All';
      $out['pgTitle'] = $out['pgName'] = $out['cat'] = 'All Products'; //everything
      $out['description'] = jr_categoryInfo($out['pgType']);
    } elseif ($params[2] == 'search') {
      $out['pgType'] = 'Search';
      $out['search'] = str_replace(' ', '|', $_GET['q']);
      $out['pgTitle'] = $out['pgName'] = $out['cat'] = 'Search Results for \''.$_GET['q'].'\'';
      $out['description'] = jr_categoryInfo($out['pgType']);

    } elseif ($categoryStainless) {
      $out['pgType'] = 'CategorySS'; //category stainless
      $out['description'] = $catDesc;
    } else { //category
      $out['description'] = $catDesc;
      $out['pgType'] = 'Category';
    }

  } elseif ($params[1] == 'arrivals') { //new in
    $out['pgType'] = 'New';
    $out['pgTitle'] = $out['pgName'] = 'Just In';
    $out['description'] = jr_categoryInfo($out['pgType']);

  } elseif ($params[1] == 'coming-soon') { //soon
    $out['pgType'] = 'Soon';
    $out['pgTitle'] = $out['pgName'] = 'Coming Soon';
    $out['description'] = jr_categoryInfo($out['pgType']);

  } elseif ($params[1] == 'sold') { //sold
    $out['pgName'] = $out['pgTitle'] = 'Recently Sold';
    $out['pgType'] = 'Sold';
    $out['description'] = jr_categoryInfo($out['pgType']);

  } elseif ($params[1] == 'special-offers') { //sale
    $out['pgType'] = 'Sale';
    $out['pgTitle'] = $out['pgName'] = 'Special Offers';
    $out['saleNum'] = $params[2];
    $out['description'] = jr_categoryInfo($out['pgType']);

  } elseif ($params[1] == 'brand') { //brand
    $out['pgType'] = 'Brand';
    $out['brand'] =  jr_urlToTitle($params[2],'brand');
    $out['pgTitle'] = $out['pgName'] = 'Products from '.$out['brand'];
  } elseif ($params[1] == 'rhc') { //product
    if (jrQ_rhc($params[2])) {
      $getItem = jrQ_titles($params[2]);
      $out['rhc'] = $params[2];
      $out['pgName'] = $getItem['ProductName'];
      $out['cat'] = $getItem['Category'];
      $out['ss'] = false;
      $out['pgTitle'] = 'RHC'.$out['rhc'].' - '.$out['pgName'];
    } else {
      $out['rhc'] = 'Not Found';
    }
    $out['pgType'] = 'Item';

  } elseif ($params[1] == 'rhcs') { //product-ss
    if (jrQ_rhcs($params[2])) {
      $getItem = jrQ_titles($params[2], $SS = true);
      $out['rhc'] = $params[2];
      $out['ss'] = true;
      $out['pgName'] = $getItem['ProductName'];
      $out['cat'] = $getItem['Category'];
      $out['pgTitle'] = 'RHCs'.$out['rhc'].' - '.$out['pgName'];
    } else {
      $out['rhc'] = 'Not Found';
    }
    $out['pgType'] = 'Item';

  } else { //get the page title if not part of the shop
    $out['pgType'] = $out['pgName'] = get_the_title();
  };
  return $out;
};*/
?>
