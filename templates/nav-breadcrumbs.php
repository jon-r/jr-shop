<div class="nav-right nav-breadcrumbs" >
<?php
$breadLinks = jr_pageCrumbles ($jr_safeArray);

foreach ($breadLinks as $breadSlices) {
  foreach ($breadSlices as $name => $link) {
    echo $link ? '<a class="text-icon arrow-r" href="'.$link.'" ><em class="lesser">'.$name."</em></a>" : null;
  }
}
?>
</div>
