<?php /*

  dot indicators for the form

*/?>
<?php $formSubject = isset($jr_page->ref) ? $jr_page->ref : get_the_title(); ?>

<form name="contact_form" class="js_contact_form" method="POST" action="">

  <?php if (isset($formPopout) && $formPopout == 'query') : ?>

  <fieldset class="subform-active">
    <h3>Ask us about this item</h3>

    <label class="input-tag">Subject</label>
    <input type="text" name="subject" value="<?php echo $formSubject ?>" class="text-input">

    <label class="required input-tag">Your Question</label>
    <textarea name="message" cols="40" rows="10" class="text-input req"></textarea>
    <span class="form-output error"></span>

    <label class="required input-tag">Postcode</label>
    <input type="text" name="postcode" size="10" class="text-input req">
    <span class="form-output error"></span>

    <button class="js_nextBtn btn-red form-btn" type="button">
      <h3 class="text-icon arrow-r-w">Next</h3>
    </button>
  </fieldset>

<?php elseif (isset($formPopout) && $formPopout == 'buy') : ?>

  <fieldset class="subform-active">
    <h3>Get a delivery quote</h3>

    <label class="input-tag">Subject</label>
    <input type="text" name="subject" value="Delivery Quote Request - <?php echo $formSubject ?>" class="text-input">

    <label class="required input-tag">Your Address</label>
    <textarea name="postcode" cols="40" rows="10" class="text-input req"></textarea>
    <span class="form-output error"></span>

    <label class="input-tag">Special Delivery Notes</label>
    <textarea name="message" cols="40" rows="10" class="text-input" ></textarea>

    <button class="js_nextBtn btn-red form-btn" type="button">
      <h3 class="text-icon arrow-r-w">Next</h3>
    </button>
  </fieldset>

<?php endif ?>

  <fieldset>
    <label class="required input-tag">Name</label>
    <input type="text" name="name" placeholder="Your Name" size="40" class="text-input req">
    <span class="form-output error"></span>

    <label class="required input-tag">Email Address</label>
    <input type="email" name="email" placeholder="Email" size="40" class="text-input req">
    <span class="form-output error"></span>

    <label class="input-tag">Phone Number</label>
    <input type="tel" name="phone number" placeholder="Your Number" size="40" class="text-input">
    <span class="form-output error"></span>

<?php if (!isset($formPopout)) : ?>

    <label class="required input-tag">Postcode</label>
    <input type="text" name="postcode" placeholder="Postcode" size="10" class="text-input req">
    <span class="form-output error"></span>

      </fieldset>

  <fieldset>

    <label class="input-tag">Subject</label>
    <input type="text" name="subject" value="<?php echo $formSubject ?>" class="text-input">

    <label class="input-tag">Your Message</label>
    <textarea name="message" cols="40" rows="10" class="text-input"></textarea>

<?php endif ?>
    <input type="hidden" name="formType" value="default" >

    <span class="form-output response"><em>(Items with an asterisk (*) are required)</em></span>

    <button id="js-test" class="btn-red form-btn" name="submit" type="submit">
      <h3 class="text-icon email-w">Send</h3>
    </button>
<?php if (isset($formPopout)) : ?>
    <button class="js_backBtn btn-grey form-btn" type="button">
      <h3 class="text-icon-left arrow-l-w">Back</h3>
    </button>
<?php endif ?>
  </fieldset>
</form>



