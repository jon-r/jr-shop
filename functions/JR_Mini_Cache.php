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

/* --- cache clearing -----------------------------------------------------------------*/
/*
  takes an array of rhc numbers from the url. uses this to target and remove specific bits of cache.
  this is a fairly significant hole into the back of the site, that is actually deleting files.
  therefore the input is very strict - accepting only an array of rhc(s) references).
  anything that doesnt start with will print an error message on the html
*/


function jr_clearCache() {
  $getRefs = isset($_GET['refs']) ? $_GET['refs'] : [];
  $ssRefs = isset($_GET['ssrefs']) ? $_GET['ssrefs'] : [];
  $out['fail'] = '';
  $out['success'] = '';

  //first gets the list of categories + items
  foreach($getRefs as $ref) {
    //check its an int
    if (is_numeric($ref)) {
      $fileList[] = 'item-rhc'.$ref;
      $c = jrQA_cacheValues($ref);
      $catsList[] = $c['Category'] != '0' ? $c['Category'] : null;
      $catsList[] = $c['Cat1'] != '0' ? $c['Cat1'] : null;
      $catsList[] = $c['Cat2'] != '0' ? $c['Cat2'] : null;
      $catsList[] = $c['Cat3'] != '0' ? $c['Cat3'] : null;
      //$out['success'] .= 'item-rhc'.$ref;
    } elseif ($ref == "full") { //clear all. will probably end up being the main one to use


      $fileListScan = glob('../RHC*/cached-files/*');
      foreach ($fileListScan as $file) {
        $fileList[] = str_replace(['../RHC_Online/cached-files/','../RHC/cached-files/','-cached.html'],
                                  ['','',''], $file);
      }
      $catlist = []; //all covered in the glob so can skip

    } else {
      $out['fail'] .= "<li>The value '$ref' is invalid</li>";
    }
  }

  foreach($ssRefs as $ref) {
    //check its an int
    if (is_numeric($ref)) {
      $fileList[] = 'item-rhcs'.$ref;
      $c = jrQA_cacheValues($ref, $ss = true);
      $catsList[] = $c['Category'];


    } else {
      $out['fail'] .= "<li>The value '$ref' is invalid</li>";
    }

    //gets the filenames of each category
  }
  foreach ($catsList as $catName) {

    $id = jrQ_categoryID($catName);
    if (!is_null($id)) {
      $fileList[] =  'category-'.$id;
    }
  }

    //now we have a list of categorys and items to be "reset"
    foreach ($fileList as $file) {
      $cachefileName = 'cached-files/'.$file.'-cached.html';
      $out['success'] .= "<li>The cache file '$file' was removed</li>";
      if ($file != '' && file_exists($cachefileName)) {
        unlink($cachefileName);
      }

    }
  //$out = ['success' => $success, 'fail' => $error];
  return $out;
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
    set_transient('jr_t_faq', $results, YEAR_IN_SECONDS);
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
    set_transient('jr_t_categories_full', $results, YEAR_IN_SECONDS);
    return $results;
  }
}

function jrCached_Categories_Sorted() {
  $transient = get_transient('jr_t_categories_sorted');

  if( !empty($transient)) {
    return $transient;
  } else {
    $results = jr_categoryFilter();
    set_transient('jr_t_categories_sorted', $results, YEAR_IN_SECONDS);
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
