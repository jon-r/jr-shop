<?php //moving this to sepatare page just to break down a bit ?>

<aside class="shop-buttons">
  <?php // cart button tbc ?>
  <button id="js-query-btn" class="btn-red">
    <h4 class="text-icon question-w" >Need more info</h4>
  </button>

  <div id="js-query-modal" class="modal right block dark-block">
    <div class="modal-close btn-icon close-w"></div>
    <div class="form-modal" >
      <?php $formPopout = true ?>
      <?php include('form-base.php') ?>

      <ul class="form-progress">
        <li class="progress-blip active"></li>
        <li class="progress-blip"></li>
        <li class="progress-blip"></li>
      </ul>
    </div>
  </div>


</aside>
