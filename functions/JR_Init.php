<?php
/* this is the first point of action. gets the baseline variables used on every page.
this is the stuff on everypage, so can be called straight away
*/

global $jr_safeArr,$jr_getGroup, $jr_getCategory, $jr_groupArray;

$jr_getGroup = jrQ_keywords('group');
$jr_getCategory = jrQ_categories();

foreach ($jr_getGroup as $grp) {
  $jr_groupArray[$grp] = jr_groupFilter($grp);
}

//now to clean the parameter input
$jr_safeArray = jr_validate_urls(jr_getUrl());



?>

