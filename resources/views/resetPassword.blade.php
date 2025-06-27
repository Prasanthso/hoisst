<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Hoisst Reset Password</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('/assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('/assets/img/newlogo.png') }}" rel="newlogo">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <!-- Bootstrap 4 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for Eye Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #00bfff;
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

        .custom-container {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }

        .custom-input {
            height: 60px;
            border-radius: 30px;
            padding-right: 45px;
        }

        .custom-btn {
            border-radius: 30px;
            height: 40px;
            width: 160px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #555;
        }
    </style>
</head>

<body>
    <!-- Floating Images -->
    <img src="https://hoisst.trackmargin.com/uploads/cookie.png" alt="cookie" class="floating-img img2">
    <img src="https://hoisst.trackmargin.com/uploads/samosa.png" alt="samosa" class="floating-img img1">
    <img src="https://hoisst.trackmargin.com/uploads/cake.png" alt="cake" class="floating-img img3">
    <img src="https://hoisst.trackmargin.com/uploads/bread.png" alt="bread" class="floating-img img4">

    <div class="container text-center">
        <div class="custom-container mx-auto text-left">
            <div class="d-flex justify-content-center mb-3">
                <img src="/assets/img/logo.svg" alt="Recipe Management System Logo" style="height: 70px;">
            </div>
            <h2 class="text-center">Change Password</h2>
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group position-relative">
                    <input type="email" name="email" value="{{ $email }}" required placeholder="Email"
                        class="form-control custom-input">
                </div>

                <div class="form-group position-relative">
                    <input type="password" name="password" id="password" required
                        placeholder="New Password"
                        pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[@#$%^&+=!]).{8,}$"
                        title="Password must be at least 8 characters, include a letter, a number, and a special character"
                        class="form-control custom-input">
                    <span class="toggle-password" onclick="togglePassword('password', this)">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>

                <div class="form-group position-relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        placeholder="Confirm Password"
                        pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[@#$%^&+=!]).{8,}$"
                        title="Password must be at least 8 characters, include a letter, a number, and a special character"
                        class="form-control custom-input">
                    <span class="toggle-password" onclick="togglePassword('password_confirmation', this)">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary custom-btn">Reset Password</button>
                </div>
            </form>

            @if ($errors->any())
            <ul class="text-danger mt-3">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            @endif

        </div>
    </div>

    <!-- Scripts -->
    <script>
        function togglePassword(fieldId, toggleIcon) {
            const input = document.getElementById(fieldId);
            const icon = toggleIcon.querySelector('i');

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>

    <!-- Bootstrap 4 JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>