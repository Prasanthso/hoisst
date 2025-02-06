@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>OverAll Costing</h1>
        <div class="d-flex align-items-center">
            <button class="btn btn-sm me-2" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;"  id="editRecipeBtn" data-id="">
                <i class="fas fa-edit" style="color: black;"></i>
            </button>
            <button class="btn btn-sm" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;" id="deleteRecipebtn" data-id="">
                <i class="fas fa-trash" style="color: red;"></i>
            </button>
            <a href="{{ 'addoverallcosting' }}" class='text-decoration-none ps-add-btn text-white py-1 px-4'>
                <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i>Add</button>
            </a>
            <!--<button id="exportPdf" class="btn btn-primary">Export</button>-->

        </div>
    </div><!-- End Page Title -->
    <section class="section dashboard">
        <div class="container mt-5">
            <div class="mb-4">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            </div>
            <div class="mb-4">
                <label for="recipeSelect" id="recipeSelectLabel" class="form-label">Select Recipe</label>
                <div class="col-8">
                    <select id="recipeSelect" class="form-select select2" aria-labelledby="recipeSelectLabel">
                    <option selected disabled>Choose...</option>
                    @foreach($costings as $recipe)
                    <option value="{{ $recipe->id }}">{{ $recipe->product_name }}</option>
                    @endforeach
                    </select>
                    @error('productId')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
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

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const recipeSelect = document.getElementById('recipeSelect');
        const editCostingBtn = document.getElementById('editRecipeBtn');
        const deleteCostingBtn = document.getElementById('deleteRecipebtn');

        $('#recipeSelect').select2({
            theme: 'bootstrap-5',
            placeholder: "Type or select a recipe...",
          });

        $('#recipeSelect').on('change', function () {
        const selectedValue = $(this).val();
            console.log(selectedValue);
        if (selectedValue) {
            recipedata(selectedValue);
        } else {
            console.log("No recipe selected.");
        }
        });

        /* if edit icon clicked */
        editRecipeBtn.addEventListener('click', () => {
            const recipeId = editRecipeBtn.getAttribute('data-id');

            if (recipeId) {
                // Redirect to the edit recipe page with the selected recipe ID
                window.location.href = `/editoverallcosting/${recipeId}`;
            } else {
                alert('Please select a recipe to edit.');
            }
        });

        async function recipedata(recipeId)
        {
            if (recipeId) {
            try {

                editCostingBtn.setAttribute('data-id', recipeId);
                deleteCostingBtn.setAttribute('data-id', recipeId);
                window.location.href = `/editoverallcosting/${recipeId}`;

            }catch (error) {alert(error);}
        }
        }
    });
</script>

<!-- Template Main JS File -->
<script src="{{ asset('js/main.js') }}"></script>


