@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Overall Costing</h1>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-6">

                    <div class="card">
                        <div class="card-body">

                            <!-- Vertical Form -->
                            <form method="POST" action="{{ route('rawmaterials.store') }}" class="row g-3 mt-2">
                                @csrf
                                <!-- <div class="col-12">
                                    <label for="inputNanme4" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="inputNanme4" name="name">
                                </div> -->
                                <!-- <div class="col-12">
                                    <label for="inputNanme4" class="form-label">RM Code</label>
                                    <input type="text" class="form-control" id="inputNanme4">
                                </div> -->
                                <div class="col-md-12">
                                    <label for="inputState" class="form-label">Choose Recipe</label>
                                    <select id="inputState" class="form-select" name="uom">
                                        <option selected>Select Recipe...</option>
                                        <option>Egg Puff</option>
                                        <option>Samosa</option>
                                        <option>Cake</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <h4>Overall Costing</h4>
                                </div>
                            <div class="row">
                            <div class="col">
                                <div class="col-12">
                                    <label for="inputRmcost" class="form-label">RM Cost/Unit(A)</label>
                                    <input type="text" class="form-control" id="inputRmcost" name="name">
                                </div>
                                <div class="col-12">
                                    <label for="inputPmcost" class="form-label">PM Cost/Unit(A)</label>
                                    <input type="text" class="form-control" id="inputPmcost" name="name">
                                </div>
                                <div class="col-12">
                                    <label for="inputRmPmcost" class="form-label">RM & PM Cost</label>
                                    <input type="text" class="form-control" id="inputRmPmcost" name="name">
                                </div>
                                <div class="col-12">
                                    <label for="inputOverhead" class="form-label">Overhead % C</label>
                                    <input type="text" class="form-control" id="inputOverhead" name="name">
                                </div>
                            </div>
                            <div class="col">
                                <div class="col-12">
                                    <label for="inputRmSgmrp" class="form-label">RM(%) Suggested MRP</label>
                                    <input type="text" class="form-control" id="inputRmSgmrp" name="name">
                                </div>
                                <div class="col-12">
                                    <label for="inputPmSgmrp" class="form-label">PM(%) Suggested MRP</label>
                                    <input type="text" class="form-control" id="inputPmSgmrp" name="name">
                                </div>
                                <div class="col-12">
                                    <label for="inputSgMrp" class="form-label"> Suggested MRP </label>
                                    <input type="text" class="form-control" id="inputSgMrp" name="name">
                                </div>
                                <div class="col-12">
                                    <label for="inputSgMargin" class="form-label"> Suggested Margin </label>
                                    <input type="text" class="form-control" id="inputSgMargin" name="name">
                                </div>
                            </div>
                                <div class="col-12">
                                    <label for="inputOhAmt" class="form-label">Overhead Amount D</label>
                                    <input type="text" class="form-control" id="inputOhAmt" name="name">
                                </div>
                                <div class="col-12">
                                    <label for="inputTotalCost" class="form-label">Total cost J</label>
                                    <input type="text" class="form-control" id="inputTotalCost" name="name">
                                </div>
                                <div class="col-12">
                                    <label for="inputSellRate" class="form-label">Selling Rate</label>
                                    <input type="text" class="form-control" id="inputSellRate" name="name">
                                </div>
                                <div class="col-12">
                                    <label for="inputSellRatebf" class="form-label">Selling Rate before tax</label>
                                    <input type="text" class="form-control" id="inputSellRatebf" name="name">
                                </div>
                                <div class="col-12">
                                    <label for="inputTax" class="form-label">Tax</label>
                                    <input type="text" class="form-control" id="inputTax" name="name">
                                </div>
                                <div class="col-12">
                                    <label for="inputMarginAmt" class="form-label">Margin Amount</label>
                                    <input type="text" class="form-control" id="inputMarginAmt" name="name">
                                </div>
                                <div class="col-12">
                                    <label for="inputDiscount" class="form-label">Discount</label>
                                    <input type="text" class="form-control" id="inputDiscount" name="name">
                                </div>
                                <div class="col-12">
                                    <label for="inputPresentMrp" class="form-label">Present MRP</label>
                                    <input type="text" class="form-control" id="inputPresentMrp" name="name">
                                </div>
                                <div class="col-12">
                                    <label for="inputMargin" class="form-label">Margin</label>
                                    <input type="text" class="form-control" id="inputMargin" name="name">
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
    $(document).ready(function() {
        $('#categorySelect').select2({
            theme: 'bootstrap-5',
            placeholder: 'Choose Categories',
            allowClear: true
        });
    });
</script>


<!--Template Main JS File-->
<script src="{{ asset('js/main.js') }}">
</script>
