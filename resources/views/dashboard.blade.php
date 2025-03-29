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
        <div class="container mt-4">
            <h5>Do you want to integrate WhatsApp API?</h5>
            <button class="btn btn-success me-2" onclick="window.location.href='{{ route('twilio.keys') }}'">Yes</button>
            <button class="btn btn-danger" onclick="alert('WhatsApp API not selected')">No</button>
        </div>

    </section>
    {{-- <button class="btn btn-outline-primary whatsapp-btn" onclick="window.location.href='{{ route('whatsapp') }}'">whatsapp</button> --}}

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
