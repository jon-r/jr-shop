<?php
class compile {

  private $ss;
  //private $rawArr;
/*
private $refNum;
  private $rawArr;
  private $detail;

  */

/*
public function setVal($arr) {
    $this->rawArr = $arr;
  }*/

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

  private function setBrand($dbRaw) {
    if ($dbRaw->Brand) {
      $brandUrl = sanitize_title($dbRaw->Brand);
      $brandImg = jr_siteImg('brands/long/'.$brandUrl.'-logo.jpg', $relative = true);
      if (file_exists(ABSPATH.$brandImg)) {
        $brandText = '<img class="framed" src="'.site_url($brandImg).'" alt="'.$dbRaw->Brand.'" >'
          .'<a href="'.home_url('products/brand/'.$brandUrl).'" >More from '.$dbRaw->Brand.'</a>';
      } else {
        $brandText = $dbRaw->Brand.' (<a href="'.home_url('products/brand/'.$brandUrl).'" >More</a>)';
      }
    } else {
      $brandText = null;
    };
    return $brandText;
  }

  private function setPower($dbRaw) {
    if ($dbRaw->Wattage >= 1500) {
      $pwrCheck = ($dbRaw->Wattage / 1000).'kw, '.$dbRaw->Power;
    } elseif ($dbRaw->Wattage < 1500 && $dbRaw->Wattage > 0) {
      $pwrCheck = $dbRaw->Wattage.' watts, '.$dbRaw->Power;
    } elseif ($dbRaw->Power) {
      $pwrCheck = $dbRaw->Power;
    } else {
      $pwrCheck = null;
    };
    return $pwrCheck;
  }

  private function setSpecs($dbRaw) {
    $out = [
      'Brand'   => $this->setBrand($dbRaw) ?:null,
      'Model'   => $dbRaw->Model ?: null,
      'Power'   => $this->setPower($dbRaw) ?: null,
      'Height'  => $dbRaw->Height ? $dbRaw->Height.'mm / '.$this->MMtoFeet($dbRaw->Height) : null,
      'Width'   => $dbRaw->Width ? $dbRaw->Width.'mm / '.$this->MMtoFeet($dbRaw->Width) : null,
      'Depth'   => $dbRaw->Depth ? $dbRaw->Depth.'mm / '.$this->MMtoFeet($dbRaw->Depth) : null,
    ];
    if ($dbRaw->ExtraMeasurements) {
      $extras = explode(';',$dbRaw->ExtraMeasurements);
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
    $out['Please Note'] = $dbRaw->{'Condition/Damages'} != "0" ? $dbRaw->{'Condition/Damages'} : null;
    return array_filter($out);
  }

  private function setImgs($dbRaw) {
    $name = ($this->ss) ? 'RHCs' : 'RHC';
    $out = glob('images/gallery/'.$name.$dbRaw->RHC.'[!0-9]*', GLOB_BRACE);
    return $out;
  }

  private function setIcon($dbRaw) {
    $iconCheck = null;

    if (!$this->ss) {
      $catArray = [$dbRaw->Category,  $dbRaw->Cat1,  $dbRaw->Cat2, $dbRaw->Cat3 ];

      if (in_array('Fridges', $catArray) && in_array('Freezers', $catArray)) {
        $iconCheck = 'fridge-freezer';
      } elseif (in_array('Fridges', $catArray)) {
        $iconCheck = 'fridge';
      } elseif (in_array('Freezers', $catArray)) {
        $iconCheck = 'freezer';
      } elseif ($dbRaw->Power) {
        $iconCheck = str_replace(' ', '-', strtolower($dbRaw->Power));
      }
    }
    return $iconCheck;
  }

  private function setInfo($dbRaw) {
    if (isset($dbRaw->isSale)) {
      $infoCheck = "sale";
    } elseif ($dbRaw->Quantity == 0) {
      $infoCheck = "sold";
    } elseif (in_array($dbRaw->RHC, jrQ_ItemsNew())) {
      $infoCheck = "new";
    } else {
      $infoCheck = null;
    };
    return $infoCheck;
  }

  private function setPrice($dbRaw) {
    if ($dbRaw->Quantity == 0) {
      $priceCheck = 'Sold';
    } elseif ($dbRaw->Price) {
      $priceCheck = "£".$dbRaw->Price." + VAT";
    } else {
      $priceCheck = "Price Coming Soon";
    };
    return $priceCheck;
  }

  private function setWebLink($dbRaw) {
    if ($this->ss) {
      $url = 'rhcs/';
    } else {
      $url = 'rhc/';
    }
    return $url.$dbRaw->RHC.'/'.sanitize_title($dbRaw->ProductName);
  }

  private function setRefNum($dbRaw) {
    if ($this->ss) {
      $id = 'RHCs';
    } else {
      $id = 'RHC';
    }
    return $id.$dbRaw->RHC;
  }

  public function itemCompile($dbRaw,$detail,$ss) {

    $out = array();
    //$this->rawArr = $dbRaw;
    $this->ss = $ss;

    switch ($detail) {
      case ('full') :
        $out1 = [
          'height'    => $dbRaw->Height ?: null,
          'width'     => $dbRaw->Width ?: null,
          'depth'     => $dbRaw->Depth ?: null,
          'desc'      => ($dbRaw->{'Line 1'} != "0" ? '<p>'.$dbRaw->{'Line 1'}.'</p>' : null).
                         ($dbRaw->{'Line 2'} != "0" ? '<p>'.$dbRaw->{'Line 2'}.'</p>' : null).
                         ($dbRaw->{'Line 3'} != "0" ? '<p>'.$dbRaw->{'Line 3'}.'</p>' : null),
          'specs'     => $this->setSpecs($dbRaw),
          'imgAll'    => $this->setImgs($dbRaw),
          //this glob targets only the valid RHC reference. ie 'RHC10', 'RHC10 b', NOT 'RHC101'
          'category'  => $dbRaw->Category
        ];
      case ('tile') :
        $out2 = [
          'widthFt'  => $dbRaw->Width ? $this->MMtoFeet($dbRaw->Width) : null,
          'icon'     => $this->setIcon($dbRaw),
          'info'     => $this->setInfo($dbRaw),
          'quantity' => $dbRaw->Quantity > 1 ? $dbRaw->Quantity." in Stock" : null,
        ];
      case ('lite') :
        $out3 = [
          'price'    => $this->setPrice($dbRaw),
          'webLink'  => $this->setWebLink($dbRaw),
          'rhc'      => $this->setRefNum($dbRaw),
          'name'     => $dbRaw->ProductName,
          'imgFirst' => jr_siteImg('gallery/RHC'.$dbRaw->RHC.'.jpg'),
        ];
        break;
      default:
        $out1 = ['error' => 'invalid term "'.$detail.'"'];
        break;
    }
    return array_merge($out1,$out2,$out3);
  }
};
  ?>
