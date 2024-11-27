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
