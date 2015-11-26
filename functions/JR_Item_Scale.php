<?php

// ---------------------- box scaler ------------------------------------------------------
/**
 * Generates relative sizes of HxW for the items page. also "average man" to scale
 * @param  array $item compiled product data
 * @return array relative dimensions and correct image
 */
function jr_boxGen($item) {

  //size of the svg, 500x500units square with 10units padding
  $boxDims =     480;
  $boxPadding =   10;
  $tableHeight = 800; // a generic worktop
  $tableWidth = 1200; //... that is 1.2m long
  $manHeight =  1750; // average male
  $manWidth =    875; // the man image will be half height
  $shortH =      550; //stuff on tables
  $tallH =      1500; //the tallest things

  $bottomPoint =  $boxPadding + $boxDims; //used for most of the X/Y Coordinates
  $itemH =        $item->Height;
  $itemW =        $item->Width;
  $findMax = max($itemH, $itemW, $manHeight);//the largest dimension sets the scale
  // dimensions of the svg rectangles
  $out1 = [
    'itemH'   => round($itemH / $findMax * $boxDims, 3),
    'itemW'   => round($itemW / $findMax * $boxDims, 3),
    'manH'    => round($manHeight / $findMax * $boxDims, 3),
    'manW'    => round($manWidth / $findMax * $boxDims, 3),
    'manX'   => 0,
    'tableH'  => round($tableHeight / $findMax * $boxDims, 3),
    'tableW'  => round($tableWidth / $findMax * $boxDims, 3),
  ];
  //shortest items "propped up" on a table. unless it IS a table
  if ($itemH < $shortH && !$item->ss) {

    $out2 = [
      'itemX'  => ($boxDims - $out1['itemW']) / 2,
      'itemY'  => $bottomPoint - $out1['itemH'] - $out1['tableH'],
      'tableY' => $bottomPoint - $out1['tableH'],
      'tableX' => ($boxDims - $out1['tableW']) / 2,
      'manY'   => $boxPadding,
    ];

  } else {

    $out2 = [
      'itemX' => ($boxDims - $out1['itemW']) / 2,
      'itemY' => $bottomPoint - $out1['itemH'],
      'manY'  => $bottomPoint - $out1['manH'],
    ];

  }
 //set the image based on the size, and if its stainless steel
  if ($itemH < $shortH) {

    $out3 = ['itemImg' => 'appliance-short','tableImg' => 'appliance-table'];

  } elseif ($itemH > $tallH) {

    $out3 = ['itemImg' => ($item->ss) ? 'appliance-table-tall' : 'appliance-tall'];

  } elseif ($item->ss) {

    $out3 = ['itemImg' => ($item->Category == 'Sinks') ? 'appliance-sink' : 'appliance-table'];

  } else {

    $out3 = ['itemImg' => 'appliance-med'];

  }

  //adding line measurements. several are the same as the actual box
  $outLines = [
    'heightLineX' =>  $boxDims - $out2['itemX'] + 10, //right sided
    'widthLineY' =>  $out2['itemY'] - 10 //top sided
  ];
  $out4 = [
    'pathA' =>        'M'.$outLines['heightLineX'].' '.$out2['itemY'].'v'.$out1['itemH'],
    'pathB' =>        'M'.$out2['itemX'].' '.$outLines['widthLineY'].'h'.$out1['itemW'],
    'heightText'  =>  $item->Height.'mm',
    'heightTextX' =>  $outLines['heightLineX'] + 3,
    'heightTextY' =>  $out2['itemY'] + ($out1['itemH']/2),
    'widthText'   =>  $item->Width.'mm',
    'widthTextX'  =>  $out2['itemX'] + ($out1['itemW']/2),
    'widthTextY'  =>  $outLines['widthLineY'] - 3
  ];

  $out = array_merge($out1, $out2, $out3, $out4);

  return $out;
}

?>



