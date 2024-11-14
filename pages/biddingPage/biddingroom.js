// Function to update bid value from slider
function updateBidValue(value) {
    document.getElementById('bid-value').value = value;
}

// Function to handle bid increase
function increaseBid() {
    let bidValue = parseInt(document.getElementById('bid-value').value, 10);
    let slider = document.getElementById('bid-slider');
    if (bidValue < slider.max) {
        bidValue += 100;
        slider.value = bidValue;
        document.getElementById('bid-value').value = bidValue;
    }
}

// Function to handle bid decrease
function decreaseBid() {
    let bidValue = parseInt(document.getElementById('bid-value').value, 10);
    let slider = document.getElementById('bid-slider');
    if (bidValue > slider.min) {
        bidValue -= 100;
        slider.value = bidValue;
        document.getElementById('bid-value').value = bidValue;
    }
}

// Function to handle bid submission
function submitBid() {
    const bidValue = document.getElementById('bid-value').value;
    document.getElementById('confirm-bid-value').innerText = bidValue;
    document.getElementById('confirmation-modal').style.display = 'block';
}

// Function to confirm bid
function confirmBid() {
    // Add functionality to confirm the bid and send it to the server
    document.getElementById('confirmation-modal').style.display = 'none';
    showEncouragementMessage('You are the highest bidder!', 'highest-bidder');
}

// Function to close modal
function closeModal() {
    document.getElementById('confirmation-modal').style.display = 'none';
}

// Function to show encouragement message
function showEncouragementMessage(message, type) {
    const messageElement = document.getElementById('encouragement-message');
    messageElement.innerText = message;
    messageElement.className = `message ${type}`;
    messageElement.style.display = 'block';
}

// Function to simulate getting a chance
function getChance() {
    // Add functionality to handle getting a chance to bid
    console.log('Get Chance clicked');
}

// Function to show participant profile info
function showProfileInfo(participant) {
    // Display profile info and bidding history (simulate with alert for now)
    alert(`Showing profile info for ${participant}`);
}

document.addEventListener('DOMContentLoaded', () => {
    // Attach event listener for the confirmation button
    document.getElementById('confirm-bid-btn').addEventListener('click', confirmBid);
});