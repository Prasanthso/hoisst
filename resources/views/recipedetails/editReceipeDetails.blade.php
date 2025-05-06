@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Edit Details & Description</h1>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <!-- Vertical Form -->
                            <form action="{{ route('editrecipedetails.update', $editrecipe->rid) }}" method="POST" class="row g-3 mt-2" enctype="multipart/form-data"  id="recipeDetailsDescForm">
                                @csrf
                                @method('PUT')
                                <div class="col-12">
                                    <label for="recipeSelect" class="form-label">Select Recipe</label>
                                    <div class="col-6">
                                        <select id="recipeSelect" class="form-select" name="productId" aria-labelledby="recipeSelectLabel">
                                        <option value="{{ $editrecipe->product_id }}" selected disabled>{{ $editrecipe->name  }}</option>
                                        </select>
                                        @error('productId')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="fw-bold mb-2" id="selectedrecipesname"></label>
                                </div>
                                <div class="col-12">
                                    <label for="recipeDescription" class="form-label fw-bold">Recipe Description</label>

                                    <div class="form-floating">
                                    <textarea class="form-control" placeholder="Receipe Description" name="recipeDescription" id="recipeDescription" style="height: 100px;">{{$editrecipe->description}}</textarea>
                                    @error('recipeDescription')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                </div>
                                <div class="col-12">
                                    <label for="receipeInstruction" class="form-label fw-bold">Recipe Making Instruction</label>
                                    <div class="form-floating">
                                    <textarea class="form-control" placeholder="Receipe Making Instruction" name="receipeInstruction" id="receipeInstruction" style="height: 150px;" >{{$editrecipe->instructions}}</textarea>
                                    @error('receipeInstruction')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                </div>
                                <div class="col-12">
                                    <label for="receipevideo" class="form-label fw-bold">Recipe Making Video</label>
                                    <input type="file" name="receipevideo" class="form-control" id="receipevideo">
                                    @if($editrecipe->video_path)
                                    <small class="text-muted" id="old_receipevideo">
                                        Current Recipe: <a href="{{ asset($editrecipe->video_path) }}" target="_blank">View Recipe Video</a>
                                    </small>
                                @endif
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary">
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

<!-- Template Main JS File -->
<script src="{{ asset('js/main.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const recipeSelect = document.getElementById('recipeSelect');
        const selectedRecipesName = document.getElementById('selectedrecipesname');

        if (recipeSelect && selectedRecipesName) {
            recipeSelect.addEventListener('change', () => {
                const selectedText = recipeSelect.options[recipeSelect.selectedIndex].text.trim(); // Get the selected text

                // Check if a valid option is selected (not the disabled one)
                if (selectedText !== "Choose...") {
                    selectedRecipesName.innerText = selectedText + '-Enter DETAILS';
                } else {
                    selectedRecipesName.innerText = ""; // Reset if "Choose..." is selected
                }
            });

        }
    });
</script>
