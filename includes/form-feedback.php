<?php //feedback/multiple choice form, taking questions from the database ?>
<?php $form = new feedbackForm; $form->init(); ?>

  <article class="flex-container">
    <section class="flex-1 form-contact special wider tile-outer dark">


        <form name="contact_form" class="js_contact_form" method="POST" action="">

        <?php foreach ($form->questions as $key=>$qu) : ?>

          <header class="tile-header lined">
            <h3 ><?php echo $key.'. '.$qu['question'] ?></h3>
          </header>

          <div class="form-radio <?php echo $qu['type'] ?>">
          <?php foreach ($qu['answer'] as $key2=>$answer) : ?>

            <input id="<?php echo "q{$key}a{$key2}" ?>" type="radio"
                   name="<?php echo "q_$key" ?>" value="<?php echo "a_$key2" ?>">
            <label class="radio-<?php echo $qu['type'] ?>" for="<?php echo "q{$key}a{$key2}" ?>">
              <h3><?php echo $answer ?></h3>
            </label>

          <?php endforeach; ?>
          </div>
        <?php endforeach ?>

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
            <input type="hidden" name="rmg" value="0">

            <label class="input-tag">Your Message</label>
            <textarea name="message" cols="40" rows="10" class="text-input"></textarea>

            <span class="form-output response"><em>(Items with an asterisk (*) are required)</em></span>

            <button id="js-test" class="btn-red form-btn" name="submit" type="submit">
              <h3 class="text-icon email-w">Send</h3>
            </button>
          </fieldset>
        </form>
    </section>
  </article>
