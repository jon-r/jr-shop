<?php

// ----------------------array compiler--------------------------------------------------
// Converts the raw querys into useful blocks of text
function jr_itemComplile($ref,$detail,$newCheck = []) {
  $out1 = $out2 = $out3 = [];
  switch ($detail) {
  case 'itemSS' :
    $out1 = [
      'height'  => $ref['Height'] ?: null,
      'width'   => $ref['Width'] ?: null,
      'depth'   => $ref['Depth'] ?: null,
      'hFull'   => $ref['Height'] ? "<b>Height</b>: ".$ref['Height']."mm / ".jr_MMtoFeet($ref['Height'])."inch" : null,
      'wFull'   => $ref['Width'] ? "<b>Width:</b> ".$ref['Width']."mm / ".jr_MMtoFeet($ref['Width'])."inch" : null,
      'dFull'   => $ref['Depth'] ? "<b>Depth:</b> ".$ref['Depth']."mm / ".jr_MMtoFeet($ref['Depth'])."inch" : null,
      'desc'    => ($ref['Line1'] != "0" ? $ref['Line1']."<br>" : null),
      'imgAll'  => glob('images/gallery/RHCs'.$ref['RHCs'].'*')
    ];

  case 'listSS':
    $out2 = [
      'quantity'  => $ref['Quantity'] > 1 ? $ref['Quantity'].' in Stock' : null,
      'info'      => $ref['Quantity'] == 0 ? 'sold' : null,
      'icon'      => null
    ];
  case 'tinySS':
    if ($ref['Quantity'] == 0) {
      $priceCheck = 'Sold';
    } elseif ($ref['Price']) {
      $priceCheck = "£".$ref['Price']." + VAT";
    } else {
      $priceCheck = "Price Coming Soon";
    };
    $out3 = [
      'widthFt'     => $ref['Width'] ? jr_MMtoFeet($ref['Width']) : null,
      'webLink'   => 'rhcs/'.$ref['RHCs'].'/'.sanitize_title($ref['ProductName']),
      'rhc'       => 'Ref: RHCs'.$ref['RHCs'],
      'name'      => $ref['ProductName'],
      'imgFirst'  => jr_siteImg('gallery/RHCs'.$ref['RHCs'].'.jpg'),
      'price'     => $priceCheck
    ];
    break;

  case 'item':
    if ($ref['Brand']) {
      $brandUrl = sanitize_title($ref['Brand']);
      $brandImg = jr_siteImg('brands/long/'.$brandUrl.'-logo.jpg');
      if (file_exists($brandImg)) {
        $brandText = '<img src="'.site_url($brandImg).'" alt="'.$ref['Brand'].'" >'
          .'<a href="'.site_url('brand/'.$brandUrl).'" >More from '.$ref['Brand'].'</a>';
      } else {
        $brandText = $ref['Brand'].' (<a href="'.site_url('brand/'.$brandUrl).'" >More</a>)';
      }
    } else {
      $brandText = null;
    };
    if ($ref['Wattage'] >= 1500) {
      $pwrCheck = ($ref['Wattage'] / 1000).'kw, '.$ref['Power'];
    } elseif ($ref['Wattage'] < 1500 && $ref['Wattage'] > 0) {
      $pwrCheck = $ref['Wattage'].' watts, '.$ref['Power'];
    } elseif ($ref['Power']) {
      $pwrCheck = $ref['Power'];
    } else {
      $pwrCheck = null;
    };
    $specList = [
      'Brand'   => $brandText,
      'Model'   => $ref['Model'] ?: null,
      'Power'   => $pwrCheck,
      'Height'  => $ref['Height'] ? $ref['Height'].'mm / '.jr_MMtoFeet($ref['Height']).'inch' : null,
      'Width'   => $ref['Width'] ? $ref['Width'].'mm / '.jr_MMtoFeet($ref['Width']).'inch' : null,
      'Depth'   => $ref['Depth'] ? $ref['Depth'].'mm / '.jr_MMtoFeet($ref['Depth']).'inch' : null,
    ];
    if ($ref['ExtraMeasurements']) {
      $extras = explode(';',$ref['ExtraMeasurements']);
      foreach($extras as $extra) {
        $item = explode(':',$extra);
        $specList[trim($item[0])] = $item[1] ? trim($item[1]) : null;
      }
    };

    //put last to keep in order
    $specList['Condition'] = $ref['Condition/Damages'] != "0" ? $ref['Condition/Damages'] : null;

    $out1 = [
      'height'    => $ref['Height'] ?: null,
      'width'     => $ref['Width'] ?: null,
      'depth'     => $ref['Depth'] ?: null,
      'desc'      => ($ref['Line 1'] != "0" ? $ref['Line 1']." " : null).
                     ($ref['Line 2'] != "0" ? $ref['Line 2']." " : null).
                     ($ref['Line 3'] != "0" ? $ref['Line 3'] : null),
      'specs'     => $specList,
      'imgAll'    => glob('images/gallery/RHC'.$ref['RHC'].'*'),
      'category'  => $ref['Category']
    ];

  case 'list':
    $catArray = [ $ref['Category'],  $ref['Cat1'],  $ref['Cat2'], $ref['Cat3'] ];
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
    if ($ref['IsSoon']) {
      $infoCheck = "soon";
    } elseif (isset($ref['isSale'])) {
      $infoCheck = "sale";
    } elseif ($ref['Quantity'] == 0) {
      $infoCheck = "sold";
    } elseif (in_array($ref['RHC'], $newCheck)) {
      $infoCheck = "new";
    } else {
      $infoCheck = null;
    };
    $out2 = [
      'icon'     => $iconCheck,
      'info'     => $infoCheck,
      'quantity' => $ref['Quantity'] > 1 ? $ref['Quantity']." in Stock" : null,
      'category' => $ref['Category']
    ];
  case 'tiny' :
    if ($ref['Quantity'] == 0) {
      $priceCheck = '- Sold -';
    } elseif ($ref['Price']) {
      $priceCheck = "£".$ref['Price']." + VAT";
    } else {
      $priceCheck = "Price Coming Soon";
    };
    $out3 = [
      'price'    => $priceCheck ,
      'webLink'  => 'rhc/'.$ref['RHC'].'/'.sanitize_title($ref['ProductName']),
      'rhc'      => 'ref: RHC'.$ref['RHC'],
      'name'     => $ref['ProductName'],
      'imgFirst' => jr_siteImg('gallery/RHC'.$ref['RHC'].'.jpg'),
    ];
  break;
  };
  $out = array_merge ($out1,$out2,$out3);
  return $out;
};

function jr_MMtoFeet($mm) {
  $justInches = $mm / 25.4;
  if ($justInches < 24) {
    $out = ceil($justInches);
  } else {
    $feet = floor($justInches / 12);
    $inches = $justInches % 12;
    $out = "${feet}ft $inches";
  }

  return $out;
}
?>



