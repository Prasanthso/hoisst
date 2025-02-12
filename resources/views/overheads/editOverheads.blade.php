@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <!-- Initially displaying "View Raw Material" -->
        <h1 id="pageTitle">View Overheads</h1>
        <div class="d-flex justify-content-end mb-2 action-buttons">
            <button class="btn btn-sm edit-table-btn me-2" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;" id="editButton">
                <i class="fas fa-edit" style="color: black;"></i>
            </button>
            <!-- <button class="btn btn-sm delete-table-btn" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;" id="deleteButton" style="display: none;">
                <i class="fas fa-trash" style="color: red;"></i>
            </button>-->
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-6">

                    <div class="card">
                        <div class="card-body">

                            <!-- Vertical Form -->
                            <form method="POST" action="{{ route('overheads.edit', $overheads->id) }}" class="row g-3 mt-2" id="rawMaterialForm">
                                @csrf
                                @method('PUT')
                                <div class="col-12">
                                    <label for="inputNanme4" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="inputNanme4" name="name" value="{{ $overheads->name}}" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputHSNcode" class="form-label">HSN Code</label>
                                    <input type="text" class="form-control" id="inputHSNcode" name="hsncode" value="{{ $overheads->hsncode}}" disabled>
                                </div>
                                <div class="col-md-12">
                                    <label for="inputNanme4" class="form-label">Choose Category For</label>
                                    <select id="inputState" class="form-select select2" name="uom" disabled>
                                        <option selected>{{ $overheads->uom}}</option>
                                        <option>Ltr</option>
                                        <option>Kgs</option>
                                        <option>Nos</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="categorySelect" class="form-label">Overheads Category</label>

                                    <!-- The dropdown list for selecting categories (hidden initially) -->
                                    <select class="form-select" id="categorySelect" name="category_ids[]" multiple disabled>
                                        @foreach($overheadsCategories as $categories)
                                        <option value="{{ $categories->id }}"
                                            @foreach(range(1, 10) as $i)
                                            @php
                                            $categoryId='category_id' . $i;
                                            @endphp
                                            @if($overheads->$categoryId == $categories->id) selected @endif
                                            @endforeach
                                            >{{ $categories->itemname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="inputItemType" class="form-label">Item Type</label>
                                    <input type="text" class="form-control" id="inputItemType" name="itemtype" value="{{ $overheads->itemtype}}" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputNanme4" class="form-label">Price</label>
                                    <input type="text" class="form-control" id="inputNanme4" name="price" value="{{ $overheads->price}}" disabled>
                                </div>
                                <div class="col-12">
                                    <label for="inputTax" class="form-label">Tax</label>
                                    <input type="text" class="form-control mb-2" id="inputTax" name="tax" value="{{ $overheads->tax}}" disabled>
                                </div>
                                <div class="row mb-4">
                                    <label for="inputNanme4" class="form-label">Pricing update frequency</label>
                                    <div class="col-md-3">
                                        <select class="form-select mb-2" id="update_frequency" name="update_frequency" disabled>
                                            <option selected>{{ $overheads->update_frequency}}</option>
                                            <option>Days</option>
                                            <option>Weeks</option>
                                            <option>Monthly</option>
                                            <option>Yearly</option>
                                        </select>
                                    </div>
                                     <div class="col-md-9">
                                        <input type="text" class="form-control" id="inputNanme4" name="price_update_frequency" value="{{ $overheads->price_update_frequency}}" disabled>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="inputNanme4" class="form-label">Price threshold</label>
                                    <input type="text" class="form-control" id="inputNanme4" name="price_threshold" value="{{ $overheads->price_threshold}}" disabled>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary" id="saveButton" style="display: none;">
                                        Update
                                    </button>
                                </div>
                            </form><!-- Vertical Form -->

                        </div>
                    </div>
                </div>

            </div>
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

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize select2 for category select
        $('#categorySelect').select2({
            theme: 'bootstrap-5',
            placeholder: 'Choose Categories',
            allowClear: true
        });

        $('#inputState').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select UoM',
        });
        // Toggle edit mode
        $('#editButton').on('click', function() {
            // Change the page title text
            $('#pageTitle').text('Edit Overheads');

            // Enable form fields
            $('#rawMaterialForm input, #rawMaterialForm select').prop('disabled', false);

            // Show the Save button
            $('#saveButton').show();
        });

        $('#editButton').on('click', function() {
            // Hide the category list and show the select dropdown
            $('#categoryList').hide(); // Hide the list of categories
            $('#categorySelect').show(); // Show the select dropdown

            // Enable the select dropdown for editing
            $('#categorySelect').prop('disabled', false);
        });
    });
</script>

<!--Template Main JS File-->
<script src="{{ asset('js/main.js') }}">
</script>
