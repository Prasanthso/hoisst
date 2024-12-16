@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

<style>
    body {
        background-color: #ffffff !important;
    }
</style>


    <div class="pagetitle">
        <h1><b>DASHBOARD</b></h1>
    </div><!-- End Page Title -->

    <section class="section dashboard">
 

            </div><!-- End Left side columns -->
            <!-- Right side columns -->
    <!-- First Row of Boxes -->
    <div class="row d-flex flex-wrap justify-content-between" style="margin: 0 -10px;">
        <!-- Box 1 -->
        <div class="col-xxl-2 col-md-4" style="margin: 10px;">
            <div class="card info-card revenue-card" style="background-color: rgb(255, 226, 229); border-radius: 20px; padding: 15px 0 15px 0;">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-start">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="margin-bottom: 10px;">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="ps-3" style="margin-bottom: 10px;">
                            <h6>3500</h6>
                        </div>
                        <div class="ps-3">
                            <span class="text-muted small pt-2 ps-1"><b>Raw Materials</b></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Box 2 -->
        <div class="col-xxl-2 col-md-4" style="margin: 10px;">
            <div class="card info-card revenue-card" style="background-color: rgb(255,244,222); border-radius: 20px; padding: 15px 0 15px 0;">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-start">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="margin-bottom: 10px;">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="ps-3" style="margin-bottom: 10px;">
                            <h6>300</h6>
                        </div>
                        <div class="ps-3">
                            <span class="text-muted small pt-2 ps-1"><b>Packing Materials</b></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Box 3 -->
        <div class="col-xxl-2 col-md-4" style="margin: 10px;">
            <div class="card info-card revenue-card" style="background-color: rgb(220,252,231); border-radius: 20px; padding: 15px 0 15px 0;">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-start">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="margin-bottom: 10px;">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="ps-3" style="margin-bottom: 10px;">
                            <h6>125</h6>
                        </div>
                        <div class="ps-3">
                            <span class="text-muted small pt-2 ps-1"><b>Overheads</b></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Box 4 -->
        <div class="col-xxl-2 col-md-4" style="margin: 10px;">
            <div class="card info-card revenue-card" style="background-color: rgb(243,232,255); border-radius: 20px; padding: 15px 0 15px 0;">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-start">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="margin-bottom: 10px;">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="ps-3" style="margin-bottom: 10px;">
                            <h6>500</h6>
                        </div>
                        <div class="ps-3">
                            <span class="text-muted small pt-2 ps-1"><b>Products</b></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Box 5 -->
        <div class="col-xxl-2 col-md-4" style="margin: 10px;">
            <div class="card info-card revenue-card" style="background-color: rgb(214,236,236); border-radius: 20px; padding: 15px 0 15px 0;">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-start">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="margin-bottom: 10px;">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="ps-3" style="margin-bottom: 10px;">
                            <h6>445</h6>
                        </div>
                        <div class="ps-3">
                            <span class="text-muted small pt-2 ps-1"><b>Recipes</b></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row of Boxes -->
    <div class="row d-flex flex-wrap justify-content-between" style="margin: 0 -10px;">
        <!-- Box 6 -->
        <div class="col-xxl-2 col-md-4" style="margin: 10px;">
            <div class="card info-card revenue-card" style="background-color: rgb(212,245,255); border-radius: 20px; padding: 15px 0 15px 0;">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-start">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="margin-bottom: 10px;">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="ps-3" style="margin-bottom: 10px;">
                            <h6>125</h6>
                        </div>
                        <div class="ps-3">
                            <span class="text-muted small pt-2 ps-1"><b>Raw Materail categories</b></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Box 7 -->
        <div class="col-xxl-2 col-md-4" style="margin: 10px;">
            <div class="card info-card revenue-card" style="background-color: rgb(255,254,222); border-radius: 20px; padding: 15px 0 15px 0;">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-start">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="margin-bottom: 10px;">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="ps-3" style="margin-bottom: 10px;">
                            <h6>102</h6>
                        </div>
                        <div class="ps-3">
                            <span class="text-muted small pt-2 ps-1"><b>Packing Material categories</b></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Box 8 -->
        <div class="col-xxl-2 col-md-4" style="margin: 10px;">
            <div class="card info-card revenue-card" style="background-color: rgb(223,234,227); border-radius: 20px; padding: 15px 0 15px 0;">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-start">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="margin-bottom: 10px;">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="ps-3" style="margin-bottom: 10px;">
                            <h6>03</h6>
                        </div>
                        <div class="ps-3">
                            <span class="text-muted small pt-2 ps-1"><b>Overheads Categories</b></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Box 9 -->
        <div class="col-xxl-2 col-md-4" style="margin: 10px;">
            <div class="card info-card revenue-card" style="background-color: rgb(232,238,255); border-radius: 20px; padding: 15px 0 15px 0;">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-start">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="margin-bottom: 10px;">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="ps-3" style="margin-bottom: 10px;">
                            <h6>29</h6>
                        </div>
                        <div class="ps-3">
                            <span class="text-muted small pt-2 ps-1"><b>Products Categories</b></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Box 10 -->
        <div class="col-xxl-2 col-md-4" style="margin: 10px;">
            <div class="card info-card revenue-card" style="background-color: rgb(249,207,180); border-radius: 20px; padding: 15px 0 15px 0;">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-start">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="margin-bottom: 10px;">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="ps-3" style="margin-bottom: 10px;">
                            <h6>14</h6>
                        </div>
                        <div class="ps-3">
                            <span class="text-muted small pt-2 ps-1"><b>Alert Messages</b></span>
                        </div>
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
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="margin-bottom: 10px;">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="ps-3" style="margin-bottom: 10px;">
                            <h6>14</h6>
                        </div>
                        <div class="ps-3">
                            <span class="text-muted small pt-2 ps-1"><b>Products with high & low margins</b></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



                <!-- End Right side columns -->
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

<!-- Template Main JS File -->
<script src="{{ asset('js/main.js') }}"></script>