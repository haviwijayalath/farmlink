<?php
class Farmers extends Controller
{
  private $farmerModel;
  private $notificationHelper;
  private $consultantModel;

  public function __construct()
  {
    $this->farmerModel = $this->model('Farmer');
    $this->notificationHelper = new NotificationHelper();
    $this->consultantModel = $this->model('Consultant');
  }

  public function register()
  {
    if (isLoggedIn()) {
      redirect('farmers/index');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'name' => trim($_POST['name']),
        'email' => trim($_POST['email']),
        'phone_number' => trim($_POST['phone_number']),
        'password' => trim($_POST['password']),
        'confirm_password' => trim($_POST['confirm_password']),
        'addr_no' => trim($_POST['addr_no']),
        'addr_street' => trim($_POST['addr_street']),
        'addr_city' => trim($_POST['addr_city']),
        'image' => isset($_FILES['image']) ? $_FILES['image'] : '',
        'id_card_front' => isset($_FILES['id_card_front']) ? $_FILES['id_card_front'] : '',
        'id_card_back' => isset($_FILES['id_card_back']) ? $_FILES['id_card_back'] : '',
        'name_err' => '',
        'email_err' => '',
        'phone_number_err' => '',
        'password_err' => '',
        'confirm_password_err' => '',
        'image_err' => '',
        'id_card_front_err' => '',
        'id_card_back_err' => ''
      ];

      // Validate Email
      if (empty($data['email'])) {
        $data['email_err'] = 'Please enter email';
      } else {
        // if email exists
        if ($this->farmerModel->findFarmerByEmail($data['email'])) {
          $data['email_err'] = 'This email is already taken';
        }
      }

      // Validate Name
      if (empty($data['name'])) {
        $data['name_err'] = 'Please enter name';
      }

      // Validate Phone Number
      if (empty($data['phone_number'])) {
        $data['phone_number_err'] = 'Please enter phone number';
      }

      // Validate Password
      if (empty($data['password'])) {
        $data['password_err'] = 'Please enter password';
      } elseif (strlen($data['password']) < 6) {
        $data['password_err'] = 'Password must be at least 6 characters';
      }

      // Validate Confirm Password
      if (empty($data['confirm_password'])) {
        $data['confirm_password_err'] = 'Please confirm password';
      } else {
        if ($data['password'] != $data['confirm_password']) {
          $data['confirm_password_err'] = 'Passwords do not match';
        }
      }

      // image saved directory
      $target_dir = APPROOT . '/../public/uploads/farmer/profile/';
      $filename = time() . basename($_FILES['image']['name']);
      $target_file = $target_dir . $filename;
      $_picuploaded = true;
      $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

      // Check if image file is a actual image or fake image
      $check = getimagesize($_FILES['image']['tmp_name']);
      if ($check !== false) {
        $_picuploaded = true;
      } else {
        $data['image_err'] = 'File is not an image';
        $_picuploaded = false;
      }

      // Check file size
      if ($_FILES['image']['size'] > 2000000) {
        $data['image_err'] = 'Your photo exceeds the size limit of 2MB';
        $_picuploaded = false;
      }

      // Allow certain file formats
      if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg') {
        $data['image_err'] = 'Please upload a photo with extension .jpg, .jpeg, or .png';
        $_picuploaded = false;
      }

      // Check if $_picuploaded is set to false
      if ($_picuploaded == false) {
        $data['image_err'] = 'Sorry, your file was not uploaded';
      } else {
        // if everything is ok, try to upload file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
          $data['image'] = $filename;
        } else {
          $data['image_err'] = 'Sorry, there was an error uploading your file';
        }
      }

      // Process ID card front image
      $id_front_uploaded = true;
      $id_front_filename = '';
      if(isset($_FILES['id_card_front']) && !empty($_FILES['id_card_front']['name'])) {
        $id_front_target_dir = APPROOT . '/../public/uploads/farmer/id_cards/';
        $id_front_filename = time() . '_front_' . basename($_FILES['id_card_front']['name']);
        $id_front_target_file = $id_front_target_dir . $id_front_filename;
        $id_front_FileType = strtolower(pathinfo($id_front_target_file, PATHINFO_EXTENSION));
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES['id_card_front']['tmp_name']);
        if($check === false) {
          $data['id_card_front_err'] = 'File is not an image';
          $id_front_uploaded = false;
        }
        
        // Check file size (2MB limit)
        if($_FILES['id_card_front']['size'] > 2000000) {
          $data['id_card_front_err'] = 'Your file exceeds the size limit of 2MB';
          $id_front_uploaded = false;
        }
        
        // Allow certain file formats
        if($id_front_FileType != 'jpg' && $id_front_FileType != 'png' && $id_front_FileType != 'jpeg') {
          $data['id_card_front_err'] = 'Please upload an image with extension .jpg, .jpeg, or .png';
          $id_front_uploaded = false;
        }
        
        // Upload the file if no errors
        if($id_front_uploaded) {
          if(!is_dir($id_front_target_dir)) {
            mkdir($id_front_target_dir, 0755, true);
          }
          if(move_uploaded_file($_FILES['id_card_front']['tmp_name'], $id_front_target_file)) {
            $data['id_card_front'] = $id_front_filename;
          } else {
            $data['id_card_front_err'] = 'Sorry, there was an error uploading your file';
            $id_front_uploaded = false;
          }
        }
      }

      // Process ID card back image
      $id_back_uploaded = true;
      $id_back_filename = '';
      if(isset($_FILES['id_card_back']) && !empty($_FILES['id_card_back']['name'])) {
        $id_back_target_dir = APPROOT . '/../public/uploads/farmer/id_cards/';
        $id_back_filename = time() . '_back_' . basename($_FILES['id_card_back']['name']);
        $id_back_target_file = $id_back_target_dir . $id_back_filename;
        $id_back_FileType = strtolower(pathinfo($id_back_target_file, PATHINFO_EXTENSION));
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES['id_card_back']['tmp_name']);
        if($check === false) {
          $data['id_card_back_err'] = 'File is not an image';
          $id_back_uploaded = false;
        }
        
        // Check file size (2MB limit)
        if($_FILES['id_card_back']['size'] > 2000000) {
          $data['id_card_back_err'] = 'Your file exceeds the size limit of 2MB';
          $id_back_uploaded = false;
        }
        
        // Allow certain file formats
        if($id_back_FileType != 'jpg' && $id_back_FileType != 'png' && $id_back_FileType != 'jpeg') {
          $data['id_card_back_err'] = 'Please upload an image with extension .jpg, .jpeg, or .png';
          $id_back_uploaded = false;
        }
        
        // Upload the file if no errors
        if($id_back_uploaded) {
          if(!is_dir($id_back_target_dir)) {
            mkdir($id_back_target_dir, 0755, true);
          }
          if(move_uploaded_file($_FILES['id_card_back']['tmp_name'], $id_back_target_file)) {
            $data['id_card_back'] = $id_back_filename;
          } else {
            $data['id_card_back_err'] = 'Sorry, there was an error uploading your file';
            $id_back_uploaded = false;
          }
        }
      }

      // Make sure no other errors before uploading the picture
      if (empty($data['name_err']) && empty($data['email_err']) && empty($data['phone_number_err']) && empty($data['password_err']) && empty($data['confirm_password_err']) && empty($data['image_err']) && empty($data['id_card_front_err']) && empty($data['id_card_back_err'])) {
        // hashing password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        // user registration
        if ($this->farmerModel->register($data)) {
          flash('register_success', 'You are successfully registered! Log in now');
          // redirect to login
          redirect('users/login');
        } else {
          die('Something went wrong! Please try again.');
        }
      } else {
        // Load view with errors
        $this->view('farmers/register', $data);
      }
    } else {
      // Init data
      $data = [
        'name' => '',
        'email' => '',
        'phone_number' => '',
        'image' => '',
        'password' => '',
        'confirm_password' => '',
        'addr_no' => '',
        'addr_street' => '',
        'addr_city' => '',
        'id_card_front' => '',
        'id_card_back' => '',

        'name_err' => '',
        'email_err' => '',
        'phone_number_err' => '',
        'password_err' => '',
        'confirm_password_err' => '',
        'image_err' => '',
        'id_card_front_err' => '',
        'id_card_back_err' => ''
      ];

      // Load view
      $this->view('farmers/register', $data);
    }
  }

  public function index()
  {
    if (!isLoggedIn() || $_SESSION['user_role'] != 'farmer') {
      redirect('users/login');
    }

    // to get total sales and orders
    $sales = $this->farmerModel->getSales();
    $monthlySales = $this->calMonthlySales($sales);
    $totals = $this->calTotalSalesTotalOrders($monthlySales); 

    // to get pending orders, recent orders, top product
    $orders = $this->farmerModel->getOrders();
    $pendingOrders = $this->farmerModel->getPendingOrders();
    $pendingOrdersCount = count($pendingOrders);

    // current stock count
    $currentStock = $this->farmerModel->getStocks();
    $currentStockCount = 0;
    foreach ($currentStock as $stock) {
      if ($stock->farmer_id != $_SESSION['user_id']) {
        continue;
      }
      $currentStockCount += $stock->stock;
    }

    // expiring stock count
    $expiringStock = $this->farmerModel->getExpiringStocks();
    $expiringStockCount = 0;
    foreach ($expiringStock as $stock) {
      if ($stock->farmer_id != $_SESSION['user_id']) {
        continue;
      }
      $expiringStockCount += $stock->stock;
    }

    // top products
    $topProducts = $this->farmerModel->getTopProducts();

    // total products
    $totalProducts = $this->farmerModel->getTotalProducts();

    // sales change
    $salesChange = $this->salesChange();

    $data = [
      'totalSales' => $totals['totalSales'],
      'salesChange' => $salesChange['percentage'],
      'totalOrders' => $totals['totalOrders'],
      'pendingOrders' => $pendingOrdersCount,
      'currentStock' => $currentStockCount,
      'expiringStockCount' => $expiringStockCount,
      'totalProducts' => $totalProducts,
      'topProducts' => $topProducts,
      'recentOrders' => array_slice($orders, 0, 5)
    ];

    $this->view('farmers/index', $data);
  }

  public function salesChange()
  {
    if (!isLoggedIn() || $_SESSION['user_role'] != 'farmer') {
      redirect('users/login');
    }

    $sales = $this->farmerModel->getSales();
    $monthlySales = $this->calMonthlySales($sales);
    
    // Sort monthly sales by date (most recent first)
    usort($monthlySales, function($a, $b) {
      return strtotime(date('Y-m', strtotime($b['month']))) - strtotime(date('Y-m', strtotime($a['month'])));
    });
    
    // Calculate percentage change between current and previous month
    $change = 0;
    $changePercent = 0;
    
    if (count($monthlySales) >= 2) {
      $currentMonth = $monthlySales[0]['totalFee'];
      $previousMonth = $monthlySales[1]['totalFee'];
      
      $change = $currentMonth - $previousMonth;
      
      if ($previousMonth > 0) {
        $changePercent = ($change / $previousMonth) * 100;
      } else if ($currentMonth > 0) {
        $changePercent = 100; // If previous month had 0 sales but current has sales
      }
    }
    
    return [
      'change' => $change,
      'percentage' => round($changePercent, 2),
      'isPositive' => $change >= 0
    ];
  }

  public function viewprofile()
  {
    if (!isLoggedIn() || $_SESSION['user_role'] != 'farmer') {
      redirect('users/login');
    }

    $farmer = $this->farmerModel->getFarmerbyId($_SESSION['user_id']);
    $data = [
      'name' => $farmer->name,
      'phone' => $farmer->phone,
      'email' => $farmer->email,
      'image' => $farmer->image,

      'name_err' => '',
      'email_err' => '',
      'phone_err' => '',
      'image_err' => ''
    ];

    $this->view('farmers/viewprofile', $data);
  }

  public function editprofile()
  {
    if (!isLoggedIn() || $_SESSION['user_role'] != 'farmer') {
      redirect('users/login');
    }

    // Check for POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Process form
      // Sanitize POST data
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'name' => trim($_POST['name']),
        'email' => trim($_POST['email']),
        'phone' => trim($_POST['phone']),
        'image' => isset($_POST['image']) ? $_POST['image'] : '',

        'name_err' => '',
        'email_err' => '',
        'phone_err' => '',
        'image_err' => ''
      ];

      // Validate Name
      if (empty($data['name'])) {
        $data['name_err'] = 'Please enter name';
      }

      // Validate Email
      if (empty($data['email'])) {
        $data['email_err'] = 'Please enter email';
      }

      // Validate Phone
      if (empty($data['phone'])) {
        $data['phone_err'] = 'Please enter phone number';
      }

      if (!empty($_FILES['image']['name'])) {
        // image saved directory
        $target_dir = APPROOT . '/../public/uploads/farmer/profile/';
        $filename = time() . basename($_FILES['image']['name']);
        $target_file = $target_dir . $filename;
        $_picuploaded = true;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check !== false) {
          $_picuploaded = true;
        } else {
          $data['image_err'] = 'File is not an image';
          $_picuploaded = false;
        }

        // Check file size
        if ($_FILES['image']['size'] > 2000000) {
          $data['image_err'] = 'Your photo exceeds the size limit of 2MB';
          $_picuploaded = false;
        }

        // Allow certain file formats
        if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg') {
          $data['image_err'] = 'Please upload a photo with extension .jpg, .jpeg, or .png';
          $_picuploaded = false;
        }

        // Check if $_picuploaded is set to false
        if ($_picuploaded == false) {
          $data['image_err'] = 'Sorry, your file was not uploaded';
        } else {
          // if everything is ok, try to upload file
          if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $data['image'] = $filename;
          } else {
            $data['image_err'] = 'Sorry, there was an error uploading your file';
          }
        }
      } else {
        // If image is not changed, keep the old image
        $data['image'] = $this->farmerModel->getFarmerbyId($_SESSION['user_id'])->image;
      }

      // user profile update
      if ($this->farmerModel->updateProfile($data)) {
        // flash('register_success', 'You are successfully registered! Log in now');
        // redirect to login
        redirect('farmers/index');
      } else {
        die('Something went wrong! Please try again.');
      }
    }
  }

  // In here all the data chexking is done by the controller. This should be done by using JS in front end. It should be done in the view file.
  public function changepassword()
  {
    //   if (!isLoggedIn() || $_SESSION['user_role'] != 'farmer') {
    //     redirect('users/login');
    //   }

    //   // Check for POST
    //   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //     // Process form
    //     // Sanitize POST data
    //     $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    //     $data = [
    //       'current_password' => trim($_POST['current-password']),
    //       'new_password' => trim($_POST['new-password']),
    //       'confirm_password' => trim($_POST['confirm-password']),

    //       'current_password_err' => '',
    //       'new_password_err' => '',
    //       'confirm_password_err' => ''
    //     ];

    //     // Validate Current Password
    //     if (empty($data['current_password'])) {
    //       $data['current_password_err'] = 'Please enter current password';
    //     } elseif (!$this->farmerModel->verifyPassword($data['current_password'], $_SESSION['user_id'])) {
    //       $data['current_password_err'] = 'Incorrect password';
    //     }

    //     // Validate New Password
    //     if (empty($data['new_password'])) {
    //       $data['new_password_err'] = 'Please enter new password';
    //     } elseif (strlen($data['new_password']) < 6) {
    //       $data['new_password_err'] = 'Password must be at least 6 characters';
    //     }

    //     // Validate Confirm Password
    //     if (empty($data['confirm_password'])) {
    //       $data['confirm_password_err'] = 'Please confirm password';
    //     } else {
    //       if ($data['new_password'] != $data['confirm_password']) {
    //         $data['confirm_password_err'] = 'Passwords do not match';
    //       }
    //     }

    //     // Make sure no other errors before uploading the picture
    //     if (empty($data['current_password_err']) && empty($data['new_password_err']) && empty($data['confirm_password_err'])) {
    //       // hashing password
    //       $data['new_password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);

    //       // user registration
    //       if ($this->farmerModel->changePassword($data['new_password'], $_SESSION['user_id'])) {
    //         flash('password_changed', 'Password changed successfully');
    //         // redirect to login
    //         redirect('farmers/viewprofile');
    //       } else {
    //         die('Something went wrong! Please try again.');
    //       }
    //     } else {
    //       // Load view with errors
    //       redirect('farmers/index', $data);
    //     }
    //   } else {
    //     // Init data
    //     $data = [
    //       'current_password' => '',
    //       'new_password' => '',
    //       'confirm_password' => '',

    //       'current_password_err' => '',
    //       'new_password_err' => '',
    //       'confirm_password_err' => ''
    //     ];
    //   }
  }

  public function managestocks()
  {
    if (!isLoggedIn()) {
      redirect('users/login');
    }

    $this->farmerModel->removeExpiredStocks();

    $data = $this->farmerModel->getStocks();
    $this->view('farmers/managestocks', $data);
  }

  public function addstocks()
  {
    if (!isLoggedIn()) {
      redirect('users/login');
    }

    // Check for POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Process form
      // Sanitize POST data
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'name' => trim($_POST['product_name']),
        'type' => trim($_POST['category']),
        'description' => trim($_POST['description']),
        'price' => trim($_POST['price']),
        'stock' => trim($_POST['quantity']),
        'exp_date' => trim($_POST['exp_date']),
        'image' => isset($_POST['image']) ? $_POST['image'] : '',

        'name_err' => '',
        'type_err' => '',
        'price_err' => '',
        'stock_err' => '',
        'exp_date_err' => '',
        'image_err' => ''
      ];

      // Validate Name
      if (empty($data['name'])) {
        $data['name_err'] = 'Please enter name';
      }

      // Validate Type
      if (empty($data['type'])) {
        $data['type_err'] = 'Please select a type';
      }

      // Validate Price
      if (empty($data['price']) && $data['price'] <= 0) {
        $data['price_err'] = 'Please enter a valid price';
      }

      // Validate Stock
      if (empty($data['stock']) && $data['stock'] <= 0) {
        $data['stock_err'] = 'Please enter a valid stock';
      }

      // Validate Expiry Date
      if (empty($data['exp_date'])) {
        $data['exp_date_err'] = 'Please enter expiry date';
      }

      // image saved directory
      $target_dir = APPROOT . '/../public/uploads/farmer/products/';
      $filename = time() . basename($_FILES['image']['name']);
      $target_file = $target_dir . $filename;
      $_picuploaded = true;
      $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

      // Check if image file is a actual image or fake image
      $check = getimagesize($_FILES['image']['tmp_name']);
      if ($check !== false) {
        $_picuploaded = true;
      } else {
        $data['image_err'] = 'File is not an image';
        $_picuploaded = false;
      }

      // Check file size
      if ($_FILES['image']['size'] > 2000000) {
        $data['image_err'] = 'Your photo exceeds the size limit of 2MB';
        $_picuploaded = false;
      }

      // Allow certain file formats
      if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg') {
        $data['image_err'] = 'Please upload a photo with extension .jpg, .jpeg, or .png';
        $_picuploaded = false;
      }

      // Check if $_picuploaded is set to false
      if ($_picuploaded == false) {
        $data['image_err'] = 'Sorry, your file was not uploaded';
      } else {
        // if everything is ok, try to upload file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
          $data['image'] = $filename;
        } else {
          $data['image_err'] = 'Sorry, there was an error uploading your file';
        }
      }

      // Make sure no other errors before uploading the picture
      if (empty($data['name_err']) && empty($data['type_err']) && empty($data['email_err']) && empty($data['phone_number_err']) && empty($data['password_err']) && empty($data['confirm_password_err']) && empty($data['image_err'])) {
        // Add stock to the database
        if ($this->farmerModel->addStock($data)) {
          flash('stock_message', 'Stock Added');
          $this->notificationHelper->send_notification('f', $_SESSION['user_id'], 'f', $_SESSION['user_id'], 'New stock added', 'New stock ' . $data['stock'] . 'kg of ' . $data['name'] . ' added', '/farmlink/farmers/managestocks', 'stock');
          
          // Notify buyers who wish to buy this product
          $buyers = $this->farmerModel->wishToBuyBuyers($data['name']);
          foreach ($buyers as $buyer) {
            $this->notificationHelper->send_notification('f', $_SESSION['user_id'], 'b', $buyer->buyer_id, 'Product Available', 'The product ' . $data['name'] . ' is now available in stock', '/farmlink/buyers/viewproduct/' . $data['name'], 'product');
          }

          redirect('farmers/managestocks');
        } else {
          die('Something went wrong');
        }
      } else {
        // Load view with errors
        $this->view('farmers/register', $data);
      }
    } else {
      // Init data
      $data = [
        'name' => '',
        'type' => '',
        'description' => '',
        'price' => '',
        'stock' => '',
        'exp_date' => '',
        'image' => '',

        'name_err' => '',
        'type_err' => '',
        'price_err' => '',
        'stock_err' => '',
        'exp_date_err' => '',
        'image_err' => ''
      ];

      // Load view
      $this->view('farmers/addstocks', $data);
    }
  }

  public function editstocks($id)
  {
    if (!isLoggedIn()) {
      redirect('users/login');
    }

    // Check for POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Process form
      // Sanitize POST data
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'name' => trim($_POST['product_name']),
        'type' => trim($_POST['category']),
        'description' => trim($_POST['description']),
        'price' => trim($_POST['price']),
        'stock' => trim($_POST['quantity']),
        'exp_date' => trim($_POST['exp_date']),
        'image' => isset($_POST['image']) ? $_POST['image'] : '',

        'name_err' => '',
        'type_err' => '',
        'price_err' => '',
        'stock_err' => '',
        'exp_date_err' => '',
        'image_err' => ''
      ];

      // Validate Name
      if (empty($data['name'])) {
        $data['name_err'] = 'Please enter name';
      }

      // Validate Type
      if (empty($data['type'])) {
        $data['type_err'] = 'Please select a type';
      }

      // Validate Price
      if (empty($data['price']) && $data['price'] <= 0) {
        $data['price_err'] = 'Please enter a valid price';
      }

      // Validate Stock
      if (empty($data['stock']) && $data['stock'] <= 0) {
        $data['stock_err'] = 'Please enter a valid stock';
      }

      // Validate Expiry Date
      if (empty($data['exp_date'])) {
        $data['exp_date_err'] = 'Please enter expiry date';
      }

      // Check if image is changed
      if (!empty($_FILES['image']['name'])) {
        // image saved directory
        $target_dir = APPROOT . '/../public/uploads/farmer/products/';
        $filename = time() . basename($_FILES['image']['name']);
        $target_file = $target_dir . $filename;
        $_picuploaded = true;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check !== false) {
          $_picuploaded = true;
        } else {
          $data['image_err'] = 'File is not an image';
          $_picuploaded = false;
        }

        // Check file size
        if ($_FILES['image']['size'] > 2000000) {
          $data['image_err'] = 'Your photo exceeds the size limit of 2MB';
          $_picuploaded = false;
        }

        // Allow certain file formats
        if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg') {
          $data['image_err'] = 'Please upload a photo with extension .jpg, .jpeg, or .png';
          $_picuploaded = false;
        }

        // Check if $_picuploaded is set to false
        if ($_picuploaded == false) {
          $data['image_err'] = 'Sorry, your file was not uploaded';
        } else {
          // if everything is ok, try to upload file
          if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $data['image'] = $filename;
          } else {
            $data['image_err'] = 'Sorry, there was an error uploading your file';
          }
        }
      } else {
        // If image is not changed, keep the old image
        $data['image'] = $this->farmerModel->getStockById($id)->image;
      }

      // Make sure no other errors before uploading the picture
      if (empty($data['name_err']) && empty($data['type_err']) && empty($data['email_err']) && empty($data['phone_number_err']) && empty($data['password_err']) && empty($data['confirm_password_err']) && empty($data['image_err'])) {
        // Add stock to the database
        if ($this->farmerModel->updateStock($id, $data)) {
          flash('stock_message', 'Stock Updated');
          redirect('farmers/managestocks');
        } else {
          die('Something went wrong');
        }
      } else {
        // Load view with errors
        $this->view('farmers/register', $data);
      }
    } else {
      // Init data
      $product = $this->farmerModel->getStockById($id);

      if ($product->farmer_id != $_SESSION['user_id']) {
        redirect('farmers/managestocks');
      }

      $data = [
        'id' => $id,
        'product_name' => $product->name,
        'category' => $product->type,
        'description' => $product->description,
        'price' => $product->price,
        'quantity' => $product->stock,
        'exp_date' => $product->exp_date,
        'image' => $product->image,

        'price_err' => '',
        'quantity_err' => '',
        'exp_date_err' => '',
        'image_err' => ''
      ];

      $this->view('farmers/editstocks', $data);
    }
  }

  public function deletestock($id)
  {
    if (!isLoggedIn()) {
      redirect('users/login');
    }

    $product = $this->farmerModel->getStockById($id);

    if ($product->farmer_id != $_SESSION['user_id']) {
      redirect('farmers/managestocks');
    }

    if ($this->farmerModel->deleteStock($id)) {
      flash('stock_message', 'Stock Removed');
      $this->notificationHelper->send_notification('f', $_SESSION['user_id'], 'f', $_SESSION['user_id'], 'Stock removed', 'Stock ' . $product->name . ' removed', '/farmlink/farmers/managestocks', 'stock');
      redirect('farmers/managestocks');
    } else {
      die('Something went wrong');
    }
  }

  public function manageorders()
  {
    if (!isLoggedIn()  || $_SESSION['user_role'] != 'farmer') {
      redirect('users/login');
    }

    $data = $this->farmerModel->getOrders();

    $this->view('farmers/manageorders', $data);
  }

  public function orderready()
  {
    if (!isLoggedIn() || $_SESSION['user_role'] != 'farmer') {
      redirect('users/login');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      $orderID = trim($_POST['order_id']);

      if ($this->farmerModel->orderReady($orderID)) {
        if ($this->farmerModel->deliveryRequested($orderID)) {
          // send a notification to the delivery person
          $msg = 'Order ' . $orderID . ' is ready for delivery';
          $url = '/farmlink/deliveryperson/manageorders';
          $this->notificationHelper->send_notification('f', $_SESSION['user_id'], 'd', $this->farmerModel->dpersonIdOfOrder($orderID), 'Order ready', $msg, $url, 'confirmation');
        } else {
          // send a notification to the buyer
          $msg = 'Order ' . $orderID . ' is ready for pickup';
          $url = '/farmlink/buyers/manageorders';
          $this->notificationHelper->send_notification('f', $_SESSION['user_id'], 'b', $this->farmerModel->buyerIdOfOrder($orderID), 'Order ready', $msg, $url, 'confirmation');
        }
        flash('order_message', 'Order marked as ready');
        redirect('farmers/manageorders');
      } else {
        die('Something went wrong');
      }
    }
  }

  public function calTotalSalesTotalOrders($monthlySales)
  {
    $totalSales = 0;
    $totalOrders = 0;
    
    if(!empty($monthlySales)) {
      foreach($monthlySales as $monthData) {
        $totalSales += $monthData['totalFee'];
        $totalOrders += count($monthData['orders']);
      }
    }

    return [
      'totalSales' => $totalSales,
      'totalOrders' => $totalOrders
    ];
  }

  public function calMonthlySales($sales) 
  {
    // Group sales by month and calculate sum of farmersFee
    $monthlySales = [];
    $salesByMonth = [];

    foreach ($sales as $sale) {
      $month = date('F Y', strtotime($sale->orderDate)); // Get month and year from orderDate
      
      if (!isset($salesByMonth[$month])) {
        $salesByMonth[$month] = [
          'totalFee' => 0,
          'orders' => []
        ];
      }
      
      // Add farmer's fee to the total for this month
      $salesByMonth[$month]['totalFee'] += $sale->famersFee;
      // Add the sale to the orders for this month
      $salesByMonth[$month]['orders'][] = $sale;
    }

    // Convert to array for the view
    foreach ($salesByMonth as $month => $data) {
      $monthlySales[] = [
        'month' => $month,
        'totalFee' => $data['totalFee'],
        'orders' => $data['orders']
      ];
    }

    return $monthlySales;
  }

  public function viewsales()
  {
    if (!isLoggedIn()) {
      redirect('users/login');
    }

    $sales = $this->farmerModel->getSales();

    $monthlySales = $this->calMonthlySales($sales);
    $totals = $this->calTotalSalesTotalOrders($monthlySales); 

    $data = [
      'monthlySales' => $monthlySales,
      'totalSales' => $totals['totalSales'],
      'totalOrders' => $totals['totalOrders']
    ];

    $this->view('farmers/viewsales', $data);
  }

  public function expstock()
  {
    if (!isLoggedIn() || $_SESSION['user_role'] != 'farmer') {
      redirect('users/login');
    }

    $data = $this->farmerModel->getExpiredStocks();
    $this->view('farmers/expstock', $data);
  }

  public function bookconsultant()
  {
    if (!isLoggedIn()) {
      redirect('users/login');
    }
    // Load the Consultant model
    $this->consultantModel = $this->model('Consultant');
    // Retrieve all consultants
    $consultants = $this->consultantModel->getConsultants();
    $data = [
      'consultants' => $consultants
    ];
    // Load the view for listing consultants
    $this->view('farmers/bookconsultant', $data);
  }
}
