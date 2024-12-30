// Search Functionality
function searchTable() {
    // ... (same search function as before)
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("search-bar");
    filter = input.value.toUpperCase();
    table = document.getElementById("carTable");
    tr = table.getElementsByTagName("tr");

    for (i = 1; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById("search-bar").addEventListener("keyup", searchTable);
    const editButtons = document.querySelectorAll('.edit-btn');

    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const carId = this.dataset.carId;
            let currentStatus = this.dataset.currentStatus;

            let newStatus;
            if (currentStatus === 'active') {
                newStatus = 'rented';
            } else if (currentStatus === 'rented') {
                newStatus = 'out of service';
            }
            else if (currentStatus === 'out of service'){
                newStatus = 'active';
            }

            document.getElementById('status-' + carId).textContent = newStatus;

            fetch('update_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'car_id=' + carId + '&status=' + newStatus,
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(data => {
                if (data !== "status updated"){
                    alert(data);
                    document.getElementById('status-' + carId).textContent = currentStatus;
                }
                this.dataset.currentStatus = newStatus;
            })
            .catch(error => {
                console.error('There has been a problem with your fetch operation:', error);
                alert("An error occurred while updating the status.");
                document.getElementById('status-' + carId).textContent = currentStatus;
            });
        });
    });
});