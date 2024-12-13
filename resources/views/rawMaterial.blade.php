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
            <!-- Left side columns -->
            <div class="col-lg-2">
                <!-- Categories Section -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Categories</h5>
                        <div class="row mb-3">
                            <div class="col-sm-10">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="gridCheck1">
                                    <label class="form-check-label" for="gridCheck1">Oils</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="gridCheck2" checked>
                                    <label class="form-check-label" for="gridCheck2">Vegetables</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="gridCheck3" checked>
                                    <label class="form-check-label" for="gridCheck3">Bread</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Categories Section -->
            </div><!-- End Left side columns -->

            <div class="col-lg-1"></div>

            <!-- Right side columns -->
            <div class="col-lg-7">
                <div class="row">
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end mb-2">
                        <button class="btn btn-sm edit-table-btn me-2" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                            <i class="fas fa-edit" style="color: black;"></i>
                        </button>
                        <button class="btn btn-sm delete-table-btn" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                            <i class="fas fa-trash" style="color: red;"></i>
                        </button>
                    </div>

                    <!-- Bordered Table -->
                    <table class="table table-bordered">
                        <thead class="custom-header table-primary">
                            <tr>
                                <th scope="col">
                                    <input type="checkbox" id="select-all" class="form-check-input">
                                </th>
                                <th scope="col">S.NO</th>
                                <th scope="col">Raw Materials</th>
                                <th scope="col">RM Code</th>
                                <th scope="col">Raw Material Category</th>
                                <th scope="col">Price(Rs)</th>
                                <th scope="col">UoM</th>
                            </tr>
                        </thead>
                        <tbody id="rawMaterialTable">
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input row-checkbox">
                                </td>
                                <td>1</td>
                                <td>SunFlower oil</td>
                                <td>RM0001</td>
                                <td>Oils</td>
                                <td>
                                    <span class="price-text">300</span>
                                    <input type="text" class="form-control price-input d-none" style="width: 80px;" value="300">
                                </td>
                                <td>Ltr</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input row-checkbox">
                                </td>
                                <td>2</td>
                                <td>Maida Flour</td>
                                <td>RM0002</td>
                                <td>Flour</td>
                                <td>
                                    <span class="price-text">100</span>
                                    <input type="text" class="form-control price-input d-none" style="width: 80px;" value="300">
                                </td>
                                <td>Kgs</td>
                            </tr>
                        </tbody>
                    </table>
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
                <table class="table table-bordered">
                    <thead class="custom-header table-primary" style="border-bottom: none;">
                        <tr>
                            <th>Effective From</th>
                            <th>Price</th>
                            <th>Updated By</th>
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
    document.addEventListener("DOMContentLoaded", function () {
        const table = document.getElementById("rawMaterialTable");
        const editTableBtn = document.querySelector(".edit-table-btn");
        const deleteTableBtn = document.querySelector(".delete-table-btn");
        const selectAllCheckbox = document.getElementById('select-all');
        const rows = document.querySelectorAll('#rawMaterialTable tr');

        // Function to get all row checkboxes dynamically
        const getRowCheckboxes = () => document.querySelectorAll('.row-checkbox');

        // Edit Table Button - Toggles Edit Mode for Price Column
        editTableBtn.addEventListener("click", function () {
            table.querySelectorAll("tr").forEach(row => {
                const priceText = row.querySelector(".price-text");
                const priceInput = row.querySelector(".price-input");

                if (priceText && priceInput) {
                    if (priceText.classList.contains("d-none")) {
                        // Save the edited value
                        priceText.textContent = priceInput.value;
                        priceText.classList.remove("d-none");
                        priceInput.classList.add("d-none");
                    } else {
                        // Enter edit mode
                        priceText.classList.add("d-none");
                        priceInput.classList.remove("d-none");
                    }
                }
            });
        });

        // Delete Table Button - Deletes All Rows
        deleteTableBtn.addEventListener("click", function () {
            table.innerHTML = "";
            selectAllCheckbox.checked = false; // Uncheck the Select All checkbox
        });

        // Event listener for Select All checkbox
        selectAllCheckbox.addEventListener('change', function () {
            const isChecked = this.checked;

            // Toggle all row checkboxes
            getRowCheckboxes().forEach((checkbox) => {
                checkbox.checked = isChecked;
            });
        });

        // Event listener for individual row checkboxes
        const updateRowCheckboxListeners = () => {
            getRowCheckboxes().forEach((checkbox) => {
                checkbox.addEventListener('change', function () {
                    // If any checkbox is unchecked, uncheck Select All
                    if (!this.checked) {
                        selectAllCheckbox.checked = false;
                    } else {
                        // If all checkboxes are checked, check Select All
                        const allChecked = Array.from(getRowCheckboxes()).every((cb) => cb.checked);
                        selectAllCheckbox.checked = allChecked;
                    }
                });
            });
        };

        // Initialize listeners for row checkboxes
        updateRowCheckboxListeners();

// Attach a click event to each Price column
    table.querySelectorAll("tr").forEach(row => {
        const priceColumn = row.querySelector(".price-text"); // Price column
        if (priceColumn) {
            priceColumn.addEventListener("click", function () {
                // Fetch data for the modal
                const effectiveFrom = "2024-01-01"; // Example static value
                const price = priceColumn.textContent.trim();
                const updatedBy = "Admin"; // Example static value

                // Inject data into the modal table
                const modalTableBody = document.getElementById("priceDetailsTable");
                modalTableBody.innerHTML = `
                    <tr>
                        <td>${effectiveFrom}</td>
                        <td>${price}</td>
                        <td>${updatedBy}</td>
                    </tr>
                `;

                // Show the modal
                const priceModal = new bootstrap.Modal(document.getElementById("priceModal"));
                priceModal.show();
            });
        }
    });
    });
</script>

