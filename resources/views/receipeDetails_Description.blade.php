@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Details & Description</h1>
        <a href="{{ 'receipedetails' }}" class='text-decoration-none ps-add-btn text-white py-1 px-4'>
            <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
        </a>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="container mt-5">
            <div class="mb-4">
                <label for="recipeSelect" class="form-label">Select Recipe</label>
                <div class="col-6">
                    <select id="recipeSelect" class="form-select">
                    {{-- <option value="samosa" selected>Samosa</option>
                    <option value="Puff">Puff</option>
                    <option value="Cake">Cake</option> --}}
                    <option selected disabled>Choose...</option>
                    @foreach($recipes as $recipesitems)
                    <option value="{{ $recipesitems->id }}">{{ $recipesitems->recipesname }}</option>
                    @endforeach
                    </select>
                    @error('recipeId')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
              <h5 class="fw-bold mb-4">
                <label for="selectedrecipesname" id="selectedrecipesname"> </label> - DETAILS</h5>

              <h6 class="fw-bold">Recipe Description</h6>
              <p>
                At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborom et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.
              </p>

              <h6 class="fw-bold mt-4">Recipe Making Instruction</h6>
              <ul>
                <li>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum</li>
                <li>deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate</li>
                <li>non provident, similique sunt in culpa qui officia deserunt mollitia animi,</li>
                <li>id est laborom et dolorum fuga. Et harum quidem rerum facilis</li>
                <li>est et expedita distinctio.</li>
              </ul>
            </div>

               <!-- Video Section -->
    <div class="mt-5">
        <h6 class="fw-bold">Recipe Making Video</h6>
        <hr />

         <!-- Normal-sized Video -->
         <div class="row">
            <!-- Video on the Left -->
            <div class="col-md-6">
              <div style="width: 100%; height: auto;">
                <iframe
                  width="80%"
                  height="215"
                  src="https://youtu.be/3OZn-iCGf5s?si=QGsQ9YIuyApDGCd5"
                  title="Recipe Video"
                  frameborder="0"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen>
                </iframe>
              </div>

            </div>

            <!-- Video Links on the Right -->
            <div class="col-md-6">
              <h6 class="fw-bold">Watch Related Videos:</h6>
              <ul class="list-unstyled">
                <li><a href="#" target="_blank" class="text-decoration-none">How to Make Samosa - Video 1</a></li>
                {{-- <li><a href="https://www.youtube.com/watch?v=VIDEO2" target="_blank" class="text-decoration-none">Samosa Recipe Tips - Video 2</a></li>
                <li><a href="https://www.youtube.com/watch?v=VIDEO3" target="_blank" class="text-decoration-none">Perfect Samosa Techniques - Video 3</a></li> --}}
              </ul>
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
    const recipeSelect = document.getElementById('recipeSelect');
    const selectedRecipesName = document.getElementById('selectedrecipesname');

    recipeSelect.addEventListener('change', () => {
        const selectedText = recipeSelect.options[recipeSelect.selectedIndex].text; // Get the selected text

        // Update the label with the selected recipe name
        selectedRecipesName.innerText = selectedText;
    });
</script>
