<?php
class Appointments extends Controller {
  private $appointmentModel;
  private $consultantModel;
  private $farmerModel;
  private $notificationHelper;

  public function __construct() {
    $this->appointmentModel  = $this->model('Appointment');
    $this->consultantModel   = $this->model('Consultant');
    $this->farmerModel       = $this->model('Farmer');
    $this->notificationHelper= new NotificationHelper();
  }

  public function book($consultant_id) {
    if (!isLoggedIn()) redirect('users/login');
    $consultant = $this->consultantModel->getConsultantById($consultant_id);
    if (!$consultant) redirect('pages/notfound');

    // Pull available dates from Appointment model:
    $slots = $this->appointmentModel->getAvailability($consultant_id);
    $dates = array_map(fn($s) => $s->available_date, $slots);

    $data = [
      'consultant'     => $consultant,
      'availableDates' => $dates,
      'appointment_date'=> '',
      'appointment_time'=> '',
      'notes'          => '',
      'date_err'       => '',
      'time_err'       => '',
      'notes_err'      => ''
    ];
    $this->view('appointment/book', $data);
  }

  /**
   * Handle the form POST.  Only allow dates in $availableDates.
   */
  public function create($consultant_id) {
    if (!isLoggedIn()) redirect('users/login');
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      return redirect("appointments/book/$consultant_id");
    }

    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    $date = trim($_POST['appointment_date']);
    $time = trim($_POST['appointment_time']);
    $notes = trim($_POST['notes']);

    // re-fetch availability
    $slots = $this->appointmentModel->getAvailability($consultant_id);
    $dates = array_map(fn($s) => $s->available_date, $slots);

    $data = [
      'consultant_id'    => $consultant_id,
      'farmer_id'        => $_SESSION['user_id'],
      'appointment_date' => $date,
      'appointment_time' => $time,
      'notes'            => $notes,
      'availableDates'   => $dates,
      'date_err'         => '',
      'time_err'         => ''
    ];

    // Validate presence
    if (empty($date)) {
      $data['date_err'] = 'Please select a date.';
    }
    // Validate that the chosen date is actually in the consultant’s availability
    elseif (!in_array($date, $dates)) {
      $data['date_err'] = 'That date is not available. Please choose one from the list.';
    }

    if (empty($time)) {
      $data['time_err'] = 'Please select a time.';
    }

    if (empty($data['date_err']) && empty($data['time_err'])) {
      if ($this->appointmentModel->bookAppointment($data)) {
        $lastId = $this->appointmentModel->getLastInsertId();
        $appt   = $this->appointmentModel->getAppointmentById($lastId);

        // 2) fetch farmer name (so we can personalize the notification)
        $farmer = $this->farmerModel->getFarmerById($_SESSION['user_id']);
        $farmerName = $farmer->name ?? 'Farmer';

        // 3) build & send notification to the consultant
        $subject = "New appointment request";
        $content = "{$farmerName} requested ";
        $content .= "a slot on " . date('M d, Y', strtotime($appt->appointment_date))
                  . " at {$appt->appointment_time}.";
        $url     = URLROOT . "/appointments/consultantAppointments";

        $this->notificationHelper->send_notification(
          'f', $appt->farmer_id,
          'c', $appt->consultant_id,
          $subject, $content, $url, 'info'
        );
        flash('appointment_success','Appointment booked!');
        return redirect('appointments/index');
      }
      die('Something went wrong.');
    }

    // on error, re-load the form
    $data['consultant'] = $this->consultantModel->getConsultantById($consultant_id);
    $this->view('appointment/book', $data);
  }

  // Consultant approves
  public function accept($appointment_id) {
    if (!isLoggedIn()) redirect('users/login');
    if ($this->appointmentModel->updateStatus($appointment_id, 'Accepted')) {
      $appt = $this->appointmentModel->getAppointmentById($appointment_id);

      // notify farmer
      $consultantName = $_SESSION['user_name'] ?? 'Consultant';
      $subject        = "Appointment accepted";
      $content        = "{$consultantName} accepted your appointment on "
                      . date('M d, Y', strtotime($appt->appointment_date))
                      . " at {$appt->appointment_time}.";
      $url            = URLROOT . "/appointments/index";

      $this->notificationHelper->send_notification(
        'c', $appt->consultant_id,
        'f', $appt->farmer_id,
        $subject, $content, $url, 'success'
      );

      flash('appointment_success','Appointment accepted.');
    } else {
      flash('appointment_error','Could not update.', 'alert alert-danger');
    }
    redirect('appointments/consultantAppointments');
  }

  // Consultant declines
  public function decline($appointment_id) {
    if (!isLoggedIn()) redirect('users/login');
    if ($this->appointmentModel->updateStatus($appointment_id, 'Declined')) {
      $appt = $this->appointmentModel->getAppointmentById($appointment_id);

      // notify farmer
      $consultantName = $_SESSION['user_name'] ?? 'Consultant';
      $subject        = "Appointment declined";
      $content        = "{$consultantName} declined your appointment on "
                      . date('M d, Y', strtotime($appt->appointment_date))
                      . " at {$appt->appointment_time}.";
      $url            = URLROOT . "/appointments/index";

      $this->notificationHelper->send_notification(
        'c', $appt->consultant_id,
        'f', $appt->farmer_id,
        $subject, $content, $url, 'warning'
      );

      flash('appointment_success','Appointment declined.');
    } else {
      flash('appointment_error','Could not update.', 'alert alert-danger');
    }
    redirect('appointments/consultantAppointments');
  }

  // Either party cancels
  public function cancel($appointment_id) {
    if (!isLoggedIn()) redirect('users/login');
    if ($this->appointmentModel->updateStatus($appointment_id, 'Cancelled')) {
      $appt = $this->appointmentModel->getAppointmentById($appointment_id);

      // decide direction
      if ($_SESSION['user_type'] === 'consultant') {
        // consultant → farmer
        $fromType  = 'c';  $toType = 'f';
        $fromId    = $appt->consultant_id;
        $toId      = $appt->farmer_id;
        $who       = 'Consultant';
      } else {
        // farmer → consultant
        $fromType  = 'f';  $toType = 'c';
        $fromId    = $appt->farmer_id;
        $toId      = $appt->consultant_id;
        $who       = 'Farmer';
      }

      $subject = "Appointment cancelled";
      $content = "{$who} cancelled the appointment on "
               . date('M d, Y', strtotime($appt->appointment_date))
               . " at {$appt->appointment_time}.";
      $url     = ($_SESSION['user_type']==='consultant')
               ? URLROOT . "/appointments/consultantAppointments"
               : URLROOT . "/appointments/index";

      $this->notificationHelper->send_notification(
        $fromType, $fromId,
        $toType,   $toId,
        $subject,  $content, $url, 'info'
      );

      flash('appointment_success','Appointment cancelled.');
    } else {
      flash('appointment_error','Could not cancel.', 'alert alert-danger');
    }
    redirect('appointments/consultantAppointments');
  }

  // farmer’s list
  public function index() {
    if (!isLoggedIn()) redirect('users/login');
    $appts = $this->appointmentModel->getAppointmentsByFarmer($_SESSION['user_id']);
    $this->view('appointment/index', ['appointments'=>$appts]);
  }

  // consultant’s list
  public function consultantAppointments() {
    if (!isLoggedIn()) redirect('users/login');
    $appts = $this->appointmentModel->getAppointmentsByConsultant($_SESSION['user_id']);
    $this->view('appointment/consultantAppointments', ['appointments'=>$appts]);
  }
}
