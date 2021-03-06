<?php
/* --- cache clearing -----------------------------------------------------------------*/
/**
 * targets and removes specific bits of cache.
 * the input is very strict - accepting only a whitelist.
 * anything that doesnt start with will print an error message on the html
 * @return array output li elements for the results page
 */
function jr_clearCache() {

  $out['fail'] = '';
  $out['success'] = '';
  $cacheDir = ABSPATH.'cached-files/';
  $map = new sitemap;

  if (isset($_GET['refs'])) {

    $ref = $_GET['refs'];

    if ($ref == "product") { //clear all stock related pages (not header/footer/nav)

      $htmlFiles = scandir($cacheDir);
      $filteredFiles = array_diff($htmlFiles, ['..', '.']);

      $itemCount = 0;
      $catCount = 0;
      foreach($filteredFiles as $file) {

        if (strpos($file,'item-') === 0) {
          $fileDir = $cacheDir.$file;
          if (file_exists($fileDir)) {
            unlink($fileDir);
            $itemCount++;
          }

        } elseif (strpos($file,'category-') === 0) {
          $fileDir = $cacheDir.$file;
          if (file_exists($fileDir)) {
            unlink($fileDir);
            $catCount++;
          }

        } elseif (strpos($file,'carousel-') === 0) {
          $fileDir = $cacheDir.$file;
          if (file_exists($fileDir)) {
            unlink($fileDir);
            $catCount++;
            $out['success'] .= "<li>The carousel has been refreshed</li>";
          }
        }

     };
      $out['success'] .= "<li>$catCount cached category lists have been reset</li>";
      $out['success'] .= "<li>$itemCount cached Items have been reset</li>";

      $map->build();

    } elseif ($ref == "full") {

      $htmlFiles = scandir($cacheDir);
      $filteredFiles = array_diff($htmlFiles, ['..', '.']);

      foreach($filteredFiles as $file) {

        $fileDir = $cacheDir.$file;

        if (file_exists($fileDir )) {

          unlink($fileDir );
        }
      }

      $count1 = count($filteredFiles);
      $out['success'] .= "<li>$count1 cached pages deleted</li>";

      unlink($fullDir);

      // also clears the transients
      $transientList = jrQA_transients();
      $count2 = count($transientList);

      foreach ($transientList as $t) {

        $name = str_replace('_transient_','',$t);
        delete_transient($name);

      }

      $out['success'] .= "<li>$count2 database storage transients deleted</li>";
      //since we are cleaning up the cache, we call the image cleanups
      $out['success'] .= jr_imgWipe('thumb');
      $out['success'] .= jr_imgWipe('tile');
      $out['success'] .= jr_soldWipe();

      $map->build();

    } else {

      $out['fail'] .= "<li>The value '$ref' is invalid</li>";

    }

  } else {
    //this function shouldnt even start if theres no "ref" so the page normally "404's" before this
    //here just in case
    $out['fail'] .= "<li>Invalid request</li>";

  }

  return $out;
}

/* --- image clearing -----------------------------------------------------------------*/
/**
 * targets and removes old or obsolete images.
 * @param  string $size the size of the images we're looking at, thumbnails or tiles.
 * @return string list output result
 */
function jr_imgWipe($size) {

  $n = 0;
  $oneMonth = 30 * 86400;

  $fileDir = "images/gallery-$size/";
  $fileList = scanDir($fileDir);
  $filteredFiles = array_diff($fileList, ['..', '.']);

  foreach ($filteredFiles as $file) {

    $fileName = $fileDir.$file;
    $fileAge = intval((time() - filectime($fileName)));

    if ($fileAge > $oneMonth) {

      $n++;
      unlink($fileName);

    }
  }

  $out = "<li>$n old $size images removed</li>";

  return $out;
}


/**
 * deletes images for items sold over Xmonths ago (the soldvariable)
 * also cleans up old (inactive) carousel images
 * @return string list output result
 */
function jr_soldWipe() {

  global $itemSoldDuration;
  $n = 0;

  $fileDir = 'images/gallery/';
  $fileList = scanDir($fileDir);
  $filteredFiles = array_diff($fileList, ['..', '.']);

  $listValid = jrQA_ValidItems();
  $listLiveCarousel = jrQ_carouselPics();

  foreach ($filteredFiles as $item) {

    $itemBreak = preg_split('/([^a-z0-9\/])/i', $item);
    //first targets the old RHC/RHCs items

    if (!in_array($itemBreak[0], $listValid ) && stripos($itemBreak[0], 'rhc') === 0) {

      $deadRHC[] = $fileDir.$item;
      unlink($fileDir.$item);
      $n++;
    //then targets anything in the folder thats NOT an rhc or gallery image
    } elseif (!in_array($item, $listLiveCarousel) && stripos($itemBreak[0], 'rhc') !== 0) {

      $deadPics[] = $fileDir.$item;
      unlink($fileDir.$item);
      $n++;
    }
  }

  $out = "<li>$n other images removed</li>";
  return $out;
}

?>
