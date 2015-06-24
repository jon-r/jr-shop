<?php
//output functions
//this is where the shop database is processed into content


//-------------- pick testimonial -----------------------------------------------
// grabs a single testimonial at random
function jr_randomFeedback() {
  $in = jrQ_tesimonial();
  $countIn = count($in) - 1;
  $random = rand(0,$countIn);
  return $in[$random];
}

?>
