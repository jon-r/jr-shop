<?php //direct 'boss' version. ?>

<form name="contact_form" class="js_contact_form" method="POST" action="">
  <h1>I want to...</h1>
  <div class="form-survey" >

    <input id="select-equip" type="radio" name="subject" value="Feedback about Equipment" checked>
    <label class="radio-text" for="select-equip" >
      <h3>Discuss equipment I have purchased.</h3>
    </label>
    <input id="select-review" type="radio" name="subject" value="Reviewing staff">
    <label class="radio-text" for="select-review" >
      <h3>Give feedback about the team at Red Hot Chilli.</h3>
    </label>
    <input id="select-suggestion" type="radio" name="subject" value="Suggestion">
    <label class="radio-text" for="select-suggestion" >
      <h3>Make a suggestion for improving our services.</h3>
    </label>
  </div>

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

    <label class="required input-tag">Postcode</label>
    <input type="text" name="postcode" placeholder="Postcode" size="10" class="text-input req">
    <span class="form-output error"></span>

  </fieldset>

  <fieldset>
    <input type="hidden" name="formType" value="rmg" >

    <label class="input-tag">Your Message</label>
    <textarea name="message" cols="40" rows="10" class="text-input"></textarea>

    <span class="form-output response"><em>(Items with an asterisk (*) are required)</em></span>

    <button id="js-test" class="btn-red form-btn" name="submit" type="submit">
      <h3 class="text-icon email-w">Send</h3>
    </button>
  </fieldset>
</form>



