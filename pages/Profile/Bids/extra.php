document.addEventListener('DOMContentLoaded', () => {
    fetch('MyBids.php?json=1')
    .then(res => res.json())
    .then(data => {
        populateMyBids(data.my_bids);
        populateLiveBids(data.live_bids);
        populateUpcoming(data.upcoming_bids);
        populateCompleted(data.completed_bids);
    });
});

function populateMyBids(bids) {
    const tbody = document.querySelector('.bids-table tbody'); // adjust selectors for each section
    tbody.innerHTML = ''; // clear before populate
    bids.forEach(bid => {
        const row = `
            <tr>
                <td><img src="../../../assets/images/stone${bid.id}.jpg" alt="stone"></td>
                <td>#${String(bid.id).padStart(3, '0')}</td>
                <td>${bid.startBid}</td>
                <td>${bid.highest_bid}</td>
                <td>${bid.my_highest_bid}</td>
                <td>
                    <a href="./activeBids.html" class="bid-now-button">Details</a>
                    <a href="../../bidding/bidding-itemPage.html?id=${bid.id}" class="bid-now-button">Bid Now</a>
                </td>
                <td>${timeRemaining(bid.finishDate)}</td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

// Repeat similar `populateLiveBids`, `populateUpcoming`, `populateCompleted` using their respective containers

function timeRemaining(finishDateStr) {
    const finish = new Date(finishDateStr);
    const now = new Date();
    const diff = finish - now;

    if (diff <= 0) return 'Ended';

    const mins = Math.floor((diff / 1000 / 60) % 60);
    const hrs = Math.floor((diff / 1000 / 60 / 60) % 24);
    const days = Math.floor(diff / 1000 / 60 / 60 / 24);

    return `${days} Days ${hrs} Hr ${mins} Mins`;
}
