<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/farmers/inc/farmer_sidebar.php'; ?>

<div class="profile-and-calendar-container">
  <!-- Consultant Details Column (Smaller) -->
  <div class="profile-container">
    <h1>Consultant Profile</h1>
    <div class="profile-card">
      <div class="profile-image">
        <img src="<?= URLROOT ?>/public/uploads/consultants/<?= !empty($data['image']) ? $data['image'] : 'placeholder.png' ?>" alt="<?= htmlspecialchars($data['name']) ?>">
      </div>
      <div class="profile-details">
        <h2><?= htmlspecialchars($data['name']) ?></h2>
        <p><strong>Email:</strong> <?= htmlspecialchars($data['email']) ?></p>
        <p><strong>Specialization:</strong> <?= htmlspecialchars($data['specialization']) ?></p>
        <p><strong>Experience:</strong> <?= htmlspecialchars($data['experience']) ?> years</p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($data['phone']) ?></p>
        <!-- Make Appointment Button -->
        <a href="<?= URLROOT ?>/appointments/book/<?= $data['id'] ?>" class="btn-appointment">Make Appointment</a>
      </div>
    </div>
  </div>
  
  <!-- Availability Calendar Column (Larger and with more top margin) -->
  <div class="availability-container">
    <h2>Availability Calendar</h2>
    <!-- Navigation for months -->
    <div id="public-calendar-header">
      <button type="button" id="prevMonth" class="nav-btn">&lt;</button>
      <span id="currentMonthYear"></span>
      <button type="button" id="nextMonth" class="nav-btn">&gt;</button>
    </div>
    <div id="public-calendar" class="calendar"></div>
  </div>
</div>

<style>
  /* Flex container for profile details and calendar */
  .profile-and-calendar-container {
    display: flex;
    gap: 20px;
    margin-left: 220px; /* space for the sidebar */
    padding: 20px;
    align-items: flex-start;
  }
  
  /* Left column: consultant details – a bit smaller */
  .profile-container {
    flex: 0 0 55%;
  }
  
  .profile-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    display: flex;
    align-items: center;
    padding: 15px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  }
  .profile-image img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 15px;
  }
  .profile-details h2 {
    font-size: 1.5rem;
    margin-bottom: 8px;
  }
  .profile-details p {
    font-size: 16px;
    margin: 4px 0;
    color: #555;
  }
  /* Make Appointment Button */
  .btn-appointment {
    display: inline-block;
    padding: 8px 16px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    margin-top: 10px;
    transition: background-color 0.3s ease;
  }
  .btn-appointment:hover {
    background-color: #0056b3;
  }
  
  /* Right column: availability calendar – larger */
  .availability-container {
    flex: 1;
    max-width: 600px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-top: 90px; /* increased top margin */
  }
  
  /* Month navigation styling */
  #public-calendar-header {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 10px;
  }
  .nav-btn {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 6px 10px;
    cursor: default;
    border-radius: 4px;
    margin: 0 10px;
    font-size: 1rem;
  }
  #currentMonthYear {
    font-size: 1.2rem;
    font-weight: bold;
  }
  
  /* Calendar grid */
  .calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 3px;
    max-width: 600px;
    margin: 0 auto;
    border: 1px solid #ccc;
  }
  .calendar-header, .calendar-day {
    padding: 8px;
    text-align: center;
    border: 1px solid #ddd;
    font-size: 0.9rem;
  }
  .calendar-header {
    background-color: #f5f5f5;
    font-weight: bold;
  }
  .calendar-day {
    cursor: default;
  }
  .calendar-day.inactive {
    background-color: #f9f9f9;
    color: #ccc;
  }
  .calendar-day.selected {
    background-color: #28a745;
    color: #fff;
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Ensure availability data is parsed properly as an array of dates
  let selectedDates = JSON.parse('<?= isset($data['availability']) ? $data['availability'] : '[]' ?>');
  
  let calendarEl = document.getElementById('public-calendar');
  let currentMonthYearEl = document.getElementById('currentMonthYear');
  
  // Set currentDate using first available date (if any) or today
  let currentDate = selectedDates.length > 0 ? new Date(selectedDates[0]) : new Date();
  
  function renderCalendar(year, month) {
    calendarEl.innerHTML = ''; // Clear the calendar
    
    const monthNames = [
      "January", "February", "March", "April", "May", "June",
      "July", "August", "September", "October", "November", "December"
    ];
    currentMonthYearEl.textContent = `${monthNames[month]} ${year}`;
    
    // Create weekday headers
    const weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    weekdays.forEach(day => {
      let header = document.createElement('div');
      header.classList.add('calendar-header');
      header.textContent = day;
      calendarEl.appendChild(header);
    });
    
    let firstDayIndex = new Date(year, month, 1).getDay();
    let lastDate = new Date(year, month + 1, 0).getDate();
    let prevLastDate = new Date(year, month, 0).getDate();
    
    // Fill in blanks for previous month
    for (let i = 0; i < firstDayIndex; i++) {
      let blank = document.createElement('div');
      blank.classList.add('calendar-day', 'inactive');
      blank.textContent = prevLastDate - firstDayIndex + i + 1;
      calendarEl.appendChild(blank);
    }
    
    // Populate current month days
    for (let d = 1; d <= lastDate; d++) {
      let dayEl = document.createElement('div');
      dayEl.classList.add('calendar-day');
      dayEl.textContent = d;
      
      let dateStr = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(d).padStart(2, '0');
      dayEl.dataset.date = dateStr;
      
      if (selectedDates.indexOf(dateStr) !== -1) {
        dayEl.classList.add('selected');
      }
      
      calendarEl.appendChild(dayEl);
    }
  }
  
  document.getElementById('prevMonth').addEventListener('click', function() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar(currentDate.getFullYear(), currentDate.getMonth());
  });
  
  document.getElementById('nextMonth').addEventListener('click', function() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar(currentDate.getFullYear(), currentDate.getMonth());
  });
  
  renderCalendar(currentDate.getFullYear(), currentDate.getMonth());
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
