<?php
//output functions
//this is where the shop database is processed into content
//list of brands from what we have pictures of
function jr_featuredBrands() {
  $jrGetBrands = jrQ_brandUnique();
  foreach ($jrGetBrands as $brand) {
    $url = sanitize_title($brand);
    $img = jr_siteImg('brands/square/'.$url.'.jpg');
    if (file_exists($img)) {
      $out[$brand] = [
        'Name'    => $brand,
        'RefName' => $url
      ];
    }
  }
  return $out;
}
// ---------------------- carousel compiler --------------------------------------
// converts the database carousel to a web one
// also takes the carousel "link" and converts it to a sale if it is just a number.
// else treats it like a link
function jr_positionCarousel($in) {
  if ($in == "Middle") {
    $out = "go-mid";
  } elseif ($in == "Left") {
    $out = "go-left";
  } elseif ($in == "Right") {
    $out = "go-right";
  }
  return $out;
}
function jr_styleCarousel($in) {
  if ($in == "Bold") {
    $out = "go-bold";
    } elseif ($in == "White") {
    $out = "go-white";
  } elseif ($in == "Bold_White") {
    $out = "go-bold go-white";
  } elseif ($in == "Red") {
    $out = "go-red";
  } elseif ($in == "Bold_Red") {
    $out = "go-bold go-red";
  } else {
    $out = null;
  }
  return $out;
}
//because descriptive function names are too mainstream
function jr_magicRoundabout($slideIn) {
  $out = [
    title     => $slideIn[Title],
    titlePos  => jr_positionCarousel($slideIn[TitlePos]),
    text1     => $slideIn[Description] != "0" ? $slideIn[Description] : null,
    text2     => $slideIn[Desc2] != "0" ? $slideIn[Desc2] : null,
    text3     => $slideIn[Desc3] != "0" ? $slideIn[Desc3] : null,
    textPos   => jr_positionCarousel($slideIn[TextPos]),
    style1    => jr_styleCarousel($slideIn[Desc1Emphasis]),
    style2    => jr_styleCarousel($slideIn[Desc2Emphasis]),
    style3    => jr_styleCarousel($slideIn[Desc3Emphasis]),
    image     => jr_siteImg('carousel/'.$slideIn[ImageRef].'.jpg'),
    link      => is_numeric($slideIn[WebLink]) ? "?page_id=16&sale=$slideIn[WebLink]" : $slideIn[WebLink],
    linkPos   => jr_positionCarousel($slideIn[ClickHerePos]),
    linkCol   => jr_styleCarousel($slideIn[ClickHereColour])
  ];
  return $out;
}
// ---------------------- items list setup ----------------------------------------------
// figures out what to show on output page, based on safeArr and the page number
function jr_itemsList($safeArr,$pageNumber) {
  global $itemCountMax;
  //the full list query will always be the same, since this function is preset to cap at one page
  $listUnsold = jrQ_items($safeArr, $pageNumber);
  $out['paginate'] = false;
  $lastPage = 1;

  if ($safeArr['pgType'] != 'New' && $safeArr['pgType'] != 'Sold') {
    //the "sold" and "new" already capped at a single page, no need to count
    $fullItemCount = jrQ_itemsCount($safeArr);
    //breaks down into pages
    if ($fullItemCount > $itemCountMax) {
      $out['paginate'] = $lastPage = intval(ceil($fullItemCount / $itemCountMax));
    }
    //fills up the last page with sold items
    if ($pageNumber == $lastPage && $safeArr['pgType'] != 'Soon') {
      $itemsOnLastPage = $fullItemCount % $itemCountMax;
      $listSold = jrQ_itemsSold($safeArr, $itemsOnLastPage);
    }
  }
  $out['list'] = $listSold ? array_merge($listUnsold, $listSold) : $listUnsold;
  return $out;
}
// ----------------------array compiler--------------------------------------------------
// Converts the raw querys into useful blocks of text
function jr_itemComplile($ref,$detail) {
  $out1 = $out2 = [];
  switch ($detail) {
  case 'itemSS' :
    $out1 = [
      height      => $ref[Height] ?: null,
      width       => $ref[Width] ?: null,
      depth       => $ref[Depth] ?: null,
      hFull       => $ref[Height] ? "Height: ".$ref[Height]."mm / ".ceil($ref[Height] / 25.4)." inches" : null,
      wFull       => $ref[Width] ? "Width: ".$ref[Width]."mm / ".ceil($ref[Width] / 25.4)." inches" : null,
      dFull       => $ref[Depth] ? "Depth: ".$ref[Depth]."mm / ".ceil($ref[Depth] / 25.4)." inches" : null,
      desc        => ($ref['Line1'] != " " ? $ref['Line 1']."<br>" : null),
      imgAll      => glob('images/gallery/RHCs'.$ref[RHCs].'*')
    ];

  case 'listSS':
    if ($ref[Quantity] == 0) {
      $priceCheck = 'Sold';
    } elseif ($ref[Price]) {
      $priceCheck = "£".$ref[Price]." + VAT";
    } else {
      $priceCheck = "Price Coming Soon";
    }
    $out2 = [
      webLink     => "rhcs/$ref[RHCs]/".sanitize_title($ref[ProductName]),
      rhc         => "Ref: RHCs".$ref[RHCs],
      name        => $ref[ProductName],
      imgFirst    => jr_siteImg('gallery/RHCs'.$ref[RHCs].'.jpg'),
      price       => $priceCheck ,
      width       => "$ref[TableinFeet]ft",
      quantity    => $ref[Quantity] > 1 ? $ref[Quantity]." in Stock" : null,
      info        => $ref[Quantity] == 0 ? sold : null
    ];
    break;

  case 'item':
    if ($ref[Brand]) {
      $brandUrl = sanitize_title($ref[Brand]);
      $brandIconLocation = jr_siteImg('brands/long/'.$brandUrl.'-logo.jpg');
      $brandName = file_exists($brandIconLocation) ?
        '<img src="'.site_url($brandIconLocation).'" alt="'.$ref[Brand].'" >' : '<b>Brand: </b>'.$ref[Brand].'<br>';
      $brandLink = '<a href="'.site_url('brand/'.$brandUrl).'" >More from '.$ref[Brand].'</a>';
    } else {
      $brandName = null;
      $brandLink = null;
    };
    if ($ref[Wattage] >= 1500) {
      $pwrCheck = "<b>Power:</b> ".($ref[Wattage] / 1000)."kw, ".$ref[Power];
    } elseif ($ref[Wattage] < 1500 && $ref[Wattage] > 0) {
      $pwrCheck = "<b>Power:</b> ".$ref[Wattage]." watts, ".$ref[Power];
    } elseif ($ref[Power]) {
      $pwrCheck = "<b>Power:</b> ".$ref[Power];
    } else {
      $pwrCheck = null;
    }
    $out1 = [
      height      => $ref[Height] ?: null,
      width       => $ref[Width] ?: null,
      depth       => $ref[Depth] ?: null,
      hFull       => $ref[Height] ? "<b>Height</b>: ".$ref[Height]."mm / ".ceil($ref[Height] / 25.4)." inches" : null,
      wFull       => $ref[Width] ? "<b>Width:</b> ".$ref[Width]."mm / ".ceil($ref[Width] / 25.4)." inches" : null,
      dFull       => $ref[Depth] ? "<b>Depth:</b> ".$ref[Depth]."mm / ".ceil($ref[Depth] / 25.4)." inches" : null,
      desc        => ($ref['Line 1'] != "0" ? $ref['Line 1']." " : null).
                        ($ref['Line 2'] != "0" ? $ref['Line 2']." " : null).
                        ($ref['Line 3'] != "0" ? $ref['Line 3'] : null),
      model       => $ref[Model] ? "<b>Model:</b> ".$ref[Model] : null,
      extra       => $ref[ExtraMeasurements] != "0" ? "<b>Extra Measurements:</b> ".$ref[ExtraMeasurements] : null,
      condition   => $ref[Condition] != "0" ? "<b>Condition:</b> ".$ref['Condition/Damages'] : null,
      brandName   => $brandName,
      brandLink   => $brandLink,
      power       => $pwrCheck,
      imgAll      => glob('images/gallery/RHC'.$ref[RHC].'*'),
      category    => $ref[Category]
    ];

  case 'list':
    if ($ref[Quantity] == 0) {
      $priceCheck = '- Sold -';
    } elseif ($ref[Price]) {
      $priceCheck = "£".$ref[Price]." + VAT";
    } else {
      $priceCheck = "Price Coming Soon";
    }
    $catArray = [ $ref[Category],  $ref[Cat1],  $ref[Cat2], $ref[Cat3] ];
    if (in_array('Fridges', $catArray) && in_array('Freezers', $catArray)) {
      $iconCheck = 'fridge-freezer';
    } elseif (in_array('Fridges', $catArray)) {
      $iconCheck = 'fridge';
    } elseif (in_array('Freezers', $catArray)) {
      $iconCheck = 'freezer';
    } elseif ($ref[Power]) {
      $iconCheck = str_replace(' ', '-', strtolower($ref[Power]));
    };
    if ($ref[IsSoon]) {
      $infoCheck = "soon";
    } elseif ($ref[isSale]) {
      $infoCheck = "sale";
    } elseif ($ref[Quantity] == 0) {
      $infoCheck = "sold";
    } elseif (in_array($ref[RHC], jrQ_itemsNew())) {
      $infoCheck = "new";
    }
    $out2 = [
      icon        => $iconCheck,
      price       => $priceCheck ,
      webLink     => "rhc/$ref[RHC]/".sanitize_title($ref[ProductName]),
      rhc         => "ref: RHC$ref[RHC]",
      name        => $ref[ProductName],
      imgFirst    => jr_siteImg('gallery/RHC'.$ref[RHC].'.jpg'),
      info        => $infoCheck,
      quantity    => $ref[Quantity] > 1 ? $ref[Quantity]." in Stock" : null,
      category    => $ref[Category]
    ];
  break;
  };
  $out = array_merge ($out1,$out2);
  return $out;
};
//-------------- pick testimonial -----------------------------------------------
// grabs a single testimonial at random
function jr_randomFeedback() {
  $in = jrQ_tesimonial();
  $countIn = count($in) - 1;
  $random = rand(0,$countIn);
  return $in[$random];
}
// ---------------------- box scaler ------------------------------------------------------
// gives relative sizes of HxW for items page. also "average man" to scale
function jr_boxGen($item) {
  //size of the svg, 500x500units square with 10units padding
  $boxDims =     480;
  $boxPadding =   10;
  $tableHeight = 800; // a generic worktop
  $tableWidth = 1200; //... that is 1.2m wide
  $manHeight =  1750; // average male
  $manWidth =    875; // the image will be half height
  $shortH =      550; //stuff on tables
  $tallH =      1500; //the tallest things
  $bottomPoint =  $boxPadding + $boxDims; //used for most of the X/Y Coordinates
  $itemH =        $item['Height'];
  $itemW =        $item['Width'];
  $findMax = max($itemH, $itemW, $manHeight);//the largest dimension sets the scale
  // dimensions of the svg rectangles
  $out1 = [
    itemH   => round($itemH / $findMax * $boxDims, 3),
    itemW   => round($itemW / $findMax * $boxDims, 3),
    manH    => round($manHeight / $findMax * $boxDims, 3),
    manW    => round($manWidth / $findMax * $boxDims, 3),
    manX   => 0,
    tableH  => round($tableHeight / $findMax * $boxDims, 3),
    tableW  => round($tableWidth / $findMax * $boxDims, 3),
  ];
  //shortest items "propped up" on a table. unless it IS a table
  if ($itemH < $shortH && !$item['RHCs']) {
    $out2 = [
      itemX  => ($boxDims - $out1[itemW]) / 2,
      itemY  => $bottomPoint - $out1[itemH] - $out1[tableH],
      tableY => $bottomPoint - $out1[tableH],
      tableX => ($boxDims - $out1[tableW]) / 2,
      manY   => $boxPadding,
    ];
  } else {
    $out2 = [
      itemX => ($boxDims - $out1[itemW]) / 2,
      itemY => $bottomPoint - $out1[itemH],
      manY  => $bottomPoint - $out1[manH],
    ];
  }
 //set the image based on the size, and if its stainless steel
  if ($itemH < $shortH) {
    $out3 = [itemImg => 'appliance-short',tableImg => 'appliance-table'];
  } elseif ($itemH > $tallH) {
    $out3 = [ itemImg => $item['RHCs'] ? 'appliance-table-tall' : 'appliance-tall'];
  } elseif ($item['RHCs']) {
    $out3 = [ itemImg => ($item[Category] == 'Sinks') ? 'appliance-sink' : 'appliance-table'];
  } else {
    $out3 = [ itemImg => 'appliance-med'];
  }
  $out = array_merge($out1, $out2, $out3);
  return $out;
}
?>
