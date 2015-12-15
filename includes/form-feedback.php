<?php //feedback/multiple choice form, taking questions from the database ?>
<?php $form = new feedbackForm; $form->init(); ?>

  <article class="flex-container">
    <section class="flex-1 form-contact special wider tile-outer dark">


        <form name="contact_form" class="js_contact_form" method="POST" action="">

        <?php foreach ($form->questions as $key=>$qu) : ?>

          <header class="tile-header lined">
            <h3 ><?php echo $key.'. '.$qu['question'] ?></h3>
          </header>

          <div class="form-survey <?php echo $qu['type'] ?>">

          <?php if ($qu['type'] == 'input') : ?>
            <textarea name="SURVEY~<?php echo $qu['question'] ?>" cols="40" rows="10" class="text-input">
            </textarea>


          <?php else : foreach ($qu['answer'] as $key2=>$answer) : ?>

            <input id="<?php echo "q{$key}a{$key2}" ?>" type="radio"
                   name="SURVEY~<?php echo $qu['question'] ?>" value="<?php echo $answer ?>">
            <label class="radio-<?php echo $qu['type'] ?>" for="<?php echo "q{$key}a{$key2}" ?>">
              <h3><?php echo $answer ?></h3>
            </label>

          <?php endforeach; endif; ?>
          </div>
        <?php endforeach ?>

          <header class="tile-header lined">
            <h3 >A few extra details</h3>
          </header>

          <fieldset>
            <span class="form-output"><em>(Please provide your email address. We will not link it to this survey but will be entered in a prize draw to win big monies!)</em></span>
            <label class="input-tag">Email Address</label>
            <input type="email" name="email" placeholder="Email" size="40" class="text-input">
            <span class="form-output error"></span>


          </fieldset>

          <fieldset>


            <input type="hidden" name="formType" value="survey" >

            <label class="input-tag">Any extra comments</label>
            <textarea name="message" cols="40" rows="10" class="text-input"></textarea>

            <span class="form-output response"></span>

            <button id="js-test" class="btn-red form-btn" name="submit" type="submit">
              <h3 class="text-icon email-w">Send</h3>
            </button>
          </fieldset>
        </form>
    </section>
  </article>
