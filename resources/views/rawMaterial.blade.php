@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Raw Material</h1>
        <a href="{{ 'addrawmaterial' }}" class='text-decoration-none ps-add-btn text-white py-1 px-4'>
            <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
        </a>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <!-- Left side columns -->
            <div class="col-lg-2">

                <!-- Recent Activity -->
                <div class="card">


                    <div class="card-body">
                        <h5 class="card-title">Categories</h5>

                        <div class="row mb-3">

                            <div class="col-sm-10">

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="gridCheck1">
                                    <label class="form-check-label" for="gridCheck1">
                                        Oils
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="gridCheck2" checked>
                                    <label class="form-check-label" for="gridCheck2">
                                        Vegetables
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="gridCheck2" checked>
                                    <label class="form-check-label" for="gridCheck2">
                                        Bread
                                    </label>
                                </div>

                            </div>
                        </div>

                    </div>
                </div><!-- End Recent Activity -->

            </div><!-- End Left side columns -->
            <div class="col-lg-1">
            </div>
            <!-- Right side columns -->
            <div class="col-lg-7">
                <div class="row">
                    <!-- Bordered Table -->
                    <table class="table table-bordered">
                        <thead class="custom-header table-primary">
                            <tr>
                                <th scope="col">S.NO</th>
                                <th scope="col">Raw Materials</th>
                                <th scope="col">RM Code</th>
                                <th scope="col">Raw Material Category</th>
                                <th scope="col">Price(Rs)</th>
                                <th scope="col">UoM</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>SunFlower oil</td>
                                <td>RM0001</td>
                                <td>Oils</td>
                                <td>300</td>
                                <td>Ltr</td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- End Bordered Table -->

                </div>
            </div><!-- End Right side columns -->
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