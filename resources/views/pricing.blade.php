@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Add Pricing</h1>
        <div class="row">
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
            {{-- <div class="container-fluid mt-4"> --}}
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
                            </tr>
                        </thead>
                        <tbody id="rawMaterialTable"></tbody>
                    </table>
                    <div class="text-end" style="background-color: #eaf8ff; width:90%;">
                        <strong>RM Cost (A) : </strong> <span id="totalRmCost">0.00</span>
                    </div>
                </div>
            </div>
            {{-- </div> --}}
            {{-- Packing materials --}}
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
                    {{-- <a href="#" class='text-decoration-none pm-ps-add-btn text-white py-4 px-4'> --}}
                    <button type="button" class="btn btn-primary pmaddbtn" id="pmaddbtn"><i class="fas fa-plus"></i> Add</button>
                    {{-- </a> --}}
                </div>
            </div>
            {{-- <div class="container-fluid mt-4"> --}}
            <div class="row mb-4">
                <div class="col-12 col-md-12 mx-auto table-responsive"> <!-- Use col-md-11 for slightly left alignment -->
                    <table class="table table-bordered text-center" style="background-color: #F1F1F1; width:90%;">
                        <thead class="no border">
                            <tr>
                                <th>Packing Material</th>
                                <th>Quantity</th>
                                <th>PM Code</th>
                                <th>UoM</th>
                                <th>Price</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody id="packingMaterialTable">
                        </tbody>
                    </table>
                    <div class="text-end" style="background-color:#F1F1F1; width:90%;">
                        <strong>PM Cost (B) : </strong> <span id="totalPmCost">0.00</span>
                    </div>
                    <!-- <div class="text-end col-10" style="background-color:#F1F1F1; width:84%; ">
                        <strong>PM Cost(B): </strong> <span id="totalCost2"> 2000 </span>
                    </div> -->
                </div>
            </div>

            {{-- Overheads --}}
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
                    {{-- <a href="#" class='text-decoration-none oh-ps-add-btn text-white py-4 px-4'> --}}
                    <button type="button" class="btn btn-primary ohaddbtn" id="ohaddbtn"><i class="fas fa-plus"></i> Add</button>
                    {{-- </a> --}}
                </div>
            </div>
            <div id="manualEntry" style="display: none;">
                <div class="row mb-4">
                    <div class="d-flex flex-column" style="flex: 1.5;">
                        <label for="manualOverheads" class="form-label">Overheads Name</label>
                        <input type="text" class="form-control rounded" id="manualOverheads" name="manualOverheads">
                    </div>

                    <!-- Dropdown for selecting Overheads Type -->
                    <div class="d-flex flex-column" style="flex: 1.5;">
                        <label for="manualOhType" class="form-label">Type</label>
                        <select id="manualOhType" class="form-select">
                            <option value="price" selected>Overheads Price</option>
                            <option value="percentage">Overheads Percentage</option>
                        </select>
                    </div>

                    <!-- Input Fields (Only One Will Be Visible at a Time) -->
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
            {{-- <div class="container-fluid mt-4"> --}}
            <div class="row mb-4">
                <div class="col-12 col-md-12 mx-auto table-responsive"> <!-- Use col-md-11 for slightly left alignment -->
                    <table class="table table-bordered text-center" style=" background-color: #D7E1E4; width:90%;">
                        <thead class="no border">
                            <tr>
                                <th>Overheads</th>
                                <th>Quantity</th>
                                <th>OH Code</th>
                                <th>UoM</th>
                                <th>Price</th>
                                <th>Amount</th>
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
                <div class=" mb-2">
                    <div class="mt-2">
                        <label for="totalcost" class="form-label">Total Cost (A+B+C):
                    </div>
                    <div>
                        </label> <input type="text" class="form-control" id="totalcost" disabled>
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

{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
 --}}
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
        const totalCostInput = document.getElementById('totalcost'); // Total Cost (A+B+C)
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
        // let isaddRp = false;

        const rpoutputInput = document.getElementById('recipeOutput');
        const rpuomInput = document.getElementById('recipeUoM');

        const fromMastersCheckbox = document.getElementById("frommasters");
        const enterManuallyCheckbox = document.getElementById("entermanually");
        const masterEntryDiv = document.getElementById("overheads").closest(".row.mb-4");
        const manualEntryDiv = document.getElementById("manualEntry");
        let manualOhPriceValue = 0;
        let manualOhPercValue = 0;

        let product_id = null;
        // let rmTotal = 0; // Initialize globally
        // let pTotal = 0;

        function recipevalidation() {
        const rpvalue = document.getElementById('productSelect').value.trim();
        const rpopvalue = document.getElementById('recipeOutput').value.trim();
        const rpuomvalue = document.getElementById('recipeUoM').value.trim();

        if (rpvalue === "" && rpvalue === "Choose...") {
            alert("Please fill in the Recipe Name.");
            document.getElementById('productSelect').focus();
            return;
        }
        else if(rpopvalue === "")
        {
            alert("Please fill in the Recipe Output.");
            document.getElementById('recipeOutput').focus();
            return;
        }
        else if(rpuomvalue === "")
        {
            alert("Please fill in the Recipe UoM.");
            document.getElementById('recipeUoM').focus();
            return;
        }
    }

        // Function to toggle visibility based on checkbox selection
        function toggleForms() {
            if (fromMastersCheckbox.checked) {
                masterEntryDiv.style.display = "flex";
                manualEntryDiv.style.display = "none";
            } else if (enterManuallyCheckbox.checked) {
                masterEntryDiv.style.display = "none";
                manualEntryDiv.style.display = "block";
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

        // Ensure correct field is shown when the page loads
        toggleFields();

        // Listen for changes in dropdown selection
        manualOhType.addEventListener("change", toggleFields);

        // Add event listeners to checkboxes to toggle the forms
        fromMastersCheckbox.addEventListener("change", function() {
            if (fromMastersCheckbox.checked) {
                enterManuallyCheckbox.checked = false; // Uncheck the "Enter Manually" checkbox
            }
            toggleForms();
        });

        enterManuallyCheckbox.addEventListener("change", function() {
            if (enterManuallyCheckbox.checked) {
                fromMastersCheckbox.checked = false; // Uncheck the "From Masters" checkbox
            }
            toggleForms();
        });

        // Set default to "From Masters" and call toggleForms() to show the correct form
        fromMastersCheckbox.checked = true;
        toggleForms();

        productSelect.addEventListener('change', function() {
           product_id = this.value; // Update product_id with the selected value
            console.log('Selected product ID:', product_id); // Debug log to check the selected value
        });

        // Update fields when raw material is selected
        rawMaterialSelect.addEventListener('change', function() {
            recipevalidation();
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
        rawMaterialSelect.addEventListener('input', (event) => {
            const selectedOption = event.target.options[event.target.selectedIndex];  //this.options[this.selectedIndex];
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

        // Update amount on quantity input
        quantityInput.addEventListener('input', updateAmount);

        // Add raw material row to the table
        addButton.addEventListener('click', function() {
            console.log('rm p',product_id);

            if (!product_id) {
                alert('Please select a valid product.');
                return;
            }
            const rawMaterialId = rawMaterialSelect.value;
            const rawMaterialName = rawMaterialSelect.options[rawMaterialSelect.selectedIndex]?.text;
            const quantity = parseFloat(quantityInput.value) || 0;
            const code = codeInput.value;
            const uom = uomInput.value;
            const price = parseFloat(priceInput.value) || 0;
            const amount = parseFloat(amountInput.value) || 0;

            const rpoutput = rpoutputInput.value.trim(); // Convert to number
            const rpuom = rpuomInput.value;
            console.log("rp", rpoutput, rpuom, rawMaterialName, rawMaterialId);
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
            // console.log(product_id);

            fetch('/rm-for-recipe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({
                        product_id: product_id,
                        raw_material_id: rawMaterialId,
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
                    if (!response.ok) {
                        throw new Error('Server response not OK');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                    const rmInsertedId = data.rmInserted_id;
                    // Add row to the table
                    const row = `<tr>
                    <td>${rawMaterialName}</td>
                    <td>${quantity}</td>
                    <td>${code}</td>
                    <td>${uom}</td>
                    <td>${price.toFixed(2)}</td>
                    <td>${amount.toFixed(2)}</td>
                    <td>
                    <span class="delete-icon" style="cursor: pointer; color: red;" title="Remove Row" data-id="${rmInsertedId}">&#x1F5D1;</span>
                    </td>
                    </tr>`;
                    tableBody.insertAdjacentHTML('beforeend', row);
                    updateTotalCost(amount); // Update the total cost after adding a row
                    clearFields();

                })
                .catch(error => console.error('Error:', error.message));
        });

        // Delete row functionality
        tableBody.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-icon')) {
                const deleteIcon = e.target;
                const row = deleteIcon.closest('tr');
                const rmInsertedId = deleteIcon.getAttribute('data-id');

                // Confirm deletion
                if (!confirm('Are you sure you want to delete this record?')) {
                    return;
                }

                // CSRF token
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!token) {
                    console.error('CSRF token not found.');
                    return;
                }

                // Send DELETE request to the server
                fetch(`/rm-for-recipe/${rmInsertedId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Server response not OK');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Success:', data);

                        // Remove the row from the table
                        const amount = parseFloat(row.cells[5].textContent) || 0;
                        row.remove();

                        // Update the total cost
                        updateTotalCost(-amount);
                    })
                    .catch(error => console.error('Error:', error.message));
            }
        });

        // Helper functions
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
            // Clear select dropdown
            rawMaterialSelect.value = rawMaterialSelect.options[0].value; // Set to the first option (usually the placeholder)

            // Clear input fields
            quantityInput.value = '';
            codeInput.value = '';
            uomInput.value = '';
            priceInput.value = '';
            amountInput.value = '';

        }

        packingMaterialSelect.addEventListener('change', function() {
            recipevalidation();
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

            fetch('/pm-for-recipe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({
                        product_id: product_id,
                        packing_material_id: packingMaterialId,
                        quantity: quantity,
                        amount: amount,
                        code: code,
                        uom: uom,
                        price: price,
                    }),
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Server response not OK');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                    const pmInsertedId = data.pmInserted_id;
                    // Add row to the table
                    const row = `<tr>
                    <td>${packingMaterialName}</td>
                    <td>${quantity}</td>
                    <td>${code}</td>
                    <td>${uom}</td>
                    <td>${price.toFixed(2)}</td>
                    <td>${amount.toFixed(2)}</td>
                    <td>
                    <span class="delete-icon" style="cursor: pointer; color: red;" title="Remove Row" data-id="${pmInsertedId}">&#x1F5D1;</span>
                    </td>
                    </tr>`;
                    packingMaterialTable.insertAdjacentHTML('beforeend', row);
                    updatePmTotalCost(amount); // Update the total cost after adding a row
                    clearPmFields();

                })
                .catch(error => console.error('Error:', error.message));

        });

        packingMaterialTable.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-icon')) {
                const deleteIcon = e.target;
                const row = deleteIcon.closest('tr');
                const pmInsertedId = deleteIcon.getAttribute('data-id');

                // Confirm deletion
                if (!confirm('Are you sure you want to delete this record?')) {
                    return;
                }

                // CSRF token
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!token) {
                    console.error('CSRF token not found.');
                    return;
                }

                // Send DELETE request to the server
                fetch(`/pm-for-recipe/${pmInsertedId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Server response not OK');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Success:', data);

                        // Remove the row from the table
                        const amount = parseFloat(row.cells[5].textContent) || 0;
                        row.remove();

                        // Update the total cost
                        updatePmTotalCost(-amount);
                    })
                    .catch(error => console.error('Error:', error.message));
            }
        });

        // Helper functions

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

        //overheads
        overheadsSelect.addEventListener('change', function() {
            recipevalidation();
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.disabled) {
                clearPmFields();
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

            const fromMastersCheckbox = document.getElementById("frommasters");
            const fromMastersLabel = document.querySelector("label[for='frommasters']");
            const manualCheckbox = document.getElementById("entermanually");
            const manualLabel = document.querySelector("label[for='entermanually']");

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
                alert('This overheads has already been added to the table.');
                clearOhFields();
                return;
            }

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!token) {
                console.error('CSRF token not found.');
                return;
            }

            if (!overheadsId || quantity <= 0 || amount <= 0) {
                alert('Please select a valid overheads and fill all fields correctly.');
                return;
            }

            fetch('/oh-for-recipe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({
                        product_id: product_id,
                        overheads_id: overheadsId,
                        quantity: quantity,
                        amount: amount,
                        code: code,
                        uom: uom,
                        price: price,
                    }),
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Server response not OK');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                    const insertedId = data.inserted_id;
                    // Add row to the table
                    const row = `<tr>
                    <td>${overheadsName}</td>
                    <td>${quantity}</td>
                    <td>${code}</td>
                    <td>${uom}</td>
                    <td>${price.toFixed(2)}</td>
                    <td>${amount.toFixed(2)}</td>
                    <td>
                    <span class="delete-icon" style="cursor: pointer; color: red;" title="Remove Row" data-id="${insertedId}">&#x1F5D1;</span>
                    </td>
                    </tr>`;
                    overheadsTable.insertAdjacentHTML('beforeend', row);
                    updateOhTotalCost(amount); // Update the total cost after adding a row

                    if (document.getElementById("frommasters").checked) {
                        manualCheckbox.style.display = "none";
                        manualLabel.style.display = "none";
                    } else if (document.getElementById("entermanually").checked) {
                        fromMastersCheckbox.style.display = "none";
                        fromMastersLabel.style.display = "none";
                    }
                    clearOhFields();

                })
                .catch(error => console.error('Error:', error.message));

        });

        const manualOhAddButton = document.getElementById('manualOhaddbtn');

        // if (!manualOhAddButton) {
        //     console.error("Add button not found in the DOM!");
        //     return;
        // }

        function calcForManual()
        {
            let manualOhAmount = 0;
            let manualOhPercent = 0;
            const manualOhType = document.getElementById("manualOhType").value.trim();

            let rmTotal = parseFloat(totalCostSpan.textContent) || 0;
            let pmTotal = parseFloat(totalPmCostSpan.textContent) || 0;
            if ((rmTotal + pmTotal) <= 0) {
                alert("Please add raw materials & packing materials.");
                return;
            }
            if(manualOhType == 'percentage')
            {
                manualOhPercValue = parseFloat(document.getElementById("manualOhPerc").value) || 0;
                console.log(parseFloat(rmTotal));
                    manualOhAmount = ((rmTotal + pmTotal) * manualOhPercValue/100);
                    console.log(manualOhAmount);
                    manualOhPriceValue = manualOhAmount;
            }
            else if(manualOhType == 'price')
            {
                manualOhPriceValue = parseFloat(document.getElementById("manualOhPrice").value) || 0;
                console.log(parseFloat(rmTotal));
                manualOhPercent = (manualOhPriceValue / (rmTotal + pmTotal)) * 100;
                console.log(manualOhPercent);
                manualOhPercValue = manualOhPercent;
            }
        }

        manualOhAddButton.addEventListener('click', function() {
            console.log("Add button clicked"); // Debugging
            recipevalidation();
            // if (productSelect.value.trim() == null) {
            //     alert('Please select a valid product and output');
            //     return;
            // }

            if ((parseFloat(totalCostSpan.textContent) || 0) <= 0) {
                alert("Please add raw materials");
                return;
            }
            const fromMastersCheckbox = document.getElementById("frommasters");
            const fromMastersLabel = document.querySelector("label[for='frommasters']");
            const manualCheckbox = document.getElementById("entermanually");
            const manualLabel = document.querySelector("label[for='entermanually']");
            const overheadsTable = document.getElementById("overheadsTable");

            const manualOverheadsName = document.getElementById("manualOverheads").value.trim();
            const manualOhType = document.getElementById("manualOhType").value;
            // let manualOhPriceValue = 0;
            // let manualOhPercValue = 0;

            if (manualOhType === "price") {
                manualOhPriceValue = parseFloat(document.getElementById("manualOhPrice").value) || 0;
                calcForManual();
                // manualOhPercValue = manualOhPercent;
            } else {
                manualOhPercValue = parseFloat(document.getElementById("manualOhPerc").value) || 0;
                calcForManual();
                // manualOhPriceValue = manualOhAmount;
            }
            updateMohTotalCost(manualOhPriceValue);
            if (!manualOverheadsName || (manualOhType === "price" && manualOhPriceValue <= 0) || (manualOhType === "percentage" && manualOhPercValue <= 0)) {
                alert("Please fill all fields before adding.");
                return;
            }

            const data = {
                product_id: product_id, // Ensure product_id is set
                manualOverheads: manualOverheadsName,
                manualOverheadsType: manualOhType,
                manualOhPrice: manualOhPriceValue,
                manualOhPerc: manualOhPercValue,
            };
            console.log(data);
            const token = document.querySelector("meta[name='csrf-token']")?.getAttribute("content");
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
                        // alert("Manual overhead added successfully!");
                        console.log("Manual overhead added successfully!");
                        const insertedId = data.inserted_id; // Get the inserted ID from the response

                        // Add a new row to the table
                        const row = `<tr>
                    <td>${manualOverheadsName}</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>${manualOhPriceValue.toFixed(2)}</td>
                    <td>${manualOhPriceValue.toFixed(2)}</td>
                    <td>
                        <span class="delete-icon" style="cursor: pointer; color: red;" title="Remove Row" data-id="${insertedId}">&#x1F5D1;</span>
                    </td>
                </tr>`;

                        overheadsTable.insertAdjacentHTML("beforeend", row);

                        // Update total cost after adding a row
                        if (typeof updateOhTotalAmount === "function") {
                            updateOhTotalAmount(); // Ensure this function exists
                        }

                        // Debugging: Check which checkbox is selected
                        console.log("From Masters Checked:", fromMastersCheckbox.checked);
                        console.log("Enter Manually Checked:", manualCheckbox.checked);

                        // Hide the opposite checkbox & label
                        if (manualCheckbox.checked) {
                            fromMastersCheckbox.style.display = "none";
                            fromMastersLabel.style.display = "none";
                            console.log("Hiding From Masters checkbox");
                        }

                        if (fromMastersCheckbox.checked) {
                            manualCheckbox.style.display = "none";
                            manualLabel.style.display = "none";
                            console.log("Hiding Enter Manually checkbox");
                        }

                        // Clear the input fields after adding
                        if (typeof clearOhFields === "function") {
                            clearOhFields(); // Ensure this function exists
                        }
                    } else {
                        alert("Failed to save manual overhead.");
                    }
                })
                .catch((error) => console.error("Fetch error:", error));
        });

        if(overheadsTable)
        {
        overheadsTable.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-icon')) {
                const deleteIcon = e.target;
                const row = deleteIcon.closest('tr'); // Get the clicked row
                const insertedId = deleteIcon.getAttribute('data-id');

                if (!row) {
                    console.error("Row not found.");
                    return;
                }

                // Confirm deletion
                if (!confirm('Are you sure you want to delete this record?')) {
                    return;
                }

                // Get the CSRF token
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!token) {
                    console.error('CSRF token not found.');
                    return;
                }

                // ✅ Get the code value from the correct row (assuming the code is in the second column)
                const code = row.cells[1]?.textContent.trim(); // Adjust the index if needed

                console.log("Code value from row:", code); // Debugging

                if (code === '-') {
                    console.log("Calling mohDelete() because code is empty");
                    mohDelete(insertedId, row, token);
                } else {
                    console.log(`Calling ohDelete() because code has a value: ${code}`);
                    ohDelete(insertedId, row, token);
                }
            }
        });
        }

        // Function to handle deletion when the code is empty
        function ohDelete(insertedId, row, token) {
            fetch(`/oh-for-recipe/${insertedId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Server response not OK');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Deleted with empty code:', data);
                    if (!row) {
                        console.error("Row not found.");
                        return;
                    }

                    // Remove the row from the table
                    const amount = parseFloat(row.cells[5]?.textContent) || 0;
                    row.remove();

                    // Update the total cost
                    updateOhTotalCost(-amount);
                })
                .catch(error => console.error('Error:', error.message));
        }

        // Function to handle deletion when the code is provided
        function mohDelete(insertedId, row, token) {
            if (!row) {
                console.error("Row not found.");
                return;
            }

            fetch(`/moh-for-recipe/${insertedId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Server response not OK');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);

                    // Remove the row from the table
                    const amount = parseFloat(row.cells[5].textContent) || 0;
                    row.remove();
                    // Update the total cost
                    updateOhTotalCost(-amount);
                })
                .catch(error => console.error('Error:', error.message));
        }

        // Helper functions

        function updateOhAmount() {
            const price = parseFloat(ohPriceInput.value) || 0;
            const quantity = parseFloat(ohQuantityInput.value) || 0;
            ohAmountInput.value = (price * quantity).toFixed(2);
        }

        // function updateOhTotalAmount() {
        //     const overheadsTable = document.getElementById('overheadsTable');
        //     let totalAmount = 0;

        //     // Loop through all rows of the table and sum the "Amount" column (index 5)
        //     const rows = overheadsTable.querySelectorAll('tr');
        //     rows.forEach(row => {
        //         const amountCell = row.cells[5]; // 6th column (index 5) for Amount
        //         if (amountCell) {
        //             const amount = parseFloat(amountCell.textContent) || 0;
        //             totalAmount += amount;
        //         }
        //     });

        //     // Update the total amount display
        //     const totalOhCostSpan = document.getElementById('totalohCost');
        //     totalOhCostSpan.textContent = totalAmount.toFixed(2);
        // }
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
        }

        function updateGrandTotal() {
            const rawMaterialTotal = parseFloat(totalCostSpan.textContent) || 0;
            const packingMaterialTotal = parseFloat(totalPmCostSpan.textContent) || 0;
            const overheadsTotal = parseFloat(totalOhCostSpan.textContent) || 0;
            const grandTotal = rawMaterialTotal + packingMaterialTotal + overheadsTotal; // Add other totals if needed
            totalCostInput.value = grandTotal.toFixed(2); // Display in Total Cost (A+B+C)
            updateUnitTotal();
        }

        function updateUnitTotal() {
            const rawMaterialTotal = parseFloat(totalCostSpan.textContent) || 0;
            const packingMaterialTotal = parseFloat(totalPmCostSpan.textContent) || 0;
            const overheadsTotal = parseFloat(totalOhCostSpan.textContent) || 0;
            const grandTotal = rawMaterialTotal + packingMaterialTotal + overheadsTotal; // Sum of all costs

            const recipeOutput = parseFloat(rpoutputInput.value) || 1; // Prevent division by zero

            console.log("Raw Material:", rawMaterialTotal);
            console.log("Packing Material:", packingMaterialTotal);
            console.log("Overheads:", overheadsTotal);
            console.log("Grand Total:", grandTotal);
            console.log("Recipe Output:", recipeOutput);

            const unitCost = grandTotal / recipeOutput;

            console.log("unit cost:", unitCost);
            unitCostInput.value = unitCost.toFixed(2); // Display in Unit Cost field
        }

        // Initial Calculation
        updateUnitTotal();

        // Update when input changes
        rpoutputInput.addEventListener('input', updateUnitTotal);

        // function updatemohAmount() {
        //     const price = parseFloat(ohPriceInput.value) || 0;
        //     // const quantity = parseFloat(ohQuantityInput.value) || 0;
        //     ohAmountInput.value = (price * quantity).toFixed(2);
        // }
        function updateMohTotalCost(newAmount) {
            const currentTotal = parseFloat(totalOhCostSpan.textContent) || 0;
            totalOhCostSpan.textContent = (currentTotal + newAmount).toFixed(2);
            updateGrandTotal();
        }
        function recipePricing() {
            const rpoutputInput = document.getElementById('recipeOutput');
            const rpuomInput = document.getElementById('recipeUoM');

            const rpoutput = rpoutputInput.value.trim(); // Convert to number
            const rpuom = rpuomInput.value;
            // Validate inputs
            if (rpoutput <= 0 || rpoutput <= 0) {
                console.log(rpoutput, rpuom);
                alert('Invalid input values. Please check your data.');
                return;
            }

            // Retrieve CSRF token
            const tokenElement = document.querySelector('meta[name="csrf-token"]');
            if (!tokenElement) {
                console.error('CSRF token not found.');
                alert('A CSRF token is required for this action.');
                return;
            }
            const csrfToken = tokenElement.getAttribute('content');

            // Product ID
            // const productIdInput = document.getElementById('productId'); // Adjust as needed
            // const product_id = productIdInput ? parseInt(productIdInput.value) : null;
            if (!product_id) {
                alert('Product ID is missing or invalid.');
                return;
            }

            // Send the data to the server using fetch API
            fetch('/recipepricing', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken, // Include CSRF token for security
                    },
                    body: JSON.stringify({
                        product_id: product_id,
                        rpoutput: rpoutput,
                        rpuom: rpuom,
                    }),
                })
                .then(response => {
                    console.log('Rp Response:', response);
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                })
                .then(data => {
                    console.log('Success:', data);
                    alert('Recipe-pricing added successfully');

                    // Clear input fields
                    // rpoutputInput.value = '';
                    // rpuomInput.value = '';
                })
                .catch(error => console.error('Error:', error.message));
        }

    });
</script>

<!-- Template Main JS File -->
<script src="{{ asset('js/main.js') }}"></script>
