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

  private function setDbInfo() {
    global $wpdb;
    $safeRHC = $this->refNum;

    if ($this->ss) {
      $queryFull = $wpdb->get_row("SELECT `RHCs`, `ProductName`, `Category`, `Height`, `Width`, `Depth`, `Price`, `Quantity`, `TableinFeet`, `Line1` FROM `benchessinksdb` WHERE `RHCs` = '$safeRHC'");
    } else {
      $queryFull = $wpdb->get_row("SELECT `RHC`, `ProductName`, `Price`, `Height`, `Width`, `Depth`, `Model`, `Brand`, `Wattage`, `Power`, `ExtraMeasurements`, `Line 1`, `Line 2`, `Line 3`, `Condition/Damages`, `Sold`, `Quantity`, `Category`, `Cat1`, `Cat2`, `Cat3`, `SalePrice` FROM `networked db` WHERE `RHC` = '$safeRHC'");
    }
    $this->dbRaw = $queryFull;
  }


  public function compiler() {
    $this->setDbInfo();
    $item = new compile;
    $out = $item->itemCompile($this->dbRaw,'full',$this->ss);
    return $out;
  }

  public function related() {
    $related = new itemList();
    $filters = [
      'title' => $this->dbRaw->Category,
      'filterVal' => $this->refNum,
      'ss' => $this->ss,
      'filterType' => 'related'
    ];
    $related->getRelated($filters);
   // print_r();
    return $related;
  }

}

?>

