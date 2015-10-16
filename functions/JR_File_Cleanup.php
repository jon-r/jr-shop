<?php
/* --- cache clearing -----------------------------------------------------------------*/
/*
  targets and removes specific bits of cache.
  the input is very strict - accepting only a whitelist.
  anything that doesnt start with will print an error message on the html
*/


function jr_clearCache() {

  $out['fail'] = '';
  $out['success'] = '';

  if (isset($_GET['refs'])) {
    $ref = $_GET['refs'];

    if ($ref == "product") { //clear all stock related pages (not header/footer/nav)


      $htmlFiles = scandir("cached-files/");
      $filteredFiles = array_diff($htmlFiles, ['..', '.']);

      foreach($filteredFiles as $file) {
        if (strpos($file,'item-') === 0
           || strpos($file,'category-') === 0
           || strpos($file,'carousel-') === 0) {
          $fileDir = "cached-files/$file";

          if (file_exists($fileDir)) {
            unlink($fileDir);
            $out['success'] .= "<li>The cached file $file has been removed</li>";
          }

       }
     };

    } elseif ($ref == "full") {

      $htmlFiles = scandir("cached-files/");
      $filteredFiles = array_diff($htmlFiles, ['..', '.']);

      foreach($filteredFiles as $file) {
        $fileDir = "cached-files/$file";
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

      $deletedCount = $count1 + $count2;

      $out['success'] .= "<li>$count2 database storage transients deleted</li>";

      //since we are cleaning up the cache, we call the image cleanups
      jr_imgWipe('thumb');
      jr_imgWipe('tile');
      jr_soldWipe();

    } else {
      $out['fail'] .= "<li>The value '$ref' is invalid</li>";
    }
  } else {
    $out['fail'] .= "<li>Invalid request</li>";
    //this function shouldnt even start if theres no "ref" so the page normally "404's" before this
  }

  return $out;
}

/* --- image clearing -----------------------------------------------------------------*/
/*
  targets and removes old or obsolete images.
*/

/* - periodically removes old thumbnails. */
function jr_imgWipe($size) {

  $fileDir = "images/gallery-$size/";
  $oneMonth = 30 * 86400;

  $fileList = scanDir($fileDir);
  $filteredFiles = array_diff($fileList, ['..', '.']);

  foreach ($filteredFiles as $file) {
    $fileName = $fileDir.$file;
    $fileAge = intval((time() - filectime($fileName)));

    if ($fileAge > $oneMonth) {
      $filesToKill[] = $fileName.' - '.intval($fileAge/86400).' days old';
      unlink($fileName);
    }
  }
}


/*
  - deletes images for items sold over Xmonths ago (the soldvariable)
  - cleans up old carousel images
*/
function jr_soldWipe() {
  global $itemSoldDuration;

  $fileDir = "images/gallery/";
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
    //then targets anything in the folder thats NOT an rhc or gallery image
    } elseif (!in_array($item, $listLiveCarousel) && stripos($itemBreak[0], 'rhc') !== 0) {
      $deadPics[] = $fileDir.$item;
      unlink($fileDir.$item);
    }
  };
  var_dump($deadRHC + $deadPics);
  //unlink($deadPics);
}

/*
function jr_ImageTidy() {
  $deadPics = $deadTiles = $deadThumbs = [];
  $fullDir = "../images/$folder";
  $listAll = jrA_FileNames($fullDir);
  //gallery images. atm this is all it will be used for - other images are synced directly

    $listAll_tiles = jrA_FileNames($fullDir.'-tile');
    $listAll_thumbs = jrA_FileNames($fullDir.'-thumb');
    $listValid = jrQA_ValidItems();

    foreach ($listAll as $item) {
      $itemBreak = preg_split('/([^a-z0-9\/])/i', $item);
      if (!in_array($itemBreak[0], $listValid )) {
        $deadPics[] = $itemBreak[0];
      }
    };
    foreach ($listAll_tiles as $item) {
      $itemBreak = preg_split('/([^a-z0-9\/])/i', $item);
      if (!in_array($itemBreak[0], $listValid )) {
        $deadTiles[] = $itemBreak[0];
      }
    };
    foreach ($listAll_thumbs as $item) {
      $itemBreak = preg_split('/([^a-z0-9\/])/i', $item);
      if (!in_array($itemBreak[0], $listValid )) {
        $deadThumbs[] = $itemBreak[0];
      }
    };
    $files_Pic = jrA_unleashImages($deadPics, 'gallery');
    $files_Thumb = jrA_unleashImages($deadThumbs, 'gallery-thumb');
    $files_Tile = jrA_unleashImages($deadTiles, 'gallery-tile');
    $out['Names'] = array_merge($files_Pic['name'], $files_Thumb['name'], $files_Tile['name']);
    $out['Size'] = array_merge($files_Pic['size'], $files_Thumb['size'], $files_Tile['size']);

  return($out);
}
// send the readable info
function jrA_deadImageStats () {
  $in = $_GET['keyword'];
  $fileInfo = jrA_ImageSearch($in);

  if ($fileInfo['Names'] != null) {
    $out['count'] = count($fileInfo['Names']);
    $out['size'] =  sizeFormat(array_sum($fileInfo['Size']));
  } else {
    $out['count'] = 0;
    $out['size'] = 0;
  }
  echo json_encode($out);
  wp_die();
}

function jrA_deadImageDelete() {
  $in = $_GET['keyword'];
  $fileInfo = jrA_ImageSearch($in);
  $out = array();

  foreach($fileInfo['Names'] as $file) {
    if (file_exists($file)) {
      $out[] = $file;
      unlink($file);
    }
  }
  echo json_encode(count($out));
  wp_die();
}
// spews out the files of all the "dead" files.
function jrA_unleashImages($fileArray, $type) {
  $outArr = ['name' => [], 'size' => []];
  foreach (array_unique($fileArray) as $fileName) {
    foreach (glob("../images/$type/$fileName*") as $file) {
      $outArr['name'][] = $file;
      $outArr['size'][] = filesize($file);
    }
  }
  return $outArr;
}
*/

?>
