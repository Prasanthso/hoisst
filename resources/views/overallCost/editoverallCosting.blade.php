@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>View Overall Costing</h1>
        <div class="d-flex justify-content-end mb-2 action-buttons">
            <button class="btn btn-sm edit-table-btn me-2" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;" id="editButton">
                <i class="fas fa-edit" style="color: black;"></i>
            </button>
           <!-- <button class="btn btn-sm delete-table-btn" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;" id="deleteButton" style="display: none;">
                <i class="fas fa-trash" style="color: red;"></i>
            </button>-->
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
                                    <input type="text" class="form-control mb-2" id="inputRmcost" name="inputRmcost" value="{{ number_format($rmCost, 2) }}" disabled readonly>
                                </div>
                                <div class="col-12">
                                    <label for="inputPmcost" class="form-label">PM Cost/Unit(B)</label>
                                    <input type="text" class="form-control mb-2" id="inputPmcost" name="inputPmcost" value="{{ number_format($pmCost, 2) }}" disabled readonly>
                                </div>
                                <div class="col-12">
                                    <label for="inputRmPmcost" class="form-label">RM & PM Cost(A+B)</label>
                                    <input type="text" class="form-control mb-2" id="inputRmPmcost" name="inputRmPmcost" value="{{ number_format(($rmCost ?? 0) + ($pmCost ?? 0), 2) }}" disabled readonly>
                                </div>
                                <div class="col-12">
                                    <label for="inputOverhead" class="form-label">Overhead(C)</label>
                                    <input type="text" class="form-control mb-2" id="inputOverhead" name="inputOverhead" value="{{ number_format($ohCost, 2) }}" disabled readonly>
                                </div>

                          <!--  <div class="col">
                                <div class="col-12">
                                    <label for="inputRmSgmrp" class="form-label">RM(%) Suggested MRP</label>
                                    <input type="text" class="form-control" id="inputRmSgmrp" name="inputRmSgmrp" value="" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputPmSgmrp" class="form-label">PM(%) Suggested MRP</label>
                                    <input type="text" class="form-control" id="inputPmSgmrp" name="inputPmSgmrp" value="" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputSgMrp" class="form-label"> Suggested MRP </label>
                                    <input type="text" class="form-control" id="inputSgMrp" name="inputSgMrp" value="" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputSgMargin" class="form-label"> Suggested Margin </label>
                                    <input type="text" class="form-control" id="inputSgMargin" name="inputSgMargin" value="" disabled>
                                </div>
                            </div>-->
                                <!--<div class="col-12">
                                    <label for="inputOhAmt" class="form-label">Overhead Amount D</label>
                                    <input type="text" class="form-control" id="inputOhAmt" name="inputOhAmt" value="" disabled>
                                </div>-->
                                <div class="col-12 mb-2">
                                    <input type="hidden" class="form-control mb-2" name="productType" id="productType">
                                    <label for="inputTotalCost" class="form-label">Total cost</label>
                                    <input type="text" class="form-control mb-2" id="inputTotalCost" name="inputTotalCost" value="{{ number_format(($rmCost ?? 0) + ($pmCost ?? 0) + ($ohCost ?? 0), 2) }}" disabled readonly>
                                </div>

                                </div>
                                <div class="col">
                                <div class="col-12">
                                        <label for="inputMargin" class="form-label">Margin</label>
                                        <input type="text" class="form-control mb-2" id="inputMargin" name="inputMargin" value="{{ $costing->margin}}" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputMarginAmt" class="form-label">Margin Amount</label>
                                    <input type="text" class="form-control mb-2" id="inputMarginAmt" name="inputMarginAmt" value="{{ $costing->margin_amt }}" disabled readonly>
                                </div>
                                <div class="col-12">
                                    <label for="inputTax" class="form-label">Tax</label>
                                    <input type="text" class="form-control mb-2" id="inputTax" name="inputTax" value="{{ $producttax }}" readonly disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputDiscount" class="form-label">Discount</label>
                                    <input type="text" class="form-control" id="inputDiscount" name="inputDiscount" value="{{ $costing->discount }}" disabled>
                                    <div id="DiscountAmt" class="mb-2" style="color:blue;"></div>
                                </div>
                                <div class="col-12" hidden>
                                    <label for="inputSuggRate" class="form-label">Suggested Rate</label>
                                    <input type="text" class="form-control mb-2" id="inputSuggRate" name="inputSuggRate" value="{{ number_format(($rmCost ?? 0) + ($pmCost ?? 0) + ($ohCost ?? 0), 2) }}" disabled readonly>
                                </div>
                                <div class="col-12">
                                    <label for="inputSuggMrp" class="form-label">Suggested MRP</label>
                                    <input type="text" class="form-control mb-2" id="inputSuggMrp" name="inputSuggMrp" value="{{ $costing->suggested_mrp }}" disabled readonly>
                                </div>
                                <div class="col-12" hidden>
                                    <label for="inputSuggRatebf" class="form-label">Suggested Rate before tax</label>
                                    <input type="text" class="form-control mb-2" id="inputSuggRatebf" name="inputSuggRatebf" value="{{ $costing->sugg_rate_bf }}" disabled readonly>
                                </div>
                            </div>
                            </div>
                                <div>
                                    <button type="submit" class="btn btn-primary" id="saveButton" style="display: none;">
                                        Update
                                    </button>
                                    <input type="hidden" id="inputRpoutput" value="{{ $rpoutput }}">
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
    //     const totalRmCost = 0;
    //    const totalPmCost = 0;
    //    const totalOhCost = 0;

        let permargin = parseFloat(permarginInput.value) || 0;
        let perdiscount = parseFloat(Discount.value) || 0;
        let pertax = parseFloat(pertaxInput.value) || 0;

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

        calculate();

        // $('#recipeSelect').on('input', function () {
        //     const selectedValue = $(this).val();
        //     console.log("Selected value:", selectedValue);
        //     if (selectedValue) {
        //         recipedata(selectedValue);
        //     } else {
        //         console.log("No recipe selected.");
        //     }
        // });

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
                updateCalculations(data);
                toVisiable(data);
                    // if(selectedText != null)
                    // {
                    //     RmCostA.value = data.rpoutput !== 0 ? (data.totalRmCost / data.rpoutput).toFixed(2) : 0;
                    //     PmCostB.value = data.rpoutput !== 0 ? (data.totalPmCost / data.rpoutput).toFixed(2) : 0;
                    //     OhCostC.value = data.rpoutput !== 0 ? (data.totalOhCost / data.rpoutput).toFixed(2) : 0;

                    //     RmPmCost.value = (parseFloat(data.totalRmCost) + parseFloat(data.totalPmCost)).toFixed(2);
                    //    TotalCost.value = (parseFloat(data.totalRmCost) + parseFloat(data.totalPmCost) + parseFloat(data.totalOhCost)).toFixed(2);
                    //    Margin.value =  (parseFloat(TotalCost.value) * permargin / 100).toFixed(2);
                    //    Discount.value =  (parseFloat(TotalCost.value) * perdiscount / 100).toFixed(2);
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
        console.log(RmCostA, PmCostB, OhCostC);

        // Ensure numerical values before calculations
        let rmCost = parseFloat(RmCostA.value) || 0;
        let pmCost = parseFloat(PmCostB.value) || 0;
        let ohCost = parseFloat(OhCostC.value) || 0;

        // Calculate Costs
        RmPmCost.value = (rmCost + pmCost).toFixed(2);

        if(data.itemtype == 'Trading')
        {
            console.log("Trading item price :",parseFloat(data.tradingCost));
            document.getElementById('productType').value = data.itemtype;
            TotalCost.value = parseFloat(data.tradingCost).toFixed(2) || 0;
        }
        else{
            TotalCost.value = (rmCost + pmCost + ohCost).toFixed(2);
        }

        // Recalculate margin
        let totalCostNum = parseFloat(TotalCost.value);
        let marginAmount = (totalCostNum * permargin / 100).toFixed(2);
        MarginAmt.value = marginAmount;

        let margin_Total = (totalCostNum + parseFloat(marginAmount)).toFixed(2);

        // Recalculate tax
        let pertax = parseFloat(pertaxInput.value) || 0;
        let tax_amt = (parseFloat(margin_Total) * pertax / 100).toFixed(2);
        let tax_Total = (parseFloat(margin_Total) + parseFloat(tax_amt)).toFixed(2);

        // Recalculate discount
        let disc_amt = (parseFloat(tax_Total) * perdiscount / 100).toFixed(2);
        let discount_Total = (parseFloat(tax_Total) + parseFloat(disc_amt)).toFixed(2);
        discountAmt.innerHTML = "Discount Amount: " + disc_amt;

        // Final calculations
        let netTotal = parseFloat(discount_Total).toFixed(2);
        let recipeOut = parseFloat(recipeOutput.value) || 0;

        suggRate.value = recipeOut > 0 ? totalCostNum.toFixed(2) : 'N/A';
        suggRatebftax.value = recipeOut > 0 ? margin_Total : 'N/A';
        suggestedMrp.value = recipeOut > 0 ? netTotal : 'N/A';
    }


    function toVisiable(data)
        {
            if (!data) return;

            if(data.itemtype == 'Trading')
            {
                document.getElementById("lblinputRmcost").style.display = "none";
                document.getElementById("lblinputPmcost").style.display = "none";
                document.getElementById("lblinputRmPmcost").style.display = "none";
                document.getElementById("lblinputOverhead").style.display = "none";
                RmCostA.style.display = "none";
                PmCostB.style.display = "none";
                RmPmCost.style.display = "none";
                OhCostC.style.display  = "none";
            }
            else{
                document.getElementById("lblinputRmcost").style.display = "block";
                document.getElementById("lblinputPmcost").style.display = "block";
                document.getElementById("lblinputRmPmcost").style.display = "block";
                document.getElementById("lblinputOverhead").style.display = "block";
                RmCostA.style.display = "block";
                PmCostB.style.display = "block";
                RmPmCost.style.display = "block";
                OhCostC.style.display  = "block";
            }
        }

/*
    function updateCalculations(data) {
        if (!data) return;

        RmCostA.value = recipeOutput > 0 ? (data.totalRmCost / recipeOutput).toFixed(2) : 'N/A';
        PmCostB.value = recipeOutput > 0 ? (data.totalPmCost / recipeOutput).toFixed(2) : 'N/A';
        OhCostC.value = recipeOutput > 0 ? (data.totalOhCost / recipeOutput).toFixed(2) : 'N/A';
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
        discountAmt.innerHTML = "Discount Amount: "+ disc_amt;

        // console.log(recipeOutput.value);
        // Final calculations
        let netTotal = parseFloat(discount_Total).toFixed(2);
        suggRate.value = parseFloat(recipeOutput.value) > 0 ? (parseFloat(TotalCost.value)).toFixed(2) : 'N/A';
        suggRatebftax.value = parseFloat(recipeOutput.value) > 0 ? (parseFloat(margin_Total)).toFixed(2) : 'N/A';
        suggestedMrp.value = parseFloat(recipeOutput.value) > 0 ? (parseFloat(netTotal)).toFixed(2) : 'N/A';
        // sellRate.value = parseFloat(recipeOutput.value) > 0 ? (parseFloat(TotalCost.value) / parseFloat(recipeOutput.value)).toFixed(2) : 'N/A';
        // sellRatebftax.value = parseFloat(recipeOutput.value) > 0 ? (parseFloat(margin_Total) / parseFloat(recipeOutput.value)).toFixed(2) : 'N/A';
        // presentMrp.value = parseFloat(recipeOutput.value) > 0 ? (parseFloat(netTotal) / parseFloat(recipeOutput.value)).toFixed(2) : 'N/A';
    }
*/
     // **Call updateCalculations when margin input changes**
     permarginInput.addEventListener('change', () => {
        let permargin = parseFloat(permarginInput.value) || 0; // Update margin percentage
        console.log(`Margin updated: ${permargin}%`);
        calculate();
    });

    // // **Call updateCalculations when tax input changes**
    // pertaxInput.addEventListener('change', () => {
    //         pertax = parseFloat(pertaxInput.value) || 0; // Update margin percentage
    //         console.log(`Tax updated: ${pertax}%`);
    //         calculate();
    //     });

  // **Call updateCalculations when Discount input changes**
  Discount.addEventListener('change', () => {
       let perdiscount = parseFloat(Discount.value) || 0; // Update margin percentage
        console.log(`Discount updated: ${perdiscount}%`);
        calculate();
    });

    function calculate() {
    let totalCost = parseFloat(TotalCost.value) || 0;
    let permargin = parseFloat(permarginInput.value) || 0;
    let pertax = parseFloat(pertaxInput.value) || 0;
    let perdiscount = parseFloat(Discount.value) || 0;

    // Calculate Margin Amount
    let marginAmt = (totalCost * permargin / 100).toFixed(2);
    let marginTotal = (totalCost + parseFloat(marginAmt)).toFixed(2);

    // Calculate Tax Amount
    let taxAmt = (parseFloat(marginTotal) * pertax / 100).toFixed(2);
    let taxTotal = (parseFloat(marginTotal) + parseFloat(taxAmt)).toFixed(2);

    // Calculate Discount Amount
    let discAmt = (parseFloat(taxTotal) * perdiscount / 100).toFixed(2);
    let netTotal = (parseFloat(taxTotal) + parseFloat(discAmt)).toFixed(2);

    // Update UI Elements
    MarginAmt.value = marginAmt;
    // pertaxInput.value = taxAmt;
    discountAmt.innerHTML = discAmt;
    suggestedMrp.value = netTotal;

    // Debugging Logs
    console.log("Total Cost:", totalCost);
    console.log("Margin Amount:", marginAmt);
    console.log("Margin Total:", marginTotal);
    console.log("Tax Amount:", taxAmt);
    console.log("Tax Total:", taxTotal);
    console.log("Discount Amount:", discAmt);
    console.log("Final Suggested MRP:", netTotal);
}


/*
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
        discountAmt.innerHTML = discAmt;;
        // console.log(recipeOutput.value);
        let netTotal = parseFloat(discountTotal).toFixed(2);
        suggRate.value = parseFloat(recipeOutput.value) > 0 ? (parseFloat(TotalCost.value)).toFixed(2) : 'N/A';
        suggRatebftax.value = parseFloat(recipeOutput.value) > 0 ? (parseFloat(margin_Total)).toFixed(2) : 'N/A';
        suggestedMrp.value = parseFloat(recipeOutput.value) > 0 ? (parseFloat(netTotal)).toFixed(2) : 'N/A';
    }
*/
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
