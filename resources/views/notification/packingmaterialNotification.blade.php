@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Packing Material Alert Notification</h1>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        @if(count($packingMaterialsPriceThresholdCollection) > 0)
        <div class="card">
            <div class="card-header  bg-dark text-white">Packing Material Price Threshold Alert</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S. No.</th>
                            <th>Packing Material</th>
                            <th>PM Code
                            <th>Price</th>
                            <th>Threshold</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($packingMaterialsPriceThresholdCollection as $index => $item)
                        <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['pmcode'] }}</td>
                            <td>{{ number_format($item['price'], 2) }}</td>
                            <td>{{ $item['threshold'] }}</td>
                            <td><a href="{{ url('/editpackingmaterial/' . $item['id']) }}">View Details</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if(count($packingMaterialsPriceAlertCollection) > 0)
        <div class="card mt-4">
            <div class="card-header bg-dark text-white">Packing Material Price Update Alert</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S. No.</th>
                            <th>Packing Material</th>
                            <th>PM Code
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($packingMaterialsPriceAlertCollection as $index => $item)
                        <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['pmcode'] }}</td>
                            <td><a href="{{ url('/editpackingmaterial/' . $item['id']) }}">View Details</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </section>

</main><!-- End #main -->
@endsection