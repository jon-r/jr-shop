<?php


/* setting transients of some of the most common querys. */
function jrCached_Categories() {
  $transient = get_transient('jr_t_categories');

  if( !empty($transient)) {
    return $transient;
  } else {
    $results = jr_categoryFilter();
    set_transient('jr_t_categories', $results, DAY_IN_SECONDS);
    return $results;
  }
}
function jrCached_Groups() {
  $transient = get_transient('jr_t_groups');

  if( !empty($transient)) {
    return $transient;
  } else {
    $results = jrQ_keywords('group');
    set_transient('jr_t_groups', $results, DAY_IN_SECONDS);
    return $results;
  }
}
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
function jrCached_Brands() {
  $transient = get_transient('jr_t_brands');

  if( !empty($transient)) {
    return $transient;
  } else {
    $results = jr_featuredBrands();
    set_transient('jr_t_brands', $results, HOUR_IN_SECONDS);
    return $results;
  }
}

?>
