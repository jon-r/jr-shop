<?php

/*--- init ----------------------------------------------------------------------------*/

function rhc_getScripts() {
  if ($_GET[page] == 'rhc-maintenance') {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', ( 'https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js' ), false, null, true );

    wp_enqueue_style( 'caramel_stylesheet', plugin_dir_url( __FILE__ ) . 'caramel.min.css');
    wp_enqueue_style( 'jr_admin_stylesheet', plugin_dir_url( __FILE__ ) . 'jr_admin_style.css');
    wp_enqueue_script( 'jr_admin_script', plugin_dir_url( __FILE__ ) . 'jr_admin_script.js', array( 'jquery' ), '', true );
    wp_localize_script( 'jr_admin_script', 'fileSrc', ['ajaxAdmin' => admin_url( 'admin-ajax.php' )]);
  }
}

function rhc_setup_menu(){
  add_menu_page( 'Red Hot Chilli Maintenance', 'RHC Maintenance', 'manage_options', 'rhc-maintenance', 'rhc_init' );

}

function rhc_init(){
  include('JR_Admin_Template.php');
}

/*------------ File Cleanup --------------------------------------------------------- */

//gets everything in the file, and compared to what *should* be there
function jrA_ImageSearch($folder) {
  $deadPics = $deadTiles = $deadThumbs = array();
  $fullDir = "../images/$folder";

  $listAll = jrA_FileNames($fullDir);
  //$files_Pic = $files_Thumb = $files_Tile = array('name' => [],'size' => []);

//gallery images
  if ($folder == 'gallery') {
    $listValid = jrQA_validItems();

    $listAll_tiles = jrA_FileNames($fullDir.'-tile');
    $listAll_thumbs = jrA_FileNames($fullDir.'-thumb');

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



  } else {

  }

  return($out);
}
// send the readable info
function jrA_deadImageStats () {
  $in = $_GET[keyword];

  $fileInfo = jrA_ImageSearch($in);

  if ($fileInfo['Names'] != null) {
    $out[count] = count($fileInfo['Names']);
    $out[size] =  sizeFormat(array_sum($fileInfo['Size']));
  } else {
    $out[count] = 0;
    $out[size] = 0;
  }


  echo json_encode($out);
  wp_die();
}

function jrA_deadImageDelete() {
  $in = $_GET[keyword];
  $fileInfo = jrA_ImageSearch($in);
  $out = array();
  $fullDir = "../images/$folder";

  foreach($fileInfo['Names'] as $file) {
    if (file_exists($file)) {
      $out[] = $file;
      unlink($file);
    }


  }

  RemoveEmptySubFolders($fullDir);

  echo json_encode(count($out));
  wp_die();

}

// spews out the files of all the "dead" files.
function jrA_unleashImages($fileArray, $type) {
  $outArr = ['name' => [], 'size' => []];
  foreach (array_unique($fileArray) as $dirName) {
    foreach (glob("../images/$type/$dirName*") as $file) {
      $outArr['name'][] = $file;
      $outArr['size'][] = filesize($file);
    }
  }
  return $outArr;
}


//prints all images from a certain rhc/rhcs
function jrA_specificImg() {
  $ref = $_GET[reference];
  $imgDir = null;

  if (stripos($ref, "rhcs") === 0)  {
    $refNum = intVar(str_ireplace('rhcs','',$ref));
    $imgDir = jrQA_itemDir($refNum,$steel = true);

  } elseif (stripos($ref, "rhc") === 0) {
    $refNum = intval(str_ireplace('rhc','',$ref));
    $imgDir = jrQA_itemDir($refNum,$steel = false);
  }

  if ($imgDir) {
    $out[first] = '../images/gallery/'.$imgDir.'.jpg';
    $out[all] = glob('../images/gallery/'.$imgDir.'*');

    $thumbs = glob('../images/gallery-thumb/'.$imgDir.'*');
    $tiles = glob('../images/gallery-tile/'.$imgDir.'*');
  }

  $oldImgs = array_merge($thumbs, $tiles);
  foreach ($oldImgs as $file) {
    unlink($file);
    $out[] = $file;
  }

  echo json_encode($out);

  wp_die();
}


  //load the ajax calls
  add_action('wp_ajax_jra_deadimgstats', 'jrA_deadImageStats');
  add_action('wp_ajax_jra_deadimgdel', 'jrA_deadImageDelete');
  add_action('wp_ajax_jra_specificimg', 'jrA_specificImg');

/* ---------------------- misc admin functions -------------------------------------- */

//http://www.go4expert.com/articles/calculate-directory-size-using-php-t290/
function getDirectorySize($path) {
  $totalsize = 0;
  $totalcount = 0;
  $dircount = 0;
  if ($handle = opendir ($path)) {
    while (false !== ($file = readdir($handle))) {
      $nextpath = $path . '/' . $file;
      if ($file != '.' && $file != '..' && !is_link ($nextpath)) {
        if (is_dir ($nextpath)) {
          $dircount++;
          $result = getDirectorySize($nextpath);
          $totalsize += $result['size'];
          $totalcount += $result['count'];
          $dircount += $result['dircount'];
        } elseif (is_file ($nextpath)) {
          $totalsize += filesize ($nextpath);
          $totalcount++;
        }
      }
    }
  }
  closedir ($handle);
  $total['size'] = $totalsize;
  $total['count'] = $totalcount;
  $total['dircount'] = $dircount;
  return $total;
}

function sizeFormat($size) {
    if($size<1024) {
        return $size." bytes";
    } elseif($size<(1024*1024)) {
        $size=round($size/1024,1);
        return $size." KB";
    }  else if($size<(1024*1024*1024)) {
        $size=round($size/(1024*1024),1);
        return $size." MB";
    } else {
        $size=round($size/(1024*1024*1024),1);
        return $size." GB";
    }
}

// http://www.stevenmcmillan.co.uk/blog/2011/recursive-folder-scan-using-recursivedirectoryiterator/
function jrA_FileNames($dir) {
  if (isset($dir) && is_readable($dir)) {
    $dlist = Array();
    $dir = realpath($dir);
    $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

    foreach($objects as $entry => $object){
      if (is_file($entry)) {
        $entry = str_replace($dir, '', $entry);
        $entry = substr(str_replace('\\', '/', $entry),1,-4);
        $dlist[] = $entry;
      }
    }

    return $dlist;
  }
};

// http://stackoverflow.com/a/1833681
function RemoveEmptySubFolders($path) {
  $empty=true;
  foreach (glob($path.DIRECTORY_SEPARATOR."*") as $file) {
     $empty &= is_dir($file) && RemoveEmptySubFolders($file);
  }
  return $empty && rmdir($path);
}



?>

