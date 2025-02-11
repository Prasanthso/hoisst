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

        $totalRm = RawMaterial::where('status', 'active')->count();
        $totalPm = PackingMaterial::where('status', 'active')->count();
        $totalOh = Overhead::where('status', 'active')->count();
        $totalPd = Product::where('status', 'active')->count();
        $totalCitems = CategoryItems::where('status', 'active')->count();
        $totalrecipes = Recipe::where('status', 'active')->count();

        $totalRmC = CategoryItems::where('categoryId', $rmc)->where('status', 'active')->count();
        $totalPmC = CategoryItems::where('categoryId', $pmc)->where('status', 'active')->count();
        $totalOhC = CategoryItems::where('categoryId', $ohc)->where('status', 'active')->count();
        $totalPdC = CategoryItems::where('categoryId', $pdc)->where('status', 'active')->count();

        return view('dashboard', compact('totalRm', 'totalPm','totalOh','totalPd','totalCitems','totalrecipes','totalRmC','totalPmC','totalOhC','totalPdC'));
    }

}
