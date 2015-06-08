<?php // contact form at the bottom of most pages.
  $singleFeedback = jr_randomFeedback();
?>
<article class="contact-bar flex-container">

  <form class="form-contact item-tile flex-2">
    <header>
      <h2>Contact us</h2>
    </header>
    <br>
    <label for="Name">Name</label>
    <input type="text" id="name">
    <label for="email">Email Address</label>
    <input type="email" id="email">
    <label for="phone">Phone Number</label>
    <input type="tel" id="phone">
    <label for="message">Message</label>
    <textarea id="message">THIS DOES NOTHING YET
      <?php echo $jr_safeArray[pgName]; ?>
    </textarea>
    <button class="btn-red">
      <h3>Send</h3>
    </button>
  </form>

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
      <a class="btn-icon facebook" href="<?php echo jr_linkTo(facebook) ?>">facebook</a>
      <a class="btn-icon linkedin" href="<?php echo jr_linkTo(linkedin) ?>">linkedIn</a>
      <a class="btn-icon twitter" href="<?php echo jr_linkTo(twitter) ?>">twitter</a>
    </div>
  </div>

</article>
