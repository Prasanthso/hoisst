<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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
                <img src="/assets/img/RMSLogo.png" alt="Recipe Management System Logo" style="height: 70px;">
            </div>
            <h2 class="text-center">Forget Password</h2>
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            <!-- Display Success Message -->
            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <!-- <input type="email" name="email" placeholder="Enter your email" required> -->
                <div class="form-group">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                        class="form-control custom-input"
                        required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary custom-btn">Send Reset Link</button>
                </div>
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