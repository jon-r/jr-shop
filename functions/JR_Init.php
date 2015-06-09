<?php
/* this is the first point of action. gets the baseline variables used on every page.
this stuff is on everypage (in the main menu at very least), so called straight away.
*/
global $jr_safeArr,$jr_getGroup, $jr_groupArray;
$jr_getGroup = jrQ_keywords('group');
$jr_groupArray = jr_categoryFilter();

function jr_categoryFilter() {
  $getCategory = jrQ_categories();
  foreach ($getCategory as $c) {
    $out[$c['CategoryGroup']][] =  $c;
  }
  return $out;
}
//now to clean the parameter input.
$jr_safeArray = jr_validate_urls(jr_getUrl());
?>

