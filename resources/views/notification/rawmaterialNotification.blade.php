@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Raw Material Alert Notification</h1>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        @if(count($rawMaterialsPriceThresholdCollection) > 0)
        <div class="card">
            <div class="card-header  bg-dark text-white">Raw Material Price Threshold Alert</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S. No.</th>
                            <th>Raw Material</th>
                            <th>RM Code
                            <th>Price</th>
                            <th>Threshold</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rawMaterialsPriceThresholdCollection as $index => $item)
                        <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['rmcode'] }}</td>
                            <td>{{ number_format($item['price'], 2) }}</td>
                            <td>{{ $item['threshold'] }}</td>
                            <td><a href="{{ url('/editrawmaterial/' . $item['id']) }}">View Details</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if(count($rawMaterialsPriceAlertCollection) > 0)
        <div class="card mt-4">
            <div class="card-header bg-dark text-white">Raw Material Price Update Alert</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S. No.</th>
                            <th>Raw Material</th>
                            <th>RM Code
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rawMaterialsPriceAlertCollection as $index => $item)
                        <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['rmcode'] }}</td>
                            <td><a href="{{ url('/editrawmaterial/' . $item['id']) }}">View Details</a></td>
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

<!-- Vendor JS Files -->
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