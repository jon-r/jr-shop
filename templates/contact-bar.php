<?php
  // contact form at the bottom of most pages.
  $singleFeedback = jrx_random_feedback();
?>

<article class="contact-bar flex-container">

  <form class="form-contact flex-2">
    <h2>Contact us</h2>
    <br>
    <label for="Name">Name</label>
    <input type="text" id="name">
    <label for="email">Email Address</label>
    <input type="email" id="email">
    <label for="phone">Phone Number</label>
    <input type="number" id="phone">
    <label for="message">Message</label>
    <textarea id="message">THIS DOES NOTHING YET
      <?php echo $safeArr[pgName]; ?>
    </textarea>
    <button class="btn-red">
      <h3>Send</h3>
    </button>
  </form>

  <div class="social flex-2">
    <h2>What our customers say</h2>
    <blockquote class="testimonials">
      <p>"<?php echo addslashes ($singleFeedback['Testimonial_Short']);?>"</p>
      <h4><?php echo addslashes ($singleFeedback['Name']); ?></h4>
    </blockquote>

    <h2>Find us online</h2>
    <div class="social-links">
      <a class="btn-icon facebook" href="<?php echo link_to(facebook) ?>">facebook</a>
      <a class="btn-icon linkedin" href="<?php echo link_to(linkedin) ?>">linkedIn</a>
      <a class="btn-icon twitter" href="<?php echo link_to(twitter) ?>">twitter</a>
    </div>

  </div>

</article>
