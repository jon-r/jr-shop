<?php
/* most of the cache is set to reset every 7 days.
 in theory (nearly) everything could be set to a year or even endless,
 since a clear cache would be used to hard reset */

function jrCached_HTML($file, $cacheName, $timeInDays) {
  global $jr_safeArray;
  $cachefile = 'cached-files/'.$cacheName.'-cached.html';
  $cachetime = $timeInDays * 86400;

  if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile)) {

    readfile($cachefile);

  } else {
    ob_start();
    include($file);
    echo '<!-- Page '.$cacheName.' cached on '.date(DATE_COOKIE).' -->';
    $fp = fopen($cachefile, 'w');
    fwrite($fp, ob_get_contents());
    fclose($fp);
    ob_get_flush();
  }
}

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

function jrCached_FAQ() {
  $transient = get_transient('jr_t_faq');

  if( !empty($transient)) {
    return $transient;
  } else {
    $results = jrQ_faq();
    set_transient('jr_t_faq', $results, WEEK_IN_SECONDS);
    return $results;
  }
}

/* setting transients of some of the most common querys. */
function jrCached_Categories_Full() {
  $transient = get_transient('jr_t_categories_full');

  if( !empty($transient)) {
    return $transient;
  } else {
    $results = jrQ_categories();
    set_transient('jr_t_categories_full', $results, WEEK_IN_SECONDS);
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
    $results = jr_getGroups();
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
