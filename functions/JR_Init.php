<?php
/* this is the first point of action. gets the baseline variables used on every page.
this stuff is on everypage (in the main menu at very least), so called straight away.
*/
global $jr_safeArr,$jr_getGroup, $jr_groupArray;
$jr_getGroup = jrCached_Groups();
$jr_groupArray = jrCached_Categories();

//now to clean the parameter input.
$jr_safeArray = jr_validate_urls(jr_getUrl());
?>

