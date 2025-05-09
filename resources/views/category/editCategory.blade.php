@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle">
        <h1 id="pageTitle">View CategoryItem</h1>
        <div class="d-flex justify-content-end mb-2 action-buttons">
            <button class="btn btn-sm edit-table-btn me-2" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;" id="editButton">
                <i class="fas fa-edit" style="color: black;"></i>
            </button>
            <!--<button class="btn btn-sm delete-table-btn" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;" id="deleteButton" style="display: none;">
                <i class="fas fa-trash" style="color: red;"></i>
            </button>-->
        </div>
    </div> <!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-6">

                    <div class="card">
                        @if(session('success'))
                        <div id="success-message" class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                        <div id="error-message" class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        <div class="card-body">
                            <!-- Vertical Form -->
                            <form method="POST" action="{{ route('categoryitem.update',$items->id) }}" class="row g-3 mt-2" id="categoryItemsForm">
                                @csrf
                                @method('PUT')
                                <div class="col-md-12">
                                    <label for="inputState" class="form-label">Choose Category For</label>
                                    <select id="inputState" name="categoryId" class="form-select" disabled>
                                        <option value="{{ $items->category->id }}" selected disabled>{{ $items->category->categoryname }}</option>
                                    </select>
                                    @error('categoryId')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="itemname" class="form-label">Category Name</label>
                                    <input type="text" class="form-control" id="itemname" name="itemname" value="{{ $items->itemname}}" disabled>
                                    @error('itemname')
                                    <span id="itemname-error" class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="floatingTextarea" class="form-label">Description</label>
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Category Description" name="description" id="floatingTextarea" style="height: 100px;" disabled>{{ $items->description}}</textarea>
                                    </div>
                                    {{-- @error('description')
                                    <span id="desc-error" class="text-danger">{{ $message }}</span>
                                    @enderror --}}
                                </div>
                                <fieldset class="row mb-3 mt-3">
                                    <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                name="status"
                                                id="active"
                                                value="active"
                                                {{ $items->status == 'active' ? 'checked' : '' }}
                                                disabled>
                                            <label class="form-check-label" for="active">
                                                Active
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                name="status"
                                                id="inactive"
                                                value="inactive"
                                                {{ $items->status == 'inactive' ? 'checked' : '' }}
                                                disabled>
                                            <label class="form-check-label" for="inactive">
                                                Inactive
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                                <div>
                                    <button type="submit" class="btn btn-primary" id="saveButton" style="display: none;">Update</button>
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

<!-- Template Main JS File -->
<script src="{{ asset('js/main.js') }}"></script>
<script>
    $(document).ready(function() {
        // Initialize select2 for category select
        $('#categorySelect').select2({
            theme: 'bootstrap-5',
            placeholder: 'Choose Categories',
            allowClear: true
        });

        // Toggle edit mode
        $('#editButton').on('click', function() {
            // Change the page title text
            $('#pageTitle').text('Edit CategoryItem');

            // Enable form fields
            $('#categoryItemsForm input, #categoryItemsForm select').prop('disabled', false);
            document.getElementById("floatingTextarea").disabled = false;
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

    document.addEventListener("DOMContentLoaded", function() {
        const itemNameInput = document.getElementById("itemname");
        // const desc = document.getElementById("floatingTextarea");

        itemNameInput.addEventListener("input", function() {
            let errorMsg = document.getElementById("itemname-error");
            if (this.value !== "") {
                if (errorMsg) {
                    errorMsg.remove();
                } // Remove error message
            }
        });

        setTimeout(() => {
            ['success-message', 'error-message', 'itemname-error'].forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.style.display = 'none';
                }
            });
        }, 900);

    });
</script>