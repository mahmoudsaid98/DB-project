<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read JSON input
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate input
    if (empty($data['Payment_method']) || empty($data['cash'])) {
        echo json_encode(["error" => "Invalid input data"]);
        exit;
    }

    // Extract data
    $paymentMethod = $data['Payment_method'];
    $cash = floatval($data['cash']);

    // Perform database operations if needed (e.g., insert payment details into the database)

    // Dummy response for now
    $response = ["success" => true, "message" => "Payment processed successfully"];
    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Page</title>
  <link rel="stylesheet" href="payment_style.css">
</head>
<body>
  <div class="payment-container">
    <h1>Payment</h1>
    
    <!-- Payment Method Dropdown -->
    <label for="paymentMethod">Payment Method</label>
    <select id="paymentMethod">
      <option value="" disabled selected>Select Payment Method</option>
      <option value="Credit Card">Credit Card</option>
      <option value="PayPal">PayPal</option>
      <option value="Cash">Cash</option>
    </select>
    
    <!-- Cash Amount Display -->
    <p>Cash to Pay: <span id="cashToPay">0</span></p>
    
    <!-- Book Now Button -->
    <button id="bookNow">Book Now</button>
  </div>

  <script>
    // Function to get URL query parameters
    function getQueryParams() {
      const params = {};
      const queryString = window.location.search.substring(1);
      const queryArray = queryString.split("&");

      queryArray.forEach(query => {
        const [key, value] = query.split("=");
        params[decodeURIComponent(key)] = decodeURIComponent(value);
      });

      return params;
    }

    // Populate Cash to Pay from URL parameters
    function populateCashToPay() {
      const params = getQueryParams();
      const cashToPay = params.price_per_day || '0'; // Default to 0 if not provided
      document.getElementById('cashToPay').textContent = cashToPay;
    }

    // Handle the "Book Now" button click
    document.getElementById('bookNow').addEventListener('click', function () {
      const paymentMethod = document.getElementById('paymentMethod').value;
      const cash = document.getElementById('cashToPay').textContent;

      // Validate input
      if (!paymentMethod) {
        alert("Please select a payment method");
        return;
      }

      // Prepare data for the POST request
      const paymentData = {
        Payment_method: paymentMethod,
        cash: parseFloat(cash)
      };

      // Send POST request to the server
      fetch('payment.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(paymentData)
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert(data.message);
        } else {
          alert("Error: " + (data.error || "Unknown error"));
        }
      })
      .catch(error => {
        console.error("Error:", error);
        alert("An error occurred while processing the payment.");
      });
    });

    // Populate cash to pay on page load
    window.onload = populateCashToPay;
  </script>
</body>
</html>
