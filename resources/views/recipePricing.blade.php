@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Recipe Pricing</h1>
        <a href="{{ 'addoverheads' }}" class='text-decoration-none ps-add-btn text-white py-1 px-4'>
            <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
        </a>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-1"></div>

            <!-- Right side columns -->
            <div class="col-lg-8">
                <div class="row">
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end mb-2 action-buttons">
                        <button class="btn btn-sm edit-table-btn me-2" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                            <i class="fas fa-edit" style="color: black;"></i>
                        </button>
                        <button class="btn btn-sm delete-table-btn" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                            <i class="fas fa-trash" style="color: red;"></i>
                        </button>
                    </div>

                    <!-- Bordered Table -->
                    <table class="table table-bordered">
                        <thead class="custom-header">
                            <tr>
                                <!-- <th class="head" scope="col">
                                    <input type="checkbox" id="select-all" class="form-check-input">
                                </th> -->
                                <th scope="col" style="color:white;">S.NO</th>
                                <th scope="col" style="color:white;">Recipe</th>
                                <th scope="col" style="color:white;">RP Code</th>
                                <th scope="col" style="color:white;">Raw Material Cost</th>
                                <th scope="col" style="color:white;">Selling Price</th>
                                <th scope="col" style="color:white;">Discount</th>
                                <th scope="col" style="color:white;">Margin</th>
                            </tr>
                        </thead>
                        <tbody id="rawMaterialTable">
                            <tr>
                                
                                <td>1.</td> <!-- Auto-increment S.NO -->
                                <td>Egg Puff</td>
                                <td>RP0001</td> <!-- RM Code -->
                                <td>37</td> <!-- UoM -->
                                <td>45</td>
                                <td>5%</td>
                                <td>5</td>
                            </tr>
                            <tr>
                                
                                <td>2.</td> <!-- Auto-increment S.NO -->
                                <td>Egg Puff</td>
                                <td>RP0001</td> <!-- RM Code -->
                                <td>37</td> <!-- UoM -->
                                <td>45</td>
                                <td>5%</td>
                                <td>5</td>
                            </tr>
                            <tr>
                                
                                <td>3.</td> <!-- Auto-increment S.NO -->
                                <td>Egg Puff</td>
                                <td>RP0001</td> <!-- RM Code -->
                                <td>37</td> <!-- UoM -->
                                <td>45</td>
                                <td>5%</td>
                                <td>5</td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div>

                        </div>
                        <div>
                            <!-- Pagination Links -->

                        </div>
                    </div>
                    <!-- End Bordered Table -->
                </div>
            </div><!-- End Right side columns -->
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="priceModal" tabindex="-1" aria-labelledby="priceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="priceModalLabel">Price Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-price">
                        <thead class="custom-header">
                            <tr>
                                <th style="color:white;">Effective From</th>
                                <th style="color:white;">Price</th>
                                <th style="color:white;">Updated By</th>
                            </tr>
                        </thead>
                        <tbody id="priceDetailsTable">
                            <!-- Data will be dynamically injected here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</main><!-- End #main -->
@endsection

<!-- Vendor JS Files -->
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ asset('js/main.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const table = document.getElementById("rawMaterialTable");
        const editTableBtn = document.querySelector(".edit-table-btn");
        const deleteTableBtn = document.querySelector(".delete-table-btn");
        const selectAllCheckbox = document.getElementById('select-all');
        const rows = document.querySelectorAll('#rawMaterialTable tr');
        const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
        let isEditing = false; // Track if edit mode is active

        // Function to get all row checkboxes dynamically
        const getRowCheckboxes = () => document.querySelectorAll('.row-checkbox');

        // Function to toggle editing mode for selected rows
        const toggleEditMode = (enable) => {
            table.querySelectorAll("tr").forEach(row => {
                const checkbox = row.querySelector(".row-checkbox");
                const priceText = row.querySelector(".price-text");
                const priceInput = row.querySelector(".price-input");

                if (checkbox && priceText && priceInput) {
                    if (checkbox.checked && enable) {
                        // Enable editing
                        priceText.classList.add("d-none");
                        priceInput.classList.remove("d-none");
                    } else {
                        // Disable editing
                        priceInput.classList.add("d-none");
                        priceText.classList.remove("d-none");
                    }
                }
            });
        };

        // Function to add Save and Cancel buttons
        const showSaveCancelButtons = () => {
            const actionButtonsContainer = document.querySelector(".action-buttons");
            actionButtonsContainer.innerHTML = `
            <button class="btn btn-sm save-btn me-2" style="background-color: #28a745; color: white; border-radius: 50%; padding: 10px;">
                <i class="fas fa-save"></i>
            </button>
            <button class="btn btn-sm cancel-btn" style="background-color: #dc3545; color: white; border-radius: 50%; padding: 10px;">
                <i class="fas fa-times"></i>
            </button>
        `;

            // Add functionality to Save and Cancel buttons
            document.querySelector(".save-btn").addEventListener("click", saveChanges);
            document.querySelector(".cancel-btn").addEventListener("click", cancelEditing);
        };

        // Function to restore Edit/Delete buttons
        const showEditDeleteButtons = () => {
            isEditing = false;
            const actionButtonsContainer = document.querySelector(".action-buttons");
            actionButtonsContainer.innerHTML = `
            <button class="btn btn-sm edit-table-btn me-2" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                <i class="fas fa-edit" style="color: black;"></i>
            </button>
            <button class="btn btn-sm delete-table-btn" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                <i class="fas fa-trash" style="color: red;"></i>
            </button>
        `;

            // Reassign the edit button functionality
            document.querySelector(".edit-table-btn").addEventListener("click", enableEditing);
        };

        // Function to save changes
        const saveChanges = () => {
            const updatedData = [];
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // CSRF token

            table.querySelectorAll("tr").forEach(row => {
                const checkbox = row.querySelector(".row-checkbox");
                const priceText = row.querySelector(".price-text");
                const priceInput = row.querySelector(".price-input");

                if (checkbox && checkbox.checked && !priceInput.classList.contains("d-none")) {
                    // Collect data for this row
                    const materialId = row.getAttribute("data-id"); // Ensure row has a `data-id` attribute for identifying raw material
                    const updatedPrice = priceInput.value.trim();

                    updatedData.push({
                        id: materialId,
                        price: updatedPrice
                    });
                }
            });

            // Send data to the server via AJAX
            if (updatedData.length > 0) {
                fetch("{{ route('overheads.updatePrices') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": token
                        },
                        body: JSON.stringify({
                            updatedMaterials: updatedData
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Success message
                            alert("Prices updated successfully!");

                            // Update price text and exit editing mode
                            updatedData.forEach(item => {
                                const row = document.querySelector(`tr[data-id="${item.id}"]`);
                                if (row) {
                                    const priceText = row.querySelector(".price-text");
                                    const priceInput = row.querySelector(".price-input");
                                    priceText.textContent = item.price;
                                    priceInput.value = item.price;
                                }
                            });
                            exitEditingMode();
                        } else {
                            alert("Failed to update prices. Please try again.");
                        }
                    })
                    .catch(error => {
                        console.error("Error updating prices:", error);
                        alert("An error occurred. Please try again.");
                    });
            } else {
                alert("No rows selected for saving.");
            }
        };


        // Function to cancel editing
        const cancelEditing = () => {
            const selectedRows = Array.from(getRowCheckboxes()).filter(checkbox => checkbox.checked);
            if (selectedRows.length === 0) {
                // No rows selected, prompt the user to select rows
                alert("Please select at least one row to cancel.");
                return;
            }

            table.querySelectorAll("tr").forEach(row => {
                const checkbox = row.querySelector(".row-checkbox");
                const priceText = row.querySelector(".price-text");
                const priceInput = row.querySelector(".price-input");

                if (checkbox.checked && !priceInput.classList.contains("d-none")) {
                    // Revert input value to original price text for selected row
                    priceInput.value = priceText.textContent;
                }
            });
            exitEditingMode();
        };

        // Function to exit edit mode
        const exitEditingMode = () => {
            toggleEditMode(false);
            isEditing = false;
            showEditDeleteButtons();
        };

        // Function to enable editing
        const enableEditing = () => {
            let isAnyRowSelected = false;

            // Check if any row is selected
            getRowCheckboxes().forEach(checkbox => {
                if (checkbox.checked) isAnyRowSelected = true;
            });

            if (isAnyRowSelected) {
                isEditing = true;
                toggleEditMode(true);
                showSaveCancelButtons();
            } else {
                alert("Please select at least one row to edit.");
            }
        };

        // Event listener for Select All checkbox
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;

            // Toggle all row checkboxes
            getRowCheckboxes().forEach((checkbox) => {
                checkbox.checked = isChecked;
            });
            // Automatically enable edit mode if at least one row is selected
            if (isChecked && isEditing) {
                enableEditing();
            } else {
                exitEditingMode();
            }
        });

        // Event listener for individual row checkboxes
        const updateSelectAllState = () => {
            const allCheckboxes = getRowCheckboxes();
            const allChecked = Array.from(allCheckboxes).every((checkbox) => checkbox.checked);

            // Update Select All checkbox state
            selectAllCheckbox.checked = allChecked;

            // Automatically enable or disable edit mode based on selections
            const anyChecked = Array.from(allCheckboxes).some((checkbox) => checkbox.checked);

            if (anyChecked && isEditing) {
                // editTableBtn.addEventListener("click", enableEditing);
                enableEditing();
            } else {
                exitEditingMode();
                // cancelEditing();
            }

        };

        getRowCheckboxes().forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                // const unselectedRows = Array.from(getRowCheckboxes()).filter(chk => !chk.checked);
                // If a row is unselected and editing mode is enabled, cancel editing mode
                // if (unselectedRows) {
                //     exitEditingMode();
                // }

                updateSelectAllState();
            });
        });

        // Initialize Edit button functionality
        editTableBtn.addEventListener("click", enableEditing);



        const priceModal = new bootstrap.Modal(document.getElementById("priceModal")); // Initialize Bootstrap Modal

        const showPriceModal = (materialId) => {
            const url = `{{ route('overheads.priceHistory', ':id') }}`.replace(':id', materialId);

            fetch(url) // API endpoint to fetch price details
                .then((response) => response.json())
                .then((data) => {
                    const modalTableBody = document.getElementById("priceDetailsTable");
                    modalTableBody.innerHTML = ""; // Clear previous data

                    if (data.priceDetails.length > 0) {
                        data.priceDetails.forEach((detail) => {
                            modalTableBody.innerHTML += `
                                    <tr>
                                        <td>${detail.updated_at}</td>
                                        <td>${detail.new_price}</td>
                                        <td>${detail.updated_by}</td>
                                    </tr>
                                `;
                        });
                    } else {
                        modalTableBody.innerHTML = `
                                <tr>
                                    <td colspan="3" class="text-center">No price details available</td>
                                </tr>
                            `;
                    }

                    priceModal.show(); // Show modal after populating
                })
                .catch((error) => {
                    console.error("Error fetching price details:", error);
                    alert("Unable to fetch price details. Please try again.");
                });
        };

        // Attach click event listener to each price column
        table.querySelectorAll(".price-text").forEach((priceElement) => {
            const row = priceElement.closest("tr");
            const materialId = row.getAttribute("data-id");

            priceElement.addEventListener("click", () => {
                showPriceModal(materialId);
            });
        });

        // Listen for change events on category checkboxes
        categoryCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', filterRawMaterials);
        });

        function filterRawMaterials() {
            // Get all selected categories
            const selectedCategories = Array.from(categoryCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value.toLowerCase().trim());

            rows.forEach(row => {
                const categoryCells = row.querySelector('td:nth-child(5)').textContent.toLowerCase().split(', ');
                let matches = false;

                // Check if any of the selected categories match the categories of the raw material row
                selectedCategories.forEach(selectedCategory => {
                    // Check if the selected category exists in the row's categories
                    if (categoryCells.some(category => category.trim() === selectedCategory)) {
                        matches = true;
                    }
                });

                // Show or hide the row based on the match
                if (selectedCategories.length === 0 || matches) {
                    row.style.display = ''; // Show row
                } else {
                    row.style.display = 'none'; // Hide row
                }
            });
            updateSerialNumbers();
        }

        function updateSerialNumbers() {
            // Get all visible rows
            const visibleRows = Array.from(document.querySelectorAll("#rawMaterialTable tr"))
                .filter(row => row.style.display !== 'none');

            // Update serial numbers for visible rows only
            visibleRows.forEach((row, index) => {
                const snoCell = row.querySelector("td:nth-child(2)"); // Adjust the column index for S.NO
                if (snoCell) {
                    snoCell.textContent = `${index + 1}.`; // Update the serial number
                }
            });
        }

    });

    function filterCategories() {
        // Get the search input value
        const searchValue = document.getElementById('categorySearch').value.toLowerCase();
        const keywords = searchValue.split(',').map(keyword => keyword.trim()).filter(keyword => keyword);
        // Get all category items
        const categoryItems = document.querySelectorAll('.category-item');

        // If the search box is empty, show all categories
        if (keywords.length === 0) {
            categoryItems.forEach((item) => {
                item.style.display = ''; // Show all items
            });
            return;
        }
        // Loop through category items and filter them
        categoryItems.forEach((item) => {
            const label = item.querySelector('.form-check-label').textContent.toLowerCase();

            // Check if any of the keywords match the label
            const isVisible = keywords.some(keyword => label.includes(keyword));

            // Show or hide the category item based on the match
            item.style.display = isVisible ? '' : 'none';
        });
    }
</script>