<?php
class itemList {

  private $dbRaw = array();

  public $paginate = false;
  public $newCheck;
  public $pgCount = 0;
  public $pgList;

  public function get($filterList) {
    $this->setGlobals();
    foreach ($filterList as $f => $value) {
      $this->filters[$f] = $value;
    }
    $this->pgList = $this->queryList();
    $this->pgCount = count($this->pgList);
  }

  public function getRelated($filterList) {
    $this->setGlobals();
    foreach ($filterList as $f => $value) {
      $this->filters[$f] = $value;
    }
    $this->filters['pgType'] = 'lite';
    $this->filters['filterType'] = 'related';
    $this->pgList = $this->getQueryResults();

  }

  private function setGlobals() {
    global $wpdb, $itemCountMax, $itemSoldDuration;
    $this->wpdb = $wpdb;
    $this->countMax = $itemCountMax;
    $this->duration = $itemSoldDuration;
    $this->pgNum = isset($_GET['pg']) ? $_GET['pg'] : 1;
  }

/*
public function count() {
    $num = $this->queryList || [];
    return count($num);
  }*/


  private function queryList() {
    $listUnsold = $this->queryUnsold();
    $this->paginate = false;
    $lastPage = 1;

    if ($this->filters['pgType'] != 'arrivals' && $this->filters['pgType'] != 'sold') {
      //the "sold" and "new" already capped at a single page, no need to count
      $fullItemCount =  $this->getQueryResults('counter');
      //breaks down into pages
      if ($fullItemCount > $this->countMax) {
        $this->paginate = $lastPage = intval(ceil($fullItemCount / $this->countMax));
      }
      //fills up the last page with sold items
      if ($this->pgNum == $lastPage) {
        //$itemsOnPage =  % $this->countMax;
        $soldCount = 4 - ($fullItemCount % 4);
        $this->filters['count'] = $soldCount;
        $listSold = $this->getQueryResults('sold');
      }
    }
    $out = isset($listSold) ? array_merge($listUnsold, $listSold) : $listUnsold;
    return $out;
  }


  private function queryUnsold() {

    $queryOffset = ($this->pgNum - 1) * $this->countMax;
    $queryLimiter = "$queryOffset,$this->countMax";

    $this->filters['count'] = $queryLimiter;
    return $this->getQueryResults('count');
  }


  private function queryString($value) {

    $qType = $this->filters['pgType'];
    $qFilter = $this->filters['filterType'];
    $qValue = $this->filters['filterVal'];
    $qValue2 = $this->filters['filterVal2'];
    $isSteel = $this->filters['ss'];
    //$isSold = $this->filters['sold'];
    $limit = $this->filters['count'];

    if ($isSteel) {
      $itemRef = "`RHCs`";
      $itemDB = "`benchessinksdb`";
    } else {
      $itemRef = "`RHC`";
      $itemDB = "`networked db`";
    }
    //the query "start". how much data are we getting?
    if ($value == 'counter') {
      $querySelection = "COUNT(*)";
    } elseif ($qType == 'lite' || $isSteel) {
      $querySelection = "$itemRef, `ProductName`, `Price`, `Width`, `Quantity`";
    } else {
      $querySelection = "$itemRef, `ProductName`, `Category`, `Cat1`, `Cat2`, `Cat3`, `Power`, `Price`, `SalePrice`, `Quantity`";
    }

    //the query "middle". what is the data filtered by?

    if ($isSteel) {
      $filters = "(`Category` LIKE %s) AND";
    } elseif ($qFilter == 'items' || $qFilter == 'related') {
      $filters = "(`Category` LIKE %s OR `Cat1` LIKE %s OR `Cat2` LIKE %s OR `Cat3` LIKE %s) AND";
    } elseif ($qFilter == 'search') {
      $filters = "(`ProductName` REGEXP %s OR `Brand` REGEXP %s OR `Category` REGEXP %s OR `Cat1` REGEXP %s OR `Cat2` REGEXP %s OR `Cat3` REGEXP %s) AND";
    } elseif ($qFilter == 'brand') {
      $filters = "(`Brand` LIKE %s) AND";
    } elseif ($qFilter =='sale') {
      $filters = "(`SalePrice` = %d) AND";
    } else {
      $filters = "";
    }

  //the query end. how is the data sorted and limited?
    $orderBy = "(`LiveonRHC` = 1 AND `Quantity` > 0) ORDER BY `DateLive` DESC, $itemRef DESC LIMIT $limit";

    if ($qFilter == 'sold' || $value == 'sold') {
      $orderBy = "(`LiveonRHC` = 1 AND `Quantity` = 0 AND (`DateSold` BETWEEN CURDATE() - INTERVAL $this->duration DAY AND CURDATE())) ORDER BY `DateSold` DESC LIMIT $limit";
    } elseif ($qFilter == 'related' ) {
      $orderBy = "(`LiveonRHC` = 1 AND `Quantity` > 0) AND ($itemRef != $qValue) ORDER BY RAND() LIMIT 4";
    } elseif ($value == 'counter') {
      $orderBy = "(`LiveonRHC` = 1 AND `Quantity` > 0)";
    }

    //queryPlaceholders (for wpdb->prepare)
    $qArray = false; //no prepare for non-variables
    if ($qFilter == 'items') {
      $qArray = [ $qValue, $qValue, $qValue, $qValue ];
    } elseif ($qFilter == 'related') {
      $qArray = [ $qValue2, $qValue2, $qValue2, $qValue2 ];
    } elseif ($qFilter == 'search') {
      $qArray = [ $qValue, $qValue, $qValue, $qValue, $qValue, $qValue ];
    } elseif ($isSteel || $qFilter == 'brand' || $qFilter =='sale') {
      $qArray = [ $qValue ];
    }

    $out['str'] = "SELECT $querySelection FROM $itemDB WHERE $filters $orderBy";
    $out['placeholders'] = $qArray;
    return $out;
  }

  private function getQueryResults($queryType = 'default') {

    $query = $this->queryString($queryType);

    if ($query['placeholders']) {
      $out = $this->wpdb->get_results(
        $this->wpdb->prepare($query['str'], $query['placeholders'])
      );
    } else {
      $out = $this->wpdb->get_results($query['str']);
    }
    if ($queryType == 'counter') {
      $out = $out[0]->{'COUNT(*)'};
    }
    return $out;
  }

}
?>
