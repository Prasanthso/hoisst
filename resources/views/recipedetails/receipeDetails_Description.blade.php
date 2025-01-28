@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Details & Description</h1>
        <div class="d-flex align-items-center">
            <button class="btn btn-sm me-2" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;"  id="editRecipeBtn" data-id="">
                <i class="fas fa-edit" style="color: black;"></i>
            </button>
            <button class="btn btn-sm" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                <i class="fas fa-trash" style="color: red;"></i>
            </button>
            <a href="{{ 'addreceipedetails' }}" class='text-decoration-none ps-add-btn text-white py-1 px-4'>
                <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
            </a>
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
                <div class="col-6">
                    <select id="recipeSelect" class="form-select" aria-labelledby="recipeSelectLabel">
                    <option selected disabled>Choose...</option>
                    @foreach($recipes as $recipesitems)
                    <option value="{{ $recipesitems->id }}">{{ $recipesitems->name }}</option>
                    @endforeach
                    </select>
                    @error('productId')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
              <h5 class="fw-bold mb-4">
                <label id="selectedrecipesname"></label> </h5>

              <h6 class="fw-bold"> <label id="recipedesc"></label></h6>
              {{-- <p>
                At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborom et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.
              </p> --}}
              <p id="recipeDescription">
                 {{-- {{ $recipe->description }} --}}

              </p>
              <h6 class="fw-bold mt-4"><label id="recipeins"></label></h6>
              <ul id="recipeInstructions">
                {{-- <li>{{ $recipe->instructions }}</li> --}}
                {{-- <li>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum</li>
                <li>deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate</li>
                <li>non provident, similique sunt in culpa qui officia deserunt mollitia animi,</li>
                <li>id est laborom et dolorum fuga. Et harum quidem rerum facilis</li>
                <li>est et expedita distinctio.</li> --}}
              </ul>
            </div>
               <!-- Video Section -->
            <div class="mt-5">
                 <h6 class="fw-bold"><label id="recipevideo"></label></h6>
                    {{-- <hr /> --}}

                    <!-- Normal-sized Video -->
                <div class="row">
                    <!-- Video on the Left -->
                    <div class="col-md-6">
                        <div style="width: 100%; height: auto;">
                            <iframe
                            width="80%"
                            height="215"
                            src=""
                            title="Recipe Video"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                            </iframe>
                        </div>
                        <div>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#videoDetailsModal"><label id="videolinks"></label></a></li>
                        </ul>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>
</main><!-- End #main -->
<!-- Modal for Video Details -->
<div class="modal fade" id="videoDetailsModal" tabindex="-1" aria-labelledby="videoDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="videoDetailsModalLabel">Video Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table class="table table-bordered">
              <thead class="custom-header">
                  <tr>
                      <th style="color:white;">Changed By</th>
                      <th style="color:white;">Approved By</th>
                  </tr>
              </thead>
              <tbody id="recipehistroyTable">
                  {{-- <tr>
                      <td>Demo name</td>
                      <td>Demo name</td>
                  </tr>
                  <tr>
                    <td>Demo name</td>
                    <td>Demo name</td>
                </tr> --}}
              </tbody>
          </table>
        </div>
        {{-- <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div> --}}
      </div>
    </div>
  </div>

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
        const selectedRecipesName = document.getElementById('selectedrecipesname');
        const lblrecipedesc = document.getElementById('recipedesc');
        const lblrecipeins = document.getElementById('recipeins');
        const lblrecipevideo = document.getElementById('recipevideo');
        const lblvideolinks= document.getElementById('videolinks');
        const videoDetailsModalBody = document.querySelector('#videoDetailsModal .modal-body');

        const editRecipeBtn = document.getElementById('editRecipeBtn');

        const description = document.getElementById('recipeDescription');
        const instructionsList = document.getElementById('recipeInstructions');
        const videoIframe = document.querySelector('iframe');
        // console.log(recipeSelect); // Check if null

        $('#recipeSelect').select2({
            theme: 'bootstrap-5',
            placeholder: "Type or select a recipe...",
          });

        $('#recipeSelect').on('change', function () {
        const selectedValue = $(this).val();
        // console.log("Selected value:", selectedValue); // Debugging

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
                window.location.href = `/editrecipedetails/${recipeId}`;
            } else {
                alert('Please select a recipe to edit.');
            }
        });

    async function recipedata(productId)
    {
        // const productId = recipeSelect.value;
        if (productId) {
            try {

                editRecipeBtn.setAttribute('data-id', productId);

                const response = await fetch(`/recipes/${productId}`);
                if (!response.ok) throw new Error('Recipe not found');
                const recipe = await response.json();

                const selectedText = recipeSelect.options[recipeSelect.selectedIndex].text.trim();
                if(selectedText == null)
                {
                    selectedRecipesName.innerText = 'No recipe - DETAILS';
                    description.innerText = recipe.description || 'No description available.';
                }
                else{
                // Update UI with fetched data
                selectedRecipesName.innerText = selectedText + ' - DETAILS';

                lblrecipedesc.innerText = "Recipe Decsription";
                description.innerText = recipe.description;
                lblrecipeins.innerText = "Recipe Making Instruction";
                lblrecipevideo.innerText = "Recipe Making Video";
                lblvideolinks.innerText = "Video Details";
                // Update Instructions
                instructionsList.innerHTML = '';
                if (recipe.instructions) {
                    const instructions = recipe.instructions.split('.'); // Assuming instructions are period-separated
                    instructions.forEach(instruction => {
                        if (instruction.trim()) {
                            const li = document.createElement('li');
                            li.innerText = instruction.trim();
                            instructionsList.appendChild(li);
                        }
                    });
                }

                await recipehistory(productId);

                }
                // Update Video
                if (recipe.video_path) {
                    videoIframe.src = recipe.video_path;
                } else {
                    videoIframe.src = '';
                    videoIframe.innerText = 'No video available.';
                }

            } catch (error) {
                console.error(error);
                selectedRecipesName.innerText = 'No recipe details.';
                description.innerText = '';
                instructionsList.innerHTML = '';
                videoIframe.src = '';
            }
        }
        else
        {
            editRecipeBtn.setAttribute('data-id', '');
        }
    }

    async function recipehistory(productId) {
    const recipeHistoryTable = document.getElementById('recipehistroyTable');
    if (!productId) {
        alert('Please select a recipe first.');
        return;
    }

    try {
        // Fetch Recipe History
        const historyResponse = await fetch(`/recipe-history/${productId}`);
        if (!historyResponse.ok) throw new Error('History not found');
        const history = await historyResponse.json();
        // console.log(history);
        // Populate History Table
        recipeHistoryTable.innerHTML = ''; // Clear table first
        if (history.length > 0) {
            history.forEach(item => {
                const row = `
                    <tr>
                        <td>${item.changed_by}</td>
                        <td>${item.approved_by}</td>
                    </tr>
                `;
                recipeHistoryTable.innerHTML += row;
            });
        } else {
            recipeHistoryTable.innerHTML = '<tr><td colspan="2">No history available.</td></tr>';
        }
    } catch (error) {
        // console.error("Error fetching history:", error);
        recipeHistoryTable.innerHTML = '<tr><td colspan="2">Error fetching history.</td></tr>';
    }
}
});

</script>

<!-- Template Main JS File -->
<script src="{{ asset('js/main.js') }}"></script>

<!--
if (recipeSelect) {
    recipeSelect.addEventListener('input', async () => {

        const productId = recipeSelect.value;

        if (productId) {
            try {

                editRecipeBtn.setAttribute('data-id', productId);

                const response = await fetch(`/recipes/${productId}`);
                if (!response.ok) throw new Error('Recipe not found');
                const recipe = await response.json();

                const selectedText = recipeSelect.options[recipeSelect.selectedIndex].text.trim();
                if(selectedText == null)
                {
                    selectedRecipesName.innerText = 'No recipe - DETAILS';
                    description.innerText = recipe.description || 'No description available.';
                }
                else{
                // Update UI with fetched data
                selectedRecipesName.innerText = selectedText + ' - DETAILS';

                lblrecipedesc.innerText = "Recipe Decsription";
                description.innerText = recipe.description;
                lblrecipeins.innerText = "Recipe Making Instruction";
                lblrecipevideo.innerText = "Recipe Making Video";
                lblvideolinks.innerText = "Video Details";
                // Update Instructions
                instructionsList.innerHTML = '';
                if (recipe.instructions) {
                    const instructions = recipe.instructions.split('.'); // Assuming instructions are period-separated
                    instructions.forEach(instruction => {
                        if (instruction.trim()) {
                            const li = document.createElement('li');
                            li.innerText = instruction.trim();
                            instructionsList.appendChild(li);
                        }
                    });
                }

                await recipehistory(productId);

                }
                // Update Video
                if (recipe.video_path) {
                    videoIframe.src = recipe.video_path;
                } else {
                    videoIframe.src = '';
                    videoIframe.innerText = 'No video available.';
                }

            } catch (error) {
                console.error(error);
                selectedRecipesName.innerText = 'No recipe details.';
                description.innerText = '';
                instructionsList.innerHTML = '';
                videoIframe.src = '';
            }
        }
        else
        {
            editRecipeBtn.setAttribute('data-id', '');
        }

    });
} -->

