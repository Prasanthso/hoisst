@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Categories</h1>
        <a href="{{ 'category' }}" class='text-decoration-none ps-add-btn text-white py-1 px-4'>
            <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
        </a>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            @if(session('success'))
            <div id="success-message" class="alert alert-success">{{ session('success') }}</div>
            @endif
            <!-- Left side columns -->
            <div class="col-lg-2 px-4">
                <!-- Categories Section -->
                <div class="card" style="background-color: #EEEEEE;">
                    <div class="card-body">
                        <h5 class="card-title">Categories</h5>
                        <div class="row mb-3">
                            <div class="col-sm-10">
                                @foreach($categories as $category)
                                <div class="form-check">
                                    <input
                                        name="categories[]"
                                        class="form-check-input category-checkbox"
                                        type="checkbox"
                                        data-id="category_{{ $category->id }}"
                                        value="{{ $category->id }}"
                                        {{-- data-category-name="{{ $category->itemname }}" --}}>
                                    <label class="form-check-label" for="categories{{ $category->id }}">
                                        {{ $category->categoryname }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div><!-- End Categories Section -->
            </div><!-- End Left side columns -->

            <!-- <div class="col-lg-1"></div> -->

            <!-- Right side columns -->
            <div class="col-lg-8">
                <div class="row">
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end mb-2 action-buttons">
                        <button class="btn btn-sm edit-table-btn me-2" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                            <i class="fas fa-edit" style="color: black;"></i>
                        </button>
                        <button class="btn btn-sm delete-table-btn" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                            <i class="fas fa-trash" style="color: red;"></i>
                        </button>
                    </div>

                    <!-- Bordered Table -->
                    <table class="table table-bordered" >
                        <thead class="custom-header">
                            <tr>
                                <th class="head" scope="col">
                                    <input type="checkbox" id="select-all" class="form-check-input">
                                </th>
                                <th scope="col" style="color:white;">S.NO</th>
                                <th scope="col" style="color:white;">Category Items</th>
                                <th scope="col" style="color:white;">Description</th>
                                <th scope="col" style="color:white;">Created User</th>

                            </tr>
                        </thead>
                        <tbody id="catagoriesTable">
                            @foreach ($categoriesitems as $index => $material)
                            <tr data-id="{{ $material->id }}">
                                <td>
                                    <input type="checkbox" class="form-check-input row-checkbox">
                                </td>
                                <td>{{ $index + 1 }}.</td> <!-- Auto-increment S.NO -->
                                <td><a href="{{ route('categoryitem.edit', $material->id) }}" style="color: black;font-size:16px;text-decoration: none;">{{ $material->itemname }}</a></td>
                                <td>{{ $material->description }}</td>
                                <td>{{ $material->created_user }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <!-- Content like "Showing 1 to 10 of 50 entries" -->
                            Showing {{ $categoriesitems->firstItem() }} to {{ $categoriesitems->lastItem() }} of {{ $categoriesitems->total() }} entries
                        </div>
                        <div>
                            <!-- Pagination Links -->
                            {{ $categoriesitems->links('pagination::bootstrap-5') }}
                            {{-- {{ $rawMaterials->appends(['category_ids' => implode(',', request()->input('category_ids', []))])->links('pagination::bootstrap-5') }} --}}

                        </div>
                    </div>
                    <!-- End Bordered Table -->
                </div>
            </div><!-- End Right side columns -->
        </div>
    </section>

    <!-- Modal -->
    <!-- <div class="modal fade" id="priceModal" tabindex="-1" aria-labelledby="priceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="priceModalLabel">Price Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-price">
                        <thead class="custom-header">
                            <tr>
                                <th style="color:white;">Effective From</th>
                                <th style="color:white;">Price</th>
                                <th style="color:white;">Updated By</th>
                            </tr>
                        </thead>
                        <tbody id="priceDetailsTable">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> -->

</main><!-- End #main -->
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
    const tableBody = document.getElementById("catagoriesTable");
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');

    // Listen for changes to any category checkbox
    categoryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const selectedCategories = Array.from(
                document.querySelectorAll('.category-checkbox:checked')
            ).map(cb => cb.value);

            const queryParams = new URLSearchParams({
                category_ids: selectedCategories.join(','),
            });

            // Construct the URL dynamically based on selected categories
            const url = `/showcategoryitem?${queryParams.toString()}`;

            // Fetch updated data from server
            fetch(url, {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Clear existing table content
                tableBody.innerHTML = '';

                // Populate the table with new data
                data.categoriesitems.data.forEach((item, index) => {
                    tableBody.innerHTML += `
                        <tr>
                            <td><input type="checkbox" class="form-check-input category-checkbox" value="${item.id}"></td>
                            <td>${index + 1}.</td>
                            <td><a href="/categoryitem/edit/${item.id}" style="color: black; font-size:16px; text-decoration: none;">${item.itemname}</a></td>
                            <td>${item.description}</td>
                            <td>${item.created_user}</td>
                        </tr>
                    `;
                });

                // Re-attach event listeners for dynamically added checkboxes
                document.querySelectorAll('.category-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', updateSelectedCategories);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while fetching category items.');
            });
        });
    });

    // Optionally, a function to update selected category values in the URL
    const updateSelectedCategories = () => {
        const selectedCategories = Array.from(
            document.querySelectorAll('.category-checkbox:checked')
        ).map(cb => cb.value);
        console.log("Selected categories: ", selectedCategories);
    };

    setTimeout(function () {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 5000);
});

   </script>

<!-- Vendor JS Files -->
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>

<!-- Template Main JS File -->
<!--<script src="{{ asset('js/main.js') }}"></script> -->