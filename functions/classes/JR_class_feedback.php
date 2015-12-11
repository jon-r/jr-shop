 <?php
//editable list of questions on the DB

class feedbackForm {

  private $tbl;
  public $questions = [];
  //public $answers = [];
  //public $answer_class;

  public function init() {
    global $wpdb;

    $this->tbl = $wpdb->get_results("SELECT `question`, `answer1`, `answer2`, `answer3`, `answer4`, `answer5` FROM `formfeedback`");

    $this->questions();
  }

  private function questions() {
    $count = count($this->tbl);
    for ($j=0;$j<$count;$j++) {
      $in = $this->tbl[$j];

      $this->questions[$j]['question'] = $in->question;
      $this->questions[$j]['type'] = is_numeric($in->answer1) ? 'num' : 'text';

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

