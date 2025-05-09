document.addEventListener('DOMContentLoaded', function() {
    const updateButtons = document.querySelectorAll('.update-button');

    // Handling edit buttons (for billing and shipping addresses)
    const editButtons = document.querySelectorAll('.edit-button');
    editButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            alert('This functionality is not yet implemented.');
        });
    });

    // Toggle tab content for My Bids page
    const tabLinks = document.querySelectorAll('.tab-link');
    tabLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const activeTab = document.querySelector('.tab-link.active');
            if (activeTab) {
                activeTab.classList.remove('active');
            }
            this.classList.add('active');

            // Assuming tab contents have an ID that matches the text of the tab link (e.g., #active-bids, #historic-bids)
            const contentId = this.textContent.trim().toLowerCase().replace(' ', '-');
            const activeContent = document.querySelector('.tab-content.active');
            if (activeContent) {
                activeContent.classList.remove('active');
            }
            document.getElementById(contentId).classList.add('active');
        });
    });
});

function showSection(sectionId) {
    // Hide all sections
    const sections = document.querySelectorAll('.section');
    sections.forEach(section   => {
        section.style.display = 'none';
    });

    // Show the selected section
    const selectedSection = document.getElementById(sectionId);
    selectedSection.style.display = 'block';   

}

//profile page
document.addEventListener("DOMContentLoaded", function () {
    // Handling the update buttons (for updating details and password)
    const updateButtons = document.querySelectorAll('.update-button');

    updateButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent form submission

            const form = this.closest('form');

            if (form.classList.contains('details-form')) {
                // Handling update details form
                const firstName = document.getElementById("firstName").value.trim();
                const lastName = document.getElementById("lastName").value.trim();
                const email = document.getElementById("email").value.trim();
                const phone = document.getElementById("phone").value.trim();

                let validationMessage = "";

                if (firstName === "") {
                    validationMessage += "First Name is required.\n";
                }
                if (lastName === "") {
                    validationMessage += "Last Name is required.\n";
                }
                if (email === "") {
                    validationMessage += "Email Address is required.\n";
                }
                if (phone === "") {
                    validationMessage += "Phone Number is required.\n";
                }

                if (validationMessage) {
                    alert(validationMessage);
                } else {
                    alert('Your details have been updated successfully.');
                    // Here you would normally submit the form or perform an AJAX request
                }
            } else if (form.classList.contains('password-form')) {
                // Handling update password form
                const newPassword = document.getElementById('newPassword');
                const confirmPassword = document.getElementById('confirmPassword');

                // Clear any previous error messages
                clearPasswordErrors(newPassword, confirmPassword);

                let validationMessage = validatePassword(newPassword.value.trim(), confirmPassword.value.trim());

                if (validationMessage) {
                    displayPasswordErrors(newPassword, confirmPassword, validationMessage);
                } else {
                    alert('Your password has been updated successfully.');
                    // Here you would normally submit the form or perform an AJAX request
                }
            }
        });
    });

    // Function to validate the password
    function validatePassword(newPassword, confirmPassword) {
        const capitalLetterRegex = /[A-Z]/;
        const lowercaseLetterRegex = /[a-z]/;
        const numberRegex = /[0-9]/;
        const specialCharRegex = /[!@#$%^&*(),.?":{}|<>]/;
        let validationMessage = "";

        if (newPassword.length < 8) {
            validationMessage += "Password must be at least 8 characters long.\n";
        }
        if (!capitalLetterRegex.test(newPassword)) {
            validationMessage += "Password must contain at least one capital letter.\n";
        }
        if (!lowercaseLetterRegex.test(newPassword)) {
            validationMessage += "Password must contain at least one lowercase letter.\n";
        }
        if (!numberRegex.test(newPassword)) {
            validationMessage += "Password must contain at least one number.\n";
        }
        if (!specialCharRegex.test(newPassword)) {
            validationMessage += "Password must contain at least one special character.\n";
        }
        if (newPassword !== confirmPassword) {
            validationMessage += "Passwords do not match.\n";
        }

        return validationMessage;
    }

    // Function to clear previous error messages
    function clearPasswordErrors(newPassword, confirmPassword) {
        newPassword.classList.remove('error');
        confirmPassword.classList.remove('error');
        newPassword.value = '';
        confirmPassword.value = '';
    }

    // Function to display error messages inside text boxes
    function displayPasswordErrors(newPassword, confirmPassword, message) {
        newPassword.classList.add('error');
        confirmPassword.classList.add('error');
        newPassword.placeholder = message.split('\n')[0];
        confirmPassword.placeholder = message.split('\n').slice(1).join(' ');
    }
    
});

//subscribe button
const subscribeForm = document.querySelector('.subscription-form');
    const nameInput = subscribeForm.querySelector('input[type="text"]');
    const emailInput = subscribeForm.querySelector('input[type="email"]');
    const subscribeButton = subscribeForm.querySelector('button');

    // Function to validate email
    function isValidEmail(email) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
    }

    // Handle form submission
    subscribeButton.addEventListener('click', (e) => {
        e.preventDefault(); // Prevent default button behavior

        // Get values from inputs
        const name = nameInput.value.trim();
        const email = emailInput.value.trim();

        // Validate inputs
        if (name === '') {
            alert('Please enter your name.');
            return;
        }

        if (email === '') {
            alert('Please enter your email address.');
            return;
        }

        if (!isValidEmail(email)) {
            alert('Please enter a valid email address.');
            return;
        }

        // Display a success message
        alert('Thank you for subscribing!');

        // Optionally, clear the form
        nameInput.value = '';
        emailInput.value = '';
    });

    //star sapphire
    document.addEventListener('DOMContentLoaded', function () {
        // Add event listeners to the buttons
        document.querySelector('button[onclick="window.location.href=\'#attributs\'"]').addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector('#attributs').scrollIntoView({ behavior: 'smooth' });
        });

        document.querySelector('button[onclick="window.location.href=\'#valeur\'"]').addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector('#valeur').scrollIntoView({ behavior: 'smooth' });
        });

        document.querySelector('button[onclick="window.location.href=\'#couleurs\'"]').addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector('#couleurs').scrollIntoView({ behavior: 'smooth' });
        });

        document.querySelector('button[onclick="window.location.href=\'#proprietes\'"]').addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector('#proprietes').scrollIntoView({ behavior: 'smooth' });
        });

        document.querySelector('button[onclick="window.location.href=\'#soins\'"]').addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector('#soins').scrollIntoView({ behavior: 'smooth' });
        });
    });


    document.getElementById("editGemsForm").addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent form submission by default
        console.log("Form submission event triggered"); // Log to ensure event is triggered
    
        // Get form fields
        const date = document.getElementById("date").value;
        const shape = document.getElementById("shape").value.trim();
        const type = document.getElementById("type").value.trim();
        const weight = document.getElementById("weight").value.trim();
        const color = document.getElementById("color").value.trim();
        const special = document.getElementById("special").value.trim();
        
        console.log(date, shape, type, weight, color, special); // Check values
    
        // Validation Flags
        let isValid = true;
        let errorMessage = "";
    
        // Date validation: Check if date is not empty
        if (!date) {
            isValid = false;
            errorMessage += "Please enter a valid date.\n";
        }
    
        // Shape validation: Check if shape is a non-empty string
        if (shape === "") {
            isValid = false;
            errorMessage += "Please enter a shape.\n";
        }
    
        // Type validation: Check if gem type is a non-empty string
        if (type === "") {
            isValid = false;
            errorMessage += "Please enter the gem type.\n";
        }
    
        // Weight validation: Check if weight is a positive number
        const weightValue = parseFloat(weight);
        if (isNaN(weightValue) || weightValue <= 0) {
            isValid = false;
            errorMessage += "Please enter a valid weight (positive number).\n";
        }
    
        // Color validation: Check if color is a non-empty string
        if (color === "") {
            isValid = false;
            errorMessage += "Please enter a color.\n";
        }
    
        // Special requirements validation: Check if special requirements are filled
        if (special === "") {
            isValid = false;
            errorMessage += "Please enter any special requirements.\n";
        }
    
        // If validation passes, redirect to MyRequest.html, otherwise show error message
        if (isValid) {
            // Log success and show alert
            console.log("Form is valid, redirecting...");
            alert('Your request has been updated successfully.');
            window.location.href = "../Profile/MyRequest.html";  // Ensure the path is correct
        } else {
            console.log("Form is invalid, showing error messages");
            // Show error message
            alert(errorMessage); // Display validation error messages
        }
    });
    
    
    
    
