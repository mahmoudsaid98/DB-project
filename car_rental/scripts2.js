document.getElementById("proceedToPayment").addEventListener("click", function (event) {
    // Prevent the default form submission
    event.preventDefault();

    // Get form data
    const carId = document.getElementById("car_id").value;
    const pickupDate = document.querySelector('input[name="pickup_date"]').value;
    const returnDate = document.querySelector('input[name="return_date"]').value;

    // Validate that required fields are filled
    if (!pickupDate || !returnDate) {
        alert("Please fill in the pickup and return dates before proceeding to payment.");
        return;
    }

    // Redirect to the payment page with query parameters
    const url = `payment_page.html?car_id=${carId}&pickup_date=${pickupDate}&return_date=${returnDate}`;
    window.location.href = url;
});
