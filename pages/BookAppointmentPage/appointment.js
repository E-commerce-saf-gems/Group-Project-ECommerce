document.querySelector("form").addEventListener("submit", function (event) {
    if (!validateEmail()) {
        event.preventDefault(); 
    }
});




function validateEmail() {
    const email = document.getElementById("email");
    const emailError = document.getElementById("email-error");
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    
    emailError.textContent = "";

    
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
    let selectedDate = this.value; 
    if (selectedDate) {
        fetchAvailableTimes(selectedDate);
    }
});

function fetchAvailableTimes(date) {
    const timeSelect = document.getElementById('time');
    timeSelect.innerHTML = ''; 

    fetch('getAvailableTimes.php?date=' + encodeURIComponent(date))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                
                data.times.forEach(({ availableTime_id, time }) => {
                    console.log('Adding option:', availableTime_id, time);
                
                    const option = document.createElement('option');
                    option.value = availableTime_id; 
                    option.textContent = time; 
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

    
    const dateInput = document.getElementById('date');
    const today = new Date().toISOString().split('T')[0];
    dateInput.setAttribute('min', today);