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
            <div class="col-lg-12">
                <div class="col-lg-6">

                    <div class="card"  style="width: 600px;">
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
                                    <input type="text" class="form-control" id="inputRpoutput" name="inputRpoutput" hidden>

                                    <label for="inputRmcost" class="form-label">RM Cost/Unit(A)</label>
                                    <input type="text" class="form-control mb-2" id="inputRmcost" name="inputRmcost" readonly>
                                </div>
                                <div class="col-12">
                                    <label for="inputPmcost" class="form-label">PM Cost/Unit(B)</label>
                                    <input type="text" class="form-control mb-2" id="inputPmcost" name="inputPmcost" readonly>
                                </div>
                                <div class="col-12">
                                    <label for="inputRmPmcost" class="form-label">RM & PM Cost(A+B)</label>
                                    <input type="text" class="form-control mb-2" id="inputRmPmcost" name="inputRmPmcost" readonly>
                                </div>
                                <div class="col-12">
                                    <label for="inputOverhead" class="form-label">Overhead(C)</label>
                                    <input type="text" class="form-control mb-2" id="inputOverhead" name="inputOverhead" readonly>
                                </div>

                           <!-- <div class="col">
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
                            </div>-->
                               <!-- <div class="col-12">
                                    <label for="inputOhAmt" class="form-label">Overhead Amount D</label>
                                    <input type="text" class="form-control" id="inputOhAmt" name="inputOhAmt">
                                </div>-->
                                <div class="col-12">
                                    <label for="inputTotalCost" class="form-label">Total cost(A+B+C)</label>
                                    <input type="text" class="form-control mb-2" id="inputTotalCost" name="inputTotalCost" readonly>
                                </div>
                                <div class="col-12">
                                    <label for="inputMargin" class="form-label">Margin</label>
                                    <input type="text" class="form-control mb-2" id="inputMargin" name="inputMargin">
                                </div>
                            </div>
                            <div class="col">
                                <div class="col-12">
                                    <label for="inputMarginAmt" class="form-label">Margin Amount</label>
                                    <input type="text" class="form-control mb-2" id="inputMarginAmt" name="inputMarginAmt" readonly>
                                </div>
                                <div class="col-12">
                                    <label for="inputTax" class="form-label">Tax</label>
                                    <input type="text" class="form-control mb-2" id="inputTax" name="inputTax" readonly>
                                </div>
                                <div class="col-12">
                                    <label for="inputDiscount" class="form-label">Discount</label>
                                    <input type="text" class="form-control" id="inputDiscount" name="inputDiscount">
                                    <div id="DiscountAmt" class="mb-2" style="color:blue;"></div>
                                </div>
                                <div class="col-12">
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
        const permarginInput = document.getElementById('inputMargin'); // margin-25
        const MarginAmt = document.getElementById('inputMarginAmt');
        const Discount = document.getElementById('inputDiscount'); // discount 33.33
        const pertaxInput = document.getElementById('inputTax');
        const suggRate = document.getElementById('inputSuggRate');
        const suggRatebftax = document.getElementById('inputSuggRatebf');
        const suggestedMrp = document.getElementById('inputSuggMrp');
        const recipeOutput = document.getElementById('inputRpoutput');
        let discountAmt = document.getElementById('DiscountAmt');

    //    const totalRmCost = 0;
    //    const totalPmCost = 0;
    //    const totalOhCost = 0;

        let permargin = 25; // Default margin percentage
        let perdiscount = 33.33; // Default discount percentage
        let pertax = 18;

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
                recipeOutput.value = data.rpoutput;
                pertaxInput.value = pertax;
                permarginInput.value = permargin;
                Discount.value = perdiscount.toFixed(2);
                updateCalculations(data);
                // discountAmt.style.display = 'block';

                // if (selectedText) {
                //     RmCostA.value = data.rpoutput > 0 ? (data.totalRmCost / data.rpoutput).toFixed(2) : 'N/A';
                //     PmCostB.value = data.rpoutput > 0 ? (data.totalPmCost / data.rpoutput).toFixed(2) : 'N/A';
                //     OhCostC.value = data.rpoutput > 0 ? (data.totalOhCost / data.rpoutput).toFixed(2) : 'N/A';

                //     RmPmCost.value = (parseFloat(data.totalRmCost) + parseFloat(data.totalPmCost)).toFixed(2);
                //     TotalCost.value = (parseFloat(data.totalRmCost) + parseFloat(data.totalPmCost) + parseFloat(data.totalOhCost)).toFixed(2);

                //     MarginAmt.value = (parseFloat(TotalCost.value) * permargin / 100).toFixed(2);
                //     margin_Total = (parseFloat(TotalCost.value) + parseFloat(MarginAmt.value)).toFixed(2);

                //     tax_amt = (parseFloat(margin_Total) * pertax / 100).toFixed(2);
                //     tax_Total = (parseFloat(margin_Total) + parseFloat(tax_amt)).toFixed(2);

                //     disc_amt = (parseFloat(tax_Total) * perdiscount / 100).toFixed(2);
                //     discount_Total = (parseFloat(tax_Total) + parseFloat(disc_amt)).toFixed(2);
                //     Discount.value = parseFloat(disc_amt).toFixed(2);

                //     netTotal = parseFloat(discount_Total).toFixed(2);
                //     suggRate.value = data.rpoutput > 0 ? (parseFloat(TotalCost.value) / data.rpoutput).toFixed(2) : 'N/A';
                //     suggRatebftax.value = data.rpoutput > 0 ? (parseFloat(margin_Total) / data.rpoutput).toFixed(2) : 'N/A';
                //     suggestedMrp.value = data.rpoutput > 0 ? (parseFloat(netTotal) / data.rpoutput).toFixed(2) : 'N/A';
                // }

                }
                else{ alert(data.error); }

            } catch (error) {
                console.error(error);
                alert("Error fetching cost");
            }
        }
    }

    function updateCalculations(data) {
        if (!data) return;

        RmCostA.value = data.rpoutput > 0 ? (data.totalRmCost / data.rpoutput).toFixed(2) : 'N/A';
        PmCostB.value = data.rpoutput > 0 ? (data.totalPmCost / data.rpoutput).toFixed(2) : 'N/A';
        OhCostC.value = data.rpoutput > 0 ? (data.totalOhCost / data.rpoutput).toFixed(2) : 'N/A';

        RmPmCost.value = (parseFloat(RmCostA.value) + parseFloat(PmCostB.value)).toFixed(2);
        TotalCost.value = (parseFloat(RmCostA.value) + parseFloat(PmCostB.value) + parseFloat(OhCostC.value)).toFixed(2);
        // Recalculate margin
        MarginAmt.value = (parseFloat(TotalCost.value) * permargin / 100).toFixed(2);
        let margin_Total = (parseFloat(TotalCost.value) + parseFloat(MarginAmt.value)).toFixed(2);

        // Recalculate tax
        let pertax = parseFloat(pertaxInput.value) || 0;
        let tax_amt = (parseFloat(margin_Total) * pertax / 100).toFixed(2);
        let tax_Total = (parseFloat(margin_Total) + parseFloat(tax_amt)).toFixed(2);

        // Recalculate discount
        let disc_amt = (parseFloat(tax_Total) * perdiscount / 100).toFixed(2);
        let discount_Total = (parseFloat(tax_Total) + parseFloat(disc_amt)).toFixed(2);
        discountAmt.innerHTML = "Discount Amount: "+ disc_amt; //parseFloat(disc_amt).toFixed(2);

        // Final calculations
        let netTotal = parseFloat(discount_Total).toFixed(2);
        suggRate.value = parseFloat(recipeOutput.value) > 0 ? (parseFloat(TotalCost.value)).toFixed(2) : 'N/A';
        suggRatebftax.value = parseFloat(recipeOutput.value) > 0 ? (parseFloat(margin_Total)).toFixed(2) : 'N/A';
        suggestedMrp.value = parseFloat(recipeOutput.value) > 0 ? (parseFloat(netTotal)).toFixed(2) : 'N/A';
    }

    // **Call updateCalculations when tax input changes**
    pertaxInput.addEventListener('change', () => {
        pertax = parseFloat(pertaxInput.value) || 0; // Update margin percentage
        console.log(`Tax updated: ${pertax}%`);
        calculate();
    });

    // **Call updateCalculations when margin input changes**
    permarginInput.addEventListener('change', () => {
        permargin = parseFloat(permarginInput.value) || 0; // Update margin percentage
        console.log(`Margin updated: ${permargin}%`);
        calculate();
    });

     // **Call updateCalculations when tax input changes**
     Discount.addEventListener('change', () => {
        perdiscount = parseFloat(Discount.value) || 0; // Update margin percentage
        console.log(`Discount updated: ${perdiscount}%`);
        calculate();
    });

    function calculate() {
        // Trigger the recalculation of margin-related values based on updated margin
         // Recalculate margin
         MarginAmt.value = (parseFloat(TotalCost.value) * permargin / 100).toFixed(2);
        let margin_Total = (parseFloat(TotalCost.value) + parseFloat(MarginAmt.value)).toFixed(2);

        let marginValue = parseFloat(MarginAmt.value);
        if (isNaN(marginValue)) marginValue = 0;

        let marginTotal = (parseFloat(TotalCost.value) + parseFloat(MarginAmt.value)).toFixed(2);
        let taxAmt = (parseFloat(marginTotal) * pertax / 100).toFixed(2);
        let taxTotal = (parseFloat(marginTotal) + parseFloat(taxAmt)).toFixed(2);

        let discAmt = (parseFloat(taxTotal) * perdiscount / 100).toFixed(2);
        let discountTotal = (parseFloat(taxTotal) + parseFloat(discAmt)).toFixed(2);
        discountAmt.innerHTML = discAmt; //parseFloat(discAmt).toFixed(2);

        console.log(recipeOutput.value);
        let netTotal = parseFloat(discountTotal).toFixed(2);
        suggRate.value = parseFloat(recipeOutput.value) > 0 ? (parseFloat(TotalCost.value)).toFixed(2) : 'N/A';
        suggRatebftax.value = parseFloat(recipeOutput.value) > 0 ? (parseFloat(margin_Total)).toFixed(2) : 'N/A';
        suggestedMrp.value = parseFloat(recipeOutput.value) > 0 ? (parseFloat(netTotal)).toFixed(2) : 'N/A';
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
