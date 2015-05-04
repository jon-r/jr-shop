<?php
//output functions
//this is where the shop database is processed into content

//returns if item in group. one level deeper than the normal IN_ARRAY
function jr_isGroup($group) {
  return function ($category) use ($group) {
    return ($category[CategoryGroup] == $group);
  };
}

function jr_groupFilter($group) {
  global $jr_getCategory;
  return array_filter ($jr_getCategory, jr_isGroup($group));
}

//list of major brands, from keywords_db;
function jr_brandsList() {
  $getKeyBrands = jrQ_keywords('brand');

  $out = array_map('jr_brandArrayGen', $getKeyBrands);

  return $out;
}

function jr_brandArrayGen($brand) {
  $out[Name] = $brand;
  $out[RefName] = sanitize_title($brand);
  return $out;
}

//-- readable titles --------------------------------------------------------------------
function jr_urlToTitle($url,$type) {
  global $jr_getGroup;
  $out = "Not Found";
  $getCategoryColumn = jrQ_categoryColumn();
  if ($type == 'cat') {
    $catUrls = array_map('sanitize_title', $getCategoryColumn);
    if (in_array($url,$catUrls)) {
      $cats = array_combine($getCategoryColumn, $catUrls);
      $out = array_search($url, $cats);
    }
  } elseif ($type == 'grp') {
    $grpUrls = array_map('sanitize_title', $jr_getGroup);
    if (in_array($url,$grpUrls)) {
      $grps = array_combine($jr_getGroup, $grpUrls);
      $out = array_search($url, $grps);
    }
  } elseif ($type == 'brand') {
    $getBrands = jrQ_brandUnique();
    $brandUrls = array_map('sanitize_title', $getBrands);

    if (in_array($url,$brandUrls)) {
      $brands = array_combine($getBrands, $brandUrls);
      $out = array_search($url, $brands);
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
    image     => jr_imgSrc(carousel,$slideIn[ImageRef],jpg),
    link      => is_numeric($slideIn[WebLink]) ? "?page_id=16&sale=$slideIn[WebLink]" : $slideIn[WebLink],
    linkPos   => jr_styleCarousel($slideIn[ClickHerePos])
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

 // $out['debug'] = $fullItemCount;

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
        imgAll      => glob('images/gallery/'.$ref[Image].'*')
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
        imgFirst    => jr_imgSrc('gallery',$ref[Image],'jpg'),
        price       => $priceCheck ,
        width       => "$ref[TableinFeet]ft",
        quantity    => $ref[Quantity] > 1 ? $ref[Quantity]." in Stock" : null,
        info        => $ref[Quantity] == 0 ? sold : null
      ];
    break;
    case 'item':
      if ($ref[Brand]) {
        $brandUrl = sanitize_title($ref[Brand]);
        $brandIconLocation = jr_imgSrc('brands',$brandUrl,'jpg');
        $brand = $ref[Brand];
      };
      if ($ref[Wattage] >= 1500) {
        $wattCheck = ($ref[Wattage] / 1000)."kw";
      } elseif ($ref[Wattage] < 1500 && $ref[Wattage] > 0) {
        $wattCheck = $ref[Wattage]." watts";
      } else {
        $wattCheck = null;
      }
      $out1 = [
        height      => $ref[Height] ?: null,
        width       => $ref[Width] ?: null,
        depth       => $ref[Depth] ?: null,
        hFull       => $ref[Height] ? "Height: ".$ref[Height]."mm / ".ceil($ref[Height] / 25.4)." inches" : null,
        wFull       => $ref[Width] ? "Width: ".$ref[Width]."mm / ".ceil($ref[Width] / 25.4)." inches" : null,
        dFull       => $ref[Depth] ? "Depth: ".$ref[Depth]."mm / ".ceil($ref[Depth] / 25.4)." inches" : null,
        desc        => ($ref['Line 1'] != " " ? $ref['Line 1']."<br>" : null).
                          ($ref['Line 2'] != " " ? $ref['Line 2']."<br>" : null).
                          ($ref['Line 3'] != " " ? $ref['Line 3'] : null),
        model       => $ref[Model] ? "Model: ".$ref[Model] : null,
        extra       => $ref[ExtraMeasurements],
        condition   => $ref[Condition] != " " ? $ref[Condition] : null,
        brand       => $brand ?: null,
        brandImg    => file_exists ($brandIconLocation) ? '<img src="'.$brandIconLocation.'" alt="'.$brand.'" >' : null,
        brandLink   => "brand/$brandUrl",
        watt        => $wattCheck,
        imgAll      => glob('images/gallery/'.$ref[Image].'*'),
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
      $catArray = [ $ref[Category], $ref[cat1], $ref[cat2], $ref[cat3] ];
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
        imgFirst    => jr_imgSrc('gallery',$ref[Image],'jpg'),
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

?>
