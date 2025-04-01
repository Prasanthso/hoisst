@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <section class="section dashboard">
       <div class="container">

        <div class="row">
            <div class="col-md-9">

                <div class="card">
                  <div class="custom-header text-center" style="color:white;">
                    <h2> Twilio Authentication</h2>
                  </div>
                  <div class="card-body">
                    <form action="{{ route('update.keys') }}" method="POST">
                        @csrf

                        @if ($message = Session::get('success'))
                            <div id="success-message" class="alert alert-success alert-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif

                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger alert-block">
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label" for="inputSid">Account SID:</label>
                            <input
                                type="password"
                                name="sid"
                                id="inputSid"
                                class="form-control @error('sid') is-invalid @enderror"
                                placeholder="Enter SID key"
                                value="{{ old('sid', config('services.twilio.sid')) }}" required>

                                {{-- <span class="input-group-text" onclick="toggleSidVisibility()" style="cursor: pointer; padding: 0.375rem 0.75rem;">
                                    <i id="toggleIcon" class="bi bi-eye"></i>
                                </span> --}}
                            @error('sid')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="inputToken">Auth Token:</label>
                            <input
                              type="password"
                                name="token"
                                id="inputToken"
                                class="form-control @error('message') is-invalid @enderror"
                                placeholder="Enter token key"
                                value="{{ old('token', config('services.twilio.token')) }}" required>
                                {{-- <span class="input-group-text" onclick="toggleSidVisibility()" style="cursor: pointer;">
                                    <i id="toggleIcon" class="bi bi-eye"></i>
                                </span> --}}
                            @error('token')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="inputtwiliophone">My Twilio Phone Number:</label>
                            <input
                                type="password"
                                name="whatsappphone"
                                id="inputtwiliophone"
                                class="form-control @error('message') is-invalid @enderror"
                                placeholder="+919876543210"
                                value="{{ old('whatsappphone', config('services.twilio.whatsappphone')) }}" required>

                                {{-- <span class="input-group-text" onclick="toggleSidVisibility()" style="cursor: pointer;">
                                    <i id="toggleIcon" class="bi bi-eye"></i>
                                </span> --}}
                            @error('whatsappphone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-success btn-submit"  id="sendBtn">Save</button>
                        </div>
                    </form>
                  </div>
                </div>
            </div>
        </div>
    </div>

</section>

</main><!-- End #main -->
@endsection

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<script>
    document.addEventListener("DOMContentLoaded", function() {

        setTimeout(function() {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 2000);
    });
    function toggleSidVisibility() {
    let input = document.getElementById("inputSid");
    let button = event.target;

    if (input.type === "password") {
        input.type = "text";
        button.innerText = "🙈 Hide";
    } else {
        input.type = "password";
        button.innerText = "👁 Show";
    }
}
</script>
