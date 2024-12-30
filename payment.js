
document.addEventListener('DOMContentLoaded', () => {
    // Get the query parameters from the URL
    const urlParams = new URLSearchParams(window.location.search);
    const totalAmount = urlParams.get('total_amount') || '0.00';

    // Display the total amount
    document.getElementById('cashToPay').innerText = totalAmount;

    // Handle "Book Now" button click
    document.getElementById('bookNow').addEventListener('click', () => {
        const paymentMethod = document.getElementById('paymentMethod').value;

        if (!paymentMethod) {
            alert('Please select a payment method.');
            return;
        }

        // Send payment data to the server
        fetch('payment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                Payment_method: paymentMethod,
                cash: totalAmount
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Payment processed successfully!');
                window.location.href = 'success.html';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing the payment.');
        });
    });
});
