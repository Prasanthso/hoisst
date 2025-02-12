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
            <div id="success-message" class="alert alert-success">{{ session('success') }}</div>
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
            <div class="table-responsive">
                <table class="table table-bordered mt-2" id="recipeTable" style="display: none; width:70%;">
                    <thead class="custom-header">
                        <tr>
                            <th>Product Name</th>
                            <th>Rm Cost</th>
                            <th>Totalcost</th>
                            <th>Margin</th>
                            <th>Suggeted Price</th>
                            <th>Suggeted MRP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Recipe data will be dynamically inserted here -->
                    </tbody>
                </table>
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
        // const tableBody = document.querySelector("#recipeTable tbody");
        const table = document.getElementById("recipeTable");
        const tableBody = table.querySelector("tbody");

        $('#recipeSelect').select2({
            theme: 'bootstrap-5',
            placeholder: "Type or select a recipe...",
          });

        $('#recipeSelect').on('change', function () {
        const selectedValue = $(this).val();
        console.log("Selected Overall-costing ID:", selectedValue);
        tableBody.style.display = "table-row-group";
        if (selectedValue) {
            recipedata(selectedValue);
        } else {
            console.log("No Overall-costing recipe selected.");
        }
        });

        /* if edit icon clicked */
        editCostingBtn.addEventListener('click', () => {
            const recipeId = editRecipeBtn.getAttribute('data-id');

            if (recipeId) {
                // Redirect to the edit recipe page with the selected recipe ID
                window.location.href = `/editoverallcosting/${recipeId}`;
            } else {
                alert('Please select a recipe to edit.');
            }
        });

        deleteCostingBtn.addEventListener("click", () => {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
            const recipeId = deleteCostingBtn.getAttribute("data-id");

            if (!recipeId) {
                alert("Please select a recipe to delete.");
                return;
            }

            if (!confirm("Are you sure you want to delete?")) {
                return;
            }

            console.log("Deleting over-all-costing ID:", recipeId);

            if (recipeId) {
                fetch(`/deleteoverallcosting`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": token
                    },
                    body: JSON.stringify({ ids: [recipeId] }) // Send as an array
                })
                .then(response => response.json().then(data => ({ status: response.status, body: data }))) // Capture status + body
                .then(result => {
                    console.log("Server Response:", result); // Debugging
                    if (result.status === 200 && result.body.success) {
                        alert("Selected recipe's overall costing deleted successfully!");
                        location.reload();
                    } else {
                        alert("Failed to delete recipe. Server message: " + result.body.message);
                    }
                })
                .catch(error => {
                    console.error("Error deleting recipe's overall costing:", error);
                    alert("An error occurred. Please try again.");
                });
            } else {
                alert("Please select a recipe to delete.");
            }
        });

        setTimeout(function () {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 5000);

        async function recipedata(recipeId)
        {
            if (recipeId) {
            try {

                editCostingBtn.setAttribute('data-id', recipeId);
                deleteCostingBtn.setAttribute('data-id', recipeId);

                const response = await fetch(`/showoverallcosting/${recipeId}`);
                if (!response.ok) throw new Error('Recipe not found');
                const recipe = await response.json();
                console.log(recipe);
                // const table = document.getElementById("recipeTable");
                // const tableBody = table.querySelector("tbody");
                tableBody.innerHTML = "";   // Clear table before adding new data

                if (recipe.data && recipe.data.length > 0) {
                    recipe.data.forEach((item) => {
                        const row = `<tr>
                            <td><a href="/editoverallcosting/${item.id}" style="color: black;font-size:16px;text-decoration: none;">${item.product_name || '-'}</a></td>
                            <td>${item.rm_cost_unit || '-'}</td>
                            <td>${item.total_cost || '-'}</td>
                            <td>${item.margin || '-'}</td>
                            <td>${item.sell_rate || '-'}</td>
                            <td>${item.present_mrp || '-'}</td>
                        </tr>`;
                        tableBody.innerHTML += row;

                    });
                } else {
                    tableBody.innerHTML = "<tr><td colspan='6'>No data available</td></tr>";
                }
                table.style.display = "table";
                // window.location.href = `/editoverallcosting/${recipeId}`;

            }catch (error) {alert(error);}
        }
        }
    });
</script>

<!-- Template Main JS File -->
<script src="{{ asset('js/main.js') }}"></script>


