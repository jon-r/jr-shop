<?php

// ---------------------- carousel compiler --------------------------------------

/**
 * Compiles the database carousel, formats it for the front page. because descriptive function names are too mainstream
 * @param  array $in queried row from the database.
 * @return array formatted parts of the carousel
 */
function jr_magicRoundabout($slideIn) {

  $out = [
    'title'       => $slideIn['Title'],
    'titlePos'    => jr_positionCarousel($slideIn['TitlePos']),
    'titleColor'  => jr_colorCarousel($slideIn['title_color']),
    'titleShow'   => $slideIn['title_show'],
    'titleBold'   => $slideIn['title_bold'] ? 'go-bold' : null,

    'text1'       => $slideIn['Description'] != "0" ? $slideIn['Description'] : null,
    'text1Pos'    => null,
    'text1Color'  => jr_colorCarousel($slideIn['desc1_color']),
    'text1Show'   => $slideIn['desc1_show'],
    'text1Bold'   => $slideIn['desc1_bold'] ? 'go-bold' : null,

    'text2'       => $slideIn['Desc2'] != "0" ? $slideIn['Desc2'] : null,
    'text2Pos'    => null,
    'text2Color'  => jr_colorCarousel($slideIn['desc2_color']),
    'text2Show'   => $slideIn['desc2_show'],
    'text2Bold'   => $slideIn['desc2_bold'] ? 'go-bold' : null,

    'text3'       => $slideIn['Desc3'] != "0" ? $slideIn['Desc3'] : null,
    'text3Pos'    => null,
    'text3Color'  => jr_colorCarousel($slideIn['desc3_color']),
    'text3Show'   => $slideIn['desc3_show'],
    'text3Bold'   => $slideIn['desc3_bold'] ? 'go-bold' : null,

    'link'        => 'Click Here',
    'linkPos'     => jr_positionCarousel($slideIn['ClickHerePos']),
    'linkColor'   => jr_colorCarousel($slideIn['link_color']),
    'linkShow'    => $slideIn['link_show'],
    'linkBold'    => $slideIn['link_bold'] ? 'go-bold' : null,

    'weblink'        => $slideIn['WebLink'] != "0" ? $slideIn['WebLink'] : null,
    'textPos'     => jr_positionCarousel($slideIn['TextPos']),
    'image'       => jr_siteImg('gallery/'.$slideIn['ImageRef'].'.jpg'),
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
 * validates the text color of the carousel element
 * @param  string $in style value from table
 * @return string hex color result
 */
function jr_colorCarousel($in) {

  if (ctype_xdigit($in) && strlen($in) == 6) {
    $out = $in;
  } else {
    $out = "000";
  }

  return $out;
}


function jr_carouselStr($slide, $target, $el, $className) {
  if ($slide["{$target}Show"]) {
    $strFormat = '<%1$s class="%2$s" style="color:#%3$s" >%4$s</%1$s>';
    $class = join(' ', [$className, $slide["{$target}Bold"], $slide["{$target}Pos"]]);
    $color = $slide["{$target}Color"];
    $value = $slide[$target];

    $out = sprintf($strFormat, $el, $class, $color, $value);

  } else {
    $out = null;
  }

  return $out;
}

?>
