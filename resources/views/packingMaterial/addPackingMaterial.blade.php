@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Add Packing Materials</h1>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-6">

                    <div class="card">
                        <div class="card-body">

                            <!-- Vertical Form -->
                            <form method="POST" action="{{ route('packingmaterials.store') }}" class="row g-3 mt-2">
                                @csrf
                                <div class="col-12">
                                    <label for="inputNanme4" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="inputNanme4" name="name">
                                </div>
                                <!-- <div class="col-12">
                                    <label for="inputNanme4" class="form-label">RM Code</label>
                                    <input type="text" class="form-control" id="inputNanme4">
                                </div> -->
                                <div class="col-md-12">
                                    <label for="inputNanme4" class="form-label">Choose Category For</label>
                                    <select id="inputState" class="form-select select2" name="uom">
                                        <option selected>UoM</option>
                                        <option>Ltr</option>
                                        <option>Kgs</option>
                                        <option>Nos</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="categorySelect" class="form-label">Packing Material Category</label>
                                    <select id="categorySelect" class="form-select select2" name="category_ids[]" multiple>
                                        @foreach($packingMaterialCategories as $categories)
                                        <option value="{{ $categories->id }}">{{ $categories->itemname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="inputNanme4" class="form-label">Price</label>
                                    <input type="text" class="form-control" id="inputNanme4" name="price">
                                </div>
                                <div class="row">
                                    <label for="inputNanme4" class="form-label mb-2">Pricing update frequency</label>
                                    <div class="col-md-3">
                                        <select class="form-select" id="update_frequency" name="update_frequency">
                                            <option selected>Days</option>
                                            <option>Weeks</option>
                                            <option>Monthly</option>
                                            <option>Yearly</option>
                                        </select>
                                    </div>
                                {{-- <div class="col-md-1">
                                </div> --}}
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="inputNanme4" name="price_update_frequency">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="inputNanme4" class="form-label">Price threshold in percentage</label>
                                    <input type="text" class="form-control" id="inputNanme4" name="price_threshold">
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        Save
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
        $('#categorySelect').select2({
            theme: 'bootstrap-5',
            placeholder: 'Choose Categories',
            allowClear: true
        });

        $('#inputState').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select UoM',
            });
    });
</script>


<!--Template Main JS File-->
<script src="{{ asset('js/main.js') }}">
</script>
