@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Pricing</h1>
        <div class="row">
            <!-- Action Buttons -->
            <div class="d-flex justify-content-end mb-2 action-buttons">
                <button class="btn btn-sm edit-table-btn me-2" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                    <i class="fas fa-edit" style="color: black;"></i>
                </button>
                <button class="btn btn-sm delete-table-btn" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                    <i class="fas fa-trash" style="color: red;"></i>
                </button>
            </div>
        </div>
    </div><!-- End Page Title -->
    <section class="section dashboard">
        <div class="container mt-5">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="mb-4">
                <label for="recipeSelect" class="form-label">Select Recipe</label>
                <div class="col-6">
                    <select id="recipeSelect" class="form-select" aria-labelledby="recipeSelectLabel">
                        <option selected disabled>Choose...</option>
                        @foreach($products as $productItem)
                        <option value="{{ $productItem->id }}">{{ $productItem->name }}</option>
                        @endforeach
                    </select>
                    <!-- @error('recipeId')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror -->
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 col-sm-10 mb-2">
                    <label for="recipeOutput" class="form-label">Output</label>
                    <input type="text" class="form-control rounded" id="recipeOutput" name="recipeOutput">
                </div>
                <div class="col-md-2 col-sm-10">
                    <label for="recipeUoM" class="form-label">UoM</label>
                    <input type="text" class="form-control rounded" id="recipeUoM" name="recipeUoM">
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-auto">
                    <label for="pricingrawmaterial" class="form-label text-primary">Raw Material</label>
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
                    {{-- <a href="#" class='text-decoration-none rm-ps-add-btn text-white py-4 px-4'> --}}
                    <button type="button" class="btn btn-primary rmaddbtn" id="rmaddbtn"><i class="fas fa-plus"></i> Add</button>
                    {{-- </a> --}}
                </div>
            </div>
            {{-- <div class="container-fluid mt-4"> --}}
            <div class="row mb-4">
                <div class="table-responsive">
                    <table class="table table-bordered text-center" style="background-color: #eaf8ff;">
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
                    <div class="text-end" style="background-color: #eaf8ff;">
                        <strong>RM Cost: </strong> <span id="totalCost">0.00</span>
                    </div>
                </div>
            </div>
            {{-- </div> --}}
            {{-- Packing materials --}}
            <div class="row mb-2">
                <div class="col-auto">
                    <label for="pricingpackingmaterial" class="form-label text-primary">Packing material</label>
                </div>
                <div class="col">
                    <hr />
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="packingmaterial" class="form-label">Packing material</label>
                    <select id="packingmaterial" class="form-select">
                        <option value="packingmaterial1" selected>packingmaterial1</option>
                        <option value="packingmaterial2">packingmaterial2</option>
                        <option value="packingmaterial3">packingmaterial3</option>
                    </select>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="pmQuantity" class="form-label">Quantity</label>
                    <input type="text" class="form-control rounded" id="pmQuantity" name="pmQuantity">
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="pmCode" class="form-label">PmCode</label>
                    <input type="text" class="form-control rounded" id="pmCode" name="pmCode">
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="pmUoM" class="form-label">UoM</label>
                    <input type="text" class="form-control" id="pmUoM" name="pmUoM">
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="pmPrice" class="form-label">Price</label>
                    <input type="text" class="form-control rounded" id="pmPrice" name="pmPrice">
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
                    <table class="table table-bordered text-center" style="width:84%; background-color: #F1F1F1;">
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
                            <tr>
                                <td>Packing material 1</td>
                                <td>10</td>
                                <td>PM00001</td>
                                <td>Kgs</td>
                                <td>100</td>
                                <td>1000</td>
                            </tr>
                            <tr>
                                <td>Packing material 2</td>
                                <td>10</td>
                                <td>PM00002</td>
                                <td>Kgs</td>
                                <td>100</td>
                                <td>1000</td>
                            </tr>
                            {{-- <tr>
                                    <td colspan="5"></td> <!-- Empty cells for the first 5 columns -->
                                    <td>
                                        <div class="text-end mt-2">
                                            <strong>PM Cost(B):</strong> <span id="totalCost2">2000</span>
                                        </div>
                                    </td>
                                </tr> --}}
                        </tbody>
                    </table>
                    <div class="text-end col-10" style="background-color:#F1F1F1; width:84%; ">
                        <strong>PM Cost(B): </strong> <span id="totalCost2"> 2000 </span>
                    </div>
                </div>
            </div>
            {{-- Overheads --}}
            <div class="row mb-2">
                <div class="col-auto">
                    <label for="pricingoverheads" class="form-label text-primary">Overheads</label>
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
                        <option value="overheads1" selected>Overhead1</option>
                        <option value="overheads2">Overhead2</option>
                        <option value="overheads3">Overhead3</option>
                    </select>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="ohQuantity" class="form-label">Quantity</label>
                    <input type="text" class="form-control rounded" id="ohQuantity" name="ohQuantity">
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="ohCode" class="form-label">OhCode</label>
                    <input type="text" class="form-control rounded" id="ohCode" name="ohCode">
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="ohUoM" class="form-label">UoM</label>
                    <input type="text" class="form-control" id="ohUoM" name="ohUoM">
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="ohPrice" class="form-label">Price</label>
                    <input type="text" class="form-control rounded" id="ohPrice" name="ohPrice">
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
                    <table class="table table-bordered text-center" style="width:84%; background-color: #D7E1E4;">
                        <thead class="no border">
                            <tr>
                                <th>Overheads</th>
                                <th>Quantity</th>
                                <th>OhCode</th>
                                <th>UoM</th>
                                <th>Price</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody id="overheadsTable">
                            <tr>
                                <td>overheads1</td>
                                <td>10</td>
                                <td>oh00001</td>
                                <td>Kgs</td>
                                <td>100</td>
                                <td>1000</td>
                            </tr>
                            <tr>
                                <td>overheads2</td>
                                <td>10</td>
                                <td>oh00002</td>
                                <td>Kgs</td>
                                <td>100</td>
                                <td>1000</td>
                            </tr>
                            {{-- <tr>
                                        <td colspan="5"></td> <!-- Empty cells for the first 5 columns -->
                                        <td>
                                            <div class="text-end mt-2">
                                                <strong>Oh Cost(C):</strong> <span id="totalCost3">2000</span>
                                            </div>
                                        </td>
                                    </tr> --}}
                        </tbody>

                    </table>
                    <div class="text-end col-md-10" style="width:84%;background-color:#D7E1E4;">
                        <strong>OH Cost(C): </strong> <span id="totalCost3">2000</span>
                    </div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="col-auto">
                    <label for="totalcost" class="form-label">Total Cost (A+B+C): </label>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" id="totalcost">
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

<!-- Template Main JS File -->
<script src="{{ asset('js/main.js') }}"></script>
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
 --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rawMaterialSelect = document.getElementById('rawmaterial');
        const quantityInput = document.getElementById('rmQuantity');
        const codeInput = document.getElementById('rmCode');
        const uomInput = document.getElementById('rmUoM');
        const priceInput = document.getElementById('rmPrice');
        const amountInput = document.getElementById('rmAmount');
        const addButton = document.getElementById('rmaddbtn');
        const tableBody = document.getElementById('rawMaterialTable');
        const totalCostSpan = document.getElementById('totalCost');

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
            const rawMaterialName = rawMaterialSelect.options[rawMaterialSelect.selectedIndex]?.text;
            const quantity = parseFloat(quantityInput.value) || 0;
            const code = codeInput.value;
            const uom = uomInput.value;
            const price = parseFloat(priceInput.value) || 0;
            const amount = parseFloat(amountInput.value) || 0;

            if (!rawMaterialName || !quantity || !code || !uom || !price || !amount) {
                alert('Please fill all fields before adding.');
                return;
            }

            // Append new row
            const row = `
                <tr>
                    <td>${rawMaterialName}</td>
                    <td>${quantity.toFixed(2)}</td>
                    <td>${code}</td>
                    <td>${uom}</td>
                    <td>${price.toFixed(2)}</td>
                    <td>${amount.toFixed(2)}</td>
                </tr>`;
            tableBody.insertAdjacentHTML('beforeend', row);

            // Update total cost
            updateTotalCost(amount);

            // Clear fields
            clearFields();
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
        }

        function clearFields() {
            rawMaterialSelect.value = '';
            quantityInput.value = '';
            codeInput.value = '';
            uomInput.value = '';
            priceInput.value = '';
            amountInput.value = '';
        }
    });
</script>