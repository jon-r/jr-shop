<?php
/* ---- module generator --------------------------------------------------------------*/
//turns 'jr-shop' shortcode into templates on the page
add_shortcode("jr-shop", "jr_modules");

function jr_modules($atts) {
   global $jr_safeArray;
  $a = shortcode_atts([
    'id' => '404',
    'cached'=> false
  ], $atts);
  $file = 'wp-content/plugins/jr-shop/includes/'.$a['id'].'.php';
  if (file_exists($file) && $a['cached']) {

    $cachefile = 'cached-files/'.$a['id'].'-cached.html';
    $cachetime = 604800;

    if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile)) {
      ob_start();
      readfile($cachefile);
      return ob_get_clean();
    } else {
      ob_start();
      include($file);
      echo '<!-- Page '.$a['id'].' cached on '.date(DATE_COOKIE).' -->';
      $fp = fopen($cachefile, 'w');
      fwrite($fp, ob_get_contents());
      fclose($fp);
      ob_get_flush();
    }

  } elseif (file_exists($file)) {
    ob_start();
    include($file);
    return ob_get_clean();
  } else {
    echo "[check $file]";
  }
}
/* ---- columns -----------------------------------------------------------------------*/
// adds flex-(2,3,4) dividers
/* add_shortcode("column", "jr_columns");
add_shortcode("columns", "jr_columnContainer");

function jr_columns($atts, $content = null) {
  $a = shortcode_atts([
    'size' => 'half',
    'frame' => false
  ], $atts);
  if ($a['size'] == 'full') {
    $size = 'flex-1 ';
  } elseif ($a['size'] == 'half') {
    $size = 'flex-2 ';
  } elseif ($a['size'] == 'third') {
    $size = 'flex-3 ';
  } elseif ($a['size'] == 'quarter') {
    $size = 'flex-4 ';
  }
  if ($a['frame'] == 'light') {
    $frame = 'has-frame';
  } elseif ($a['frame'] == 'light') {
    $frame = 'has-frame-dark';
  } else {
    $frame = null;
  }

  return '<div class="'.$size.$frame.'" >'.do_shortcode($content).'</div>';
}
function jr_columnContainer($atts, $content = null) {
  return '<div class="flex-container" >'.do_shortcode($content).'</div>';
}*/

add_shortcode("text-block", "jr_textBlock");
function jr_textBlock($atts, $content = null) {
  $a = shortcode_atts([
    'columns' => 1,
    'title' => false
  ], $atts);
  if ($a['columns'] >= 0 && $a['columns'] <= 6) {
    $size = 'text-columns-'.$a['columns'];
  }
  if ($a['title'] != false) {
    $title = '<header class="tile-header lined"><h2>'.$a['title'].'</h2></header>';
  }

  $str = '<article class="flex-container">'
    .'<section class="flex-1 tile-outer" >'
    .$title
    .'<div class="'.$size.'">'
    .do_shortcode($content)
    .'</div></section></article>';
  return $str;
}

/* ---- debug arrays ------------------------------------------------------------------*/
//for testing purposes. may add different options
add_shortcode("jr-debug", "jr_debugger");

function jr_debugger() {
  global $jr_safeArray;
  var_dump($jr_safeArray);
}

?>
