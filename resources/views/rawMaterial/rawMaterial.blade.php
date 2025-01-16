@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Raw Material</h1>
        <a href="{{ 'addrawmaterial' }}" class='text-decoration-none ps-add-btn text-white py-1 px-4'>
            <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
        </a>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <!-- Left side columns -->
            <div class="col-lg-2 px-4">
                <!-- Categories Section -->
                <div class="card" style="background-color: #EEEEEE;">
                    <div class="card-body">
                        <h5 class="card-title">Categories</h5>
                        <div class="row mb-3">
                            <div class="col-sm-10">
                                <div>
                                    <input
                                    type="text"
                                    id="categorySearch"
                                    class="form-control mb-3"
                                    placeholder="Search categories..."
                                    onkeyup="filterCategories()"
                                />
                                </div>

                                @foreach($categoryitems as $category)
                                <div class="form-check category-item">
                                    <input
                                        class="form-check-input category-checkbox"
                                        type="checkbox"
                                        data-id="category_{{ $category->id }}"
                                        value="{{ $category->itemname }}"
                                        {{-- data-category-name="{{ $category->itemname }}" --}}>
                                    <label class="form-check-label" for="category_{{ $category->id }}">
                                        {{ $category->itemname }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div><!-- End Categories Section -->
            </div><!-- End Left side columns -->

            <!-- <div class="col-lg-1"></div> -->

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
                                <th class="head" scope="col">
                                    <input type="checkbox" id="select-all" class="form-check-input">
                                </th>
                                <th scope="col" style="color:white;">S.NO</th>
                                <th scope="col" style="color:white;">Raw Materials</th>
                                <th scope="col" style="color:white;">RM Code</th>
                                <th scope="col" style="color:white;">Raw Material Category</th>
                                <th scope="col" style="color:white;">Price(Rs)</th>
                                <th scope="col" style="color:white;">UoM</th>
                            </tr>
                        </thead>
                        <tbody id="rawMaterialTable">
                            @foreach ($rawMaterials as $index => $material)
                            <tr data-id="{{ $material->id }}">
                                <td>
                                    <input type="checkbox" class="form-check-input row-checkbox">
                                </td>
                                <td>{{ $index + 1 }}.</td> <!-- Auto-increment S.NO -->
                                <td><a href="{{ route('rawMaterial.edit', $material->id) }}" style="color: black;font-size:16px;text-decoration: none;">{{ $material->name }}</a></td> <!-- Raw Material Name -->
                                <td>{{ $material->rmcode }}</td> <!-- RM Code -->
                                <td>
                                    {{ $material->category_name1 ?? '' }}
                                    {{ $material->category_name2 ? ', ' . $material->category_name2 : '' }}
                                    {{ $material->category_name3 ? ', ' . $material->category_name3 : '' }}
                                    {{ $material->category_name4 ? ', ' . $material->category_name4 : '' }}
                                    {{ $material->category_name5 ? ', ' . $material->category_name5 : '' }}
                                    {{ $material->category_name6 ? ', ' . $material->category_name6 : '' }}
                                    {{ $material->category_name7 ? ', ' . $material->category_name7 : '' }}
                                    {{ $material->category_name8 ? ', ' . $material->category_name8 : '' }}
                                    {{ $material->category_name9 ? ', ' . $material->category_name9 : '' }}
                                    {{ $material->category_name10 ? ', ' . $material->category_name10 : '' }}
                                </td>
                                <td>
                                    <span class="price-text">{{ $material->price }}</span>
                                    <input type="text" class="form-control price-input d-none" style="width: 80px;" value="{{ $material->price }}">
                                </td>
                                <td>{{ $material->uom }}</td> <!-- UoM -->
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <!-- Content like "Showing 1 to 10 of 50 entries" -->
                            Showing {{ $rawMaterials->firstItem() }} to {{ $rawMaterials->lastItem() }} of {{ $rawMaterials->total() }} entries
                        </div>
                        <div>
                            <!-- Pagination Links -->
                            {{ $rawMaterials->links('pagination::bootstrap-5') }}
                            {{-- {{ $rawMaterials->appends(['category_ids' => implode(',', request()->input('category_ids', []))])->links('pagination::bootstrap-5') }} --}}

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
                fetch("{{ route('rawMaterial.updatePrices') }}", {
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
            const url = `{{ route('rawMaterial.priceHistory', ':id') }}`.replace(':id', materialId);

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

        function filterRawMaterials2() {
    // Get selected categories
    const selectedCategories = Array.from(categoryCheckboxes)
        .filter(checkbox => checkbox.checked)
        .map(checkbox => checkbox.value);
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Send an AJAX request to the server with the selected categories
    fetch(`/rawmaterial?category_ids=${selectedCategories.join(',')}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': token,  // Add CSRF token
        }
    })
    .then(response => response.json())
    .then(data => {
        // Assuming your table rows are in a tbody element
        const tbody = document.querySelector('#raw-materials-table tbody');
        tbody.innerHTML = ''; // Clear existing rows

        // Loop through the returned raw materials and update the table
        data.rawMaterials.forEach(material => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${material.name}</td>
                <td>${material.rmcode}</td>
                <td>${material.price}</td>
                <td>${material.uom}</td>
                <td>${material.category_name1}, ${material.category_name2}, ${material.category_name3}, ${material.category_name4}, ${material.category_name5}</td>
            `;
            tbody.appendChild(row);
        });
    })
    .catch(error => {
        console.error("Error fetching filtered data:", error);
    });
}

        /* For filter Functions*/
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

    function updateRawMaterialsTable(rawMaterials) {
    const tableBody = document.querySelector('#rawMaterialTable tbody');
    tableBody.innerHTML = '';  // Clear the existing table rows

    rawMaterials.forEach(rawMaterial => {
        const row = document.createElement('tr');
        row.innerHTML = `
         <td>${rawMaterial.name}</td>
            <td>${rawMaterial.rmcode}</td>
            <td>${rawMaterial.price}</td>
            <td>${rawMaterial.uom}</td>
            <td>${rawMaterial.category_name1}
            ${rawMaterial.category_name2}
            ${rawMaterial.category_name3}
            ${rawMaterial.category_name4}
            ${rawMaterial.category_name5}</td>
             <td>${rawMaterial.uom}</td>
        `;
        tableBody.appendChild(row);
    });
}

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

/*
    function sorting() {
    // Get selected category IDs
    const selectedCategories = Array.from(categoryCheckboxes)
        .filter(checkbox => checkbox.checked)
        .map(checkbox => checkbox.value);

        fetch(`/rawmaterial?rawMaterials=${selectedCategories.join(',')}`, {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }, // Important for Laravel AJAX detection
        })
    .then((response) => response.json())
    .then((data) => {
        const rawMaterials = data.rawMaterials;
        const tbody = document.querySelector('#rawMaterialTable tbody');
        tbody.innerHTML = ''; // Clear existing rows

        rawMaterials.forEach((item, index) => {
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.name}</td>
                    <td>${item.rmcode}</td>
                     <td>
                                    ${ $item->category_name1 ?? '' }}
                                    ${ $item->category_name2 ? ', ' . $item->category_name2 : '' }}
                                    ${ $item->category_name3 ? ', ' . $item->category_name3 : '' }}
                                    ${ $item->category_name4 ? ', ' . $item->category_name4 : '' }}
                                    ${ $item->category_name5 ? ', ' . $item->category_name5 : '' }}
                                </td>
                    <td>${item.price}</td>
                    <td>${item.uom}</td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', row);
        });
    })
    .catch((error) => console.error('Error fetching data:', error));

    }
    */

    /* end DomLoad section*/
</script>
