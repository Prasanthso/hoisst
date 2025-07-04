@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Add Product</h1>
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
                            <form method="POST" action="{{ route('products.store') }}" class="row g-3 mt-2">
                                @csrf
                                <div class="col-12">
                                    <label for="inputName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="inputName" name="name" value="{{ old('name') }}">
                                </div>
                                <div class="col-12">
                                    <label for="inputHSNcode" class="form-label">HSN Code</label>
                                    <input type="text" class="form-control" id="inputHSNcode" name="hsnCode" value="{{ old('hsnCode') }}"
                                    maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8)">
                                </div>
                                <!-- <div class="col-12">
                                    <label for="inputNanme4" class="form-label">RM Code</label>
                                    <input type="text" class="form-control" id="inutNanpme4">
                                </div> -->
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
                                    <label for="inputItemWeight" class="form-label">Net Weight</label>
                                    <input type="text" class="form-control" id="inputItemWeight" name="itemWeight" value="{{ old('itemweight') }}">
                                </div>
                                <div class="col-md-12">
                                    <label for="categorySelect" class="form-label">Product Category</label>
                                    <select id="categorySelect" class="form-select" name="category_ids[]" multiple>
                                        @foreach($product as $categories)
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
                                             data-name="{{ $types->itemtypename }}"
                                            {{ old('itemType_id') == $types->id ? 'selected' : '' }}>
                                            {{ $types->itemtypename }}
                                        </option>
                                        @endforeach
                                    </select>
                                    {{-- <input type="text" class="form-control" placeholder="eg.Daily, Own, Trading" id="itemType" name="itemType" value="{{ old('itemType') }}"> --}}
                                </div>
                                <div id="trading-fields" style="display:none;">
                                    <div class="col-12">
                                        <label for="inputPurCost" class="form-label">Purchase Cost</label>
                                        <input type="text" class="form-control" id="inputPurCost" name="purcCost" value="{{ old('purcCost') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="inputMargin" class="form-label"> Preferred Margin(%)</label>
                                    <input type="text" class="form-control" id="inputMargin" name="margin" value="{{ old('margin') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                </div>
                                <div class="col-12">
                                    <label for="inputTax" class="form-label">Tax(%)</label>
                                    <input type="text" class="form-control" id="inputTax" name="tax" value="{{ old('tax') }}"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="inputPrice" class="form-label">Present MRP</label>
                                    <input type="text" class="form-control" id="inputPrice" name="price" value="{{ old('price') }}"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                </div>
                                <div id="trading-fields2" style="display:none;">
                                     <div class="row">
                                        <label for="update_frequency" class="form-label mb-2">Pricing update frequency</label>
                                        <div class="col-md-3">
                                            <select class="form-select mb-2" id="update_frequency" name="update_frequency">
                                                <option value="Days" {{ old('update_frequency') == 'Days' ? 'selected' : '' }}>Days</option>
                                                <option value="Weeks" {{ old('update_frequency') == 'Weeks' ? 'selected' : '' }}>Weeks</option>
                                                <option value="Monthly" {{ old('update_frequency') == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                                                <option value="Yearly" {{ old('update_frequency') == 'Yearly' ? 'selected' : '' }}>Yearly</option>
                                            </select>
                                        </div>
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
        let hasTyped = false;
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
            const itemTypeValue = $(this).val();
            const selectedOption = $(this).find('option:selected');
            const itemTypeName = selectedOption.data('name');
            if (itemTypeName === "Trading") {
                $('#trading-fields').show();
                $('#trading-fields2').show();
            } else {
                $('#trading-fields').hide();
                $('#trading-fields2').hide();
                $('#inputPurCost').val('');
                $('#price_update_frequency').val('');
                $('#price_threshold').val('');
            }
          }).trigger('change')
          .on('change', function () {
            const itemTypeValue = $(this).val(); // get selected value
            const purcCost = document.querySelector("#inputPurCost");
            const updateFrequency = document.querySelector("#update_frequency");
            const priceThreshold = document.querySelector("#price_threshold");
            const selectedOption = $(this).find('option:selected');
            const itemTypeName = selectedOption.data('name');
            if (itemTypeName !== "Trading") {
                hasTyped = true;
                clearError(purcCost); // hide error for non-Trading
                clearError(updateFrequency);
                clearError(priceThreshold);
            }
            else{
                 if (itemTypeValue === "Trading" && updateFrequency.value.trim() === "") {
                    showError(updateFrequency, "Pricing Update Frequency is required.");
                    hasTyped = false;
                }
                if (itemTypeValue === "Trading" && priceThreshold.value.trim() === "") {
                    showError(priceThreshold, "Price Threshold is required.");
                    hasTyped = false;
                }
            }
        });
    });
    document.addEventListener("DOMContentLoaded", function() {
       const btnsave = document.getElementById('btnsubmit');
       let itemtype = document.getElementById("itemType");
        let purcCost = document.getElementById("inputPurCost");
        let priceUpdateFreq = document.getElementById("price_update_frequency");
        let priceThreshold = document.getElementById("price_threshold");

    btnsave.addEventListener('click', function(event) {

    let isValid = true;
    document.querySelectorAll(".error-text").forEach(el => el.innerHTML = "");
    // Get form fields
    let name = document.getElementById("inputName");
    let hsncode = document.getElementById("inputHSNcode");
    let uom = document.getElementById("inputState");
    let itemweight = document.getElementById("inputItemWeight");
    let categorySelect = document.getElementById("categorySelect");

    let mrp = document.getElementById("inputMargin");
    let price = document.getElementById("inputPrice");
    let tax = document.getElementById("inputTax");

    let errorDiv = document.getElementById("error-message");
    errorDiv.innerHTML = ""; // Clear previous errors
    let selectedOption = itemtype.selectedOptions[0];

   // Validation checks
   if (name.value.trim() === "") { showError(name, "Name is required."); isValid = false; }
        if (hsncode.value.trim() === "") { showError(hsncode, "HSN Code is required."); isValid = false; }
        else if (!/^\d{1,8}$/.test(hsncode.value)) {
                showError(hsncode, "HSN Code must be numeric and up to 8 digits.");
                isValid = false;
            }
        if (uom.value === "UoM") { showError(uom, "Please select a valid Unit of Measure."); isValid = false; }
        if (itemweight.value.trim() === "") { showError(itemweight, "Net Weight is required."); isValid = false; }
        if (categorySelect.selectedOptions.length === 0) { showError(categorySelect, "Please select at least one category."); isValid = false; }
        if (itemtype.value.trim() === "") { showError(itemtype, "Item Type is required."); isValid = false; }
        if (selectedOption && selectedOption.text.trim() === "Trading") {
            if (purcCost.value.trim() === "" || isNaN(purcCost.value)) { showError(purcCost, "Valid purcCost is required."); isValid = false; }
        }
        else {
            clearError(purcCost); // Clear error if not Trading
            clearError(priceUpdateFreq);
            clearError(priceThreshold);
        }
        // if (itemtype.value === "Trading") {
        //     if (purcCost.value.trim() === "" || isNaN(purcCost.value)) {
        //         showError(purcCost, "Valid purcCost is required.");
        //         isValid = false;
        //     }
        // } else {
        //     clearError(purcCost); // Clear error if not Trading
        //     clearError(priceUpdateFreq);
        //     clearError(priceThreshold);
        // }

        if (mrp.value.trim() === "" || isNaN(mrp.value)) { showError(mrp, "Valid MRP is required."); isValid = false; }
        if (price.value.trim() === "" || isNaN(price.value)) { showError(price, "Valid Price is required."); isValid = false; }
        if (tax.value.trim() === "" || isNaN(tax.value)) { showError(tax, "Valid Tax value is required."); isValid = false; }

       if (selectedOption && selectedOption.text.trim() === "Trading" && priceUpdateFreq.value.trim() === "") {
            showError(priceUpdateFreq, "Pricing Update Frequency is required.");
            isValid = false;
        }
        if (selectedOption && selectedOption.text.trim() === "Trading" && priceThreshold.value.trim() === "") {
            showError(priceThreshold, "Price Threshold is required.");
            isValid = false;
        }
        // if (priceUpdateFreq.value.trim() === "" || isNaN(priceUpdateFreq.value)) { showError(priceUpdateFreq, "Valid Pricing Update Frequency is required."); isValid = false; }
        // if (priceThreshold.value.trim() === "" || isNaN(priceThreshold.value)) { showError(priceThreshold, "Valid Price Threshold is required."); isValid = false; }

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
                const itemTypeValue =  document.querySelector("#itemtype")?.value;
                const isPurcCost = input.id === "inputPurCost";
                 let selectedOption = itemtype.selectedOptions[0];
                if (isPurcCost) {
                    if (selectedOption && selectedOption.text.trim() === "Trading") {
                        if (input.value.trim() === "") {
                            hasTyped = false;
                            showError(input, "This field is required!");
                            isValid = false;
                        }
                    }
                    return; // important to skip general required validation
                }
                if (input.value.trim() === "") {
                        hasTyped = false;
                        showError(input, "This field is required!");
                    }
                });
        });
    // Special handling for select2 dropdowns
    $('#inputState, #categorySelect').on("select2:select", function () {
        clearError(this); // Pass the select element to clearError function
    });
});
    function showError(input, message) {
        let errorElement = document.createElement("div");
        errorElement.className = "error-text text-danger";
        errorElement.innerHTML = message;
        input.parentNode.appendChild(errorElement);
        input.scrollIntoView({ behavior: 'smooth', block: 'center' });
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
