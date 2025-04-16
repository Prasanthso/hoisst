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
    <!-- First Row of Boxes -->
    <div class="row d-flex flex-wrap justify-content-between" style="margin: 0 -10px;">
        <!-- Box 1 -->
        <div class="col-xxl-2 col-md-4" style="margin: 10px;">
            <a href="{{ route('rawMaterials.index') }}" style="text-decoration: none; color: inherit;">
            <div class="card info-card revenue-card" style="background-color: rgb(255, 226, 229); border-radius: 20px; padding: 15px 0 15px 0;">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-start">
                        <div class="d-flex align-items-center justify-content-center" style="margin-bottom: 10px;">
                           <!-- <i class="bi bi-currency-dollar"></i>-->
                           <img src="/assets/img/RmIcon.png" alt="receipe Icon" style="width: 2.5em; height: auto; margin-right:10px;">
                        </div>
                        <div class="ps-3" style="margin-bottom: 10px;">
                            <h6>{{ $totalRm }}</h6>
                        </div>
                        <div class="ps-3">
                            <span class="text-muted small pt-2 ps-1"><b>Raw Materials</b></span>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>

         <!-- Box 2 -->
        <div class="col-xxl-2 col-md-4" style="margin: 10px;">
            <a href="{{ route('packingMaterials.index') }}" style="text-decoration: none; color: inherit;">
            <div class="card info-card revenue-card" style="background-color: rgb(255,244,222); border-radius: 20px; padding: 15px 0 15px 0;">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-start">
                        <div class="d-flex align-items-center justify-content-center" style="margin-bottom: 10px;">
                            <img src="/assets/img/pmIcon.png" alt="receipe Icon" style="width: 2.5em; height: auto; margin-right:10px;">
                        </div>
                        <div class="ps-3" style="margin-bottom: 10px;">
                            <h6>{{ $totalPm }}</h6>
                        </div>
                        <div class="ps-1">
                            <span class="text-muted small pt-2 ps-1"><b>Packing Materials</b></span>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
         <!-- Box 3 -->
         <div class="col-xxl-2 col-md-4" style="margin: 10px;">
            <a href="{{ route('overheads.index') }}" style="text-decoration: none; color: inherit;">
            <div class="card info-card revenue-card" style="background-color: rgb(220,252,231); border-radius: 20px; padding: 15px 0 15px 0;">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-start">
                        <div class="d-flex align-items-center justify-content-center" style="margin-bottom: 10px;">
                            <img src="/assets/img/OhIcon.png" alt="receipe Icon" style="width: 2.5em; height: auto; margin-right:10px;">
                        </div>
                        <div class="ps-3" style="margin-bottom: 10px;">
                            <h6>{{ $totalOh }}</h6>
                        </div>
                        <div class="ps-3">
                            <span class="text-muted small pt-2 ps-1"><b>Overheads</b></span>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
         <!-- Box 4 -->
         <div class="col-xxl-2 col-md-4" style="margin: 10px;">
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
         <div class="col-xxl-2 col-md-4" style="margin: 10px;">
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
    </div>
    <!-- Second Row of Boxes -->
    <div class="row d-flex flex-wrap justify-content-between" style="margin: 0 -10px;">
        <!-- Box 1 -->
        <div class="col-xxl-2 col-md-4" style="margin: 10px;">
            <a href="{{ route('categoryitem.index') }}" style="text-decoration: none; color: inherit;">
            <div class="card info-card revenue-card" style="background-color: rgb(212,245,255); border-radius: 20px; padding: 15px 0 15px 0;">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-start">
                        <div class="d-flex align-items-center justify-content-center" style="margin-bottom: 10px;">
                            <img src="/assets/img/RmcIcon.png" alt="receipe Icon" style="width: 2.5em; height: auto; margin-right:10px;">
                        </div>
                        <div class="ps-3" style="margin-bottom: 10px;">
                            <h6>{{ $totalRmC }}</h6>
                        </div>
                        <div class="ps-3">
                            <span class="text-muted small pt-2 ps-1"><b>Raw Material Categories</b></span>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
         <!-- Box 2 -->
        <div class="col-xxl-2 col-md-4" style="margin: 10px;">
            <a href="{{ route('categoryitem.index') }}" style="text-decoration: none; color: inherit;">
            <div class="card info-card revenue-card" style="background-color: rgb(255,254,222); border-radius: 20px; padding: 15px 0 15px 0;">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-start">
                        <div class="d-flex align-items-center justify-content-center" style="margin-bottom: 10px;">
                            <img src="/assets/img/PmcIcon.png" alt="receipe Icon" style="width: 2.5em; height: auto; margin-right:10px;">
                        </div>
                        <div class="ps-3" style="margin-bottom: 10px;">
                            <h6>{{ $totalPmC }}</h6>
                        </div>
                        <div class="ps-2">
                            <span class="text-muted small pt-2 ps-1"><b>Packing Material Categories</b></span>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>

         <!-- Box 3 -->
         <div class="col-xxl-2 col-md-4" style="margin: 10px;">
            <a href="{{ route('categoryitem.index') }}" style="text-decoration: none; color: inherit;">
            <div class="card info-card revenue-card" style="background-color: rgb(223,234,227); border-radius: 20px; padding: 15px 0 15px 0;">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-start">
                        <div class="d-flex align-items-center justify-content-center" style="margin-bottom: 10px;">
                            <img src="/assets/img/OhcIcon.png" alt="Ovc Icon" style="width: 2.5em; height: auto; margin-right:10px;">
                        </div>
                        <div class="ps-3" style="margin-bottom: 10px;">
                            <h6>{{ $totalOhC }}</h6>
                        </div>
                        <div class="ps-3">
                            <span class="text-muted small pt-2 ps-1"><b>Overheads Categories</b></span>
                        </div>
                    </div>
                </div>
            </div>
            </a>
         </div>
         <!-- Box 4 -->
         <div class="col-xxl-2 col-md-4" style="margin: 10px;">
            <a href="{{ route('categoryitem.index') }}" style="text-decoration: none; color: inherit;">
            <div class="card info-card revenue-card" style="background-color: rgb(232,238,255); border-radius: 20px; padding: 15px 0 15px 0;">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-start">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="margin-bottom: 10px; background-color:rgb(103,133,220); width: 40px; height: 40px; border-radius: 60%;">
                            <img src="/assets/img/package.png" alt="Pdc Icon" style="width: 0.7em; height: auto; margin-right:10px;">
                        </div>
                        <div class="ps-3" style="margin-bottom: 10px;">
                            <h6>{{ $totalPdC }}</h6>
                        </div>
                        <div class="ps-3">
                            <span class="text-muted small pt-2 ps-1"><b>Product Categories</b></span>
                        </div>
                    </div>
                </div>
            </div>
            </a>
         </div>

          <!-- Box 5 -->
        <div class="col-xxl-2 col-md-4" style="margin: 10px;">
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
                                <h6>{{ $totalPm }}</h6>
                            </div>
                            <div class="ps-3">
                                <span class="text-muted small pt-2 ps-1"><b>Products with high & low margins</b></span>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<section class="section dashboard">
    <div class="row">
        <div class="row">
            <!-- Line Chart Column -->
            <div class="col-md-12">
                <div class="card p-3" style="border-radius: 15px; height: 400px;">
                    <h5>Product Margin & Cost Chart</h5>
                    <canvas id="marginLineChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <!-- Bar Chart Column -->
        <div class="col-md-6">
            <div class="card p-3" style="padding: 20px; border-radius: 15px; height: 450px;">
                <h5>Product Margin</h5>
                <canvas id="marginChart" height="300"></canvas> <!-- Increased height -->
            </div>
        </div>

        <!-- Pie Chart Column -->
        <div class="col-md-6">
            <div class="card p-3 d-flex justify-content-center align-items-center" style="height: 450px; border-radius: 15px;">
                <h5>Trading Product Cost </h5>
                <canvas id="marginPieChart" width="100" height="150"></canvas>
            </div>
        </div>
    </div>

</section>
{{--
<section class="section dashboard">
    <!-- Dot (Scatter) Chart -->
    <div class="card" style="padding: 20px; border-radius: 15px;">
        <h5 class="card-title">Product Margin Scatter Plot</h5>
        <canvas id="marginDotChart" height="80"></canvas>
    </div>

</section> --}}
{{--
<section class="section dashboard">
    <div class="card p-3 d-flex justify-content-center align-items-center" style="height: 400px;">
    <h5>Product Margin</h5>
    <canvas id="marginPieChart"  width="150" height="100"></canvas>
</div>
</section> --}}
</main><!-- End #main -->

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('marginChart').getContext('2d');
        const labels = {!! json_encode(collect($graphproducts)->pluck('name')) !!};
        const data = {!! json_encode(collect($graphproducts)->pluck('margin')) !!};
        const purcCostData = {!! json_encode(collect($graphproducts)->pluck('purcCost')) !!};
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Product Margin',
                    data: data,
                    backgroundColor: data.map(value => value < 25 ? '#f87171' : '#4ade80'), // red or green
                    borderWidth: 1
                },
                // {
                //     label: 'Purchase cost',
                //     data: purcCostData,
                //     backgroundColor: data.map(value => value < 100 ? '#f87171' : '#4ade80'), // red or green
                //     borderWidth: 1
                // },
            ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        // dotchart();
        piechart();
        graphchart();
    });

    function dotchart()
    {
        const dotctx = document.getElementById('marginDotChart').getContext('2d');

        const rawData = {!! json_encode($graphproducts) !!};

        const scatterData = rawData.map(item => ({
            x: item.purcCost,
            y: item.margin,
        }));

        new Chart(dotctx, {
            type: 'scatter',
            data: {
                datasets: [{
                    label: 'Margin vs purcCost',
                    data: scatterData,
                    backgroundColor: '#60a5fa',
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Purchase Cost'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Margin'
                        },
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });

    }
    function piechart()
    {
        const ctx = document.getElementById('marginPieChart').getContext('2d');

        const labels = {!! json_encode(collect($graphproducts)->pluck('name')) !!};
        const data = {!! json_encode(collect($graphproducts)->pluck('purcCost')) !!};

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Product Cost',
                    data: data,
                    backgroundColor: labels.map(() => getRandomColor())
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

        // Helper function to generate random colors
        function getRandomColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
    }
    function graphchart()
    {
        const labels = {!! json_encode(collect($graphproducts)->pluck('name')) !!};
        const data = {!! json_encode(collect($graphproducts)->pluck('margin')) !!};
        const purcCostData = {!! json_encode(collect($graphproducts)->pluck('purcCost')) !!};
        // Color logic: pink if < 25, green if ≥ 25
        const pointColors = data.map(value => value < 25 ? '#f472b6' : '#4ade80');

        new Chart(document.getElementById('marginLineChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Product Margin',
                    data: data,
                    fill: false,
                    borderColor: '#4ade80', // line color (you can set to '#999' if you want neutral)
                    pointBackgroundColor: pointColors, // dynamic dot colors
                    pointBorderColor: pointColors,
                    tension: 0.3
                },
                {
                        label: 'Purchase Cost',
                        data: purcCostData,
                        fill: false,
                        borderColor: '#3b82f6', // blue line
                        pointBackgroundColor: '#f87171',
                        pointBorderColor: '#3b82f6',
                        tension: 0.3
                    }
            ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Margin'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Product Name'
                        }
                    }
                }
            }
        });
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
