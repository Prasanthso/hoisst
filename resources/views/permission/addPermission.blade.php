@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Create Permission</h1>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
        @endif --}}
        <div class="col-lg-12">
            <div class="col-lg-6">

                <div class="card">
                    {{-- @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}
                </div>
                @endif --}}
                <div class="card-body">
                    <!-- Vertical Form -->
                    <form method="POST" action="{{ route('Permission.store') }}" class="row g-3 mt-2">
                        @csrf
                        <div class="col-12">
                            <label for="name" class="form-label">Permission Name</label>
                            <input type="text" class="form-control" id="name" name="name">
                            @error('itemname')
                            <span id="itemname-error" class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary" id="btnSubmit">Save</button>
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
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const itemNameInput = document.getElementById("itemname");
        // const desc = document.getElementById("floatingTextarea");

        itemNameInput.addEventListener("input", function() {
            let errorMsg = document.getElementById("itemname-error");
            if (this.value !== "") {
                if (errorMsg) {
                    errorMsg.remove();
                } // Remove error message
            }
        });

    });
</script>