<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Hoisst Reset Password</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicons -->
    <link href="{{ asset('/assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('/assets/img/newlogo.png') }}" rel="newlogo">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Bootstrap 4 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: rgb(0, 171, 223);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .floating-img {
            position: absolute;
        }

        .img1 {
            top: 20%;
            right: 80%;
        }

        .img2 {
            top: 0%;
            left: 80%;
        }

        .img3 {
            bottom: 5%;
            right: 80%;
        }

        .img4 {
            bottom: 20%;
            left: 80%;
        }

        .text {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 60px;
            color: white;
        }

        .text img {
            width: 70px;
            height: 70px;
            margin-right: 10px;
        }

        .text p {
            font-size: 25px;
            margin: 0;
        }

        .custom-container {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            height: auto;
        }

        .custom-input {
            height: 60px;
            border-radius: 30px;
        }

        .custom-btn {
            border-radius: 30px;
            height: 40px;
            width: 160px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 20px;
        }

        .forgot-password {
            display: block;
            text-align: center;
            margin-top: 10px;
        }

        body {
            background-color: #00bfff;
            font-family: Arial, sans-serif;
        }

        .form-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            width: 300px;
            margin: auto;
            margin-top: 10%;
            text-align: center;
        }

        input {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
        }
    </style>
</head>

<body>
    <!-- Floating Images -->
    <img src="./uploads/cookie.png" alt="cookie" class="floating-img img2">
    <img src="./uploads/samosa.png" alt="samosa" class="floating-img img1">
    <img src="./uploads/cake.png" alt="cake" class="floating-img img3">
    <img src="./uploads/bread.png" alt="bread" class="floating-img img4">

    <!-- Centered Content -->
    <div class="container text-center">
        <div class="custom-container mx-auto text-left">
            <div class="d-flex justify-content-center mb-3">
                <img src="/assets/img/logo.svg" alt="Recipe Management System Logo" style="height: 70px;">
            </div>
            <h2 class="text-center">Change Password</h2>
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="email" name="email" value="{{ $email }}" required placeholder="Email" class="form-control custom-input">
                <input type="password" name="password" required placeholder="New Password" class="form-control custom-input">
                <input type="password" name="password_confirmation" required placeholder="Confirm Password" class="form-control custom-input">
                <button type="submit" class="btn btn-primary custom-btn">Reset Password</button>
            </form>

            @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            @endif

        </div>
    </div>

    <!-- Bootstrap 4 JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>