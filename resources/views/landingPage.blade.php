<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Hoisst landing page</title>
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

    <!-- Vendor CSS Files -->
    <link href="{{ asset('/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Laravel Mix CSS -->
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">

    <!-- Template Main CSS File -->
    <link href="{{ asset('/assets/css/style.css') }}" rel="stylesheet">

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Laravel Mix JS -->
    <script src="{{ mix('/js/app.js') }}" defer></script>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <!-- font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            /* Full height */
            width: 100vw;
            /* Full width */
        }

        /* Left half with solid color */
        .left-half {
            width: 50%;
            /* Half the width */
            background-color: rgb(0, 171, 223);
            /* Solid background color */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            font-family: Arial, sans-serif;
            font-size: 24px;
            padding: 20px;
        }

        .text {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
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

        .description {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
            margin-left: 220px;
            margin-right: 200px;
            text-align: left;
        }

        /* Login Button */
        .login-btn {
            padding: 10px 20px;
            background-color: white;
            color: rgb(0, 171, 223);
            font-size: 18px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-right: 220px;
        }

        .right-half {
            width: 50%;
            position: relative;
            overflow: hidden;
        }

        /* Background image with blue overlay */
        .right-half::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('./uploads/landing.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: 1;
        }

        .right-half::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(64, 181, 173, 0.5);
            /* rgb(173, 216, 230)  70, 130, 180  0, 128, 128   64, 181, 173*/

            z-index: 2;
        }

        .floating-img {
            position: absolute;
            z-index: 3;
            /* Keep floating images above overlay */
        }

        .img1 {
            top: 10%;
            right: 90%;
        }

        .img2 {
            top: 0%;
            left: 40%;
        }

        .img3 {
            bottom: 2%;
            right: 90%;
        }

        .img4 {
            bottom: 20%;
            left: 40%;
        }
    </style>
</head>

<body>

    <!-- Floating Images -->
    <img src="./uploads/cookie.png" alt="cookie" class="floating-img img2">
    <img src="./uploads/samosa.png" alt="samosa" class="floating-img img1">
    <img src="./uploads/cake.png" alt="cake" class="floating-img img3">
    <img src="./uploads/bread.png" alt="bread" class="floating-img img4">

    <!-- Left Half -->
    <div class="left-half">
        <div class="text">
            <img src="./uploads/symbol.png" alt="symbol">
            <p>Recipe Management <br> System</p>
        </div>
        <div class="description">
            <p>Organize, store, and streamline the preparation and scaling of authentic bakery recipes</p>
        </div>
        <button class="login-btn" onclick="window.location.href='{{ route('login') }}'">Login</button>
    </div>

    <!-- Right Half with Light Blue Shade -->
    <div class="right-half"></div>

</body>

</html>