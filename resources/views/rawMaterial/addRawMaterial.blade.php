@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Add Raw Materials</h1>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            @if (session('success'))
            <div id="" class="alert alert-success">{{ session('success') }}</div>
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
                                    <input type="text" class="form-control" id="inputName" name="name" value="{{ old('name') }}">
                                </div>
                                <!-- <div class="col-12">
                                    <label for="inputNanme4" class="form-label">RM Code</label>
                                    <input type="text" class="form-control" id="inputNanme4">
                                </div> -->
                                <div class="col-12">
                                    <label for="hsncode" class="form-label">HSN Code</label>
                                    <input type="text" class="form-control" id="hsncode" name="hsncode" value="{{ old('hsncode') }}"
                                    maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8)">
                                </div>
                                <div class="col-md-12">
                                    <label for="inputState" class="form-label">Choose Unit</label>
                                    <select id="inputState" class="form-select select2" name="uom">
                                        <option value="UoM" {{ old('uom') == 'UoM' ? 'selected' : '' }}>UoM</option>
                                        <option value="Ltr" {{ old('uom') == 'Ltr' ? 'selected' : '' }}>Ltr</option>
                                        <option value="Kgm" {{ old('uom') == 'Kgm' ? 'selected' : '' }}>Kgm</option>
                                        <option value="Gm" {{ old('uom') == 'Gm' ? 'selected' : '' }}>Gm</option>
                                        <option value="Nos" {{ old('uom') == 'Nos' ? 'selected' : '' }}>Nos</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="itemweight" class="form-label">Net Weight</label>
                                    <input type="text" class="form-control" id="itemweight" name="itemweight" value="{{ old('itemweight') }}"">
                                </div>
                                <div class=" col-md-12">
                                    <label for="categorySelect" class="form-label">Raw Material Category</label>
                                    <select id="categorySelect" class="form-select select2" name="category_ids[]" multiple>
                                        @foreach($rawMaterialCategories as $categories)
                                        <option value="{{ $categories->id }}"
                                            {{ in_array($categories->id, old('category_ids', [])) ? 'selected' : '' }}>
                                            {{ $categories->itemname }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="itemType" class="form-label">Item Type</label>
                                    <select id="itemType" class="form-select" name="itemType_id">
                                        @foreach($itemtype as $types)
                                        <option value="{{ $types->id }}"
                                            {{ old('itemType_id') == $types->id ? 'selected' : '' }}>
                                            {{ $types->itemtypename }}
                                        </option>
                                        @endforeach
                                    </select>
                                    {{-- <input type="text" class="form-control" placeholder="eg.Daily, Own, Trading" id="itemType" name="itemType" value="{{ old('itemType') }}"> --}}
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="inputPrice" class="form-label">Price</label>
                                    <input type="text" class="form-control" id="inputPrice" name="price" value="{{ old('price') }}"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                </div>
                                <div class="col-12">
                                    <label for="inputTax" class="form-label">Tax(%)</label>
                                    <input type="text" class="form-control mb-2" id="inputTax" name="tax" value="{{ old('tax') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                </div>
                                <div class="row">
                                    <label for="inputNanme4" class="form-label">Pricing update frequency</label>
                                    <div class="col-md-3">
                                        <select class="form-select mb-2" id="update_frequency" name="update_frequency">
                                            <option value="Days" {{ old('update_frequency') == 'Days' ? 'selected' : '' }}>Days</option>
                                            <option value="Weeks" {{ old('update_frequency') == 'Weeks' ? 'selected' : '' }}>Weeks</option>
                                            <option value="Monthly" {{ old('update_frequency') == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                                            <option value="Yearly" {{ old('update_frequency') == 'Yearly' ? 'selected' : '' }}>Yearly</option>
                                        </select>
                                    </div>
                                    {{-- <div class="col-md-1">
                                </div> --}}
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="price_update_frequency" name="price_update_frequency" value="{{ old('price_update_frequency') }}"
                                        oninput="this.value = this.value.replace(/\D/g, '');">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="price_threshold" class="form-label">Price threshold</label>
                                    <input type="text" class="form-control" id="price_threshold" name="price_threshold" value="{{ old('price_threshold') }}"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
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
        $('#itemType').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select itemtype',
        });
    });



    document.addEventListener("DOMContentLoaded", function() {
        const btnsave = document.getElementById('btnsubmit');

        btnsave.addEventListener('click', function(event) {
            let isValid = true;
            document.querySelectorAll(".error-text").forEach(el => el.innerHTML = "");
            // Get form fields
            let name = document.getElementById("inputName");
            let hsncode = document.getElementById("hsncode");
            let uom = document.getElementById("inputState");
            let itemweight = document.getElementById("itemweight");
            let categorySelect = document.getElementById("categorySelect");
            let itemtype = document.getElementById("itemType");
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
            else if (!/^\d{1,8}$/.test(hsncode.value)) {
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
                    if (input.value.trim() === "") {
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
        //  Remove error message when user starts typing or selecting
        //  input.addEventListener("input", function () { clearError(input); });
        //  input.addEventListener("change", function () { clearError(input); });
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
