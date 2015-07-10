<?php //moving this to sepatare page just to break down a bit ?>

<aside class="shop-buttons">
 <?php //buy + cart button NYI
/* <button id="js-buy-btn" class="btn-red text-icon basket-w">
    <h4>Buy Today</h4>
  </button>
  <div id="js-buy-modal" class="modal right dark-block">

    <div class="buy-details flex-container">
      <div class="form-contact flex-2" >
        <div class="modal-spec" >
          <img src="<?php echo site_url(jr_imgResize($shop_item['imgFirst'], 'thumb')) ?>"
               class="modal-thumb" alt="<?php echo $shop_item['name'] ?>">
                  <h3><?php echo $shop_item['name'].' ( '.$shop_item['rhc'].' )'; ?></h3><br>
        <em><?php echo $shop_item['price'] ?></em>
        </div>

      </div>

      <div class="flex-2" >
        <h4>Purchasing Equipment:</h4><br>
        All we need are a few contact details and we'll get you an invoice right away!
        <ol>
          <li>Just fill in the form.</li>
          <li>Let us know if you need us to arrange delivery.</li>
          <li>We will email you an invoice and delivery quote as soon as we can.</li>
        </ol>

        <h4>Please note</h4><br>
        <p>Please call <?php echo jr_linkTo('phone') ?> or email us at <?php echo jr_linkTo('eLink') ?> if you want to order multiple items, or ask more questions.</p>

        <p>Any information provided will remain strictly confidential. We take customer security seriously, and no payments are made directly on this site. Payment for equipment by BACs transfer only unless stated. All delivery quotes are for third person couriers unless stated. View our [terms] page for full information.</p>
      </div>

    </div>
    <div class="modal-close btn-icon close-w"></div>

  </div>
  <button class="text-icon list"><h3>Add To Shopping List</h3></button> */ ?>

  <button id="js-query-btn" class="btn-red text-icon question-w">
    <h4>Need more info</h4>
  </button>

  <div id="js-query-modal" class="modal right dark-block is-active-small">
    <div class="modal-close btn-icon close-w"></div>
      <div class="modal-spec">
        <h2><?php echo $shop_item['name'] ?></h2>
        <br>
        <h3>
          <?php echo $shop_item['rhc'] ?> <br> <?php echo $shop_item['price'] ?>
        </h3>
      </div>

    <div class="form-modal" >

    <?php echo do_shortcode('[contact-form-7 id="167" title="Contact Form Query"]'); ?>
    </div>
  </div>

</aside>
