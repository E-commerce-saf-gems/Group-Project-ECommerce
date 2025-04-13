const photo = document.getElementById("photo").value;
if (!photo) {
  isValid = false;
  alert("Please capture a photo.");
}


// To Validate Email
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

// Add event listeners to remove the error message when input changes or loses focus
  document.getElementById("email").addEventListener("input", validateEmail );
  document.getElementById("email").addEventListener("blur", validateEmail );



  // Validate passwords function
function validatePasswords() {
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirm-password");
    const passwordError = document.getElementById("password-error");
    const confirmPasswordError = document.getElementById("password-error");

    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    passwordError.textContent = "";
    confirmPasswordError.textContent = "";
    
// Validate password against the pattern
if (!passwordPattern.test(password.value)) {
    passwordError.textContent = "Password must be at least 8 characters, contain uppercase, lowercase, number, and special character.";
    passwordError.style.color = "red";
    password.classList.add("error");
    return false;
}

// Check if re-enter password field is empty
if (confirmPassword.value.trim() === "") {
    confirmPasswordError.textContent = "Re-enter password field cannot be empty";
    confirmPasswordError.style.color = "red";
    confirmPassword.classList.add("error");
    return false;
}


    if (password.value !== confirmPassword.value) {
        /*passwordError.textContent = "Passwords do not match.";*/
        confirmPasswordError.textContent = "Password do not match";
        confirmPasswordError.style.color = "red";
        /*password.classList.add("error");*/
        confirmPassword.classList.add("error");
        return false;
    }
    password.classList.remove("error");
    confirmPassword.classList.remove("error");
    return true;
}

  document.getElementById("password").addEventListener("input", validatePasswords );
  document.getElementById("password").addEventListener("blur", validatePasswords );
  document.getElementById("confirm-password").addEventListener("input", validatePasswords);
  document.getElementById("confirm-password").addEventListener("blur", validatePasswords);



  //validate phoneNumber
  function validatephoneNumber(){
    const phone = document.getElementById("phone");
    const phoneError = document.getElementById("phone-error");

    const phonePattern = /^\d{10}$/;

    phoneError.textContent = "" ;

    if (!phonePattern.test(phone.value)) {
        phoneError.textContent = "Please enter a valid phone number";
        phoneError.style.color = "red";
        phone.classList.add("error");
        phone.focus();
        return false;
    } else {
        phone.classList.remove("error");
        return true;
    }
}

  document.getElementById("phone").addEventListener("input", validatephoneNumber );
  document.getElementById("phone").addEventListener("blur", validatephoneNumber );



function validateDOB() {
  const dob = document.getElementById("dob");
  const dobError = document.getElementById("dob-error");

  const today = new Date();
  const birthDate = new Date(dob.value);
  const age = today.getFullYear() - birthDate.getFullYear();

  // Adjust the age calculation if the birthday hasn't occurred yet this year
  const monthDifference = today.getMonth() - birthDate.getMonth();
  if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }

  dobError.textContent = ""; // Clear previous error message

  // Check if the age is 18 or older
  if (age < 18) {
    // Show error message if under 18
    dobError.textContent = "You must be at least 18 years old to register.";
    dobError.style.color = "red";
    dob.classList.add("error");
  } else {
    // Remove error styling if valid
    dobError.textContent = ""; // Clear error message
    dob.classList.remove("error");
  }
}

// Add event listeners to validate on input (typing) and blur (losing focus)
document.getElementById("dob").addEventListener("input", validateDOB);
document.getElementById("dob").addEventListener("blur", validateDOB);



   
  //validate nic number
  function validateNIC(){
    const nic = document.getElementById("nic");
    const nicError = document.getElementById("nic-error");

    const nicPattern = /^(\d{9}[VXvx]|\d{12})$/;

    nicError.textContent = "";

    if (!nicPattern.test(nic.value)) {
        nicError.textContent = "Please enter a valid NIC number";
        nicError.style.color = "red";
        nic.classList.add("error");
        nic.focus();
        return false;
    } else {
        nic.classList.remove("error");
        return true;
    }
  }

  document.getElementById("nic").addEventListener("input", validateNIC );
  document.getElementById("nic").addEventListener("blur", validateNIC );


  
  // Combined form validation for step 1 that includes email and password validation
/*function validateForm() {
    if (!validateEmail() || !validatePasswords()) {
      return false;
    }
    return validateStep(1);
  }*/

  // Navigate to the next step
function nextStep(currentStep) {
    const formError = document.getElementById(`step${currentStep}`).querySelector(".form-error");
    
    // Clear any previous error messages
    formError.textContent = "";
  
   /* if (currentStep === 1 && !validateForm()) {
      // If there are validation issues in step 1, exit without moving forward
      formError.textContent = "Please fix the errors in the form.";
      return;
    }*/
  

    if (validateStep(currentStep)) {
      // If the current step is valid, show the next step
      showStep(currentStep + 1);
    } else {
      // Display an error message if required fields are missing
      formError.textContent = "Please fill all required fields.";
    }

  }
  // Add this code to ensure the form fields trigger validation
document.querySelectorAll("input").forEach(inputField => {
    inputField.addEventListener("input", function () {
      const formError = inputField.closest(".step").querySelector(".form-error");
      formError.textContent = ""; // Clear the error message on input
    });
  });

  
  
"use strict";
  
  const video = document.getElementById("video");
  const canvas = document.getElementById("canvas");
  const snap = document.getElementById("snap");
  const errorMsgElement = document.getElementById("spanErrorMsg");
  const photoData = document.getElementById("photo-data");
  
  const constraints = {
    audio: false,
    video: {
      width: 200,
      height: 150,
    },
  };
  
  // Initialize video stream
  async function init() {
    try {
      const stream = await navigator.mediaDevices.getUserMedia(constraints);
      handleSuccess(stream);
    } catch (e) {
      errorMsgElement.innerHTML = `navigator.getUserMedia.error: ${e.toString()}`;
    }
  }
  
  function handleSuccess(stream) {
    window.stream = stream;
    video.srcObject = stream;
  }
  
  init();
  
  const context = canvas.getContext("2d");
  snap.addEventListener("click", function () {
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    // Convert the captured image to base64 and set it as hidden input value
    const dataURL = canvas.toDataURL("image/png");
    document.getElementById("photo").value = dataURL;
  });