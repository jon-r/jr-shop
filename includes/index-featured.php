<?php
  // featured items on front page
?>
<article class="flex-container">
  <section class="tile-outer dark list-featured flex-4">
    <a href="<?php echo site_url('products/arrivals/') ?>">
      <header class="tile-header dark">
        <h2>Latest Arrivals</h2>
      </header>
      <img class="framed" src="<?php echo jr_siteImg('icons/btn-arrivals.jpg') ?>" alt="Equipment just in">
    </a>
  </section>
  <section class="tile-outer dark list-featured flex-4">
    <a href="<?php echo site_url('brands/') ?>">
      <header class="tile-header dark">
        <h2>Brands</h2>
      </header>
      <img class="framed" src="<?php echo jr_siteImg('icons/btn-brands.jpg') ?>" alt="Shop By Brands">

    </a>
  </section>
  <section class="tile-outer dark list-featured flex-4">
    <a href="<?php echo site_url('services/') ?>">
      <header class="tile-header dark">
        <h2>Other Services</h2>
      </header>
      <img class="framed" src="<?php echo jr_siteImg('icons/btn-services.jpg') ?>" alt="Our Other Services">

    </a>
  </section>
  <section class="tile-outer dark list-featured flex-4">
    <a href="<?php echo site_url('about/') ?>">
      <header class="tile-header dark">
        <h2>About Red Hot Chilli</h2>
      </header>
      <img class="framed" src="<?php echo jr_siteImg('icons/btn-about.jpg') ?>" alt="About Us">

    </a>
  </section>

</article>
