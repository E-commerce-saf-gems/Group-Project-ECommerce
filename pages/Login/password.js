
// Function to redirect to the login page
function loginpage() {
    window.location.href = 'login.php';
}


 // Validate passwords function
 function validatePasswords() {
    const newPassword = document.getElementById("new-password");
    const confirmPassword = document.getElementById("confirm-password");
    const newPasswordError = document.getElementById("new-password-error");
    const confirmPasswordError = document.getElementById("confirm-password-error");

    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    newPasswordError.textContent = "";
    confirmPasswordError.textContent = "";
    
// Validate password against the pattern
if (!passwordPattern.test(newPassword.value)) {
    newPasswordError.textContent = "Password must be at least 8 characters, contain uppercase, lowercase, number, and special character.";
    newPasswordError.style.color = "red";
    newPassword.classList.add("error");
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
    newPassword.classList.remove("error");
    confirmPassword.classList.remove("error");
    return true;
}

  document.getElementById("new-password").addEventListener("input", validatePasswords );
  document.getElementById("new-password").addEventListener("blur", validatePasswords );
  document.getElementById("confirm-password").addEventListener("input", validatePasswords);
  document.getElementById("confirm-password").addEventListener("blur", validatePasswords);
