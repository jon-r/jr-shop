<?php
/*
SEARCH:
  > semi inteligent search input, but fairly vague output if the smart keywords arent triggered
    > the triggers are loose guides, prefer that they are skipped unless 90% likely to be what the customer is looking for.
    > mainly helps with common multi word phrases, that an A OR B would break on ("three phase", "blue seal" "oven range")

Priority:
 	0 > major
 	1 > major brand keywords point to the "filtered by brand" (matching unique brands in network db);
	2 > power (matching keywords_db)
	3 > stainless steel keywords from keywords_db point at a search for stainless tables/sinks/etc (matching keywords_db)
  4 > everything else will be a generic search for "A" OR "B" of whats been typed.

*/


function jr_smartSearch() {
  $searchTerm = $_GET[search];

  $safeSearch = preg_replace('/[^A-Za-z0-9 +-]/','', $searchTerm);

  $ref = http_build_query([q => $safeSearch]);
  $url = site_url("products/search/?$ref");
//RHCs must be first
  if (stripos($searchTerm, "rhcs") === 0)  {
    $ref = str_replace('rhcs','',$searchTerm);
    if (jrQ_rhcs($ref)) {
      $itemSS = jrQ_titles($ref, $SS = true);
      $name = sanitize_title($itemSS[ProductName]);
      $url = site_url("rhcs/$ref/$name");
    } else {
      $ref = http_build_query([q => $safeSearch]);
    }

  } elseif (stripos($searchTerm, "rhc") === 0) {
    $ref = str_replace('rhc','',$searchTerm);
    if (jrQ_rhc($ref)) {
      $item = jrQ_titles($ref);
      $name = sanitize_title($item[ProductName]);
      $url = site_url("rhc/$ref/$name");
    } else {
      $ref = http_build_query([q => $safeSearch]);
    }
  }

 //return $url;
return wp_redirect( $url , 301 );
}

/* ---- search shortcode --------------------------------------------------------------*/
//calls the "smart search" function
add_shortcode("jr-search", "jr_smartSearch");


/* ---- autocomplete ------------------------------------------------------------------*/
//setup data for the search autocomplete ajax
//http://code.tutsplus.com/tutorials/add-jquery-autocomplete-to-your-sites-search--wp-25155

function jr_autoComplete() {
  $in = $_GET[keyword];
  $array = jrQ_brandUnique();

  $filteredBrand = array_filter(jrQ_brandUnique(), function($var) {
    return (stripos($var, $_GET[keyword]) !== false);
  });
  $filteredCat = array_filter(jrQ_categoryColumn(), function($var) {
    return (stripos($var, $_GET[keyword]) !== false);
  });
  $listBrands = array_map('jr_addBrand', $filteredBrand);
  $listCats = array_map('jr_addCategory', $filteredCat);

  $listFull = array_merge($listCats, $listBrands);


  echo json_encode($listFull);


  wp_die();
}


//  $listCats = array_map('jr_addCategory', jrQ_categoryColumn());
//  $listBrands = array_map('jr_addBrand', jrQ_brandUnique());
//
//
//  $listFull = array_merge($listCats, $listBrands);
//  $out = array_filter(jrQ_brandUnique(), jr_strSearch($mystring, $findme))
//
//  //$out = $_GET;
//  echo json_encode($listFull);
//}
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


//  $out['name'] = $word;
//  $out
//  $out['link'] = site_url('/products/'.sanitize_title($word));
  return $out;
}

?>
