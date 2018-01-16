<div class="nav-breadcrumbs" >
<?php
$breadLinks = jr_pageCrumbles ();

foreach ($breadLinks as $breadSlices) {
  foreach ($breadSlices as $name => $link) {
    echo $link ? '<a class="text-icon arrow-r" href="'.$link.'" ><em>'.$name."</em></a>" : null;
  }
}
?>
</div>
<?php //global $jr_page; var_dump($jr_page)?>

<?php //hooking the shopping cart here. for now anyway
 // include('shop-cart.php');
?>
