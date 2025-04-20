// JavaScript for filtering functionality
const filterCategory = document.getElementById('category');
const filterSort = document.getElementById('sort');

filterCategory.addEventListener('change', function() {
    console.log(`Filter by category: ${this.value}`);
    // Add logic to filter products based on category
});

filterSort.addEventListener('change', function() {
    console.log(`Sort by: ${this.value}`);
    // Add logic to sort products
});


let cycleDuration = 60 * 60; // 1 hour in seconds
let cycleTimeLeft = cycleDuration;
let cycleInterval;

// Open Bid Confirmation Modal
function openBidConfirmation() {
    document.getElementById('bid-confirmation-modal').style.display = 'block';
}

// Close Bid Confirmation Modal
function closeBidConfirmation() {
    document.getElementById('bid-confirmation-modal').style.display = 'none';
}

// Confirm Bid
function confirmBid() {
    alert("Bid confirmed!");
    closeBidConfirmation();
}

// Timer Setup for Countdown
function startCycleTimer() {
    const timerCanvas = document.getElementById('countdown-timer');
    const ctx = timerCanvas.getContext('2d');
    const timerRadius = timerCanvas.width / 2;
    const totalSeconds = cycleDuration;

    function drawCircularTimer(timeLeft) {
        const angle = (timeLeft / totalSeconds) * 2 * Math.PI;
        ctx.clearRect(0, 0, timerCanvas.width, timerCanvas.height);
        ctx.beginPath();
        ctx.arc(timerRadius, timerRadius, timerRadius - 5, -Math.PI / 2, angle - Math.PI / 2, false);
        ctx.lineWidth = 10;
        ctx.strokeStyle = "#4caf50";
        ctx.stroke();
    }

    function updateTimerDisplay(timeLeft) {
        const hours = Math.floor(timeLeft / 3600);
        const minutes = Math.floor((timeLeft % 3600) / 60);
        const seconds = timeLeft % 60;
        document.getElementById('time-left').textContent = `${hours}:${minutes < 10 ? '0' + minutes : minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
    }

    function countdown() {
        if (cycleTimeLeft <= 0) {
            clearInterval(cycleInterval);
            alert("Cycle ended");
        } else {
            drawCircularTimer(cycleTimeLeft);
            updateTimerDisplay(cycleTimeLeft);
            cycleTimeLeft--;
        }
    }

    countdown();
    cycleInterval = setInterval(countdown, 1000);
}

// Start Timer on Page Load
window.onload = function() {
    startCycleTimer();
};
