
document.addEventListener('DOMContentLoaded', function() {
    const shippingMethodRadios = document.querySelectorAll('input[name="shipping-method"]');
    const paymentMethodRadios = document.querySelectorAll('input[name="payment-method"]');
    const shippingAddress = document.querySelector('.shipping-address');
    const pickupDate = document.querySelector('.pickup-date');
    const checkoutBtn = document.getElementById('checkout-btn');
    const payInStoreOption = document.querySelector('.pay-in-store-option');
    const payInStoreRadio = document.querySelector('input[value="pay-in-store"]');

    // Toggle shipping options between "Home Shipping" and "Store Pickup"
    function toggleShippingOptions() {
        const selectedShippingMethod = document.querySelector('input[name="shipping-method"]:checked').value;

        if (selectedShippingMethod === 'home-shipping') {
            // Hide shipping address for home delivery
            shippingAddress.classList.remove('hidden');
            pickupDate.classList.add('hidden');
            
            // Hide the "Pay In Store" option for home shipping
            payInStoreOption.classList.add('hidden');
            payInStoreRadio.disabled = true;

            // Automatically select "Pay Online"
            paymentMethodRadios[0].checked = true;
        } else if (selectedShippingMethod === 'store-pickup') {
            // Show store pickup related inputs
            shippingAddress.classList.add('hidden');
            pickupDate.classList.remove('hidden');

            // Show the "Pay In Store" option for store pickup
            payInStoreOption.classList.remove('hidden');
            payInStoreRadio.disabled = false;
        }

        updateCheckoutButton();
    }

    // Update checkout button behavior based on payment method
    function updateCheckoutButton() {
        const selectedShippingMethod = document.querySelector('input[name="shipping-method"]:checked').value;
        const selectedPaymentMethod = document.querySelector('input[name="payment-method"]:checked').value;

        if (selectedShippingMethod === 'store-pickup' && selectedPaymentMethod === 'pay-in-store') {
            checkoutBtn.textContent = 'Confirm Pickup';
            checkoutBtn.onclick = function() {
                alert("You have chosen to pay in-store. Your pickup date is confirmed.");
            };
        } else {
            checkoutBtn.textContent = 'Pay Now';
            checkoutBtn.onclick = function() {
                if (validateForm()) {
                    window.location.href = "#"; // Redirect to payment page
                }
            };
        }
    }

    // Form validation (you can expand this function)
    function validateForm() {
        // Add form validation logic here if needed
        return true;
    }

    // Initial toggle based on the default selection
    toggleShippingOptions();

    // Add event listeners for radio button changes
    shippingMethodRadios.forEach(radio => {
        radio.addEventListener('change', toggleShippingOptions);
    });

    paymentMethodRadios.forEach(radio => {
        radio.addEventListener('change', updateCheckoutButton);
    });
});
