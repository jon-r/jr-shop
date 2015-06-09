<?php // contact form at the bottom of most pages.
  $singleFeedback = jr_randomFeedback();
?>
<article class="contact-bar flex-container">

  <div class="flex-2 form-contact item-tile">
    <header>
      <h2>Contact us</h2>
    </header>
    <?php echo do_shortcode('[contact-form-7 id="132" title="Contact form Slim"]'); ?>
  </div>

  <div class="social item-tile flex-2">
    <header>
      <h2>What our customers say</h2>
    </header>

    <blockquote class="testimonials">
      <p>"<?php echo addslashes ($singleFeedback['Testimonial_Short']);?>"</p>
      <h4><?php echo addslashes ($singleFeedback['Name']); ?></h4>
    </blockquote>

    <header>
      <h2>Find us online</h2>
    </header>
    <div class="social-links">
      <a class="btn-icon facebook" href="<?php echo jr_linkTo('facebook') ?>">facebook</a>
      <a class="btn-icon linkedin" href="<?php echo jr_linkTo('linkedin') ?>">linkedIn</a>
      <a class="btn-icon twitter" href="<?php echo jr_linkTo('twitter') ?>">twitter</a>
    </div>
  </div>

</article>
