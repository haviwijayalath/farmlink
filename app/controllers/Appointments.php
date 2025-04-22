<?php
class Appointments extends Controller {

  private $appointmentModel;
  private $consultantModel;
  private $farmerModel;

  public function __construct() {
    $this->appointmentModel = $this->model('Appointment');
    $this->consultantModel = $this->model('Consultant');
    $this->farmerModel = $this->model('Farmer');
  }

  public function book($consultant_id) {
    if (!isLoggedIn()) {
      redirect('users/login');
    }
    
    // Retrieve consultant details
    $consultant = $this->consultantModel->getConsultantById($consultant_id);
    if (!$consultant) {
      flash('appointment_error', 'Consultant not found', 'alert alert-danger');
      redirect('farmers/bookconsultant');
    }
    
    // Prepare data for the booking form.
    $data = [
      'consultant'      => $consultant,
      'appointment_date'=> '',
      'appointment_time'=> '',
      'notes'           => '',
      'date_err'      => '',
      'time_err'      => '',
      'notes_err'     => ''
    ];
    
    $this->view('appointment/book', $data);
  }


  public function create($consultant_id) {
    if (!isLoggedIn()) {
      redirect('users/login');
    }
    
    // Process only POST requests
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Sanitize POST data (dates, times, notes)
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      
      $data = [
        'consultant_id'    => $consultant_id,
        'farmer_id'        => $_SESSION['user_id'], // assuming the farmer is logged in
        'appointment_date' => trim($_POST['appointment_date']),
        'appointment_time' => trim($_POST['appointment_time']),
        'notes'            => trim($_POST['notes']),
        'date_err'         => '',
        'time_err'         => '',
        'notes_err'        => ''
      ];
      
      // Validate data
      if (empty($data['appointment_date'])) {
        $data['date_err'] = 'Please select an appointment date.';
      }
      if (empty($data['appointment_time'])) {
        $data['time_err'] = 'Please select an appointment time.';
      }
      
      // You can add other validation, e.g., check that the date is in an available slot.
      
      if (empty($data['date_err']) && empty($data['time_err'])) {
        if ($this->appointmentModel->bookAppointment($data)) {
          flash('appointment_success', 'Appointment booked successfully!');
          redirect('appointments/index'); // or to a confirmation page
        } else {
          die('Something went wrong. Please try again.');
        }
      } else {
        // Reload the booking form with errors and previously entered data
        $consultant = $this->consultantModel->getConsultantById($consultant_id);
        $data['consultant'] = $consultant;
        $this->view('appointment/book', $data);
      }
    } else {
      redirect('appointments/book/' . $consultant_id);
    }
  }

  public function index() {
    if (!isLoggedIn()) {
      redirect('users/login');
    }
    
    // Retrieve appointments for the current farmer.
    $appointments = $this->appointmentModel->getAppointmentsByFarmer($_SESSION['user_id']);
    $data = [
      'appointments' => $appointments
    ];
    $this->view('appointment/index', $data);
  }
  
  public function consultantAppointments() {
    if (!isLoggedIn()) {
      redirect('users/login');
    }
    
    $appointments = $this->appointmentModel->getAppointmentsByConsultant($_SESSION['user_id']);
    $data = ['appointments' => $appointments];
    $this->view('appointment/consultantAppointments', $data);
  }
  
  public function accept($appointment_id) {
    if (!isLoggedIn()) {
        redirect('users/login');
    }
    
    if ($this->appointmentModel->updateStatus($appointment_id, 'Accepted')) {
        flash('appointment_success', 'Appointment accepted successfully.');
    } else {
        flash('appointment_error', 'Failed to update appointment status.', 'alert alert-danger');
    }
    redirect('appointments/consultantAppointments');
}

public function decline($appointment_id) {
    if (!isLoggedIn()) {
        redirect('users/login');
    }
    
    if ($this->appointmentModel->updateStatus($appointment_id, 'Declined')) {
        flash('appointment_success', 'Appointment declined successfully.');
    } else {
        flash('appointment_error', 'Failed to update appointment status.', 'alert alert-danger');
    }
    redirect('appointments/consultantAppointments');
}

public function cancel($appointment_id) {
    if (!isLoggedIn()) {
        redirect('users/login');
    }
    
    // You can either update the status or cancel the appointment
    if ($this->appointmentModel->updateStatus($appointment_id, 'Cancelled')) {
        flash('appointment_success', 'Appointment cancelled successfully.');
    } else {
        flash('appointment_error', 'Failed to cancel appointment.', 'alert alert-danger');
    }
    redirect('appointments/consultantAppointments');
}

}
