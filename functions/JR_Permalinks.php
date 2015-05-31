<?php
/* Setting up url_rewrite names. The ones that arent automagically made by wordpress */
function jr_page($pgtype) {
  $pageNum = [
    'grp' =>  '24',
    'cat' =>  '16',
    'item' => '21',
    'srch' => '30'
  ];
  return $pageNum[$pgtype];
}
function jr_setPermalinks() {
  $permalinks = [
    //depts
    '^brands/?'             => jr_page(grp),
    '^departments/([^/]*)/?' => jr_page(grp),
    //cats
    '^special-offers/?'         => jr_page(cat),
    '^sold/?'                   => jr_page(cat),
    '^coming-soon/?'            => jr_page(cat),
    '^arrivals/?'               => jr_page(cat),
    '^products/([^/]*)/?'       => jr_page(cat),
    '^brand/([^/]*)/?'          => jr_page(cat),
    '^search-results/([^/]*)/?' => jr_page(cat),
    //items
    '^rhc/([^/]*)/?'  => jr_page(item),
    '^rhcs/([^/]*)/?' => jr_page(item)
  ];

  foreach ($permalinks as $find => $replace) {
    add_rewrite_rule($find, 'index.php?page_id='.$replace, 'top');
  }
}
add_action('init', 'jr_setPermalinks');
?>
