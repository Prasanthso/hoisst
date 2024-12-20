<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CategoryItemController;
use App\Http\Controllers\RawMaterialController;

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

// Route::get('/rawmaterial', function () {
//     return view('rawMaterial');
// })->name('rawMaterial');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/category', [CategoryItemController::class, 'create'])->name('category.create');
Route::post('/categoryitem', [CategoryItemController::class, 'store'])->name('categoryitem.store');

Route::get('/addrawmaterial', [RawMaterialController::class, 'create'])->name('rawmaterial.create');
Route::post('/saverawmaterial', [RawMaterialController::class, 'store'])->name('rawmaterials.store');
Route::get('/editrawmaterial/{id}', [RawMaterialController::class, 'edit'])->name('rawMaterial.edit');
// Route to update a raw material
Route::put('/editrawmaterial/{id}', [RawMaterialController::class, 'update'])->name('rawMaterial.edit');

Route::get('/rawmaterial', [RawMaterialController::class, 'index'])->name('rawMaterials.index');
Route::post('/update-material-price/{id}', [RawMaterialController::class, 'updatePrice']);

Route::post('/update-raw-material-prices', [RawMaterialController::class, 'updatePrices'])->name('rawMaterial.updatePrices');
Route::get('/raw-material/price-details/{id}', [RawMaterialController::class, 'getRmPriceHistory'])->name('rawMaterial.priceHistory');