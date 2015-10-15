<?php

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
    'title'   => $slideIn['Title'],
    'titleCol'=> jr_styleCarousel($slideIn['TitleColour']),
    'titlePos'=> jr_positionCarousel($slideIn['TitlePos']),
    'text1'   => $slideIn['Description'] != "0" ? $slideIn['Description'] : null,
    'text2'   => $slideIn['Desc2'] != "0" ? $slideIn['Desc2'] : null,
    'text3'   => $slideIn['Desc3'] != "0" ? $slideIn['Desc3'] : null,
    'textPos' => jr_positionCarousel($slideIn['TextPos']),
    'style1'  => jr_styleCarousel($slideIn['Desc1Emphasis']),
    'style2'  => jr_styleCarousel($slideIn['Desc2Emphasis']),
    'style3'  => jr_styleCarousel($slideIn['Desc3Emphasis']),
    'image'   => jr_siteImg('gallery/'.$slideIn['ImageRef'].'.jpg'),
    'link'    => is_numeric($slideIn['WebLink']) ? '?page_id=16&sale='.$slideIn['WebLink'] : $slideIn['WebLink'],
    'linkPos' => jr_positionCarousel($slideIn['ClickHerePos']),
    'linkCol' => jr_styleCarousel($slideIn['ClickHereColour'])
  ];
  return $out;
}

?>
