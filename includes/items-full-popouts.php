<?php //moving this to separate page just to break down a bit ?>

<section class="flex-2 btn-frame">
  <button id="js-query-btn" class="btn-grey full-width">
    <h3 class="text-icon question-w" >Need more info</h3>
  </button>

  <div id="js-query-modal" class="tile-outer dark modal-frame right">
    <div class="modal-close btn-icon close-w"></div>
    <div class="form-contact modal" >
      <?php $formPopout = 'query' ?>
      <?php include('form-base.php') ?>

    </div>
  </div>
</section>
<section class="flex-2 btn-frame">
  <button id="js-buy-btn" class="btn-red full-width">
    <h3 class="text-icon basket-w" >Get a Delivery Quote</h3>
  </button>

  <div id="js-buy-modal" class="tile-outer dark modal-frame right">
    <div class="modal-close btn-icon close-w"></div>
    <div class="form-contact modal" >
      <?php $formPopout = 'buy' ?>
      <?php include('form-base.php') ?>

    </div>
  </div>
</section>
