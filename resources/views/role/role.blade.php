@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Roles</h1>

        <div>
            <a href="{{ 'addrole' }}" class='text-decoration-none ps-add-btn text-white py-1 px-4'>
                <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
            </a>
        </div>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            @if(session('success'))
            <div id="success-message" class="alert alert-success">{{ session('success') }}</div>
            @endif


            <!-- Right side columns -->
            <div class="d-flex justify-content-center w-100">
                <div class="col-lg-6">
                    <div class="row">
                        <!-- Bordered Table -->
                        <table class="table table-bordered mt-2" id='exportRm'>
                            <thead class="custom-header text-center">
                                <tr>
                                    <th scope="col" style="color:white;">S.NO</th>
                                    <th scope="col" style="color:white;">User Role</th>
                                    <th scope="col" style="color:white;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="roleTable">
                                @foreach ($roles as $index => $material)
                                <tr data-id="{{ $material->id }}">
                                    <td class="text-center">{{ $index+1 }}.</td> <!-- Auto-increment S.NO -->
                                    <td class="left-align rmname text-center">
                                        <a href="{{ route('addrolepermission.create', $material->id) }}" style="color: black; font-size:16px; text-decoration: none;">
                                            {{ $material->name }}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm edit-table-btn me-2" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                                            <i class="fas fa-edit" style="color: black;"></i>
                                        </button>
                                        <button class="btn btn-sm delete-table-btn" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                                            <i class="fas fa-trash" style="color: red;"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- End Right side columns -->

        </div>
    </section>


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