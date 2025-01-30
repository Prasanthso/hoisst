@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>View Overall Costing</h1>
        <div class="d-flex justify-content-end mb-2 action-buttons">
            <button class="btn btn-sm edit-table-btn me-2" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;" id="editButton">
                <i class="fas fa-edit" style="color: black;"></i>
            </button>
            <button class="btn btn-sm delete-table-btn" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;" id="deleteButton" style="display: none;">
                <i class="fas fa-trash" style="color: red;"></i>
            </button>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            @if(session('success'))
            <div id="success-message" class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="col-lg-12">
                <div class="col-lg-6">

                    <div class="card w-100">
                        <div class="card-body">

                            <!-- Vertical Form -->
                            <form method="POST" action="{{ route('overallcosting.update', $costing->id) }}" class="row g-3 mt-2"  id="overallCostingForm">
                                @csrf
                                @method('PUT')
                                <div class="col-md-12">
                                    <label for="inputState" class="form-label">Choose Recipe</label>
                                    <div class="col-12">
                                        <select id="recipeSelect" name="productId" class="form-select select2" aria-labelledby="recipeSelectLabel" disabled>
                                        <option value="{{ $costing->id }}">{{ $costing->product_name }}</option>
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
                                    <label for="inputRmcost" class="form-label">RM Cost/Unit(A)</label>
                                    <input type="text" class="form-control" id="inputRmcost" name="inputRmcost" value="{{ $costing->rm_cost_unit}}" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputPmcost" class="form-label">PM Cost/Unit(B)</label>
                                    <input type="text" class="form-control" id="inputPmcost" name="inputPmcost" value="{{ $costing->pm_cost_unit}}" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputRmPmcost" class="form-label">RM & PM Cost</label>
                                    <input type="text" class="form-control" id="inputRmPmcost" name="inputRmPmcost" value="{{ $costing->rm_pm_cost}}" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputOverhead" class="form-label">Overhead(C)</label>
                                    <input type="text" class="form-control" id="inputOverhead" name="inputOverhead" value="{{ $costing->overhead}}" disabled>
                                </div>
                            </div>
                            <div class="col">
                                <div class="col-12">
                                    <label for="inputRmSgmrp" class="form-label">RM(%) Suggested MRP</label>
                                    <input type="text" class="form-control" id="inputRmSgmrp" name="inputRmSgmrp" value="{{ $costing->rm_sg_mrp}}" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputPmSgmrp" class="form-label">PM(%) Suggested MRP</label>
                                    <input type="text" class="form-control" id="inputPmSgmrp" name="inputPmSgmrp" value="{{ $costing->pm_sg_mrp}}" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputSgMrp" class="form-label"> Suggested MRP </label>
                                    <input type="text" class="form-control" id="inputSgMrp" name="inputSgMrp" value="{{ $costing->sg_mrp}}" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputSgMargin" class="form-label"> Suggested Margin </label>
                                    <input type="text" class="form-control" id="inputSgMargin" name="inputSgMargin" value="{{ $costing->sg_margin}}" disabled>
                                </div>
                            </div>
                                <div class="col-12">
                                    <label for="inputOhAmt" class="form-label">Overhead Amount D</label>
                                    <input type="text" class="form-control" id="inputOhAmt" name="inputOhAmt" value="{{ $costing->oh_amt}}" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputTotalCost" class="form-label">Total cost J</label>
                                    <input type="text" class="form-control" id="inputTotalCost" name="inputTotalCost" value="{{ $costing->total_cost}}" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputSellRate" class="form-label">Selling Rate</label>
                                    <input type="text" class="form-control" id="inputSellRate" name="inputSellRate" value="{{ $costing->sell_rate}}" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputSellRatebf" class="form-label">Selling Rate before tax</label>
                                    <input type="text" class="form-control" id="inputSellRatebf" name="inputSellRatebf" value="{{ $costing->sell_rate_bf}}" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputTax" class="form-label">Tax</label>
                                    <input type="text" class="form-control" id="inputTax" name="inputTax" value="{{ $costing->tax}}" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputMarginAmt" class="form-label">Margin Amount</label>
                                    <input type="text" class="form-control" id="inputMarginAmt" name="inputMarginAmt" value="{{ $costing->margin_amt}}" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputDiscount" class="form-label">Discount</label>
                                    <input type="text" class="form-control" id="inputDiscount" name="inputDiscount" value="{{ $costing->discount}}" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputPresentMrp" class="form-label">Present MRP</label>
                                    <input type="text" class="form-control" id="inputPresentMrp" name="inputPresentMrp" value="{{ $costing->present_mrp}}" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputMargin" class="form-label">Margin</label>
                                    <input type="text" class="form-control" id="inputMargin" name="inputMargin" value="{{ $costing->margin}}" disabled>
                                </div>
                            </div>
                                <div>
                                    <button type="submit" class="btn btn-primary" id="saveButton" style="display: none;">
                                        Update
                                    </button>
                                </div>
                            </form><!-- Vertical Form -->

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

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
     document.addEventListener("DOMContentLoaded", () => {
        const recipeSelect = document.getElementById('recipeSelect');
        const RmCostA = document.getElementById('inputRmcost');
        const PmCostB = document.getElementById('inputPmcost');
        const RmPmCost = document.getElementById('inputRmPmcost');
        const OhCostC = document.getElementById('inputOverhead');
        const TotalCost = document.getElementById('inputTotalCost');
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

        $('#recipeSelect').on('change', function () {
            const selectedValue = $(this).val();
            console.log("Selected value:", selectedValue);
            if (selectedValue) {
                recipedata(selectedValue);
            } else {
                console.log("No recipe selected.");
            }
        });

    async function recipedata(productId)
    {
        // const productId = recipeSelect.value;
            if (!productId) {
                alert("Please enter a recipe");
                return;
            }
        if (productId) {
            try {
                // editRecipeBtn.setAttribute('data-id', productId);
                let response = await fetch(`/get-abc-cost?productId=${productId}`);
                let data = await response.json();
                if (response.ok) {

                const selectedText = recipeSelect.options[recipeSelect.selectedIndex].text.trim();
                    if(selectedText != null)
                    {
                        RmCostA.value = data.totalRmCost;
                        PmCostB.value = data.totalPmCost;
                        OhCostC.value = data.totalOhCost;
                        RmPmCost.value = (parseFloat(data.totalRmCost) + parseFloat(data.totalPmCost)).toFixed(2);
                        TotalCost.value = (parseFloat(data.totalRmCost) + parseFloat(data.totalPmCost) + parseFloat(data.totalOhCost)).toFixed(2)
                    }
                }
                else{ alert(data.error); }

            } catch (error) {
                console.error(error);
                alert("Error fetching cost");
            }
        }
    }

    setTimeout(function () {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 5000);
});
</script>

<!--Template Main JS File-->
<script src="{{ asset('js/main.js') }}">
</script>
