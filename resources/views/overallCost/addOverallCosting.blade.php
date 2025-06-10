@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Add Overall Costing</h1>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            @if(session('success'))
            <div id="success-message" class="alert alert-success">{{ session('success') }}</div>
            @endif
            @foreach ($errors->all() as $error)
            <div class="text-danger">{{ $error }}</div>
            @endforeach
            <div class="col-lg-12">
                <div class="col-lg-6">

                    <div class="card" style="width: 600px;">
                        <div class="card-body">

                            <!-- Vertical Form -->
                            <form method="POST" action="{{ route('overallcosting.store') }}" class="row g-3 mt-2">
                                @csrf
                                <div class="col-md-12">
                                    <label for="recipeSelect" class="form-label">Choose Recipe</label>
                                    <div class="col-12">
                                        <select id="recipeSelect" name="productId" class="form-select select2" aria-labelledby="recipeSelectLabel">
                                            <option selected disabled>Choose...</option>
                                            @foreach($recipeproducts as $recipesitem)
                                            <option value="{{ $recipesitem->id }}">{{ $recipesitem->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('productId')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <h4>Overall Costing</h4>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="col-12">
                                            <input type="text" class="form-control" id="inputRpoutput" name="inputRpoutput" hidden>

                                            <label for="inputRmcost" id="lblinputRmcost" class="form-label">RM Cost/Unit(A)</label>
                                            <input type="text" class="form-control mb-2" id="inputRmcost" name="inputRmcost" readonly>
                                        </div>
                                        <div class="col-12">
                                            <label for="inputPmcost" id="lblinputPmcost" class="form-label">PM Cost/Unit(B)</label>
                                            <input type="text" class="form-control mb-2" id="inputPmcost" name="inputPmcost" readonly>
                                        </div>
                                        <div class="col-12">
                                            <label for="inputRmPmcost" id="lblinputRmPmcost" class="form-label">RM & PM Cost(A+B)</label>
                                            <input type="text" class="form-control mb-2" id="inputRmPmcost" name="inputRmPmcost" readonly>
                                        </div>
                                        <div class="col-12">
                                            <label for="inputOverhead" id="lblinputOverhead" class="form-label">Overhead(C)</label>
                                            <input type="text" class="form-control mb-2" id="inputOverhead" name="inputOverhead" readonly>
                                        </div>

                                        <div class="col-12 mb-2">
                                            {{-- <input type="hidden" class="form-control mb-2" name="productType" id="productType" value=""> --}}
                                            <label for="inputTotalCost" class="form-label">Total cost(A+B+C)</label>
                                            <input type="text" class="form-control mb-2" id="inputTotalCost" name="inputTotalCost" readonly>
                                        </div>

                                    </div>
                                    <div class="col">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-6">
                                                    <label for="inputMargin" class="form-label">Margin(%)</label>
                                                    <input type="number" id="marginInput" name="og_margin" class="form-control mb-2" oninput="calculateMarkup()">
                                                </div>
                                                <div class="col-6">
                                                    <label for="inputMargin" class="form-label">Markup</label>
                                                    <input type="text" class="form-control mb-2" id="inputMargin" name="inputMargin" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label for="inputMarginAmt" class="form-label">Margin Amount</label>
                                            <input type="text" class="form-control mb-2" id="inputMarginAmt" name="inputMarginAmt" readonly>
                                        </div>
                                        <div class="col-12">
                                            <label for="inputTax" class="form-label">Tax(%)</label>
                                            <input type="text" class="form-control mb-2" id="inputTax" name="inputTax" readonly>
                                        </div>
                                        <div class="col-12">
                                            <label for="inputDiscount" class="form-label">Discount(%)</label>
                                            <input type="text" class="form-control" id="inputDiscount" name="inputDiscount">
                                            <div id="DiscountAmt" class="mb-2" style="color:blue;"></div>
                                        </div>
                                        <div class="col-12" hidden>
                                            <label for="inputSuggRate" class="form-label">Suggested Rate</label>
                                            <input type="text" class="form-control mb-2" id="inputSuggRate" name="inputSuggRate" readonly>
                                        </div>
                                        <div class="col-12">
                                            <label for="inputSuggMrp" class="form-label">Suggested MRP</label>
                                            <input type="text" class="form-control mb-2" id="inputSuggMrp" name="inputSuggMrp" readonly>
                                        </div>
                                        <div class="col-12" hidden>
                                            <label for="inputSuggRatebf" class="form-label">Suggested Rate before Tax</label>
                                            <input type="text" class="form-control mb-2" id="inputSuggRatebf" name="inputSuggRatebf" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary save-btn">
                                        Save
                                    </button>
                                </div>
                            </form><!-- Vertical Form -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Structure -->
        <div class="modal fade" id="markupModal" tabindex="-1" aria-labelledby="markupModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="markupModalLabel">Markup Calculation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <label>Enter Margin %:</label>
                            <input type="number" id="marginInput" class="form-control" placeholder="Enter margin %" oninput="calculateMarkup()">
                        </form>
                        <p>Markup is the percentage added to the cost price to determine the selling price.</p>
                        <p><strong>Markup %:</strong> <span id="markupResult">-</span></p>
                    </div>
                    <div class="modal-footer">
                        <!--<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>-->
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="applyMarkupBtn">Apply</button>
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
<!--Template Main JS File-->
<script src="{{ asset('js/main.js') }}"></script>
<script>
    function calculateMarkup() {
        let margin = parseFloat(document.getElementById('marginInput').value);
        let markupInput = document.getElementById('inputMargin');
        if (!isNaN(margin) && margin < 100 && margin >= 0) {
            let markup = (margin * 100) / (100 - margin);
            markupInput.value = markup.toFixed(2);
            calculate(); // Trigger MRP calculation
        } else {
            markupInput.value = "0.00"; // Default to 0 for invalid input
            calculate(); // Update with no markup
        }
    }

    $(document).ready(function() {
        $('#recipeSelect').select2({
            theme: 'bootstrap-5',
            placeholder: "Type or select a recipe...",
        });

        $('#editButton').on('click', function() {
            // Change the page title text
            $('#pageTitle').text('Edit Overall Costing');

            // Enable form fields
            $('#overallCostingForm input, #overallCostingForm select').prop('disabled', false);

            // Show the Save button
            $('#saveButton').show();
        });
    });

    function calculate() {
        let totalCost = parseFloat(document.getElementById('inputTotalCost').value) || 0;
        let markup = parseFloat(document.getElementById('inputMargin').value) || 0;
        let pertax = parseFloat(document.getElementById('inputTax').value) || 0;
        let perdiscount = parseFloat(document.getElementById('inputDiscount').value) || 0;
        let recipeOutput = parseFloat(document.getElementById('inputRpoutput').value) || 0;

        // Calculate Margin Amount using Markup
        let marginAmt = (totalCost * markup / 100).toFixed(2);
        let marginTotal = (totalCost + parseFloat(marginAmt)).toFixed(2);

        // Calculate Tax Amount
        let taxAmt = (parseFloat(marginTotal) * pertax / 100).toFixed(2);
        let taxTotal = (parseFloat(marginTotal) + parseFloat(taxAmt)).toFixed(2);

        // Calculate Discount Amount (subtract discount as it reduces the price)
        let discAmt = (parseFloat(taxTotal) * perdiscount / 100).toFixed(2);
        let netTotal = (parseFloat(taxTotal) + parseFloat(discAmt)).toFixed(2);

        // Calculate Suggested Rate and Suggested Rate before Tax
        // Use totalCost and marginTotal directly if recipeOutput is 0 to avoid division by zero
        let suggRate = recipeOutput > 0 ? (totalCost / recipeOutput).toFixed(2) : totalCost.toFixed(2);
        let suggRatebf = recipeOutput > 0 ? (parseFloat(marginTotal) / recipeOutput).toFixed(2) : marginTotal;

        // Update UI Elements
        document.getElementById('inputMarginAmt').value = marginAmt;
        document.getElementById('DiscountAmt').innerHTML = "Discount Amount: " + discAmt;
        document.getElementById('inputSuggMrp').value = netTotal;
        document.getElementById('inputSuggRate').value = suggRate;
        document.getElementById('inputSuggRatebf').value = suggRatebf;

        // Debugging Logs
        console.log("Total Cost:", totalCost);
        console.log("Markup (%):", markup);
        console.log("Margin Amount:", marginAmt);
        console.log("Margin Total:", marginTotal);
        console.log("Tax Amount:", taxAmt);
        console.log("Tax Total:", taxTotal);
        console.log("Discount Amount:", discAmt);
        console.log("Suggested Rate:", suggRate);
        console.log("Suggested Rate before Tax:", suggRatebf);
        console.log("Suggested MRP:", netTotal);
    }

    document.addEventListener("DOMContentLoaded", () => {
        // Initialize Select2
        $(document).ready(function() {
            $('#recipeSelect').select2({
                theme: 'bootstrap-5',
                placeholder: "Type or select a recipe...",
            });
        });

        // Event listener for recipe selection
        $('#recipeSelect').on('change', function() {
            const productId = $(this).val();
            if (productId) {
                recipedata(productId);
            } else {
                // Clear fields if no recipe is selected
                document.getElementById('inputRmcost').value = '0.00';
                document.getElementById('inputPmcost').value = '0.00';
                document.getElementById('inputOverhead').value = '0.00';
                document.getElementById('inputRmPmcost').value = '0.00';
                document.getElementById('inputTotalCost').value = '0.00';
                document.getElementById('inputTax').value = '0.00';
                document.getElementById('inputRpoutput').value = '0';
                document.getElementById('inputMargin').value = '0.00';
                document.getElementById('inputMarginAmt').value = '0.00';
                document.getElementById('inputSuggMrp').value = '0.00';
                document.getElementById('inputSuggRate').value = '0.00';
                document.getElementById('inputSuggRatebf').value = '0.00';
                calculate();
            }
        });

        // Event listener for margin input
        document.getElementById('marginInput').addEventListener('input', calculateMarkup);

        // Event listener for discount input
        document.getElementById('inputDiscount').addEventListener('input', () => {
            let perdiscount = parseFloat(document.getElementById('inputDiscount').value) || 0;
            console.log(`Discount updated: ${perdiscount}%`);
            calculate();
        });

        // Event listener for tax input (in case it becomes editable)
        document.getElementById('inputTax').addEventListener('input', () => {
            let pertax = parseFloat(document.getElementById('inputTax').value) || 0;
            console.log(`Tax updated: ${pertax}%`);
            calculate();
        });

        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(event) {
            let suggRate = document.getElementById('inputSuggRate').value;
            let suggRatebf = document.getElementById('inputSuggRatebf').value;
            if (!suggRate || !suggRatebf || isNaN(suggRate) || isNaN(suggRatebf)) {
                event.preventDefault();
                alert('Please ensure all cost fields are populated by selecting a recipe and setting a valid margin.');
            }
        });
    });

    async function recipedata(productId) {
        if (!productId) {
            alert("Please enter a recipe");
            return;
        }
        try {
            let response = await fetch(`/get-abc-cost?productId=${productId}`);
            let data = await response.json();
            if (response.ok) {
                // Populate fields with fetched data, default to 0.00 if invalid
                document.getElementById('inputRmcost').value = data.rpoutput > 0 ? (data.totalRmCost / data.rpoutput).toFixed(2) : '0.00';
                document.getElementById('inputPmcost').value = data.rpoutput > 0 ? (data.totalPmCost / data.rpoutput).toFixed(2) : '0.00';
                document.getElementById('inputOverhead').value = data.rpoutput > 0 ? (data.totalOhCost / data.rpoutput).toFixed(2) : '0.00';
                document.getElementById('inputRmPmcost').value = (parseFloat(data.totalRmCost / data.rpoutput || 0) + parseFloat(data.totalPmCost / data.rpoutput || 0)).toFixed(2);
                document.getElementById('inputTotalCost').value = (parseFloat(data.totalRmCost / data.rpoutput || 0) + parseFloat(data.totalPmCost / data.rpoutput || 0) + parseFloat(data.totalOhCost / data.rpoutput || 0)).toFixed(2);
                document.getElementById('inputTax').value = parseFloat(data.product_tax || 0).toFixed(2);
                document.getElementById('inputRpoutput').value = data.rpoutput || '0';
                calculate(); // Trigger calculations after data fetch
            } else {
                alert(data.error);
                // Clear fields on error
                document.getElementById('inputRmcost').value = '0.00';
                document.getElementById('inputPmcost').value = '0.00';
                document.getElementById('inputOverhead').value = '0.00';
                document.getElementById('inputRmPmcost').value = '0.00';
                document.getElementById('inputTotalCost').value = '0.00';
                document.getElementById('inputTax').value = '0.00';
                document.getElementById('inputRpoutput').value = '0';
                document.getElementById('inputSuggRate').value = '0.00';
                document.getElementById('inputSuggRatebf').value = '0.00';
                calculate();
            }
        } catch (error) {
            console.error(error);
            alert("Error fetching cost");
            // Clear fields on error
            document.getElementById('inputRmcost').value = '0.00';
            document.getElementById('inputPmcost').value = '0.00';
            document.getElementById('inputOverhead').value = '0.00';
            document.getElementById('inputRmPmcost').value = '0.00';
            document.getElementById('inputTotalCost').value = '0.00';
            document.getElementById('inputTax').value = '0.00';
            document.getElementById('inputRpoutput').value = '0';
            document.getElementById('inputSuggRate').value = '0.00';
            document.getElementById('inputSuggRatebf').value = '0.00';
            calculate();
        }
    }
</script>