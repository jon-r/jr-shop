<?php
//query functions
//these are pretty much purpose built covers over the wpdb class

/*Validate querys ---------------------------------------------------------------------*/
function jrQ_brands() {
  global $wpdb;
  $queryStr = "SELECT `Brand` FROM `networked db` WHERE `Quantity` > 0 ORDER BY `Brand` ASC";
  return $wpdb->get_col($queryStr);
}

function jrQ_categories() {
  global $wpdb;
  $results = $wpdb->get_results("SELECT `Category_ID`, `Name`, `CategoryGroup`, `CategoryDescription`, `Is_RHCs` FROM `rhc_categories` WHERE `ShowMe` = 1 ORDER BY `List_Order` DESC, `CategoryGroup` DESC", ARRAY_A);
  foreach ($results as $c) {
    $c['RefName'] = sanitize_title($c['Name']);
    $out[] = $c;
  }
  return $out;
}

function jrQ_categoryID($catName) {
  global $wpdb;
  return $wpdb->get_var("SELECT `Category_ID` FROM `rhc_categories` WHERE `Name` LIKE '$catName'");
}

/* -- get other content -------------------------------------------------------------*/
function jrQ_carousel($id = false) {
  global $wpdb;
  if ($id) {
    $query = "SELECT * FROM `carousel` WHERE `Slide_ID` = $id;";
  } else {
    $query = "SELECT * FROM `carousel` WHERE `IsLive` = 1 ORDER BY `OrderNo` DESC, `Slide_ID` DESC;";
  }
  
  return $wpdb->get_results($query, ARRAY_A);
}
function jrQ_carouselPics() {
  global $wpdb;
  $query = $wpdb->get_col("SELECT `ImageRef` FROM `carousel` WHERE `IsLive` = 1 ORDER BY `OrderNo` DESC, `Slide_ID` DESC;");
  return array_map(function($n) {return $n.'.jpg';}, $query);
}

function jrQ_tesimonial($detail = null) {
  global $wpdb;
  if ($detail) {
    $query = 'Testimonial_Full';
    $limit = null;
  } else {
    $query = 'Testimonial_Short';
    $limit = null;//"ORDER BY RAND() LIMIT 1";
  }

  return $wpdb->get_results("SELECT `$query`, `Name` FROM `rhc_testimonial` $limit", ARRAY_A);
}

/* -- admin only querys ---------------------------------------------------------------*/
function jrQA_validItems() {
  global $wpdb, $itemSoldDuration;
  $queryStr = "SELECT `RHCs` FROM `benchessinksdb` WHERE ((`Quantity` > 0) OR ( `Quantity` = 0 AND `DateSold` BETWEEN CURDATE() - INTERVAL $itemSoldDuration DAY AND CURDATE()))";
  $queryStr2 = "SELECT `RHC` FROM `networked db` WHERE ((`LiveonRHC` = 1 AND `Quantity` > 0) OR (`LiveonRHC` = 1 AND `Quantity` = 0 AND `DateSold` BETWEEN CURDATE() - INTERVAL $itemSoldDuration DAY AND CURDATE()))";

  $out1 = $wpdb->get_col($queryStr);
  $out1= array_map('jrA_addRHCs', $out1);
  $out2 = $wpdb->get_col($queryStr2);
  $out2 = array_map('jrA_addRHC', $out2);
  $out = array_merge($out2, $out1);
  return $out;
}
function jrA_addRHC($in) {
  return 'RHC'.$in;
}
function jrA_addRHCs($in) {
  return 'RHCs'.$in;
}
function jrQA_transients() {
  global $wpdb;
  $query = "SELECT `option_name` FROM $wpdb->options WHERE `option_name` LIKE '%transient_jr_t%' ORDER BY `option_name`";
  return $wpdb->get_col( $query );
}
?>
