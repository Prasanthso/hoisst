<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap 4 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
            width: 100px;
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
        <div class="text mb-4">
            <img src="./uploads/symbol.png" alt="symbol">
            <p>Recipe Management <br> System</p>
        </div>
        <div class="custom-container mx-auto text-left">
            <h2 class="text-center"><b>RMS LOGIN</b></h2>
            
            <!-- Display Success Message -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Display Error Message -->
            @if($errors->has('login_failed'))
                <div class="alert alert-danger">
                    {{ $errors->first('login_failed') }}
                </div>
            @endif

            <form action="{{ route('login.verify') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="username">UserName</label>
        <input 
            type="text" 
            id="username" 
            name="username" 
            class="form-control custom-input" 
            placeholder="rms2024@gmail.com" 
            required>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input 
            type="password" 
            id="password" 
            name="password" 
            class="form-control custom-input" 
            placeholder="Rms@1234" 
            pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[@#$%^&+=!]).{8,}$" 
            title="Password must be at least 8 characters long, include at least one letter, one number, and one special character like @, #, $, etc."
            required>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary custom-btn">Login</button>
    </div>
</form>

        </div>
    </div>

    <!-- Bootstrap 4 JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
