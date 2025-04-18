<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CategoryItemController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\RecipeController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PackingMaterialController;
use App\Http\Controllers\OverheadController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RecipePricingController;
use App\Http\Controllers\RmForRecipeController;
use App\Http\Controllers\PmForRecipeController;
use App\Http\Controllers\OhForRecipeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\OverAllCostingController;
use App\Http\Controllers\PermissionController;
use App\Models\Overhead;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\TwilioController;

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

Route::resource('permission', App\Http\Controllers\PermissionController::class);
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

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/addcategory', [CategoryItemController::class, 'create'])->name('category.create');
Route::post('/categoryitem', [CategoryItemController::class, 'store'])->name('categoryitem.store');
Route::get('/showcategoryitem', [CategoryItemController::class, 'index'])->name('categoryitem.index');
Route::get('/editcategoryitem/{id}', [CategoryItemController::class, 'edit'])->name('categoryitem.edit');
Route::put('/editcategoryitem/{id}', [CategoryItemController::class, 'update'])->name('categoryitem.update');
Route::post('/deletecategory', [CategoryItemController::class, 'delete'])->name('categoryitem.delete');
Route::post('/confirmcategory', [CategoryItemController::class, 'deleteConfirmation'])->name('categoryitem.confirm');

Route::post('/categoryitem-import', [CategoryItemController::class, 'importExcel'])->name('categoryitem.import');
Route::get('/categoryitem-excel', function () {
    return Storage::download('public/excel_file/categoryitems_.xlsx');
});

// rawmaterials route
Route::get('/rawmaterial', [RawMaterialController::class, 'index'])->name('rawMaterials.index');
Route::get('/addrawmaterial', [RawMaterialController::class, 'create'])->name('rawmaterial.create');
Route::post('/saverawmaterial', [RawMaterialController::class, 'store'])->name('rawmaterials.store');
Route::get('/editrawmaterial/{id}', [RawMaterialController::class, 'edit'])->name('rawMaterial.edit');
Route::put('/editrawmaterial/{id}', [RawMaterialController::class, 'update'])->name('rawMaterial.edit');
Route::post('/deleterawmaterial', [RawMaterialController::class, 'delete'])->name('rawMaterial.delete');
Route::post('/confirmrawmaterial', [RawMaterialController::class, 'deleteConfirmation'])->name('rawMaterial.confirm');

Route::post('/update-material-price/{id}', [RawMaterialController::class, 'updatePrice']);

Route::post('/update-raw-material-prices', [RawMaterialController::class, 'updatePrices'])->name('rawMaterial.updatePrices');
Route::get('/raw-material/price-details/{id}', [RawMaterialController::class, 'getRmPriceHistory'])->name('rawMaterial.priceHistory');

Route::post('/rawMaterial-import', [RawMaterialController::class, 'importExcel'])->name('rawMaterial.import');
Route::get('/rawMaterial-excel', function () {
    return Storage::download('public/excel_file/rawmaterials_.xlsx');
});

// packing materials route
Route::get('/packingmaterial', [PackingMaterialController::class, 'index'])->name('packingMaterials.index');
Route::get('/addpackingmaterial', [PackingMaterialController::class, 'create'])->name('packingmaterial.create');
Route::post('/savepackingmaterial', [PackingMaterialController::class, 'store'])->name('packingmaterials.store');
Route::get('/editpackingmaterial/{id}', [PackingMaterialController::class, 'edit'])->name('packingMaterial.edit');
Route::put('/editpackingmaterial/{id}', [PackingMaterialController::class, 'update'])->name('packingMaterial.edit');
Route::post('/deletepackingmaterial', [PackingMaterialController::class, 'delete'])->name('packingMaterial.delete');
Route::post('/confirmPackingmaterial', [PackingMaterialController::class, 'deleteConfirmation'])->name('packingMaterial.confirm');

Route::post('/update-packing-material-prices', [PackingMaterialController::class, 'updatePrices'])->name('packingMaterial.updatePrices');
Route::get('/packing-material/price-details/{id}', [PackingMaterialController::class, 'getPmPriceHistory'])->name('packingMaterial.priceHistory');
Route::post('/packingMaterial-import', [PackingMaterialController::class, 'importExcel'])->name('packingMaterial.import');
Route::get('/packingMaterial-excel', function () {
    return Storage::download('public/excel_file/packingmaterials_.xlsx');
});

// Overheads route
Route::get('/overheads', [OverheadController::class, 'index'])->name('overheads.index');
Route::get('/addoverheads', [OverheadController::class, 'create'])->name('overheads.create');
Route::post('/saveoverheads', [OverheadController::class, 'store'])->name('overheads.store');
Route::get('/editoverheads/{id}', [OverheadController::class, 'edit'])->name('overheads.edit');
Route::put('/editoverheads/{id}', [OverheadController::class, 'update'])->name('overheads.edit');
Route::post('/deleteoverheads', [OverheadController::class, 'delete'])->name('overheads.delete');
Route::post('/confirmoverheads', [OverheadController::class, 'deleteConfirmation'])->name('overheads.confirm');

Route::post('/update-overheads-prices', [OverheadController::class, 'updatePrices'])->name('overheads.updatePrices');
Route::get('/overheads/price-details/{id}', [OverheadController::class, 'getOhPriceHistory'])->name('overheads.priceHistory');

Route::post('/overheads-import', [OverheadController::class, 'importExcel'])->name('overheads.import');

Route::get('/overheads-excel', function () {
    return Storage::download('public/excel_file/overheads_.xlsx');
});

// products route
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/addproduct', [ProductController::class, 'create'])->name('products.create');
Route::post('/saveproduct', [ProductController::class, 'store'])->name('products.store');
Route::get('/editproduct/{id}', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/editproduct/{id}', [ProductController::class, 'update'])->name('products.edit');
Route::post('/deleteproducts', [ProductController::class, 'delete'])->name('product.delete');
Route::post('/confirmproducts', [ProductController::class, 'deleteConfirmation'])->name('product.confirm');

Route::post('/update-products-prices', [ProductController::class, 'updatePrices'])->name('products.updatePrices');
Route::get('/products/price-details/{id}', [ProductController::class, 'getPdPriceHistory'])->name('products.priceHistory');
Route::post('/products-import', [ProductController::class, 'importExcel'])->name('products.import');

Route::get('/products-excel', function () {
    return Storage::download('public/excel_file/products_.xlsx');
});

// recipe details-decsription route

// Route::get('/receipe-details-description', function () {
//     return view('receipeDetails_Description');
// })->name('receipedd');

// Route::get('/pricing', function () {
//     return view('pricing');
// })->name('pricing');

// Route::get('/recipe', [RecipeController::class, 'index'])->name('receipedetails.index');
Route::get('/receipedetails', [RecipeController::class, 'recipeDetails'])->name('receipedetails.index');
Route::get('/addreceipedetails', [RecipeController::class, 'create'])->name('addreceipedetails.create');
Route::post('/savereceipedetails', [RecipeController::class, 'store'])->name('savereceipedetails.store');
Route::get('/recipes/{id}', [RecipeController::class, 'show'])->name('recipe.show');
Route::get('/recipes/{id}', [RecipeController::class, 'fetchRecipeDetails'])->name('recipe.fetchDetails');

// pricing route
Route::get('/pricing', [RecipePricingController::class, 'index'])->name('receipepricing.index');
// Route::get('/addreceipedetails', [RecipeController::class, 'create'])->name('addreceipedetails.create');
// Route::post('/savereceipedetails', [RecipeController::class, 'store'])->name('savereceipedetails.store');
// // Route::get('/recipe/{id}', [RecipeController::class, 'show'])->name('recipe.show');
// Route::get('/recipes/{id}', [RecipeController::class, 'fetchRecipeDetails'])->name('recipe.fetchDetails');

// Route::post('/rm-for-recipe', [RmForRecipeController::class, 'rmstore'])->name('rm.for.recipe');
// Route::post('/rm-for-recipe', [RmForRecipeController::class, 'store']);


Route::post('/rm-for-recipe', [RmForRecipeController::class, 'store'])->name('rm.store');
Route::delete('/rm-for-recipe/{id}', [RmForRecipeController::class, 'destroy'])->name('rm.delete');
Route::post('/pm-for-recipe', [PmForRecipeController::class, 'store'])->name('pm.store');
Route::delete('/pm-for-recipe/{id}', [PmForRecipeController::class, 'destroy'])->name('pm.delete');
Route::post('/oh-for-recipe', [OhForRecipeController::class, 'store'])->name('oh.store');
Route::delete('/oh-for-recipe/{id}', [OhForRecipeController::class, 'destroy'])->name('oh.delete');
Route::post('/manual-overhead', [OhForRecipeController::class, 'storeManualOverhead'])->name('manual-overhead.store');
Route::delete('/moh-for-recipe/{id}', [OhForRecipeController::class, 'mohDestroy'])->name('moh.delete');

// Route::post('/recipepricing', [RecipePricingController::class, 'store'])->name('recipepricing.store');

Route::get('/editrecipedetails/{id}', [RecipeController::class, 'edit'])->name('editrecipedetails.edit');
Route::put('/editreceipedetails/{id}', [RecipeController::class, 'update'])->name('editrecipedetails.update');
Route::get('/recipe-history/{id}', [RecipeController::class, 'getRecipedetailsHistory']);
Route::post('/deleterecipedetails/{id}', [RecipeController::class, 'delete'])->name('deleterecipedetails.delete');

Route::get('/pricing-records', [RecipePricingController::class, 'showPricingForm'])->name('receipepricing.form');
Route::delete('/receipepricing/delete', [RecipePricingController::class, 'destroy'])->name('receipepricing.delete');
Route::get('/edit-pricing/{id}', [RecipePricingController::class, 'edit'])->name('receipepricing.edit');
Route::post('/update-pricing/{id}', [RmForRecipeController::class, 'update']);
Route::post('/pm-update-pricing/{id}', [PmForRecipeController::class, 'update']);
Route::post('/oh-update-pricing/{id}', [OhForRecipeController::class, 'update']);

Route::get('/check-product-exists', [RecipePricingController::class, 'checkProductExists']);

Route::get('/recipepricing', [RecipePricingController::class, 'showRecipePricingList'])->name('showRecipePricingList');

Route::get('/overallcosting', [OverAllCostingController::class, 'index'])->name('overallcosting.index');
Route::get('/addoverallcosting', [OverAllCostingController::class, 'create'])->name('overallcosting.create');
Route::post('/saveoverallcosting', [OverAllCostingController::class, 'store'])->name('overallcosting.store');
Route::get('/editoverallcosting/{id}', [OverAllCostingController::class, 'edit'])->name('overallcosting.edit');
Route::put('/editoverallcosting/{id}', [OverAllCostingController::class, 'update'])->name('overallcosting.update');
Route::post('/deleteoverallcosting', [OverAllCostingController::class, 'delete'])->name('overallcosting.delete');

Route::get('/showoverallcosting/{id}', [OverAllCostingController::class, 'show']);
Route::get('/get-abc-cost', [OverAllCostingController::class, 'getABCcost']);

Route::get('/report', [ReportController::class, 'index'])->name('report.view');

// for is whatsapp
Route::get('/whatsapp', [WhatsAppController::class, 'index'])->name('whatsapp');
Route::post('/whatsapp', [WhatsAppController::class, 'store'])->name('whatsapp.post');
// Route::post('/whatsapp', [WhatsAppController::class, 'sendMessage'])->name('whatsapp.send');
Route::get('whastappapikeys', [TwilioController::class, 'twilioaccount'])->name('twilio.keys');
Route::post('/update-keys', [TwilioController::class, 'updateTwilio'])->name('update.keys');

Route::get('/check-margins', [ReportController::class, 'checkMargins']);

Route::get('/trend-analytics', [DashboardController::class, 'getTrendAnalyticsData']);

Route::get('/permission', [PermissionController::class, 'index'])->name('permission.index');
Route::get('/addpermission', [PermissionController::class, 'create'])->name('Permission.create');
Route::post('/permission/store', [PermissionController::class, 'store'])->name('permission.store');
Route::get('/editpermission/{id}', [PermissionController::class, 'edit'])->name('Permission.edit');
Route::put('/editpermission/{id}', [PermissionController::class, 'update'])->name('Permission.update');
Route::post('/deletepermission', [PermissionController::class, 'delete'])->name('Permission.delete');
