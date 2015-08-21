<?php //moving this to sepatare page just to break down a bit ?>

<aside class="shop-buttons">

  <button id="js-query-btn" class="btn-red">
    <h4 class="text-icon question-w" >Need more info</h4>
  </button>

  <div id="js-query-modal" class="tile-outer dark modal right">
    <div class="modal-close btn-icon close-w"></div>
    <div class="form-modal" >
      <?php $formPopout = 'query' ?>
      <?php include('form-base.php') ?>

      <ul class="form-progress">
        <li class="progress-blip active"></li>
        <li class="progress-blip"></li>
        <li class="progress-blip"></li>
      </ul>
    </div>
  </div>

  <button id="js-buy-btn" class="btn-red">
    <h4 class="text-icon basket-w" >Buy Now</h4>
  </button>

  <div id="js-buy-modal" class="tile-outer dark modal right is-open-small">
    <div class="modal-close btn-icon close-w"></div>
    <div class="form-modal" >
      <?php $formPopout = 'buy' ?>
      <?php include('form-base.php') ?>

      <ul class="form-progress">
        <li class="progress-blip active"></li>
        <li class="progress-blip"></li>
        <li class="progress-blip"></li>
      </ul>
    </div>
  </div>


</aside>
