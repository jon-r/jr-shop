 <?php
//editable list of questions on the DB

class feedbackForm {

  private $tbl;
  public $questions = [];

  public function init() {
    $this->query();
    $this->questions();
  }

  private function query() {
    global $wpdb;
    $this->tbl = $wpdb->get_results("SELECT `question`, `answer1`, `answer2`, `answer3`, `answer4`, `answer5` FROM `formfeedback`");
  }

  private function questions() {
    $count = count($this->tbl);
    for ($j=0;$j<$count;$j++) {
      $in = $this->tbl[$j];

      $this->questions[$j]['question'] = $in->question;
      if (is_numeric($in->answer1)) {
        $this->questions[$j]['type'] = 'num';
      } elseif ($in->answer1 == 'textinput') {
        $this->questions[$j]['type'] = 'input';
      } else {
        $this->questions[$j]['type'] = 'text';
      }

      for ($i=1;$i<6;$i++) {
        $a = $in->{'answer'.$i};
        if ($a == '0') {
          break;
        }
        $this->questions[$j]['answer'][$i] = $a;
      }
    }
  }

}


?>

