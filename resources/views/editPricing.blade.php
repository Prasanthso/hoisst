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
                            <option value="{{ $product->id }}">
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
                            <tbody>
                                @foreach($pricingData as $data)
                                    @if($data->rm_name)
                                        <tr>
                                            <td>{{ $data->rm_name }}</td>
                                            <td class="quantity-cell" id="quantity-cell-{{ $data->rm_id }}">
                                                <span id="quantity-text-{{ $data->rm_id }}">{{ $data->rm_quantity }}</span>
                                                <input type="number" class="form-control quantity-input" id="quantity-{{ $data->rm_id }}" value="{{ $data->rm_quantity }}" style="display: none;" disabled>
                                            </td>
                                            <td>{{ $data->rm_code }}</td>
                                            <td>{{ $data->rm_uom ?? 'N/A' }}</td>
                                            <td>{{ $data->rm_price }}</td>
                                            <td>{{ $data->rm_quantity * $data->rm_price }}</td>
                                            <td>
                                                <!-- Action Buttons -->
                                                <button class="btn btn-primary btn-sm" onclick="editRow('{{ $data->rm_id }}')">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </button>
                                                <button class="btn btn-success btn-sm save-btn" id="save-{{ $data->rm_id }}" style="display:none;" onclick="saveRow('{{ $data->rm_id }}')">
                                                    <i class="bi bi-save"></i> Save
                                                </button>

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
                            <strong>RM Cost (A) : </strong> <span id="totalRmCost">0.00</span>
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
                        <tbody id="rawMaterialTable">
                            @foreach($pricingData as $data)
                                @if($data->pm_name)
                                    <tr>
                                        <td>{{ $data->pm_name }}</td>
                                        <td>{{ $data->pm_quantity }}</td>
                                        <td>{{ $data->pm_code }}</td>
                                        <td>{{ $data->pm_uom ?? 'N/A' }}</td>
                                        <td>{{ $data->pm_price }}</td>
                                        <td>{{ $data->pm_quantity * $data->pm_price }}</td>
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
                            @foreach($pricingData as $data)
                                @if($data->oh_name)
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
    document.addEventListener('DOMContentLoaded', function() {
        const rmquantityInput = document.getElementById('rmQuantity');
        const rmpriceInput = document.getElementById('rmPrice');
        const rmamountInput = document.getElementById('rmAmount');
        const totalCostSpan = document.getElementById('totalRmCost');
        const totalCostInput = document.getElementById('totalcost'); // Total Cost (A+B+C)

   // Function to enable the quantity input for editing
   function editRow(id) {
    document.getElementById(`quantity-text-${rowId}`).style.display = "none";
        const inputField = document.getElementById(`quantity-${rowId}`);
        inputField.style.display = "block";
        inputField.disabled = false;

        // Show the Save button and hide the Edit button
        document.querySelector(`#save-${rowId}`).style.display = "inline-block";
        }

        // rmquantityInput.addEventListener('input', updateAmount);

        // Function to save the edited data
        function saveRow(rowId) {
            const quantityInput = document.getElementById('quantity-' + rowId);
            const quantityText = document.getElementById('quantity-text-' + rowId);
            const saveBtn = document.getElementById('save-' + rowId);

            const newQuantity = quantityInput.value;

            // Make an AJAX request to save the quantity in the database
            fetch('/edit-pricing/${id}', {
                method: 'POST',
                body: JSON.stringify({
                    rowId: rowId,
                    newQuantity: newQuantity
                }),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            }).then(response => response.json())
              .then(data => {
                if (data.success) {
                    quantityText.textContent = newQuantity;
                    quantityText.style.display = 'inline';
                    quantityInput.style.display = 'none';
                    saveBtn.style.display = 'none';
                } else {
                    alert('Error updating data');
                }
            });
        }

        // Loop through each row to handle the edit button click
        // const editButtons = document.querySelectorAll('.btn-edit');
        // editButtons.forEach(button => {
        //     button.addEventListener('click', function() {
        //         const rowId = button.getAttribute('data-row-id');
        //         enableQuantityField(rowId);
        //     });
        // });

         // Helper functions
         function updateAmount() {
            const price = parseFloat(rmpriceInput.value) || 0;
            const quantity = parseFloat(rmquantityInput.value) || 0;
            amountInput.value = (price * quantity).toFixed(2);
        }

        function updateTotalCost(newAmount) {
            const currentTotal = parseFloat(totalCostSpan.textContent) || 0;
            totalCostSpan.textContent = (currentTotal + newAmount).toFixed(2);
            updateGrandTotal();
        }

        function updateGrandTotal() {
            const rawMaterialTotal = parseFloat(totalCostSpan.textContent) || 0;
            const packingMaterialTotal = parseFloat(totalPmCostSpan.textContent) || 0;
            const overheadsTotal = parseFloat(totalOhCostSpan.textContent) || 0;
            const grandTotal = rawMaterialTotal + packingMaterialTotal + overheadsTotal; // Add other totals if needed
            totalCostInput.value = grandTotal.toFixed(2); // Display in Total Cost (A+B+C)
        }


});
    </script>
