<!--


USE LEGENDS AS TITLES


-->

<?php $formSubject=isset($jr_safeArray['formRef']) ? $jr_safeArray['formRef'] : get_the_title(); ?>

<form name="contact_form" class="js_contact_form" method="POST" action="">
  <input type="hidden" name="formRef" value="<?php echo $formSubject ?>">

  <?php if (isset($formPopout)) :
    $questions = jrCached_FAQ();
    $optList .= '';
    $optCount = 0;
    foreach ($questions as $faq) {
      $optCount++;
      $optList .= '<label class="form-question" for="opt-'.$optCount.'"><h4>'.$faq['question'].'</h4></label>'
        .'<input id="opt-'.$optCount.'" type="radio" name="subject" value="'.$faq['question'].'"></input>'
        .'<span class="form-answer">'.$faq['answer']
        .'<em class="nextLink greater" >'.$faq['next'].'</em>'
        .'</span>';
    }
  ?>
  <fieldset class="subform-active">
    <h3>Ask us about <?php echo $formSubject ?></h3>
    <?php echo $optList; ?>
  </fieldset>

  <?php endif ?>

  <fieldset>
    <label class="required">Name</label>
    <input type="text" name="name" placeholder="Your Name" size="40" class="text-input req">
    <span class="form-output error"></span>

    <label>Company Name</label>
    <input type="text" name="business" placeholder="Business Name" size="40" class="text-input">

    <label class="required">Email Address</label>
    <input type="email" name="email" placeholder="Email" size="40" class="text-input req">
    <span class="form-output error"></span>

    <label class="required">Phone Number</label>
    <input type="tel" name="phone number" placeholder="Your Number" size="40" class="text-input req">
    <span class="form-output error"></span>

    <label>Address</label>
    <input type="text" name="address" placeholder="Your Address" class="text-input">

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



