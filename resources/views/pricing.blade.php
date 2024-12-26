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
    <section class="section">
        <div class="container mt-5">
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
                <div class="col-3">
                <label for="recipeOutput" class="form-label">Output</label>
                <input type="text" class="form-control rounded" id="recipeOutput" name="recipeOutput">
                </div>
                <div class="col-2">
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
                <div class="col-3">
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
                <a href="#" class='text-decoration-none ps-add-btn text-white py-4 px-4'>
                    <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
                </a>
                </div>
            </div>
            {{-- <div class="container-fluid mt-4"> --}}
                <div class="row mb-4">
                    <div class="col-12 col-md-12 mx-auto"> <!-- Use col-md-11 for slightly left alignment -->
                        <table class="table table-bordered text-center" style="width:84%; background-color: #eaf8ff;">
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
                                <tr>
                                    <td>Raw material 1</td>
                                    <td>10</td>
                                    <td>RM00001</td>
                                    <td>Kgs</td>
                                    <td>100</td>
                                    <td>1000</td>
                                </tr>
                                <tr>
                                    <td>Raw material 1</td>
                                    <td>10</td>
                                    <td>RM00001</td>
                                    <td>Kgs</td>
                                    <td>100</td>
                                    <td>1000</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-end mt-2">
                            <strong>RM Cost(A):</strong> <span id="totalCost">2000</span>
                        </div>
                    </div>
                </div>
            {{-- </div> --}}

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
<script>
    function calculateTotalCost() {
        let total = 0;
        const table = document.getElementById('rawMaterialTable');
        const rows = table.getElementsByTagName('tr');

        for (const row of rows) {
            const amountCell = row.cells[5]; // Amount is the 6th column (index 5)
            const amount = parseFloat(amountCell.textContent) || 0;
            total += amount;
        }

        document.getElementById('totalCost').textContent = total.toFixed(2);
    }

    // Call the function on page load or when the table updates dynamically
    calculateTotalCost();
</script>

