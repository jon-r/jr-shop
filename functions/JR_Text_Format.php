<?php
// --- microformatter -------------------------------------------------------------------
// adds basic formatting, with custom "markdown".
function jr_format($in) {
  // basic formatting replaces the 'easier' bits
  $findBasic = [
    '/\[ref:(rhc|rhcs)(\d+)\]/i',
    '/\[link@([^\]]+):([^\]]+)\]/i',
    '/\[italic:([^\]]+)\]/i',
    '/\[bold:([^\]]+)\]/i',
    '/\[red:([^\]]+)\]/i',
    '/\[tel\]/i',
    '/\[email\]/i',
  ];
  $replaceBasic = [
    '<a href="'.site_url('$1/$2').'" >$1$2</a>',
    '<a href="$1" >$2</a>',
    '<em>$1</em>',
    '<strong>$1</strong>',
    '<em class="greater">$1</em>',
    jr_linkTo('phone'),
    jr_linkTo('eLink')
  ];
  // categories taken from DB
  $getCats = jrCached_Category_Column();

  foreach ($getCats as $cat) {
    $findCat[] = '[category:'.$cat.']';
    $replaceCat[] = '<a href="'.site_url('products/'.sanitize_title($cat)).'" >'.$cat.'</a>';
  }
  $out = preg_replace($findBasic,$replaceBasic,$in);
  $out = str_ireplace($findCat,$replaceCat, $out);
  return $out;
}
