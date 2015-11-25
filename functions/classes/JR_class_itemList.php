<?php
class itemList {

  private $dbRaw = array();

  public $paginate = false;
  public $newCheck;
  public $pgCount = 0;
  public $pgList;
  public $description;
  public $title;

  public function get($filterList) {
    $this->setGlobals();
    foreach ($filterList as $f => $value) {
      $this->filters[$f] = $value;
    }
    $this->setVars();
    $this->pgList = $this->queryList();
    $this->pgCount = count($this->pgList);
  }

  public function getRelated($filterList) {
    $this->setGlobals();
    $this->title = $filterList['title'];
    $this->filters = $filterList;
    $this->limit = 4;
    $this->pgList = $this->getQueryResults();
  }

  private function setVars() {
    if ($this->filters['filterType'] == 'items') {
      $catID = $this->filters['id'];
      $cat = $this->wpdb->get_row("SELECT `Name`, `CategoryDescription`, `Is_RHCs` FROM `rhc_categories` WHERE `Category_ID` LIKE '$catID'");

      $this->title = $cat->Name;
      $desc = $cat->CategoryDescription != '0' ? $cat->CategoryDescription : null;
    } else {
      $cat = $this->filters['filterType'];
      $desc = get_option("jr_shop_pageInfo_$cat");

      $this->title = $this->filters['title'];

    }
    $this->description = $desc ? jr_format($desc) : null;
  }

  private function setGlobals() {
    global $wpdb, $itemCountMax, $itemSoldDuration;
    $this->wpdb = $wpdb;
    $this->countMax = $itemCountMax;
    $this->duration = $itemSoldDuration;
    $this->pgNum = isset($_GET['pg']) ? $_GET['pg'] : 1;
  }

  private function queryList() {
    $listUnsold = $this->queryUnsold();
    $this->paginate = false;
    $lastPage = 1;

    if ($this->filters['filterType'] != 'arrivals' && $this->filters['filterType'] != 'sold') {
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
        $this->limit = $soldCount;
        $listSold = $this->getQueryResults('sold');
      }
    }
    $out = isset($listSold) ? array_merge($listUnsold, $listSold) : $listUnsold;
    return $out;
  }


  private function queryUnsold() {

    $queryOffset = ($this->pgNum - 1) * $this->countMax;
    $queryLimiter = "$queryOffset,$this->countMax";

    $this->limit = $queryLimiter;
    return $this->getQueryResults();
  }

  public function setQuery($args = []) {

    $defaults = [
      'brand' => null,
      'category' => null,
      'search' => null,
      'sale' => null,
      'sold' => false,
      'limit' => $this->countMax,
      'type' => null,
      'ss' => false,
      'refNum' => null
    ];

    $f = wp_parse_args($args, $defaults);

    //INITIAL SETUP
    if ($f['ss']) {
      $refName = "`RHCs`";
      $tbl = "`benchessinksdb`";
    } else {
      $refName = "`RHC`";
      $tbl = "`networked db`";
    }
    if ($f['sold']) {
      $order = "`DateSold`";
      $soldSwitch = "`LiveonRHC` = 1 AND `Quantity` = 0 AND (`DateSold` BETWEEN CURDATE() - INTERVAL $this->duration DAY AND CURDATE())";
    } else {
      $order = "`DateLive`";
      $soldSwitch = "`LiveonRHC` = 1 AND `Quantity` > 0";
    }

    //SELECT
    $generic = "$refName, `ProductName`, `Price`, `Quantity`";

    if ($f['type'] == 'count') {
      $values = "COUNT(*)";
      $limit = "";

    } elseif ($f['type'] == 'related') {
      $values = "$generic";
      $refNum = $f['refNum'];
      $filterList = ["AND ($refNum != 0) "];
      $limit = "ORDER BY RAND() LIMIT ".$f['limit'];

    } else {

      if ($f['ss']) {
        $values = "$generic `Width`";
      } else {
        $values = "$generic `Category`, `Cat1`, `Cat2`, `Cat3`, `Power`, `SalePrice`";
      }
      $limit = "ORDER BY $order LIMIT ".$f['limit'];
    }

    //WHERE
    $filterList = [];
    $placeholders = [];

    if ($f['brand']) {
      $filterList['brand'] = "`Brand` LIKE %s";
      $placeholders = [$f['brand']];
    }
    if ($f['category']) {
      $filterList['cat'] = "`Category` LIKE %s OR `Cat1` LIKE %s OR `Cat2` LIKE %s OR `Cat3` LIKE %s";
      $placeholders = [$f['category'],$f['category'],
                   $f['category'],$f['category']]; //*4
    }
    if ($f['search']) {
      $filterList['search'] = "`ProductName` REGEXP %s OR `Brand` REGEXP %s OR `Category` REGEXP %s OR `Cat1` REGEXP %s OR `Cat2` REGEXP %s OR `Cat3` REGEXP %s";
      $placeholders = [$f['search'],$f['search'],$f['search'],
                   $f['search'],$f['search'],$f['search']]; //*6
    }
    if ($f['sale']) {
      $filterList['sale'] = "`SalePrice` = %d";
      $placeholders = [$f['sale']];
    }
    $filterList['sold'] = $soldSwitch;

    $filterStr = implode(") AND (", $filterList);

    $queryFull = "SELECT $values FROM $tbl WHERE ($filterStr) $limit";

    return $this->wpdb->prepare($queryFull,$placeholders);
/*

    querys

    all = rhc;
    brand = rhc;
    items = rhc OR rhcs;
    search = rhc AND rhcs;
    sale = rhc;



    type:
      'all'
      'brand'
      'items'
      'search'
      'sale'
    val:
      'forsale'
      'count'
      'related'
      'sold'
    toggle:
      'ss'*/
  }

  private function queryString($value) {

    $qFilter = $this->filters['filterType'];
    $qValue = $this->filters['filterVal'];

    $qRHC = $qValue == 'related' ? $this->filters['refNum'] : null;

    $isSteel = $this->filters['ss'];


    $limit = $this->limit;

    /*
    $generic = "$itemRef, `ProductName`, `Price`, `Quantity`";
    $dataList = [
      'RHCs'  => "$data `Width`",
      'RHC'   => "$data `Category`, `Cat1`, `Cat2`, `Cat3`, `Power`, `SalePrice`",
      'count' => "COUNT(*)",
    ];

    $data = $datalist[$qType];

    $filterList = [
      'all'       => "",
      'brand'     => "(`Brand` like %s) AND",
      'items'     => "(`Category` LIKE %s OR `Cat1` LIKE %s OR `Cat2` LIKE %s OR `Cat3` LIKE %s) AND",
      'itemsSS'   => "(`Category` LIKE %s) AND",
      'search'    => "(`ProductName` REGEXP %s OR `Brand` REGEXP %s OR `Category` REGEXP %s OR `Cat1` REGEXP %s OR `Cat2` REGEXP %s OR `Cat3` REGEXP %s) AND",
      'searchSS'  => "(`ProductName` REGEXP %s OR `Category`) AND",
      'sale'      => "(`SalePrice` = %d) AND"
    ];

    $filter = $filterList[$qFilter];

    $forSale = "(`LiveonRHC` = 1 AND `Quantity` > 0)";
    $sortList = [
      'sold'    => "(`LiveonRHC` = 1 AND `Quantity` = 0 AND (`DateSold` BETWEEN CURDATE() - INTERVAL $this->duration DAY AND CURDATE())) ORDER BY `DateSold` DESC LIMIT $limit",
      'related' => "$forSale AND ($itemRef != 0) ORDER BY RAND() LIMIT $limit",
      'count' => $forSale
    ];

    if ( array_key_exists ( $qValue , $sortList )) {
      $sort = $sortList[$qValue];
    } else {
      $sort = "$forSale ORDER BY `DateLive` DESC, $itemRef DESC LIMIT $limit";
    }

    $placeholders = [
      'items'  => [$this->title, $this->title, $this->title, $this->title],
      'search' => [$qValue, $qValue, $qValue, $qValue, $qValue, $qValue]
    ];

    if (array_key_exists($qFilter, $placeholders)) {
      $placeholders = $placeholders[$qFilter];
    } else {
      $placeholders = [$qValue];
    };

    $str = "SELECT %s FROM $itemDB WHERE %s %s";
    $query = sprintf($str, $data, $filter, $sort);



    if ($isSteel) {
      $itemRef = "`RHCs`";
      $itemDB = "`benchessinksdb`";
    } else {
      $itemRef = "`RHC`";
      $itemDB = "`networked db`";
    }



    var_dump($this->wpdb->prepare($query,$placeholders));






    */




    /*OLD BELOW --------------------------------------*/


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
    } elseif ($qFilter == 'related' || $isSteel) {
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
      $orderBy = "(`LiveonRHC` = 1 AND `Quantity` > 0) AND ($itemRef != $qValue) ORDER BY RAND() LIMIT $limit";
    } elseif ($value == 'counter') {
      $orderBy = "(`LiveonRHC` = 1 AND `Quantity` > 0)";
    }

    //queryPlaceholders (for wpdb->prepare)
    $qArray = false; //no prepare for non-variables
    if ($qFilter == 'items' || $qFilter == 'related') {
      $v = $this->title;
      $qArray = [ $v, $v, $v, $v ];
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
    } else {
      foreach ($out as $item) {
        $item->ss = property_exists($item, 'RHCs');
      }
    }


    return $out;
  }

}
?>
