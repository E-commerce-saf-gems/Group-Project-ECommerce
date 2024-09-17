const inputs = document.querySelectorAll(".input");

function focusFunc() {
    let parent = this.parentNode;
    parent.classList.add("focus");

}

function blurFunc() {
    let parent = this.parentNode;
    if(this.value == ""){
        parent.classList.remove("focus");
    }
}

inputs.forEach((input) =>{
    input.addEventListener("focus", focusFunc);
    input.addEventListener("blur", blurFunc);
});

/*-----For Gem Item Slider------*/

const slider = document.querySelector('.slider');
const nextBtn = document.getElementById('nextBtn');
const prevBtn = document.getElementById('prevBtn');
let currentIndex = 0;

nextBtn.addEventListener('click', () => {
    const items = document.querySelectorAll('.bid_item');
    const itemWidth = items[0].clientWidth;
    const visibleItems = 3; // 3 items visible at a time
    const totalItems = items.length;
    const maxIndex = totalItems - visibleItems; // Calculate maxIndex to ensure the last item is fully visible
  
    if (currentIndex < maxIndex) {
        currentIndex++;
        slider.style.transform = `translateX(-${itemWidth * currentIndex}px)`;
    }
});

prevBtn.addEventListener('click', () => {
    const items = document.querySelectorAll('.bid_item');
    const itemWidth = items[0].clientWidth;
    
    if (currentIndex > 0) {
        currentIndex--;
        slider.style.transform = `translateX(-${itemWidth * currentIndex}px)`;
    }
});

/*---For Order By dropdown to link three sections---*/

function navigateToSection() {
    const selectedValue = document.getElementById('order').value;
    const section = document.getElementById(selectedValue);

    if (section) {
        section.scrollIntoView({ behavior: 'smooth' });
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Get the URL parameters
    const params = new URLSearchParams(window.location.search);
    const gem = params.get('gem'); // e.g., 'gem1', 'gem2', etc.

    // Define your gems and corresponding details
    const gemDetails = {
        gem1: {
            name: "Gem Name 1",
            description: "Description of the first gem goes here.",
            imageSrc: "assets/images/stone1.jpg"
        },
        gem2: {
            name: "Gem Name 2",
            description: "Description of the second gem goes here.",
            imageSrc: "assets/images/stone2.jpg"
        },
        // Add more gems as needed
    };

    // Check if the gem exists in the gemDetails
    if (gem && gemDetails[gem]) {
        document.getElementById('gemImage').src = gemDetails[gem].imageSrc;
        document.getElementById('gemName').textContent = gemDetails[gem].name;
        document.getElementById('gemDescription').textContent = gemDetails[gem].description;
    } else {
        // If no valid gem is found, use a default image and text
        document.getElementById('gemImage').src = "assets/images/default.jpg";
        document.getElementById('gemName').textContent = "Unknown Gem";
        document.getElementById('gemDescription').textContent = "Description not available.";
    }
});

function placeBid() {
    // Handle bid logic here, such as updating the bid amount or timer.
    // Ensure this does not reset or remove the gem details.

    // Example of updating the last bid (you can customize this logic):
    const lastBidElement = document.getElementById('lastBid');
    const newBid = "$100.00"; // Example new bid value
    lastBidElement.innerText = newBid;

    // You can add more logic here as needed.
}

/*Countdown Timer*/

/*var target_mili_sec = new Date("Aug 24, 2024 00:00:0").getTime();*/
function timer() 
{
    var now_mili_sec = new Date().getTime();
    var remaining_sec = Math.floor( (target_mili_sec - now_mili_sec) / 1000 );

    var day = Math.floor(remaining_sec / (3600 * 24));
    var hour = Math.floor((remaining_sec % (3600 * 24)) / 3600);
    var min = Math.floor((remaining_sec % 3600) / 60);
    var sec = Math.floor(remaining_sec % 60);

    document.querySelector("#day").innerHTML = day;
    document.querySelector("#hour").innerHTML = hour;
    document.querySelector("#min").innerHTML = min;
    document.querySelector("#sec").innerHTML = sec;
}

setInterval(timer, 1000); //1000 it means 1 sec

/*Last Bid UPDATE function*/



