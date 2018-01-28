<?php

// ---------------------- carousel compiler --------------------------------------

/**
 * Compiles the database carousel, formats it for the front page. because descriptive function names are too mainstream
 * @param  array $in queried row from the database.
 * @return array formatted parts of the carousel
 */
function jr_magicRoundabout($slideIn) {

// TEMP FIX hidden titles
  $out = [
    'title'   => '', // $slideIn['Title'],
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
    'link'    => $slideIn['WebLink'] != "0" ? $slideIn['WebLink'] : null,
    'linkPos' => jr_positionCarousel($slideIn['ClickHerePos']),
    'linkCol' => jr_styleCarousel($slideIn['ClickHereColour'])
  ];

  return $out;
}

/**
 * sets the position class of the carousel element
 * @param  string $in position value from table
 * @return string css class result
 */
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

/**
 * sets the text style of the carousel element
 * @param  string $in style value from table
 * @return string css class result
 */
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
?>
