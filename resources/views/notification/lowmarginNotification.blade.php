@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Low Margin Alert Notification</h1>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card">
            <div class="card-header  bg-dark text-white">Prduct Low Margin Alert</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S. No.</th>
                            <th>Product</th>
                            <th>PD Code
                            <th>Margin</th>
                            <th>Preferred Margin</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowMarginProducts as $index => $item)
                        <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['pdcode'] }}</td>
                            <td>{{ $item['margin'] }} %</td>
                            <td>{{ $item['threshold'] }} %</td>
                            <td><a href="{{ url('/editproduct/' . $item['id']) }}">View Details</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

</main><!-- End #main -->
@endsection