@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Add Recipe Costing</h1>
        <div class="row">
            <div class="mb-4">
                <input type="file" id="importRecipeFile" accept=".csv" style="display: none;">
                <a href="{{ asset('templates/recipe_costing_template.csv') }}" download class="btn" data-bs-toggle="tooltip" title="Use category_values: raw_material,  packing_material, overhead">
                    <button type="button" class="btn btn-success">
                        <i class="bi bi-download fs-4"></i> Download Template
                    </button>
                </a>
                <span data-bs-toggle="tooltip" title="Enter product details to enable">
                    <button
                        type="button"
                        class="btn btn-success"
                        id="importRecipeBtn"
                        disabled>
                        <i class="fas fa-upload"></i> Import CSV
                    </button>
                </span>
                <!-- <a href="{{ asset('templates/recipe_costing_template.csv') }}" download>Download Template</a> -->
            </div>
        </div>
    </div><!-- End Page Title -->
    <section class="section dashboard">
        <div class="container mt-5">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="mb-4">
                <label for="productSelect" id="productSelectLabel" class="form-label">Select Product</label>
                <div class="col-6">
                    <select id="productSelect" class="form-select select2" aria-labelledby="productSelectLabel">
                        <option selected disabled>Choose...</option>
                        @foreach($products as $productItem)
                        <option value="{{ $productItem->id }}">
                            {{ $productItem->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 col-sm-10 mb-2">
                    <label for="recipeOutput" class="form-label">Output</label>
                    <input type="text" class="form-control rounded" id="recipeOutput" name="recipeOutput">
                </div>
                <div class="col-md-2 col-sm-10">
                    <label for="recipeUoM" class="form-label">UoM</label>
                    <select id="recipeUoM" class="form-select" name="recipeUoM">
                        <option value="Ltr">Ltr</option>
                        <option value="Kgs">Kgs</option>
                        <option value="Nos">Nos</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2 col-sm-10 d-flex align-items-end gap-2">
                    <button type="button" class="btn btn-primary" id="saveRecipeBtn" style="display: none;" disabled>
                        <i class="fas fa-save"></i> Save
                    </button>
                    <button type="button" class="btn btn-primary" id="editRecipeBtn" style="display: none;">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button type="button" class="btn btn-success" id="updateRecipeBtn" style="display: none;" disabled>
                        <i class="fas fa-check"></i> Update
                    </button>
                    <button type="button" class="btn btn-secondary" id="cancelRecipeBtn" style="display: none;">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </div>

            <!-- Raw Material Section -->
            <div class="row mb-2">
                <div class="col-auto">
                    <label for="pricingrawmaterial" class="form-label text-primary" name="pricingrawmaterial" id="pricingrawmaterial">Raw Material</label>
                </div>
                <div class="col">
                    <hr />
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="rawmaterial" class="form-label">Raw Material</label>
                    <select id="rawmaterial" class="form-select select2" aria-labelledby="rawmaterial" title="Save product details to enable" disabled>
                        <option selected disabled>Choose...</option>
                        @foreach($rawMaterials as $rawMaterialItem)
                        <option
                            value="{{ $rawMaterialItem->id }}"
                            data-code="{{ $rawMaterialItem->rmcode }}"
                            data-uom="{{ $rawMaterialItem->uom }}"
                            data-price="{{ $rawMaterialItem->price }}"
                            data-name="{{ $rawMaterialItem->name }}">
                            {{ $rawMaterialItem->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="rmQuantity" class="form-label">Quantity</label>
                    <input type="text" class="form-control rounded" id="rmQuantity" name="rmQuantity">
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="rmCode" class="form-label">RM Code</label>
                    <input type="text" class="form-control rounded" id="rmCode" name="rmCode" disabled>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="rmUoM" class="form-label">UoM</label>
                    <input type="text" class="form-control" id="rmUoM" name="rmUoM" disabled>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="rmPrice" class="form-label">Price</label>
                    <input type="text" class="form-control rounded" id="rmPrice" name="rmPrice" disabled>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="rmAmount" class="form-label">Amount</label>
                    <input type="text" class="form-control" id="rmAmount" name="rmAmount" disabled>
                </div>
                <div class="d-flex flex-column" style="flex: 2;">
                    <button type="button" class="btn btn-primary rmaddbtn" id="rmaddbtn">
                        <i class="fas fa-plus"></i> Add
                    </button>
                </div>
            </div>
            <div class="row mb-4">
                <div class="table-responsive">
                    <table class="table table-bordered text-center" style="background-color: #eaf8ff; width:90%;">
                        <thead>
                            <tr>
                                <th>Raw Material</th>
                                <th>Quantity</th>
                                <th>RM Code</th>
                                <th>UoM</th>
                                <th>Price</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="rawMaterialTable"></tbody>
                    </table>
                    <div class="text-end" style="background-color: #eaf8ff; width:90%;">
                        <strong>RM Cost (A) : </strong> <span id="totalRmCost">0.00</span>
                    </div>
                </div>
            </div>

            <!-- Packing Material Section -->
            <div class="row mb-2">
                <div class="col-auto">
                    <label for="pricingpackingmaterial" class="form-label text-primary" id="pricingpackingmaterial">Packing Material</label>
                </div>
                <div class="col">
                    <hr />
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="packingmaterial" class="form-label">Packing Material</label>
                    <select id="packingmaterial" class="form-select select2" title="Save product details to enable" disabled>
                        <option selected disabled>Choose...</option>
                        @foreach($packingMaterials as $packingMaterialItem)
                        <option
                            value="{{ $packingMaterialItem->id }}"
                            data-code="{{ $packingMaterialItem->pmcode }}"
                            data-uom="{{ $packingMaterialItem->uom }}"
                            data-price="{{ $packingMaterialItem->price }}"
                            data-name="{{ $packingMaterialItem->name }}">
                            {{ $packingMaterialItem->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="pmQuantity" class="form-label">Quantity</label>
                    <input type="text" class="form-control rounded" id="pmQuantity" name="pmQuantity">
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="pmCode" class="form-label">PM Code</label>
                    <input type="text" class="form-control rounded" id="pmCode" name="pmCode" disabled>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="pmUoM" class="form-label">UoM</label>
                    <input type="text" class="form-control" id="pmUoM" name="pmUoM" disabled>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="pmPrice" class="form-label">Price</label>
                    <input type="text" class="form-control rounded" id="pmPrice" name="pmPrice" disabled>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="pmAmount" class="form-label">Amount</label>
                    <input type="text" class="form-control" id="pmAmount" name="pmAmount" disabled>
                </div>
                <div class="d-flex flex-column" style="flex: 2;">
                    <button type="button" class="btn btn-primary pmaddbtn" id="pmaddbtn"><i class="fas fa-plus"></i> Add</button>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12 col-md-12 mx-auto table-responsive">
                    <table class="table table-bordered text-center" style="background-color: #F1F1F1; width:90%;">
                        <thead class="no border">
                            <tr>
                                <th>Packing Material</th>
                                <th>Quantity</th>
                                <th>PM Code</th>
                                <th>UoM</th>
                                <th>Price</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="packingMaterialTable">
                        </tbody>
                    </table>
                    <div class="text-end" style="background-color:#F1F1F1; width:90%;">
                        <strong>PM Cost (B) : </strong> <span id="totalPmCost">0.00</span>
                    </div>
                </div>
            </div>

            <!-- Overheads Section -->
            <div class="row mb-2">
                <div class="col-auto">
                    <label for="pricingoverheads" class="form-label text-primary" id="pricingoverheads">Overheads</label>
                </div>
                <div class="col-2 form-check">
                    <input type="checkbox" class="form-check-input" id="frommasters" checked>
                    <label class="form-check-label" for="frommasters"> From Masters </label>
                </div>
                <div class="col-2 form-check">
                    <input type="checkbox" class="form-check-input" id="entermanually"> <label class="form-check-label" for="entermanually"> Enter Manually </label>
                </div>
                <div class="col">
                    <hr />
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="overheads" class="form-label">Overheads</label>
                    <select id="overheads" class="form-select select2" title="Save product details to enable" disabled>
                        <option selected disabled>Choose...</option>
                        @foreach($overheads as $overheadsItem)
                        <option
                            value="{{ $overheadsItem->id }}"
                            data-code="{{ $overheadsItem->ohcode }}"
                            data-uom="{{ $overheadsItem->uom }}"
                            data-price="{{ $overheadsItem->price }}"
                            data-name="{{ $overheadsItem->name }}">
                            {{ $overheadsItem->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="ohQuantity" class="form-label">Quantity</label>
                    <input type="text" class="form-control rounded" id="ohQuantity" name="ohQuantity">
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="ohCode" class="form-label">OH Code</label>
                    <input type="text" class="form-control rounded" id="ohCode" name="ohCode" disabled>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="ohUoM" class="form-label">UoM</label>
                    <input type="text" class="form-control" id="ohUoM" name="ohUoM" disabled>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="ohPrice" class="form-label">Price</label>
                    <input type="text" class="form-control rounded" id="ohPrice" name="ohPrice" disabled>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="ohAmount" class="form-label">Amount</label>
                    <input type="text" class="form-control" id="ohAmount" name="ohAmount" disabled>
                </div>
                <div class="d-flex flex-column" style="flex: 2;">
                    <button type="button" class="btn btn-primary ohaddbtn" id="ohaddbtn"><i class="fas fa-plus"></i> Add</button>
                </div>
            </div>
            <div id="manualEntry" style="display: none;">
                <div class="row mb-4">
                    <div class="d-flex flex-column" style="flex: 1.5;">
                        <label for="manualOverheads" class="form-label">Overheads Name</label>
                        <input type="text" class="form-control rounded" id="manualOverheads" name="manualOverheads">
                    </div>
                    <div class="d-flex flex-column" style="flex: 1.5;">
                        <label for="manualOhType" class="form-label">Type</label>
                        <select id="manualOhType" class="form-select">
                            <option value="price" selected>Overheads Price</option>
                            <option value="percentage">Overheads Percentage</option>
                        </select>
                    </div>
                    <div class="d-flex flex-column" style="flex: 1.5;">
                        <label for="manualOhPrice" class="form-label" id="manualOhPricelab">Overheads Price</label>
                        <input type="number" class="form-control rounded" id="manualOhPrice" name="manualOhPrice">
                        <label for="manualOhPerc" class="form-label" id="manualOhPerclab">Overheads Percentage</label>
                        <input type="number" class="form-control rounded" id="manualOhPerc" name="manualOhPerc" style="display: none;">
                    </div>
                    <div class="d-flex flex-column" style="flex: 2;">
                        <button type="button" class="btn btn-primary ohaddbtn" id="manualOhaddbtn">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12 col-md-12 mx-auto table-responsive">
                    <table class="table table-bordered text-center" style="background-color: #D7E1E4; width:90%;">
                        <thead class="no border">
                            <tr>
                                <th>Overheads</th>
                                <th>Quantity</th>
                                <th>OH Code</th>
                                <th>UoM</th>
                                <th id="ohHeaderPrice">Price</th>
                                <th id="ohHeaderAmount">Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="overheadsTable">
                        </tbody>
                    </table>
                    <div class="text-end" style="background-color: #D7E1E4; width:90%;">
                        <strong>OH Cost (C) : </strong> <span id="totalohCost">0.00</span>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="mb-2">
                    <div class="mt-2">
                        <label for="totalcost" class="form-label">Total Cost (A+B+C):</label>
                    </div>
                    <div>
                        <input type="text" class="form-control" id="totalcost" disabled>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="mt-2">
                        <label for="unitcost" class="form-label">Unit Cost:</label>
                    </div>
                    <div class="col-md-7">
                        <input type="text" class="form-control" id="unitcost" disabled>
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
{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}

<script>
    let product_id = null;

    function recipevalidation() {
        const rpvalue = document.getElementById('productSelect').value.trim();
        const rpopvalue = document.getElementById('recipeOutput').value.trim();
        const rpuomvalue = document.getElementById('recipeUoM').value.trim();

        const outputValue = parseFloat(rpopvalue);

        if (rpvalue === "" || rpvalue === "Choose...") {
            return false;
        }

        if (rpopvalue === "" || isNaN(outputValue) || outputValue <= 1) {
            return false;
        }

        if (rpuomvalue === "" || rpuomvalue === "UoM") {
            return false;
        }

        return true;
    }


    $(document).ready(function() {
        $('#productSelect').select2({
            theme: 'bootstrap-5',
            placeholder: "Choose or type...",
        });

        $('#rawmaterial').select2({
            theme: 'bootstrap-5',
            placeholder: "Choose or type...",
        });

        $('#productSelect').on('input', function() {
            product_id = this.value;
            console.log('Selected ID:', product_id);
            // if (!recipevalidation()) return;
            // document.getElementById('importRecipeBtn').disabled = !product_id;
        });
        $('#rawmaterial').on('input', function() {
            if (!recipevalidation()) return;
            console.log('Raw material changed/input detected');
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.disabled) {
                document.getElementById('rmCode').value = '';
                document.getElementById('rmUoM').value = '';
                document.getElementById('rmPrice').value = '';
                document.getElementById('rmAmount').value = '';
                return;
            }
            const code = selectedOption.getAttribute('data-code');
            const uom = selectedOption.getAttribute('data-uom');
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            document.getElementById('rmCode').value = code || '';
            document.getElementById('rmUoM').value = uom || '';
            document.getElementById('rmPrice').value = price.toFixed(2);
        });
        $('#packingmaterial').select2({
            theme: 'bootstrap-5',
            placeholder: "Choose or type...",
        });
        $('#packingmaterial').on('input', function() {
            if (!recipevalidation()) return;
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.disabled) {
                document.getElementById('pmCode').value = '';
                document.getElementById('pmUoM').value = '';
                document.getElementById('pmPrice').value = '';
                document.getElementById('pmAmount').value = '';
                return;
            }
            const code = selectedOption.getAttribute('data-code');
            const uom = selectedOption.getAttribute('data-uom');
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;

            document.getElementById('pmCode').value = code || '';
            document.getElementById('pmUoM').value = uom || '';
            document.getElementById('pmPrice').value = price.toFixed(2);
        });
        $('#overheads').select2({
            theme: 'bootstrap-5',
            placeholder: "Choose or type...",
        });
        $('#overheads').on('input', function() {
            if (!recipevalidation()) return;
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.disabled) {
                document.getElementById('ohCode').value = '';
                document.getElementById('ohUoM').value = '';
                document.getElementById('ohPrice').value = '';
                document.getElementById('ohAmount').value = '';
                return;
            }
            const code = selectedOption.getAttribute('data-code');
            const uom = selectedOption.getAttribute('data-uom');
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            document.getElementById('ohCode').value = code || '';
            document.getElementById('ohUoM').value = uom || '';
            document.getElementById('ohPrice').value = price.toFixed(2);
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('productSelect');
        const rawMaterialSelect = document.getElementById('rawmaterial');
        const quantityInput = document.getElementById('rmQuantity');
        const codeInput = document.getElementById('rmCode');
        const uomInput = document.getElementById('rmUoM');
        const priceInput = document.getElementById('rmPrice');
        const amountInput = document.getElementById('rmAmount');
        const addButton = document.getElementById('rmaddbtn');
        const tableBody = document.getElementById('rawMaterialTable');
        const totalCostSpan = document.getElementById('totalRmCost');
        const totalCostInput = document.getElementById('totalcost');
        const unitCostInput = document.getElementById('unitcost');

        const packingMaterialSelect = document.getElementById('packingmaterial');
        const pmQuantityInput = document.getElementById('pmQuantity');
        const pmCodeInput = document.getElementById('pmCode');
        const pmUoMInput = document.getElementById('pmUoM');
        const pmPriceInput = document.getElementById('pmPrice');
        const pmAmountInput = document.getElementById('pmAmount');
        const pmAddButton = document.getElementById('pmaddbtn');
        const packingMaterialTable = document.getElementById('packingMaterialTable');
        const totalPmCostSpan = document.getElementById('totalPmCost');

        const overheadsSelect = document.getElementById('overheads');
        const ohQuantityInput = document.getElementById('ohQuantity');
        const ohCodeInput = document.getElementById('ohCode');
        const ohUoMInput = document.getElementById('ohUoM');
        const ohPriceInput = document.getElementById('ohPrice');
        const ohAmountInput = document.getElementById('ohAmount');
        const ohAddButton = document.getElementById('ohaddbtn');
        const overheadsTable = document.getElementById('overheadsTable');
        const totalOhCostSpan = document.getElementById('totalohCost');

        const rpoutputInput = document.getElementById('recipeOutput');
        const rpuomInput = document.getElementById('recipeUoM');

        const fromMastersCheckbox = document.getElementById("frommasters");
        const enterManuallyCheckbox = document.getElementById("entermanually");
        const masterEntryDiv = document.getElementById("overheads").closest(".row.mb-4");
        const manualEntryDiv = document.getElementById("manualEntry");
        let manualOhPriceValue = 0;
        let manualOhPercValue = 0;

        let product_id = null;

        // Import Button and File Input
        const importRecipeBtn = document.getElementById('importRecipeBtn');
        const importRecipeFile = document.getElementById('importRecipeFile');

        // function recipevalidation() {
        //     const rpvalue = document.getElementById('productSelect').value.trim();
        //     const rpopvalue = document.getElementById('recipeOutput').value.trim();
        //     const rpuomvalue = document.getElementById('recipeUoM').value.trim();

        //     if (rpvalue === "" || rpvalue === "Choose...") {
        //         document.getElementById('productSelect').focus();
        //         return false;
        //     } else if (rpopvalue === "") {
        //         document.getElementById('recipeOutput').focus();
        //         return false;
        //     } else if (rpuomvalue === "" || rpuomvalue === "UoM") {
        //         document.getElementById('recipeUoM').focus();
        //         return false;
        //     }
        //     return true;
        // }

        // Function to toggle the Import CSV button based on validation
        function toggleImportButton() {
            if (recipevalidation()) {
                importRecipeBtn.disabled = false;
            } else {
                importRecipeBtn.disabled = true;
            }
        }

        // Add event listeners to monitor changes in the required fields
        productSelect.addEventListener('change', function() {
            product_id = this.value;
            console.log('Selected product ID:', product_id);
            toggleImportButton();
        });

        rpoutputInput.addEventListener('input', function() {
            toggleImportButton();
            updateUnitTotal();
        });

        rpuomInput.addEventListener('change', function() {
            toggleImportButton();
        });

        // Initial check to set the button state on page load
        toggleImportButton();

        function toggleForms() {
            const priceheader = document.getElementById('ohHeaderPrice');
            const amountheader = document.getElementById('ohHeaderAmount');
            if (fromMastersCheckbox.checked) {
                masterEntryDiv.style.display = "flex";
                manualEntryDiv.style.display = "none";
                priceheader.innerText = "Price";
                amountheader.innerText = "Amount";
            } else if (enterManuallyCheckbox.checked) {
                masterEntryDiv.style.display = "none";
                manualEntryDiv.style.display = "block";
                priceheader.innerText = "Percentage(%)";
                amountheader.innerText = "Price/Amount";
            } else {
                masterEntryDiv.style.display = "none";
                manualEntryDiv.style.display = "none";
            }
        }

        const manualOhType = document.getElementById("manualOhType");
        const manualOhPrice = document.getElementById("manualOhPrice");
        const manualOhPricelab = document.getElementById("manualOhPricelab");
        const manualOhPerc = document.getElementById("manualOhPerc");
        const manualOhPerclab = document.getElementById("manualOhPerclab");

        function toggleFields() {
            if (manualOhType.value === "price") {
                manualOhPrice.style.display = "block";
                manualOhPricelab.style.display = "block";
                manualOhPerc.style.display = "none";
                manualOhPerclab.style.display = "none";
            } else {
                manualOhPrice.style.display = "none";
                manualOhPricelab.style.display = "none";
                manualOhPerc.style.display = "block";
                manualOhPerclab.style.display = "block";
            }
        }

        toggleFields();
        manualOhType.addEventListener("change", toggleFields);

        fromMastersCheckbox.addEventListener("change", function() {
            if (fromMastersCheckbox.checked) {
                enterManuallyCheckbox.checked = false;
            }
            toggleForms();
        });

        enterManuallyCheckbox.addEventListener("change", function() {
            if (enterManuallyCheckbox.checked) {
                fromMastersCheckbox.checked = false;
            }
            toggleForms();
        });

        fromMastersCheckbox.checked = true;
        toggleForms();

        // Function to update dropdown options by disabling already selected items
        function updateDropdownOptions(category) {
            let dropdown, table;
            if (category === 'raw_material') {
                dropdown = rawMaterialSelect;
                table = tableBody;
            } else if (category === 'packing_material') {
                dropdown = packingMaterialSelect;
                table = packingMaterialTable;
            } else if (category === 'overhead') {
                dropdown = overheadsSelect;
                table = overheadsTable;
            } else {
                return; // Manual overhead doesn't use a dropdown
            }

            const tableItems = Array.from(table.querySelectorAll('tr'))
                .filter(row => category !== 'overhead' || row.cells[2].textContent !== '-') // For overheads, only consider rows with a code (from masters)
                .map(row => row.cells[0].textContent);
            Array.from(dropdown.options).forEach(option => {
                if (option.value === dropdown.options[0].value) return; // Skip the "Choose..." option
                const optionName = option.getAttribute('data-name');
                option.disabled = tableItems.includes(optionName);
            });

            // Reset the dropdown to the default "Choose..." option if the current selection is disabled
            if (dropdown.options[dropdown.selectedIndex]?.disabled) {
                dropdown.value = dropdown.options[0].value;
                if (category === 'raw_material') clearFields();
                else if (category === 'packing_material') clearPmFields();
                else if (category === 'overhead') clearOhFields();
            }

            // Trigger Select2 update if applicable
            if (typeof $(dropdown).select2 === 'function') {
                $(dropdown).trigger('change');
            }
        }

        rawMaterialSelect.addEventListener('change', function() {
            if (!recipevalidation()) return;
            console.log('Raw material changed/input detected');
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.disabled) {
                clearFields();
                return;
            }
            const code = selectedOption.getAttribute('data-code');
            const uom = selectedOption.getAttribute('data-uom');
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;

            codeInput.value = code || '';
            uomInput.value = uom || '';
            priceInput.value = price.toFixed(2);
            updateAmount();
        });

        // rawMaterialSelect.addEventListener('input', function() {
        //     if (!recipevalidation()) return;
        //     console.log('Raw material changed/input detected');
        //     const selectedOption = this.options[this.selectedIndex];
        //     if (selectedOption.disabled) {
        //         clearFields();
        //         return;
        //     }
        //     const code = selectedOption.getAttribute('data-code');
        //     const uom = selectedOption.getAttribute('data-uom');
        //     const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;

        //     codeInput.value = code || '';
        //     uomInput.value = uom || '';
        //     priceInput.value = price.toFixed(2);

        //     updateAmount();
        // });

        quantityInput.addEventListener('input', updateAmount);

        addButton.addEventListener('click', function() {
            product_id = productSelect.value;
            if (!product_id) {
                alert('Please select a valid product.');
                return;
            }
            if (!recipevalidation()) return;
            const rawMaterialId = rawMaterialSelect.value;
            const rawMaterialName = rawMaterialSelect.options[rawMaterialSelect.selectedIndex]?.text;
            const quantity = parseFloat(quantityInput.value) || 0;
            const code = codeInput.value;
            const uom = uomInput.value;
            const price = parseFloat(priceInput.value) || 0;
            const amount = parseFloat(amountInput.value) || 0;

            const rpoutput = rpoutputInput.value.trim();
            const rpuom = rpuomInput.value;

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!token) {
                console.error('CSRF token not found.');
                return;
            }

            if (!rawMaterialName || !quantity || !code || !uom || !price || !amount) {
                alert('Please fill all fields before adding.');
                return;
            }

            const rows = Array.from(tableBody.querySelectorAll('tr'));
            const isAlreadyAdded = rows.some(row => row.cells[0].textContent === rawMaterialName);

            if (isAlreadyAdded) {
                alert('This raw material has already been added to the table.');
                clearFields();
                return;
            }

            addRawMaterial(rawMaterialId, rawMaterialName, quantity, code, uom, price, amount, rpoutput, rpuom, token);
        });

        function addRawMaterial(id, name, quantity, code, uom, price, amount, rpoutput, rpuom, token) {
            fetch('/rm-for-recipe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({
                        product_id: product_id,
                        raw_material_id: id,
                        quantity: quantity,
                        amount: amount,
                        code: code,
                        uom: uom,
                        price: price,
                        rpoutput: rpoutput,
                        rpuom: rpuom,
                    }),
                })
                .then(response => {
                    if (!response.ok) throw new Error('Server response not OK');
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                    const rmInsertedId = data.rmInserted_id;
                    const row = `<tr>
                    <td>${name}</td>
                    <td>${quantity}</td>
                    <td>${code}</td>
                    <td>${uom}</td>
                    <td>${price.toFixed(2)}</td>
                    <td>${amount.toFixed(2)}</td>
                    <td>
                        <span class="delete-icon" style="cursor: pointer; color: red;" title="Remove Row" data-id="${rmInsertedId}">🗑</span>
                    </td>
                </tr>`;
                    tableBody.insertAdjacentHTML('beforeend', row);
                    updateTotalCost(amount);
                    updateDropdownOptions('raw_material');
                    clearFields();
                })
                .catch(error => console.error('Error:', error.message));
        }

        tableBody.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-icon')) {
                const deleteIcon = e.target;
                const row = deleteIcon.closest('tr');
                const rmInsertedId = deleteIcon.getAttribute('data-id');

                if (!confirm('Are you sure you want to delete this record?')) return;

                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!token) {
                    console.error('CSRF token not found.');
                    return;
                }

                fetch(`/rm-for-recipe/${rmInsertedId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Server response not OK');
                        return response.json();
                    })
                    .then(data => {
                        console.log('Success:', data);
                        const amount = parseFloat(row.cells[5].textContent) || 0;
                        row.remove();
                        updateTotalCost(-amount);
                        updateDropdownOptions('raw_material');
                    })
                    .catch(error => console.error('Error:', error.message));
            }
        });

        packingMaterialSelect.addEventListener('change', function() {
            if (!recipevalidation()) return;
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.disabled) {
                clearPmFields();
                return;
            }
            const code = selectedOption.getAttribute('data-code');
            const uom = selectedOption.getAttribute('data-uom');
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;

            pmCodeInput.value = code || '';
            pmUoMInput.value = uom || '';
            pmPriceInput.value = price.toFixed(2);

            updatePmAmount();
        });

        pmQuantityInput.addEventListener('input', updatePmAmount);

        pmAddButton.addEventListener('click', function() {
            product_id = productSelect.value;
            if (!product_id) {
                alert('Please select a valid product.');
                return;
            }
            if (!recipevalidation()) return;

            const packingMaterialId = packingMaterialSelect.value;
            const packingMaterialName = packingMaterialSelect.options[packingMaterialSelect.selectedIndex]?.text;
            const quantity = parseFloat(pmQuantityInput.value) || 0;
            const code = pmCodeInput.value;
            const uom = pmUoMInput.value;
            const price = parseFloat(pmPriceInput.value) || 0;
            const amount = parseFloat(pmAmountInput.value) || 0;

            if (!packingMaterialName || !quantity || !code || !uom || !price || !amount) {
                alert('Please fill all fields before adding.');
                return;
            }

            const rows = Array.from(packingMaterialTable.querySelectorAll('tr'));
            const isAlreadyAdded = rows.some(row => row.cells[0].textContent === packingMaterialName);

            if (isAlreadyAdded) {
                alert('This packing material has already been added to the table.');
                clearPmFields();
                return;
            }

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!token) {
                console.error('CSRF token not found.');
                return;
            }

            if (!packingMaterialId || quantity <= 0 || amount <= 0) {
                alert('Please select a valid packing material and fill all fields correctly.');
                return;
            }

            addPackingMaterial(packingMaterialId, packingMaterialName, quantity, code, uom, price, amount, token);
        });

        function addPackingMaterial(id, name, quantity, code, uom, price, amount, token) {
            fetch('/pm-for-recipe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({
                        product_id: product_id,
                        packing_material_id: id,
                        quantity: quantity,
                        amount: amount,
                        code: code,
                        uom: uom,
                        price: price,
                    }),
                })
                .then(response => {
                    if (!response.ok) throw new Error('Server response not OK');
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                    const pmInsertedId = data.pmInserted_id;
                    const row = `<tr>
                    <td>${name}</td>
                    <td>${quantity}</td>
                    <td>${code}</td>
                    <td>${uom}</td>
                    <td>${price.toFixed(2)}</td>
                    <td>${amount.toFixed(2)}</td>
                    <td>
                        <span class="delete-icon" style="cursor: pointer; color: red;" title="Remove Row" data-id="${pmInsertedId}">🗑</span>
                    </td>
                </tr>`;
                    packingMaterialTable.insertAdjacentHTML('beforeend', row);
                    updatePmTotalCost(amount);
                    updateDropdownOptions('packing_material');
                    clearPmFields();
                })
                .catch(error => console.error('Error:', error.message));
        }

        packingMaterialTable.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-icon')) {
                const deleteIcon = e.target;
                const row = deleteIcon.closest('tr');
                const pmInsertedId = deleteIcon.getAttribute('data-id');

                if (!confirm('Are you sure you want to delete this record?')) return;

                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!token) {
                    console.error('CSRF token not found.');
                    return;
                }

                fetch(`/pm-for-recipe/${pmInsertedId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Server response not OK');
                        return response.json();
                    })
                    .then(data => {
                        console.log('Success:', data);
                        const amount = parseFloat(row.cells[5].textContent) || 0;
                        row.remove();
                        updatePmTotalCost(-amount);
                        updateDropdownOptions('packing_material');
                    })
                    .catch(error => console.error('Error:', error.message));
            }
        });

        overheadsSelect.addEventListener('change', function() {
            if (!recipevalidation()) return;
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.disabled) {
                clearOhFields();
                return;
            }
            const code = selectedOption.getAttribute('data-code');
            const uom = selectedOption.getAttribute('data-uom');
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;

            ohCodeInput.value = code || '';
            ohUoMInput.value = uom || '';
            ohPriceInput.value = price.toFixed(2);

            updateOhAmount();
        });

        ohQuantityInput.addEventListener('input', updateOhAmount);

        ohAddButton.addEventListener('click', function() {
            product_id = productSelect.value;
            if (!product_id) {
                alert('Please select a valid product.');
                return;
            }
            if (!recipevalidation()) return;

            const overheadsId = overheadsSelect.value;
            const overheadsName = overheadsSelect.options[overheadsSelect.selectedIndex]?.text;
            const quantity = parseFloat(ohQuantityInput.value) || 0;
            const code = ohCodeInput.value;
            const uom = ohUoMInput.value;
            const price = parseFloat(ohPriceInput.value) || 0;
            const amount = parseFloat(ohAmountInput.value) || 0;

            if (!overheadsName || !quantity || !code || !uom || !price || !amount) {
                alert('Please fill all fields before adding.');
                return;
            }

            const rows = Array.from(overheadsTable.querySelectorAll('tr'));
            const isAlreadyAdded = rows.some(row => row.cells[0].textContent === overheadsName);

            if (isAlreadyAdded) {
                alert('This overhead has already been added to the table.');
                clearOhFields();
                return;
            }

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!token) {
                console.error('CSRF token not found.');
                return;
            }

            if (!overheadsId || quantity <= 0 || amount <= 0) {
                alert('Please select a valid overhead and fill all fields correctly.');
                return;
            }

            addOverhead(overheadsId, overheadsName, quantity, code, uom, price, amount, token);
        });

        function addOverhead(id, name, quantity, code, uom, price, amount, token) {
            fetch('/oh-for-recipe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({
                        product_id: product_id,
                        overheads_id: id,
                        quantity: quantity,
                        amount: amount,
                        code: code,
                        uom: uom,
                        price: price,
                    }),
                })
                .then(response => {
                    if (!response.ok) throw new Error('Server response not OK');
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                    const insertedId = data.inserted_id;
                    const row = `<tr>
                    <td>${name}</td>
                    <td>${quantity}</td>
                    <td>${code}</td>
                    <td>${uom}</td>
                    <td>${price.toFixed(2)}</td>
                    <td>${amount.toFixed(2)}</td>
                    <td>
                        <span class="delete-icon" style="cursor: pointer; color: red;" title="Remove Row" data-id="${insertedId}">🗑</span>
                    </td>
                </tr>`;
                    overheadsTable.insertAdjacentHTML('beforeend', row);
                    updateOhTotalCost(amount);
                    updateDropdownOptions('overhead');
                    clearOhFields();
                    fromMastersCheckbox.checked = true; // Reset to masters entry
                    enterManuallyCheckbox.disabled = true;
                })
                .catch(error => console.error('Error:', error.message));
        }

        const manualOhAddButton = document.getElementById('manualOhaddbtn');

        function calcForManual() {
            const manualOhTypeValue = document.getElementById("manualOhType").value.trim();
            const rmTotal = parseFloat(totalCostSpan.textContent) || 0;
            const pmTotal = parseFloat(totalPmCostSpan.textContent) || 0;

            if ((rmTotal + pmTotal) <= 0) {
                alert("Please add raw materials & packing materials to calculate manual overheads.");
                throw new Error("Raw materials and packing materials total must be greater than 0.");
            }

            if (manualOhTypeValue === 'price') {
                manualOhPriceValue = parseFloat(document.getElementById("manualOhPrice").value) || 0;
                manualOhPercValue = (manualOhPriceValue / (rmTotal + pmTotal)) * 100;
            } else if (manualOhTypeValue === 'percentage') {
                manualOhPercValue = parseFloat(document.getElementById("manualOhPerc").value) || 0;
                manualOhPriceValue = ((rmTotal + pmTotal) * manualOhPercValue) / 100;
            }
        }

        manualOhAddButton.addEventListener('click', function() {
            console.log("Add button clicked");
            product_id = productSelect.value;
            if (!product_id) {
                alert('Please select a valid product.');
                return;
            }
            if (!recipevalidation()) return;

            const manualOverheadsName = document.getElementById("manualOverheads").value.trim();
            const manualOhTypeValue = document.getElementById("manualOhType").value;

            try {
                calcForManual();
            } catch (error) {
                console.error(error.message);
                return;
            }

            if (!manualOverheadsName || (manualOhTypeValue === "price" && manualOhPriceValue <= 0) || (manualOhTypeValue === "percentage" && manualOhPercValue <= 0)) {
                alert("Please fill all fields with valid values before adding.");
                return;
            }

            const rows = Array.from(overheadsTable.querySelectorAll('tr'));
            const isAlreadyAdded = rows.some(row => row.cells[0].textContent === manualOverheadsName);

            if (isAlreadyAdded) {
                alert('This overhead has already been added to the table.');
                clearOhFields();
                return;
            }

            const data = {
                product_id: product_id,
                manualOverheads: manualOverheadsName,
                manualOverheadsType: manualOhTypeValue,
                manualOhPrice: manualOhPriceValue,
                manualOhPerc: manualOhPercValue,
            };
            console.log("Data to send:", data);

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!token) {
                console.error("CSRF token not found.");
                return;
            }

            fetch("/manual-overhead", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": token,
                    },
                    body: JSON.stringify(data),
                })
                .then(response => {
                    if (!response.ok) throw new Error('Server response not OK');
                    return response.json();
                })
                .then(data => {
                    console.log("Parsed Response:", data);
                    if (data.success) {
                        console.log("Manual overhead added successfully!");
                        const insertedId = data.inserted_id;

                        const row = `<tr>
                            <td>${manualOverheadsName}</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>${manualOhPercValue.toFixed(2)}</td>
                            <td>${manualOhPriceValue.toFixed(2)}</td>
                            <td>
                                <span class="delete-icon" style="cursor: pointer; color: red;" title="Remove Row" data-id="${insertedId}">🗑</span>
                            </td>
                        </tr>`;

                        overheadsTable.insertAdjacentHTML("beforeend", row);
                        updateOhTotalCost(manualOhPriceValue);
                        clearOhFields();
                        enterManuallyCheckbox.checked = true; // Reset to masters entry
                        fromMastersCheckbox.disabled = true;
                    } else {
                        alert("Failed to save manual overhead: " + (data.message || "Unknown error"));
                    }
                })
                .catch(error => {
                    console.error('Error:', error.message);
                    alert("An error occurred while adding the manual overhead: " + error.message);
                });
        });

        overheadsTable.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-icon')) {
                const deleteIcon = e.target;
                const row = deleteIcon.closest('tr');
                const insertedId = deleteIcon.getAttribute('data-id');

                if (!row) {
                    console.error("Row not found.");
                    enterManuallyCheckbox.enabled = true; // Reset to masters entry
                    fromMastersCheckbox.enabled = true;
                    return;
                }

                if (!confirm('Are you sure you want to delete this record?')) return;

                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!token) {
                    console.error('CSRF token not found.');
                    return;
                }

                const code = row.cells[2]?.textContent.trim();

                if (code === '-') {
                    mohDelete(insertedId, row, token);
                } else {
                    ohDelete(insertedId, row, token);
                }

            }
        });

        function ohDelete(insertedId, row, token) {
            fetch(`/oh-for-recipe/${insertedId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                })
                .then(response => {
                    if (!response.ok) throw new Error('Server response not OK');
                    return response.json();
                })
                .then(data => {
                    console.log('Deleted overhead from masters:', data);
                    const amount = parseFloat(row.cells[5]?.textContent) || 0;
                    row.remove();
                    updateOhTotalCost(-amount);
                    updateDropdownOptions('overhead');
                    console.log('row', overheadsTable.rows.length);
                    if (overheadsTable.rows.length == 0) {
                        console.log('row test');
                        enterManuallyCheckbox.disabled = false; // Reset to masters entry
                        toggleForms();
                    }
                })
                .catch(error => console.error('Error:', error.message));
        }

        function mohDelete(insertedId, row, token) {
            fetch(`/moh-for-recipe/${insertedId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                })
                .then(response => {
                    if (!response.ok) throw new Error('Server response not OK');
                    return response.json();
                })
                .then(data => {
                    console.log('Deleted manual overhead:', data);
                    const amount = parseFloat(row.cells[5].textContent) || 0;
                    row.remove();
                    updateOhTotalCost(-amount);
                    if (overheadsTable.rows.length == 0) {
                        // Reset to masters entry
                        fromMastersCheckbox.disabled = false;
                        toggleForms();
                    }
                })
                .catch(error => console.error('Error:', error.message));
        }

        // Import Recipe CSV
        importRecipeBtn.addEventListener('click', function() {
            importRecipeFile.click();
        });

        importRecipeFile.addEventListener('change', function(e) {
            try {
                console.log(product_id);
                product_id = productSelect.value;
                if (!product_id) {
                    alert('Please select a valid product.');
                    return;
                }
                if (!recipevalidation()) return;

                const file = e.target.files[0];
                if (!file) return;

                // Check file extension
                const validExtension = /\.csv$/i.test(file.name);
                if (!validExtension) {
                    alert('Invalid file type. Please upload a CSV file. Download the template for the correct format.');
                    e.target.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const text = e.target.result;
                    const rows = text.split('\n').map(row => row.trim()).filter(row => row);

                    // Validate the header exactly
                    const expectedHeader = 'category,name,quantity,code,uom,price,amount';
                    let headerIndex = rows.findIndex(row => row === expectedHeader);
                    if (headerIndex === -1) {
                        alert('Invalid CSV format: The header does not match the expected format. Please download the template and use the correct format:\n' + expectedHeader);
                        e.target.value = '';
                        return;
                    }

                    // Skip rows before the header and process data rows after the header
                    const dataRows = rows.slice(headerIndex + 1).filter(row => row && !row.startsWith('#'));
                    if (dataRows.length === 0) {
                        alert('No data rows found in the CSV file. Please ensure the file contains data in the correct format.');
                        e.target.value = '';
                        return;
                    }

                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (!token) {
                        console.error('CSRF token not found.');
                        alert('CSRF token not found. Please refresh the page and try again.');
                        e.target.value = '';
                        return;
                    }

                    const rpoutput = rpoutputInput.value.trim();
                    const rpuom = rpuomInput.value;

                    let successCount = 0;
                    let failureCount = 0;
                    const errors = [];

                    const processRow = (row, index) => {
                        const [category, name, quantity, code, uom, price, amount] = row.split(',').map(item => item.trim());
                        if (!category || !name) {
                            errors.push(`Row ${index + 1}: Invalid data - missing category or name. Row: ${row}`);
                            failureCount++;
                            return;
                        }

                        try {
                            if (category === 'raw_material') {
                                if (!quantity || !code || !uom || !price || !amount) {
                                    throw new Error(`Invalid raw material data - missing required fields. Row: ${row}`);
                                }
                                if (isNaN(parseFloat(quantity)) || isNaN(parseFloat(price)) || isNaN(parseFloat(amount))) {
                                    throw new Error(`Invalid raw material data - quantity, price, and amount must be numeric. Row: ${row}`);
                                }
                                const rows = Array.from(tableBody.querySelectorAll('tr'));
                                if (rows.some(row => row.cells[0].textContent === name)) {
                                    throw new Error(`Raw material ${name} is already added.`);
                                }
                                const rawMaterialOption = Array.from(rawMaterialSelect.options).find(option => option.text === name);
                                if (!rawMaterialOption) {
                                    throw new Error(`Raw material ${name} not found in the database.`);
                                }
                                const rawMaterialId = rawMaterialOption.value;
                                addRawMaterial(rawMaterialId, name, parseFloat(quantity), code, uom, parseFloat(price), parseFloat(amount), rpoutput, rpuom, token);
                                successCount++;
                            } else if (category === 'packing_material') {
                                if (!quantity || !code || !uom || !price || !amount) {
                                    throw new Error(`Invalid packing material data - missing required fields. Row: ${row}`);
                                }
                                if (isNaN(parseFloat(quantity)) || isNaN(parseFloat(price)) || isNaN(parseFloat(amount))) {
                                    throw new Error(`Invalid packing material data - quantity, price, and amount must be numeric. Row: ${row}`);
                                }
                                const rows = Array.from(packingMaterialTable.querySelectorAll('tr'));
                                if (rows.some(row => row.cells[0].textContent === name)) {
                                    throw new Error(`Packing material ${name} is already added.`);
                                }
                                const packingMaterialOption = Array.from(packingMaterialSelect.options).find(option => option.text === name);
                                if (!packingMaterialOption) {
                                    throw new Error(`Packing material ${name} not found in the database.`);
                                }
                                const packingMaterialId = packingMaterialOption.value;
                                addPackingMaterial(packingMaterialId, name, parseFloat(quantity), code, uom, parseFloat(price), parseFloat(amount), token);
                                successCount++;
                            } else if (category === 'overhead') {
                                if (!quantity || !code || !uom || !price || !amount) {
                                    throw new Error(`Invalid overhead data - missing required fields. Row: ${row}`);
                                }
                                if (isNaN(parseFloat(quantity)) || isNaN(parseFloat(price)) || isNaN(parseFloat(amount))) {
                                    throw new Error(`Invalid overhead data - quantity, price, and amount must be numeric. Row: ${row}`);
                                }
                                const rows = Array.from(overheadsTable.querySelectorAll('tr'));
                                if (rows.some(row => row.cells[0].textContent === name)) {
                                    throw new Error(`Overhead ${name} is already added.`);
                                }
                                const overheadOption = Array.from(overheadsSelect.options).find(option => option.text === name);
                                if (!overheadOption) {
                                    throw new Error(`Overhead ${name} not found in the database.`);
                                }
                                const overheadId = overheadOption.value;
                                addOverhead(overheadId, name, parseFloat(quantity), code, uom, parseFloat(price), parseFloat(amount), token);
                                successCount++;
                            } else {
                                throw new Error(`Unknown category: ${category}`);
                            }
                        } catch (error) {
                            errors.push(`Row ${index + 1}: ${error.message}`);
                            failureCount++;
                        }
                    };

                    // Process each row
                    dataRows.forEach((row, index) => {
                        processRow(row, index);
                    });

                    // Display summary
                    let summaryMessage = `CSV Import Summary:\n- Successfully imported: ${successCount} rows\n- Failed: ${failureCount} rows`;
                    if (errors.length > 0) {
                        summaryMessage += '\n\nErrors encountered:\n' + errors.join('\n');
                    }
                    alert(summaryMessage);

                    e.target.value = '';
                };

                reader.onerror = function() {
                    alert('Error reading the file. Please ensure the file is a valid CSV and try again.');
                    e.target.value = '';
                };

                reader.readAsText(file);
            } catch (error) {
                console.error('Error processing CSV file:', error);
                alert('There was an issue importing the Excel file. It might be due to an invalid file format or values. Please check the file and try again.');
                e.target.value = '';
            }
        });

        function updateAmount() {
            const price = parseFloat(priceInput.value) || 0;
            const quantity = parseFloat(quantityInput.value) || 0;
            amountInput.value = (price * quantity).toFixed(2);
        }

        function updateTotalCost(newAmount) {
            const currentTotal = parseFloat(totalCostSpan.textContent) || 0;
            totalCostSpan.textContent = (currentTotal + newAmount).toFixed(2);
            updateGrandTotal();
        }

        function clearFields() {
            rawMaterialSelect.value = rawMaterialSelect.options[0].value;
            quantityInput.value = '';
            codeInput.value = '';
            uomInput.value = '';
            priceInput.value = '';
            amountInput.value = '';
        }

        function updatePmAmount() {
            const price = parseFloat(pmPriceInput.value) || 0;
            const quantity = parseFloat(pmQuantityInput.value) || 0;
            pmAmountInput.value = (price * quantity).toFixed(2);
        }

        function updatePmTotalCost(newAmount) {
            const currentTotal = parseFloat(totalPmCostSpan.textContent) || 0;
            totalPmCostSpan.textContent = (currentTotal + newAmount).toFixed(2);
            updateGrandTotal();
        }

        function clearPmFields() {
            packingMaterialSelect.value = packingMaterialSelect.options[0].value;
            pmQuantityInput.value = '';
            pmCodeInput.value = '';
            pmUoMInput.value = '';
            pmPriceInput.value = '';
            pmAmountInput.value = '';
        }

        function updateOhAmount() {
            const price = parseFloat(ohPriceInput.value) || 0;
            const quantity = parseFloat(ohQuantityInput.value) || 0;
            ohAmountInput.value = (price * quantity).toFixed(2);
        }

        function updateOhTotalCost(newAmount) {
            const currentTotal = parseFloat(totalOhCostSpan.textContent) || 0;
            totalOhCostSpan.textContent = (currentTotal + newAmount).toFixed(2);
            updateGrandTotal();
        }

        function clearOhFields() {
            overheadsSelect.value = overheadsSelect.options[0].value;
            ohQuantityInput.value = '';
            ohCodeInput.value = '';
            ohUoMInput.value = '';
            ohPriceInput.value = '';
            ohAmountInput.value = '';
            document.getElementById("manualOverheads").value = '';
            document.getElementById("manualOhPrice").value = '';
            document.getElementById("manualOhPerc").value = '';
        }

        function updateGrandTotal() {
            const rawMaterialTotal = parseFloat(totalCostSpan.textContent) || 0;
            const packingMaterialTotal = parseFloat(totalPmCostSpan.textContent) || 0;
            const overheadsTotal = parseFloat(totalOhCostSpan.textContent) || 0;
            const grandTotal = rawMaterialTotal + packingMaterialTotal + overheadsTotal;
            totalCostInput.value = grandTotal.toFixed(2);
            updateUnitTotal();
        }

        function updateUnitTotal() {
            const rawMaterialTotal = parseFloat(totalCostSpan.textContent) || 0;
            const packingMaterialTotal = parseFloat(totalPmCostSpan.textContent) || 0;
            const overheadsTotal = parseFloat(totalOhCostSpan.textContent) || 0;
            const grandTotal = rawMaterialTotal + packingMaterialTotal + overheadsTotal;
            const recipeOutput = parseFloat(rpoutputInput.value) || 0;

            const unitCost = recipeOutput > 0 ? grandTotal / recipeOutput : 0;
            unitCostInput.value = unitCost.toFixed(2);
        }

        function recipePricing() {
            const rpoutput = rpoutputInput.value.trim();
            const rpuom = rpuomInput.value;

            if (rpoutput <= 0 || !rpuom) {
                alert('Invalid input values. Please check your data.');
                return;
            }

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!token) {
                console.error('CSRF token not found.');
                alert('A CSRF token is required for this action.');
                return;
            }

            if (!product_id) {
                alert('Product ID is missing or invalid.');
                return;
            }

            fetch('/recipepricing', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({
                        product_id: product_id,
                        rpoutput: rpoutput,
                        rpuom: rpuom,
                    }),
                })
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                    alert('Recipe-pricing added successfully');
                    recipeId = data.recipe_id;
                    // Store saved values
                    savedProductId = product_id;
                    savedOutput = output;
                    savedUom = uom;
                    // Disable input fields
                    document.getElementById('productSelect').disabled = true;
                    document.getElementById('recipeOutput').disabled = true;
                    document.getElementById('recipeUoM').disabled = true;
                    // Hide Save button, show Edit button
                    document.getElementById('saveRecipeBtn').style.display = 'none';
                    document.getElementById('editRecipeBtn').style.display = 'block';
                    document.getElementById('updateRecipeBtn').style.display = 'none';
                    document.getElementById('cancelRecipeBtn').style.display = 'none';
                })
                .catch(error => console.error('Error:', error.message));
        }

        updateUnitTotal();
    });
</script>

<script>
    let recipeId = null;
    let savedProductId = null;
    let savedOutput = null;
    let savedUom = null;

    function recipevalidation() {
        const product_id = document.getElementById('productSelect').value;
        const output = document.getElementById('recipeOutput').value.trim();
        const uom = document.getElementById('recipeUoM').value;

        // Validate product selected, output is positive number, uom selected
        if (!product_id || product_id === 'Choose...') return false;
        if (!output || isNaN(output) || Number(output) <= 0) return false;
        if (!uom || uom === 'UoM') return false;

        return true;
    }

    function recipePricing() {
        const product_id = document.getElementById('productSelect').value;
        const output = document.getElementById('recipeOutput').value.trim();
        const uom = document.getElementById('recipeUoM').value.trim();
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        if (!token) {
            alert('CSRF token not found. Please refresh the page and try again.');
            return;
        }

        fetch('/addrecipecosting', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                },
                body: JSON.stringify({
                    product_id: product_id,
                    rpoutput: output,
                    rpuom: uom,
                }),
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    const msg = data.message || 'Server response not OK';
                    const errors = data.errors ? JSON.stringify(data.errors) : '';
                    throw new Error(`${msg}\n${errors}`);
                }
                return data;
            })
            .then(data => {
                alert(data.message || 'Recipe saved successfully!');
                recipeId = data.recipe_id;

                // Store saved values
                savedProductId = product_id;
                savedOutput = output;
                savedUom = uom;

                // Disable inputs
                document.getElementById('productSelect').disabled = true;
                document.getElementById('recipeOutput').disabled = true;
                document.getElementById('recipeUoM').disabled = true;

                document.getElementById('rawmaterial').disabled = false;
                document.getElementById('packingmaterial').disabled = false;
                document.getElementById('overheads').disabled = false;

                // Toggle buttons
                document.getElementById('saveRecipeBtn').style.display = 'none';
                document.getElementById('editRecipeBtn').style.display = 'block';
                document.getElementById('updateRecipeBtn').style.display = 'none';
                document.getElementById('cancelRecipeBtn').style.display = 'none';
            })
            .catch(error => {
                console.error('Error:', error.message);
                alert('Failed to save recipe: ' + error.message);
            });
    }

    function updateRecipe() {
        const product_id = document.getElementById('productSelect').value;
        const output = document.getElementById('recipeOutput').value.trim();
        const uom = document.getElementById('recipeUoM').value.trim();
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        if (!token) {
            alert('CSRF token not found. Please refresh the page and try again.');
            return;
        }

        if (!recipeId) {
            alert('No recipe ID found for updating. Please save the recipe first.');
            return;
        }

        fetch(`/recipepricing/${recipeId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                },
                body: JSON.stringify({
                    product_id: product_id,
                    rpoutput: output,
                    rpuom: uom,
                }),
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    const msg = data.message || 'Server response not OK';
                    const errors = data.errors ? JSON.stringify(data.errors) : '';
                    throw new Error(`${msg}\n${errors}`);
                }
                return data;
            })
            .then(data => {
                alert(data.message || 'Recipe updated successfully!');
                savedProductId = product_id;
                savedOutput = output;
                savedUom = uom;

                document.getElementById('productSelect').disabled = true;
                document.getElementById('recipeOutput').disabled = true;
                document.getElementById('recipeUoM').disabled = true;

                document.getElementById('updateRecipeBtn').style.display = 'none';
                document.getElementById('cancelRecipeBtn').style.display = 'none';
                document.getElementById('editRecipeBtn').style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error.message);
                alert('Failed to update recipe: ' + error.message);
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('productSelect');
        const rpoutputInput = document.getElementById('recipeOutput');
        const rpuomInput = document.getElementById('recipeUoM');
        const saveRecipeBtn = document.getElementById('saveRecipeBtn');
        const editRecipeBtn = document.getElementById('editRecipeBtn');
        const updateRecipeBtn = document.getElementById('updateRecipeBtn');
        const cancelRecipeBtn = document.getElementById('cancelRecipeBtn');

        function toggleSaveButton() {
            const isValid = recipevalidation();
            saveRecipeBtn.style.display = recipeId ? 'none' : (isValid ? 'block' : 'none');
            saveRecipeBtn.disabled = !isValid;
            updateRecipeBtn.disabled = !isValid;
        }

        productSelect.addEventListener('change', toggleSaveButton);
        rpoutputInput.addEventListener('input', toggleSaveButton);
        rpuomInput.addEventListener('change', toggleSaveButton);

        saveRecipeBtn.addEventListener('click', function() {
            if (recipevalidation()) {
                recipePricing();
            } else {
                alert('Please fill all required fields correctly before saving.');
            }
        });

        editRecipeBtn.addEventListener('click', function() {
            productSelect.disabled = false;
            rpoutputInput.disabled = false;
            rpuomInput.disabled = false;
            editRecipeBtn.style.display = 'none';
            updateRecipeBtn.style.display = 'block';
            cancelRecipeBtn.style.display = 'block';
            toggleSaveButton();
        });

        updateRecipeBtn.addEventListener('click', function() {
            if (recipevalidation()) {
                updateRecipe();
            } else {
                alert('Please fill all required fields correctly before updating.');
            }
        });

        cancelRecipeBtn.addEventListener('click', function() {
            productSelect.value = savedProductId || 'Choose...';
            rpoutputInput.value = savedOutput || '';
            rpuomInput.value = savedUom || 'UoM';

            if (typeof $(productSelect).select2 === 'function') {
                $(productSelect).trigger('change');
            }

            productSelect.disabled = true;
            rpoutputInput.disabled = true;
            rpuomInput.disabled = true;

            updateRecipeBtn.style.display = 'none';
            cancelRecipeBtn.style.display = 'none';
            editRecipeBtn.style.display = 'block';
            toggleSaveButton();
        });

        toggleSaveButton();
    });
</script>



<!-- Template Main JS File -->
<script src="{{ asset('js/main.js') }}"></script>