<?php
//combine all the product number crunching into one class
class productSingle {

  private $refNum;
  private $table;
  private $err = '';

  private $dbRaw = array();
  private $ss;


  private function ss() {
    return (strtolower($table) == 'rhcs');
  }

  public function setRef($rhcRef) {
    preg_match('/(RHCs?)(\d+)/i',$rhcRef, $refArray);
//    $ref = preg_split('/(RHCs?)(\d+)/i', $rhcRef, PREG_SPLIT_DELIM_CAPTURE);
    if ($refArray !== false) {
      $this->refNum = $refArray[0];
      $this->table = $refArray[1];
    } else {
      $this->err = 'Invalid Ref: '.$rhcRef;
    }

  }

  private function validate() {
    global $wpdb, $itemSoldDuration;

    $tbl = $this->ss() ? 'benchessinksdb' : 'networked db';
    $ref = $this->ss() ? 'RHCs' : 'RHC';

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
        $this->dbRaw = jrQ_getItem($table,$rhcRef);
      } else {
        $this->err = 'Not Found';
      }
    }
  }

  public function getCompiled($detail) {
    if ($this->err == '') {
      $array = $this->setDbInfo();
      $item = new compile;
      $out = $item->complile($array,$detail);
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


