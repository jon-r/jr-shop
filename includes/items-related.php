<?php /* Related Items list
* just 4 randomly selected items, matching the primary category page
*/
$related = jrQ_iremsRelated($jr_safeArray);
?>
<article class="flex-container">

  <header class="article-header flex-1">
    <h1>More <?php echo $jr_safeArray['cat']; ?></h1>
  </header>

  <?php
  foreach ($related as $item) {
    include( "list-item.php");
  }
  ?>

</article>
