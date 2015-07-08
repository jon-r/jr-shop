<?php
//query functions
//these are pretty much purpose built covers over the wpdb class
function jrQ_settings() {
  global $wpdb;
  $queryStr = 'SELECT `option_name`, `option_value` FROM `rhc_web_options`';
  return $wpdb->get_results($queryStr, ARRAY_A);
}
/*Validate querys ---------------------------------------------------------------------*/
function jrQ_brands() {
  global $wpdb;
  $queryStr = "SELECT `Brand` FROM `networked db` WHERE `Quantity` > 0 ORDER BY `Brand` ASC";
  return $wpdb->get_col($queryStr);
}


function jrQ_rhc($rhc) {
  global $wpdb, $itemSoldDuration;
  $queryStr = "SELECT `RHC` FROM `networked db` WHERE `RHC` = %s AND `LiveonRHC` = 1 AND ((`Quantity` > 0) OR ( `Quantity` = 0 AND `DateSold` BETWEEN CURDATE() - INTERVAL $itemSoldDuration DAY AND CURDATE()))";
  $out = $wpdb->get_var(
    $wpdb->prepare($queryStr, $rhc)
  );
  return($out);
}
function jrQ_rhcs($rhcs) {
  global $wpdb, $itemSoldDuration;
  $queryStr = "SELECT `RHCs` FROM `benchessinksdb` WHERE `RHCs` = %s AND ((`Quantity` > 0) OR ( `Quantity` = 0 AND `DateSold` BETWEEN CURDATE() - INTERVAL $itemSoldDuration DAY AND CURDATE()))";
  $out = $wpdb->get_var(
    $wpdb->prepare($queryStr, $rhcs)
  );
  return($out);
}
function jrQ_categories() {
  global $wpdb;
  $results = $wpdb->get_results("SELECT `Name`, `CategoryGroup`,`CategoryGroup2`, `CategoryDescription`, `Is_RHCs` FROM `rhc_categories` WHERE `ShowMe` = 1", ARRAY_A);
  foreach ($results as $c) {
    $c['RefName'] = sanitize_title($c['Name']);
    $out[] = $c;
  }
  return $out;
}

function jrQ_titles($safeRHC, $SS = null) {
  global $wpdb;
  if ($SS) {
    $ref = "RHCs";
    $db = "benchessinksdb";
  } else {
    $ref = "RHC";
    $db = "networked db";
  }
  return $wpdb->get_row("SELECT `ProductName`,`Category` FROM `$db` WHERE `$ref` LIKE '$safeRHC'", ARRAY_A);
}
/* query for 'items full' -------------------------------------------------------------*/
function jrQ_item($safeRHC, $SS = null) {
  global $wpdb;
  if ($SS) {
    $queryFull = $wpdb->get_row("SELECT `RHCs`, `ProductName`, `Category`, `Height`, `Width`, `Depth`, `Price`, `Quantity`, `TableinFeet`, `Line1` FROM `benchessinksdb` WHERE RHCs = $safeRHC", ARRAY_A);
  } else {
    $queryFull = $wpdb->get_row("SELECT `RHC`, `ProductName`, `Price`, `Height`, `Width`, `Depth`, `Model`, `Brand`, `Wattage`, `Power`, `ExtraMeasurements`, `Line 1`, `Line 2`, `Line 3`, `Condition/Damages`, `Sold`, `Quantity`, `Category`, `Cat1`, `Cat2`, `Cat3`, `SalePrice`, `IsSoon` FROM `networked db` WHERE RHC = $safeRHC", ARRAY_A);
  }
  return $queryFull;
}
/*--querys for items list -------------------------------------------------------------*/
//firstly lists the items on sale.
function jrQ_items( $safeArr, $pageNumber) {
  global $wpdb, $itemCountMax;
  $queryOffset = ($pageNumber - 1) * $itemCountMax;
  $queryLimiter = "$queryOffset,$itemCountMax";

  $safeArr['count'] = $queryLimiter;
  $query = jrQ_itemString($safeArr);

  if ($query['placeholders']) {
    $out = $wpdb->get_results(
      $wpdb->prepare($query['str'], $query['placeholders'])
    , ARRAY_A);
  } else {
    $out = $wpdb->get_results($query['str'], ARRAY_A);
  }
  return $out;
}
//fills page with sold items. Mix of filler for design balance and show past sales.
function jrQ_itemsSold($safeArr, $itemsOnPage) {
  global $wpdb, $itemSoldDuration;

  if ($safeArr['pgType'] == 'arrivals' || $safeArr['pgType'] == 'sold') {
    $out = null;
  } else {
    $soldCount = 8 - ($itemsOnPage % 8);
    $safeArr['sold'] = true;
    $safeArr['count'] = $soldCount;
    $query = jrQ_itemString($safeArr);

    if ($query['placeholders']) {
      $out = $wpdb->get_results(
        $wpdb->prepare($query['str'], $query['placeholders'])
      , ARRAY_A);
    } else {
      $out = $wpdb->get_results($query['str'], ARRAY_A);
    }
  }
  return $out;
}
// get random four items in the category - currently just for 'item full' page.
function jrQ_itemsRelated($safeArr) {
  global $wpdb;

  $safeArr['pgType'] = 'lite';
  $safeArr['filterType'] = 'related';

  $query = jrQ_itemString($safeArr, 'lite');

  $out = $wpdb->get_results(
    $wpdb->prepare($query['str'], $query['placeholders']),
    ARRAY_A);
  return $out;
}
//count all items from query, for pagination
function jrQ_itemsCount($safeArr) {
  global $wpdb;
  $safeArr['pgType'] = 'counter';
  $query = jrQ_itemString($safeArr);

  if ($query['placeholders']) {
    $column = $wpdb->get_col(
      $wpdb->prepare($query['str'], $query['placeholders'])
    );
  } else {
    $column = $wpdb->get_col($query['str']);
  }
  $out = count($column);
  return $out;
}
//tags items as new
function jrQ_ItemsNew() {
  global $itemCountMax, $wpdb;
  return $wpdb->get_col("SELECT `rhc` FROM `networked db` WHERE (`LiveonRHC` = 1 AND `Quantity` > 0) ORDER BY `DateLive` DESC, `rhc` DESC LIMIT $itemCountMax") ;
}
//returns the mysql query string. for debug purposes
function jrQ_debug($safeArr,$sold=false) {
  global $wpdb, $itemSoldDuration;
  $query = jrQ_itemString($safeArr, 'counter');
  $out['noPrep'] = $query['str'];
  $out['prep'] = $query['placeholders'] ? $wpdb->prepare($query['str'], $query['placeholders']) : null;
  if ($sold) {

    $safeArr['sold'] = true;
    $safeArr['count'] = 8;
    $query = jrQ_itemString($safeArr);
  }
  return $out;
}

function jrQ_itemString($safeArr) {
  $qType = $safeArr['pgType'];
  $qFilter = $safeArr['filterType'];
  $qValue = $safeArr['filterVal'];
  $isSteel = $safeArr['ss'];
  $isSold = $safeArr['sold'];
  $limit = $safeArr['count'];

  if ($isSteel) {
    $itemRef = "`RHCs`";
    $itemDB = "`benchessinksdb`";
  } else {
    $itemRef = "`RHC`";
    $itemDB = "`networked db`";
  }
  //the query "start". how much data are we getting?
  if ($qType == 'counter') {
    $querySelection = "$itemRef";
  } elseif ($qType == 'lite') {
    $querySelection = "$itemRef, `ProductName`, `Price`, `Width`, `Quantity`";
  } elseif ($isSteel) {
    $querySelection = "$itemRef, `ProductName`, `Price`, `Width`, `Quantity`";
  } else {
    $querySelection = "$itemRef, `ProductName`, `IsSoon`, `Category`, `Cat1`, `Cat2`, `Cat3`, `Power`, `Price`, `SalePrice`, `Quantity`";
  }

  //the query "middle". what is the data filtered by?

  if ($isSteel) {
    $filters = "(`Category` LIKE %s) AND";
  } elseif ($qFilter == 'items' || $qFilter == 'related') {
    $filters = "(`Category` LIKE %s OR `Cat1` LIKE %s OR `Cat2` LIKE %s OR `Cat3` LIKE %s) AND";
  } elseif ($qFilter == 'search') {
    $filters = "(`ProductName` REGEXP %s OR `Brand` REGEXP %s) AND";
  } elseif ($qFilter == 'brand') {
    $filters = "(`Brand` LIKE %s) AND";
  } elseif ($qFilter =='sale') {
    $filters = "(`SalePrice` = %d) AND";
  } else {
    $filters = "";
  }

//the query end. how is the data sorted and limited?
  $orderBy = "(`LiveonRHC` = 1 AND `Quantity` > 0) ORDER BY `DateLive` DESC, $itemRef DESC LIMIT $limit";

  if ($qFilter == 'soon' ) {
    $orderBy = "(`LiveonRHC` = 0 AND `IsSoon` = 1) ORDER BY $itemRef DESC";
  } elseif ($isSold) {
    $orderBy = "(`LiveonRHC` = 1 AND `Quantity` = 0) ORDER BY `DateSold` DESC LIMIT $limit";
  } elseif ($qFilter == 'related' ) {
    $orderBy = "(`LiveonRHC` = 1 AND `Quantity` > 0) AND ($itemRef != $qValue) ORDER BY RAND() LIMIT 4";
  } elseif ($qType == 'counter') {
    $orderBy = "(`LiveonRHC` = 1 AND `Quantity` > 0)";
  }

  //queryPlaceholders (for wpdb->prepare)
  $qArray = false; //no prepare for non-variables
  if ($qFilter == 'items') {
    $qArray = [ $qValue, $qValue, $qValue, $qValue ];
  } elseif ($qFilter == 'related') {
    $qArray = [ $safeArr['filterVal2'], $safeArr['filterVal2'], $safeArr['filterVal2'], $safeArr['filterVal2'] ];
  } elseif ($qFilter == 'search') {
    $qArray = [ $qValue, $qValue, $qValue ];
  } elseif ($isSteel || $qFilter == 'brand' || $qFilter =='sale') {
    $qArray = [ $qValue ];
  }

  $out['str'] = "SELECT $querySelection FROM $itemDB WHERE $filters $orderBy";
  $out['placeholders'] = $qArray;
  return $out;
}

/* -- get other content -------------------------------------------------------------*/
function jrQ_carousel() {
  global $wpdb;
  return $wpdb->get_results("SELECT * FROM `carousel` WHERE `IsLive` = 1 ORDER BY `OrderNo` DESC, `Slide_ID` DESC;", ARRAY_A);
}
function jrQ_tesimonial($detail = null) {
  global $wpdb;
  $query = ($detail) ? 'Testimonial_Full' : 'Testimonial_Short';
  return $wpdb->get_results("SELECT `$query`, `Name` FROM `rhc_testimonial`;", ARRAY_A);
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
