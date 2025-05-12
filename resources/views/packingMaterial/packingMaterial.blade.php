@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Packing Material</h1>
        <div>
            <a href="{{ 'addpackingmaterial' }}" class='text-decoration-none ps-add-btn text-white py-1 px-4'>
                <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
            </a>
            <a href="{{ url('/packingMaterial-excel') }}" download class="btn" data-bs-toggle="tooltip" title="Download packingMaterial excel File">
                <i class="bi bi-download fs-4"></i>
            </a>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                Import
            </button>
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
            @if(session('error'))
            <div id="error-message" class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <!-- Left side columns -->
            <div class="col-lg-2 px-2 mt-5">
                <!-- Categories Section -->
                <div class="card" style="background-color: #EEEEEE;">
                    <div class="card-body">
                        <h5 class="card-title">Categories</h5>
                        <div class="form-check form-check-inline">
                                <input class="form-check-input single-check" type="checkbox" id="inActive" name="inActive" value="inactive">
                                <label class="form-check-label small" for="inActive">Inactive</label>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <div class="me-2 align-items-center d-flex mb-2">
                                    <div class="input-group" style="width: 250px;">
                                        <select class="form-select me-2" id="searchtype" style="width: 30px;">
                                            <option value="items">Material Name</option>
                                            <option value="category">Category</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <input
                                        type="text"
                                        id="categorySearch"
                                        class="form-control mb-3"
                                        placeholder="Search ..."
                                        {{-- onkeyup="filterCategories()" --}} />
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
                    {{-- <div class="d-flex align-items-center justify-content-between mb-2"> --}}
                        <!-- Checkbox Group -->
                        {{-- <div class="d-flex">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input single-check" type="checkbox" id="Active" name="Active" value="active" checked>
                                <label class="form-check-label small" for="Active">Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input single-check" type="checkbox" id="inActive" name="inActive" value="inactive">
                                <label class="form-check-label small" for="inActive">Inactive</label>
                            </div>
                        </div> --}}
                    <!-- Action Buttons -->
                    {{-- <div class="d-flex action-buttons"> --}}
                        <button class="btn btn-sm edit-table-btn me-2" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                            <i class="fas fa-edit" style="color: black;"></i>
                        </button>
                        <button class="btn btn-sm delete-table-btn" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                            <i class="fas fa-trash" style="color: red;"></i>
                        </button>
                    {{-- </div> --}}
                </div>
                    <!-- Bordered Table -->
                    <table class="table table-bordered mt-2" id='exportRm'>
                        <thead class="custom-header">
                            <tr>
                                <th class="head" scope="col">
                                    <input type="checkbox" id="select-all" class="form-check-input">
                                </th>
                                <th scope="col" style="color:white;">S.NO</th>
                                <th scope="col" style="color:white;">Packing Materials</th>
                                <th scope="col" style="color:white;">PM Code</th>
                                <th scope="col" style="color:white;">Packing Material Category</th>
                                <th scope="col" style="color:white;">Price(Rs)</th>
                                <th scope="col" style="color:white;">UoM</th>
                                <th scope="col" style="color:white;">Status</th>
                            </tr>
                        </thead>
                        <tbody id="packingMaterialTable">
                            @foreach ($packingMaterials as $index => $material)
                            <tr data-id="{{ $material->id }}">
                                <td>
                                    <input type="checkbox" class="form-check-input row-checkbox">
                                </td>
                                <td>{{ ($packingMaterials->currentPage() - 1) * $packingMaterials->perPage() + $loop->iteration }}.</td> <!-- Auto-increment S.NO -->
                                <td class="left-align"><a href="{{ route('packingMaterial.edit', $material->id) }}" style="color: black;font-size:16px;text-decoration: none;">{{ $material->name }}</a></td> <!-- Raw Material Name -->
                                <td>{{ $material->pmcode }}</td> <!-- RM Code -->
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
                                <td class="d-flex justify-content-between align-items-center">
                                    <span class="price-text">{{ $material->price }}</span>
                                    <input type="text" class="form-control price-input d-none" style="width: 80px;" value="{{ $material->price }}">
                                    <i class="fas fa-eye ms-2 mt-2 eye-icon" style="font-size: 0.8rem; cursor: pointer; color: #007bff;"></i>
                                </td>
                                <td>{{ $material->uom }}</td> <!-- UoM -->
                                 <td><span class="badge {{ strtolower($material->status) === 'active' ? 'bg-success' : 'bg-danger' }}" style="font-weight: normal;">
                                    {{ $material->status }}
                                </span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div id="showingEntries">
                            <!-- Content like "Showing 1 to 10 of 50 entries" -->
                            Showing {{ $packingMaterials->firstItem() }} to {{ $packingMaterials->lastItem() }} of {{ $packingMaterials->total() }} entries
                            <input type="hidden" id="currentPage" value="{{ $packingMaterials->currentPage() }}">
                            <input type="hidden" id="perPage" value="{{ $packingMaterials->perPage() }}">
                        </div>
                        <div id="paginationWrapper">
                            @if ($packingMaterials->total() > $packingMaterials->perPage())
                                {{ $packingMaterials->links('pagination::bootstrap-5') }}
                            @endif
                        </div>
                        {{-- <div>
                            <!-- Pagination Links -->
                            {{ $packingMaterials->links('pagination::bootstrap-5') }}
                        </div> --}}
                    </div>
                    <!-- End Bordered Table -->
                </div>
            </div><!-- End Right side columns -->
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import for Packing Material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('packingMaterial.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="excelFile" class="form-label">Select Excel File</label>
                            <input type="file" name="excel_file" id="excelFile" class="form-control" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


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
                                <th style="color:white;">Old Price</th>
                                <th style="color:white;">New Price</th>
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
        const table = document.getElementById("packingMaterialTable");
        const editTableBtn = document.querySelector(".edit-table-btn");
        const deleteTableBtn = document.querySelector(".delete-table-btn");
        const selectAllCheckbox = document.getElementById('select-all');
        const rows = document.querySelectorAll('#packingMaterialTable tr');
        const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
        let isEditing = false; // Track if edit mode is active
        let isFilter = false;
        let visibleData = [];

        document.getElementById('exportBtn').addEventListener('click', function() {
            const table = document.getElementById('packingMaterialTable');
            const rows = table.querySelectorAll('tr');
            let exportData = [];
            const header = ["S. NO.", "Packing Materials", "PM Code", "Packing Materials Category", "Price(Rs)", "UoM"];
            exportData.push(header);
            let serial = 1;

            visibleData.forEach(item => {
                const categories = [
                        item.category_name1, item.category_name2, item.category_name3,
                        item.category_name4, item.category_name5, item.category_name6,
                        item.category_name7, item.category_name8, item.category_name9,
                        item.category_name10
                    ].filter(Boolean).join(', ');
                    exportData.push([
                        serial++, // Serial number
                        item.name, // Raw Material Name
                        item.pmcode, // RM Code
                        categories, // Raw Materials Category
                        item.price, // Price (you can directly use it from item)
                        item.uom // UoM
                    ]);
                });
            if (isFilter) {
                // Export filtered data
                // exportData = exportData.concat(visibleData);
                const ws = XLSX.utils.aoa_to_sheet(exportData);
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'packingMaterials');
                XLSX.writeFile(wb, 'packingMaterials_filtered.xlsx');
            } else {
                // Export all from backend
                fetch('/packingMaterials/export-all')
                    .then(response => response.json())
                    .then(data => {
                        data.forEach((item, index) => {
                            exportData.push([
                                index + 1,
                                item.name,
                                item.pmcode,
                                item.categories, // Comes as comma-separated string
                                item.price,
                                item.uom
                            ]);
                        });

                        const ws = XLSX.utils.aoa_to_sheet(exportData);
                        const wb = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(wb, ws, 'packingMaterials');
                        XLSX.writeFile(wb, 'packingMaterials_all.xlsx');
                    })
                    .catch(error => {
                        console.error('Export error:', error);
                        alert('Failed to export all data.');
                    });
            }
        });

        document.getElementById('exportPdfBtn').addEventListener('click', function() {
            const table = document.getElementById('packingMaterialTable');
            const rows = table.querySelectorAll('tr');

            const summaryText = document.querySelector('.d-flex')?.innerText || '';
            const totalMatch = summaryText.match(/of\s+(\d+)\s+entries/i);
            const totalEntries = totalMatch ? parseInt(totalMatch[1]) : 0;

            // Add S. No. to header
            const header = ["S. No.", "Packing Materials", "PM Code", "Packing Materials Category", "Price(Rs)", "UoM"];
            let exportData = [];

            // Get visible rows from DOM table
            let count = 1;
            visibleData.forEach(item => {
                const categories = [
                        item.category_name1, item.category_name2, item.category_name3,
                        item.category_name4, item.category_name5, item.category_name6,
                        item.category_name7, item.category_name8, item.category_name9,
                        item.category_name10
                    ].filter(Boolean).join(', ');
                    exportData.push([
                        count++, // Serial number
                        item.name, // Raw Material Name
                        item.pmcode, // RM Code
                        categories, // Raw Materials Category
                        item.price, // Price (you can directly use it from item)
                        item.uom // UoM
                    ]);
                });

            if (isFilter)  {
                console.log("Exporting visible data:", exportData);
                generatePdf(header, exportData, 'packingMaterials_filtered.pdf');
            } else {
                // Fetch all data from backend
                fetch('/packingMaterials/export-all')
                    .then(response => {
                        if (!response.ok) throw new Error('Fetch failed');
                        return response.json();
                    })
                    .then(data => {
                        console.log("Fetched full data:", data);

                        const allData = data.map((item, index) => [
                            index + 1, // S. No.
                            item.name || '',
                            item.pmcode || '',
                            item.categories || '',
                            item.price || '',
                            item.uom || ''
                        ]);

                        console.log("Final data to export:", allData);
                        generatePdf(header, allData, 'packingMaterials_all.pdf');
                    })
                    .catch(error => {
                        console.error('PDF Export Error:', error);
                        alert('Failed to export PDF.');
                    });
            }
        });

        function generatePdf(header, data, filename) {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            doc.autoTable({
                head: [header],
                body: data,
                startY: 20,
                styles: {
                    fontSize: 10
                },
                headStyles: {
                    fillColor: [0, 123, 255]
                }
            });

            doc.save(filename);
        }


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
                fetch("{{ route('packingMaterial.updatePrices') }}", {
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
            const url = `{{ route('packingMaterial.priceHistory', ':id') }}`.replace(':id', materialId);

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
                                        <td>${detail.old_price}</td>
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

            if (selectedRows.length === 0) {
                alert("Please select at least one row to delete.");
                return;
            }
            const selectedIds = selectedRows.map(checkbox => checkbox.closest('tr').getAttribute('data-id'));

            // if (!confirm(`Are you sure you want to delete ${selectedIds.length} selected row(s)?`)) {
            //     return;
            // }

            fetch("{{ route('packingMaterial.delete') }}", {
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
                    if (data.success) {
                        if (data.confirm) {
                            // If confirmation is required, show a confirmation dialog
                            if (confirm("Are you want to delete this item of packing material. Do you want to proceed?")) {
                                // Make a second request to actually delete (mark inactive)
                                fetch('/confirmPackingmaterial', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            "X-CSRF-TOKEN": token
                                        },
                                        body: JSON.stringify({
                                            ids: selectedIds
                                        }) // Send the selected IDs again
                                    })
                                    .then(response => response.json())
                                    .then(confirmData => {
                                        if (confirmData.success) {
                                            selectedRows.forEach(checkbox => {
                                                const row = checkbox.closest("tr");
                                                row.remove();
                                            });
                                            updateSerialNumbers();
                                            alert("Selected rows deleted successfully!");
                                            window.location.reload();
                                        }
                                    })
                                    .catch(error => {
                                        console.error("Error confirming deletion:", error);
                                        alert("An error occurred. Please try again.");
                                    });
                            }
                        }
                    } else {
                        alert("No packing materials item can be deleted. They might be in use.");
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

        // Select all elements with the class 'eye-icon' within the table
        table.querySelectorAll(".eye-icon").forEach((iconElement) => {
            const row = iconElement.closest("tr");
            const materialId = row.getAttribute("data-id");

            iconElement.addEventListener("click", () => {
                showPriceModal(materialId);
            });
        });
        // Listen for change events on category checkboxes
        categoryCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                const selectedCategories = Array.from(
                    document.querySelectorAll('.category-checkbox:checked')
                ).map(cb => cb.value);
                isFilter = true;
                if (selectedCategories.length > 0) {
                    const queryParams = new URLSearchParams({
                        category_ids: selectedCategories.join(','),
                    });
                    console.log(queryParams.toString());
                    // Construct the URL dynamically based on selected categories
                    const url = `/packingmaterial?${queryParams.toString()}`;

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
                            filteredData = data.packingMaterials;
                            visibleData = data.packingMaterials;
                            currentPage = 1; // reset to page 1 on new filter
                            renderTablePage(currentPage, filteredData);
                            renderPagination(filteredData.length);
                    //         // Clear existing table content
                    //         packingMaterialTable.innerHTML = '';
                    //         console.log('Fetched Data:', data.packingMaterials);
                    //         // Populate the table with new data
                    //         data.packingMaterials.forEach((item, index) => {
                    //             packingMaterialTable.innerHTML += `
                    //     <tr data-id="${item.id}">
                    //         <td><input type="checkbox" class="form-check-input row-checkbox" value="${item.id}"></td>
                    //         <td>${index + 1}.</td>
                    //         <td class="left-align"><a href="/editpackingmaterial/${item.id}" style="color: black; font-size:16px; text-decoration: none;">${item.name}</a></td>
                    //         <td>${item.pmcode}</td>
                    //          <td>
                    //             ${item.category_name1 ?? ''}
                    //             ${item.category_name2 ? ', ' + item.category_name2 : ''}
                    //             ${item.category_name3 ? ', ' + item.category_name3 : ''}
                    //             ${item.category_name4 ? ', ' + item.category_name4 : ''}
                    //             ${item.category_name5 ? ', ' + item.category_name5 : ''}
                    //             ${item.category_name6 ? ', ' + item.category_name6 : ''}
                    //             ${item.category_name7 ? ', ' + item.category_name7 : ''}
                    //             ${item.category_name8 ? ', ' + item.category_name8 : ''}
                    //             ${item.category_name9 ? ', ' + item.category_name9 : ''}
                    //             ${item.category_name10 ? ', ' + item.category_name10 : ''}
                    //         </td> <!-- Categories -->
                    //         <td>
                    //             <span class="price-text">${item.price}</span>
                    //             <input type="text" class="form-control price-input d-none" style="width: 80px;" value="${item.price}">
                    //         </td>
                    //         <td>${item.uom}</td>
                    //     </tr>
                    // `;
                    //         });

                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while fetching packingMaterials.');
                        });
                } else {
                    location.reload();
                }
            });

        });

        let filteredData = []; // store the filtered data globally
        let currentPage = 1;
        const maxPerPage = 10;

    function renderTablePage(page, data) {
        const start = (page - 1) * maxPerPage;
        const end = page * maxPerPage;
        const sliced = data.slice(start, end);
        const table = document.getElementById('packingMaterialTable');
        table.innerHTML = '';

        sliced.forEach((item, index) => {
            const categories = [
                item.category_name1, item.category_name2, item.category_name3,
                item.category_name4, item.category_name5, item.category_name6,
                item.category_name7, item.category_name8, item.category_name9,
                item.category_name10
            ].filter(Boolean).join(', ');

            table.innerHTML += `
                <tr data-id="${item.id}">
                    <td><input type="checkbox" class="form-check-input row-checkbox" value="${item.id}"></td>
                    <td>${start + index + 1}.</td>
                    <td class="left-align"><a href="/editpackingmaterial/${item.id}" style="color: black; font-size:16px; text-decoration: none;">${item.name}</a></td>
                    <td>${item.pmcode}</td>
                    <td>${categories}</td>
                   <td class="d-flex justify-content-between align-items-center">
                        <span class="price-text">${item.price}</span>
                        <input type="text" class="form-control price-input d-none" style="width: 80px;" value="${item.price}">
                        <i class="fas fa-eye ms-2 mt-2 eye-icon" style="font-size: 0.8rem; cursor: pointer; color: #007bff;"></i>
                     </td>
                    <td>${item.uom}</td>
                   <td>
                    <span class="badge" style="background-color: ${item.status.toLowerCase() === 'active' ? 'green' : '#dc3545'}; font-weight: normal;">
                    ${item.status}
                </span>
                </td>
                </tr>
            `;
        });
        const last = Math.min(currentPage * maxPerPage, data.length);
        const showingDiv = document.getElementById('showingEntries');

         showingDiv.textContent = `Showing ${start + 1} to ${last} of ${data.length} entries`;
         // Attach click event listener to each price column
         table.querySelectorAll(".price-text").forEach((priceElement) => {
             const row = priceElement.closest("tr");
             const materialId = row.getAttribute("data-id");

             priceElement.addEventListener("click", () => {
                 showPriceModal(materialId);
             });
         });
        // Select all elements with the class 'eye-icon' within the table
        table.querySelectorAll(".eye-icon").forEach((iconElement) => {
            const row = iconElement.closest("tr");
            const materialId = row.getAttribute("data-id");

            iconElement.addEventListener("click", () => {
                showPriceModal(materialId);
            });
        });
    }

    function renderPagination(totalItems) {
        const totalPages = Math.ceil(totalItems / maxPerPage);
        const wrapper = document.getElementById('paginationWrapper');
        wrapper.innerHTML = '';
           if (totalPages <= 1) return;
    // Previous Button
    const prevBtn = document.createElement('button');
        prevBtn.textContent = 'Previous';
        prevBtn.className = 'btn btn-md border border-primary mx-2';
        if (currentPage === 1) {
            prevBtn.disabled = true;
            prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            prevBtn.onclick = () => {
                currentPage--;
                renderTablePage(currentPage, filteredData);
                renderPagination(totalItems);
            };
        }
        wrapper.appendChild(prevBtn);
        // Page Buttons
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            // btn.className = 'btn btn-md border border-primary text-primary bg-white mx-2';
            // Default class
        btn.className = 'btn btn-md border border-primary mx-1';
        // Apply styles based on whether it's the current page
        if (i === currentPage) {
            btn.classList.add('bg-primary', 'text-white');
        } else {
            btn.classList.add('bg-white', 'text-primary');
        }
            btn.textContent = i;
            btn.onclick = () => {
                currentPage = i;

                renderTablePage(currentPage, filteredData);
                renderPagination(totalItems);
            };
            wrapper.appendChild(btn);
        }
        // Next Button
        const nextBtn = document.createElement('button');
        nextBtn.textContent = 'Next';
        nextBtn.className = 'btn btn-md border border-primary mx-2';
        if (currentPage === totalPages) {
            nextBtn.disabled = true;
            nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            nextBtn.onclick = () => {
                currentPage++;
                renderTablePage(currentPage, filteredData);
                renderPagination(totalItems);
            };
        }
        wrapper.appendChild(nextBtn);
    }

        function updateSerialNumbers() {
            let currentPage = parseInt(document.querySelector("#currentPage").value) || 1; // Get current page number
            let perPage = parseInt(document.querySelector("#perPage").value) || 10;
            // Get all visible rows
            const visibleRows = Array.from(document.querySelectorAll("#packingMaterialTable tr"))
                .filter(row => row.style.display !== 'none');

            // Update serial numbers for visible rows only
            visibleRows.forEach((row, index) => {
                const snoCell = row.querySelector("td:nth-child(2)"); // Adjust the column index for S.NO
                if (snoCell) {
                    snoCell.textContent = ((currentPage - 1) * perPage + index + 1) + "."; // `${index + 1}.`; // Update the serial number
                }
            });
        }

        document.getElementById('categorySearch').addEventListener('keyup', function() {
            const searchType = document.getElementById('searchtype').value;
            if (searchType === 'category') {
                filterCategories();
            } else if (searchType === 'items') {
                filterItems();
                isFilter = true;
            }
        });
        document.getElementById('searchtype').addEventListener('change', function () {
            const searchTypeselection = this.value;
            document.getElementById("categorySearch").value ="";
            const categoryItems = document.querySelectorAll(".category-item");
            if (searchTypeselection === 'category') {
                 document.querySelector('.single-check').checked = false;
                categoryItems.forEach(item => item.style.display = "block");

            } else if (searchTypeselection === 'items') {
                categoryItems.forEach(item => item.style.display = "none");
            }
        });

        setTimeout(function() {
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            } else if (errorMessage) {
                errorMessage.style.display = 'none';
            }
        }, 3000);
    // });

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

    function filterItems() {
        let searchText = document.getElementById('categorySearch').value.toLowerCase().trim();
        let table = document.getElementById('packingMaterialTable');
        let rows = table.getElementsByTagName('tr');

        if (searchText.length > 0) {
            const queryParams = new URLSearchParams({
                pmText: searchText,
            });
            console.log(queryParams.toString());
            // Construct the URL dynamically based on selected categories
            const url = `/packingmaterial?${queryParams.toString()}`;
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
                            filteredData = data.packingMaterials;
                            console.log('Fetched Data:', data.packingMaterials);
                            visibleData = data.packingMaterials;
                            currentPage = 1; // reset to page 1 on new filter
                            renderTablePage(currentPage, filteredData);
                            renderPagination(filteredData.length);
                    //         // Clear existing table content
                    //         packingMaterialTable.innerHTML = '';
                    //         console.log('Fetched Data:', data.packingMaterials);
                    //         // Populate the table with new data
                    //         data.packingMaterials.forEach((item, index) => {
                    //             packingMaterialTable.innerHTML += `
                    //     <tr data-id="${item.id}">
                    //         <td><input type="checkbox" class="form-check-input row-checkbox" value="${item.id}"></td>
                    //         <td>${index + 1}.</td>
                    //         <td class="left-align"><a href="/editpackingmaterial/${item.id}" style="color: black; font-size:16px; text-decoration: none;">${item.name}</a></td>
                    //         <td>${item.pmcode}</td>
                    //          <td>
                    //             ${item.category_name1 ?? ''}
                    //             ${item.category_name2 ? ', ' + item.category_name2 : ''}
                    //             ${item.category_name3 ? ', ' + item.category_name3 : ''}
                    //             ${item.category_name4 ? ', ' + item.category_name4 : ''}
                    //             ${item.category_name5 ? ', ' + item.category_name5 : ''}
                    //             ${item.category_name6 ? ', ' + item.category_name6 : ''}
                    //             ${item.category_name7 ? ', ' + item.category_name7 : ''}
                    //             ${item.category_name8 ? ', ' + item.category_name8 : ''}
                    //             ${item.category_name9 ? ', ' + item.category_name9 : ''}
                    //             ${item.category_name10 ? ', ' + item.category_name10 : ''}
                    //         </td> <!-- Categories -->
                    //         <td>
                    //             <span class="price-text">${item.price}</span>
                    //             <input type="text" class="form-control price-input d-none" style="width: 80px;" value="${item.price}">
                    //         </td>
                    //         <td>${item.uom}</td>
                    //     </tr>
                    // `;
                    //         });

                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while fetching packingMaterials.');
                });
        } else {
            location.reload();
        }
    }
    //   function selection_isActive()
    // {
        const checkboxes = document.querySelectorAll('.single-check');
        checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const checkedBoxes = Array.from(checkboxes).filter(cb => cb.checked);
            if (isEditing) {
                 isEditing = false;
                // exitEditingMode();
                showEditDeleteButtons();
            }
            if (this.checked) {
            // Uncheck all others
            checkboxes.forEach(cb => {
                if (cb !== this) cb.checked = false;
            });

            } else if (checkedBoxes.length === 0) {
            // If user tries to uncheck the only selected one, pick another
            for (const cb of checkboxes) {
                if (cb !== this) {
                cb.checked = true;
                break;
                }
            }
            }
        const checkedBox = document.querySelector('.single-check:checked'); // Finds the checkbox that is checked
        let table = document.getElementById('packingMaterialTable');
        let rows = table.getElementsByTagName('tr');

            // If no checkbox is checked, exit early
            if (!checkedBox) {
                console.log('No checkbox selected');
                location.reload();
                return;
            }
            // Get the status from the checkbox's id or value
            const selectedStatus = checkedBox.value.toLowerCase().trim(); // e.g., 'active', 'inactive', 'all'
            console.log("Selected Status:", selectedStatus);

        if (selectedStatus) {
                // Proceed with constructing the URL and fetching data
                const queryParams = new URLSearchParams({
                    statusValue: selectedStatus, // Always include the selected status
                });
                    console.log(queryParams.toString());
                   // Construct the URL dynamically based on selected categories
                  const url = `/packingmaterial?${queryParams.toString()}`;
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
                            filteredData = data.packingMaterials;
                            console.log('Fetched Data:', data.packingMaterials);
                            visibleData = data.packingMaterials;
                            currentPage = 1; // reset to page 1 on new filter
                            renderTablePage(currentPage, filteredData);
                            renderPagination(filteredData.length);

                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while fetching packingMaterial(s).');
                        });
                    }else{
                        location.reload();
                    }
            });
        });
    // }
    default_searchType();
    // selection_isActive();
});

function default_searchType()
{
   const searchType = document.getElementById('searchtype').value;
            const categoryItems = document.querySelectorAll(".category-item");
            if (searchType === 'category') {
                categoryItems.forEach(item => item.style.display = "block");
            } else if (searchType === 'items') {
                categoryItems.forEach(item => item.style.display = "none");
            }
}

        /*
        function filterPackingMaterials() {
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

</script>
