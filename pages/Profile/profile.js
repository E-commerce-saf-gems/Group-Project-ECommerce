document.addEventListener('DOMContentLoaded', function() {
    const updateButtons = document.querySelectorAll('.update-button');

    
    const editButtons = document.querySelectorAll('.edit-button');
    editButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            alert('This functionality is not yet implemented.');
        });
    });

    
    const tabLinks = document.querySelectorAll('.tab-link');
    tabLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const activeTab = document.querySelector('.tab-link.active');
            if (activeTab) {
                activeTab.classList.remove('active');
            }
            this.classList.add('active');

            
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
    
    const sections = document.querySelectorAll('.section');
    sections.forEach(section   => {
        section.style.display = 'none';
    });

    
    const selectedSection = document.getElementById(sectionId);
    selectedSection.style.display = 'block';   

}


document.addEventListener("DOMContentLoaded", function () {
    
    const updateButtons = document.querySelectorAll('.update-button');

    updateButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); 

            const form = this.closest('form');

            if (form.classList.contains('details-form')) {
                ementById("firstName").value.trim();
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
                    
                }
            } else if (form.classList.contains('password-form')) {
                
                const newPassword = document.getElementById('newPassword');
                const confirmPassword = document.getElementById('confirmPassword');

                
                clearPasswordErrors(newPassword, confirmPassword);

                let validationMessage = validatePassword(newPassword.value.trim(), confirmPassword.value.trim());

                if (validationMessage) {
                    displayPasswordErrors(newPassword, confirmPassword, validationMessage);
                } else {
                    alert('Your password has been updated successfully.');
                    
                }
            }
        });
    });

    
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

    
    function clearPasswordErrors(newPassword, confirmPassword) {
        newPassword.classList.remove('error');
        confirmPassword.classList.remove('error');
        newPassword.value = '';
        confirmPassword.value = '';
    }

    
    function displayPasswordErrors(newPassword, confirmPassword, message) {
        newPassword.classList.add('error');
        confirmPassword.classList.add('error');
        newPassword.placeholder = message.split('\n')[0];
        confirmPassword.placeholder = message.split('\n').slice(1).join(' ');
    }
    
});


const subscribeForm = document.querySelector('.subscription-form');
    const nameInput = subscribeForm.querySelector('input[type="text"]');
    const emailInput = subscribeForm.querySelector('input[type="email"]');
    const subscribeButton = subscribeForm.querySelector('button');

    
    function isValidEmail(email) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
    }

    
    subscribeButton.addEventListener('click', (e) => {
        e.preventDefault(); 

        
        const name = nameInput.value.trim();
        const email = emailInput.value.trim();

        
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

        
        alert('Thank you for subscribing!');

        
        nameInput.value = '';
        emailInput.value = '';
    });

    
    document.addEventListener('DOMContentLoaded', function () {
        
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
        event.preventDefault(); 
        console.log("Form submission event triggered"); 
    
        
        const date = document.getElementById("date").value;
        const shape = document.getElementById("shape").value.trim();
        const type = document.getElementById("type").value.trim();
        const weight = document.getElementById("weight").value.trim();
        const color = document.getElementById("color").value.trim();
        const special = document.getElementById("special").value.trim();
        
        console.log(date, shape, type, weight, color, special); // Check values
    
        
        let isValid = true;
        let errorMessage = "";
    
        
        if (!date) {
            isValid = false;
            errorMessage += "Please enter a valid date.\n";
        }
    
        
        if (shape === "") {
            isValid = false;
            errorMessage += "Please enter a shape.\n";
        }
    
        
        if (type === "") {
            isValid = false;
            errorMessage += "Please enter the gem type.\n";
        }
    
        
        const weightValue = parseFloat(weight);
        if (isNaN(weightValue) || weightValue <= 0) {
            isValid = false;
            errorMessage += "Please enter a valid weight (positive number).\n";
        }
    
        
        if (color === "") {
            isValid = false;
            errorMessage += "Please enter a color.\n";
        }
    
        
        if (special === "") {
            isValid = false;
            errorMessage += "Please enter any special requirements.\n";
        }
    
        
        if (isValid) {
            
            console.log("Form is valid, redirecting...");
            alert('Your request has been updated successfully.');
            window.location.href = "../Profile/MyRequest.html";  
        } else {
            console.log("Form is invalid, showing error messages");
            
            alert(errorMessage); 
        }
    });
    
    
    
    
