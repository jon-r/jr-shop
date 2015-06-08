<section class="map-container item-tile" >
  <iframe class="contact-map" src="https://www.google.com/maps/embed/v1/place?q=Red%20Hot%20Chilli%20Northwest%2C%20Winwick%20Street%2C%20Warrington%2C%20United%20Kingdom&attribution_source=Red+Hot+Chilli+Northwest&attribution_web_url=<?php echo urlencode(site_url()) ?>&zoom=10&key=AIzaSyBxcWgYfqrqVij6i74K1hrQyt2tDnXvMXs">
  </iframe>
</section>

<section class="flex-4 item-tile" >
  <header>
    <h2>Visit Our Showroom</h2>
  </header>
  <?php echo jr_linkTo(address) ?>
  <hr>

  <header>
    <h2>Talk to us directly</h2>
  </header>
  <p class="text-icon-left phone"><?php echo jr_linkTo(phone) ?></p>
  <p class="text-icon-left email"><?php echo jr_linkTo(eLink) ?></p>
<hr>
  <header>
    <h2>Find us online</h2>
  </header>
  <div class="social-links-small">
    <a class="btn-icon-small facebook" href="<?php echo jr_linkTo(facebook) ?>">facebook</a>
    <a class="btn-icon-small linkedin" href="<?php echo jr_linkTo(linkedin) ?>">linkedIn</a>
    <a class="btn-icon-small twitter" href="<?php echo jr_linkTo(twitter) ?>">twitter</a>
  </div>
</section>
