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
          $data = [
              'question_id' => trim($_POST['question_id']),
              'answer'      => trim($_POST['answer']),
              'answer_err'  => ''
          ];
  
          if (empty($data['answer'])) {
              $data['answer_err'] = 'Answer is required';
          }
  
          if (empty($data['answer_err'])) {
              if ($this->consultantModel->storeAnswer($data)) {
                  //  AFTER saving the answer, fetch the question to get the farmer ID
                  $question = $this->farmerModel->getQuestionById($data['question_id']);
                  if ($question) {
                      // Send the notification to the farmer
                      require_once APPROOT . '/helpers/notification_helper.php';
                      $notificationHelper = new NotificationHelper();
                      $from_id = getUserId();    // consultant's id
                      $to_id = $question->farmer_id;  // farmer's id
                      $from_type = 'c'; // consultant
                      $to_type = 'f';   // farmer
                      $subject = 'New Answer to Your Question';
                      $content = 'A consultant has answered your question: "' . substr($question->question, 0, 50) . '..."';
                      $url = URLROOT . '/forums/index'; 
                      $msg_type = 'info';
  
                      $notificationHelper->send_notification($from_type, $from_id, $to_type, $to_id, $subject, $content, $url, $msg_type);
                  }
  
                  flash('forum_message', 'Answer submitted successfully');
                  redirect('forums/index');
              } else {
                  die('Something went wrong.');
              }
          } else {
              $this->index();
          }
      } else {
          redirect('forums/index');
      }
    }
  
    public function editAnswer($ans_id) {
      // Get the answer data for editing.
      $answerData = $this->consultantModel->getAnswerById($ans_id);
      if (!$answerData) {
          flash('forum_message', 'Answer not found or unauthorized', 'alert alert-danger');
          redirect('forums/index');
      }
      
      // Fetch all questions as in the index method.
      $questions = $this->farmerModel->fetchQuestions();
      foreach ($questions as $question) {
          $question->answers = $this->consultantModel->fetchAnswers($question->id);
      }
      
      // Prepare data with questions and the answer being edited.
      $data = [
          'questions'     => $questions,
          'edit_answer'   => $answerData,
          'ans_id'        => $ans_id,
          'answer'        => $answerData->answer
      ];
      
      // Load the forum view, which will now display all questions and show the inline edit form for the selected answer.
      $this->view('forum/index', $data);
  }
   
      // Process the update of an answer
      public function updateAnswer($ans_id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
          
          $data = [
            'answer' => trim($_POST['answer']),
            'answer_err' => ''
          ];
          
          if (empty($data['answer'])) {
            $data['answer_err'] = 'Answer cannot be empty';
          }
          
          if (empty($data['answer_err'])) {
            if ($this->consultantModel->updateAnswer($ans_id, $data)) {
              flash('forum_message', 'Answer updated successfully');
              redirect('forums/index');
            } else {
              die('Something went wrong.');
            }
          } else {
            // Redisplay the form with error
            $data['ans_id'] = $ans_id;
            $this->view('forum/editAnswer', $data);
          }
        } else {
          redirect('forums/index');
        }
      }
    
      // Delete an answer
      public function deleteAnswer($ans_id) {
        if ($this->consultantModel->deleteAnswer($ans_id)) {
          flash('forum_message', 'Answer deleted successfully');
          redirect('forums/index');
        } else {
          die('Something went wrong.');
        }
      }

      // Show inline edit form for a question (only for the farmer who asked it)
public function editQuestion($q_id) {
  // Ensure only a logged-in farmer can edit
  if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'farmer'){
      redirect('forums/index');
  }
  $question = $this->farmerModel->getQuestionById($q_id);
  if(!$question || $question->farmer_id != $_SESSION['user_id']){
      flash('forum_message', 'Unauthorized access', 'alert alert-danger');
      redirect('forums/index');
  }
  // Fetch all questions with their answers
  $questions = $this->farmerModel->fetchQuestions();
  foreach ($questions as $q) {
       $q->answers = $this->consultantModel->fetchAnswers($q->id);
  }
  $data = [
       'questions' => $questions,
       'edit_question' => $question
  ];
  $this->view('forum/index', $data);
}

// Process the update of a question
public function updateQuestion($q_id) {
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
       $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
       $data = [
            'question' => trim($_POST['question']),
            'question_err' => ''
       ];
       if(empty($data['question'])){
            $data['question_err'] = 'Please enter a question';
       }
       if(empty($data['question_err'])){
           if($this->farmerModel->updateQuestion($q_id, $data)){
               flash('forum_message', 'Question updated successfully');
               redirect('forums/index');
           } else {
               die('Something went wrong');
           }
       } else {
           $question = $this->farmerModel->getQuestionById($q_id);
           $questions = $this->farmerModel->fetchQuestions();
           foreach($questions as $q){
              $q->answers = $this->consultantModel->fetchAnswers($q->id);
           }
           $data['edit_question'] = $question;
           $data['questions'] = $questions;
           $this->view('forum/index', $data);
       }
  } else {
       redirect('forums/index');
  }
}

// Delete a question (only allowed for the farmer who asked it)
public function deleteQuestion($q_id) {
  if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'farmer'){
       redirect('forums/index');
  }
  $question = $this->farmerModel->getQuestionById($q_id);
  if(!$question || $question->farmer_id != $_SESSION['user_id']){
       redirect('forums/index');
  }
  if($this->farmerModel->deleteQuestion($q_id)){
       flash('forum_message', 'Question deleted successfully');
       redirect('forums/index');
  } else {
       die('Something went wrong');
  }
}

}
  