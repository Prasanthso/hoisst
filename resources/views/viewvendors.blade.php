@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <!-- Page Title -->
    <div class="pagetitle d-flex justify-content-between mb-4">
        <h1 class="mb-0">Vendor List</h1>
        <div>
        <a href="#" class="btn btn-primary rounded-3 shadow" style="width: 200px;">
            <i class="fas fa-plus me-2"></i> Add New Vendor
        </a>
        </div>
    </div>

    <!-- Filters Section -->
    <section class="section dashboard">
        <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3 px-2 pt-4">
            <div class="col-md-3">
                <select id="vendorType" class="form-select rounded-2">
                    <option value="">Select Type</option>
                    <option value="Manufacture">Manufacture</option>
                    <option value="Distributor">Distributor</option>
                </select>
            </div>

            <div class="col-md-3">
                <input type="text" class="form-control rounded-2" id="vendorSearch" name="vendorsearch" placeholder="Vendor name...">
            </div>

            <div class="col-md-3">
                <input type="text" class="form-control rounded-2" id="vendorLocation" name="vendorlocation" placeholder="Location...">
            </div>
        </div>


    <!-- Vendor Table -->
    <div class="col-lg-10 mb-4">
        <div class="table-responsive">
            <table class="table table-bordered mt-2" id="vendorlist">
                <thead class="custom-header">
                    <tr>
                        <th scope="col" style="color:white;">S.NO</th>
                        <th scope="col" style="color:white;">Name</th>
                        <th scope="col" style="color:white;">Mobile No</th>
                        <th scope="col" style="color:white;">Type</th>
                        <th scope="col" style="color:white;">Company Name</th>
                        <th scope="col" style="color:white;">Location</th>
                        <th scope="col" style="color:white;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data Rows will go here -->
                </tbody>
            </table>
        </div>
    </div>
    </section>

</main>

@endsection
