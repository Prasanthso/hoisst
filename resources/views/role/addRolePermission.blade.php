@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Role Permission</h1>

        <!-- <div>
            <a href="{{ 'addrawmaterial' }}" class='text-decoration-none ps-add-btn text-white py-1 px-4'>
                <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
            </a>
            <a href="{{ url('/rawMaterial-excel') }}" download class="btn" data-bs-toggle="tooltip" title="Download rawMaterial excel File">
                <i class="bi bi-download fs-4"></i>
            </a>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                Import
            </button>
            <button id="exportBtn" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export to Excel
            </button>
            <button id="exportPdfBtn" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Export to PDF
            </button>
        </div> -->
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <h5 class="px-4">Role : <span style="color:rgb(237, 11, 11);">{{ $role->name }}</span></h5>
            @if(session('success'))
            <div id="success-message" class="alert alert-success">{{ session('success') }}</div>
            @endif
            <!-- Left side columns -->
            <div class="col-lg-2 px-2 mt-5">
                <!-- Categories Section -->
                <div class="card" style="background-color: #EEEEEE;">
                    <div class="card-body">
                        <h5 class="card-title">Categories</h5>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                @foreach($permission_category as $category)
                                <div class="form-check category-item">
                                    <input
                                        class="form-check-input category-checkbox"
                                        type="checkbox"
                                        data-id="category_{{ $category->id }}"
                                        value="{{ $category->id }}"
                                        {{-- data-category-name="{{ $category->categoryName }}" --}}>
                                    <label class="form-check-label" for="category_{{ $category->id }}">
                                        {{ $category->categoryName }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div><!-- End Categories Section -->
            </div><!-- End Left side columns -->

            <!-- <div class="col-lg-1"></div> -->

            <!-- Right side columns -->
            <div class="col-lg-8">
                <div class="row">

                </div>
            </div><!-- End Right side columns -->
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="priceModal" tabindex="-1" aria-labelledby="priceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="priceModalLabel">Price Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-price">
                        <thead class="custom-header">
                            <tr>
                                <th style="color:white;">Effective From</th>
                                <th style="color:white;">Price</th>
                                <th style="color:white;">Updated By</th>
                            </tr>
                        </thead>
                        <tbody id="priceDetailsTable">
                            <!-- Data will be dynamically injected here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</main><!-- End #main -->
@endsection

<!-- Vendor JS Files -->
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<!-- Template Main JS File -->
<script src="{{ asset('js/main.js') }}"></script>