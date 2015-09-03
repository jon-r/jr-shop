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
    'title' => null, 'pgType' => null,
    'count' => $GLOBALS['itemCountMax'], 'ss' => false, 'sold' => false,
    'filterType' => null, 'filterVal' => null, 'filterVal2' => null
  ];

  if ($params[1] == '') { // index
    $out['title'] = $out['pgType'] = 'Home';
    $out['formRef'] = 'Hello';

  } elseif ($params[1]  == 'departments') {
    $out['pgType'] = 'Group';
    $out['filterType'] = 'categories';

    if ($params[2] == 'all') {
      $out['title'] = "Shop by Category";
      $out['filterVal'] = 'all';
    } else {
      $out['title'] = $out['filterVal'] = jr_urlToTitle($params[2],'grp');
    }

  } elseif ($params[1] == 'brands') {
    $out['title'] = "Shop by Brand";
    $out['pgType'] = 'Group';
    $out['filterVal'] = 'brand';

  } elseif ($params[1] == 'products') {
    $out['pgType'] = 'category';

    if ($params[2] == 'all') {
      $out['title'] = 'All Products'; //everything
      $out['filterType'] = 'all';
      $out['filterVal2'] = jr_categoryInfo('all');

    } elseif ($params[2] == 'search-results') {
      $out['title'] = 'Search Results for \''.$_GET['q'].'\'';
      $out['filterType'] = 'search';
      $out['filterVal'] = str_replace(' ', '|', $_GET['q']);
      $out['filterVal2'] = jr_categoryInfo('search');

    } elseif ($params[2] == 'category') {
      $title = jr_urlToTitle($params[3],'cat');
      $out['title'] = $out['filterVal'] = $title;
      $out['filterType'] = 'items';

      $cat = jrCached_Categories_Full();
      $n = array_search($title, array_column($cat, 'Name'));
      $out['filterVal2'] = $cat[$n]['CategoryDescription'] != '0' ? jr_format($cat[$n]['CategoryDescription']) : null;
      $out['catRef'] = $cat[$n]['RefName'];
      $out['ss'] = $cat[$n]['Is_RHCs'] ? true : false;


    } elseif ($params[2] == 'arrivals') { //new in
      $out['filterType'] = 'arrivals';
      $out['title'] = 'Just In';
      $out['filterVal2'] = jr_categoryInfo('new');

    } elseif ($params[2] == 'coming-soon') { //soon
      $out['filterType'] = 'soon';
      $out['title'] = 'Coming Soon';
      $out['filterVal2'] = jr_categoryInfo('soon');

    } elseif ($params[2] == 'sold') { //sold
      $out['sold'] = true;
      $out['filterType'] = 'all';
      $out['title'] = 'Recently Sold';
      $out['filterVal2'] = jr_categoryInfo('sold');

    } elseif ($params[2] == 'special-offers') { //sale
      $out['filterType'] = 'sale';
      $out['filterVal'] = $params[3];
      $out['title'] = 'Special Offers';
      $out['filterVal2'] = jr_categoryInfo('sale');

    } elseif ($params[2] == 'brand') { //brand
      $out['filterType'] = 'brand';
      $out['filterVal'] =  jr_urlToTitle($params[3],'brand');
      $out['title'] = 'Products from '.$out['filterVal'];
    }

  } elseif ($params[1] == 'rhc') { //product
    $out['pgType'] = 'Item';

    if (jrQ_rhc($params[2])) {
      $getItem = jrQ_titles($params[2]);
      $out['filterVal'] = $params[2];
      $out['title'] = $getItem['ProductName'];
      $out['ss'] = false;
      $out['filterVal2'] = $getItem['Category'];
      $out['formRef'] = 'RHC'.$out['filterVal'].' - '.$out['title'];
    } else {
      $out['filterVal'] = 'Not Found';
    }


  } elseif ($params[1] == 'rhcs') { //product-ss
    $out['pgType'] = 'Item';

    if (jrQ_rhcs($params[2])) {
      $getItem = jrQ_titles($params[2], $SS = true);
      $out['filterVal'] = $params[2];
      $out['ss'] = true;
      $out['title'] = $getItem['ProductName'];
      $out['filterVal2'] = $getItem['Category'];
      $out['formRef'] = 'RHCs'.$out['filterVal'].' - '.$out['title'];

    } else {
      $out['filterVal'] = 'Not Found';
    }

  };

  return $out;
};

?>
