<article class="flex-container four-oh-four" >

  <div class="flex-2" >
    <img src="<?php echo site_url('/').imgSrcRoot('rhc','four-oh-four','jpg'); ?>" alt="four-oh-four">
  </div>

  <header class="article-header flex-1">
    <h1>Something Went Wrong!</h1>
    <p>We can't find what you're looking for. Maybe try searching for something else!</p>
    <?php echo $safeArr[imgURL] ?>
  </header>



  <form class="flex-2 form-central flex-container" method="get" action="<?php echo site_url('search'); ?>">
    <h2 class="text-icon-right search-w">Search Catering Equipment</h2>

    <input type="search" name="search" placeholder="Enter Keyword or Reference">

    <button class="btn-red" type="submit">
      <h3>Go</h3>
    </button>
  </form>

</article>
