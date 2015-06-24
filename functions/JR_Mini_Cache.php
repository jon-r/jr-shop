<?php
/* most of the cache is set to reset every 7 days.
 in theory (nearly) everything could be set to a year or even endless,
 since a clear cache would be used to hard reset */


/* setting the general settings as a year long transient*/
function jrCached_Settings() {
  $transient = get_transient('jr_t_settings');

  if( !empty($transient)) {
    return $transient;
  } else {
    $results = jr_settings_hook();
    set_transient('jr_t_settings', $results, YEAR_IN_SECONDS);
    return $results;
  }
}

/* setting transients of some of the most common querys. */
function jrCached_Category_Names() {
  $transient = get_transient('jr_t_categories');

  if( !empty($transient)) {
    return $transient;
  } else {
    $results = jrQ_categoryNames();
    set_transient('jr_t_categories', $results, WEEK_IN_SECONDS);
    return $results;
  }
}

function jrCached_Categories_Sorted() {
  $transient = get_transient('jr_t_categories_sorted');

  if( !empty($transient)) {
    return $transient;
  } else {
    $results = jr_categoryFilter();
    set_transient('jr_t_categories_sorted', $results, WEEK_IN_SECONDS);
    return $results;
  }
}

function jrCached_Groups() {
  $transient = get_transient('jr_t_groups');

  if( !empty($transient)) {
    return $transient;
  } else {
    $results = jrQ_keywords('group');
    set_transient('jr_t_groups', $results, WEEK_IN_SECONDS);
    return $results;
  }
}

function jrCached_Brands() {
  $transient = get_transient('jr_t_brands');

  if( !empty($transient)) {
    return $transient;
  } else {
    $results = jr_featuredBrands();
    set_transient('jr_t_brands', $results, DAY_IN_SECONDS);
    return $results;
  }
}
?>
