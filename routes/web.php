<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CategoryItemController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\RecipeController;

use App\Http\Controllers\PackingMaterialController;
use App\Http\Controllers\OverheadController;
use App\Http\Controllers\ProductController;


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

Route::get('/rawmaterial', [RawMaterialController::class, 'index'])->name('rawMaterials.index');
Route::get('/addrawmaterial', [RawMaterialController::class, 'create'])->name('rawmaterial.create');
Route::post('/saverawmaterial', [RawMaterialController::class, 'store'])->name('rawmaterials.store');
Route::get('/editrawmaterial/{id}', [RawMaterialController::class, 'edit'])->name('rawMaterial.edit');
Route::put('/editrawmaterial/{id}', [RawMaterialController::class, 'update'])->name('rawMaterial.edit');

Route::post('/update-material-price/{id}', [RawMaterialController::class, 'updatePrice']);

Route::post('/update-raw-material-prices', [RawMaterialController::class, 'updatePrices'])->name('rawMaterial.updatePrices');
Route::get('/raw-material/price-details/{id}', [RawMaterialController::class, 'getRmPriceHistory'])->name('rawMaterial.priceHistory');

Route::get('/packingmaterial', [PackingMaterialController::class, 'index'])->name('packingMaterials.index');
Route::get('/addpackingmaterial', [PackingMaterialController::class, 'create'])->name('packingmaterial.create');
Route::post('/savepackingmaterial', [PackingMaterialController::class, 'store'])->name('packingmaterials.store');
Route::get('/editpackingmaterial/{id}', [PackingMaterialController::class, 'edit'])->name('packingMaterial.edit');
Route::put('/editpackingmaterial/{id}', [PackingMaterialController::class, 'update'])->name('packingMaterial.edit');

Route::post('/update-packing-material-prices', [PackingMaterialController::class, 'updatePrices'])->name('packingMaterial.updatePrices');
Route::get('/packing-material/price-details/{id}', [PackingMaterialController::class, 'getPmPriceHistory'])->name('packingMaterial.priceHistory');


Route::get('/overheads', [OverheadController::class, 'index'])->name('overheads.index');
Route::get('/addoverheads', [OverheadController::class, 'create'])->name('overheads.create');
Route::post('/saveoverheads', [OverheadController::class, 'store'])->name('overheads.store');
Route::get('/editoverheads/{id}', [OverheadController::class, 'edit'])->name('overheads.edit');
Route::put('/editoverheads/{id}', [OverheadController::class, 'update'])->name('overheads.edit');

Route::post('/update-overheads-prices', [OverheadController::class, 'updatePrices'])->name('overheads.updatePrices');
Route::get('/overheads/price-details/{id}', [OverheadController::class, 'getOhPriceHistory'])->name('overheads.priceHistory');


Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/addproduct', [ProductController::class, 'create'])->name('products.create');
Route::post('/saveproduct', [ProductController::class, 'store'])->name('products.store');
Route::get('/editproduct/{id}', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/editproduct/{id}', [ProductController::class, 'update'])->name('products.edit');

Route::post('/update-products-prices', [ProductController::class, 'updatePrices'])->name('products.updatePrices');
Route::get('/products/price-details/{id}', [ProductController::class, 'getPdPriceHistory'])->name('products.priceHistory');

// Route::get('/receipe-details-description', function () {
//     return view('receipeDetails_Description');
// })->name('receipedd');

Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');

Route::get('/receipedetails', [RecipeController::class, 'index'])->name('receipedetails.index');
Route::get('/addreceipedetails', [RecipeController::class, 'create'])->name('addreceipedetails.create');
Route::post('/savereceipedetails', [RecipeController::class, 'store'])->name('savereceipedetails.store');
// Route::get('/recipe/{id}', [RecipeController::class, 'show'])->name('recipe.show');
Route::get('/recipes/{id}', [RecipeController::class, 'fetchRecipeDetails'])->name('recipe.fetchDetails');
