<?php

// use Illuminate\Support\Facades\Route;



// Route::get('/', function () {
//     return view('landingPage');
// });

// Route::get('/landing',[LoginController::class])->name('landing');

// Route::view('/login', 'LoginController.login')->name('login');


// use App\Http\Controllers\LoginController;

// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
// Route::post('/login', [LoginController::class, 'verifyLogin'])->name('login.verify');

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

// Landing Page
Route::get('/', function () {
    return view('landingPage');
})->name('landing');

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login'); // Serves the login form
Route::post('/login', [LoginController::class, 'verifyLogin'])->name('login.verify'); // Verifies credentials
