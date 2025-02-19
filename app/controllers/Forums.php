<?php
class Forums extends Controller {
  
    private $farmerModel;
    private $consultantModel;
    
    public function __construct() {
        $this->farmerModel = $this->model('Farmer');
        $this->consultantModel = $this->model('Consultant');
    }
    
    // Display the forum: list each question and below it its answers.
    public function index() {
      // Fetch all questions
      $questions = $this->farmerModel->fetchQuestions();
  
      // For each question, fetch its answers from the Consultant model.
      // (This will result in one extra query per question—consider a JOIN for heavy traffic.)
      foreach ($questions as $question) {
          $question->answers = $this->consultantModel->fetchAnswers($question->id);
      }
  
      $data = ['questions' => $questions];
      $this->view('forum/index', $data);
    }
    
    // Process a question submission (only for farmers)
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
    
    // Process an answer submission (only for consultants)
    public function answer() {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
          // Assume the answer form includes hidden field "question_id"
          $data = [
              'question_id' => trim($_POST['question_id']),
              'answer'      => trim($_POST['answer']),
              'description' => trim($_POST['description'] ?? ''),
              'answer_err'  => ''
          ];
          
          if (empty($data['answer'])) {
              $data['answer_err'] = 'Answer is required';
          }
          
          if (empty($data['answer_err'])) {
              if ($this->consultantModel->storeAnswer($data)) {
                  flash('forum_message', 'Answer submitted successfully');
                  redirect('forums/index');
              } else {
                  die('Something went wrong.');
              }
          } else {
              // If there is an error, re-display the forum.
              $this->index();
          }
      } else {
          redirect('forums/index');
      }
    }
  }
  