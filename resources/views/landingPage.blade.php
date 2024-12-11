<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoisst Landing page</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh; /* Full height */
            width: 100vw; /* Full width */
        }

        /* Left half with solid color */
        .left-half {
            width: 50%; /* Half the width */
            background-color: rgb(0, 171, 223); /* Solid background color */
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
            background-color: rgba(64, 181, 173, 0.5); /* rgb(173, 216, 230)  70, 130, 180  0, 128, 128   64, 181, 173*/ 

            z-index: 2;
        }

        .floating-img {
            position: absolute;
            z-index: 3; /* Keep floating images above overlay */
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
