<?php

namespace App\Http\Controllers;
use App\Models\RawMaterial;
use App\Models\PackingMaterial;
use App\Models\Overhead;
use App\Models\Product;
use App\Models\Category;
use App\Models\CategoryItems;
use App\Models\Recipe;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        // category ids
        $rmc = 1;
        $pmc = 2;
        $ohc = 3;
        $pdc = 4;

        $totalRm = RawMaterial::count(); // Count of raw materials
        $totalPm = PackingMaterial::count();

        $totalOh = Overhead::count(); // Count of raw materials
        $totalPd = Product::count();

        $totalCitems = CategoryItems::count(); // Count of raw materials
        $totalrecipes = Recipe::count();
        $totalRmC = CategoryItems::where('categoryId', $rmc)->count();
        $totalPmC = CategoryItems::where('categoryId', $pmc)->count();
        $totalOhC = CategoryItems::where('categoryId', $ohc)->count();
        $totalPdC = CategoryItems::where('categoryId', $pdc)->count();

        return view('dashboard', compact('totalRm', 'totalPm','totalOh','totalPd','totalCitems','totalrecipes','totalRmC','totalPmC','totalOhC','totalPdC'));
    }

}
