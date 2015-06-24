<?php

//filtered categories
function jr_categoryFilter() {
  $getCategory = jrQ_categories();
  foreach ($getCategory as $c) {
    $out[$c['CategoryGroup']][] =  $c['Name'];
  }
  return $out;
}
//list of brands from what we have pictures of
function jr_featuredBrands() {
  $brandsAll = jrQ_brands();
  $brandsUnique = array_unique($brandsAll);
  $brandsListed = jr_get_multiple($brandsAll);


  foreach ($brandsListed as $brand) {
    $url = sanitize_title($brand);
    $img = jr_siteImg('brands/square/'.$url.'.jpg');
    if (file_exists($img)) {
      $brandsImages[$brand] = [
        'Name'    => $brand,
        'RefName' => $url
      ];
    }
  }

  $brandsUnlisted = array_diff($brandsUnique, array_keys($brandsImages));

  foreach ($brandsUnlisted as $brand) {
    $url = sanitize_title($brand);
    $brandsText[$brand] = [
      'Name'    => $brand,
      'RefName' => $url
    ];
  }

  $out = [
    'images'  => $brandsImages,
    'text'    => $brandsText
  ];

  return $out;
}
//only want to show an icon when more than one from that brand
function jr_get_multiple($arrIn) {
  $arrCount = array_count_values($arrIn);
  $arrMultiple = array_filter($arrCount,function($k) {
    return($k > 1);
  });
  return array_keys($arrMultiple);
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
    if ($pageNumber == $lastPage && $safeArr['pgType'] != 'Soon') {
      $itemsOnLastPage = $fullItemCount % $itemCountMax;
      $listSold = jrQ_itemsSold($safeArr, $itemsOnLastPage);
    }
  }
  $out['list'] = isset($listSold) ? array_merge($listUnsold, $listSold) : $listUnsold;
  return $out;
}

?>
