// Function to show the specified step and hide the others
function showStep(step) {
    document.querySelectorAll(".step").forEach((el) => {
        el.classList.remove("active");
    });

    const stepElement = document.getElementById(`step${step}`);
    if (stepElement) {
        stepElement.classList.add("active");
    } else {
        console.error(`Step ${step} does not exist.`);
    }
}

// Function to validate all required fields in the current step
function validateStep(step) {
    const stepElement = document.getElementById(`step${step}`);
    if (!stepElement) {
        console.error(`Step ${step} does not exist.`);
        return false;
    }

    const requiredFields = stepElement.querySelectorAll("[required]");
    let isValid = true;

    requiredFields.forEach((field) => {
        if (field.type === 'file') {
            if (field.files.length === 0) {
                isValid = false;
                field.classList.add("error");
            } else {
                field.classList.remove("error");
            }
        } else if (!field.value.trim()) {
            isValid = false;
            field.classList.add("error");
        } else {
            field.classList.remove("error");
        }
    });

    // Additional check for the photo in step 3
    if (step === 3) {
        const photo = document.getElementById("photo").value;
        if (!photo) {
            isValid = false;
            alert("Please capture a photo.");
        }
    }

    return isValid;
}

// Navigate to the next step
function nextStep(currentStep) {
    if (validateStep(currentStep)) {
        showStep(currentStep + 1);
    } else {
        alert("Please fill all required fields.");
    }
}

// Navigate to the previous step
function prevStep(currentStep) {
    showStep(currentStep - 1);
}

// Initialize to show the first step
document.addEventListener("DOMContentLoaded", () => {
    showStep(1);
});



//photo 
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
            height: 100,
          },
        };

        async function init() {
          try {
            const stream = await navigator.mediaDevices.getUserMedia(
              constraints
            );
            handleSuccess(stream);
          } catch (e) {
            errorMsgElement.innerHTML =
              "navigator.getUserMedia.error: ${e.toString()}";
          }
        }

        function handleSuccess(stream) {
          window.stream = stream;
          video.srcObject = stream;
        }

        init();

        var context = canvas.getContext("2d");
        snap.addEventListener("click", function () {
          context.drawImage(video, 0, 0, canvas.width, canvas.height);
          // Convert the captured image to base64 and set it as hidden input value
          const dataURL = canvas.toDataURL("image/png");
          document.getElementById("photo").value = dataURL;
        });