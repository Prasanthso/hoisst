@extends('layouts.headerNav')

@section('content')
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Edit Pricing</h1>
        <div class="row">
            <!-- Action Buttons -->
        </div>
    </div>

    <section class="section dashboard">
        <div class="container mt-5">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="mb-4">
                <label for="productSelect" class="form-label">Select Product</label>
                <div class="col-6">
                    <select id="productSelect" class="form-select" name="productSelect" aria-labelledby="productSelect">
                        <option value="" disabled selected>Select a Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" selected>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 col-sm-10 mb-2">
                    <label for="recipeOutput" class="form-label">Output</label>
                    <input type="text" class="form-control rounded" id="recipeOutput" name="recipeOutput" value="{{ $products[0]->rp_output ?? '' }}">
                </div>
                <div class="col-md-2 col-sm-10">
                    <label for="recipeUoM" class="form-label">UoM</label>
                    <select id="recipeUoM" class="form-select" name="recipeUoM">
                        <option value="" disabled selected>UoM</option>
                        @foreach ($products as $rpuom)
                            <option value="{{ $rpuom->rp_uom }}" {{ $rpuom->rp_uom == $products[0]->rp_uom ? 'selected' : '' }}>
                                {{ $rpuom->rp_uom }}
                            </option>
                        @endforeach
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

            @if(isset($pricingData) && $pricingData->isNotEmpty())
                <div class="row mb-4">
                    <!-- Raw Materials Table -->
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
                            <tbody id="rawMaterialTable">
                                @php $rmTotal = 0;
                                $filteredData = collect($pricingData)->unique('rid')->values();
                                 @endphp
                                @foreach($filteredData as $data)
                                    @if($data->rm_name)
                                    @php
                                        $amount = $data->rm_quantity * $data->rm_price;
                                        $rmTotal += $amount;
                                     @endphp
                                        <tr>
                                            <td>{{ $data->rm_name }}</td>
                                            <td class="quantity-cell" id="quantity-cell-{{ $data->rid }}">
                                                <span id="quantity-text-{{ $data->rid }}">{{ $data->rm_quantity }}</span>
                                                <input type="number" class="form-control quantity-input" id="quantity-{{ $data->rid }}" value="{{ $data->rm_quantity }}" style="display: none;" disabled>
                                            </td>
                                            <td>{{ $data->rm_code }}</td>
                                            <td>{{ $data->rm_uom ?? 'N/A' }}</td>
                                            <td>{{ $data->rm_price }}</td>
                                            <td>{{ $amount }}</td>
                                            <td>
                                                <!-- Action Buttons -->
                                                <span
                                                class="icon-action edit-btn"
                                                id="edit-{{ $data->rid }}"
                                                style="cursor: pointer; color: blue;"
                                                title="Edit Row"
                                                onclick="editRow('{{ $data->rm_id }}', '{{ $data->rid }}')">
                                                &#9998;
                                            </span>
                                            <span
                                                class="icon-action save-btn"
                                                id="save-{{ $data->rid }}"
                                                style="cursor: pointer; color: green; display:none;"
                                                title="Save Row"
                                                onclick="saveRow('{{ $data->rm_id }}', '{{ $data->rid }}')">
                                                &#x2714;
                                            </span>
                                            <span class="delete-icon" style="cursor: pointer; color: red;" title="Remove Row" data-id="{{ $data->rid }}">&#x1F5D1;</span>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach

                                @if($pricingData->whereNotNull('rm_name')->isEmpty())
                                    <tr>
                                        <td colspan="6">No records available for Raw Materials</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="text-end" style="background-color: #eaf8ff; width:90%;">
                            <strong>RM Cost (A) : </strong> <span id="totalRmCost">{{ $rmTotal }}</span>
                        </div>
                    </div>
                </div>
            @endif

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
            <div class="row mb-4">
                <!-- Packing Materials Table -->
                <div class="table-responsive">
                    <table class="table table-bordered text-center" style="background-color: #F1F1F1; width:90%;">
                        <thead>
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
                            @php
                            $filteredData = collect($pricingData)->unique('pid')->values();
                             $pmTotal = 0; @endphp
                            @foreach($filteredData  as $data)
                                @if($data->pm_name)
                                @php
                                    $amount = $data->pm_quantity * $data->pm_price;
                                     $pmTotal += $amount;
                                 @endphp
                                    <tr>
                                        <td>{{ $data->pm_name }}</td>
                                        <td class="pmquantity-cell" id="pmquantity-cell-{{ $data->pid }}">
                                            <span id="pmquantity-text-{{ $data->pid }}">{{ $data->pm_quantity }}</span>
                                            <input type="number" class="form-control pmquantity-input" id="pmquantity-{{ $data->pid }}" value="{{ $data->pm_quantity }}" style="display: none;" disabled>
                                        </td>
                                        <td>{{ $data->pm_code }}</td>
                                        <td>{{ $data->pm_uom ?? 'N/A' }}</td>
                                        <td>{{ $data->pm_price }}</td>
                                        <td class="pmamount-cell">{{ $amount }}</td>
                                        <td>
                                            <!-- Action Buttons -->
                                            <span
                                                class="icon-action pm-edit-btn"
                                                id="pmedit-{{ $data->pid }}"
                                                style="cursor: pointer; color: blue;"
                                                title="Edit Row"
                                                onclick="pm_editRow('{{ $data->pm_id }}', '{{ $data->pid }}')">
                                                &#9998;
                                            </span>
                                            <span
                                                class="icon-action pm-save-btn"
                                                id="pmsave-{{ $data->pid }}"
                                                style="cursor: pointer; color: green; display:none;"
                                                title="Save Row"
                                                onclick="pm_saveRow('{{ $data->pm_id }}', '{{ $data->pid }}')">
                                                &#x2714;
                                            </span>
                                            <span class="delete-icon" style="cursor: pointer; color: red;" title="Remove Row" data-id="{{ $data->pid }}">&#x1F5D1;</span>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            @if($pricingData->whereNotNull('pm_name')->isEmpty())
                                <tr>
                                    <td colspan="6">No records available for Packing Materials</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="text-end" style="background-color:#F1F1F1; width:90%;">
                        <strong>PM Cost (B) : </strong> <span id="totalPmCost">{{ $pmTotal }}</span>
                    </div>
                </div>
            </div>

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
                    <label for="overheads" class="form-label">Overheads</label>
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
            <div class="row mb-4">
                <!-- Overheads Table -->
                <div class="table-responsive">
                    <table class="table table-bordered text-center" style="background-color: #D7E1E4; width:90%;">
                        <thead>
                            <tr>
                                <th>Overheads</th>
                                <th>Quantity</th>
                                <th>OH Code</th>
                                <th>UoM</th>
                                <th>Price</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $ohTotal = 0;
                                $filteredData = collect($pricingData)->unique('ohid')->values();
                                 @endphp
                            @foreach($filteredData as $data)
                                @if($data->oh_name)
                                    @php
                                    $amount = $data->oh_quantity * $data->oh_price;
                                    $ohTotal += $amount;
                                @endphp
                                    <tr>
                                        <td>{{ $data->oh_name }}</td>
                                        <td>{{ $data->oh_quantity }}</td>
                                        <td>{{ $data->oh_code }}</td>
                                        <td>{{ $data->oh_uom ?? 'N/A' }}</td>
                                        <td>{{ $data->oh_price }}</td>
                                        <td>{{ $data->oh_quantity * $data->oh_price }}</td>
                                    </tr>
                                @endif
                            @endforeach

                            @if($pricingData->whereNotNull('oh_name')->isEmpty())
                                <tr>
                                    <td colspan="6">No records available for Overheads</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-2 mt-2">
                    <label for="totalcost" class="form-label">Total Cost (A+B+C):</label>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="totalcost" value = "{{ $totalCost }}" disabled>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
<script>
    // Ensure functions are available in the global scope
    document.addEventListener('DOMContentLoaded', function () {

        window.editRow = editRow;
        window.saveRow = saveRow;

        window.pm_editRow = pm_editRow;
        window.pm_saveRow = pm_saveRow;

        rmforRecipe();
        pmforRecipe();
    });

    // Function to enable editing for a specific row
    function editRow(id, rid) {
        // Hide the text and show the input field
        document.getElementById('quantity-text-' + rid).style.display = 'none';
        document.getElementById('quantity-' + rid).style.display = 'inline-block';

        // Enable the input field
        document.getElementById('quantity-' + rid).disabled = false;

        // Show the save button and hide the edit button
        document.querySelector(`#save-${rid}`).style.display = 'inline-block';
        document.querySelector(`#edit-${rid}`).style.display = 'none';
    }

    // Function to save the edited data
    function saveRow(id, rid) {
        const quantityInput = document.getElementById('quantity-' + rid);
        const quantity = parseFloat(quantityInput.value); // Get and parse the input value

        // Perform validation
        if (isNaN(quantity) || quantity <= 0) {
            alert('Please enter a valid quantity greater than 0.');
            return;
        }
        // Get CSRF token from the meta tag
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // Send data to the server via a POST request
        fetch(`/update-pricing/${rid}`, {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ quantity: quantity }),
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                alert("Quantity updated successfully.");

                // Update the text to show the new quantity
                document.getElementById('quantity-text-' + rid).textContent = quantity.toFixed(2);

                // Update any other fields if needed, e.g., amount
                // document.querySelector(`#amount-cell-${rid}`).textContent = data.newAmount.toFixed(2);
            } else {
                alert("Error updating quantity.");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("An unexpected error occurred while updating quantity.");
        });

        // Disable the input again and revert UI changes
        quantityInput.disabled = true;
        document.getElementById('quantity-text-' + rid).style.display = 'inline-block'; // Show the text
        document.getElementById('quantity-' + rid).style.display = 'none'; // Hide the input

        // Hide the save button and show the edit button again
        document.querySelector(`#save-${rid}`).style.display = 'none';
        document.querySelector(`#edit-${rid}`).style.display = 'inline-block';
    }
    // raw materials recipe-pricing details
    function rmforRecipe()
    {
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

        // add rawmaterial
    document.getElementById('rmaddbtn').addEventListener('click', function () {
        const product_id = productSelect.value;
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

        // Prepare data for server request
        const rmdata = {
            raw_material_id: rawMaterialId,
            product_id: product_id,
            quantity: quantity,
            amount: amount,
            code: code,
            uom: uom,
            price: price,
            rpoutput: rpoutput,
            rpuom: rpuom,
        };

        // console.log(rmdata);
        // Send data to the server
        fetch('/rm-for-recipe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify(rmdata),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add the new row to the table
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
                alert('Raw material added successfully!');
            } else {
                alert('Failed to add raw material. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding raw material.');
        });
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
            // updateGrandTotal();
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
    }

    function pm_editRow(id, pid) {
        // Hide the text and show the input field
        document.getElementById('pmquantity-text-' + pid).style.display = 'none';
        document.getElementById('pmquantity-' + pid).style.display = 'inline-block';

        // Enable the input field
        document.getElementById('pmquantity-' + pid).disabled = false;

        // Show the save button and hide the edit button
        document.querySelector(`#pmsave-${pid}`).style.display = 'inline-block';
        document.querySelector(`#pmedit-${pid}`).style.display = 'none';
    }
    // Function to save the edited data
    function pm_saveRow(id, pid) {
        const pmquantityInput = document.getElementById('pmquantity-' + pid);
        const pmquantity = parseFloat(pmquantityInput.value); // Get and parse the input value

        // Perform validation
        if (isNaN(pmquantity) || pmquantity <= 0) {
            alert('Please enter a valid quantity greater than 0.');
            return;
        }
        // Get CSRF token from the meta tag
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // Send data to the server via a POST request
        fetch(`/pm-update-pricing/${pid}`, {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ quantity: pmquantity }),
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                alert("Quantity updated successfully.");
                // Update the text to show the new quantity
                document.getElementById('pmquantity-text-' + pid).textContent = pmquantity.toFixed(2);

            } else {
                alert("Error updating quantity.");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("An unexpected error occurred while updating quantity.");
        });

        // Disable the input again and revert UI changes
        pmquantityInput.disabled = true;

        document.getElementById('pmquantity-text-' + pid).style.display = 'inline-block'; // Show the text
        document.getElementById('pmquantity-' + pid).style.display = 'none'; // Hide the input
        // Hide the save button and show the edit button again
        document.querySelector(`#pmsave-${pid}`).style.display = 'none';
        document.querySelector(`#pmedit-${pid}`).style.display = 'inline-block';
    }

    // packing materials details
    function pmforRecipe() {
        // const productSelect = document.getElementById('productSelect');
        const packingMaterialSelect = document.getElementById('packingmaterial');
        const pmQuantityInput = document.getElementById('pmQuantity');
        const pmCodeInput = document.getElementById('pmCode');
        const pmUoMInput = document.getElementById('pmUoM');
        const pmPriceInput = document.getElementById('pmPrice');
        const pmAmountInput = document.getElementById('pmAmount');
        const pmAddButton = document.getElementById('pmaddbtn');
        const packingMaterialTable = document.getElementById('packingMaterialTable');
        const totalPmCostSpan = document.getElementById('totalPmCost'); // Total Cost (A+B+C)

        productSelect.addEventListener('change', function () {
            const product_id = this.value; // Update product_id with the selected value
            console.log('Selected product ID:', product_id); // Debug log to check the selected value
        });

    // Update fields when packing material is selected
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

    // Update amount on quantity input
    pmQuantityInput.addEventListener('input', updatePmAmount);

    // Add packing material
    pmAddButton.addEventListener('click', function () {
        const product_id = productSelect.value;
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

        // Prepare data for server request
        const pmData = {
                product_id: product_id,
                packing_material_id: packingMaterialId,
                quantity: quantity,
                amount: amount,
               code: code,
              uom: uom,
              price: price,
        };

        // Send data to the server
        fetch('/pm-for-recipe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify(pmData),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
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
                    alert('Packing material added successfully!');
                } else {
                    alert('Failed to add packing material. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding packing material.');
            });
    });

    // Delete row functionality
    packingMaterialTable.addEventListener('click', function (e) {
        if (e.target.classList.contains('delete-icon')) {
            const deleteIcon = e.target;
            const row = deleteIcon.closest('tr');
            const pmInsertedId = deleteIcon.getAttribute('data-id');

            // Confirm deletion
            if (!confirm('Are you sure you want to delete this record?')) {
                return;
            }
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
}

</script>
