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
    </div>

    <!-- Second Row of Boxes -->
   
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