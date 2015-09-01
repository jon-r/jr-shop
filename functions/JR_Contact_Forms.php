<?php
/* sends the contact form info via ajax.
uses javascript to check valid info before supbimtting.
also uses php validators, to check without java script
*/
/*
function jr_getQuestions() {
  $questions = jrCached_FAQ();
  $out = '';
  $optCount = 0;
  foreach ($questions as $faq) {
    $optCount++;
    $answer = jr_format($faq['answer']);
    $out .= '<label class="form-question" for="opt-'.$optCount.'"><h3>'.$faq['question'].'</h3></label>'
      .'<input id="opt-'.$optCount.'" type="radio" name="subject" value="'.$faq['question'].'"></input>'
      .'<p class="form-answer">'.$answer
      .'<button class="js_nextBtn btn-red form-btn" type="button">'
      .'<span class="text-icon arrow-r-w" >'.$faq['next'].'</span>'
      .'</button>'
      .'</p>';
  }
  return $out;
}

function jr_getAnswers() {
  global $jr_safeArray;
  $getQuery = $_GET['keyword'];
  $questions = jrCached_FAQ();


  if ($getQuery != "") {
    $answer = array_search($getQuery, array_column($questions, 'question'));
    $out['answer'] = jr_format($questions[$answer]['answer']);
    $out['next'] = '<h3 class="text-icon arrow-r-w">'
      .$questions[$answer]['next'].'</h3>';
  } else {
    $out['answer'] = $out['next'] = "";
  }

  echo json_encode($out);
  wp_die();
}
add_action('wp_ajax_jr_getAnswers', 'jr_getAnswers');
add_action('wp_ajax_nopriv_jr_getAnswers', 'jr_getAnswers');

*/
function jr_formSubmit() {

  $errors = '';
  $to = jr_linkTo('email');
  $in = $_GET['keyword'];

  parse_str($in, $params);

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
    $form_ref = $params['formRef'] ?: null;
    $form_subject = $params['subject'] ?: null;
    $form_phone = $params['phone_number'] ?: null;
    $form_message = wordwrap($params['message'], 70);
    //extra
    $formURL = $_GET['url'] ?: null;

    $subject = "Message from $form_name - $form_subject";
    $ref = isset ($form_ref) ? "Ref: $form_ref \n" : null;

    $message = "You have a message from $form_name. \n"
      ."--- \n"
      ."$form_message \n"
      ."$ref"
      ."--- \n"
      ."Contact Details \n"
      ."Location: $form_postcode \n"
      ."Phone Number: $form_phone \n"
      ."Email: $form_email"
      ."--- \n"
      ."This email was sent from page: \n $formURL";

    $headers = "From: $to \n";
    $headers .= "Reply-To: $form_email";

    mail($to, $subject, $message, $headers);

    $out = "Form mailed successfully";
  } else {
    $out = $errors;
  }
  echo json_encode($out);
  wp_die();
}
add_action('wp_ajax_jr_formsubmit', 'jr_formSubmit');
add_action('wp_ajax_nopriv_jr_formsubmit', 'jr_formSubmit');

?>
