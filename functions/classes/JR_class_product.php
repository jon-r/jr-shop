<?php
//combine all the product number crunching into one class
class product {

  private $refNum;

  private $dbRaw = array();
  private $ss;
  private $safeArray;

  public function setRef($arr) {
    $this->safeArray = $arr;
    $this->refNum = $this->safeArray['filterVal'];
    $this->ss = $this->safeArray['ss'];
  }

/*
private function validate() {
    global $wpdb, $itemSoldDuration;

    $tbl = $this->ss ? 'benchessinksdb' : 'networked db';
    $ref = $this->ss ? 'RHCs' : 'RHC';

    $q =  "SELECT `$ref` FROM `$tbl` ";
    $q .= "WHERE `$ref` = %s AND `LiveonRHC` = 1 AND ((`Quantity` > 0) OR ";
    $q .= "( `Quantity` = 0 AND `DateSold` BETWEEN CURDATE() - INTERVAL $itemSoldDuration DAY AND CURDATE()))";

    $out = $wpdb->get_var($wpdb->prepare($q, $this->refNum));
    return $out != null;
  }*/

  private function setDbInfo() {
    $this->dbRaw = jrQ_getItem($this->refNum,$this->ss);
  }


  public function compiler() {
    $this->setDbInfo();
    $item = new compile;
    $out = $item->itemCompile($this->dbRaw,'full',$this->ss);
    return $out;
  }

  public function related() {
    $related = new itemList();
    $related->getRelated($this->safeArray);
    return $related->pgList;
  }

}

?>

