
    document.addEventListener("DOMContentLoaded", function () {
        const shippingMethodInputs = document.querySelectorAll("input[name='shipping-method']");
        const paymentMethodInputs = document.querySelectorAll("input[name='payment-method']");
        const pickupDateContainer = document.querySelector(".pickup-date");
        const shippingAddressContainer = document.querySelector(".shipping-address");
        const payInStoreOption = document.querySelector(".pay-in-store-option");
        const paypalContainer = document.getElementById("paypal-button-container");
        const placeOrderButton = document.querySelector(".btn-primary");

        function updateFormVisibility() {
            const selectedShippingMethod = document.querySelector("input[name='shipping-method']:checked").value;
            const selectedPaymentMethod = document.querySelector("input[name='payment-method']:checked").value;

            pickupDateContainer.style.display = selectedShippingMethod === "store-pickup" ? "block" : "none";

            shippingAddressContainer.style.display = selectedShippingMethod === "home-shipping" ? "block" : "none";

            // Show or hide Pay In Store option based on shipping method
            payInStoreOption.style.display = selectedShippingMethod === "store-pickup" ? "block" : "none";

            // Show PayPal button and hide Place Order button when "Pay Online" is selected
            if (selectedPaymentMethod === "pay-online") {
                paypalContainer.style.display = "block";  // Show PayPal button
                placeOrderButton.style.display = "none";  // Hide Proceed to Checkout
            } else {
                paypalContainer.style.display = "none";  // Hide PayPal button
                placeOrderButton.style.display = "block";  // Show Proceed to Checkout for Pay In Store
            }
        }

        // Attach event listeners
        shippingMethodInputs.forEach(input => input.addEventListener("change", updateFormVisibility));
        paymentMethodInputs.forEach(input => input.addEventListener("change", updateFormVisibility));

        // Run once on page load
        updateFormVisibility();
    });

