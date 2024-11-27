
setTimeout(function() {
    const message = document.querySelector(".success-message");
    if (message) {
        message.style.display = "none";
    }
}, 5000);



document.getElementById('customRequestForm').addEventListener('submit', function(e) {
    const colorField = document.getElementById('color');
    const typeField = document.getElementById('gem-type');
    
    const colorError = document.getElementById('color-error');
    const typeError = document.getElementById('gem-type-error');
    
    const regex = /^[A-Za-z]+( [A-Za-z]+)*$/; // Matches alphabetic characters and spaces

    colorError.textContent = '';
    typeError.textContent = '';

    let valid = true;

    if (!regex.test(colorField.value)) {
        colorError.textContent = 'Enter a valid color name with only alphabetic characters and spaces.';
        valid = false;
    }

    if (typeField.value && !regex.test(typeField.value)) {
        typeError.textContent = 'Enter a valid gemstone type with only alphabetic characters and spaces.';
        valid = false;
    }

    if (!valid) {
        e.preventDefault();
    }

    return valid;
});


