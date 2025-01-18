@extends('layouts.headerNav')

@section('content')
<main id="main" class="main">
    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Pricing</h1>
    </div>

    <section class="section dashboard">
        <div class="container mt-5">
            <div class="mb-4">
                <label for="productSelect" class="form-label">Select Product</label>
                <div class="row align-items-center">
                    <div class="col-6">
                        <form action="{{ route('receipepricing.form') }}" method="GET" class="d-flex">
                            <select id="productSelect" class="form-select me-2" name="product_id" aria-labelledby="productSelect">
                                <option selected disabled>Choose...</option>
                                @foreach($products as $productItem)
                                <option value="{{ $productItem->id }}" @if(request('product_id') == $productItem->id) selected @endif>
                                    {{ $productItem->name }}
                                </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary me-2">Submit</button>
                            <a href="{{ 'pricing' }}" class="btn btn-primary">Add</a>
                        </form>
                    </div>
                    <!-- <div class="col-auto">

                    </div> -->
                </div>
            </div>

            @if(isset($pricingData) && $pricingData->isNotEmpty())
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
                        <tbody>
                            @foreach($pricingData as $data)
                            @if($data->rm_name)
                            <tr>
                                <td>{{ $data->rm_name }}</td>
                                <td>{{ $data->rm_quantity }}</td>
                                <td>{{ $data->rm_code }}</td>
                                <td>{{ $data->rm_uom ?? 'N/A' }}</td>
                                <td>{{ $data->rm_price }}</td>
                                <td>{{ $data->rm_quantity * $data->rm_price }}</td>
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
                        <tbody>
                            @foreach($pricingData as $data)
                            @if($data->pm_name)
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
                </div>
            </div>

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
                        <tbody>
                            @foreach($pricingData as $data)
                            @if($data->oh_name)
                            <tr>
                                <td>{{ $data->oh_name }}</td>
                                <td>{{ $data->oh_quantity }}</td>
                                <td>{{ $data->oh_code }}</td>
                                <td>{{ $data->oh_uom ?? 'N/A' }}</td>
                                <td>{{ $data->oh_price }}</td>
                                <td>{{ $data->oh_quantity * $data->oh_price }}</td>
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
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-2 mt-2">
                    <label for="totalcost" class="form-label">Total Cost (A+B+C):</label>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="totalcost" value="{{ $totalCost }}" disabled>
                </div>
            </div>
            @else
                <p>No pricing data available for this product.</p>
            @endif
        </div>
    </section>
</main>
@endsection
<!-- <script>
    document.getElementById('productSelect').addEventListener('change', function () {
        // Get the selected product ID
        const selectedProductId = this.value;

        // Redirect to the route with the selected product ID
        if (selectedProductId) {
            window.location.href = `{{ route('receipepricing.form') }}?product_id=${selectedProductId}`;
        }
    });
</script> -->
