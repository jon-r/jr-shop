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
  // categories taken from DB cache
  $getCats = jrCached_Categories_Full();

  foreach ($getCats as $cat) {
    $findCat[] = '[category:'.$cat['Name'].']';
    $replaceCat[] = '<a href="'.site_url('products/'.$cat['RefName']).'" >'.$cat['Name'].'</a>';
  }
  $out = preg_replace($findBasic,$replaceBasic,$in);
  $out = str_ireplace($findCat,$replaceCat, $out);
  return $out;
}