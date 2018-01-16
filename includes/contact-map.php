<article class="flex-container"  >
  <section class="flex-4 tile-outer" >
    <header class="tile-header lined">
      <h2>Visit Our Showroom</h2>
    </header>
    <?php echo jr_linkTo('address') ?>

    <header class="tile-header lined">
      <h2>Talk to us directly</h2>
    </header>
    <p class="text-icon-left phone"><?php echo jr_linkTo('phone') ?></p>
    <p class="text-icon-left email"><?php echo jr_linkTo('eLink') ?></p>

    <p>Mondays-Fridays: <?php echo jr_openingTimes('weekday'); ?><br>
    Saturdays: <?php echo jr_openingTimes('saturday'); ?></p>
    <br>
    <em>Closed on Public UK Holidays</em>

    <header class="tile-header lined">
      <h2>Find us online</h2>
    </header>
    <div class="social-links-small">
      <a class="btn-icon-small facebook" href="<?php echo jr_linkTo('facebook') ?>">facebook</a>
      <a class="btn-icon-small gplus" href="<?php echo jr_linkTo('gplus') ?>">google plus</a>
      <a class="btn-icon-small twitter" href="<?php echo jr_linkTo('twitter') ?>">twitter</a>
    </div>
  </section>

  <section class="map-container tile-outer" >
    <iframe class="contact-map" src="https://www.google.com/maps/embed/v1/place?q=Red%20Hot%20Chilli%20Northwest%2C%20Winwick%20Street%2C%20Warrington%2C%20United%20Kingdom&attribution_source=Red+Hot+Chilli+Northwest&attribution_web_url=<?php echo urlencode(home_url()) ?>&zoom=10&key=AIzaSyBxcWgYfqrqVij6i74K1hrQyt2tDnXvMXs">
    </iframe>
  </section>
</article>
