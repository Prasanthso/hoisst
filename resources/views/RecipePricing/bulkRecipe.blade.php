@extends('layouts.headerNav')

@section('content')
<main id="main" class="main">
    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Add Bulk Recipe</h1>
        <div class="row">
            <div class="mb-4">
                <a href="{{ asset('templates/bulk_recipe_template.xlsx') }}" download class="btn btn-success" data-bs-toggle="tooltip" title="Use category_values: raw_material, packing_material, overhead">
                    <i class="bi bi-download fs-4"></i> Download Template
                </a>

                <form method="POST" action="{{ route('bulk-recipe.import') }}" enctype="multipart/form-data" class="mt-4 p-4 border rounded shadow-sm bg-light">
                    @csrf

                    <div class="mb-3">
                        <label for="recipeFile" class="form-label fw-bold">Upload Recipe File</label>
                        <input type="file" name="file" id="recipeFile" class="form-control" required>
                        <div class="form-text">Accepted formats: .xlsx</div>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload me-1"></i> Upload Recipes
                    </button>
                </form>

            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <div class="px-4">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
        @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
    </div>

    <section class="section dashboard px-4">

        @if(!empty($skippedCodes))
        <div class="alert alert-warning">
            <strong>Skipped Codes:</strong>
            <ul>
                @foreach($skippedCodes as $code)
                <li>{{ $code }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(!empty($recipes) && $recipes->count() > 0)
        <h4>Imported Recipes</h4>
        <div class="table-responsive mt-3">
            <table class="table table-bordered text-center">
                <thead style="background-color: #eaf8ff;">
                    <tr>
                        <th>#</th>
                        <th>Recipe Code (RP)</th>
                        <th>Product Code (PD)</th>
                        <th>Product Name</th>
                        <th>UOM</th>
                        <th>Output</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recipes as $index => $recipe)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $recipe->product->name ?? '-' }}</td>
                        <td>{{ $recipe->rpcode }}</td>
                        <td>{{ $recipe->product->pdcode ?? '-' }}</td>
                        <td>{{ $recipe->uom }}</td>
                        <td>{{ $recipe->Output }}</td>
                        <td>{{ ucfirst($recipe->status) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @elseif(request()->isMethod('post'))
        <div class="alert alert-info">No recipes were imported.</div>
        @endif

    </section>
</main>
@endsection

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

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