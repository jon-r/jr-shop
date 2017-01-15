
<article class="flex-container">

  <section class="flex-2 form-contact tile-outer dark">
    <header class="tile-header lined">
      <h2>Contact us</h2>
    </header>
    <?php include('form-base.php') ?>
  </section>

  <section class="flex-2 tile-outer dark">
    <header class="tile-header lined">
      <h2>What our customers say</h2>
    </header>

    <blockquote class="testimonials">
      <?php $singleFeedback = jr_randomFeedback(); ?>
      <p>"<?php echo addslashes ($singleFeedback['Testimonial_Short']);?>"</p>
      <h3><?php echo addslashes ($singleFeedback['Name']); ?></h3>
    </blockquote>

    <header class="tile-header lined">
      <h2>Find us online</h2>
    </header>
    <div class="social-links">
      <a class="btn-icon facebook" href="<?php echo jr_linkTo('facebook') ?>">facebook</a>
      <a class="btn-icon gplus" href="<?php echo jr_linkTo('gplus') ?>">google plus</a>
      <a class="btn-icon twitter" href="<?php echo jr_linkTo('twitter') ?>">twitter</a>
    </div>

    <?php if (is_front_page()) : ?>
    <header class="tile-header lined">
      <h2>Stay up to date</h2>
    </header>
    <a class="twitter-timeline"
       data-theme="dark" data-link-color="#ca6868"
       data-chrome="nofooter noheader noborders transparent"
       data-tweet-limit="2" href="https://twitter.com/RHC_Catering">
      Tweets by RHC_Catering</a>
    <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
    <?php endif ?>

  </section>

</article>

