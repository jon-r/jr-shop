<?php
class itemList {

  private $args;


  public $paginate = false;
  public $pgCount = 0;
  public $pgList;

  public function get() {
    $this->setArgs();
    $this->pgList = $this->queryList();
    $this->pgCount = count($this->pgList);
  }

  public function getCustom($args) {
    $this->setArgs();
    $this->pgList = $this->queryResults($args);
  }

  private function setArgs() {
    global $jr_page, $wpdb;

    $this->maxList = get_option('jr_shop_itemCountMax');

    $defaults = [
      'brand' => null,'category' => null, 'search' => null,
      'sale' => null, 'sold' => false,    'limit' => $this->maxList,
      'type' => null, 'ss' => false,      'arrivals' => false,'refNum' => null
    ];

    $this->args = wp_parse_args($jr_page->args, $defaults);
    $this->newList = $wpdb->get_col("SELECT `rhc` FROM `networked db` WHERE (`LiveonRHC` = 1 AND `Quantity` > 0) ORDER BY `DateLive` DESC, `rhc` DESC LIMIT $this->maxList") ;

    $this->pgNum = isset($_GET['pg']) ? $_GET['pg'] : 1;
  }

  private function queryList() {

    $this->paginate = false;
    $lastPage = 1;

    $offset = ($this->pgNum - 1) * $this->maxList;

    $listUnsold = $this->queryResults(['limit'=>"$offset,$this->maxList"]);
    $listSold = array();

    if (!$this->args['arrivals'] && !$this->args['sold'] && $this->args['type'] != 'related') {
      //counts whole query. the "sold" and "new" already capped at a single page, no need to count
      $fullItemCount =  $this->queryResults(['type'=>'count']);
      //breaks down into pages
      if ($fullItemCount > $this->maxList) {
        $this->paginate = intval(ceil($fullItemCount / $this->maxList));
      }

      //fills up the last page with sold items
      if ($this->paginate <= $this->pgNum) {
        $soldCount = 8 - ($fullItemCount % 4);
        $listSold = $this->queryResults(['sold'=>true,'limit'=>$soldCount]);
      }
    }
    $out = array_merge($listUnsold, $listSold);
    return $out;
  }


  private function setQuery($args) {

    global $itemSoldDuration;
    //INITIAL SETUP
    if ($args['ss']) {
      $refName = "`RHCs`";
      $tbl = "`benchessinksdb`";
    } else {
      $refName = "`RHC`";
      $tbl = "`networked db`";
    }
    if ($args['sold']) {
      $order = "`DateSold` DESC";
      $soldSwitch = "`LiveonRHC` = 1 AND `Quantity` = 0 AND (`DateSold` BETWEEN CURDATE() - INTERVAL $itemSoldDuration DAY AND CURDATE())";
    } else {
      $order = "`DateLive` DESC";
      $soldSwitch = "`LiveonRHC` = 1 AND `Quantity` > 0";
    }

    //SELECT
    $generic = "$refName, `ProductName`, `Price`, `SalePrice`, `Quantity`";

    if ($args['type'] == 'count') {
      $values = "COUNT(*)";
      $limit = "";

    } elseif ($args['type'] == 'related') {
      $values = "$generic";
      $ref = $args['ref'];
      $id = $args['id'];
      $filterList = ["AND ($ref != $id) "];
      $limit = "ORDER BY RAND() LIMIT ".$args['limit'];

    } else {

      if ($args['ss']) {
        $values = "$generic, `Width`";
      } else {
        $values = "$generic, `Category`, `Cat1`, `Cat2`, `Cat3`, `Power`, `SalePrice`, `flagItem`, `isDomestic`";
      }
      $limit = "ORDER BY $order LIMIT ".$args['limit'];
    }

    //WHERE
    $filterList = [];
    $placeholders = [];

    if ($args['brand']) {
      $filterList['brand'] = "`Brand` LIKE %s";
      $placeholders = [$args['brand']];
    }
    if ($args['category']) {
      if ($args['ss']) {
        $filterList['cat'] = "`Category` LIKE %s";
        $placeholders = [$args['category']]; //*1
      } else {
        $filterList['cat'] = "`Category` LIKE %s OR `Cat1` LIKE %s OR `Cat2` LIKE %s OR `Cat3` LIKE %s";
        $ph = $args['category'];
        $placeholders = [$ph,$ph,$ph,$ph]; //*4
      }

    }
    if ($args['search']) {
      if ($args['ss']) {
        $filterList['search'] = "`ProductName` REGEXP %1$s OR `Category` REGEXP %1$s";
        $ph = $args['search'];
        $placeholders = [$ph,$ph]; //*2
      } else {
        $filterList['search'] = "`ProductName` REGEXP %1$s OR `Brand` REGEXP %1$s OR `Category` REGEXP %1$s OR `Cat1` REGEXP %1$s OR `Cat2` REGEXP %1$s OR `Cat3` REGEXP %1$s";
        $ph = $args['search'];
        $placeholders = [$ph,$ph,$ph,$ph,$ph,$ph]; //*6
      }
    }
    if ($args['sale']) {
      $filterList['sale'] = "`SalePrice` = %d";
      $placeholders = [$args['sale']];
    }
    $filterList['sold'] = $soldSwitch;

    $filterStr = implode(") AND (", $filterList);

    $queryFull = "SELECT $values FROM $tbl WHERE ($filterStr) $limit";

    if (!empty($placeholders)) {
      global $wpdb;
      return $wpdb->prepare($queryFull,$placeholders);
    } else {
      return $queryFull;
    }

  }

  private function queryResults($tweaks = []) {
    global $wpdb;
    $argsTweaked = array_replace($this->args,$tweaks);
    $query = $this->setQuery($argsTweaked);
    $out = $wpdb->get_results($query);

    if ($argsTweaked['type'] == 'count') {
      $out = $out[0]->{'COUNT(*)'};
    } else {
      foreach ($out as $item) {
        //settings not taken directly from DB
        $item->ss = property_exists($item, 'RHCs');
        $item->isNew = property_exists($item, 'RHC') && in_array($item->RHC, $this->newList);
      }
    }

    return $out;
  }

}
?>
