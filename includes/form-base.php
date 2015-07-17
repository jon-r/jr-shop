<form name="contact_form" class="js_contact_form" method="POST" action="" >
  <?php
$formSubject=isset($jr_safeArray[ 'formRef']) ? $jr_safeArray['formRef'] : get_the_title();
global $hasMailForm;
$hasMailForm = true;
  ?>
  <p>
    <label class="required">Name</label>
    <input type="text" name="name" placeholder="Your Name" size="40" class="text-input" aria-required="true">
    <span class="form-error-output"></span>

    <label>Business</label>
    <input type="text" name="business" placeholder="Business Name" size="40" class="text-input">

    <label class="required">Email Address</label>
    <input type="email" name="email" placeholder="Email" size="40" class="text-input" aria-required="true">
    <span class="form-error-output"></span>

    <label class="required">Phone Number</label>
    <input type="tel" name="tel" placeholder="Your Number" size="40" class="text-input" aria-required="true">
    <span class="form-error-output"></span>

    <label>Address</label>
    <input type="text" name="address" placeholder="Your Address" class="text-input">

    <label class="required">Postcode</label>
    <input type="text" name="postcode" placeholder="Postcode" size="10" class="text-input" aria-required="true">
    <span class="form-error-output"></span>

    <label>Subject</label>
    <input type="text" name="subject" value="<?php echo $formSubject ?>" class="text-input">

    <em>(Items with an asterisk (*) are required)</em>
  </p>
  <p>
    <label>Your Message</label>
    <textarea name="your-message" cols="40" rows="10" class="text-input"></textarea>

    <br>
  <button id="js-test" type="submit" class="btn-red" name="submit" type="submit">Send</button>

  </p>
</form>



