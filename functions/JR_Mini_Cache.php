<?php
/* most of the cache is set to reset every 7 days.
 in theory (nearly) everything could be set to a year or even endless,
 since a clear cache would be used to hard reset */

function jrCached_HTML($file, $cacheName, $timeInDays) {
  global $jr_safeArray;

  $cachefile = ABSPATH.'cached-files/'.$cacheName.'-cached.html';
  $cachetime = $timeInDays * 86400;

  if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile)) {

    readfile($cachefile);

  } else {
    ob_start();
    include($file);
    echo '<!-- Page '.$cacheName.' cached on '.date(DATE_COOKIE).' -->';
    $htmlMin = compress_page(ob_get_contents());
    $fp = fopen($cachefile, 'w');
    fwrite($fp, $htmlMin);
    fclose($fp);
    ob_get_flush();
  }
}

/* this html doesnt need to be user friendly. the removal of whitespace cuts ~25% of the size.
only like 10kb saving on the front page (compared to the 1-2mb gained by img compression), but it all adds up
http://stackoverflow.com/questions/6225351/how-to-minify-php-page-html-output
*/

function compress_page($buffer) {
  $search = array(
    '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
    '/[^\S ]+\</s',  // strip whitespaces before tags, except space
    '/(\s)+/s'       // shorten multiple whitespace sequences
  );
  $replace = array(
    '>',
    '<',
    '\\1'
  );
  $buffer = preg_replace($search, $replace, $buffer);
  return $buffer;
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

//currently not used, kept just in case.
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
