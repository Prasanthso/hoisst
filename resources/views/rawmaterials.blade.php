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
        <h3 class="mb-4">ADD RAW MATERIAL CATEGORY FOR</h3>
        <form>
            <div class="form-group">
                <label for="rawmaterial" class="font-weight-bold">Raw Material Name</label>
                <input
                    type="text"
                    class="form-control"
                    id="rawmaterial"
                    placeholder="Enter raw material name">
            </div>
            <div class="form-group">
                <label for="rmdescription" class="font-weight-bold">Raw Material Description</label>
                <textarea
                    class="form-control"
                    id="rmdescription"
                    rows="4"
                    placeholder="Enter description"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Bootstrap 4 JS and Dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
