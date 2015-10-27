<?php
/* most of the cache is set to reset every 7 days.
 in theory (nearly) everything could be set to a year or even endless,
 since a clear cache would be used to hard reset */

function jrCached_HTML($file, $cacheName, $timeInDays) {
  global $jr_safeArray;
  $cachefile = is_front_page() ? 'rhc/' : '';
  $cachefile .= 'cached-files/'.$cacheName.'-cached.html';
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


/* setting transients of some of the most common querys. */
function jrCached_Categories_Full() {
  $transient = get_transient('jr_t_categories_full');

  if( !empty($transient)) {
    return $transient;
  } else {
    $results = jrQ_categories();
    set_transient('jr_t_categories_full', $results, YEAR_IN_SECONDS);
    return $results;
  }
}


function jrCached_Groups() {
  $transient = get_transient('jr_t_groups');

  if( !empty($transient)) {
    return $transient;
  } else {
    $results = jr_getGroups();
    set_transient('jr_t_groups', $results, YEAR_IN_SECONDS);
    return $results;
  }
}

?>
