@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <!-- Initially displaying "View Raw Material" -->
        <h1 id="pageTitle">View Product</h1>
        <div class="d-flex justify-content-end mb-2 action-buttons">
            <button class="btn btn-sm edit-table-btn me-2" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;" id="editButton">
                <i class="fas fa-edit" style="color: black;"></i>
            </button>
            <!--<button class="btn btn-sm delete-table-btn" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;" id="deleteButton" style="display: none;">
                <i class="fas fa-trash" style="color: red;"></i>
            </button>-->
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div id="error-message" class="text-danger mt-2"></div>
            <div class="col-lg-12">
                <div class="col-lg-6">

                    <div class="card">
                        <div class="card-body">

                            <!-- Vertical Form -->
                            <form method="POST" action="{{ route('products.edit', $product->id) }}" class="row g-3 mt-2" id="productForm">
                                @csrf
                                @method('PUT')
                                <div class="col-12">
                                    <label for="inputName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="inputName" name="name" value="{{ $product->name }}" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputHSNcode" class="form-label">HSN Code</label>
                                    <input type="text" class="form-control" id="inputHSNcode" name="hsnCode" value="{{ $product->hsnCode }}"
                                    maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8)" disabled>
                                </div>
                                <div class="col-md-12">
                                    <label for="inputState" class="form-label">Choose Unit</label>
                                    <select id="inputState" class="form-select select2" name="uom" disabled>
                                        <option selected>{{ $product->uom }}</option>
                                        <option>Ltr</option>
                                        <option>Kgm</option>
                                        <option>Gm</option>
                                        <option>Nos</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="inputItemWeight" class="form-label">Net Weight</label>
                                    <input type="text" class="form-control" id="inputItemWeight" name="itemWeight" value="{{ $product->itemWeight }}" disabled>
                                </div>
                                <div class="col-md-12">
                                    <label for="categorySelect" class="form-label">Product Category</label>

                                    <!-- The dropdown list for selecting categories (hidden initially) -->
                                    <select class="form-select " id="categorySelect" name="category_ids[]" multiple disabled>
                                        @foreach($productCategories as $categories)
                                        <option value="{{ $categories->id }}"
                                            @foreach(range(1, 5) as $i)
                                            @php
                                            $categoryId='category_id' . $i;
                                            @endphp
                                            @if($product->$categoryId == $categories->id) selected @endif
                                            @endforeach
                                            >{{ $categories->itemname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="itemType" class="form-label">Item Type</label>
                                    <select id="itemType" class="form-select" name="itemType_id" disabled>
                                        @foreach($itemtype as $types)
                                        <option value="{{ $types->id }}"
                                            {{ (old('itemType_id', $product->itemType_id) == $types->id) ? 'selected' : '' }}>
                                            {{ $types->itemtypename }}
                                        </option>
                                        @endforeach
                                    </select>
                                    {{-- <input type="text" class="form-control" placeholder="eg.Daily, Own, Trading" id="itemType" name="itemType" value="{{ old('itemType') }}"> --}}
                                </div>
                                <div class="col-12">
                                    <label for="inputPurCost" class="form-label">Purchase Cost</label>
                                    <input type="text" class="form-control" id="inputPurCost" name="purcCost" value="{{ $product->purcCost }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputMargin" class="form-label">Preferred Margin(%)</label>
                                    <input type="text" class="form-control" id="inputMargin" name="margin" value="{{ $product->margin }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" disabled>
                                </div>

                                <div class="col-12 mb-2">
                                    <label for="inputTax" class="form-label">Tax(%)</label>
                                    <input type="text" class="form-control" id="inputTax" name="tax" value="{{ $product->tax }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" disabled>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="inputPrice" class="form-label">Present MRP</label>
                                    <input type="text" class="form-control" id="inputPrice" name="price" value="{{ $product->price }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" disabled>
                                </div>

                                <div class="row">
                                    <label for="update_frequency" class="form-label">Pricing update frequency</label>
                                    <div class="col-md-3">
                                        <select class="form-select mb-2" id="update_frequency" name="update_frequency" disabled>
                                            <option selected>{{ $product->update_frequency }}</option>
                                            <option>Days</option>
                                            <option>Weeks</option>
                                            <option>Monthly</option>
                                            <option>Yearly</option>
                                        </select>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="price_update_frequency" name="price_update_frequency" value="{{ $product->price_update_frequency }}" disabled>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="price_threshold" class="form-label">Price threshold</label>
                                    <input type="text" class="form-control" id="price_threshold" name="price_threshold" value="{{ $product->price_threshold }}"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" disabled>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary" id="saveButton" style="display: none;">
                                        Update
                                    </button>
                                </div>
                            </form><!-- Vertical Form -->

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</main><!-- End #main -->
@endsection

<!-- Vendor JS Files -->
<script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
<script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize select2 for category select
        $('#categorySelect').select2({
            theme: 'bootstrap-5',
            placeholder: 'Choose Categories',
            allowClear: true
        });
        $('#inputState').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select UoM',
        });
        $('#itemType').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select itemtype',
        }).on('change', function () {
    const itemTypeValue = $(this).val(); // get selected value
    const purcCost = document.querySelector("#inputPurCost");
    let isValid = true;
    if (itemTypeValue !== "2") {
        clearError(purcCost); // hide error for non-Trading
    }

});

        // Toggle edit mode
        $('#editButton').on('click', function() {
            // Change the page title text
            $('#pageTitle').text('Edit Product');

            // Enable form fields
            $('#productForm input, #productForm select').prop('disabled', false);

            // Show the Save button
            $('#saveButton').show();
        });

        $('#editButton').on('click', function() {
            // Hide the category list and show the select dropdown
            $('#categoryList').hide(); // Hide the list of categories
            $('#categorySelect').show(); // Show the select dropdown

            // Enable the select dropdown for editing
            $('#categorySelect').prop('disabled', false);
        });
    });
    document.addEventListener("DOMContentLoaded", function() {
        const btnsave = document.getElementById('saveButton');

        btnsave.addEventListener('click', function(event) {
            let isValid = true;
            document.querySelectorAll(".error-text").forEach(el => el.innerHTML = "");
            // Get form fields
            let name = document.getElementById("inputName");
            let hsncode = document.getElementById("inputHSNcode");
            let uom = document.getElementById("inputState");
            let itemweight = document.getElementById("inputItemWeight");
            let categorySelect = document.getElementById("categorySelect");
            let itemtype = document.getElementById("itemType");
            let purcCost = document.getElementById("inputPurCost");
            let mrp = document.getElementById("inputMargin");
            let price = document.getElementById("inputPrice");
            let tax = document.getElementById("inputTax");
            let priceUpdateFreq = document.getElementById("price_update_frequency");
            let priceThreshold = document.getElementById("price_threshold");

            let errorDiv = document.getElementById("error-message");
            errorDiv.innerHTML = ""; // Clear previous errors

            // Validation checks
            if (name.value.trim() === "") {
                showError(name, "Name is required.");
                isValid = false;
            }
            if (hsncode.value.trim() === "") {
                showError(hsncode, "HSN Code is required.");
                isValid = false;
            }
            if (!/^\d{1,8}$/.test(hsncode.value)) {
                showError(hsncode, "HSN Code must be numeric and up to 8 digits.");
                isValid = false;
            }
            if (uom.value === "UoM") {
                showError(uom, "Please select a valid Unit of Measure.");
                isValid = false;
            }
            if (itemweight.value.trim() === "") {
                showError(itemweight, "Net Weight is required.");
                isValid = false;
            }
            if (categorySelect.selectedOptions.length === 0) {
                showError(categorySelect, "Please select at least one category.");
                isValid = false;
            }
            if (itemtype.value.trim() === "") {
                showError(itemtype, "Item Type is required.");
                isValid = false;
            }
            if (itemtype.value === "2") {
                if (purcCost.value.trim() === "" || isNaN(purcCost.value)) {
                    showError(purcCost, "Valid purcCost is required.");
                    isValid = false;
                }
            }

            if (mrp.value.trim() === "" || isNaN(mrp.value)) {
                showError(mrp, "Valid MRP is required.");
                isValid = false;
            }
            if (price.value.trim() === "" || isNaN(price.value)) {
                showError(price, "Valid Price is required.");
                isValid = false;
            }
            if (tax.value.trim() === "" || isNaN(tax.value)) {
                showError(tax, "Valid Tax value is required.");
                isValid = false;
            }
            if (priceUpdateFreq.value.trim() === "" || isNaN(priceUpdateFreq.value)) {
                showError(priceUpdateFreq, "Valid Pricing Update Frequency is required.");
                isValid = false;
            }
            if (priceThreshold.value.trim() === "" || isNaN(priceThreshold.value)) {
                showError(priceThreshold, "Valid Price Threshold is required.");
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault();
            }
        });

        document.querySelectorAll("input, select").forEach(input => {
            let hasTyped = false; // Track if the user has typed

            input.addEventListener("input", () => { hasTyped = true; clearError(input)});
            input.addEventListener("change", () => { hasTyped = true; clearError(input)});
            input.addEventListener("blur", () => {
                clearError(input);
                // const itemTypeValue =  document.querySelector("#itemtype")?.value;
                // const isPurcCost = input.id === "inputPurCost";
                // if (isPurcCost) {
                //     if (itemTypeValue === "2") {
                //         if (input.value.trim() === "") {
                //             hasTyped = false;
                //             showError(input, "This field is required!");
                //             isValid = false;
                //         }
                //     }
                //     return; // important to skip general required validation
                // }
                    if(input.value.trim() === "") {
                        hasTyped = false;
                        showError(input, "This field is required!");
                    }

                });
        });

        // Special handling for select2 dropdowns
        $('#inputState, #categorySelect').on("select2:select", function() {
            clearError(this); // Pass the select element to clearError function
        });
    });

    function showError(input, message) {
        let errorElement = document.createElement("div");
        errorElement.className = "error-text text-danger";
        errorElement.innerHTML = message;
        input.parentNode.appendChild(errorElement);
        input.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }

    function clearError(input) {
        let errorMsg = input.parentNode.querySelector(".error-text");
        if (errorMsg) {
            errorMsg.remove();
        }
    }
</script>

<!--Template Main JS File-->
<script src="{{ asset('js/main.js') }}">
</script>
