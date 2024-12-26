@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Add Details & Description</h1>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <!-- Vertical Form -->
                            <form method="POST" action="action="{{ route('addreceipedetails.store') }}"" class="row g-3 mt-2" enctype="multipart/form-data">
                                @csrf
                                <div class="col-12">
                                    <label for="recipeSelect" class="form-label">Select Recipe</label>

                                    <select id="recipeSelect" class="form-select" name="recipe">
                                        @foreach($recipes as $recipe)
                                            <option value="{{ $recipe->id }}">{{ $recipe->recipesname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="fw-bold mb-2">SAMOSA - Enter Details</label>
                                </div>
                                <div class="col-12">
                                    <label for="recipeDescription" class="form-label fw-bold">Recipe Description</label>

                                    <div class="form-floating">
                                    <textarea class="form-control" placeholder="Receipe Description" name="recipeDescription" id="recipeDescription" style="height: 100px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="receipeInstruction" class="form-label fw-bold">Recipe Making Instruction</label>
                                    <div class="form-floating">
                                    <textarea class="form-control" placeholder="Receipe Making Instruction" name="receipeInstruction" id="receipeInstruction" style="height: 100px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="receipevideo" class="form-label fw-bold">Recipe Making Video</label>
                                    <input type="file" name="receipevideo" class="form-control" id="receipevideo">
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

<!-- Template Main JS File -->
<script src="{{ asset('js/main.js') }}"></script>
