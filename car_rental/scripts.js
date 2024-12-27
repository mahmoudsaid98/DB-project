document.addEventListener("DOMContentLoaded", function () {
    // Task 1: Change text color based on car type
    const carTypeElement = document.getElementById("carType");
    if (carTypeElement && carTypeElement.textContent.toLowerCase().includes("electric")) {
        carTypeElement.style.color = "green";
    } else if (carTypeElement) {
        carTypeElement.style.color = "brown";
    }

    // Task 2: Handle booking action with confirmation
    const bookCarButton = document.getElementById("bookCar");
    if (bookCarButton) {
        bookCarButton.addEventListener("click", function () {
            const confirmBooking = confirm("Are you sure you want to book this car?");
            if (confirmBooking) {
                // Redirect to try.html if OK is pressed
                window.location.href = "try.html"; //5555555555555555555555
            } else {
                // Show cancellation message if Cancel is pressed
                alert("Booking cancelled.");
            }
        });
    }
});
