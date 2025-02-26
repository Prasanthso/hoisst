@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Add Raw Materials</h1>
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
                            <form id="rawMaterialForm" method="POST" action="{{ route('rawmaterials.store') }}" class="row g-3 mt-2">
                                @csrf
                                <div class="col-12">
                                    <label for="inputName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="inputName" name="name">
                                </div>
                                <!-- <div class="col-12">
                                    <label for="inputNanme4" class="form-label">RM Code</label>
                                    <input type="text" class="form-control" id="inputNanme4">
                                </div> -->
                                <div class="col-12">
                                    <label for="hsncode" class="form-label">HSN Code</label>
                                    <input type="text" class="form-control" id="hsncode" name="hsncode">
                                </div>
                                <div class="col-md-12">
                                    <label for="inputState" class="form-label">Choose Unit</label>
                                    <select id="inputState" class="form-select select2" name="uom">
                                        <option selected>UoM</option>
                                        <option>Ltr</option>
                                        <option>Kgm</option>
                                        <option>Gm</option>
                                        <option>Nos</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="itemweight" class="form-label">Net Weight</label>
                                    <input type="text" class="form-control" id="itemweight" name="itemweight">
                                </div>
                                <div class="col-md-12">
                                    <label for="categorySelect" class="form-label">Raw Material Category</label>
                                    <select id="categorySelect" class="form-select select2" name="category_ids[]" multiple>
                                        @foreach($rawMaterialCategories as $categories)
                                        <option value="{{ $categories->id }}">{{ $categories->itemname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="itemtype" class="form-label">Item Type</label>
                                    <input type="text" class="form-control" id="itemtype" name="itemtype">
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="text" class="form-control" id="price" name="price">
                                </div>
                                <div class="col-12">
                                    <label for="tax" class="form-label">Tax</label>
                                    <input type="text" class="form-control mb-2" id="tax" name="tax">
                                </div>
                                <div class="row">
                                    <label for="inputNanme4" class="form-label">Pricing update frequency</label>
                                    <div class="col-md-3">
                                        <select class="form-select mb-2" id="update_frequency" name="update_frequency">
                                            <option selected>Days</option>
                                            <option>Weeks</option>
                                            <option>Monthly</option>
                                            <option>Yearly</option>
                                        </select>
                                    </div>
                                {{-- <div class="col-md-1">
                                </div> --}}
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="price_update_frequency" name="price_update_frequency">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="price_threshold" class="form-label">Price threshold</label>
                                    <input type="text" class="form-control" id="price_threshold" name="price_threshold">
                                </div>


                                <div>
                                    <button type="submit" class="btn btn-primary" id="btnsubmit">
                                        Save
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
        $('#categorySelect').select2({
            theme: 'bootstrap-5',
            placeholder: 'Choose Categories',
            allowClear: true
        });

        $('#inputState').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select UoM',
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
       const btnsave = document.getElementById('btnsubmit');

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
