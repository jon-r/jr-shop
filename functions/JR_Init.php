<?php
/* this is the first point of action. gets the baseline validateion/variables used on every page.
this stuff is on everypage (in the main menu at very least), so called straight away.
*/
global $jr_safeArr;
$jr_safeArray = jr_validate_urls(jr_getUrl());
?>

