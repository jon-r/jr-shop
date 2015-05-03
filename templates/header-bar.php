<div class="container flex-container<?php echo is_front_page() ? ' home' : ' not-home' ?>">

  <a class="header-logo flex-2" href="<?php echo home_url(); ?>" rel="nofollow">
    <img src="<?php echo site_url('/').imgSrcRoot('rhc','RHC-Web','png'); ?>" alt="Red Hot Chilli - Used Catering Equipment"/>
  </a>

  <menu class="header-links flex-<?php echo is_front_page() ? '1' : '2' ?> flex-container">

    <form class="form-head-search flex-<?php echo is_front_page() ? '2' : '1' ?>" method="get" action="<?php echo site_url('search'); ?>">
      <h3 class="head-title">Search Catering Equipment</h3>
      <label class="text-icon-right search"></label>
      <input type="search" name="search" placeholder="Enter Keyword or Reference">
      <button class="btn-red" type="submit"><h3>Go</h3></button>
    </form>

    <div class="header-contact flex-<?php echo is_front_page() ? '2' : '1' ?>">
      <h3 class="head-title">Talk to us Direct</h3>
      <h3 class="text-icon-right phone-w"><?php echo link_to(phone) ?></h3>
      <a href="mailto:<?php echo link_to(email) ?>"><h3 class="text-icon-right email-w"><?php echo link_to(email) ?></h3></a>
    </div>

  </menu>

</div>
