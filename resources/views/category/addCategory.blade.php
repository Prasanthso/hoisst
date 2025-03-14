@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Create Category</h1>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}
            <div class="col-lg-12">
                <div class="col-lg-6">

                    <div class="card">
                        {{-- @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif --}}
                        <div class="card-body">
                            <!-- Vertical Form -->
                            <form method="POST" action="{{ route('categoryitem.store') }}" class="row g-3 mt-2">
                                @csrf
                                <div class="col-md-12">
                                    <label for="inputState" class="form-label">Choose Category For</label>
                                    <select id="inputState" name="categoryId" class="form-select select2">
                                        <option selected disabled>Choose...</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('categoryId') == $category->id ? 'selected' : '' }}>
                                            {{ $category->categoryname }}
                                        </option>
                                            {{-- <option value="{{ $category->id }}">{{ $category->categoryname }}</option> --}}
                                        @endforeach
                                    </select>
                                    @error('categoryId')
                                    <span id="category-error" class="text-danger">{{ $message }}</span>
                                @enderror
                                </div>
                                <div class="col-12">
                                    <label for="itemname" class="form-label">Category Name</label>
                                    <input type="text" class="form-control" id="itemname" name="itemname" value="{{ old('itemname') }}">
                                    @error('itemname')
                                    <span id="itemname-error" class="text-danger">{{ $message }}</span>
                                @enderror
                                </div>

                                <div class="col-12">
                                    <label for="inputNanme4" class="form-label">Description</label>
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Category Description" name="description" id="floatingTextarea" style="height: 100px;"></textarea>
                                        <label for="floatingTextarea">Category Description</label>
                                        @error('description')
                                        <span id="desc-error" class="text-danger">{{ $message }}</span>
                                    @enderror
                                    </div>

                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary" id="btnSubmit">Save</button>
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

<!-- Template Main JS File -->
<script src="{{ asset('js/main.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

$(document).ready(function() {
        $('#inputState').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select',
         });
    });

    document.addEventListener("DOMContentLoaded", function () {
    const itemNameInput = document.getElementById("itemname");
    // const desc = document.getElementById("floatingTextarea");

        itemNameInput.addEventListener("input", function () {
            let errorMsg = document.getElementById("itemname-error");
            if (this.value !== "") {
                if(errorMsg){
                errorMsg.remove(); }  // Remove error message
            }
        });
        $('#inputState').on("select2:select", function (e) {
        let selectedValue = $(this).val(); // Get selected value
        console.log(selectedValue);
        let errorMsg = document.getElementById("category-error");
        if (selectedValue !== "Choose..." && errorMsg) {
            errorMsg.remove();
        }
        });

        // desc.addEventListener("input", function () {
        //     let errorMsg = document.getElementById("desc-error");
        //     if (this.value.trim() !== "" && errorMsg) {
        //         errorMsg.remove(); // Remove error message
        //     }
        // });

    });

</script>
