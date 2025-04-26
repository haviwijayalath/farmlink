<?php
class Consultant {
  private $db;

  public function __construct() {
    $this->db = new Database;
  }

  // Save address.
  public function saveAddress($no, $street, $city) {
    $this->db->query('INSERT INTO address (number, Street, City) VALUES(:number, :street, :city)');
    $this->db->bind(':number', $no);
    $this->db->bind(':street', $street);
    $this->db->bind(':city', $city);
    if ($this->db->execute()) {
      return $this->db->lastInsertId();
    } else {
      return false;
    }
  }

  // Register consultant.
  public function register($data) {
    $address_id = $this->saveAddress($data['addr_no'], $data['addr_street'], $data['addr_city']);
    $this->db->query('INSERT INTO consultants (name, password, email, address_id, phone, image, specialization, experience, verification_doc) VALUES(:name, :password, :email, :address_id, :phone, :image, :specialization, :experience, :verification_doc)');
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':phone', $data['phone_number']);
    $this->db->bind(':image', $data['image']);
    $this->db->bind(':password', $data['password']);
    $this->db->bind(':address_id', $address_id);
    $this->db->bind(':specialization', $data['specialization']);
    $this->db->bind(':experience', $data['experience']);
    $this->db->bind(':verification_doc', $data['verification_doc']);
    return $this->db->execute();
  }

  // Find user by email across several tables.
  public function findUserByEmail($email) {
    $tables = ['farmers', 'buyers', 'consultants', 'suppliers', 'delivery_persons'];
    foreach ($tables as $table) {
      $this->db->query("SELECT * FROM $table WHERE email = :email");
      $this->db->bind(':email', $email);
      $row = $this->db->single();
      if ($this->db->rowCount() > 0) {
        return true;
      }
    }
    return false;
  }

  // Login consultant.
  public function login($email, $password) {
    $this->db->query('SELECT * FROM consultants WHERE email = :email');
    $this->db->bind(':email', $email);
    $row = $this->db->single();
    if ($row && password_verify($password, $row->password)) {
      return $row;
    } else {
      return false;
    }
  }

  // Get consultant by id.
  public function getConsultantById($id) {
    $this->db->query('SELECT * FROM consultants WHERE id = :id');
    $this->db->bind(':id', $id);
    return $this->db->single();
  }

  public function updateConsultant($data) {
    $this->db->query("
        UPDATE consultants 
        SET 
            name = :name,
            email = :email,
            specialization = :specialization,
            experience = :experience,
            address = :address,
            image = :image,
            verification_doc = :verification_doc,
            password = :password
        WHERE id = :id
    ");
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':specialization', $data['specialization']);
    $this->db->bind(':experience', $data['experience']);
    $this->db->bind(':address', $data['address']);
    $this->db->bind(':image', $data['image']);
    $this->db->bind(':verification_doc', $data['verification_doc']);
    $this->db->bind(':password', $data['password']);
    $this->db->bind(':id', $data['id']);
    
    return $this->db->execute();
}

  // Fetch all questions from the questions table
  public function fetchQuestions() {
    $this->db->query('SELECT q_id, farmer_id, questions FROM forum_questions');

    // Return the result set as an array of objects
    return $this->db->resultSet();
  }

public function storeAnswer($data) {
  $this->db->query("
    INSERT INTO forum_answers (q_id, consultant_id, answer, createdAt)
    VALUES (:q_id, :consultant_id, :answer, NOW())
  ");
  // Assume the logged-in consultant's ID is stored in $_SESSION['user_id']
  $this->db->bind(':q_id', $data['question_id']);
  $this->db->bind(':consultant_id', $_SESSION['user_id']);
  $this->db->bind(':answer', $data['answer']);
  return $this->db->execute();
}

public function fetchAnswers($question_id) {
  $this->db->query("
    SELECT 
      fa.ans_id, 
      fa.answer, 
      fa.createdAt,
      fa.consultant_id, 
      c.name AS consultant_name,
      c.image AS consultant_profile_picture
    FROM forum_answers fa
    JOIN consultants c ON fa.consultant_id = c.id
    WHERE fa.q_id = :q_id
    ORDER BY fa.createdAt ASC
  ");
  $this->db->bind(':q_id', $question_id);
  return $this->db->resultSet();
}

public function getAnswerById($ans_id) {
  $this->db->query("SELECT * FROM forum_answers WHERE ans_id = :ans_id AND consultant_id = :consultant_id");
  $this->db->bind(':ans_id', $ans_id);
  $this->db->bind(':consultant_id', $_SESSION['user_id']);
  return $this->db->single();
}


// Update an existing answer
public function updateAnswer($ans_id, $data) {
  $this->db->query("
    UPDATE forum_answers 
    SET answer = :answer
    WHERE ans_id = :ans_id
      AND consultant_id = :consultant_id
  ");
  $this->db->bind(':answer', $data['answer']);
  $this->db->bind(':ans_id', $ans_id);
  // Ensure only the consultant who submitted can update
  $this->db->bind(':consultant_id', $_SESSION['user_id']);
  return $this->db->execute();
}

// Delete an answer
public function deleteAnswer($ans_id) {
  $this->db->query("
    DELETE FROM forum_answers 
    WHERE ans_id = :ans_id
      AND consultant_id = :consultant_id
  ");
  $this->db->bind(':ans_id', $ans_id);
  $this->db->bind(':consultant_id', $_SESSION['user_id']);
  return $this->db->execute();
}

public function getConsultants() {
  $this->db->query("SELECT id, name, email, specialization, experience, image FROM consultants ORDER BY name ASC");
  return $this->db->resultSet();
}

public function getAvailability($consultant_id) {
  $this->db->query("SELECT available_date FROM consultant_availability WHERE consultant_id = :consultant_id");
  $this->db->bind(':consultant_id', $consultant_id);
  return $this->db->resultSet();
}

public function deleteAvailability($consultant_id) {
  $this->db->query("DELETE FROM consultant_availability WHERE consultant_id = :consultant_id");
  $this->db->bind(':consultant_id', $consultant_id);
  return $this->db->execute();
}

public function addAvailability($consultant_id, $date) {
  $this->db->query("INSERT INTO consultant_availability (consultant_id, available_date) VALUES (:consultant_id, :available_date)");
  $this->db->bind(':consultant_id', $consultant_id);
  $this->db->bind(':available_date', $date);
  return $this->db->execute();
}

public function addPost($data) {
    // Insert post
    $this->db->query("INSERT INTO posts (consultant_id, content) VALUES (:c_id, :content)");
    $this->db->bind(':c_id',     $data['consultant_id']);
    $this->db->bind(':content',  $data['content']);
    if (!$this->db->execute()) return false;

    $post_id = $this->db->lastInsertId();

    // Handle file uploads if any
    if (!empty($data['attachments'])) {
      foreach ($data['attachments'] as $file) {
          $raw = file_get_contents($file['tmp_name']);
        
          $this->db->query("
            INSERT INTO post_attachments 
              (post_id, filename, mime_type, file_data) 
            VALUES 
              (:post_id, :fname, :mime, :fdata)
          ");
          $this->db->bind(':post_id', $post_id);
          $this->db->bind(':fname',   $file['name']);
          $this->db->bind(':mime',   $file['type']);
          $this->db->bind(':fdata', $raw, PDO::PARAM_LOB);
          $this->db->execute();
        
      }
    }

    return true;
  }

// fetch all posts by this consultant
public function getPostsByConsultant($consultant_id) {
  $this->db->query("
    SELECT post_id, content, created_at
      FROM posts
      WHERE consultant_id = :cid
      ORDER BY created_at DESC
  ");
  $this->db->bind(':cid',$consultant_id);
  return $this->db->resultSet();
}

// fetch attachments for a given post
public function getPostAttachments($post_id) {
  $this->db->query("
    SELECT filename, mime_type, file_data
      FROM post_attachments
      WHERE post_id = :pid
  ");
  $this->db->bind(':pid',$post_id);
  return $this->db->resultSet();
}

public function getAverageRating($consultantId) {
  $this->db->query("SELECT AVG(rating) AS avg_rating FROM consultant_ratings WHERE consultant_id = :id");
  $this->db->bind(':id', $consultantId);
  return $this->db->single()->avg_rating ?? 0;
}

public function getUserRating($consultantId, $farmerId) {
  $this->db->query("SELECT rating FROM consultant_ratings WHERE consultant_id = :cid AND farmer_id = :fid");
  $this->db->bind(':cid', $consultantId);
  $this->db->bind(':fid', $farmerId);
  $row = $this->db->single();
  return $row ? $row->rating : 0;
}

public function rateConsultant($consultantId, $farmerId, $rating) {
  $this->db->query("REPLACE INTO consultant_ratings (consultant_id, farmer_id, rating) VALUES (:cid, :fid, :rating)");
  $this->db->bind(':cid', $consultantId);
  $this->db->bind(':fid', $farmerId);
  $this->db->bind(':rating', $rating);
  return $this->db->execute();
}
  
}