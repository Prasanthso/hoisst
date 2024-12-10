<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Category</title>
    <!-- Bootstrap 4 CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3 class="mb-4">ADD CATEGORY FOR</h3>
        <div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <p class="font-weight-bold">Add Category for</p>
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    @foreach ($categories as $index => $category)
                        <div class="form-check mb-2">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="category"
                                id="category{{ $index }}"
                                value="{{ $category }}">
                            <label class="form-check-label" for="category{{ $index }}">
                                {{ $category }}
                            </label>
                        </div>
                    @endforeach
                    <button type="submit" class="btn btn-primary mt-3 px-5" style="border-radius: 18px;">Okay</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 4 JS and Dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
