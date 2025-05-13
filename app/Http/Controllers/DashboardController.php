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
    public function index(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        // category ids
        $rmc = 1;
        $pmc = 2;
        $ohc = 3;
        $pdc = 4;

        // $totalRm = RawMaterial::where('status', 'active')->count();
        // $totalPm = PackingMaterial::where('status', 'active')->count();
        // $totalOh = Overhead::where('status', 'active')->count();
        $totalPd = Product::where('status', 'active')->where('store_id',$storeid)->count();
        // $totalCitems = CategoryItems::where('status', 'active')->count();
        $totalrecipes = Recipe::where('status', 'active')->where('store_id',$storeid)->count();

        $graphproducts = Product::all()->where('store_id',$storeid)->map(function ($product) {
            // $margin = $product->price - $product->purcCost ?? 0;
            // $margin = $product->margin;
            return [
                'name' => $product->name,
                'margin' => (float) $product->margin ?? 0, // make sure it's numeric
                'purcCost' =>  (float)$product->price - (float)($product->purcCost ?? 0),
            ];
        });

        // $totalRmC = CategoryItems::where('categoryId', $rmc)->where('status', 'active')->count();
        // $totalPmC = CategoryItems::where('categoryId', $pmc)->where('status', 'active')->count();
        // $totalOhC = CategoryItems::where('categoryId', $ohc)->where('status', 'active')->count();
        $totalPdC = CategoryItems::where('categoryId', $pdc)->where('status', 'active')->where('store_id',$storeid)->count();

        $highcostingredients = [];

        if ($request->has('material_name') && trim($request->input('material_name')) !== '') {
            $highcostingredients = $this->highCostIngredients($request);
        }

        $costindicator = $this->indicatorBadge();
        $modifications = $this->getTrendAnalyticsData();
        $alerts = $this->getAlertforFlags();
        $trendData = $this->priceTrendChart();

          // Unpack modifications arrays
            $months = $modifications['months'];
            $products = $modifications['products'];
            $rawMaterials = $modifications['rawMaterials'];
            $quantities = $modifications['quantities'];
            $impacts = $modifications['impacts'];

            if ($request->ajax()) {
                // Return the partial view if it's an AJAX request
                return response()->json([
                    'highcostingredients' => $highcostingredients,
                    'alerts' => $alerts,
                    'message' => empty($highcostingredients) ? 'No high cost alert for this material.' : null,
                ]);
            }
        return view('dashboard', compact('totalPd','totalrecipes','totalPdC','graphproducts',
            'costindicator','months', 'products', 'rawMaterials', 'quantities','impacts','alerts','trendData','highcostingredients'));

            // 'thisMonthCost', 'lastMonthCost', 'costChange', 'costTrendIndicator'));
    }

    public function indicatorBadge()
    {
        $storeid = session()->get('store_id');
           // indicatorBadge below this
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        // This month's total cost
        $thisMonthCost = RawMaterial::where('store_id',$storeid)->whereBetween('updated_at', [$startOfMonth, now()])
            ->sum('price'); // or 'price', depending on your column

        // Last month's total cost
        $lastMonthCost = RawMaterial::where('store_id',$storeid)->whereBetween('updated_at', [$startOfLastMonth, $endOfLastMonth])
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
        $storeid = session()->get('store_id');
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
            ->where('raw_materials.store_id',$storeid)
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
            $impacts = $topModifications->pluck('impact')->toArray();

            // $labels = $topModifications->map(function ($item) {
            //     return date("F", mktime(0, 0, 0, $item->month, 1)) . ' - ' . $item->product_name . ' (' . $item->raw_material_name . ')';
            // })->toArray();
            return [
                // 'labels' => $labels,
                'months' => $months,
                'products' => $products,
                'rawMaterials' => $rawMaterials,
                'quantities' => $quantities,
                'impacts' => $impacts,
            ];
    }

    public function getAlertforFlags()
    {
        $storeid = session()->get('store_id');
        $lowMarginAlerts = [];
        $highMarginAlerts = [];
        $allUnitCosts = [];

        $products = Product::join('recipe_master', 'product_master.id', '=', 'recipe_master.product_id')
            ->join('overall_costing', 'recipe_master.product_id', '=', 'overall_costing.productid')
            ->where('product_master.store_id', $storeid)
            ->where('product_master.status', 'active')
            ->where('recipe_master.status', 'active')
            ->where('overall_costing.status', 'active')
            ->select('product_master.*', 'overall_costing.suggested_mrp')
            ->distinct()
            ->get();

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
                ->where('recipe_master.store_id', $storeid)
                ->select(
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

            if ($costData->isEmpty()) continue;

            // 🧮 Cost calculations
            $totalRmCost = $costData->sum(fn($d) => ($d->rm_quantity ?? 0) * ($d->rm_price ?? 0));
            $totalPmCost = $costData->sum(fn($d) => ($d->pm_quantity ?? 0) * ($d->pm_price ?? 0));
            $totalOhCost = $costData->sum(fn($d) => ($d->oh_quantity ?? 0) * ($d->oh_price ?? 0));
            $totalMohCost = $costData->sum(fn($d) => $d->moh_price ?? 0);
            $rpOutput = $costData->first()->rp_output ?? 1;

            $totalCost = ($totalOhCost > 0)
                ? ($totalRmCost + $totalPmCost + $totalOhCost)
                : ($totalRmCost + $totalPmCost + $totalMohCost);

            $unitCost = $totalCost / $rpOutput;
            $allUnitCosts[$product->id] = $unitCost;

            // 🎯 Margin logic
            $price = (float) $product->price;
            $suggestedMrp = (float) $product->suggested_mrp;
            // $discount =  (float) $product->discount;
            $prod_tax = (float) $product->tax;
            $targetMargin = (float) $product->margin;
            // $mrp = $unitCost*targetMargin
            // Margin based on MRP
            $actualMargin = (($price - $unitCost) / $unitCost) * 100;

            $actualMarginFormatted = number_format($actualMargin, 2);
            $targetMarginFormatted = number_format($targetMargin, 2);

            if ($actualMargin < $targetMargin) {
                $lowMarginAlerts[] = [
                    'item' => $product->name,
                    'flag_type' => "🔴 RED FLAG 2: Low Margin",
                    'description' => "Actual Margin {$actualMarginFormatted}% is less than expected {$targetMargin}%",
                    'unit_cost' => round($unitCost, 2),
                    'mrp' => $suggestedMrp,
                    'actual_margin' => round($actualMargin, 2),
                ];
            } elseif ($actualMargin > $targetMargin) {
                $highMarginAlerts[] = [
                    'item' => $product->name,
                    'flag_type' => "🔴 RED FLAG 3: High Margin",
                    'description' => "Profit margin is {$actualMarginFormatted}%, above the target of {$targetMargin}%",
                    'unit_cost' => round($unitCost, 2),
                    'mrp' => $suggestedMrp,
                    'actual_margin' => round($actualMargin, 2),
                ];
            }
        }

        return [
            // 'highCostAlerts' => $highCostAlerts,
            'lowMarginAlerts' => $lowMarginAlerts,
            'highMarginAlerts' => $highMarginAlerts,
            'unitCosts' => $allUnitCosts,
            'lowMarginCount' => count($lowMarginAlerts),
            'highMarginCount' => count($highMarginAlerts),
        ];
    }
    // for costIngredients
    public function highCostIngredients(Request $request)
    {
        $storeid = session()->get('store_id');
        $inputName = strtolower(trim($request->input('material_name')));
        $materialNames = array_map('strtolower', array_map('trim', explode(',', $inputName)));
        $highCostAlerts = [];

        foreach ($materialNames as $name) {
            $name = trim($name);
            $material = DB::table('raw_materials')
                ->whereRaw('LOWER(name) = ?', [$name])
                ->where('status', 'active')
                ->where('store_id', $storeid)
                ->first();

            if ($material) {
                $currentPrice = $material->price;
                $threshold = $material->price_threshold;

                if ($currentPrice > $threshold) {
                    $description = $currentPrice > $threshold
                    ? "Current cost ₹$currentPrice is above threshold ₹$threshold"
                    : "Current cost ₹$currentPrice is within safe range";

                $highCostAlerts[] = [
                    'item' => $material->name,
                    'flag_type' => 'Red Flag 1: High Cost Ingredient',
                    'description' => $description,
                    'alert_type' => $currentPrice > $threshold ? 'danger' : 'success',
                    'increase_percent' => null
                ];

                }
            }
        }
        return $highCostAlerts;

    }
    // for cost-insights
    public function priceTrendChart()
    {
        $storeid = session()->get('store_id');
        $trendData = DB::table('rm_price_histories as h')
        ->join('raw_materials as r', 'h.raw_material_id', '=', 'r.id')
        ->select(
            'r.name AS material_name',
            'h.updated_at AS month',  // Get the actual date of the price change
            'h.old_price',
            'h.new_price'
        )
        ->where('h.updated_at', '>=', Carbon::now()->subMonths(6))  // Get the last 6 months of data
        ->where('h.store_id', $storeid)
        ->orderBy('h.updated_at', 'desc')  // Order by most recent price changes
        ->limit(10)  // Optional: limit to the last 10 price updates
        ->get();


            // $topProfitable = DB::table('product_master')
            // ->select('name', 'price', 'margin')
            // ->orderByDesc('margin')
            // ->limit(10)
            // ->get();
            $topProfitable = Product::join('recipe_master', 'product_master.id', '=', 'recipe_master.product_id')
            ->join('overall_costing', 'recipe_master.product_id', '=', 'overall_costing.productid')
            ->where('product_master.status', 'active')
            ->where('recipe_master.status', 'active')
            ->where('overall_costing.status', 'active')
            ->where('product_master.store_id', $storeid)
            ->select(
                'product_master.*',
                'overall_costing.total_cost',
                \DB::raw('((product_master.price - overall_costing.total_cost) / overall_costing.total_cost) * 100 as profit_margin')
            )
            ->having('profit_margin', '>', 0) // ensure only profitable ones
            ->orderByDesc('profit_margin')
            ->limit(10)
            ->get();


            return [
                'trendData' => $trendData,       // structured properly for Chart.js
                'top_profitable' => $topProfitable  // for table
            ];

    }
/*
     public function costTrendLinegraph()
     {
        $trendData = DB::table('rm_price_histories as h')
        ->join('raw_materials as r', 'h.raw_material_id', '=', 'r.id')
        ->selectRaw("r.name, DATE_FORMAT(h.updated_at, '%Y-%m') as month, AVG(h.new_price) as avg_price")
        ->where('h.updated_at', '>=', now()->subMonths(6))
        ->groupBy('r.name', 'month')
        ->orderBy('r.name')
        ->orderBy('month')
        ->get();
        // Step 1: Format basic data
    $formattedData = [];
    $allMonthsSet = [];

    foreach ($trendData as $row) {
        $formattedData[$row->name][$row->month] = (float) $row->avg_price;
        $allMonthsSet[$row->month] = true;
    }

    // Step 2: Create sorted list of all months
    $allMonths = array_keys($allMonthsSet);
    sort($allMonths);

    // Step 3: Fill missing months with null
    $alignedData = [];
    foreach ($formattedData as $material => $monthData) {
        $alignedData[$material] = [];
        foreach ($allMonths as $month) {
            $alignedData[$material][] = $monthData[$month] ?? null;
        }
    }
        $topProfitable = DB::table('product_master')
        ->select('name', 'price', 'margin')
        ->orderByDesc('margin')
        ->limit(10)
        ->get();

        return [
            'cost_trend' => $alignedData,       // structured properly for Chart.js
            'months' => $allMonths,             // unified x-axis labels
            'top_profitable' => $topProfitable  // for table
        ];
     }
     */

}
