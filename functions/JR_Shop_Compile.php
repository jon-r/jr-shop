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
      $brandIconLocation = jr_siteImg('brands/long/'.$brandUrl.'-logo.jpg');
      $brandName = file_exists($brandIconLocation) ?
        '<img src="'.site_url($brandIconLocation).'" alt="'.$ref['Brand'].'" >' : '<b>Brand: </b>'.$ref['Brand'].'<br>';
      $brandLink = '<a href="'.site_url('brand/'.$brandUrl).'" >More from '.$ref['Brand'].'</a>';
    } else {
      $brandName = null;
      $brandLink = null;
    };
    if ($ref['Wattage'] >= 1500) {
      $pwrCheck = "<b>Power:</b> ".($ref['Wattage'] / 1000)."kw, ".$ref['Power'];
    } elseif ($ref['Wattage'] < 1500 && $ref['Wattage'] > 0) {
      $pwrCheck = "<b>Power:</b> ".$ref['Wattage']." watts, ".$ref['Power'];
    } elseif ($ref['Power']) {
      $pwrCheck = "<b>Power:</b> ".$ref['Power'];
    } else {
      $pwrCheck = null;
    };
    $out1 = [
      'height'    => $ref['Height'] ?: null,
      'width'     => $ref['Width'] ?: null,
      'depth'     => $ref['Depth'] ?: null,
      'hFull'     => $ref['Height'] ? "<b>Height</b>: ".$ref['Height']."mm / ".jr_MMtoFeet($ref['Height'])."inch" : null,
      'wFull'     => $ref['Width'] ? "<b>Width:</b> ".$ref['Width']."mm / ".jr_MMtoFeet($ref['Width'])."inch" : null,
      'dFull'     => $ref['Depth'] ? "<b>Depth:</b> ".$ref['Depth']."mm / ".jr_MMtoFeet($ref['Depth'])."inch" : null,
      'desc'      => ($ref['Line 1'] != "0" ? $ref['Line 1']." " : null).
                      ($ref['Line 2'] != "0" ? $ref['Line 2']." " : null).
                        ($ref['Line 3'] != "0" ? $ref['Line 3'] : null),
      'model'       => $ref['Model'] ? "<b>Model:</b> ".$ref['Model'] : null,
      'extra'       => $ref['ExtraMeasurements'] != "0" ? "<b>Extra Measurements:</b> ".$ref['ExtraMeasurements'] : null,
      'condition'   => $ref['Condition/Damages'] != "0" ? "<b>Condition:</b> ".$ref['Condition/Damages'] : null,
      'brandName'   => $brandName,
      'brandLink'   => $brandLink,
      'power'       => $pwrCheck,
      'imgAll'      => glob('images/gallery/RHC'.$ref['RHC'].'*'),
      'category'    => $ref['Category']
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



