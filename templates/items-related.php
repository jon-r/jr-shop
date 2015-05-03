<?php /* Related Items list
*
* just 4 randomly selected items, matching the primary category page
*/

$related = jrx_query_related($safeArr);

?>

<article class="flex-container">

  <header class="article-header flex-1">
    <h1>More <?php echo $safeArr[cat]; ?></h1>
  </header>

  <?php
  foreach ($related as $item) {
    include( "list-item.php");
  }
  ?>

</article>
