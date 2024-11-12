document.getElementById("customRequestForm").addEventListener("submit", function (event) {
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
