<?php
/*TO DO

  - add/fill in for q/a. perhaps submit the question rather than wierd number references? would prob save on db queries
  - add an "optional" overide for the manadtory messages
  - new thankyou message. maybe link to google?

  - add a 'text input' option

  LATER
  - put the old form stuff into the form class?

*/




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

  $to = get_option('jr_shop_contact_form_to');
  $survey = false;

  //modifiers for 'special' forms
  if ($params['formType'] == 'rmg') {
    $to =  get_option('jr_shop_contact_boss');
    $response = "Thankyou for your feedback. Robert reads each message personally and will use it to improve our service.";
  } elseif ($params['formType'] == 'survey') {
    $survey = true;#
    $response = "Thankyou for your feedback. We read every response personally and will use it to improve our service.";
  } else {
    $response = "Thankyou for your message. Someone will be in touch as soon as possible.";
  }

  //$to = $params['formType'] ? get_option('jr_shop_contact_boss') : 'red.hotchilli@outlook.com';

  if(!$survey && (empty($params['name']) || empty($params['email']) || empty($params['postcode']))) {

    $errors .= "All fields marked with '*' are required.";

    //validate email
  } elseif (!empty($params['email']) && !filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {

    $errors .= "Please check your email address.";

  }

  if(empty($errors)) {

    //requireds, enforced by the first errorcheck (made optional by the survey overide)
    $form_name = $params['name'] ?: 'no name provided';
    $form_email = $params['email'] ?: '';
    $form_postcode = $params['postcode'] ?: '';
    //optionals
    $form_subject = $params['subject'] ?: null;
    $form_phone = $params['phone_number'] ?: null;
    $form_message = wordwrap($params['message'], 70);
    //extra
    $formURL = $_GET['url'] ?: null;

    if ($survey) {
      $form_subject = 'Survey results';
      $form_survey = "--- \n\n SURVEY RESULTS \n\n";
      foreach ($params as $q=>$answer) {
        if (strpos($q, 'SURVEY~') === 0) {
          $question = str_replace('SURVEY~','',$q);
          $form_survey .= "$question: $answer \n";
        }
      }
    } else {
      $form_survey = "";
    }

    $subject = "Message from $form_name - $form_subject";

    $message = "You have a message from $form_name. \n\n"
      ."$form_survey \n"
      ."--- \n\n"
      ."$form_message \n\n"
      ."--- \n\n"
      ."Contact Details \n\n"
      ."Location: $form_postcode \n"
      ."Phone Number: $form_phone \n"
      ."Email: $form_email \n\n"
      ."--- \n\n"
      ."This email was sent from page: \n $formURL";

    $headers = "From: $source \n";
    $headers .= "Reply-To: $form_email";

    mail($to, $subject, $message, $headers);

    $out['output'] = $response;
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
