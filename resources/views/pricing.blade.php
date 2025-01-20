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
                    <select id="productSelect" class="form-select" aria-labelledby="productSelect">
                        <option selected disabled>Choose...</option>
                        @foreach($products as $productItem)
                        <option value="{{ $productItem->id }}">{{ $productItem->name }}</option>
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
                    <label for="packingmaterial" class="form-label" id="packingmaterial">Packing Material</label>
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
                    <input type="checkbox" class="form-check-input" id="frommasters"> <label class="form-check-label" for="frommasters"> From Masters </label>

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
                    <label for="overheads" class="form-label" id="overheads">Overheads</label>
                    <select id="overheads" class="form-select">
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
            <div class="row mb-2">
                <div class="col-md-2 mt-2">
                    <label for="totalcost" class="form-label">Total Cost (A+B+C):
                </div>
                <div class="col-md-3">
                    </label> <input type="text" class="form-control" id="totalcost" disabled>
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
        let isaddRp = false;

        const rpoutputInput = document.getElementById('recipeOutput');
        const rpuomInput = document.getElementById('recipeUoM');


        productSelect.addEventListener('change', function() {
            product_id = this.value; // Update product_id with the selected value
            console.log('Selected product ID:', product_id); // Debug log to check the selected value
        });

        // Update fields when raw material is selected
        rawMaterialSelect.addEventListener('change', function() {
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

        // Update amount on quantity input
        quantityInput.addEventListener('input', updateAmount);

        // Add raw material row to the table
        addButton.addEventListener('click', function() {

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
            console.log("rp",rpoutput,rpuom);
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

                // if(isaddRp == false)
                // {
                //     recipePricing();
                // }
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
                clearPmFields();
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
                    clearOhFields();

                })
                .catch(error => console.error('Error:', error.message));

        });

        overheadsTable.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-icon')) {
                const deleteIcon = e.target;
                const row = deleteIcon.closest('tr');
                const insertedId = deleteIcon.getAttribute('data-id');

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
                        console.log('Success:', data);

                        // Remove the row from the table
                        const amount = parseFloat(row.cells[5].textContent) || 0;
                        row.remove();

                        // Update the total cost
                        updateOhTotalCost(-amount);
                    })
                    .catch(error => console.error('Error:', error.message));
            }
        });

        // Helper functions

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
        }

        function updateGrandTotal() {
            const rawMaterialTotal = parseFloat(totalCostSpan.textContent) || 0;
            const packingMaterialTotal = parseFloat(totalPmCostSpan.textContent) || 0;
            const overheadsTotal = parseFloat(totalOhCostSpan.textContent) || 0;
            const grandTotal = rawMaterialTotal + packingMaterialTotal + overheadsTotal; // Add other totals if needed
            totalCostInput.value = grandTotal.toFixed(2); // Display in Total Cost (A+B+C)
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
