const API_URL = "fetch_payment_methods.php"; // PHP script URL
const paymentSelect = document.getElementById("paymentMethod");
const cashToPay = document.getElementById("cashToPay");

// Fetch payment methods from the server
async function fetchPaymentMethods() {
    try {
        const response = await fetch(API_URL); // Fetch data from the PHP script
        const paymentMethods = await response.json(); // Parse the JSON response

        console.log("Fetched Payment Methods:", paymentMethods); // Debug log

        if (paymentMethods.length === 0) {
            alert("No payment methods found.");
            return;
        }

        // Populate the dropdown menu with fetched payment methods
        paymentMethods.forEach((method) => {
            const option = document.createElement("option");
            option.value = method.PaymentMethod; // Use PaymentMethod as value
            option.textContent = method.PaymentMethod; // Set the display text
            paymentSelect.appendChild(option);
        });
    } catch (error) {
        console.error("Error fetching payment methods:", error);
        alert("Failed to fetch payment methods. Please try again later.");
    }
}

// Initialize the script by fetching payment methods
fetchPaymentMethods();
