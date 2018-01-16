<?php
// quick and dirty brands list. picks 4 at random

class brands {

  private $companies = [
    "Alton Towers",
    "Bella Italia",
    "Bolton Council",
    "Coronation Street Tours",
    "Gullivers World",
    "Harvey Nichols",
    "Hollyoaks",
    "Manchester Student Union",
    "Manchester University"
  ];

  public function pick($n = 4) {
    shuffle($this->companies);

    $select = array_slice($this->companies, 0, $n);


    foreach ($select as $a) {
      $b = str_replace(' ', '-', $a);
      $out[$a] = jr_siteImg('portfolio/'.strtolower($b).'.png');
    }
    return $out;
  }

}


?>
