<?php
class compile {

  public function itemCompile($dbRaw,$detail) {

    $out1 = $out2 = $out3 = array();
    $this->db = $dbRaw;
    $this->ss = $this->db->ss;
    $this->rhc = $this->db->ss ? 'RHCs' : 'RHC';
    $this->ref = $this->db->ss ? $this->db->RHCs : $this->db->RHC;

    switch ($detail) {
      case ('full') :
        $out1 = [
          'height'    => $this->db->Height ?: null,
          'width'     => $this->db->Width ?: null,
          'depth'     => $this->db->Depth ?: null,
          'desc'      => $this->setDesc(),
          'specs'     => $this->setSpecs(),
          'imgAll'    => $this->setImgs(),
          //this glob targets only the valid RHC reference. ie 'RHC10', 'RHC10 b', NOT 'RHC101'
          'category'  => $this->db->Category,
          'hasVideo' => $this->db->videoLink != "0" ? $this->db->videoLink : null
        ];
      case ('tile') :
        $w = isset($this->db->Width) ? $this->db->Width : 0;
        $out2 = [

          'widthFt'  => $this->MMtoFeet($w,$justFeet = true),
          'icon'     => $this->setIcon(),
          'info'     => $this->setInfo(),
          'quantity' => $this->db->Quantity > 1 ? $this->db->Quantity." in Stock" : null,
          'ss'       => $this->ss
        ];
      case ('lite') :
        $out3 = [
          'price'    => $this->setPrice(),
          'webLink'  => $this->setWebLink(),
          'rhc'      => $this->rhc.$this->ref,
          'name'     => $this->db->ProductName,
          'imgFirst' => 'images/gallery/'.$this->rhc.$this->ref.'.jpg',
        ];
        break;
      default:
        $out1 = ['error' => 'invalid term "'.$detail.'"'];
        break;
    }
    return array_merge($out1,$out2,$out3);
  }

  private function MMtoFeet($mm,$justFeet = false) {
    if ($mm > 0) {

      $out = !$justFeet ? $mm.'mm / ' : null;
      $justInches = $mm / 25.4;
      if ($justInches < 24) {
        $out .= ceil($justInches).'in';
      } else {
        $feet = floor($justInches / 12);
        $inches = $justInches % 12;
        $out .= $feet."ft ";
        $out .= $inches > 0 ? $inches.'in' : null;
      }
    } else {
      $out = null;
    }
    return $out;
  }

  private function setBrand() {
    $brand = isset($this->db->Brand) ? $this->db->Brand : null;
    if ($brand) {
      $brandUrl = sanitize_title($brand);
      $brandImg = jr_siteImg('brands/long/'.$brandUrl.'-logo.jpg', $relative = true);
      if (file_exists(ABSPATH.$brandImg)) {
        $brandText = '<img class="framed" src="'.site_url($brandImg).'" alt="'.$brand.'" >'
          .'<a href="'.home_url('products/brand/'.$brandUrl).'" >More from '.$brand.'</a>';
      } else {
        $brandText = $brand.' (<a href="'.home_url('products/brand/'.$brandUrl).'" >More</a>)';
      }
    } else {
      $brandText = null;
    };
    return $brandText;
  }

  private function setPower() {
    $watt = isset($this->db->Wattage) ? $this->db->Wattage : null;
    $pwr = isset($this->db->Power) ? $this->db->Power : null;
    if ($watt >= 1500) {
      $pwrCheck = ($watt / 1000).'kw, '.$pwr;
    } elseif ($watt < 1500 && $watt > 0) {
      $pwrCheck = $watt.' watts, '.$pwr;
    } elseif ($pwr) {
      $pwrCheck = $pwr;
    } else {
      $pwrCheck = null;
    };
    return $pwrCheck;
  }

  private function setSpecs() {
    $h = $this->db->Height;
    $w = $this->db->Width;
    $d = $this->db->Depth;
    $out = [
      'Brand'   => $this->setBrand() ?:null,
      'Model'   => isset($this->db->Model) ? $this->db->Model : null,
      'Power'   => $this->setPower() ?: null,
      'Height'  => $this->MMtoFeet($h),
      'Width'   => $this->MMtoFeet($w),
      'Depth'   => $this->MMtoFeet($d),
    ];
    if (isset($this->db->ExtraMeasurements)) {
      $extras = explode(';',$this->db->ExtraMeasurements);
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
    if (!$this->ss && $this->db->{'Condition/Damages'} != "0") {
      $out['Please Note'] = $this->db->{'Condition/Damages'};
    }
    return array_filter($out);
  }

/*
for some reason I cant figure out, the glob location on live server differs from the version on local server.
though every other link else seems to work fine. to work around rhc/.
probably some basic thing i've overlooked to do with the _SERVER global
*/

  private function setImgs() {
    if ($this->ref > 0) {

      $out1 = glob('images/gallery/'.$this->rhc.$this->ref.'[!0-9]*', GLOB_BRACE);
      $out2 = glob('rhc/images/gallery/'.$this->rhc.$this->ref.'[!0-9]*', GLOB_BRACE);
      $out = array_merge($out1,$out2);
    } else {
      $out = null;
    }
    return $out;
  }

  private function setIcon() {
    $iconCheck = null;

    if (!$this->ss) {
      $catArray = [$this->db->Category,  $this->db->Cat1,  $this->db->Cat2, $this->db->Cat3 ];
      if ($this->db->isDomestic) {
        $iconCheck = 'domestic';
      } elseif (in_array('Fridges', $catArray) && in_array('Freezers', $catArray)) {
        $iconCheck = 'fridge-freezer';
      } elseif (in_array('Fridges', $catArray)) {
        $iconCheck = 'fridge';
      } elseif (in_array('Freezers', $catArray)) {
        $iconCheck = 'freezer';
      } elseif ($this->db->Power) {
        $iconCheck = str_replace(' ', '-', strtolower($this->db->Power));
      }
    }
    return $iconCheck;
  }

  private function setInfo() {
    if ($this->db->Quantity == 0) {
      $infoCheck = "sold";
    } elseif ($this->db->flagItem == 'reserved') {
      $infoCheck = "reserved";
    } elseif ($this->db->flagItem == 'sale') {
      $infoCheck = "sale";
    } elseif ($this->db->isNew) {
      $infoCheck = "new";
    } else {
      $infoCheck = null;
    };
    return $infoCheck;
  }

  private function setPrice() {
    if ($this->db->Quantity == 0) {
      $priceCheck = 'Sold';
    } elseif ($this->db->SalePrice > 0) {
      $priceCheck = '<span class="old-price" >£'.$this->db->Price.'</span><br> £'.$this->db->SalePrice.' + VAT';
    } elseif ($this->db->Price) {
      $priceCheck = "£{$this->db->Price} + VAT";
    } else {
      $priceCheck = "Price Coming Soon";
    };
    return $priceCheck;
  }

  private function setWebLink() {
    if ($this->ss) {
      $url = 'rhcs/';
    } else {
      $url = 'rhc/';
    }
    return $url.$this->ref.'/'.sanitize_title($this->db->ProductName);
  }

  private function setDesc() {
    $parsedown = new Parsedown();
    if ($this->ss) {
      $out = $this->db->Line1 != "0" ? $this->db->Line1 : null;
    } else {
      $out = ($this->db->{'Line 1'} != "0" ? $this->db->{'Line 1'} : null).
             ($this->db->{'Line 2'} != "0" ? "\n\r".$this->db->{'Line 2'} : null).
             ($this->db->{'Line 3'} != "0" ? "\n\r".$this->db->{'Line 3'} : null);
    }
    return $parsedown
      ->setBreaksEnabled(true)
      ->text($out);
  }

};
  ?>
