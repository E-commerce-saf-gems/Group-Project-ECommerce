 /*document.querySelector("form").addEventListener("submit", function (event) {
    if (!validateEmail()) {
        event.preventDefault(); // Prevent form submission if email is invalid
    }
}); */

document.getElementById("meetingRequestForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent page reload

    // Show the success message
    const successMessage = document.getElementById("successMessage");
    successMessage.classList.add("show");

    // Optionally hide the success message after 3 seconds
    setTimeout(() => {
        successMessage.classList.remove("show");
    }, 3000);

    // Reset the form fields after submission
    event.target.reset();
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

// Add event listeners to remove the error message when input changes or loses focus
  document.getElementById("email").addEventListener("input", validateEmail );
  document.getElementById("email").addEventListener("blur", validateEmail );

