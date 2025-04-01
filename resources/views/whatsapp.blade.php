@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <section class="section dashboard">
    <div class="container">

        <div class="row">
            <div class="col-md-9">

                <div class="card">
                  <div class="card-header">
                    <h2> Send Whatsapp message </h2>
                  </div>
                  <div class="card-body">
                    <form method="POST" action="{{ route('whatsapp.post') }}">

                        {{ csrf_field() }}

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
                            <label class="form-label" for="inputName">Phone:</label>
                            <input
                                type="text"
                                name="phone"
                                id="inputName"
                                class="form-control @error('phone') is-invalid @enderror"
                                placeholder="+919876543210" required>

                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="inputName">Message:</label>
                            <textarea
                                name="message"
                                id="inputName"
                                class="form-control @error('message') is-invalid @enderror"
                                placeholder="Enter Message" required></textarea>

                            @error('message')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-success btn-submit"  id="sendBtn">Send Message</button>
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

