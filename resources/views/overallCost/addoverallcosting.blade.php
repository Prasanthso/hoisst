@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Overall Costing</h1>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            @if(session('success'))
            <div id="success-message" class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="col-lg-12">
                <div class="col-lg-6">

                    <div class="card">
                        <div class="card-body">

                            <!-- Vertical Form -->
                            <form method="POST" action="{{ route('overallcosting.store') }}" class="row g-3 mt-2">
                                @csrf
                                <div class="col-md-12">
                                    <label for="inputState" class="form-label">Choose Recipe</label>
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
                                    <label for="inputRmcost" class="form-label">RM Cost/Unit(A)</label>
                                    <input type="text" class="form-control" id="inputRmcost" name="inputRmcost">
                                </div>
                                <div class="col-12">
                                    <label for="inputPmcost" class="form-label">PM Cost/Unit(B)</label>
                                    <input type="text" class="form-control" id="inputPmcost" name="inputPmcost">
                                </div>
                                <div class="col-12">
                                    <label for="inputRmPmcost" class="form-label">RM & PM Cost</label>
                                    <input type="text" class="form-control" id="inputRmPmcost" name="inputRmPmcost">
                                </div>
                                <div class="col-12">
                                    <label for="inputOverhead" class="form-label">Overhead(C)</label>
                                    <input type="text" class="form-control" id="inputOverhead" name="inputOverhead">
                                </div>
                            </div>
                            <div class="col">
                                <div class="col-12">
                                    <label for="inputRmSgmrp" class="form-label">RM(%) Suggested MRP</label>
                                    <input type="text" class="form-control" id="inputRmSgmrp" name="inputRmSgmrp">
                                </div>
                                <div class="col-12">
                                    <label for="inputPmSgmrp" class="form-label">PM(%) Suggested MRP</label>
                                    <input type="text" class="form-control" id="inputPmSgmrp" name="inputPmSgmrp">
                                </div>
                                <div class="col-12">
                                    <label for="inputSgMrp" class="form-label"> Suggested MRP </label>
                                    <input type="text" class="form-control" id="inputSgMrp" name="inputSgMrp">
                                </div>
                                <div class="col-12">
                                    <label for="inputSgMargin" class="form-label"> Suggested Margin </label>
                                    <input type="text" class="form-control" id="inputSgMargin" name="inputSgMargin">
                                </div>
                            </div>
                                <div class="col-12">
                                    <label for="inputOhAmt" class="form-label">Overhead Amount D</label>
                                    <input type="text" class="form-control" id="inputOhAmt" name="inputOhAmt">
                                </div>
                                <div class="col-12">
                                    <label for="inputTotalCost" class="form-label">Total cost J</label>
                                    <input type="text" class="form-control" id="inputTotalCost" name="inputTotalCost">
                                </div>
                                <div class="col-12">
                                    <label for="inputSellRate" class="form-label">Selling Rate</label>
                                    <input type="text" class="form-control" id="inputSellRate" name="inputSellRate">
                                </div>
                                <div class="col-12">
                                    <label for="inputSellRatebf" class="form-label">Selling Rate before tax</label>
                                    <input type="text" class="form-control" id="inputSellRatebf" name="inputSellRatebf">
                                </div>
                                <div class="col-12">
                                    <label for="inputTax" class="form-label">Tax</label>
                                    <input type="text" class="form-control" id="inputTax" name="inputTax">
                                </div>
                                <div class="col-12">
                                    <label for="inputMarginAmt" class="form-label">Margin Amount</label>
                                    <input type="text" class="form-control" id="inputMarginAmt" name="inputMarginAmt">
                                </div>
                                <div class="col-12">
                                    <label for="inputDiscount" class="form-label">Discount</label>
                                    <input type="text" class="form-control" id="inputDiscount" name="inputDiscount">
                                </div>
                                <div class="col-12">
                                    <label for="inputPresentMrp" class="form-label">Present MRP</label>
                                    <input type="text" class="form-control" id="inputPresentMrp" name="inputPresentMrp">
                                </div>
                                <div class="col-12">
                                    <label for="inputMargin" class="form-label">Margin</label>
                                    <input type="text" class="form-control" id="inputMargin" name="inputMargin">
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
