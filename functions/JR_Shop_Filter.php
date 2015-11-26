<?php

//filtered categories
function jr_categoryFilter() {
  $getCategory = jrQ_categories();
  foreach ($getCategory as $c) {
    $out[$c['CategoryGroup']][$c['Name']] = [
      'ID' => $c['Category_ID'],
      'Name' => $c['Name'],
      'RefName' => $c['RefName']
    ];
  }
  ksort($out);
  return $out;
}

function jr_getGroups() {
  $getCategory = jrQ_categories();
  foreach ($getCategory as $category) {
    $n = $category['CategoryGroup'];
    $groups[$n] = sanitize_title($n);
  }
  return $groups;
}

//list of brands from what we have pictures of
function jr_featuredBrands() {
  $brandsAll = jrQ_brands();
  $brandsListed = jr_get_multiple($brandsAll);
  $brandsImages = [];
  foreach ($brandsListed as $brand) {
    $url = sanitize_title($brand);
    $img = jr_siteImg('brands/square/'.$url.'.jpg', $relative = true);
    if (file_exists(ABSPATH.$img)) {
      $brandsImages[$brand] = [
        'Name'    => $brand,
        'RefName' => $url
      ];
    }
  }

  $brandsUnlisted = array_diff($brandsAll, array_keys($brandsImages));

  foreach ($brandsUnlisted as $key=>$brand) {
    if ($brand != '0' && $brand != null) {
      $url = sanitize_title($brand);
      $brandsText[$brand] = [
        'Name'    => $brand,
        'RefName' => $url
      ];
    }
  }

  $out = [
    'images'  => $brandsImages,
    'text'    => $brandsText
  ];

  return $out;
}

//all brands is not cached, only used for search
function jr_allBrands() {
  $getBrands = jrQ_brands();
  foreach($getBrands as $key=>$brand) {
    if ($brand != '0' && $brand != null) {
      $out[$brand] = true;
    }
  }
  return array_keys($out);
}
//only want to show an icon when more than one from that brand
function jr_get_multiple($arrIn) {
  $arrCount = array_count_values($arrIn);
  $arrMultiple = array_filter($arrCount,function($k) {
    return($k > 1);
  });
  return array_keys($arrMultiple);
}

?>
