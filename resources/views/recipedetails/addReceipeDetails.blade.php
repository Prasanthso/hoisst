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
                            <form method="POST" action="{{ route('savereceipedetails.store') }}" class="row g-3 mt-2" enctype="multipart/form-data">
                                @csrf
                                <div class="col-12">
                                    <label for="recipeSelect" class="form-label">Select Recipe</label>
                                    <div class="col-12">
                                        <select id="recipeSelect" class="form-select" name="productId" aria-labelledby="recipeSelectLabel">
                                        <option selected disabled>Choose...</option>
                                        @foreach($recipes as $recipesitems)
                                        <option value="{{ $recipesitems->id }}" {{ old('productId') == $recipesitems->id ? 'selected' : '' }}>{{ $recipesitems->name }}</option>
                                        @endforeach
                                        </select>
                                        @error('productId')
                                        <span  id="error-productId" class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="fw-bold mb-2" id="selectedrecipesname"></label>
                                </div>
                                <div class="col-12">
                                    <label for="recipeDescription" class="form-label fw-bold">Recipe Description</label>

                                    <div class="form-floating">
                                    <textarea class="form-control" placeholder="Receipe Description" name="recipeDescription" id="recipeDescription" style="height: 100px;">{{ old('recipeDescription') }}</textarea>
                                    @error('recipeDescription')
                                    <span id="error-recipeDescription" class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                </div>
                                <div class="col-12">
                                    <label for="receipeInstruction" class="form-label fw-bold">Recipe Making Instruction</label>
                                    <div class="form-floating">
                                    <textarea class="form-control" placeholder="Receipe Making Instruction" name="receipeInstruction" id="receipeInstruction" style="height: 100px;">{{ old('receipeInstruction') }}</textarea>
                                    @error('receipeInstruction')
                                    <span id="error-receipeInstruction" class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                </div>
                                <div class="col-12">
                                    <label for="receipevideo" class="form-label fw-bold">Recipe Making Video</label>
                                    <input type="file" name="receipevideo" class="form-control" id="receipevideo">
                                    @error('receipevideo')
                                    <span id="error-receipevideo" class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        Save
                                    </button>
                                </div>
                            </form> <!-- Vertical Form -->
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const recipeSelect = document.getElementById('recipeSelect');
        const selectedRecipesName = document.getElementById('selectedrecipesname');
        // const recipedescr = document.getElementById('recipeDescription');
        // const recipeinstr = document.getElementById('receipeInstruction');

        $('#recipeSelect').select2({
            theme: 'bootstrap-5',
            placeholder: "Type or select a recipe...",
        });

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

        $('#recipeSelect').on("select2:select", function (e) {
        let selectedValue = $(this).val(); // Get selected value
        console.log(selectedValue);
        let errorMsg = document.getElementById("error-productId");
        if (selectedValue !== "Choose..." && errorMsg) {
            errorMsg.remove();
        }
        });
        // Clear error messages when user types or selects a value
        // clearErrorMessage('recipeSelect', 'error-productId');
        clearErrorMessage('recipeDescription', 'error-recipeDescription');
        clearErrorMessage('receipeInstruction', 'error-receipeInstruction');
        clearErrorMessage('receipevideo', 'error-receipevideo');

    });

    function clearErrorMessage(inputField, errorField) {
            document.getElementById(inputField)?.addEventListener('input', function() {
                document.getElementById(errorField).innerText = '';
            });
        }


</script>
