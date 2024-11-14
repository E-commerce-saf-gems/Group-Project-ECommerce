// Function to handle claiming the certificate
function claimCertificate() {
    // Placeholder for certificate claiming functionality
    alert('Certificate of Authenticity claimed!');
}

// Function to handle feedback submission
function submitFeedback() {
    const feedbackText = document.getElementById('feedback-text').value;
    if (feedbackText.trim() !== '') {
        // Placeholder for feedback submission functionality
        alert('Feedback submitted!');
    } else {
        alert('Please enter feedback before submitting.');
    }
}

// Function to share on Facebook
function shareOnFacebook() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
}

// Function to share on Twitter
function shareOnTwitter() {
    const text = encodeURIComponent('Check out my auction result!');
    const url = encodeURIComponent(window.location.href);
    window.open(`https://twitter.com/intent/tweet?text=${text}&url=${url}`, '_blank');
}

// Function to animate confetti
function animateConfetti() {
    // Example implementation of confetti animation using a library
    const confetti = document.getElementById('confetti');
    confetti.style.backgroundImage = 'url(confetti.gif)';
    confetti.style.backgroundSize = 'cover';
}

// Initialize result page
document.addEventListener('DOMContentLoaded', () => {
    animateConfetti();
    
    // Sample data for recommendations
    const recommendedAuctions = [
        "Auction 1: Sapphire Gems",
        "Auction 2: Ruby Gems"
    ];

    const recommendationsList = document.getElementById('recommended-auctions');
    recommendationsList.innerHTML = recommendedAuctions.map(auction => `<li>${auction}</li>`).join('');
});

/* scripts.js */

let currentGem = null;
let currentBid = 0;
let sessionTime = 120; // 2 minutes for each round
let timerInterval;
let bidders = ["Alice", "Bob", "Charlie"]; // Example bidders

function goToBidNow(gemName, startingBid) {
    currentGem = gemName;
    currentBid = startingBid;
    sessionTime = 120;
    localStorage.setItem("currentGem", gemName);
    localStorage.setItem("currentBid", startingBid);
    window.location.href = "bid-now.html";
}

function goToBiddingRoom() {
    window.location.href = "bidding-room.html";
}

function getChance() {
    // Logic to handle when the user gets a chance to bid
}

function increaseBid() {
    let bidValue = document.getElementById('bid-value').value;
    document.getElementById('bid-value').value = parseInt(bidValue) + 10;
}

function decreaseBid() {
    let bidValue = document.getElementById('bid-value').value;
    if (bidValue > 10) {
        document.getElementById('bid-value').value = parseInt(bidValue) - 10;
    }
}

function submitBid() {
    let bidValue = document.getElementById('bid-value').value;
    alert("Bid submitted: $" + bidValue);
}

function startCountdown() {
    timerInterval = setInterval(() => {
        sessionTime--;
        document.getElementById('session-timer').innerText = formatTime(sessionTime);

        if (sessionTime <= 0) {
            clearInterval(timerInterval);
            alert("Time's up!"); // Or any other action when time runs out
            window.location.href = "result.html";
        }
    }, 1000);
}

function formatTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${minutes < 10 ? '0' : ''}${minutes}:${secs < 10 ? '0' : ''}${secs}`;
}

function displayResult() {
    document.getElementById('winner-name').innerText = "Alice";
    document.getElementById('winning-value').innerText = "1000";
    let participantsValues = document.getElementById('participants-values');
    bidders.forEach(bidder => {
        let li = document.createElement('li');
        li.innerText = `${bidder}: $900`;
        participantsValues.appendChild(li);
    });
}

function claimCertificate() {
    alert("Certificate claimed! Congratulations!");
}

/*FEEDBACK FORM*/

    document.getElementById('feedback-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting the default way

        // Get form values
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const feedbackType = document.getElementById('feedback-type').value;
        const comments = document.getElementById('comments').value;

        // Create an object to hold the form data
        const feedbackData = {
            name: name,
            email: email,
            feedbackType: feedbackType,
            comments: comments
        };

        // Send the form data to the server
        fetch('submit-feedback.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(feedbackData)
        })
        .then(response => response.json())
        .then(data => {
            // Display a success or error message
            const responseMessage = document.getElementById('response-message');
            if (data.success) {
                responseMessage.innerHTML = `<p class="success-message">Thank you for your feedback!</p>`;
                document.getElementById('feedback-form').reset(); // Reset the form
            } else {
                responseMessage.innerHTML = `<p class="error-message">${data.message}</p>`;
            }
        })
        .catch(error => console.error('Error:', error));
    });