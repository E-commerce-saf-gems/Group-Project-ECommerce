document.addEventListener("DOMContentLoaded", () => {
    const addToCartButtons = document.querySelectorAll(".add-to-cart-btn");

    addToCartButtons.forEach(button => {
        button.addEventListener("click", () => {
            const stoneId = button.getAttribute("data-id");

            fetch("add_to_cart.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({ stoneId })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message || "Error adding to cart");
            })
            .catch(error => console.error("Error:", error));
        });
    });
});
