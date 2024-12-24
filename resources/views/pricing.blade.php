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

            <div class="row mb-4">
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
                <div class="col-1">
                    <label for="rmQuantity" class="form-label">Quantity</label>
                    <input type="text" class="form-control rounded" id="rmQuantity" name="rmQuantity">
                </div>
                <div class="col-1">
                    <label for="rmCode" class="form-label">RmCode</label>
                    <input type="text" class="form-control rounded" id="rmCode" name="rmCode">
                    </div>
                <div class="col-1">
                        <label for="rmUoM" class="form-label">UoM</label>
                        <input type="text" class="form-control" id="rmUoM" name="rmUoM">
                </div>
                <div class="col-1">
                    <label for="rmPrice" class="form-label">Price</label>
                    <input type="text" class="form-control rounded" id="rmPrice" name="rmPrice">
                    </div>
                <div class="col-1">
                        <label for="rmAmount" class="form-label">Amount</label>
                        <input type="text" class="form-control" id="rmAmount" name="rmAmount">
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
