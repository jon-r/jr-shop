<article class="flex-container four-oh-four" >

  <div class="flex-2" >
    <img src="<?php echo site_url('/').jr_imgSrc('rhc','four-oh-four','jpg'); ?>" alt="four-oh-four">
  </div>

  <header class="article-header flex-1">
    <h1>Something Went Wrong!</h1>
    <p>We can't find what you're looking for. Maybe try searching for something else!</p>
    <?php echo $safeArr[imgURL] ?>
  </header>


  <?php echo do_shortcode( "[jr-shop id='search-bar' dark=true]"); ?>


</article>
