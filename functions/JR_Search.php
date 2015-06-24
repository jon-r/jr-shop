<?php
/*
SEARCH:
  > semi inteligent search input, but fairly vague output if the smart keywords arent triggered
    > the triggers are loose guides, prefer that they are skipped unless 90% likely to be what the customer is looking for.
    > mostly designed to help with common multi word phrases, that an A OR B would break on ("three phase", "blue seal" "oven range")
*/


function jr_smartSearch() {
  $rawSearchTerm = $_GET['search'];
  $safeSearch = preg_replace('/[^\w +-]/i','', $rawSearchTerm );
  $ref = http_build_query(['q' => $safeSearch]);
  $url = site_url("products/search/?$ref");

  if (stripos($rawSearchTerm, "rhc") === 0) {
    $findRef = '/(rhc|rhcs)(\d+)/i';
    $replaceRef ='$1/$2';
    $url =  site_url(preg_replace($findRef, $replaceRef, $safeSearch));
  //the '- brand'/'- category' are teken from the autocomplete. One could type them in manually,
  //but unlikely to unless intentionally knows about this
  } elseif (strpos($rawSearchTerm, "- Category") > 0) {
    $ref = str_replace(" - Category", "", $safeSearch);
    $url = site_url('products/'.sanitize_title($ref));
  } elseif (strpos($rawSearchTerm, "- Brand") > 0) {
    $ref = str_replace(" - Brand", "", $safeSearch);
    $url = site_url('brand/'.sanitize_title($ref));
  }
  return wp_redirect( $url , 301 );
}
//calls the "smart search" function
add_shortcode("jr-search", "jr_smartSearch");
/* ---- autocomplete ------------------------------------------------------------------*/
//setup data for the search autocomplete ajax
//http://code.tutsplus.com/tutorials/add-jquery-autocomplete-to-your-sites-search--wp-25155
function jr_autoComplete() {
  $in = $_GET['keyword'];
  $filteredBrand = array_filter(jrQ_brandUnique(), function($var) {
    return (stripos($var, $_GET['keyword']) !== false);
  });
  $getCats = jrQ_categories();
  $filteredCat = array_filter($getCats, function($var) {
    return (stripos($var['Name'], $_GET['keyword']) !== false);
  });
  $listBrands = array_map('jr_addBrand', $filteredBrand);
  $listCats = array_map('jr_addCategory', $filteredCat['Name']);
  $listFull = array_merge($listCats, $listBrands);

  echo json_encode($listFull);
  wp_die();
}
add_action('wp_ajax_jr_autocomplete', 'jr_autoComplete');
add_action('wp_ajax_nopriv_jr_autocomplete', 'jr_autoComplete');
function jr_addBrand($word) {
  $out = [
    'name' => $word,
    'url' => sanitize_title($word),
    'filter' => 'brand'
  ];
  return $out;
}
function jr_addCategory($word) {
  $out = [
    'name' => $word,
    'url' => sanitize_title($word),
    'filter' => 'products'
  ];
  return $out;
}
?>
