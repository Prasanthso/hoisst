@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <!-- Initially displaying "View Raw Material" -->
        <h1 id="pageTitle">View Raw Material</h1>
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
                <div class="alert" style="color: white; background-color: rgba(255, 0, 0, 0.819); padding: 10px; border-radius: 5px;">{{ session('error') }}</div>
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
                            <form method="POST" action="{{ route('rawMaterial.edit', $rawMaterial->id) }}" class="row g-3 mt-2" id="rawMaterialForm">
                                @csrf
                                @method('PUT')
                                <div class="col-12">
                                    <label for="inputName" class="form-label"> Item Name</label>
                                    <input type="text" class="form-control" id="inputName" name="name" value="{{ $rawMaterial->name}}" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="hsncode" class="form-label">HSN Code</label>
                                    <input type="text" class="form-control" id="hsncode" name="hsncode" value="{{ $rawMaterial->hsncode}}" disabled>
                                </div>
                                <div class="col-md-12">
                                    <label for="inputState" class="form-label">Choose Unit </label>
                                    <select id="inputState" class="form-select select2" name="uom" disabled>
                                        <option selected>{{ $rawMaterial->uom}}</option>
                                        <option>Ltr</option>
                                        <option>Kgm</option>
                                        <option>Gm</option>
                                        <option>Nos</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="itemweight" class="form-label">Net Weight</label>
                                    <input type="text" class="form-control" id="itemweight" name="itemweight" value="{{ $rawMaterial->itemweight}}" disabled>
                                </div>
                                <div class="col-md-12">
                                    <label for="categorySelect" class="form-label">Raw Material Category</label>

                                    <!-- The dropdown list for selecting categories (hidden initially) -->
                                    <select class="form-select" id="categorySelect" name="category_ids[]" multiple disabled>
                                        @foreach($rawMaterialCategories as $categories)
                                        <option value="{{ $categories->id }}"
                                            @foreach(range(1, 10) as $i)
                                            @php
                                            $categoryId='category_id' . $i;
                                            @endphp
                                            @if($rawMaterial->$categoryId == $categories->id) selected @endif
                                            @endforeach
                                            >{{ $categories->itemname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="itemtype" class="form-label">Item Type</label>
                                    <input type="text" class="form-control" id="itemtype" name="itemtype" value="{{ $rawMaterial->itemtype}}" disabled>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="text" class="form-control" id="price" name="price" value="{{ $rawMaterial->price}}" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="tax" class="form-label">Tax</label>
                                    <input type="text" class="form-control mb-2" id="tax" name="tax" value="{{ $rawMaterial->tax}}" disabled>
                                </div>
                                <div class="row">
                                    <label for="update_frequency" class="form-label">Pricing update frequency</label>
                                    <div class="col-md-3">
                                        <select class="form-select mb-2" id="update_frequency" name="update_frequency" disabled>
                                            <option selected>{{ $rawMaterial->update_frequency}}</option>
                                            <option>Days</option>
                                            <option>Weeks</option>
                                            <option>Monthly</option>
                                            <option>Yearly</option>
                                        </select>
                                    </div>
                                     <div class="col-md-9">
                                        <input type="text" class="form-control" id="price_update_frequency" name="price_update_frequency" value="{{ $rawMaterial->price_update_frequency}}" disabled>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="price_threshold" class="form-label">Price threshold</label>
                                    <input type="text" class="form-control" id="price_threshold" name="price_threshold" value="{{ $rawMaterial->price_threshold}}" disabled>
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
<script src="{{ asset('/assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('/assets/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/quill/quill.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ asset('/assets/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/php-email-form/validate.js') }}"></script>

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
        // Toggle edit mode
        $('#editButton').on('click', function() {
            // Change the page title text
            $('#pageTitle').text('Edit Raw Material');

            // Enable form fields
            $('#rawMaterialForm input, #rawMaterialForm select').prop('disabled', false);

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
    let errorMessage = "";

    // Get form fields
    let name = document.getElementById("inputName").value.trim();
    let hsncode = document.getElementById("hsncode").value.trim();
    let uom = document.getElementById("inputState").value;
    let itemweight = document.getElementById("itemweight").value.trim();
    let categorySelect = document.getElementById("categorySelect");
    let itemtype = document.getElementById("itemtype").value.trim();
    let price = document.getElementById("price").value.trim();
    let tax = document.getElementById("tax").value.trim();
    let priceUpdateFreq = document.getElementById("price_update_frequency").value.trim();
    let priceThreshold = document.getElementById("price_threshold").value.trim();
    let errorDiv = document.getElementById("error-message");
    errorDiv.innerHTML = ""; // Clear previous errors

    if (name === "") errorMessage += "Name is required.<br>";
        if (hsncode === "") errorMessage += "HSN Code is required.<br>";
        if (uom === "UoM") errorMessage += "Please select a valid Unit of Measure.<br>";
        if (itemweight === "") errorMessage += "Net Weight is required.<br>";
        if (categorySelect.selectedOptions.length === 0) errorMessage += "Please select at least one Raw Material Category.<br>";
        if (itemtype === "") errorMessage += "Item Type is required.<br>";
        if (price === "" || isNaN(price)) errorMessage += "Valid Price is required.<br>";
        if (tax === "" || isNaN(tax)) errorMessage += "Valid Tax value is required.<br>";
        if (priceUpdateFreq === "" || isNaN(priceUpdateFreq)) errorMessage += "Valid Pricing Update Frequency is required.<br>";
        if (priceThreshold === "" || isNaN(priceThreshold)) errorMessage += "Valid Price Threshold is required.<br>";

        // Display errors
        if (errorMessage) {
            errorDiv.innerHTML = `<div class="alert alert-danger">${errorMessage}</div>`;
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
            event.preventDefault();
        }
    });

 });

</script>

<!--Template Main JS File-->
<script src="{{ asset('js/main.js') }}">
</script>
