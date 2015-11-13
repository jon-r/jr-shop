<?php
//combine all the product number crunching into one class
class productSingle {

  private $refNum;
  private $table;
  private $err = '';

  private $dbRaw = array();
  private $ss;

  public function setRef($rhcRef) {
    preg_match('/(RHCs?)(\d+)/i',$rhcRef, $refArray);
//    $ref = preg_split('/(RHCs?)(\d+)/i', $rhcRef, PREG_SPLIT_DELIM_CAPTURE);
    if ($refArray !== false) {
      $this->table = $refArray[1];
      $this->refNum = $refArray[2];
      $this->ss = $refArray[1] == 'rhcs';
    } else {
      $this->err = 'Invalid Ref: '.$rhcRef;
    }

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

  //private $isValid = $this->validate();

  private function setDbInfo() {
    if ($this->err == '') {
      if ($this->validate()) {
        $this->dbRaw = jrQ_getItem($this->table,$this->refNum);
      } else {
        $this->err = 'Not Found';
      }
    }

  }

  public function compiler($detail) {
    if ($this->err == '') {
      $this->setDbInfo();
      $item = new compile;
      $out = $item->itemCompile($this->dbRaw,$detail,$this->ss);
    } else {
      $out['err'] = $this->err;
    }
    return $out;
  }

}

/*
class productMulti {

  private $refArray;
  private $table;


}
*/


