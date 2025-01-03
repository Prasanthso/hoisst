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
              <select id="recipeSelect" class="form-select">
                <option value="samosa" selected>Samosa</option>
                <option value="Puff">Puff</option>
                <option value="Cake">Cake</option>
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
                    <input type="text" class="form-control rounded" id="recipeUoM" name="recipeUoM">
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-auto">
                    <label for="pricingrawmaterial" class="form-label text-primary">Ram material</label>
                </div>
                <div class="col">
                    <hr />
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-3">
                <label for="rawmaterial" class="form-label">Raw material</label>
                <select id="rawmaterial" class="form-select">
                    <option value="Rawmaterial1" selected>Rawmaterial1</option>
                    <option value="Rawmaterial2">Puff</option>
                    <option value="Rawmaterial3">Cake</option>
                  </select>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="rmQuantity" class="form-label">Quantity</label>
                    <input type="text" class="form-control rounded" id="rmQuantity" name="rmQuantity">
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="rmCode" class="form-label">RmCode</label>
                    <input type="text" class="form-control rounded" id="rmCode" name="rmCode">
                    </div>
                    <div class="d-flex flex-column" style="flex: 1.5;">
                        <label for="rmUoM" class="form-label">UoM</label>
                        <input type="text" class="form-control" id="rmUoM" name="rmUoM">
                    </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="rmPrice" class="form-label">Price</label>
                    <input type="text" class="form-control rounded" id="rmPrice" name="rmPrice">
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
                    <div class="col-12 col-md-12 mx-auto table-responsive"> <!-- Use col-md-11 for slightly left alignment -->
                        <table class="table table-bordered text-center" style="width:84%; background-color: #eaf8ff;">
                            <thead class="no border">
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
                                <tr>
                                    <td>Raw material 1</td>
                                    <td>10</td>
                                    <td>RM00001</td>
                                    <td>Kgs</td>
                                    <td>100</td>
                                    <td>1000</td>
                                </tr>
                                <tr>
                                    <td>Raw material 2</td>
                                    <td>10</td>
                                    <td>RM00002</td>
                                    <td>Kgs</td>
                                    <td>100</td>
                                    <td>1000</td>
                                </tr>
                                {{-- <tr id="rmCostRow">
                                    <td colspan="5"></td> <!-- Empty cells for the first 5 columns -->
                                    <td>
                                        <div class="text-end mt-2">
                                            <strong>RM Cost(A):</strong> <span id="totalCost1">2000</span>
                                        </div>
                                    </td>
                                </tr> --}}
                            </tbody>
                        </table>
                        <div class="text-end col-10" style="background-color:#eaf8ff;width:84%;">
                            <strong>RM Cost(A) : </strong> <span id="totalCost1"> 2000 </span>
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
 document.addEventListener('DOMContentLoaded', function () {
    const rmaddButton = document.getElementById('rmaddbtn');
    const rawMaterialTable = document.getElementById('rawMaterialTable');

    const packingMaterialTable = document.getElementById('packingMaterialTable');
    const pmaddButton = document.getElementById('pmaddbtn');

    const overheadsTable = document.getElementById('overheadsTable');
    const ohaddButton = document.getElementById('ohaddbtn');

    // const costRow = document.getElementById('rmCostRow');

    rmaddButton.addEventListener('click', function () {
        // Gather input values
        const rawMaterial = document.getElementById('rawmaterial').value;
        const quantity = document.getElementById('rmQuantity').value;
        const rmCode = document.getElementById('rmCode').value;
        const uom = document.getElementById('rmUoM').value;
        const price = document.getElementById('rmPrice').value;
        const amount = document.getElementById('rmAmount').value;

        // Validate inputs (optional)
        if (!rawMaterial || !quantity || !rmCode || !uom || !price || !amount) {
            alert('Please fill all fields before adding a row.');
            return;
        }

        // Create a new table row
        const newRow = `
            <tr>
                <td>${rawMaterial}</td>
                <td>${quantity}</td>
                <td>${rmCode}</td>
                <td>${uom}</td>
                <td>${price}</td>
                <td>${amount}</td>
            </tr>
        `;

        // Append the new row to the table
        rawMaterialTable.insertAdjacentHTML('beforeend', newRow);
        // costRow.parentNode.insertBefore(newRow, costRow);

        // Clear input fields
        document.getElementById('rawmaterial').value = '';
        document.getElementById('rmQuantity').value = '';
        document.getElementById('rmCode').value = '';
        document.getElementById('rmUoM').value = '';
        document.getElementById('rmPrice').value = '';
        document.getElementById('rmAmount').value = '';
    });


    // packingmaterials
    pmaddButton.addEventListener('click', function () {
        // Gather input values
        const packingmaterial = document.getElementById('packingmaterial').value;
        const pmquantity = document.getElementById('pmQuantity').value;
        const pmCode = document.getElementById('pmCode').value;
        const pmuom = document.getElementById('pmUoM').value;
        const pmprice = document.getElementById('pmPrice').value;
        const pmamount = document.getElementById('pmAmount').value;

        // Validate inputs (optional)
        if (!packingmaterial || !pmquantity || !pmCode || !pmuom || !pmprice || !pmamount) {
            alert('Please fill all fields before adding a row.');
            return;
        }

        // Create a new table row
        const pmnewRow = `
            <tr>
                <td>${packingmaterial}</td>
                <td>${pmquantity}</td>
                <td>${pmCode}</td>
                <td>${pmuom}</td>
                <td>${pmprice}</td>
                <td>${pmamount}</td>
            </tr>
        `;

        // Append the new row to the table
        packingMaterialTable.insertAdjacentHTML('beforeend', pmnewRow);
        // costRow.parentNode.insertBefore(newRow, costRow);

        // Clear input fields
        document.getElementById('packingmaterial').value = '';
        document.getElementById('pmQuantity').value = '';
        document.getElementById('pmCode').value = '';
        document.getElementById('pmUoM').value = '';
        document.getElementById('pmPrice').value = '';
        document.getElementById('pmAmount').value = '';
    });

    // Overheads
    ohaddButton.addEventListener('click', function () {
        // Gather input values
        const overheads = document.getElementById('overheads').value;
        const ohquantity = document.getElementById('ohQuantity').value;
        const ohCode = document.getElementById('ohCode').value;
        const ohuom = document.getElementById('ohUoM').value;
        const ohprice = document.getElementById('ohPrice').value;
        const ohamount = document.getElementById('ohAmount').value;

        // Validate inputs (optional)
        if (!overheads || !ohquantity || !ohCode || !ohuom || !ohprice || !ohamount) {
            alert('Please fill all fields before adding a row.');
            return;
        }

        // Create a new table row
        const ohnewRow = `
            <tr>
                <td>${overheads}</td>
                <td>${ohquantity}</td>
                <td>${ohCode}</td>
                <td>${ohuom}</td>
                <td>${ohprice}</td>
                <td>${ohamount}</td>
            </tr>
        `;

        // Append the new row to the table
        overheadsTable.insertAdjacentHTML('beforeend', ohnewRow);
        // costRow.parentNode.insertBefore(newRow, costRow);

        // Clear input fields
        document.getElementById('overheads').value = '';
        document.getElementById('ohQuantity').value = '';
        document.getElementById('ohCode').value = '';
        document.getElementById('ohUoM').value = '';
        document.getElementById('ohPrice').value = '';
        document.getElementById('ohAmount').value = '';
    });
});
</script>
