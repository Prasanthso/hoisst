@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Raw Material</h1>

        <div>
            <a href="{{ 'addrawmaterial' }}" class='text-decoration-none ps-add-btn text-white py-1 px-4'>
                <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
            </a>
            <button id="exportBtn" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export to Excel
            </button>
            <button id="exportPdfBtn" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Export to PDF
            </button>
        </div>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            @if(session('success'))
            <div id="success-message" class="alert alert-success">{{ session('success') }}</div>
            @endif
            <!-- Left side columns -->
            <div class="col-lg-2 px-2 mt-5">
                <!-- Categories Section -->
                <div class="card" style="background-color: #EEEEEE;">
                    <div class="card-body">
                        <h5 class="card-title">Categories</h5>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <div>
                                    <input
                                        type="text"
                                        id="categorySearch"
                                        class="form-control mb-3"
                                        placeholder="Search categories..."
                                        onkeyup="filterCategories()" />
                                </div>

                                @foreach($categoryitems as $category)
                                <div class="form-check category-item">
                                    <input
                                        class="form-check-input category-checkbox"
                                        type="checkbox"
                                        data-id="category_{{ $category->id }}"
                                        value="{{ $category->id }}"
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
                    <table class="table table-bordered mt-2" id='exportRm'>
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
                                <td>{{ ($rawMaterials->currentPage() - 1) * $rawMaterials->perPage() + $loop->iteration }}.</td> <!-- Auto-increment S.NO -->
                                <td class="left-align"><a href="{{ route('rawMaterial.edit', $material->id) }}" style="color: black;font-size:16px;text-decoration: none; text-align:left;">{{ $material->name }}</a></td> <!-- Raw Material Name -->
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

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

        document.getElementById('exportBtn').addEventListener('click', function() {
            const table = document.getElementById('exportRm'); // Ensure this ID exists in your table
            if (!table) {
                console.error('Table with ID "exportRm" not found.');
                return;
            }
            console.log('Table with ID "exportRm" not found.');

            const rows = Array.from(table.querySelectorAll('tr')); // Get all rows
            const visibleData = [];
            let serialNumber = 1; // Initialize serial number

            // Iterate through each row
            rows.forEach((row, rowIndex) => {
                if (row.style.display !== 'none') { // Only include visible rows
                    const cells = Array.from(row.children);
                    const rowData = [];

                    if (rowIndex > 0) {
                        rowData.push(serialNumber++); // Auto-increment serial number
                    } else {
                        rowData.push("S.NO"); // Add "S.NO" to the header row
                    }

                    cells.forEach((cell, index) => {
                        if (index !== 0) { // Skip checkboxes column
                            rowData.push(cell.innerText.trim());
                        }
                    });

                    visibleData.push(rowData);
                }
            });

            // Convert data to workbook
            const ws = XLSX.utils.aoa_to_sheet(visibleData);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Raw Material Report');

            // Export as an Excel file
            XLSX.writeFile(wb, 'raw_material_list.xlsx');
        });

        // PDF Export Function
        document.getElementById('exportPdfBtn').addEventListener('click', function() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            const table = document.getElementById('exportRm');
            if (!table) {
                console.error('Table with ID "exportRm" not found.');
                return;
            }

            const rows = Array.from(table.querySelectorAll('tr'));
            const tableData = [];
            let serialNumber = 1;

            rows.forEach((row, rowIndex) => {
                if (row.style.display !== 'none') {
                    const cells = Array.from(row.children);
                    const rowData = [];

                    if (rowIndex > 0) {
                        rowData.push(serialNumber++);
                    } else {
                        rowData.push("S.NO");
                    }

                    cells.forEach((cell, index) => {
                        if (index !== 0) { // Skip checkboxes column
                            rowData.push(cell.innerText.trim());
                        }
                    });

                    tableData.push(rowData);
                }
            });

            // Add Table to PDF
            doc.autoTable({
                head: [tableData[0]], // Header row
                body: tableData.slice(1), // Table content
                startY: 20,
                theme: 'striped',
            });

            doc.save('raw_material_list.pdf');
        });

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
            document.querySelector(".delete-table-btn").addEventListener("click", deleteRows);
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

        // Function to handle row deletion
        const deleteRows = () => {
            const selectedRows = Array.from(getRowCheckboxes()).filter(checkbox => checkbox.checked);
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // CSRF token
            console.log(selectedRows);
            if (selectedRows.length === 0) {
                alert("Please select at least one row to delete.");
                return;
            }
            const selectedIds = selectedRows.map(checkbox => checkbox.closest('tr').getAttribute('data-id'));
            // if (!confirm(`Are you sure you want to delete ${selectedIds.length} selected row(s)?`)) {
            //     return;
            // }

            fetch("{{ route('rawMaterial.delete') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": token
                    },
                    body: JSON.stringify({
                        ids: selectedIds // Array of IDs to delete
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("data: ", data);
                    if (data.success) {
                        if (data.confirm) {
                        // If confirmation is required, show a confirmation dialog
                        if (confirm("Are you want to delete this item of raw material. Do you want to proceed?")) {
                            // Make a second request to actually delete (mark inactive)
                            fetch('/confirmrawmaterial', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    "X-CSRF-TOKEN": token
                                },
                                body: JSON.stringify({ ids: selectedIds }) // Send the selected IDs again
                            })
                            .then(response => response.json())
                            .then(confirmData  => {
                                if (confirmData.success) {
                                    selectedRows.forEach(checkbox => {
                                        const row = checkbox.closest("tr");
                                        row.remove();
                                    });
                                    updateSerialNumbers();
                                    alert("Selected rows deleted successfully!");
                                }
                            })
                            .catch(error => {
                                console.error("Error confirming deletion:", error);
                                alert("An error occurred. Please try again.");
                            });
                        }
                    }
                }
                else
                    {
                        alert("No raw materials item can be deleted. They might be in use.");
                    }
                })
                .catch(error => {
                    console.error("Error deleting rows:", error);
                    alert("An error occurred. Please try again.");
                });
        };

        deleteTableBtn.addEventListener("click", deleteRows);

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
            checkbox.addEventListener('change', () => {
                const selectedCategories = Array.from(
                    document.querySelectorAll('.category-checkbox:checked')
                ).map(cb => cb.value);

                if (selectedCategories.length > 0) {
                    const queryParams = new URLSearchParams({
                        category_ids: selectedCategories.join(','),
                    });
                    console.log(queryParams.toString());
                    // Construct the URL dynamically based on selected categories
                    const url = `/rawmaterial?${queryParams.toString()}`;

                    // Fetch updated data from server
                    fetch(url, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Clear existing table content
                            rawMaterialTable.innerHTML = '';
                            console.log('Fetched Data:', data.rawMaterials);
                            // Populate the table with new data
                            data.rawMaterials.forEach((item, index) => {
                                rawMaterialTable.innerHTML += `
                        <tr data-id="${item.id}">
                            <td><input type="checkbox" class="form-check-input row-checkbox" value="${item.id}"></td>
                            <td>${index + 1}.</td>
                            <td class="left-align"><a href="/editrawmaterial/${item.id}" style="color: black; font-size:16px; text-decoration: none;">${item.name}</a></td>
                            <td>${item.rmcode}</td>
                             <td>
                                ${item.category_name1 ?? ''}
                                ${item.category_name2 ? ', ' + item.category_name2 : ''}
                                ${item.category_name3 ? ', ' + item.category_name3 : ''}
                                ${item.category_name4 ? ', ' + item.category_name4 : ''}
                                ${item.category_name5 ? ', ' + item.category_name5 : ''}
                                ${item.category_name6 ? ', ' + item.category_name6 : ''}
                                ${item.category_name7 ? ', ' + item.category_name7 : ''}
                                ${item.category_name8 ? ', ' + item.category_name8 : ''}
                                ${item.category_name9 ? ', ' + item.category_name9 : ''}
                                ${item.category_name10 ? ', ' + item.category_name10 : ''}
                            </td> <!-- Categories -->
                            <td>
                                <span class="price-text">${item.price}</span>
                                <input type="text" class="form-control price-input d-none" style="width: 80px;" value="${item.price}">
                            </td>
                            <td>${item.uom}</td>
                        </tr>
                    `;
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while fetching rawMaterials(s).');
                        });
                } else {
                    location.reload();
                }
            });
        });

        /* For filter Functions*/
        /*
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
        */

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

        setTimeout(function() {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 3000);

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
