document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('resetForm');
    const emailField = document.getElementById('email');
    const passwordField = document.getElementById('new-password');
    const confirmPasswordField = document.getElementById('confirm-password');
  
    const emailError = document.getElementById('email-error');
    const passwordError = document.getElementById('password-error');
    const confirmPasswordError = document.getElementById('confirm-password-error');
  
    emailField.addEventListener('blur', function () {
      if (!validateEmail(emailField.value)) {
        emailError.textContent = 'Enter a valid email address.';
      } else {
        emailError.textContent = '';
      }
    });
  
    passwordField.addEventListener('input', function () {
      if (passwordField.value.length < 6) {
        passwordError.textContent = 'Password must be at least 6 characters.';
      } else {
        passwordError.textContent = '';
      }
    });
  
    confirmPasswordField.addEventListener('input', function () {
      if (confirmPasswordField.value !== passwordField.value) {
        confirmPasswordError.textContent = 'Passwords do not match.';
      } else {
        confirmPasswordError.textContent = '';
      }
    });
  
    form.addEventListener('submit', function (e) {
      if (
        emailError.textContent !== '' ||
        passwordError.textContent !== '' ||
        confirmPasswordError.textContent !== ''
      ) {
        e.preventDefault();
        alert('Please fix the errors before submitting.');
      }
    });
  });
  
  function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email.toLowerCase());
  }
  
  function loginpage() {
    window.location.href = "../LoginPage/login.php";
  }
  