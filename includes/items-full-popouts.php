<?php //moving this to sepatare page just to break down a bit ?>

<aside class="shop-buttons">
  <button id="js-buy-btn" class="btn-red text-icon basket-w">
    <h4>Buy Today</h4>
  </button>
  <div id="js-buy-modal" class="modal right dark-block">

    <div class="buy-details flex-container">
      <div class="flex-2">
        <h4>Purchasing Equipment:</h4><br>
        All we need are a few contact details and we'll get you an invoice right away!
        <ol>
          <li>Just fill in the form.</li>
          <li>Let us know if you need us to arrange delivery.</li>
          <li>We will email you an invoice and delivery quote as soon as we can.</li>
        </ol>
      </div>

      <div class="flex-2" >
        <h4>Please note</h4><br>
        <p>Please call <?php echo jr_linkTo('phone') ?> or email us at <?php echo jr_linkTo('eLink') ?> if you want to order multiple items, or ask more questions.</p>

        <p>Any information provided will remain strictly confidential. We take customer security seriously, and no payments are made directly on this site. Payment for equipment by BACs transfer only unless stated. All delivery quotes are for third person couriers unless stated. View our [terms] page for full information.</p>
      </div>

      <form class="form-contact flex-2">
        <h4><?php echo $shop_item['name'].' ( '.$shop_item['rhc'].' )'; ?></h4><br>
        <em><?php echo $shop_item['price'] ?></em><br>
        <hr>
        <label for="Name">Name</label>
        <input type="text" id="name" placeholder="John Smith">
        <label for="email">Email Address</label>
        <input type="email" id="email" placeholder="john.smith@email.com">
        <label for="phone">Phone Number</label>
        <input type="tel" id="phone">
        <label for="Name">Name</label>
        <input type="text" id="name">
        <label for="message">Extra Information</label>
        <textarea id="message">THIS DOES NOTHING YET
          <?php echo $jr_safeArray['pgRef']; ?>
        </textarea>
        <button class="btn-red">
          <h3>Send</h3>
        </button>
      </form>

    </div>
    <div class="modal-close btn-icon close-w"></div>

  </div>



  <button id="js-query-btn" class="btn-red text-icon question-w">
    <h4>Need more info</h4>
  </button>
  <!--    <button class="text-icon list"><h3>Add To Shopping List</h3></button>-->
  <div id="js-query-modal" class="modal right dark-block">
    <div class="modal-close btn-icon close-w"></div>
  </div>
</aside>
