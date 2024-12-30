// Function to filter the table rows based on search input
function searchTable() {
    const searchQuery = document.getElementById('searchBar').value.toLowerCase();
    const table = document.getElementById('carTable');
    const rows = Array.from(table.rows).slice(1); // Skip header row

    rows.forEach(row => {
        const cells = Array.from(row.cells);
        const make = cells[0].textContent.toLowerCase();
        const model = cells[1].textContent.toLowerCase();
        const year = cells[2].textContent.toLowerCase();
        const fuelType = cells[3].textContent.toLowerCase();
        const location = cells[6].textContent.toLowerCase();

        // Check if search query matches any of the cell's text
        const matchesSearch = make.includes(searchQuery) || model.includes(searchQuery) || year.includes(searchQuery) ||
                              fuelType.includes(searchQuery) || location.includes(searchQuery);

        // Show or hide row based on search
        row.style.display = matchesSearch ? '' : 'none';
    });
}

// Function to apply filters
function filterTable() {
    const selectedCompanies = Array.from(document.querySelectorAll('.filter-checkbox.company:checked')).map(input => input.value.toLowerCase());
    const selectedFuelTypes = Array.from(document.querySelectorAll('.filter-checkbox.fuel:checked')).map(input => input.value.toLowerCase());
    const selectedLocations = Array.from(document.querySelectorAll('.filter-checkbox.location:checked')).map(input => input.value.toLowerCase());

    const table = document.getElementById('carTable');
    const rows = Array.from(table.rows).slice(1); // Skip header row

    rows.forEach(row => {
        const cells = Array.from(row.cells);
        const make = cells[0].textContent.toLowerCase();
        const fuelType = cells[3].textContent.toLowerCase();
        const location = cells[6].textContent.toLowerCase();

        // Check if company filter matches
        const matchesCompany = selectedCompanies.length === 0 || selectedCompanies.includes(make);

        // Check if fuel type filter matches
        const matchesFuelType = selectedFuelTypes.length === 0 || selectedFuelTypes.includes(fuelType);

        // Check if location filter matches
        const matchesLocation = selectedLocations.length === 0 || selectedLocations.includes(location);

        // Show or hide row based on filters
        row.style.display = (matchesCompany && matchesFuelType && matchesLocation) ? '' : 'none';
    });
}

// Add event listener to search bar for real-time search
document.getElementById('searchBar').addEventListener('input', searchTable);

// Add event listener to filter button
document.getElementById('filterButton').addEventListener('click', filterTable);
