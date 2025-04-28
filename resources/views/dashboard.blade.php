@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <style>
        body {
            background-color: #ffffff !important;
        }
    </style>

    <div class="pagetitle">
        <h1> DASHBOARD </h1>
    </div><!-- End Page Title -->

    <section class="section dashboard">
            <!-- End Left side columns -->
            <!-- Right side columns -->

        <!-- Box 4 -->
        <div class="row d-flex flex-wrap justify-content-between" style="margin: 0 -10px;">
        <div class="col-xxl-2 col-md-2" style="margin: 10px;">
            <a href="{{ route('products.index') }}" style="text-decoration: none; color: inherit;">
                <div class="card info-card revenue-card" style="background-color: rgb(243,232,255); border-radius: 20px; padding: 15px 0 15px 0;">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-start">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="margin-bottom: 10px;background-color:rgb(191,131,255); width: 40px; height: 40px; border-radius: 60%;">
                                {{-- <img src="/assets/img/PdIcon.png" alt="receipe Icon" style="width: 2.5em; height: auto; margin-right:10px;"> --}}
                                <img src="/assets/img/package.png" alt="Pdc Icon" style="width: 0.7em; height: auto; margin-right:10px;">
                            </div>
                            <div class="ps-3" style="margin-bottom: 10px;">
                                <h6>{{ $totalPd }}</h6>
                            </div>
                            <div class="ps-3">
                                <span class="text-muted small pt-2 ps-1"><b>Products</b></span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
         <!-- Box 5 -->
        <div class="col-xxl-2 col-md-2" style="margin: 10px;">
            <a href="{{ route('receipedetails.index') }}" style="text-decoration: none; color: inherit;">
                <div class="card info-card revenue-card" style="background-color: rgb(214,236,236); border-radius: 20px; padding: 15px 0 15px 0;">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-start">
                            <div class="d-flex align-items-center justify-content-center" style="margin-bottom: 10px;">
                                <img src="/assets/img/rIcon.png" alt="receipe Icon" style="width: 2.5em; height: auto; margin-right:10px;">
                            </div>
                            <div class="ps-3" style="margin-bottom: 10px;">
                                <h6>{{ $totalrecipes }}</h6>
                            </div>
                            <div class="ps-3">
                                <span class="text-muted small pt-2 ps-1"><b>Recipes</b></span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

             <!-- Box 5 -->
            <div class="col-xxl-2 col-md-2" style="margin: 10px;">
                <div class="card info-card revenue-card" style="background-color: rgb(249,207,180); border-radius: 20px; padding: 15px 0 15px 0;">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-start">
                            <div class="d-flex align-items-center justify-content-center" style="margin-bottom: 16px;">
                                <!--<i class="bi bi-currency-dollar"></i>-->
                                <img src="/assets/img/pmIcon.png" alt="receipe Icon" style="width: 2.5em; height: auto; margin-right:10px;">
                            </div>
                            <div class="ps-3" style="margin-bottom: 10px;">
                                <h6>0</h6>
                            </div>
                            <div class="ps-3">
                                <span class="text-muted small pt-2 ps-1"><b>Alert Messages</b></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Box 11 -->
            <div class="col-xxl-2 col-md-4" style="margin: 10px;">
                <div class="card info-card revenue-card" style="background-color: rgb(180,249,242); border-radius: 20px; padding: 15px 0 15px 0;">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-start">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="margin-bottom: 10px;background-color:rgb(59,218,202); width: 40px; height: 40px; border-radius: 60%;">
                                <!--<i class="bi bi-currency-dollar"></i>-->
                                <img src="/assets/img/package.png" alt="Pdc Icon" style="width: 0.7em; height: auto; margin-right:10px;">
                            </div>
                            <div class="ps-3" style="margin-bottom: 10px;">
                                <h6>{{ ($alerts['lowMarginCount']) + ($alerts['highMarginCount']) }}</h6>
                            </div>
                            <div class="ps-1">
                                <span class="text-muted small pt-2 ps-1"><b>Products margin (Low & High)</b></span>
                            </div>

                    </div>
                </div>
            </div>
        </div>
                <!-- End Right side columns -->
    </section>
    <section class="section dashboard">
        {{-- <div class="container mt-4">
            <h5>Do you want to integrate WhatsApp API?</h5>

            <button class="btn btn-success me-2" onclick="window.location.href='{{ route('twilio.keys') }}'">Yes</button>
        <button class="btn btn-danger" onclick="alert('Thanks for information.')">No</button>
        </div> --}}
        {{-- <div class="container mt-4">
            <h5>Do you want to activate WhatsApp messages?</h5>
            <button class="btn btn-success me-2" onclick="window.location.href='{{ route('whatsapp') }}'">Yes</button>
        <button class="btn btn-danger" onclick="alert('WhatsApp mgs is not sent')">No</button>
        </div> --}}

    {{-- <button class="btn btn-outline-primary whatsapp-btn" onclick="window.location.href='{{ route('whatsapp') }}'">whatsapp</button> --}}

</section>
<section class="section dashboard">
    <!-- this is for sanpshot panel section -->
    <div class="container"> <!--container-fluid-->
        <!-- First Row: Cost Trend + Chart -->
        <div class="row mb-4">
            <!-- Cost Trend -->
            <div class="col-md-3">
                {{-- <div class="snapshot-panel card bg-light p-3 h-60"> --}}
                    <div class="card">
                        <div class="card-header bg-primary text-white">Cost Trend</div>
                        <div class="card-body">
                        <p>Current Month: ₹{{ number_format($costindicator['thisMonthCost'], 2) }}</p>
                        <p>Last Month: ₹{{ number_format($costindicator['lastMonthCost'], 2) }}</p>
                        <p>
                            Change:
                            <span class="{{ $costindicator['costTrendIndicator'] == 'increase' ? 'text-success fw-bold' : ($costindicator['costTrendIndicator'] == 'decrease' ? 'text-danger fw-bold' : '') }}">
                                {{ number_format($costindicator['costChange'], 2) }}%
                                @if($costindicator['costTrendIndicator'] == 'increase')
                                    🔺
                                @elseif($costindicator['costTrendIndicator'] == 'decrease')
                                    🔻
                                @else
                                    ➖
                                @endif
                            </span>
                        </p>
                        </div>
                    </div>
                {{-- </div> --}}
            </div>
            <!-- Chart -->
            <div class="col-md-9">
                <div class="card  h-100">
                    <div class="card-header bg-primary text-white">
                        Trend Analytics - Modifications & Impact
                    </div>
                    <div class="card-body">
                        <canvas id="trendChart" width="500" height="240"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row: Alerts -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Alerts & Red Flags</h5>
                    </div>
                    <div class="card-body">
                        <form id="check-form" class="d-flex align-items-center">
                            <label for="material_name" class="form-label mb-0 me-2">Rawmaterial name:</label>
                            <input type="text" id="material_name" class="form-control me-2" name="material_name" placeholder="Enter raw material name" required>
                            <button class="btn btn-primary me-2" type="submit">Check</button>
                            {{-- <button class="btn btn-primary" type="btnClear">Clear</button> --}}
                        </form>
                          <!-- High Cost Ingredients Alerts -->
                        <div id="alert-container">
                            @if(isset($highcostingredients) && !empty($highcostingredients))
                                <div class="alert alert-danger">
                                    <h5>🚨 High Cost Ingredient</h5>
                                    @foreach($highcostingredients as $alert)
                                        <p><strong>{{ $alert['item'] }}</strong> - {{ $alert['description'] }}</p>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info mt-2" id="result">No high cost alert for this material.</div>
                            @endif
                        </div>

                  <!-- Low Margin Products Alerts -->
@if(count($alerts['lowMarginAlerts']) > 0)
<div class="alert alert-warning">
    <h5>🚨 Low Margin Products</h5>
    @foreach($alerts['lowMarginAlerts'] as $index => $lowalert)
        @if($index < 2)
            <p><strong>{{ $lowalert['item'] }}</strong> - {{ $lowalert['description'] }}</p>
        @else
            <div class="more-low-margin-alerts" style="display: none;">
                <p><strong>{{ $lowalert['item'] }}</strong> - {{ $lowalert['description'] }}</p>
            </div>
        @endif
    @endforeach

    @if(count($alerts['lowMarginAlerts']) > 1)
        <button onclick="toggleLowMarginAlerts()" class="btn btn-primary btn-sm mt-2">View More</button>
    @endif
</div>
@else
<p>No low margin product alerts.</p>
@endif

<!-- High Margin Products Alerts -->
@if(count($alerts['highMarginAlerts']) > 0)
<div class="alert alert-success">
    <h5>🚨 High Margin Products</h5>
    @foreach($alerts['highMarginAlerts'] as $index => $highalert)
        @if($index < 2)
            <p><strong>{{ $highalert['item'] }}</strong> - {{ $highalert['description'] }}</p>
        @else
            <div class="more-high-margin-alerts" style="display: none;">
                <p><strong>{{ $highalert['item'] }}</strong> - {{ $highalert['description'] }}</p>
            </div>
        @endif
    @endforeach

    @if(count($alerts['highMarginAlerts']) > 1)
        <button onclick="toggleHighMarginAlerts()" class="btn btn-primary btn-sm mt-2">View More</button>
    @endif
</div>
@else
<p>No high margin product alerts.</p>
@endif

                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- </section>
<section class="section dashboard"> --}}

    <div class="container">
        <h5>Cost Insights</h5>
        <!-- Cost Trend Chart -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">Cost Trend (Last 6 Months)</div>
            <div class="card-body">
                <canvas id="costTrendChart" height="h-100"></canvas>
            </div>
        </div>
        <!-- Top Profitable Recipes -->
        @php
            $topProfitable = $trendData['top_profitable'];
        @endphp
        <div class="card">
            <div class="card-header  bg-primary text-white">Top Profitable Recipes</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Margin (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProfitable as $item)
                            <tr>
                                <td>{{ $item->name }}</td>

                                <td>{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->margin }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</section>
</main>  <!-- End #main -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
        $('#check-form').on('submit', function(e) {
            e.preventDefault();

            let materialName = $('#material_name').val();

            $.ajax({
                url: '{{ route("dashboard") }}',
                type: 'GET',
                data: { material_name: materialName },
                success: function(response) {
                    // Update the content based on the response
                    if (response.highcostingredients.length > 0) {
                        let alertHtml = '<div class="alert alert-danger"><h5>🚨 High Cost Ingredient</h5>';
                        response.highcostingredients.forEach(function(alert) {
                            alertHtml += `<p><strong>${alert.item}</strong> - ${alert.description}</p>`;
                        });
                        alertHtml += '</div>';
                        $('#alert-container').html(alertHtml);
                    } else {
                        $('#alert-container').html('<div class="alert alert-info mt-2" id="result">No high cost alert for this material.</div>');
                    }

                    // Handle other alerts (if needed)
                    // Example: Low Margin Alerts, High Margin Alerts, etc.
                },
                error: function() {
                    $('#alert-container').html('<div class="alert alert-danger">Something went wrong. Please try again.</div>');
                }
            });
        });

        $('#btnClear').on('click', function () {
            $('#material_name').val('');              // Clear input
            $('#alert-container').html('');           // Clear alert messages
        });

    document.addEventListener('DOMContentLoaded', function () {
        barchart();
        costtrendLineChart();
    });

    // modifications & Impact
    function barchart() {
    const months = @json($months);
    const products = @json($products);
    const rawMaterials = @json($rawMaterials);
    const quantities = @json($quantities);
    const impacts = @json($impacts);

    const labels = months.map((month, i) => `${month} - ${products[i]} (${rawMaterials[i]})`);

    const ctx = document.getElementById('trendChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Top Raw Material Quantity',
                data: quantities,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                marginData: impacts // Custom data for tooltips
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Quantity'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Month - Product (Raw Material)'
                    }
                }
            }, // <- FIXED: added missing comma here
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const quantity = context.raw;
                            const margin = context.dataset.marginData[context.dataIndex];
                            return `Quantity: ${quantity}, Margin: ${margin.toFixed(2)}%`;
                        }
                    }
                }
            }
        }
    });
    }

    function costtrendLineChart()
    {
        const ctx = document.getElementById('costTrendChart').getContext('2d');
        const rawData = @json($trendData['trendData']);
        // Extract unique months
        const months = [...new Set(rawData.map(item => item.month))];

        // Group prices
        const groupedOld = {};
        const groupedNew = {};

        rawData.forEach(item => {
            if (!groupedOld[item.material_name]) groupedOld[item.material_name] = {};
            if (!groupedNew[item.material_name]) groupedNew[item.material_name] = {};

            groupedOld[item.material_name][item.month] = item.avg_old_price;
            groupedNew[item.material_name][item.month] = item.avg_new_price;
        });

        // Create datasets (old in dashed lines, new in solid lines)
        const datasets = [];

        Object.keys(groupedOld).forEach((name, index) => {
            const color = randomColor();

            datasets.push({
                label: `${name} - Old Price`,
                data: months.map(m => groupedOld[name][m] ?? null),
                borderColor: color,
                borderDash: [5, 5],
                pointBackgroundColor: color,
                backgroundColor: color,
                fill: false,
                tension: 0.3
            });

            datasets.push({
                label: `${name} - New Price`,
                data: months.map(m => groupedNew[name][m] ?? null),
                borderColor: color,
                pointBackgroundColor: color,
                backgroundColor: color,
                fill: false,
                tension: 0.3
            });
        });


        new Chart(document.getElementById('costTrendChart'), {
            type: 'line',
            data: {
                labels: months,
                datasets: datasets
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false,
                        title: {
                            display: true,
                            text: 'Price ($)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    }
                },
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: ctx => `${ctx.dataset.label}: $${ctx.raw?.toFixed(2)}`
                        }
                    }
                },
                elements: {
                    point: {
                        radius: 4,
                        hoverRadius: 6
                    },
                    line: {
                        borderWidth: 2
                    }
                }
            }
        });
    }
    function randomColor() {
            const colors = ['#f44336', '#03a9f4', '#ffeb3b', '#4caf50', '#9c27b0'];
            return colors[Math.floor(Math.random() * colors.length)];
        }
</script>


<!-- Vendor JS Files -->
<script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
<script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ asset('js/main.js') }}"></script>
