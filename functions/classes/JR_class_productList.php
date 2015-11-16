<?
class productList {

  private $err = '';
  private $wpdb = $GLOBALS['wpdb'];
  private $countMax = $GLOBALS['itemCountMax'];
  private $duration = $GLOBALS['itemSoldDuration']
  private $dbRaw = array();

  public function getItems($filterType,$filterList) {
    foreach ($filterList as $f => $value) {
      $this->filters[$f] = $value;
    }

    return $this->queryList();
  }

  private function queryList() {
    $listUnsold = $this->queryUnsold();
    $out['paginate'] = false;
    $lastPage = 1;

    if ($this->filters['filterType'] != 'arrivals' && $this->filters['sold'] == false) {
      //the "sold" and "new" already capped at a single page, no need to count
      $fullItemCount = $this->itemsCount();
      //breaks down into pages
      if ($fullItemCount > $this->countMax) {
        $out['paginate'] = $lastPage = intval(ceil($fullItemCount / $this->countMax));
      }
      //fills up the last page with sold items
      if ($pageNumber == $lastPage) {
        $itemsOnLastPage = $fullItemCount % $this->countMax;
        $listSold = $this->querySold($itemsOnLastPage);
      }
    }
    $out['list'] = isset($listSold) ? array_merge($listUnsold, $listSold) : $listUnsold;
    return $out;
  }

  private function itemsCount() {
    $this->filters['pgType'] = 'counter';

    $result = $this->getQueryResults();
    return count($result[0]);
  }

  private function queryUnsold() {

    $pageNumber = isset($_GET['pg']) ? $_GET['pg'] : 1;

    $queryOffset = ($pageNumber - 1) * $this->countMax;
    $queryLimiter = "$queryOffset,$this->countMax";

    $this->filters['count'] = $queryLimiter;
    return $this->getQueryResults();
  }

  private function querySold($itemsOnPage) {

    if ($this->filters['pgType'] == 'arrivals' || $this->filters['pgType'] == 'sold') {
      $out = null;
    } else {
      $soldCount = 4 - ($itemsOnPage % 4);
      $this->filters['sold'] = true;
      $this->filters['count'] = $soldCount;

    }
    return $out;
  }

  private function queryString() {

    $qType = $this->filters['pgType'];
    $qFilter = $this->filters['filterType'];
    $qValue = $this->filters['filterVal'];
    $qValue2 = $this->filters['filterVal2'];
    $isSteel = $this->filters['ss'];
    $isSold = $this->filters['sold'];
    $limit = $this->filters['count'];

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

  //  if ($qFilter == 'soon' ) {
  //    $orderBy = "(`LiveonRHC` = 0 AND `IsSoon` = 1) ORDER BY $itemRef DESC";
  //  } else
    if ($isSold) {
      $orderBy = "(`LiveonRHC` = 1 AND `Quantity` = 0 AND (`DateSold` BETWEEN CURDATE() - INTERVAL $this->duration DAY AND CURDATE())) ORDER BY `DateSold` DESC LIMIT $limit";
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

  private function getQueryResults() {
    $query = $this->queryString();

    if ($query['placeholders']) {
      $out = $this->wpdb->get_results(
        $this->wpdb->prepare($query['str'], $query['placeholders'])
      );
    } else {
      $out = $this->wpdb->get_results($query['str']);
    }
    return $out;
  }

}
*/
?>
