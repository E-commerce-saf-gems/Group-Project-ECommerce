document.querySelector("form").addEventListener("submit", function (event) {
    if (!validateEmail()) {
        event.preventDefault(); // Prevent form submission if email is invalid
    }
});



// Validate email function
function validateEmail() {
    const email = document.getElementById("email");
    const emailError = document.getElementById("email-error");
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    // Clear previous error messages
    emailError.textContent = "";

    // Validate email
    if (!emailPattern.test(email.value)) {
        emailError.textContent = "Please enter a valid email address";
        emailError.style.color = "red";
        email.classList.add("error");
        email.focus();
        return false;
    } else {
        email.classList.remove("error");
        return true;
    }
}

  document.getElementById("email").addEventListener("input", validateEmail );
  document.getElementById("email").addEventListener("blur", validateEmail );

  setTimeout(function() {
    const message = document.querySelector(".success-message");
    if (message) {
        message.style.display = "none";
    }
}, 5000);

document.getElementById('date').addEventListener('change', function() {
    let selectedDate = this.value; // Get the selected date
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
                // Populate the time dropdown with available times
                data.times.forEach(({ availableTime_id, time }) => {
                    console.log('Adding option:', availableTime_id, time);
                
                    const option = document.createElement('option');
                    option.value = availableTime_id; // This should be the ID
                    option.textContent = time; // This should be the display text
                    timeSelect.appendChild(option);
                });
                
            } else {
                // If no times available, inform the user
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
