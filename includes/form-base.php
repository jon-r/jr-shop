<?php /*

  dot indicators for the form

*/?>
<?php $formSubject=isset($jr_safeArray['formRef']) ? $jr_safeArray['formRef'] : get_the_title(); ?>

<form name="contact_form" class="js_contact_form" method="POST" action="">
  <input type="hidden" name="formRef" value="<?php echo $formSubject ?>">

  <?php if (isset($formPopout) && $formPopout == 'query') :
    $optList = jr_getQuestions();
  ?>
  <fieldset class="subform-active">
    <h3>Ask us about <?php echo $formSubject ?></h3>
    <?php echo $optList; ?>
  </fieldset>

  <?php elseif (isset($formPopout) && $formPopout == 'buy') : ?>
<fieldset class="subform-active">
  <h2>Want to purchase <?php echo $shop_item['name'] ?>?</h2>
  <p>Feel free to visit our Warrington showroom and collect the item right away. <br>
    Phone us on
    <?php echo jr_linkTo('phone') ?> or email
    <?php echo jr_linkTo('eLink') ?> with reference number:
    <b><?php echo $shop_item['rhc'] ?></b>
  </p>
  <H3>Or</H3>
  <label class="form-question" for="purchase"><h3>Order Online</h3></label>
  <input id="purchase" type="radio" name="subject" value="Delivery Request: <?php echo $formSubject ?>">
  <p class="form-answer tall">
    <label class="required">Invoice Address</label>
    <textarea name="Invoice Address" cols="40" rows="10" class="text-input req"></textarea>
    <span class="form-output error"></span>

    <label>Delivery Address <br> (if different)</label>
    <textarea name="Delivery Address" cols="40" rows="10" class="text-input"></textarea>

    <span>
      Fill out our short form, and we will email you a full quote including delivery.
      Note that we only accept bank tranfers for online purchases.
      Call <?php echo jr_linkTo('phone') ?> for larger or special orders.
    </span>

    <button class="js_nextBtn btn-red form-btn" type="button">
      <h3 class="text-icon arrow-r-w">Next</h3>
    </button>
  </p>

</fieldset>
  <?php endif ?>

  <fieldset>
    <label class="required">Name</label>
    <input type="text" name="name" placeholder="Your Name" size="40" class="text-input req">
    <span class="form-output error"></span>

    <label class="required">Email Address</label>
    <input type="email" name="email" placeholder="Email" size="40" class="text-input req">
    <span class="form-output error"></span>

    <label>Phone Number</label>
    <input type="tel" name="phone number" placeholder="Your Number" size="40" class="text-input">
    <span class="form-output error"></span>

    <label class="required">Postcode</label>
    <input type="text" name="postcode" placeholder="Postcode" size="10" class="text-input req">
    <span class="form-output error"></span>
<?php if (!isset($formPopout)) : ?>
  </fieldset>

  <fieldset>
    <label>Subject</label>
    <input type="text" name="subject" value="<?php echo $formSubject ?>" class="text-input">
<?php else : ?>
    <span class="form-output response"><em>(Items with an asterisk (*) are required)</em></span>

    <button class="js_nextBtn btn-red form-btn" type="button">
      <h3 class="text-icon arrow-r-w">Next</h3>
    </button>
    <button class="js_backBtn btn-red form-btn" type="button">
      <h3 class="text-icon-left arrow-l-w">Back</h3>
    </button>
  </fieldset>

  <fieldset>
<?php endif ?>

    <label>Your Message</label>
    <textarea name="message" cols="40" rows="10" class="text-input"></textarea>

    <span class="form-output response"><em>(Items with an asterisk (*) are required)</em></span>

    <button id="js-test" type="submit" class="btn-red form-btn" name="submit" type="submit">
      <h3 class="text-icon email-w">Send</h3>
    </button>
    <?php if (isset($formPopout)) : ?>
    <button class="js_backBtn btn-red form-btn" type="button">
      <h3 class="text-icon-left arrow-l-w">Back</h3>
    </button>
    <?php endif ?>
  </fieldset>
</form>



