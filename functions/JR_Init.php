<?php
/* this is the first point of action. gets the baseline variables used on every page.
this is the stuff on everypage, so can be called straight away
*/

global $jr_safeArr,$jr_getGroup, $jr_getCategory, $jr_groupArray;

$getGroup = jrQ_keywords('group');
$getCategory = jrQ_categories();

foreach ($getGroup as $grp) {
  $groupArray[$grp] = jr_groupFilter($grp);
}

//now to clean the parameter input
$jr_safeArr = jr_validate_urls(jr_getUrl());



?>

