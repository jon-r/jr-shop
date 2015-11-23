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
    $filters = [
      'title' => $this->dbRaw->Category,
      'filterVal' => $this->refNum,
      'ss' => $this->ss,
      'count' => '4',
      'pgType' => 'lite',
      'filterType' => 'related'
    ];
    $related->getRelated($filters);
   // print_r();
    return $related;
  }

}

?>

