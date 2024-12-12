<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CategoryItemController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('landingPage');
})->name('landing');

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login'); // Serves the login form
Route::post('/login', [LoginController::class, 'verifyLogin'])->name('login.verify'); // Verifies credentials

// Route::get('/', function () {
//     return view('category');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/rawmaterial', function () {
    return view('rawMaterial');
})->name('rawMaterial');

// Route::get('/category', function () {
//     return view('addcategory');
// })->name('category');

// Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
// Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');

// Route::get('/category', function () {
//     $categories = DB::table('categories')->get();
//     // dd($categories); // Fetch all categories from the database
//     return view('addcategory', compact('categories')); // Pass categories to the view
// })->name('category');

Route::get('/category', [CategoryItemController::class, 'create'])->name('category.create');
Route::post('/categoryitem', [CategoryItemController::class, 'store'])->name('categoryitem.store');

