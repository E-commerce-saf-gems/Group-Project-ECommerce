document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(".details-form");

    form.addEventListener("submit", function (event) {
        const email = document.getElementById("email").value.trim();
        const phone = document.getElementById("phone").value.trim();

        // Email Regex
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailRegex.test(email)) {
            alert("Please enter a valid email address.");
            event.preventDefault(); // Prevent form submission
            return;
        }

        const phoneRegex = /^[0-9]{10}$/;
        if (!phoneRegex.test(phone)) {
            alert("Please enter a valid phone number.");
            event.preventDefault(); // Prevent form submission
            return;
        }
        // If both validations pass
        alert("Form submitted successfully!");
    });
});
