document.addEventListener("DOMContentLoaded", function () {
    const dateInput = document.getElementById('date');
    const timeSelect = document.getElementById('time');
    const selectedTime = "<?php echo $row['available_time']; ?>"; // Pass selected time from PHP

    // Fetch available times whenever the date is selected or changed
    dateInput.addEventListener('change', function() {
        const selectedDate = dateInput.value;
        if (selectedDate) {
            fetchAvailableTimes(selectedDate);
        }
    });

    function fetchAvailableTimes(date) {
        const timeSelect = document.getElementById('time');
        timeSelect.innerHTML = ''; // Clear previous times

        fetch('getAvailableTimes.php?date=' + encodeURIComponent(date))
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    data.times.forEach(({ availableTimes_id, time }) => {
                        const option = document.createElement('option');
                        option.value = availableTimes_id; 
                        option.textContent = time;

                        // Pre-select the option if it matches the selected time
                        if (time === selectedTime) {
                            option.selected = true;
                        }

                        timeSelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'No available times for this date';
                    timeSelect.appendChild(option);
                }
            })
            .catch(error => {
                console.error('Error fetching available times:', error);
            });
    }

    // Trigger the initial fetch if there's a date set when the page loads
    if (dateInput.value) {
        fetchAvailableTimes(dateInput.value);
    }
});
