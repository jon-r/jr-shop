<?php
  // featured items on front page
?>
<article class="flex-container featured">

  <section class="tile-outer list-featured flex-2">
    <img class="framed" src="<?php echo jr_siteImg('icons/btn-arrivals.jpg') ?>" alt="Equipment just in">
    <a href="<?php echo home_url('products/arrivals/') ?>">
      <h2>Latest Arrivals</h2>
      <span>
        Fresh equipment <br>
        from the workshop floor.
      </span>
    </a>
  </section>
  <section class="tile-outer  list-featured flex-2">
    <img class="framed" src="<?php echo jr_siteImg('icons/btn-brands.jpg') ?>" alt="Shop By Brands">
    <a href="<?php echo home_url('brands/') ?>">
      <h2>Featured Brands</h2>
      <span>
        The manufacturers <br>
        that you know and trust.
      </span>
    </a>
  </section>
  <section class="tile-outer list-featured flex-2">
    <img class="framed" src="<?php echo jr_siteImg('icons/btn-exports.jpg') ?>" alt="National and International Deliveries">
    <a href="<?php echo home_url('deliveries/') ?>">
      <h2>Deliveries &amp; Exports</h2>
      <span>
        Competitive delivery quotes.<br>
        International customers welcome.
      </span>
    </a>
  </section>
  <section class="tile-outer list-featured flex-2">
    <img class="framed" src="<?php echo jr_siteImg('icons/btn-talk2.jpg') ?>" alt="National and International Deliveries">
    <a href="<?php echo home_url('feedback/') ?>">
      <h2>Talk to the boss</h2>
      <span>
        Any comments? Feedback?<br>
        Speak directly to Robert, M.D.
      </span>
    </a>
  </section>

</article>
