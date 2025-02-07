<?php
class Forums extends Controller {
  
  private $farmerModel;
  private $consultantModel;
  
  public function __construct() {
      $this->farmerModel = $this->model('Farmer');
      $this->consultantModel = $this->model('Consultant');
  }
  
  // Display the forum with list of questions and, if applicable, answers for a selected question.
  public function index($question_id = null) {
    // Fetch all questions using the Farmer model.
    $questions = $this->farmerModel->fetchQuestions();
    $data = ['questions' => $questions];

    // If a specific question is selected, fetch its answers.
    if ($question_id) {
        $answers = $this->consultantModel->fetchAnswers($question_id);
        $data['answers'] = $answers;
        $data['current_question_id'] = $question_id;

        // Find the text of the selected question from the list.
        foreach ($questions as $question) {
            if ($question->id == $question_id) {
                $data['current_question_text'] = $question->question;
                break;
            }
        }
    }
    $this->view('forum/index', $data);
}

  
  // Process a question submission (only available to farmers)
  public function ask() {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
          $data = ['question' => trim($_POST['question'])];
          
          if (empty($data['question'])) {
              $data['question_err'] = 'Question is required';
          }
          
          if (empty($data['question_err'])) {
              if ($this->farmerModel->storeQuestion($data)) {
                  flash('forum_message', 'Question submitted successfully');
                  redirect('forums/index');
              } else {
                  die('Something went wrong.');
              }
          } else {
              $this->view('forum/index', $data);
          }
      } else {
          redirect('forums/index');
      }
  }
  
  // Process an answer submission (only available to consultants)
  public function answer() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = [
            'question_id' => trim($_POST['question_id']),
            'answer'      => trim($_POST['answer'])
        ];
        
        if (empty($data['answer'])) {
            $data['answer_err'] = 'Answer is required';
        }
        
        if (empty($data['answer_err'])) {
            if ($this->consultantModel->storeAnswer($data)) {
                flash('forum_message', 'Answer submitted successfully');
                redirect('forum/index/' . $data['question_id']);
            } else {
                die('Something went wrong.');
            }
        } else {
            // If there is an error, we need to also fetch the list of questions and answers so the view is fully populated.
            $questions = $this->farmerModel->fetchQuestions();
            $data['questions'] = $questions;
            // Retrieve the answers for the current question
            $data['answers'] = $this->consultantModel->fetchAnswers($data['question_id']);
            // Retrieve current question text from the fetched questions.
            foreach ($questions as $question) {
                if ($question->id == $data['question_id']) {
                    $data['current_question_text'] = $question->question;
                    break;
                }
            }
            $this->view('forum/index', $data);
        }
    } else {
        redirect('forum/index');
    }
}

}
