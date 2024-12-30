document.addEventListener("DOMContentLoaded", () => {
    const carSelect = document.getElementById("carSelect");
    const pickupDate = document.getElementById("pickupDate");
    const returnDate = document.getElementById("returnDate");
    const proceedToPayment = document.getElementById("proceedToPayment");

    // Fetch available cars from the server
    fetch("get_cars.php")
        .then(response => response.json())
        .then(data => {
            data.forEach(car => {
                const option = document.createElement("option");
                option.value = car.car_id;
                option.textContent = `${car.company} ${car.model} (${car.price_per_day}/day)`;
                carSelect.appendChild(option);
            });
        })
        .catch(error => console.error("Error fetching cars:", error));

    // Redirect to payment page with calculated cash
    proceedToPayment.addEventListener("click", () => {
        const carId = carSelect.value;
        const pickup = new Date(pickupDate.value);
        const returnD = new Date(returnDate.value);

        if (!carId || isNaN(pickup) || isNaN(returnD) || pickup >= returnD) {
            alert("Please select a valid car and dates.");
            return;
        }

        const days = Math.ceil((returnD - pickup) / (1000 * 60 * 60 * 24));

        // Get car price from the selected option text
        const selectedCar = carSelect.options[carSelect.selectedIndex].textContent;
        const pricePerDay = parseFloat(selectedCar.match(/\((\d+\.\d+)/)[1]);

        const totalPayment = days * pricePerDay;

        // Store data in sessionStorage to pass to the payment page
        sessionStorage.setItem("carId", carId);
        sessionStorage.setItem("totalPayment", totalPayment);
        sessionStorage.setItem("pickupDate", pickupDate.value);
        sessionStorage.setItem("returnDate", returnDate.value);

        // Redirect to payment page
        window.location.href = "payment.html";
    });
});