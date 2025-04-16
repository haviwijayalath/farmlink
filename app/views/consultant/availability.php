<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebars/consultant.php'; ?>

<div class="container">
  <h1>Set Your Available Dates</h1>
  <!-- Navigation for months -->
  <div id="calendar-header">
    <button type="button" id="prevMonth" class="nav-btn">&lt;</button>
    <span id="currentMonthYear"></span>
    <button type="button" id="nextMonth" class="nav-btn">&gt;</button>
  </div>
  
  <form id="availabilityForm" action="<?= URLROOT ?>/consultants/setAvailability" method="POST">
    <!-- Hidden field to store the selected dates as JSON -->
    <input type="hidden" id="datesInput" name="dates" value="[]">
    <!-- Calendar container -->
    <div id="calendar" class="calendar"></div>
    <!-- Save button; it will be disabled if no changes have been made -->
    <button type="submit" id="saveBtn" class="btn-save">Save Availability</button>
  </form>
</div>

<style>
  body {
    font-family: Arial, sans-serif;
  }
  .container {
    margin-left: 220px; /* Adjust based on your sidebar */
    padding: 20px;
  }
  #calendar-header {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 10px;
  }
  .nav-btn {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 6px 12px;
    cursor: pointer;
    border-radius: 4px;
    margin: 0 10px;
  }
  #currentMonthYear {
    font-size: 1.25rem;
    font-weight: bold;
  }
  .calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
    max-width: 700px;
    margin: 0 auto 20px;
    border: 1px solid #ccc;
  }
  .calendar-header, .calendar-day {
    padding: 10px;
    text-align: center;
    border: 1px solid #ddd;
  }
  .calendar-header {
    background-color: #f5f5f5;
    font-weight: bold;
  }
  .calendar-day {
    cursor: pointer;
  }
  .calendar-day.inactive {
    background-color: #f9f9f9;
    color: #ccc;
    cursor: default;
  }
  .calendar-day.selected {
    background-color: #28a745;
    color: #fff;
  }
  .btn-save {
    display: block;
    margin: 20px auto;
    padding: 10px 20px;
    background-color: #28a745;
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
  }
  .btn-save:disabled {
    background-color: #aaa;
    cursor: not-allowed;
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  let selectedDates = [];
  
  // Retrieve preselected dates from the controller as JSON
  let preselected = <?= isset($data['preselected']) ? $data['preselected'] : '[]' ?>;
  // Clone the preselected dates array into our working array
  let preselectedDates = preselected.slice(); 
  selectedDates = preselected.slice();

  // Function to compare two arrays after sorting them.
  function arraysEqual(a, b) {
    if(a.length !== b.length) return false;
    // Create sorted copies
    let sortedA = a.slice().sort();
    let sortedB = b.slice().sort();
    for(let i = 0; i < sortedA.length; i++){
      if(sortedA[i] !== sortedB[i]) return false;
    }
    return true;
  }

  // Reference to the Save button
  const saveBtn = document.getElementById('saveBtn');

  // Update Save Button status based on whether changes are made
  function updateSaveButtonStatus() {
    if(arraysEqual(selectedDates, preselectedDates)) {
      // No changes
      saveBtn.disabled = true;
    } else {
      saveBtn.disabled = false;
    }
  }

  updateSaveButtonStatus(); // initial check

  // Calendar variables
  let calendarEl = document.getElementById('calendar');
  let currentDate = new Date(); // start with current date

  const currentMonthYearEl = document.getElementById('currentMonthYear');

  // Render the calendar for a given year and month
  function renderCalendar(year, month) {
    calendarEl.innerHTML = ''; // Clear calendar

    // Set header text
    const monthNames = [
      "January", "February", "March", "April", "May", "June",
      "July", "August", "September", "October", "November", "December"
    ];
    currentMonthYearEl.textContent = monthNames[month] + ' ' + year;

    // Add weekday headers
    const weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    weekdays.forEach(day => {
      let header = document.createElement('div');
      header.classList.add('calendar-header');
      header.textContent = day;
      calendarEl.appendChild(header);
    });

    // Determine first day of month and number of days
    let firstDayIndex = new Date(year, month, 1).getDay();
    let lastDate = new Date(year, month + 1, 0).getDate();
    let prevLastDate = new Date(year, month, 0).getDate();

    // Fill in previous month's blank days
    for (let i = 0; i < firstDayIndex; i++) {
      let blank = document.createElement('div');
      blank.classList.add('calendar-day', 'inactive');
      blank.textContent = prevLastDate - firstDayIndex + i + 1;
      calendarEl.appendChild(blank);
    }

    // Populate current month's days
    for (let d = 1; d <= lastDate; d++) {
      let dayEl = document.createElement('div');
      dayEl.classList.add('calendar-day');
      dayEl.textContent = d;
      
      // Format date string as "YYYY-MM-DD"
      let dateStr = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(d).padStart(2, '0');
      dayEl.dataset.date = dateStr;
      
      // Mark date as selected if it exists in selectedDates array
      if (selectedDates.indexOf(dateStr) !== -1) {
        dayEl.classList.add('selected');
      }
      
      // Add click event
      dayEl.addEventListener('click', function() {
        toggleDate(dayEl, dateStr);
        updateSaveButtonStatus();  // Update button status after change
      });
      
      calendarEl.appendChild(dayEl);
    }
  }

  // Toggle the selection status of a date
  function toggleDate(dayEl, dateStr) {
    let index = selectedDates.indexOf(dateStr);
    if (index > -1) {
      selectedDates.splice(index, 1);
      dayEl.classList.remove('selected');
    } else {
      selectedDates.push(dateStr);
      dayEl.classList.add('selected');
    }
    console.log("Selected dates: ", selectedDates);
  }

  // Setup month navigation
  document.getElementById('prevMonth').addEventListener('click', function() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar(currentDate.getFullYear(), currentDate.getMonth());
    updateSaveButtonStatus();
  });
  
  document.getElementById('nextMonth').addEventListener('click', function() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar(currentDate.getFullYear(), currentDate.getMonth());
    updateSaveButtonStatus();
  });

  // Initial render (using currentDate or preselected, if available)
  if(selectedDates.length > 0) {
    // If there are preselected dates, use the month of the first one
    currentDate = new Date(selectedDates[0]);
  }
  renderCalendar(currentDate.getFullYear(), currentDate.getMonth());

  // On form submission, save the selected dates
  document.getElementById('availabilityForm').addEventListener('submit', function() {
    document.getElementById('datesInput').value = JSON.stringify(selectedDates);
    console.log("Submitting dates: ", selectedDates);
  });
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
