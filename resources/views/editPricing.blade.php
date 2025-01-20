@extends('layouts.headerNav')

@section('content')
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Edit Pricing</h1>
        <div class="row">
            <!-- Action Buttons -->
        </div>
    </div>

    <section class="section dashboard">
        <div class="container mt-5">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="mb-4">
                <label for="productSelect" class="form-label">Select Product</label>
                <div class="col-6">
                    <select id="productSelect" class="form-select" name="productSelect" aria-labelledby="productSelect">
                        <option value="" disabled selected>Select a Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" selected disabled>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 col-sm-10 mb-2">
                    <label for="recipeOutput" class="form-label">Output</label>
                    <input type="text" class="form-control rounded" id="recipeOutput" name="recipeOutput" value="{{ $pricingData->output }}">
                </div>
                <div class="col-md-2 col-sm-10">
                    <label for="recipeUoM" class="form-label">UoM</label>
                    <select id="recipeUoM" class="form-select" name="recipeUoM">
                        <option value="" disabled  selected> UoM </option>
                        @foreach ($pricingData as $rpuom)
                        <option value="{{ $rpuom->uom }}" selected disabled>
                            {{ $rpuom->uom }}
                        </option>
                    @endforeach
                        {{-- <option value="Ltr">Ltr</option>
                        <option value="Kgs">Kgs</option> {{ $pricingData->uom }}
                        <option value="Nos">Nos</option> --}}
                    </select>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-auto">
                    <label for="pricingrawmaterial" class="form-label text-primary" name="pricingrawmaterial" id="pricingrawmaterial">Raw Material</label>
                </div>
                <div class="col">
                    <hr />
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
            @endif

            <div class="row mb-2">
                <div class="col-auto">
                    <label for="pricingpackingmaterial" class="form-label text-primary" id="pricingpackingmaterial">Packing Material</label>
                </div>
                <div class="col">
                    <hr />
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

            <div class="row mb-2">
                <div class="col-auto">
                    <label for="pricingoverheads" class="form-label text-primary" id="pricingoverheads">Overheads</label>
                </div>
                <div class="col-2 form-check">
                    <input type="checkbox" class="form-check-input" id="frommasters"> <label class="form-check-label" for="frommasters"> From Masters </label>
                </div>
                <div class="col-2 form-check">
                    <input type="checkbox" class="form-check-input" id="entermanually"> <label class="form-check-label" for="entermanually"> Enter Manually </label>
                </div>
                <div class="col">
                    <hr />
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
                    <input type="text" class="form-control" id="totalcost" disabled>
                </div>
            </div>

        </div>
    </section>
</main>
@endsection
