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
                            <div class="alert alert-success alert-block">
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
                                type="text"
                                name="sid"
                                id="inputSid"
                                class="form-control @error('sid') is-invalid @enderror"
                                placeholder="Enter SID key"
                                value="{{ old('sid', config('services.twilio.sid')) }}" required>

                            @error('sid')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="inputToken">Auth Token:</label>
                            <input
                                name="authtoken"
                                id="inputToken"
                                class="form-control @error('message') is-invalid @enderror"
                                placeholder="Enter token key"
                                value="{{ old('sid', config('services.twilio.token')) }}" required>

                            @error('authtoken')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="inputtwiliophone">My Twilio Phone Number:</label>
                            <input
                                name="twiliophone"
                                id="inputtwiliophone"
                                class="form-control @error('message') is-invalid @enderror"
                                placeholder="Enter twilio phone"
                                value="{{ old('sid', config('services.twilio.whatsappphone')) }}" required>

                            @error('twiliophone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-success btn-submit"  id="sendBtn">Store</button>
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

