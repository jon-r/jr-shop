<form name="contact" class="js_contact_form" action="post"  novalidate="novalidate">
  <?php
$formSubject=isset($jr_safeArray[ 'formRef']) ? $jr_safeArray['formRef'] : get_the_title();
global $hasMailForm;
$hasMailForm = true;
  ?>
  <p>
    <label>* Name</label>
    <input type="text" name="name" placeholder="Your Name" size="40" class="text-input" aria-required="true" aria-invalid="false">
    <br>
    <label>Business</label>
    <input type="text" name="business" placeholder="Business Name" size="40" class="text-input" aria-invalid="false">
    <br>
    <label>* Email Address</label>
    <input type="email" name="email" placeholder="Email Address" size="40" class="text-input" aria-required="true" aria-invalid="false">
    <br>
    <label>* Phone Number</label>
    <input type="tel" name="tel" placeholder="Your Number" size="40" class="text-input" aria-required="true" aria-invalid="false">
    <br>
    <label>Address</label>
    <input type="text" name="address" placeholder="Full Address" class="text-input" aria-required="true" aria-invalid="false">
    <br>
    <label>* Postcode</label>
    <input type="text" name="postcode" placeholder="Postcode" size="10" class="text-input" aria-required="true" aria-invalid="false">
    <br>
    <label>Subject</label>
    <input type="text" name="subject" value="<?php echo $formSubject ?>" class="text-input" aria-invalid="false">
    <br>
    <em>(Items with an asterisk (*) are required)</em>
    <div class="js_form_success" ></div>
  </p>
  <p>
    <label>Your Message</label>
    <textarea name="your-message" cols="40" rows="10" class="text-input" aria-invalid="false"></textarea>

    <br>
    <input type="submit" value="Send" class="btn-red button">

  </p>
</form>

