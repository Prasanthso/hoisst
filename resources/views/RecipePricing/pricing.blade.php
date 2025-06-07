@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Add Recipe Costing</h1>
        <div class="row">
            <div class="mb-4">
                <button type="button" class="btn btn-success" id="importRecipeBtn"><i class="fas fa-upload"></i> Import CSV</button>
                <input type="file" id="importRecipeFile" accept=".csv" style="display: none;">
                <a href="{{ asset('templates/recipe_costing_template.csv') }}" download>Download Template</a>
            </div>
            <!-- Action Buttons -->
            <!--
            <div class="d-flex justify-content-end mb-2 action-buttons">
                <button class="btn btn-sm edit-table-btn me-2" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                    <i class="fas fa-edit" style="color: black;"></i>
                </button>
                <button class="btn btn-sm delete-table-btn" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                    <i class="fas fa-trash" style="color: red;"></i>
                </button>
            </div>-->
        </div>
    </div><!-- End Page Title -->
    <section class="section dashboard">
        <div class="container mt-5">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="mb-4">
                <label for="productSelect" class="form-label">Select Product</label>
                <div class="col-6">
                    <select id="productSelect" class="form-select select2" aria-labelledby="productSelect">
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
                    <select id="recipeUoM" class="form-select select2" name="recipeUoM">
                        <option selected>UoM</option>
                        <option value="Ltr">Ltr</option>
                        <option value="Kgs">Kgs</option>
                        <option value="Nos">Nos</option>
                    </select>
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
                    <select id="rawmaterial" class="form-select">
                        <option selected disabled>Choose...</option>
                        @foreach($rawMaterials as $rawMaterialItem)
                        <option
                            value="{{ $rawMaterialItem->id }}"
                            data-code="{{ $rawMaterialItem->rmcode }}"
                            data-uom="{{ $rawMaterialItem->uom }}"
                            data-price="{{ $rawMaterialItem->price }}">
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
                    <input type="text" class="form-control rounded" id="rmCode" name="rmCode" readonly>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="rmUoM" class="form-label">UoM</label>
                    <input type="text" class="form-control" id="rmUoM" name="rmUoM" readonly>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="rmPrice" class="form-label">Price</label>
                    <input type="text" class="form-control rounded" id="rmPrice" name="rmPrice" readonly>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="rmAmount" class="form-label">Amount</label>
                    <input type="text" class="form-control" id="rmAmount" name="rmAmount">
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
                    <select id="packingmaterial" class="form-select">
                        <option selected disabled>Choose...</option>
                        @foreach($packingMaterials as $packingMaterialItem)
                        <option
                            value="{{ $packingMaterialItem->id }}"
                            data-code="{{ $packingMaterialItem->pmcode }}"
                            data-uom="{{ $packingMaterialItem->uom }}"
                            data-price="{{ $packingMaterialItem->price }}">
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
                    <input type="text" class="form-control" id="pmAmount" name="pmAmount">
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
                    <select id="overheads" class="form-select select2">
                        <option selected disabled>Choose...</option>
                        @foreach($overheads as $overheadsItem)
                        <option
                            value="{{ $overheadsItem->id }}"
                            data-code="{{ $overheadsItem->ohcode }}"
                            data-uom="{{ $overheadsItem->uom }}"
                            data-price="{{ $overheadsItem->price }}">
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
                    <input type="text" class="form-control" id="ohAmount" name="ohAmount">
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
<script>
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

        function recipevalidation() {
            const rpvalue = document.getElementById('productSelect').value.trim();
            const rpopvalue = document.getElementById('recipeOutput').value.trim();
            const rpuomvalue = document.getElementById('recipeUoM').value.trim();

            if (rpvalue === "" || rpvalue === "Choose...") {
                alert("Please fill in the Recipe Name.");
                document.getElementById('productSelect').focus();
                return false;
            } else if (rpopvalue === "") {
                alert("Please fill in the Recipe Output.");
                document.getElementById('recipeOutput').focus();
                return false;
            } else if (rpuomvalue === "") {
                alert("Please fill in the Recipe UoM.");
                document.getElementById('recipeUoM').focus();
                return false;
            }
            return true;
        }

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

        productSelect.addEventListener('change', function() {
            product_id = this.value;
            console.log('Selected product ID:', product_id);
        });

        rawMaterialSelect.addEventListener('change', function() {
            if (!recipevalidation()) return;
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

        quantityInput.addEventListener('input', updateAmount);

        addButton.addEventListener('click', function() {
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
                    })
                    .catch(error => console.error('Error:', error.message));
            }
        });
        

        // Import Recipe CSV
        importRecipeBtn.addEventListener('click', function() {
            importRecipeFile.click();
        });

        importRecipeFile.addEventListener('change', function(e) {
            if (!product_id) {
                alert('Please select a valid product.');
                return;
            }
            if (!recipevalidation()) return;

            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const text = e.target.result;
                const rows = text.split('\n').map(row => row.trim()).filter(row => row);

                // Find the index of the header row (starts with "category,name,quantity")
                let headerIndex = rows.findIndex(row => row.startsWith('category,name,quantity'));
                if (headerIndex === -1) {
                    alert('Invalid CSV format: Header row not found.');
                    return;
                }

                // Skip rows before the header and process data rows after the header
                const dataRows = rows.slice(headerIndex + 1).filter(row => row && !row.startsWith('#'));
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!token) {
                    console.error('CSRF token not found.');
                    return;
                }

                const rpoutput = rpoutputInput.value.trim();
                const rpuom = rpuomInput.value;

                dataRows.forEach(row => {
                    const [category, name, quantity, code, uom, price, amount, type, value] = row.split(',').map(item => item.trim());
                    if (!category || !name) {
                        alert(`Invalid data in CSV row: ${row}`);
                        return;
                    }

                    if (category === 'raw_material') {
                        if (!quantity || !code || !uom || !price || !amount) {
                            alert(`Invalid raw material data in CSV row: ${row}`);
                            return;
                        }
                        const rows = Array.from(tableBody.querySelectorAll('tr'));
                        if (rows.some(row => row.cells[0].textContent === name)) {
                            alert(`Raw material ${name} is already added.`);
                            return;
                        }
                        const rawMaterialOption = Array.from(rawMaterialSelect.options).find(option => option.text === name);
                        if (!rawMaterialOption) {
                            alert(`Raw material ${name} not found in the database.`);
                            return;
                        }
                        const rawMaterialId = rawMaterialOption.value;
                        addRawMaterial(rawMaterialId, name, parseFloat(quantity), code, uom, parseFloat(price), parseFloat(amount), rpoutput, rpuom, token);
                    } else if (category === 'packing_material') {
                        if (!quantity || !code || !uom || !price || !amount) {
                            alert(`Invalid packing material data in CSV row: ${row}`);
                            return;
                        }
                        const rows = Array.from(packingMaterialTable.querySelectorAll('tr'));
                        if (rows.some(row => row.cells[0].textContent === name)) {
                            alert(`Packing material ${name} is already added.`);
                            return;
                        }
                        const packingMaterialOption = Array.from(packingMaterialSelect.options).find(option => option.text === name);
                        if (!packingMaterialOption) {
                            alert(`Packing material ${name} not found in the database.`);
                            return;
                        }
                        const packingMaterialId = packingMaterialOption.value;
                        addPackingMaterial(packingMaterialId, name, parseFloat(quantity), code, uom, parseFloat(price), parseFloat(amount), token);
                    } else if (category === 'overhead') {
                        if (!quantity || !code || !uom || !price || !amount) {
                            alert(`Invalid overhead data in CSV row: ${row}`);
                            return;
                        }
                        const rows = Array.from(overheadsTable.querySelectorAll('tr'));
                        if (rows.some(row => row.cells[0].textContent === name)) {
                            alert(`Overhead ${name} is already added.`);
                            return;
                        }
                        const overheadOption = Array.from(overheadsSelect.options).find(option => option.text === name);
                        if (!overheadOption) {
                            alert(`Overhead ${name} not found in the database.`);
                            return;
                        }
                        const overheadId = overheadOption.value;
                        addOverhead(overheadId, name, parseFloat(quantity), code, uom, parseFloat(price), parseFloat(amount), token);
                    } else if (category === 'manual_overhead') {
                        if (!type || !value || !['price', 'percentage'].includes(type)) {
                            alert(`Invalid manual overhead data in CSV row: ${row}`);
                            return;
                        }
                        const rows = Array.from(overheadsTable.querySelectorAll('tr'));
                        if (rows.some(row => row.cells[0].textContent === name)) {
                            alert(`Overhead ${name} is already added.`);
                            return;
                        }
                        const rmTotal = parseFloat(totalCostSpan.textContent) || 0;
                        const pmTotal = parseFloat(totalPmCostSpan.textContent) || 0;
                        if ((rmTotal + pmTotal) <= 0) {
                            alert("Please add raw materials & packing materials.");
                            return;
                        }
                        let manualOhPriceValue = 0;
                        let manualOhPercValue = 0;
                        if (type === 'percentage') {
                            manualOhPercValue = parseFloat(value) || 0;
                            manualOhPriceValue = ((rmTotal + pmTotal) * manualOhPercValue / 100);
                        } else {
                            manualOhPriceValue = parseFloat(value) || 0;
                            manualOhPercValue = (manualOhPriceValue / (rmTotal + pmTotal)) * 100;
                        }
                        const data = {
                            product_id: product_id,
                            manualOverheads: name,
                            manualOverheadsType: type,
                            manualOhPrice: manualOhPriceValue,
                            manualOhPerc: manualOhPercValue,
                        };
                        fetch("/manual-overhead", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": token,
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    const insertedId = data.inserted_id;
                                    const row = `<tr>
                            <td>${name}</td>
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
                                } else {
                                    alert(`Failed to save manual overhead: ${name}`);
                                }
                            })
                            .catch(error => console.error('Error:', error.message));
                    } else {
                        alert(`Unknown category in CSV row: ${category}`);
                    }
                });

                e.target.value = '';
            };
            reader.readAsText(file);
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
                    })
                    .catch(error => console.error('Error:', error.message));
            }
        });

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
            packingMaterialSelect.value = '';
            pmQuantityInput.value = '';
            pmCodeInput.value = '';
            pmUoMInput.value = '';
            pmPriceInput.value = '';
            pmAmountInput.value = '';
        }

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
                        <td>
                        <span class="delete-icon" style="cursor: pointer; color: red;" title="Remove Row" data-id="${insertedId}">🗑</span>
                    </td>
                </tr>`;
                    overheadsTable.insertAdjacentHTML('beforeend', row);
                    updateOhTotalCost(amount);
                    clearOhFields();
                })
                .catch(error => console.error('Error:', error.message));
        }

        const manualOhAddButton = document.getElementById('manualOhaddbtn');

        function calcForManual() {
            let manualOhAmount = 0;
            let manualOhPercent = 0;
            const manualOhType = document.getElementById("manualOhType").value.trim();

            let rmTotal = parseFloat(totalCostSpan.textContent) || 0;
            let pmTotal = parseFloat(totalPmCostSpan.textContent) || 0;
            if ((rmTotal + pmTotal) <= 0) {
                alert("Please add raw materials & packing materials.");
                return;
            }
            if (manualOhType == 'percentage') {
                manualOhPercValue = parseFloat(document.getElementById("manualOhPerc").value) || 0;
                manualOhAmount = ((rmTotal + pmTotal) * manualOhPercValue / 100);
                manualOhPriceValue = manualOhAmount;
            } else if (manualOhType == 'price') {
                manualOhPriceValue = parseFloat(document.getElementById("manualOhPrice").value).get || 0;
                manualOhPercent = (manualOhPriceValue / (rmTotal + pmTotal)) * 100;
                manualOhPercValue = manualOhPercent;
            }
        }

        manualOhAddButton.addEventListener('click', function() {
            console.log("Add button clicked");
            if (!recipevalidation()) return;

            if ((parseFloat(totalCostSpan.textContent) || 0) <= 0) {
                alert("Please add raw materials");
                return;
            }
            const manualOverheadsName = document.getElementById("manualOverheads").value.trim();
            const manualOhType = document.getElementById("manualOhType").value;

            if (manualOhType === "price") {
                manualOhPriceValue = parseFloat(document.getElementById("manualOhPrice").value) || 0;
                calcForManual();
            } else {
                manualOhPercValue = parseFloat(document.getElementById("manualOhPerc").value) || 0;
                calcForManual();
            }

            if (!manualOverheadsName || (manualOhType === "price" && manualOhPriceValue <= 0) || (manualOhType === "percentage" && manualOhPercValue <= 0)) {
                alert("Please fill all fields before adding.");
                return;
            }

            const data = {
                product_id: product_id,
                manualOverheads: manualOverheadsName,
                manualOverheadsType: manualOhType,
                manualOhPrice: manualOhPriceValue,
                manualOhPerc: manualOhPercValue,
            };
            console.log(data);
            const token = document.querySelector('meta[name="csrf-token"]')?.content?.getAttribute('content');
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
                .then((response) => response.json())
                .then((data) => {
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

                        if (enterManuallyCheckbox.checked) {
                            fromMastersCheckbox.style.display = "none";
                            document.querySelector("label[for='frommasters']").style.display = "none";
                        }
                        if (fromMastersCheckbox.checked) {
                            enterManuallyCheckbox.style.display = "none";
                            document.querySelector("label[for='entermanually']").style.display = "none";
                        }

                        clearOhFields();
                    } else {
                        alert("Failed to save manual overhead.");
                    }
                })
                .catch(error => console.error('Error:', error.message));
        });

        overheadsTable.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-icon')) {
                const deleteIcon = e.target;
                const row = deleteIcon.closest('tr');
                const insertedId = deleteIcon.getAttribute('data-id');

                if (!row) {
                    console.error("Row not found.");
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
                    console.log('Deleted with empty code:', data);
                    const amount = parseFloat(row.cells[5]?.textContent) || 0;
                    row.remove();
                    updateOhTotalCost(-amount);
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
                    console.log('Success:', data);
                    const amount = parseFloat(row.cells[5].textContent) || 0;
                    row.remove();
                    updateOhTotalCost(-amount);
                })
                .catch(error => console.error('Error:', error.message));
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
            overheadsSelect.value = '';
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
            const recipeOutput = parseFloat(rpoutputInput.value) || 1;

            console.log("Raw Material:", rawMaterialTotal);
            console.log("Packing Material:", packingMaterialTotal);
            console.log("Overheads:", overheadsTotal);
            console.log("Grand Total:", grandTotal);
            console.log("Recipe Output:", recipeOutput);

            const unitCost = grandTotal / recipeOutput;

            console.log("unit cost:", unitCost);
            unitCostInput.value = unitCost.toFixed(2);
        }

        rpoutputInput.addEventListener('input', updateUnitTotal);

        function recipePricing() {
            const rpoutput = rpoutputInput.value.trim();
            const rpuom = rpuomInput.value;

            if (rpoutput <= 0 || !rpuom) {
                console.log(rpoutput, rpuom);
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
                    console.log('Rp Response:', response);
                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                    alert('Recipe-pricing added successfully');
                })
                .catch(error => console.error('Error:', error.message));
        }

        updateUnitTotal();
    });
</script>

<!-- Template Main JS File -->
<script src="{{ asset('js/main.js') }}"></script>