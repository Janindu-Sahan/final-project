document.addEventListener("DOMContentLoaded", function() {
    const budgetsPerPage = 5;
    let currentPage = 1;
    let budgets = [];

    function loadBudgets() {
        fetch('data/budget.json') // Change the path to your budget data file
            .then(response => response.json())
            .then(data => {
                budgets = data.budgets;
                displayBudgets();
                updatePagination();
            })
            .catch(error => console.error('Error loading budgets:', error));
    }

    function displayBudgets() {
        const budgetTableBody = document.querySelector('#budgetTable tbody');
        budgetTableBody.innerHTML = '';

        const startIndex = (currentPage - 1) * budgetsPerPage;
        const endIndex = startIndex + budgetsPerPage;
        const budgetsToDisplay = budgets.slice(startIndex, endIndex);

        budgetsToDisplay.forEach(budget => {
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${budget.id}</td>
                <td>${budget.category}</td>
                <td>${budget.allocatedAmount}</td>
                <td>
                    <button class="editBtn" data-id="${budget.id}">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="deleteBtn" data-id="${budget.id}">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
                </td>
            `;

            budgetTableBody.appendChild(row);
        });

        // Add event listeners for Edit and Delete buttons
        const editButtons = document.querySelectorAll('.editBtn');
        const deleteButtons = document.querySelectorAll('.deleteBtn');

        editButtons.forEach(button => {
            button.addEventListener('click', handleEdit);
        });

        deleteButtons.forEach(button => {
            button.addEventListener('click', handleDelete);
        });
    }

    function handleEdit(event) {
        const budgetId = event.target.closest('button').getAttribute('data-id');
        alert(`Edit budget with ID: ${budgetId}`);
        // Implement the actual edit functionality here
    }

    function handleDelete(event) {
        const budgetId = event.target.closest('button').getAttribute('data-id');
        const confirmed = confirm(`Are you sure you want to delete budget with ID: ${budgetId}?`);
        if (confirmed) {
            // Implement the actual delete functionality here
            budgets = budgets.filter(budget => budget.id !== parseInt(budgetId));
            displayBudgets(); // Update the table after deletion
        }
    }

    function updatePagination() {
        const totalPages = Math.ceil(budgets.length / budgetsPerPage);
        const prevButton = document.getElementById('prevPage');
        const nextButton = document.getElementById('nextPage');
        const currentPageLabel = document.getElementById('currentPage');

        prevButton.disabled = currentPage === 1;
        nextButton.disabled = currentPage === totalPages;

        currentPageLabel.textContent = `Page ${currentPage} of ${totalPages}`;
    }

    document.getElementById('prevPage').addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            displayBudgets();
            updatePagination();
        }
    });

    document.getElementById('nextPage').addEventListener('click', function() {
        const totalPages = Math.ceil(budgets.length / budgetsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            displayBudgets();
            updatePagination();
        }
    });

    loadBudgets(); // Load the budgets data initially
});
