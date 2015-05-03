<article class="default-page flex-container">

    <header class="article-header flex-1">
      <h1><?php echo $safeArr[pgName]; ?></h1>
      <!--      <h2><?php the_title(); ?></h2>-->
      <?php echo $safeArr[imgURL] ?>
    </header>

  <section class=" flex-1">



    <?php the_content(); ?>

  </section>
</article>

