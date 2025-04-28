const photo = document.getElementById("photo").value;
if (!photo) {
  isValid = false;
  alert("Please capture a photo.");
}


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



  
function validatePasswords() {
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirm-password");
    const passwordError = document.getElementById("password-error");
    const confirmPasswordError = document.getElementById("password-error");

    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    passwordError.textContent = "";
    confirmPasswordError.textContent = "";
    

if (!passwordPattern.test(password.value)) {
    passwordError.textContent = "Password must be at least 8 characters, contain uppercase, lowercase, number, and special character.";
    passwordError.style.color = "red";
    password.classList.add("error");
    return false;
}


if (confirmPassword.value.trim() === "") {
    confirmPasswordError.textContent = "Re-enter password field cannot be empty";
    confirmPasswordError.style.color = "red";
    confirmPassword.classList.add("error");
    return false;
}


    if (password.value !== confirmPassword.value) {
        
        confirmPasswordError.textContent = "Password do not match";
        confirmPasswordError.style.color = "red";
        
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

  
  const monthDifference = today.getMonth() - birthDate.getMonth();
  if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }

  dobError.textContent = ""; 

  
  if (age < 18) {
    
    dobError.textContent = "You must be at least 18 years old to register.";
    dobError.style.color = "red";
    dob.classList.add("error");
  } else {
    
    dobError.textContent = ""; 
    dob.classList.remove("error");
  }
}


document.getElementById("dob").addEventListener("input", validateDOB);
document.getElementById("dob").addEventListener("blur", validateDOB);



   
  
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


  
  
function nextStep(currentStep) {
    const formError = document.getElementById(`step${currentStep}`).querySelector(".form-error");
    
    
    formError.textContent = "";
  
   
  

    if (validateStep(currentStep)) {
      
      showStep(currentStep + 1);
    } else {
      
      formError.textContent = "Please fill all required fields.";
    }

  }
  
document.querySelectorAll("input").forEach(inputField => {
    inputField.addEventListener("input", function () {
      const formError = inputField.closest(".step").querySelector(".form-error");
      formError.textContent = ""; 
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
    
    const dataURL = canvas.toDataURL("image/png");
    document.getElementById("photo").value = dataURL;
  });