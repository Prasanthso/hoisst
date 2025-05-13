@extends('layouts.headerNav')

@section('content')
<main id="main" class="main">
    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Recipe Costing</h1>
        <div class="d-flex align-items-center">
            <!-- Action Buttons -->
            <!--<div class="d-flex justify-content-end mb-2 action-buttons">-->
            <button class="btn btn-sm me-2 edit-table-btn" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                <i class="fas fa-edit" style="color: black;"></i>
            </button>
            <button class="btn btn-sm delete-table-btn" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                <i class="fas fa-trash" style="color: red;"></i>
            </button>
            <a href="{{ 'pricing' }}" class='text-decoration-none ps-add-btn text-white py-1 px-4'>
                <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
            </a>
            <!-- </div>-->
        </div>
    </div>

    <section class="section dashboard">
        <div class="container mt-5">
            <div class="mb-2">
                <label for="productSelect" class="form-label">Select Recipe</label>
                <div class="row align-items-center">
                    <div class="col-8">
                        <form action="{{ route('receipepricing.form') }}" method="GET" class="d-flex">
                            <select id="productSelect" class="form-select me-2" name="product_id" aria-labelledby="productSelect">
                                <option selected disabled>Choose...</option>
                                @foreach($products as $productItem)
                                <option value="{{ $productItem->id }}" @if(request('product_id')==$productItem->id) selected @endif>
                                    {{ $productItem->name }}
                                </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary me-2" id="Submitbtn" hidden>Submit</button>
                            {{-- <a href="{{ 'pricing' }}" class="btn btn-primary">Add</a> --}}
                        </form>
                    </div>
                    <!-- <div class="col-auto">

                    </div> -->
                </div>
            </div>

            @if(isset($pricingData) && $pricingData->isNotEmpty())
            @php
            $rpoutput = $pricingData->first()->rp_output ?? 'N/A';
            $rpuom = $pricingData->first()->rp_uom ?? 'N/A';
            @endphp
            <div class="d-flex align-items-center mb-2">
                <strong>Output :</strong> <span class="ms-2">{{ $rpoutput }}</span>
                <strong class="ms-4">UoM :</strong> <span class="ms-2">{{ $rpuom }}</span>
            </div>
            <div class="row mb-4">
                <!-- Raw Materials Table -->
                <div class="table-responsive">
                    <table class="table table-bordered text-center" style="background-color: #eaf8ff; width:90%;">
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
                            @php $rmTotal = 0;
                            $filteredData = collect($pricingData)->unique('rid')->values();
                            @endphp
                            @foreach($filteredData as $data)
                            @if($data->rm_name)
                            @php
                            $amount = $data->rm_quantity * $data->rm_price;
                            $rmTotal += $amount;
                            @endphp
                            <tr>
                                <td>{{ $data->rm_name }}</td>
                                <td>{{ $data->rm_quantity }}</td>
                                <td>{{ $data->rm_code }}</td>
                                <td>{{ $data->rm_uom ?? 'N/A' }}</td>
                                <td>{{ $data->rm_price }}</td>
                                <td>{{ $amount }}</td>
                            </tr>
                            @endif
                            @endforeach

                            @if($pricingData->whereNotNull('rm_name')->isEmpty())
                            <tr>
                                <td colspan="6">No records available for Raw Materials</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="text-end" style="background-color: #eaf8ff; width:90%;">
                        <strong>RM Cost (A) : </strong> <span id="totalRmCost">{{ $rmTotal }} </span>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <!-- Packing Materials Table -->
                <div class="table-responsive">
                    <table class="table table-bordered text-center" style="background-color: #F1F1F1; width:90%;">
                        <thead>
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
                            @php
                            $filteredData = collect($pricingData)->unique('pid')->values();
                            $pmTotal = 0; @endphp
                            @foreach($filteredData as $data)
                            @if($data->pm_name)
                            @php
                            $amount = $data->pm_quantity * $data->pm_price;
                            $pmTotal += $amount;
                            @endphp
                            <tr>
                                <td>{{ $data->pm_name }}</td>
                                <td>{{ $data->pm_quantity }}</td>
                                <td>{{ $data->pm_code }}</td>
                                <td>{{ $data->pm_uom ?? 'N/A' }}</td>
                                <td>{{ $data->pm_price }}</td>
                                <td>{{ $data->pm_quantity * $data->pm_price }}</td>
                            </tr>
                            @endif
                            @endforeach

                            @if($pricingData->whereNotNull('pm_name')->isEmpty())
                            <tr>
                                <td colspan="6">No records available for Packing Materials</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="text-end" style="background-color:#F1F1F1; width:90%;">
                        <strong>PM Cost (B) : </strong> <span id="totalPmCost">{{ $pmTotal }}</span>
                    </div>
                </div>
            </div>
            @php
            $ohTotal = 0;
            $mohTotal = 0;
            @endphp
            @if($pricingData->whereNotNull('oh_name')->isNotEmpty())
            <div class="row mb-4">
                <!-- Overheads Table -->

                <div class="table-responsive">
                    <table class="table table-bordered text-center" style="background-color: #D7E1E4; width:90%;">
                        <thead>
                            <tr>
                                <th>Overheads</th>
                                <th>Quantity</th>
                                <th>OH Code</th>
                                <th>UoM</th>
                                <th>Price</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody id="overheadsTable">
                            @php $ohTotal = 0;
                            $filteredData = collect($pricingData)->unique('ohid')->values();
                            @endphp
                            @foreach($filteredData as $data)
                            @if($data->oh_name)
                            @php
                            $amount = $data->oh_quantity * $data->oh_price;
                            $ohTotal += $amount;
                            $mohTotal = 0;
                            @endphp
                            <tr>
                                <td>{{ $data->oh_name }}</td>
                                <td>{{ $data->oh_quantity }}</td>
                                <td>{{ $data->oh_code }}</td>
                                <td>{{ $data->oh_uom ?? 'N/A' }}</td>
                                <td>{{ $data->oh_price }}</td>
                                <td>{{ $amount }}</td>
                            </tr>
                            @endif
                            @endforeach

                            @if($pricingData->whereNotNull('oh_name')->isEmpty())
                            <tr>
                                <td colspan="6">No records available for Overheads</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="text-end" style="background-color:#F1F1F1; width:90%;">
                        <strong>OH Cost (C) : </strong> <span id="totalohCost">{{ $ohTotal }}</span>
                    </div>
                </div>
            </div>
            @endif
            @if($pricingData->whereNotNull('moh_name')->isNotEmpty())
            <div class="row mb-4">
                <!-- Overheads Table -->

                <div class="table-responsive">
                    <table class="table table-bordered text-center" style="background-color: #D7E1E4; width:90%;">
                        <thead>
                            <tr>
                                <th>Overheads</th>
                                <th>Quantity</th>
                                <th>OH Code</th>
                                <th>UoM</th>
                                <th>Percentage(%)</th>
                                <th>Price/Amount</th>
                            </tr>
                        </thead>
                        <tbody id="overheadsTable">
                            @php $mohTotal = 0;
                            $filteredData = collect($pricingData)->unique('mohid')->values();
                            @endphp
                            @foreach($filteredData as $data)
                            @if($data->moh_name)
                            @php
                            $amount = $data->moh_price;
                            $mohTotal += $amount;
                            $ohTotal = 0;
                            @endphp
                            <tr>
                                <td>{{ $data->moh_name }}</td>
                                <td> - </td>
                                <td> - </td>
                                <td> - </td>
                                <td>{{ $data->moh_percentage }}</td>
                                <td>{{ $amount }}</td>
                            </tr>
                            @endif
                            @endforeach

                            @if($pricingData->whereNotNull('moh_name')->isEmpty())
                            <tr>
                                <td colspan="6">No records available for Overheads</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="text-end" style="background-color:#F1F1F1; width:90%;">
                        <strong>OH Cost (C) : </strong> <span id="totalohCost">{{ $mohTotal }}</span>
                    </div>
                </div>
            </div>
            @endif

            <div class="d-flex justify-content-between">
                <div class=" mb-2">
                    <div class="mt-2">
                        <label for="totalcost" class="form-label">Total Cost (A+B+C):
                    </div>
                    <div>
                        <input type="text" class="form-control" id="totalcost" value="{{ ($rmTotal ?: 0) + ($pmTotal ?: 0) + ($ohTotal ?: 0) + ($mohTotal ?: 0) }}" disabled>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="mt-2">
                        <label for="unitcost" class="form-label">Unit Cost:</label>
                    </div>
                    <div class="col-md-7">
                        <input type="text" class="form-control" id="totalcost" value="{{ $data->rp_output ? round(($rmTotal + $pmTotal + $ohTotal+ $mohTotal) / $data->rp_output, 2) : 0 }}" disabled>
                    </div>
                </div>
            </div>

            {{-- @else
            <p>No pricing recipe selected.</p> --}}
            @endif
        </div>
        <div>
            <form action="{{ route('receipepricing.delete') }}" method="POST" id="deleteForm" style="display: none;">
                @csrf
                @method('DELETE')
                <input type="hidden" name="product_id" id="product_id_to_delete">
            </form>
        </div>
    </section>
</main>
@endsection

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
<script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#productSelect').select2({
            theme: 'bootstrap-5',
            placeholder: "Type or select a recipe...",
        });

        // Hide the submit button initially
        // $('#Submitbtn').hide();
        // Listen for change event on the select element
        $('#productSelect').on('change', function() {
            const selectedValue = $(this).val();

            if (selectedValue) {
                // Submit the form automatically
                $(this).closest('form').submit(); // Submit the form
                console.log("Form submitted automatically.");
            } else {
                console.log("No recipe selected.");
            }
        });

        // edit products//
        document.querySelector('.edit-table-btn')?.addEventListener('click', function() {
            const productId = document.getElementById('productSelect').value;

            if (productId && productId !== 'Choose...') {
                // Redirect to the edit route with the selected product ID
                window.location.href = `/edit-pricing/${productId}`;
            } else {
                alert('Please select a product to edit.');
            }
        });

        // delete products//
        document.querySelector('.delete-table-btn')?.addEventListener('click', async function() {
            var productId = document.getElementById('productSelect').value.trim();
            console.log(productId);
            if (productId === 'Choose...') {
                alert('Please select a product before deleting.');
                return;
            }
            try {
                // AJAX request to check if the product exists in overall_costing
                let response = await fetch(`/check-product-exists?productId=${productId}`);
                let data = await response.json();
                console.log("Server Response:", data);
                if (data.exists) {
                    alert('Recipe-Pricing data might be in use and cannot be deleted.');
                    return;
                }
                // if (productId) {
                // Show confirmation dialog
                if (confirm('Are you sure you want to delete the pricing data for this product?')) {
                    // Set the product ID to the hidden input
                    document.getElementById('product_id_to_delete').value = productId;

                    // Submit the form
                    document.getElementById('deleteForm').submit();
                } else {
                    // User canceled the action
                    console.log('Delete action canceled by user.');
                }
                // } else {
                //     alert('Please select a product before deleting.');
                // }
            } catch (error) {
                console.error('Error checking product existence:', error);
                alert('Something went wrong. Please try again.');
            }
        });

    });
</script>

<!-- <script>
    document.getElementById('productSelect').addEventListener('change', function () {
        // Get the selected product ID
        const selectedProductId = this.value;

        // Redirect to the route with the selected product ID
        if (selectedProductId) {
            {{-- window.location.href = `{{ route('receipepricing.form') }}?product_id=${selectedProductId}`; --}}
        }
    });
</script> -->
