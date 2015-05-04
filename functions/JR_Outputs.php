<?php
//output functions
//this is where the database is processed into content

/* ---- module generator --------------------------------------------------------------*/
//turns 'jr-shop' shortcode into templates on the page
add_shortcode("jr-shop", "jr_modules");

function jr_modules($atts) {
  global $jr_groupArray, $jr_safeArray;
  $a = shortcode_atts([
    'id' => '404'
  ], $atts);

  $file = "wp-content/plugins/jr-shop/templates/$atts[id].php";

  if (file_exists($file)) {
    include($file);
    echo $blah;
  } else {
    echo "[check $file]";
  }

}

/* ---- debug arrays ------------------------------------------------------------------*/
//for testing purposes. will probably keep adding different options
add_shortcode("jr-debug", "jr_debugger");

function jr_debugger() {
  global $jr_safeArray;
  var_dump($jr_safeArray);

//  var_dump( jr_pageCrumbles ($jr_safeArray));

}

// ----------------------breadcrumb builder----------------------------------------------
// Makes the breadcrumbs
function jr_pageCrumbles ($safeArr) {
  $crumbs[0] = ['Home' => home_url()];

  if ($safeArr[rhc] == 'Not Found' || $safeArr[cat] == 'Not Found' || $safeArr[group] == 'Not Found' || is_404()) {

    $crumbs[1] = ['Page Not Found' => home_url()];

  } else {

    if ($safeArr[pgType] == 'Item') {
      $crumbs[1] = [$safeArr[cat] => site_url('/products/'.sanitize_title($safeArr[cat]))];
      $crumbs[2] = [$safeArr[pgName] => jr_getUrl()];
    } else {
      $crumbs[1] = [$safeArr[pgName] => jr_pgSet()];
      //page set instead of getURL to reset to page1 on paginated output
    };

  }

  return $crumbs;
}

// ---------------------- items list setup ----------------------------------------------
// figures out what to show on output page, based on safeArr and the page number
function jr_itemsList($safeArr,$pageNumber) {
  global $itemCountMax;

  //the full list query will always be the same, since this function is preset to cap at one page
  $listUnsold = jrQ_items($safeArr, $pageNumber);
  $out['paginate'] = false;
  $lastPage = 1;

  if ($safeArr['pgType'] != 'New' && $safeArr['pgType'] != 'Sold') {

    //the "sold" and "new" already capped at a single page, no need to count
    $fullItemCount = jrQ_itemsCount($safeArr);

    //breaks down into pages
    if ($fullItemCount > $itemCountMax) {
      $out['paginate'] = $lastPage = intval(ceil($fullItemCount / $itemCountMax));
    }

    //fills up the last page with sold items
    if ($pageNumber == $lastPage) {
      $itemsOnLastPage = $fullItemCount % $itemCountMax;
      $listSold = jrQ_itemsSold($safeArr, $itemsOnLastPage);

    }
  }

  $out['list'] = $listSold ? array_merge($listUnsold, $listSold) : $listUnsold;

 // $out['debug'] = $fullItemCount;

  return $out;
}

// ----------------------array compiler--------------------------------------------------
// Converts the raw querys into useful blocks of text

function jr_itemComplile($ref,$detail) {
  $out1 = $out2 = [];
  switch ($detail) {
    case 'itemSS' :
      $out1 = [
        height      => $ref[Height] ?: null,
        width       => $ref[Width] ?: null,
        depth       => $ref[Depth] ?: null,
        hFull       => $ref[Height] ? "Height: ".$ref[Height]."mm / ".ceil($ref[Height] / 25.4)." inches" : null,
        wFull       => $ref[Width] ? "Width: ".$ref[Width]."mm / ".ceil($ref[Width] / 25.4)." inches" : null,
        dFull       => $ref[Depth] ? "Depth: ".$ref[Depth]."mm / ".ceil($ref[Depth] / 25.4)." inches" : null,
        desc        => ($ref['Line1'] != " " ? $ref['Line 1']."<br>" : null),
        imgAll      => glob('images/gallery/'.$ref[Image].'*')
      ];
    case 'listSS':
      if ($ref[Quantity] == 0) {
        $priceCheck = 'Sold';
      } elseif ($ref[Price]) {
        $priceCheck = "£".$ref[Price]." + VAT";
      } else {
        $priceCheck = "Price Coming Soon";
      }

      $out2 = [
        webLink     => "rhcs/$ref[RHCs]/".sanitize_title($ref[ProductName]),
        rhc         => "Ref: RHCs".$ref[RHCs],
        name        => $ref[ProductName],
        imgFirst    => jr_imgSrc('gallery',$ref[Image],'jpg'),
        price       => $priceCheck ,
        width       => "$ref[TableinFeet]ft",
        quantity    => $ref[Quantity] > 1 ? $ref[Quantity]." in Stock" : null,
        info        => $ref[Quantity] == 0 ? sold : null
      ];
    break;
    case 'item':
      if ($ref[Brand]) {
        $brandUrl = sanitize_title($ref[Brand]);
        $brandIconLocation = jr_imgSrc('brands',$brandUrl,'jpg');
        $brand = $ref[Brand];
      };
      if ($ref[Wattage] >= 1500) {
        $wattCheck = ($ref[Wattage] / 1000)."kw";
      } elseif ($ref[Wattage] < 1500 && $ref[Wattage] > 0) {
        $wattCheck = $ref[Wattage]." watts";
      } else {
        $wattCheck = null;
      }
      $out1 = [
        height      => $ref[Height] ?: null,
        width       => $ref[Width] ?: null,
        depth       => $ref[Depth] ?: null,
        hFull       => $ref[Height] ? "Height: ".$ref[Height]."mm / ".ceil($ref[Height] / 25.4)." inches" : null,
        wFull       => $ref[Width] ? "Width: ".$ref[Width]."mm / ".ceil($ref[Width] / 25.4)." inches" : null,
        dFull       => $ref[Depth] ? "Depth: ".$ref[Depth]."mm / ".ceil($ref[Depth] / 25.4)." inches" : null,
        desc        => ($ref['Line 1'] != " " ? $ref['Line 1']."<br>" : null).
                          ($ref['Line 2'] != " " ? $ref['Line 2']."<br>" : null).
                          ($ref['Line 3'] != " " ? $ref['Line 3'] : null),
        model       => $ref[Model] ? "Model: ".$ref[Model] : null,
        extra       => $ref[ExtraMeasurements],
        condition   => $ref[Condition] != " " ? $ref[Condition] : null,
        brand       => $brand ?: null,
        brandImg    => file_exists ($brandIconLocation) ? '<img src="'.$brandIconLocation.'" alt="'.$brand.'" >' : null,
        brandLink   => "brand/$brandUrl",
        watt        => $wattCheck,
        imgAll      => glob('images/gallery/'.$ref[Image].'*'),
        category    => $ref[Category]
      ];
    case 'list':
      if ($ref[Quantity] == 0) {
        $priceCheck = '- Sold -';
      } elseif ($ref[Price]) {
        $priceCheck = "£".$ref[Price]." + VAT";
      } else {
        $priceCheck = "Price Coming Soon";
      }
      $catArray = [ $ref[Category], $ref[cat1], $ref[cat2], $ref[cat3] ];
      if (in_array('Fridges', $catArray) && in_array('Freezers', $catArray)) {
        $iconCheck = 'fridge-freezer';
      } elseif (in_array('Fridges', $catArray)) {
        $iconCheck = 'fridge';
      } elseif (in_array('Freezers', $catArray)) {
        $iconCheck = 'freezer';
      } elseif ($ref[Power]) {
        $iconCheck = str_replace(' ', '-', strtolower($ref[Power]));
      };
      if ($ref[IsSoon]) {
        $infoCheck = "soon";
      } elseif ($ref[isSale]) {
        $infoCheck = "sale";
      } elseif ($ref[Quantity] == 0) {
        $infoCheck = "sold";
      } elseif (in_array($ref[RHC], jrx_query_new())) {
        $infoCheck = "new";
      }
      $out2 = [
        icon        => $iconCheck,
        price       => $priceCheck ,
        webLink     => "rhc/$ref[RHC]/".sanitize_title($ref[ProductName]),
        rhc         => "ref: RHC$ref[RHC]",
        name        => $ref[ProductName],
        imgFirst    => jr_imgSrc('gallery',$ref[Image],'jpg'),
        info        => $infoCheck,
        quantity    => $ref[Quantity] > 1 ? $ref[Quantity]." in Stock" : null,
        category    => $ref[Category]
      ];
    break;
  };

  $out = array_merge ($out1,$out2);

  return $out;
};

// ----------------------pg-clips--------------------------------------------------------
// tweaks the 'pg' number from page urls. specifically for category page navigation
// can produce numbers outside range (eg page 0) paginate links should be hidden on front end if at max/min
function jr_pgSet ($pgSet = null, $pgCap = 1) {
  $url = strtok(jr_getUrl(), '?');
  $arrParams = $_GET;
  if (is_int($pgSet)) {
    $arrParams['pg'] = $pgSet;
  } elseif ($pgSet == 'plus') {
    $arrParams['pg'] ? $arrParams['pg']++ : $arrParams['pg'] = 2;
  } elseif ($pgSet == 'minus') {
    $arrParams['pg'] > 1 ? $arrParams['pg']-- : $arrParams['pg'];
  } else {
    unset($arrParams['pg']);
  }

  $urlQueries = http_build_query($arrParams);
  return $url.'?'.$urlQueries;
}

// gets the current page, taking into account no pg value = 1
function jr_isPg($pgNum) {
  $getPg = $_GET['pg'] ? $_GET['pg'] : 1;
  return $getPg == $pgNum ? true : false;
}

//-------------- pick testimonial -----------------------------------------------
// grabs a single testimonial at random
function jr_randomFeedback() {
  $in = jrQ_tesimonial();
  $countIn = count($in) - 1;
  $random = rand(0,$countIn);

  return $in[$random];
}

?>
