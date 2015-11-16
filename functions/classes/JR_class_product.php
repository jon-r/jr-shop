<?php
//combine all the product number crunching into one class
class product {

  private $refNum;
  private $err = '';

  private $dbRaw = array();
  private $ss;

  public $OUTTEMP;

  public function setRef($ref,$ss) {
    $this->refNum = $ref;
    $this->ss = $ss;
  }

  private function validate() {
    global $wpdb, $itemSoldDuration;

    $tbl = $this->ss ? 'benchessinksdb' : 'networked db';
    $ref = $this->ss ? 'RHCs' : 'RHC';

    $q =  "SELECT `$ref` FROM `$tbl` ";
    $q .= "WHERE `$ref` = %s AND `LiveonRHC` = 1 AND ((`Quantity` > 0) OR ";
    $q .= "( `Quantity` = 0 AND `DateSold` BETWEEN CURDATE() - INTERVAL $itemSoldDuration DAY AND CURDATE()))";

    $out = $wpdb->get_var($wpdb->prepare($q, $this->refNum));
    return $out != null;
  }

  private function setDbInfo() {
    if ($this->err == '') {
      if ($this->validate()) {
        $this->dbRaw = jrQ_getItem($this->refNum,$this->ss);
        $this->OUTTEMP = $this->dbRaw;
      } else {
        $this->err = 'Not Found';
      }
    }

  }

  public function compiler() {
    if ($this->err == '') {
      $this->setDbInfo();
      $item = new compile;
      $out = $item->itemCompile($this->dbRaw,'full',$this->ss);
    } else {
      $out['err'] = $this->err;
    }
    return $out;
  }

}

?>

