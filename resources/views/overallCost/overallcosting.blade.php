@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Categories</h1>
        <a href="{{ 'addoverallcosting' }}" class='text-decoration-none ps-add-btn text-white py-1 px-4'>
            <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
        </a>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            @if(session('success'))
            <div id="success-message" class="alert alert-success">{{ session('success') }}</div>
            @endif
            

            <!-- Left side columns -->
             <!--
            <div class="col-lg-2 px-2 mt-5">
                <div class="card" style="background-color: #EEEEEE;">
                    <div class="card-body">
                        <h5 class="card-title">Overall Costing</h5>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <div>
                                    <input
                                    type="text"
                                    id="categorySearch"
                                    class="form-control mb-3"
                                    placeholder="Search categories..."
                                    onkeyup="filterCategories()"
                                />
                                </div>
                                {{-- @foreach($categories as $category)
                                <div class="form-check category-item">
                                    <input
                                        name="categories[]"
                                        class="form-check-input category-checkbox"
                                        type="checkbox"
                                        data-id="category_{{ $category->id }}"
                                        value="{{ $category->id }}"
                                        {{-- data-category-name="{{ $category->itemname }}" --}}>
                                    {{-- <label class="form-check-label" for="categories{{ $category->id }}">
                                        {{ $category->categoryname }}
                                    </label>
                                </div>
                                @endforeach --}} --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- End Left side columns -->

            <!-- <div class="col-lg-1"></div> -->

            <!-- Right side columns -->
            <div class="col-lg-8">
                <div class="row">
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end mb-2 action-buttons">
                        <!--<button class="btn btn-sm edit-table-btn me-2" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                            <i class="fas fa-edit" style="color: black;"></i>
                        </button>-->
                         <button class="btn btn-sm delete-table-btn" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                            <i class="fas fa-trash" style="color: red;"></i>
                        </button>
                    </div>

                    <!-- Bordered Table -->
                    <table class="table table-bordered mt-2">
                        <thead class="custom-header">
                            <tr>
                                <th class="head" scope="col">
                                    <input type="checkbox" id="select-all" class="form-check-input">
                                </th>
                                <th scope="col" style="color:white;">S.NO</th>
                                <th scope="col" style="color:white;">Recipe</th>
                                <th scope="col" style="color:white;">Raw Material Cost</th>
                                <th scope="col" style="color:white;">Selling Price</th>
                                <th scope="col" style="color:white;">Discount</th>
                                <th scope="col" style="color:white;">Margin</th>
                            </tr>
                        </thead>
                        <tbody id="overallcostingTable">
                            @foreach ($costings as $index => $material)
                            <tr data-id="{{ $material->id }}">
                               <td>
                                    <input type="checkbox" class="form-check-input row-checkbox">
                                </td>
                                <td>{{ $index + 1 }}.</td> <!-- Auto-increment S.NO -->
                                <td><a href="{{ route('overallcosting.edit', $material->id) }}" style="color: black;font-size:16px;text-decoration: none;">{{ $material->product_name }}</a></td>
                                <td>{{ $material->rm_cost_unit }}</td>
                                <td>{{ $material->sell_rate }}</td>
                                <td>{{ $material->discount }}</td>
                                <td>{{ $material->margin }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <!-- Content like "Showing 1 to 10 of 50 entries" -->
                            Showing {{ $costings->firstItem() }} to {{ $costings->lastItem() }} of {{ $costings->total() }} entries
                        </div>
                        <div class="pagination-container">
                            <!-- Pagination Links -->
                            {{ $costings->links('pagination::bootstrap-5') }}
                            {{-- {{ $rawMaterials->appends(['category_ids' => implode(',', request()->input('category_ids', []))])->links('pagination::bootstrap-5') }} --}}

                        </div>
                    </div>
                    <!-- End Bordered Table -->
                </div>
            </div><!-- End Right side columns -->
        </div>
    </section>

</main><!-- End #main -->
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
    const tableBody = document.getElementById("overallcostingTable");
    // const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
        // const deleteTableBtn = document.querySelector(".delete-table-btn");
        const selectAllCheckbox = document.getElementById('select-all');
        const rows = document.querySelectorAll('#overallcostingTable tr');

        const getRowCheckboxes = () => document.querySelectorAll('.row-checkbox');
        let isEditing = false; // Track if edit mode is active

    // Listen for changes to any category checkbox
    /*
    categoryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const selectedCategories = Array.from(
                document.querySelectorAll('.category-checkbox:checked')
            ).map(cb => cb.value);

        if(selectedCategories.length > 0)
        {
            const queryParams = new URLSearchParams({
                category_ids: selectedCategories.join(','),
            });

            // Construct the URL dynamically based on selected categories
            const url = `/showcategoryitem?${queryParams.toString()}`;

            // Fetch updated data from server
            fetch(url, {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
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
                        <tr>
                            <td><input type="checkbox" class="form-check-input row-checkbox" value="${item.id}"></td>
                            <td>${index + 1}.</td>
                            <td><a href="/categoryitem/edit/${item.id}" style="color: black; font-size:16px; text-decoration: none;">${item.itemname}</a></td>
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
        }
         else
            {
                location.reload();
            }
        });

    });
    */

        /*editdelete icons */
           // Function to restore Edit/Delete buttons
           const showEditDeleteButtons = () => {
            isEditing = false;
            const actionButtonsContainer = document.querySelector(".action-buttons");
            actionButtonsContainer.innerHTML = `
            <!--<button class="btn btn-sm edit-table-btn me-2" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                <i class="fas fa-edit" style="color: black;"></i>
            </button>-->
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
            if (!confirm(`Are you sure you want to delete ${selectedIds.length} selected row(s)?`)) {
                return;
            }
            fetch("{{ route('overallcosting.delete') }}", {
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
                        selectedRows.forEach(checkbox => {
                            const row = checkbox.closest("tr");
                            row.remove();
                            updateSerialNumbers();
                        });
                        alert("Selected rows deleted successfully!");
                    } else {
                        alert("Failed to delete rows. Please try again.");
                    }
                })
                .catch(error => {
                    console.error("Error deleting rows:", error);
                    alert("An error occurred. Please try again.");
                });
        };

        function updateSerialNumbers() {
            // Get all visible rows
            const visibleRows = Array.from(document.querySelectorAll("#overallcostingTable tr"))
                .filter(row => row.style.display !== 'none');

            // Update serial numbers for visible rows only
            visibleRows.forEach((row, index) => {
                const snoCell = row.querySelector("td:nth-child(2)"); // Adjust the column index for S.NO
                if (snoCell) {
                    snoCell.textContent = `${index + 1}.`; // Update the serial number
                }
            });
        }

        document.querySelector(".delete-table-btn").addEventListener("click", deleteRows);

    // // Optionally, a function to update selected category values in the URL
    // const updateSelectedCategories = () => {
    //     const selectedCategories = Array.from(
    //         document.querySelectorAll('.category-checkbox:checked')
    //     ).map(cb => cb.value);
    //     // console.log("Selected categories: ", selectedCategories);
    // };

    setTimeout(function () {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 5000);
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

<!-- Vendor JS Files -->
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>

<!-- Template Main JS File -->
<!--<script src="{{ asset('js/main.js') }}"></script> -->
