<?php
//combine all the product number crunching into one class
class product {

  private $refNum;

  private $dbRaw = array();
  private $ss;

  public function setRef() {
    global $jr_page;

    $this->refNum = $jr_page->args['id'];
    $this->ss = $jr_page->args['ref'] == 'rhcs';
    $this->category = $jr_page->args['category'];
  }

  private function setDbInfo() {
    global $wpdb;
    $refNum = $this->refNum;

    if ($this->ss) {
      $queryFull = $wpdb->get_row("SELECT `RHCs`, `ProductName`, `Category`, `Height`, `Width`, `Depth`, `Price`, `Quantity`, `TableinFeet`, `Line1` FROM `benchessinksdb` WHERE `RHCs` = '$refNum'");
    } else {
      $queryFull = $wpdb->get_row("SELECT `RHC`, `ProductName`, `Price`, `Height`, `Width`, `Depth`, `Model`, `Brand`, `Wattage`, `Power`, `ExtraMeasurements`, `Line 1`, `Line 2`, `Line 3`, `Condition/Damages`, `Sold`, `Quantity`, `Category`, `Cat1`, `Cat2`, `Cat3`, `SalePrice`, `video_link`, `SEO_meta_text` FROM `networked db` WHERE `RHC` = '$refNum'");
    }
    $this->dbRaw = $queryFull;
    $this->dbRaw->ss = $this->ss;
    $this->dbRaw->isNew = false;

  }

  public function compiler() {
    $this->setDbInfo();
    $item = new compile;
    $out = $item->itemCompile($this->dbRaw,'full');
    return $out;
  }

  public function related() {
    $related = new itemList();
    $args = [
      'type' => 'related',
      'ss' => $this->ss,
      'limit' => '4'
    ];
    $related->getCustom($args);
    return $related;
  }

  public function scaleBox() {
     //show the box sim, if not furnishings and valid height/width
    $badCats = explode(',',get_option('jr_shop_hide_scale'));

    if ($this->dbRaw->Height > 0
        && $this->dbRaw->Width > 0
        && !in_array($this->dbRaw->Category, $badCats)
       ) {
      return jr_boxGen($this->dbRaw);
    } else {
      return false;
    }


  }

}

?>
