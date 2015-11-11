<?php
//combine all the product number crunching into one class
class productSingle {

  private $refNum;
  private $table;

  private $dbRaw = array();
  private $ss = strtolower($table) == 'rhcs';

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

  private $isValid = $this->validate();

  private function getDbInfo() {
    return $this->dbRaw = jrQ_getItem($table,$safeRHC);
  }

}

/*
class productMulti {

  private $refArray;
  private $table;


}
*/

class compile {

  private $refNum;
  private $detail;
  private $ss;

  private function MMtoFeet($mm) {
    $justInches = $mm / 25.4;
    if ($justInches < 24) {
      $out = ceil($justInches).'in';
    } else {
      $feet = floor($justInches / 12);
      $inches = $justInches % 12;
      $out = $feet."ft ";
      $out .= $inches > 0 ? $inches.'in' : null;
    }
    return $out;
  }

  private function getBrand() {
    if ($this->Brand) {
      $brandUrl = sanitize_title($this->Brand);
      $brandImg = jr_siteImg('brands/long/'.$brandUrl.'-logo.jpg', $relative = true);
      if (file_exists(ABSPATH.$brandImg)) {
        $brandText = '<img class="framed" src="'.site_url($brandImg).'" alt="'.$this->Brand.'" >'
          .'<a href="'.home_url('products/brand/'.$brandUrl).'" >More from '.$this->Brand.'</a>';
      } else {
        $brandText = $this->Brand.' (<a href="'.home_url('products/brand/'.$brandUrl).'" >More</a>)';
      }
    } else {
      $brandText = null;
    };
    return $brandText;
  }

  private function getPower() {
    if ($this->Wattage >= 1500) {
      $pwrCheck = ($this->Wattage / 1000).'kw, '.$this->Power;
    } elseif ($this->Wattage < 1500 && $this->Wattage > 0) {
      $pwrCheck = $this->Wattage.' watts, '.$this->Power;
    } elseif ($this->Power) {
      $pwrCheck = $this->Power;
    } else {
      $pwrCheck = null;
    };
    return $pwrCheck;
  }

  private function getSpecs() {
    $out = [
      'Brand'   => $this->getBrand() ?:null,
      'Model'   => $this->Model ?: null,
      'Power'   => $this->getPower() ?: null,
      'Height'  => $this->Height ? $this->Height.'mm / '.MMtoFeet($this->Height) : null,
      'Width'   => $this->Width ? $this->Width.'mm / '.MMtoFeet($this->Width) : null,
      'Depth'   => $this->Depth ? $this->Depth.'mm / '.MMtoFeet($this->Depth) : null,
    ];
    if ($this->ExtraMeasurements) {
      $extras = explode(';',$this->ExtraMeasurements);
      foreach($extras as $extra) {
        if (strpos($extra, ":") === false) {
          $out[] = trim($extra);
        } else {
          $item = explode(':',$extra);
          $out[trim($item[0])] = trim($item[1]);
        }
      }
    };
    //put last to keep in order
    $out['Please Note'] = $this->{'Condition/Damages'} != "0" ? $this->{'Condition/Damages'} : null;
    return array_filter($out);
  }

  private function getImgs() {
    $name = ($this->ss) ? 'RHCs' : 'RHC';
    $out = glob('rhc/images/gallery/'.$name.$this->refNum.'[!0-9]*', GLOB_BRACE);
    return $out;
  }

  private function getIcon() {
    if ($this->ss) {
      $catArray = [$ref['Category']];
    } else {
      $catArray = [ $ref['Category'],  $ref['Cat1'],  $ref['Cat2'], $ref['Cat3'] ];
    }

    if (in_array('Fridges', $catArray) && in_array('Freezers', $catArray)) {
      $iconCheck = 'fridge-freezer';
    } elseif (in_array('Fridges', $catArray)) {
      $iconCheck = 'fridge';
    } elseif (in_array('Freezers', $catArray)) {
      $iconCheck = 'freezer';
    } elseif ($ref['Power']) {
      $iconCheck = str_replace(' ', '-', strtolower($ref['Power']));
    } else {
      $iconCheck = null;
    };
    return $iconCheck;
  }

  private function getInfo() {
    if (isset($ref['isSale'])) {
      $infoCheck = "sale";
    } elseif ($ref['Quantity'] == 0) {
      $infoCheck = "sold";
    } elseif (in_array($ref['RHC'], $newCheck)) {
      $infoCheck = "new";
    } else {
      $infoCheck = null;
    };
    return $infoCheck;
  }

  public function compile($dbRaw) {

    switch ($this->detail) {
      case ('Full') :
        $out = [
          'height'    => $dbRaw->Height ?: null,
          'width'     => $dbRaw->Width ?: null,
          'depth'     => $dbRaw->Depth ?: null,
          'desc'      => ($dbRaw->{'Line 1'} != "0" ? '<p>'.$dbRaw->{'Line 1'}.'</p>' : null).
                         ($dbRaw->{'Line 2'} != "0" ? '<p>'.$dbRaw->{'Line 2'}.'</p>' : null).
                         ($dbRaw->{'Line 3'} != "0" ? '<p>'.$dbRaw->{'Line 3'}.'</p>' : null),
          'specs'     => $dbRaw->getSpecs(),
          'imgAll'    => $dbRaw->getImgs(),
          //this glob targets only the valid RHC reference. ie 'RHC10', 'RHC10 b', NOT 'RHC101'
          'category'  => $dbRaw->'Category'
        ];
      case ('Tile') :
        $out = [
          'icon'     => $dbRaw->iconCheck(),
          'info'     => $dbRaw->infoCheck(),
          'quantity' => $ref['Quantity'] > 1 ? $ref['Quantity']." in Stock" : null,
        ]

    }

  }
