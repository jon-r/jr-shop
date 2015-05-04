<div class="nav-right nav-breadcrumbs" >
<?php
$breadLinks = jr_pageCrumbles ($jr_safeArray);

foreach ($breadLinks as $breadSlices) {
  foreach ($breadSlices as $name => $link) {
    echo $link ? '<a class="text-icon arrow-r" href="'.$link.'" ><h3>'.$name."</h3></a>" : null;
  }
}
?>
</div>
