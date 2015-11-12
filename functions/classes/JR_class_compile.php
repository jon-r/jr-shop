<?php
class compile {

  private $ss;
/*
private $refNum;
  private $rawArr;
  private $detail;

  */

/*  public function setArr($arr) {
    $this->rawArr = $arr;
  }*/

  private function MMtoFeet() {
    $justInches = $this / 25.4;
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

  private function setBrand() {
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

  private function setPower() {
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

  private function setSpecs() {
    $out = [
      'Brand'   => $this->setBrand() ?:null,
      'Model'   => $this->Model ?: null,
      'Power'   => $this->setPower() ?: null,
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

  private function setImgs() {
    $name = ($this->ss) ? 'RHCs' : 'RHC';
    $out = glob('rhc/images/gallery/'.$name.$this->refNum.'[!0-9]*', GLOB_BRACE);
    return $out;
  }

  private function setIcon() {
    if ($this->ss) {
      $catArray = [$this->Category];
    } else {
      $catArray = [$this->Category,  $this->Cat1,  $this->Cat2, $this->Cat3 ];
    }

    if (in_array('Fridges', $catArray) && in_array('Freezers', $catArray)) {
      $iconCheck = 'fridge-freezer';
    } elseif (in_array('Fridges', $catArray)) {
      $iconCheck = 'fridge';
    } elseif (in_array('Freezers', $catArray)) {
      $iconCheck = 'freezer';
    } elseif ($this->Power) {
      $iconCheck = str_replace(' ', '-', strtolower($this->Power));
    } else {
      $iconCheck = null;
    };
    return $iconCheck;
  }

  private function setInfo() {
    if (isset($this->isSale)) {
      $infoCheck = "sale";
    } elseif ($this->Quantity == 0) {
      $infoCheck = "sold";
    } elseif (in_array($this->RHC, $newCheck)) {
      $infoCheck = "new";
    } else {
      $infoCheck = null;
    };
    return $infoCheck;
  }

  public function compile($dbRaw,$detail,$isSS) {

    $out = array();
    $this->ss = $isSS;

    switch ($detail) {
      case ('Full') :
        array_push($out, [
          'height'    => $dbRaw->Height ?: null,
          'width'     => $dbRaw->Width ?: null,
          'depth'     => $dbRaw->Depth ?: null,
          'desc'      => ($dbRaw->{'Line 1'} != "0" ? '<p>'.$dbRaw->{'Line 1'}.'</p>' : null).
                         ($dbRaw->{'Line 2'} != "0" ? '<p>'.$dbRaw->{'Line 2'}.'</p>' : null).
                         ($dbRaw->{'Line 3'} != "0" ? '<p>'.$dbRaw->{'Line 3'}.'</p>' : null),
          'specs'     => $dbRaw->setSpecs(),
          'imgAll'    => $dbRaw->setImgs(),
          //this glob targets only the valid RHC reference. ie 'RHC10', 'RHC10 b', NOT 'RHC101'
          'category'  => $dbRaw->Category
        ]);
      case ('Tile') :
        array_push($out, [
          'widthFt'  => $dbRaw->Width ? MMtoFeet($dbRaw->Width) : null,
          'icon'     => $dbRaw->setIcon(),
          'info'     => $dbRaw->setInfo(),
          'quantity' => $dbRaw->Quantity > 1 ? $dbRaw->Quantity." in Stock" : null,
        ]);
      case ('Lite') :
        array_push($out, [
          'price'    => $priceCheck,
          'webLink'  => 'rhc/'.$dbRaw->RHC.'/'.sanitize_title($dbRaw->ProductName),
          'rhc'      => 'ref: RHC'.$dbRaw->RHC,
          'name'     => $dbRaw->ProductName,
          'imgFirst' => jr_siteImg('gallery/RHC'.$dbRaw->RHC.'.jpg'),
        ]);
    }
    return $out;
  }
};
  ?>
