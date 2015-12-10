<?php
/**
 * ajax attached contact form.
 * uses javascript to check valid info before submitting.
 * also uses php validators, to check without javascript
 */
function jr_formSubmit() {

  $errors = '';

  $source = jr_linkTo('email');
  $in = $_GET['keyword'];

  parse_str($in, $params);

  $to = $params['rmg'] ? get_option('jr_shop_contact_boss') : 'red.hotchilli@outlook.com' ;

  if(empty($params['name'])  ||
     empty($params['email']) ||
     empty($params['postcode'])
    ) {

    $errors .= "All fields marked with '*' are required.";

    //validate email
  } elseif (!filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {

    $errors .= "Please check your email address.";

  }

  if(empty($errors)) {

    //requireds, enforced by the first errorcheck
    $form_name = $params['name'];
    $form_email = $params['email'];
    $form_postcode = $params['postcode'];
    //optionals
    $form_subject = $params['subject'] ?: null;
    $form_phone = $params['phone_number'] ?: null;
    $form_message = wordwrap($params['message'], 70);
    //extra
    $formURL = $_GET['url'] ?: null;

    $subject = "Message from $form_name - $form_subject";

    $message = "You have a message from $form_name. \n"
      ."--- \n"
      ."$form_message \n"
      ."--- \n"
      ."Contact Details \n"
      ."Location: $form_postcode \n"
      ."Phone Number: $form_phone \n"
      ."Email: $form_email"
      ."--- \n"
      ."This email was sent from page: \n $formURL";

    $headers = "From: $source \n";
    $headers .= "Reply-To: $form_email";

    mail($to, $subject, $message, $headers);

    $out['output'] = "Thankyou for your message. Someone will be in touch as soon as possible.";
    $out['success'] = true;
  } else {
    $out['output'] = $errors;
    $out['success'] = false;
  }

  echo json_encode($out);
  wp_die();
}

add_action('wp_ajax_jr_formsubmit', 'jr_formSubmit');
add_action('wp_ajax_nopriv_jr_formsubmit', 'jr_formSubmit');

?>
