<?php
/*
SEARCH:
  > semi inteligent search input, but fairly vague output if the smart keywords arent triggered
  > the triggers are loose guides, prefer that they are skipped unless 90% likely to be what the customer is looking for.
  > mostly designed to help with common multi word phrases, that an A OR B would break on ("three phase", "blue seal" "oven range")
*/


function jr_smartSearch() {
  $rawSearchTerm = $_GET['search'];
  $safeSearch = preg_replace('/[^\w &+-]/i','', $rawSearchTerm );

  $ssToggle = searchSteel($safeSearch);

  $ref = http_build_query(['q' => $safeSearch,'ss' => $ssToggle]);
  $url = home_url("products/search-results/?$ref");

  if (empty($safeSearch)) { //if searching for nothing, show everything.
    $url = home_url("products/all/");

  } elseif (is_numeric($rawSearchTerm)) {
    $url = home_url('rhc/'.$rawSearchTerm);

  } elseif (stripos($rawSearchTerm, "rhc") === 0) {
    $findRef = '/(rhcs?)(\d+)/i';
    $replaceRef ='$1/$2';
    $url =  home_url(strtolower(preg_replace($findRef, $replaceRef, $safeSearch)));

  //the '- brand'/'- category' are taken from the autocomplete. One could type them in manually,
  //but unlikely to unless intentionally knows about this
  } elseif (strpos($rawSearchTerm, "- Category") > 0) {
    $ref = str_replace(" - Category", "", $safeSearch);
    $categoryID = jrQ_categoryID($ref) ?: $ref;
    $url = home_url('products/category/'.$categoryID.'/'.sanitize_title($ref));

  } elseif (strpos($rawSearchTerm, "- Brand") > 0) {
    $ref = str_replace(" - Brand", "", $safeSearch);
    $url = home_url('products/brand/'.sanitize_title($ref));

  }
  return wp_redirect( $url , 301 );
}

//simple check for steel keywords
function searchSteel($str) {
  $steelKeys = explode(',',get_option('jr_shop_steel_keywords'));

  foreach ($steelKeys as $key) {
    if (stripos($str,$key) !== false) return true;
  }
  return false;
}

//calls the "smart search" function
add_shortcode("jr-search", "jr_smartSearch");
/* ---- autocomplete ------------------------------------------------------------------*/
//setup data for the search autocomplete ajax
//http://code.tutsplus.com/tutorials/add-jquery-autocomplete-to-your-sites-search--wp-25155
function jr_autoComplete() {
  $input = $_GET['keyword'];

  $getBrands = jr_allBrands();
  $getCats = jrCached_Categories_Full();
  $listFull = [];

  foreach($getBrands as $brand) {
    if (stripos($brand, $input) !== false) {
      $listFull[] = [
        'name' => $brand,
        'url' => sanitize_title($brand),
        'filter' => 'brand'
      ];
    }
  };

  foreach($getCats as $cat) {
    if (stripos($cat['Name'], $input) !== false) {
      $listFull[] = [
        'name' => $cat['Name'],
        'url' => $cat['Category_ID'].'/'.$cat['RefName'],
        'filter' => 'category'
      ];
    }
  };

  echo json_encode($listFull);
  wp_die();
}
add_action('wp_ajax_jr_autocomplete', 'jr_autoComplete');
add_action('wp_ajax_nopriv_jr_autocomplete', 'jr_autoComplete');

?>
