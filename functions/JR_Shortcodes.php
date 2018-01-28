<?php
/* ---- module generator --------------------------------------------------------------*/
//turns 'jr-shop' shortcode into templates on the page
add_shortcode("jr-shop", "jr_modules");

function jr_modules($atts) {
  global $jr_page;
  $a = shortcode_atts([
    'id' => '404',
    'cached'=> false
  ], $atts);

  $file = ABSPATH.'wp-content/plugins/jr-shop/includes/'.$a['id'].'.php';
  if (file_exists($file)) {

    if ($a['cached'] == 'unique') {

      if ($jr_page->unique) {

        jrCached_HTML($file, $jr_page->unique, 7);
  //      return "success -.$file"
      } else {

        ob_start();
        include($file);
        return ob_get_clean();

      }

    } elseif ($a['cached']) {

      jrCached_HTML($file, $a['id'], 30);

    } else  {

      ob_start();
      include($file);
      return ob_get_clean();
    }

  } else {
    echo "[check $file]";
  }
}
/* ---- columns -----------------------------------------------------------------------*/

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
  } else {
    $title = '';
  }

  $str = '<section class="flex-1 tile-outer" >'
    .$title
    .'<div class="'.$size.'">'
    .do_shortcode($content)
    .'</div></section>';
  return $str;
}

/* ---- add category headers -----------------------------------------------------------------------*/

add_shortcode("page-banner", "jr_pageBanner");
function jr_pageBanner($atts, $content = null) {
  $a = shortcode_atts([
    'carousel' => false,
    'image' => false
  ], $atts);
  
  if ($a['carousel']) {
    $carousel = jrQ_carousel($a['carousel']);
    $slide = jr_magicRoundabout($carousel[0]);
    $file = ABSPATH.'wp-content/plugins/jr-shop/includes/carousel-single.php';
    include($file);

  } elseif ($a['image']) {
    $img = jr_siteImg('gallery/'.$a['image'].'.jpg');
    $str = '<section >'
      .'<img class="framed" src="'.$img.'" >'
      .'</section>';
    echo $str;
  }
  
}


/* ---- debug arrays ------------------------------------------------------------------*/
//for testing purposes. may add different options
add_shortcode("jr-debug", "jr_debugger");

function jr_debugger() {
  global $jr_page;
  var_dump($jr_page);
}

?>
