@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Categories</h1>
        <div>
            <a href="{{ 'addcategory' }}" class='text-decoration-none ps-add-btn text-white py-1 px-4'>
                <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
            </a>
            <a href="{{ url('/categoryitem-excel') }}" download class="btn"  data-bs-toggle="tooltip" title="Download category excel File">
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
            <!-- Left side columns -->
            <div class="col-lg-2 px-2 mt-5">
                <!-- Categories Section -->
                <div class="card" style="background-color: #EEEEEE;">
                    <div class="card-body">
                        <h5 class="card-title">Categories</h5>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <div class="me-2 align-items-center d-flex mb-2">
                                    <div class="input-group" style="width: 400px;">
                                        <select class="form-select me-2" id="searchtype" style="width: 40px;">
                                            <option value="items">Name</option>
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
                                        {{-- onkeyup="filterCategories()" --}}
                                        />
                                </div>
                                @foreach($categories as $category)
                                <div class="form-check category-item">
                                    <input
                                        name="categories[]"
                                        class="form-check-input category-checkbox"
                                        type="checkbox"
                                        data-id="category_{{ $category->id }}"
                                        value="{{ $category->id }}"
                                        {{-- data-category-name="{{ $category->itemname }}" --}}>
                                    <label class="form-check-label" for="categories{{ $category->id }}">
                                        {{ $category->categoryname }}
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
                        <!-- <button class="btn btn-sm edit-table-btn me-2" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                            <i class="fas fa-edit" style="color: black;"></i>
                        </button>-->
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
                                <th scope="col" style="color:white;">Category Items</th>
                                <th scope="col" style="color:white;">Description</th>
                                <th scope="col" style="color:white;">Created User</th>

                            </tr>
                        </thead>
                        <tbody id="catagoriesTable">
                            @foreach ($categoriesitems as $index => $material)
                            <tr data-id="{{ $material->id }}">
                                <td>
                                    <input type="checkbox" class="form-check-input row-checkbox">
                                </td>
                                <td>{{ ($categoriesitems->currentPage() - 1) * $categoriesitems->perPage() + $loop->iteration }}.</td> <!-- Auto-increment S.NO -->
                                <td class="left-align"><a href="{{ route('categoryitem.edit', $material->id) }}" style="color: black;font-size:16px;text-decoration: none;">{{ $material->itemname }}</a></td>
                                <td>{{ $material->description }}</td>
                                <td>{{ $material->created_user }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <!-- Content like "Showing 1 to 10 of 50 entries" -->
                            Showing {{ $categoriesitems->firstItem() }} to {{ $categoriesitems->lastItem() }} of {{ $categoriesitems->total() }} entries
                        </div>
                        <div class="pagination-container">
                            <!-- Pagination Links -->
                            {{ $categoriesitems->links('pagination::bootstrap-5') }}
                            {{-- {{ $rawMaterials->appends(['category_ids' => implode(',', request()->input('category_ids', []))])->links('pagination::bootstrap-5') }} --}}

                        </div>
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
                        <h5 class="modal-title" id="importModalLabel">Import for categoryitem</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('categoryitem.import') }}" method="POST" enctype="multipart/form-data">
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
</main><!-- End #main -->
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tableBody = document.getElementById("catagoriesTable");
        const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
        const deleteTableBtn = document.querySelector(".delete-table-btn");
        const selectAllCheckbox = document.getElementById('select-all');
        const rows = document.querySelectorAll('#catagoriesTable tr');

        const getRowCheckboxes = () => document.querySelectorAll('.row-checkbox');
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
            XLSX.writeFile(wb, 'categories_list.xlsx');
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

            doc.save('categories_list.pdf');
        });

        // Listen for changes to any category checkbox
        categoryCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                const selectedCategories = Array.from(
                    document.querySelectorAll('.category-checkbox:checked')
                ).map(cb => cb.value);

                if (selectedCategories.length > 0) {
                    const queryParams = new URLSearchParams({
                        category_ids: selectedCategories.join(','),
                    });

                    // Construct the URL dynamically based on selected categories
                    const url = `/showcategoryitem?${queryParams.toString()}`;

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
                            tableBody.innerHTML = '';
                            console.log('Fetched Data:', data);
                            // Populate the table with new data
                            data.categoriesitems.forEach((item, index) => {

                                tableBody.innerHTML += `
                        <tr data-id="${item.id}">
                            <td><input type="checkbox" class="form-check-input row-checkbox" value="${item.id}"></td>
                            <td>${index + 1}.</td>
                            <td class="left-align"><a href="/editcategoryitem/${item.id}" style="color: black; font-size:16px; text-decoration: none;">${item.itemname}</a></td>
                            <td>${item.description}</td>
                            <td>${item.created_user}</td>
                        </tr>
                    `;
                            });

                            // // Re-attach event listeners for dynamically added checkboxes
                            // document.querySelectorAll('.category-checkbox').forEach(checkbox => {
                            //     checkbox.addEventListener('change', updateSelectedCategories);
                            // });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while fetching category items.');
                        });
                } else {
                    location.reload();
                }
            });

        });

        /*editdelete icons */
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
            // document.querySelector(".edit-table-btn").addEventListener("click", enableEditing);
        };

        // Event listener for Select All checkbox
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;

            // Toggle all row checkboxes
            getRowCheckboxes().forEach((checkbox) => {
                checkbox.checked = isChecked;
                updateSelectAllState();
            });
            // Automatically enable edit mode if at least one row is selected
            // if (isChecked && isEditing) {
            //     enableEditing();
            // } else {
            //     exitEditingMode();
            // }
        });


        // Event listener for individual row checkboxes
        const updateSelectAllState = () => {
            const allCheckboxes = getRowCheckboxes();
            const allChecked = Array.from(allCheckboxes).every((checkbox) => checkbox.checked);

            // Update Select All checkbox state
            selectAllCheckbox.checked = allChecked;

            // Automatically enable or disable edit mode based on selections
            const anyChecked = Array.from(allCheckboxes).some((checkbox) => checkbox.checked);

            // if (anyChecked && isEditing) {
            //     // editTableBtn.addEventListener("click", enableEditing);
            //     enableEditing();
            // } else {
            //     exitEditingMode();
            //     // cancelEditing();
            // }
        };

        getRowCheckboxes().forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                updateSelectAllState();
            });
        });
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

            fetch("{{ route('categoryitem.delete') }}", {
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
                    console.log(data);
                    if (data.success) {
                        if (data.confirm) {
                        // If confirmation is required, show a confirmation dialog
                        if (confirm("Do you want to delete this item of category-item . Do you want to proceed?")) {
                            fetch('/confirmcategory', {
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
                                    alert("Selected row(s) deleted successfully!");
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
                        alert("No category item can be deleted. They might be in use.");
                    }
                })
                .catch(error => {
                    console.error("Error deleting rows:", error);
                    alert("An error occurred. Please try again.");
                });
        };

        function updateSerialNumbers() {
            // Get all visible rows
            const visibleRows = Array.from(document.querySelectorAll("#catagoriesTable tr"))
                .filter(row => row.style.display !== 'none');

            // Update serial numbers for visible rows only
            visibleRows.forEach((row, index) => {
                const snoCell = row.querySelector("td:nth-child(2)"); // Adjust the column index for S.NO
                if (snoCell) {
                    snoCell.textContent = `${index + 1}.`; // Update the serial number
                }
            });
        }

        deleteTableBtn.addEventListener("click", deleteRows);

        // // Optionally, a function to update selected category values in the URL
        // const updateSelectedCategories = () => {
        //     const selectedCategories = Array.from(
        //         document.querySelectorAll('.category-checkbox:checked')
        //     ).map(cb => cb.value);
        //     // console.log("Selected categories: ", selectedCategories);
        // };

        document.getElementById('categorySearch').addEventListener('keyup', function () {
            const searchType = document.getElementById('searchtype').value;

            if (searchType === 'category') {
                filterCategories();
            } else if (searchType === 'items') {
                filterItems();
            }
        });
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
    function filterItems() {
        let searchText = document.getElementById('categorySearch').value.toLowerCase().trim();
        let table = document.getElementById('catagoriesTable');
        let rows = table.getElementsByTagName('tr');

            if (searchText.length > 0) {
                    const queryParams = new URLSearchParams({
                        categoryItem: searchText,
                    });
                    console.log(queryParams.toString());
                    // Construct the URL dynamically based on selected categories
                   // Construct the URL dynamically based on selected categories
                   const url = `/showcategoryitem?${queryParams.toString()}`;

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
                            catagoriesTable.innerHTML = '';
                            console.log('Fetched Data:', data);
                            // Populate the table with new data
                            data.categoriesitems.forEach((item, index) => {

                                catagoriesTable.innerHTML += `
                        <tr data-id="${item.id}">
                            <td><input type="checkbox" class="form-check-input row-checkbox" value="${item.id}"></td>
                            <td>${index + 1}.</td>
                            <td class="left-align"><a href="/editcategoryitem/${item.id}" style="color: black; font-size:16px; text-decoration: none;">${item.itemname}</a></td>
                            <td>${item.description}</td>
                            <td>${item.created_user}</td>
                        </tr>
                    `;
                            });

                            // // Re-attach event listeners for dynamically added checkboxes
                            // document.querySelectorAll('.category-checkbox').forEach(checkbox => {
                            //     checkbox.addEventListener('change', updateSelectedCategories);
                            // });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while fetching category items.');
                        });
                    } else {
                    location.reload();
                    }
            }
</script>

<!-- Vendor JS Files -->
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<!-- Template Main JS File -->
<!--<script src="{{ asset('js/main.js') }}"></script> -->
