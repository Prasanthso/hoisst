<?php

namespace App\Http\Controllers;
use App\Models\RawMaterial;
use App\Models\PackingMaterial;
use App\Models\Overhead;
use App\Models\Product;
use App\Models\Category;
use App\Models\CategoryItems;
use App\Models\Recipe;
use App\Models\RmForRecipe;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

        $graphproducts = Product::all()->map(function ($product) {
            // $margin = $product->price - $product->purcCost ?? 0;
            // $margin = $product->margin;
            return [
                'name' => $product->name,
                'margin' => (float) $product->margin ?? 0, // make sure it's numeric
                'purcCost' =>  (float)$product->price - (float)($product->purcCost ?? 0),
            ];
        });

        $totalRmC = CategoryItems::where('categoryId', $rmc)->where('status', 'active')->count();
        $totalPmC = CategoryItems::where('categoryId', $pmc)->where('status', 'active')->count();
        $totalOhC = CategoryItems::where('categoryId', $ohc)->where('status', 'active')->count();
        $totalPdC = CategoryItems::where('categoryId', $pdc)->where('status', 'active')->count();


        $costindicator = $this->indicatorBadge();
        $modifications = $this->getTrendAnalyticsData();
        $alerts = $this->getAlertforFlags();

          // Unpack modifications arrays
            $months = $modifications['months'];
            $products = $modifications['products'];
            $rawMaterials = $modifications['rawMaterials'];
            $quantities = $modifications['quantities'];

        return view('dashboard', compact('totalRm', 'totalPm','totalOh','totalPd','totalCitems','totalrecipes','totalRmC','totalPmC','totalOhC','totalPdC','graphproducts',
            'costindicator','months', 'products', 'rawMaterials', 'quantities','alerts'));

            // 'thisMonthCost', 'lastMonthCost', 'costChange', 'costTrendIndicator'));
    }

    public function indicatorBadge()
    {
           // indicatorBadge below this
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        // This month's total cost
        $thisMonthCost = RawMaterial::whereBetween('created_at', [$startOfMonth, now()])
            ->sum('price'); // or 'price', depending on your column

        // Last month's total cost
        $lastMonthCost = RawMaterial::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('price');

        // % Change
        $costChange = 0;
        if ($lastMonthCost > 0) {
            $costChange = (($thisMonthCost - $lastMonthCost) / $lastMonthCost) * 100;
        }

        // Determine badge
        $costTrendIndicator = 'neutral';
        if ($costChange > 0) {
            $costTrendIndicator = 'increase';
        } elseif ($costChange < 0) {
            $costTrendIndicator = 'decrease';
        }
                // Return the calculated data as an array or object
            return [
                'thisMonthCost' => $thisMonthCost,
                'lastMonthCost' => $lastMonthCost,
                'costChange' => $costChange,
                'costTrendIndicator' => $costTrendIndicator
            ];
    }

    public function getTrendAnalyticsData()
    {
        // $modifications  = RmForRecipe::selectRaw('MONTH(updated_at) as month, COUNT(*) as changes, SUM(margin) as impact')
        // ->whereYear('updated_at', now()->year)
        // ->groupBy('month')
        // ->orderBy('month')
        // ->get();
                $allModifications = RmForRecipe::selectRaw('
                MONTH(rm_for_recipe.updated_at) as month,
                product_master.name as product_name,
                raw_materials.name as raw_material_name,
                SUM(rm_for_recipe.quantity) as total_quantity,
                SUM(product_master.margin) as impact
            ')
            ->join('product_master', 'product_master.id', '=', 'rm_for_recipe.product_id')
            ->join('raw_materials', 'raw_materials.id', '=', 'rm_for_recipe.raw_material_id')
            ->whereYear('rm_for_recipe.updated_at', now()->year)
            ->groupByRaw('MONTH(rm_for_recipe.updated_at), product_master.name, raw_materials.name')
            ->orderByRaw('MONTH(rm_for_recipe.updated_at), product_master.name, total_quantity DESC')
            ->get();

        // Now filter top raw material per product per month
        $topModifications = $allModifications->groupBy(fn ($item) => $item->month . '-' . $item->product_name)
            ->map(function ($group) {
                return $group->sortByDesc('total_quantity')->first(); // top raw material per group
            })
            ->values(); // flatten

            // Prepare arrays for chart
            $months = $topModifications->pluck('month')->map(fn($m) => date("F", mktime(0, 0, 0, $m, 1)))->toArray();
            $products = $topModifications->pluck('product_name')->toArray();
            $rawMaterials = $topModifications->pluck('raw_material_name')->toArray();
            $quantities = $topModifications->pluck('total_quantity')->toArray();

            return [
                'months' => $months,
                'products' => $products,
                'rawMaterials' => $rawMaterials,
                'quantities' => $quantities,
            ];
    }

    public function getAlertforFlags()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $highCostAlerts = [];    // For High Cost Ingredients
        $lowMarginAlerts = [];   // For Low Margin Products
        $highMarginAlerts = [];

        //  RED FLAG 1: High Cost Ingredient
    $materials = DB::table('raw_materials')->select('id', 'name')->distinct()->where('status','active')->get();
    $currentMonthStart = Carbon::now()->startOfMonth();           // e.g., 2025-04-01 00:00:00
    $currentMonthEnd   = Carbon::now()->endOfMonth();             // e.g., 2025-04-30 23:59:59

    $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();  // e.g., 2025-03-01 00:00:00
    $lastMonthEnd   = Carbon::now()->subMonth()->endOfMonth();

        foreach ($materials as $item) {
            $current = DB::table('raw_materials')
            ->where('id', $item->id)
            ->where('status','active')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->orderByDesc('created_at')
            ->value('price');

        $last = DB::table('raw_materials')
            ->where('id', $item->id)
            ->where('status','active')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->orderByDesc('created_at')
            ->value('price');

        $threshold = DB::table('raw_materials')
            ->where('id', $item->id)
            ->where('status','active')
            ->orderByDesc('created_at')
            ->value('price_threshold');

            if ($current && $last) {
                $diff = (($current - $last) / $last) * 100;
                if ($current > $threshold && $diff >= 2) {
                    $highCostAlerts[] = [
                        'item' => $item->name,
                    'flag_type' => 'Red Flag 1: High Cost Ingredient',
                    'description' => "Cost ↑ by " . round($diff, 1) . "% (₹$last → ₹$current)",
                    'alert_type' => 'danger',
                    'increase_percent' => $diff
                    ];
                }
            }
        }

        // // Sort by % increase and take top 5
        // $highCostAlerts = collect($highCostAlerts)
        //     ->sortByDesc('increase_percent')
        //     ->take(5)
        //     ->values()
        //     ->toArray();

        // 🚨 Red Flag 2 & 3: Product Margin Analysis
        $products = Product::where('status', 'active')->where('recipe_created_status','yes')->get();


    foreach ($products as $product) {
        $costData = DB::table('recipe_master')
            ->join('rm_for_recipe', 'rm_for_recipe.product_id', '=', 'recipe_master.product_id')
            ->leftJoin('pm_for_recipe', 'pm_for_recipe.product_id', '=', 'recipe_master.product_id')
            ->leftJoin('oh_for_recipe', 'oh_for_recipe.product_id', '=', 'recipe_master.product_id')
            ->leftJoin('moh_for_recipe', 'moh_for_recipe.product_id', '=', 'recipe_master.product_id')
            ->leftJoin('raw_materials', 'rm_for_recipe.raw_material_id', '=', 'raw_materials.id')
            ->leftJoin('packing_materials', 'pm_for_recipe.packing_material_id', '=', 'packing_materials.id')
            ->leftJoin('overheads', 'oh_for_recipe.overheads_id', '=', 'overheads.id')
            ->where('recipe_master.product_id', $product->id)
            ->select(
                'rm_for_recipe.raw_material_id as rm_id',
                'rm_for_recipe.quantity as rm_quantity',
                'raw_materials.price as rm_price',
                'pm_for_recipe.quantity as pm_quantity',
                'packing_materials.price as pm_price',
                'oh_for_recipe.quantity as oh_quantity',
                'overheads.price as oh_price',
                'moh_for_recipe.price as moh_price',
                'recipe_master.output as rp_output'
            )
            ->distinct()
            ->get();

        if ($costData->isEmpty()) {
            continue; // skip this product if no data
        }

        $totalRmCost = $costData->sum(fn($data) => ($data->rm_quantity ?? 0) * ($data->rm_price ?? 0));
        $totalPmCost = $costData->sum(fn($data) => ($data->pm_quantity ?? 0) * ($data->pm_price ?? 0));
        $totalOhCost = $costData->sum(fn($data) => ($data->oh_quantity ?? 0) * ($data->oh_price ?? 0));
        $totalMohCost = $costData->sum(fn($data) => $data->moh_price ?? 0);

        $rpOutput = $costData->first()->rp_output ?? 1;
        $totalCost = $totalRmCost + $totalPmCost + ($totalOhCost ?: $totalMohCost);
        $costPerUnit = round($totalCost / $rpOutput, 2);

    // echo "Product: {$product->name}, Cost Per Unit: ₹{$costPerUnit}<br>";
    }

        foreach ($products as $product) {
            // Red Flag 2: Low Margin Product
            if ($product->price < $costPerUnit ) {
                $lowMarginAlerts[] = [
                    'item' => $product->name,
                    'flag_type' => 'Red Flag 2: Low Margin Product-'.$product->name,
                    'description' => "Margin dropped below threshold ({$product->margin}%)",
                    'alert_type' => 'warning'
                ];
            }

            // Red Flag 3: High Margin Product
            if ($product->price > $costPerUnit) { // Customize threshold
                $highMarginAlerts[] = [
                    'item' => $product->name,
                    'flag_type' => 'Red Flag 3: High Margin Product-'.$product->name,
                    'description' => "High margin ({$product->margin}%) — review pricing",
                    'alert_type' => 'success'
                ];
            }
        }
        return [
            'highCostAlerts' => $highCostAlerts,
            'lowMarginAlerts' => $lowMarginAlerts,
            'highMarginAlerts' => $highMarginAlerts,
        ];
     }

}
