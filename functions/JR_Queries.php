<?php
//query functions
//these are pretty much purpose built covers over the wpdb class

function jrQ_settings() {
  global $wpdb;
  $queryStr = 'SELECT `option_name`, `option_value` FROM `rhc_web_options`';
  return $wpdb->get_results($queryStr, ARRAY_A);
}

/*Validate querys ---------------------------------------------------------------------*/
function jrQ_brandUnique() {
  global $wpdb;
  $queryStr = "SELECT `Brand` FROM `networked db` WHERE `Quantity` > 0";
  return array_unique($wpdb->get_col($queryStr));
}

function jrQ_categoryColumn() {
  global $wpdb;
  $queryStr = "SELECT `name` FROM `rhc_categories`";
  return array_unique($wpdb->get_col($queryStr));
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
  return $wpdb->get_results("SELECT `Name`, `CategoryGroup`, `CategoryDescription` FROM `rhc_categories` WHERE `ShowMe` = 1", ARRAY_A);
}

function jrQ_keywords($keyword) {
  global $wpdb;

  $queryStr = "SELECT `keyword` FROM `keywords_db` WHERE `keywordGroup` = '$keyword'";
  return array_unique($wpdb->get_col($queryStr));
  //return $queryStr;
}

function jrQ_categoryDesc( $safeCategory ) {
  global $wpdb;
  return $wpdb->get_var("SELECT `CategoryDescription` FROM `rhc_categories` WHERE `Name` LIKE '$safeCategory'");
}


/*get titles for the breadcrumbs/search----------------------------------------------- */
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
  $queryLimiter = " LIMIT $queryOffset,$itemCountMax";
  $queryAll = jrQ_itemString($safeArr);
  $queryFull = $queryAll[str].$queryLimiter;

  if ($queryAll[placeholders]) {
    $out = $wpdb->get_results(
      $wpdb->prepare($queryFull, $queryAll[placeholders])
    , ARRAY_A);
  } else {
    $out = $wpdb->get_results($queryFull, ARRAY_A);
  }

  return $out;
}

//fills page with sold items. Mix of filler for design balance and show past sales.
function jrQ_itemsSold($safeArr, $itemsOnPage) {
  global $wpdb, $itemSoldDuration;

  if ($safeArr['pgType'] == 'New' || $safeArr['pgType'] == 'Sold') {
    $out = null;
  } else {
    $soldCount = 8 - ($itemsOnPage % 8);
    $query = jrQ_itemString($safeArr);

    $querySoldOn = str_replace(['`Quantity` > 0','ORDER BY `RHC`'],
                               ['`Quantity` = 0 AND `DateSold` BETWEEN CURDATE() - INTERVAL '.$itemSoldDuration.' DAY AND CURDATE()','ORDER BY `DateSold`'], $query[str]);
    $queryLimiter = " LIMIT $soldCount";
    $queryFull = $querySoldOn.$queryLimiter;

    if ($query[placeholders]) {
      $out = $wpdb->get_results(
        $wpdb->prepare($queryFull, $query[placeholders])
      , ARRAY_A);
    } else {
      $out = $wpdb->get_results($queryFull, ARRAY_A);
    }
  }

  return $out;
}

// get random four items in the category - currently just for 'item full' page
function jrQ_iremsRelated($safeArr) {
  global $wpdb;

  $arr[cat] = $safeArr[cat];

  if ($safeArr[ss]) {
    $arr[pgType] = 'CategorySS';
    $query = jrQ_itemString($arr);
    $queryRand = str_replace('ORDER BY `RHCs` DESC', 'ORDER BY RAND() LIMIT 4', $query);
  } else {
    $arr[pgType] = 'Category';
    $query = jrQ_itemString($arr);
    $queryRand = str_replace('ORDER BY `RHC` DESC', 'ORDER BY RAND() LIMIT 4', $query);
  }

  $out = $wpdb->get_results(
    $wpdb->prepare($queryRand[str], $queryRand[placeholders]),
    ARRAY_A);

  return $out;
}

//count all items from query, for pagination
function jrQ_itemsCount($safeArr) {
    global $wpdb;

    $query = jrQ_itemString($safeArr, $isCounter = true);

    if ($query[placeholders]) {
      $column = $wpdb->get_col(
        $wpdb->prepare($query[str], $query[placeholders])
      );
    } else {
      $column = $wpdb->get_col($query[str]);
    }

    $out = count($column);

  return $out;
}

//tags items as new
function jrQ_ItemsNew() {
  global $itemCountMax, $wpdb;
  return $wpdb->get_col("SELECT `rhc` FROM `networked db` WHERE (`LiveonRHC` = 1 AND `Quantity` > 0) ORDER BY `rhc` DESC LIMIT $itemCountMax") ;
}

//returns the mysql query. for debug purposes
function jrQ_debug($safeArr,$sold=false) {
  global $wpdb, $itemSoldDuration;
  $query = jrQ_itemString($safeArr, $isCounter = true);

  $out[noPrep] = $query[str];
  $out[prep] = $query[placeholders] ? $wpdb->prepare($query[str], $query[placeholders]) : null;
  if ($sold) {
    $out[sold] = str_replace(['`Quantity` > 0','ORDER BY `RHC`'],
                               ['`Quantity` = 0 AND `DateSold` BETWEEN CURDATE() - INTERVAL '.$itemSoldDuration.' DAY AND CURDATE())','ORDER BY `DateSold`'], $query[str]);
  }
  return $out;
}

//core query category function
function jrQ_itemString($safeArr, $isCounter = false) {

  $qType = $safeArr[pgType];

//the query "start". what data are we getting?
  if ($isCounter && $qType == 'CategorySS') {
    $queryStart = "SELECT `RHCs` FROM `benchessinksdb` ";
  } elseif ($isCounter) {
    $queryStart = "SELECT `RHC` FROM `networked db` ";
  } elseif ($qType == 'CategorySS') {
    $queryStart = "SELECT `RHCs`, `ProductName`, `Price`, `Category`, `TableinFeet`, `Quantity` FROM `benchessinksdb` ";
  } else {
    $queryStart = "SELECT `RHC`, `ProductName`, `IsSoon`, `Sold`, `Category`, `Cat1`, `Cat2`, `Cat3`, `Power`, `Price`, `SalePrice`, `Quantity` FROM `networked db` ";
  };
//the query "middle". what is the data filtered by?
  $queryMid = "WHERE ";
  if ($qType == 'Category') {
    $queryMid = "WHERE (`Category` LIKE %s OR `Cat1` LIKE %s OR `Cat2` LIKE %s OR `Cat3` LIKE %s) AND ";
    $qValue = $safeArr[cat];
  } elseif ($qType == 'Search') {
    $queryMid = "WHERE (`ProductName` REGEXP %s OR `Power` REGEXP %s OR `Brand` REGEXP %s) AND ";
    $qValue = $safeArr[search]; //PUT searchstr to regex in validation
  } elseif ($qType == 'CategorySS') {
    $queryMid = "WHERE (`Category` LIKE %s) AND ";
    $qValue = $safeArr[cat];
  } elseif ($qType == 'Brand') {
    $queryMid = "WHERE (`Brand` LIKE %s) AND ";
    $qValue = $safeArr[brand];
  } elseif ($qType =='Sale') {
    $queryMid = "WHERE (`SalePrice` = %d) AND ";
    $qValue = $safeArr[saleNum];
  };
//the query end. how is the data sorted?
  $queryEnd = "(`LiveonRHC` = 1 AND `Quantity` > 0) ORDER BY `RHC` DESC";
  if ($qType == 'Soon' ) {
    $queryEnd = "(`LiveonRHC` = 0 AND `IsSoon` = 1) ORDER BY `RHC` DESC";
  } elseif ($qType == 'Sold' ) {
    $queryEnd = "`Quantity` = 0 ORDER BY `DateSold` DESC";
  } elseif ($qType == 'CategorySS') {
    $queryEnd = "`Quantity` > 0 ORDER BY `RHCs` DESC";
  };
//queryPlaceholders (for wpdb->prepare)
  $qArray = null; //no prepare of non-variables
  if ($qType == 'Category') {
    $qArray = [ $qValue, $qValue, $qValue, $qValue ];
  } elseif ($qType == 'Search') {
    $qArray = [ $qValue, $qValue, $qValue ];
  } elseif ($qType == 'CategorySS' || $qType == 'Brand' || $qType =='Sale') {
    $qArray = [ $qValue ];
  }

  $out[str] = $queryStart.$queryMid.$queryEnd;
  $out[placeholders] = $qArray;

  return $out;
}

/* -- get other content -------------------------------------------------------------*/

function jrQ_carousel() {
  global $wpdb;
  return $wpdb->get_results("SELECT * FROM `carousel` WHERE `IsLive` = 1 ORDER BY `OrderNo` DESC;", ARRAY_A);
}


function jrQ_tesimonial($detail = null) {
  global $wpdb;

  $query = ($detail) ? 'Testimonial_Full' : 'Testimonial_Short';
  return $wpdb->get_results("SELECT `$query`, `Name` FROM `rhc_testimonial`;", ARRAY_A);
}

/* -- admin querys. ---------------------------------------------------------------*/
//this may include a lot of sold/deleted, incomplete items. should not be use for customer end shop

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

function jrA_addRHC($n) {
  return 'RHC'.$n;
}
function jrA_addRHCs($n) {
  return 'RHCs'.$n;
}

//function jrQA_itemDir($ref,$steel = false) {
//  global $wpdb;
//  if ($steel) {
//    $query = "SELECT `Image` FROM `benchessinksdb` WHERE `RHCs` = %d";
//  } else {
//    $query = "SELECT `Image` FROM `networked db` WHERE `RHC` = %d";
//  }
//
//
//  $out = $wpdb->get_var(
//    $wpdb->prepare($query, $ref)
//  );
//
//  return $out;
//}
//SELECT `RHC` FROM `networked db` WHERE (`Category` LIKE 'Tables & Chairs' OR `Cat1` LIKE 'Tables & Chairs' OR `Cat2` LIKE 'Tables & Chairs' OR `Cat3` LIKE 'Tables & Chairs') AND (`LiveonRHC` = 1 AND `Quantity` = 0 AND `DateSold` BETWEEN CURDATE() - INTERVAL  DAY AND CURDATE()) ORDER BY `DateSold` DESC
?>
