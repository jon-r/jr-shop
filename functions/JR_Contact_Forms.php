<?php
/* sends the contact form via ajax.
uses validate.js to check valid info before supbimtting.
also uses php validators, to check without java script
*/

/*function jr_enqueue_validate() {
  wp_enqueue_script( 'validate', plugin_dir_url( __FILE__ ) . 'js/validate.min.js', '', '1.4.1', true );
}
add_action( 'wp_enqueue_scripts', 'jr_enqueue_validate' );*/



function jr_formSubmit() {

  $errors = '';
  $to = jr_linkTo('email');

  if(empty($_GET['name'])  ||
     empty($_GET['email']) ||
     empty($_GET['postcode']) ||
     empty($_GET['phone number'] ) ||
     empty($_GET['message'])
    ) {
    $errors .= "Error: all fields marked with '*' are required";
   //validate email
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors .= "Error: please check your email address";
  }

  if(empty($errors)) {
    //requireds, enforced by the first errorcheck
    $form_name = $_GET['name'];
    $form_email = $_GET['email'];
    $form_message = wordwrap($_GET['message'], 70);
    $form_phone = $_GET['phone number'];
    $form_postcode = $_GET['postcode'];
    //optionals
    $form_business = $_GET['business'] ?: null;
    $form_subject = $_GET['subject'] ?: null;
    $form_address = $_GET['address'] ?: null;
    //extra
    $formURL = $_GET['url'] ?: null;

    $subject = "Message from $form_name - $form_subject";
    $message = "You have a message from $form_name. /n"
      ."--- /n"
      ."$form_message /n"
      ."--- /n"
      ."Contact Details /n"
      .isset($form_business) ? "Business Name: $form_business /n" : null
      .isset($form_address) ? "Address: $form_address /n" : null
      ."Postcode: $form_postcode /n"
      ."Phone Number: $form_postcode /n"
      ."Email: $form_email"
      ."--- /n"
      ."This email was sent from /n $formURL";

    $headers = "From: $to \n";
    $headers .= "Reply-To: $form_email";

    mail($to, $subject, $message, $headers);

    //echo
  }

}




/*

$errors = '';
$myemail = 'webuycatering@hotmail.com';//<-----Put Your email address here.

if(empty($_POST['name'])  ||
   empty($_POST['email']) ||
   empty($_POST['postcode']) ||
   empty($_POST['number'] )
  ) {
  $errors .= "\n Error: all fields marked with '*' are required";
}

$name = $_POST['name'];
$email_address = $_POST['email'];
$number = $_POST['number'];
$message = $_POST['message'];
$message = wordwrap($message, 70);
$location = $_POST['postcode'];
$source = $_POST['source'];
$site = $_POST['site'];

if (!preg_match(
  "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i",
  $email_address)
   ) {
  $errors .= "\n Error: Invalid email address";
}

if( empty($errors))
{
  $to = $myemail;
  $email_subject = "We Buy Catering message from: $name";
  $email_message = "You have received a new message. \n".
    " Here are the details:\n Name: $name \n ".
    "Location: $location \n Email: $email_address  \n Telephone: $number \n Message: \n $message \n\n".
    "The visitor was on the $site site, and tells us the advert was via $source";

  $headers = "From: info@webuycateringnorthwest.co.uk \n";
  $headers .= "Reply-To: $email_address";

  mail($to,
       $email_subject,
       $email_message,
       $headers);
  //redirect to the 'thank you' page
  header('Location: thank-you.php');
}
?>
<!DOCTYPE html>
<html>
<?php include ('wbc_head.php') ?>
<?php include ('wbc_functions.php') ?>

<body>
  <header class="block-blue page-header">
    <div class="banner-float">
      <picture>
         <source media="(min-width: 640px)" srcset="style/media/WeBuyCateringNW.png">
         <source srcset="style/media/WeBuyCateringNW_Small.png">
         <img src="style/media/WeBuyCateringNW.png" alt="We Buy Catering">
      </picture>
    </div>
  </header>

  <main>
    <section class="block-white page-block">
      <header class="section-title">
        <h1>Error</h1>
      </header>

      <div class="block-float">
        <p class="grid-6">
          Something went wrong. Please re-check your form details.
          <?php
          echo nl2br($errors);
          ?>
        </p>
        <form method="post" name="contactForm" action="contact-form-handler.php" class="grid-6">
          <label>* Your Name:</label>
          <input type="text" name="name" required>
          <label>* Email Address:</label>
          <input type="email" name="email"  required>
          <label>Phone Number: (not required but for quickest response)</label>
          <input type="tel" name="number" required>
          <div id='contactForm_number_errorloc'></div>
          <label>* Postcode:</label>
          <input type="text" name="postcode" required>
          <label>Message:</label>
          <textarea name="message"></textarea>
          <label>Where did you find us?:</label>
          <select name="source">
            <option></option>
            <option>Google Search</option>
            <option>192 Listing</option>
            <option>Second Hand Catering Equipment</option>
            <option>Other</option>
          </select>
          <button type="submit">Send</button>
          <p>(*) marks required fields. We do not store any details beyond this query, it just helps us give you a faster reply.</p>
          <p>Please give as many details as you can, such as products, price and location</p>
        </form>
      </div>

    </section>
  </main>

  <footer class="block-blue page-footer">
    <div class="banner-float">
      <h1>Phone <?php echo $tel ?> today!<br>
        <?php echo $elink ?>
      </h1>
    </div>
  </footer>
</body>

<?php include ('wbc_foot.php') ?>
  */
?>