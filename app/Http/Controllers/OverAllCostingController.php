<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\OverallCosting;

class OverAllCostingController extends Controller
{
    public function index()
    {
         // $products = DB::table('product_master')->get();
         $costings = DB::table('overall_costing')
         ->join('product_master', 'overall_costing.productId', '=', 'product_master.id')
         ->select(
             'overall_costing.*',
             'product_master.name as product_name' // Select product name from product_master
         )
         ->where('overall_costing.status', 'active') // Fetch only active records
         ->paginate(10);
        return view('overallCost.overallCosting', compact('costings'));

    }

    public function create()
    {
        // $recipeproducts = DB::table('recipe_master')
        //     ->join('product_master', 'recipe_master.product_id', '=', 'product_master.id')
        //     ->leftJoin('overall_costing', function ($join) {
        //         $join->on('recipe_master.product_id', '=', 'overall_costing.productId')
        //              ->where('overall_costing.status', 'active'); // Ensures active costing is filtered out
        //     })
        //     ->where('recipe_master.status', 'active')
        //     ->whereNull('overall_costing.productId') // This now ensures there's no active costing
        //     ->select('recipe_master.product_id as id', 'product_master.name as name')
        //     ->get();

        $recipeproducts = DB::table('product_master as pd')
        ->leftJoin('item_type as it', 'pd.itemType_Id', '=', 'it.id')
        ->leftJoin('recipe_master as rp', 'pd.id', '=', 'rp.product_id')
        ->leftJoin('overall_costing as ovc', function ($join) {
            $join->on('pd.id', '=', 'ovc.productId')
                 ->where('ovc.status', 'active'); // Exclude active costing
        })
        ->where(function ($query) {
            $query->where(function ($q) {
                $q->where('it.itemtypename', 'Trading')
                  ->where('pd.status', 'active'); // Rule 1: Active Trading products
            })
            ->orWhere('rp.status', 'active'); // Rule 2: Recipe exists & active
        })
        ->whereNull('ovc.productId') // Rule 3: Not in active overall_costing
        ->select('pd.id as id', 'pd.name as name')
        ->get();

        return view('overallCost.addOverallCosting', compact('recipeproducts'));
    }

    public function getABCcost(Request $request)
    {

        // If a product is selected, fetch the pricing data
        $productId = $request->query('productId');  // Get productId from request

        if (!$productId) {
            return response()->json(['error' => 'Product ID is required'], 400);
        }
        $product = DB::table('product_master')
            ->join('item_type', 'product_master.itemType_Id', '=', 'item_type.id') // Join product with itemtype
            ->where('product_master.id', $productId) // Filter by productId
            ->where('item_type.itemtypename', 'Trading') // Check if item type is 'Trading'
            ->select('product_master.*') // Select product details
            ->first();

           if($product)
           {
            $totalRmCost = 0;
            $totalPmCost = 0;
            $totalOhCost = 0;
            $rpoutput = 1;
            $tradingCost = $product->price ?? 0;  // Ensure price is set, default to 0 if null
            $product_tax = $product->tax ?? 0;
            $itemtype = 'Trading';
           }
           else{
            $totalRmCost = DB::table('rm_for_recipe')
            ->where('product_id', $productId)
                ->sum('amount');

            $totalPmCost = DB::table('pm_for_recipe')
            ->where('product_id', $productId)
                ->sum('amount');

            $totalOhCost = DB::table('oh_for_recipe')
            ->where('product_id', $productId)
            ->sum('amount');

        // $totalMohCost = 0;

            if (empty($totalOhCost)) { // If NULL or 0
                $totalOhCost = DB::table('moh_for_recipe')
                ->where('product_id', $productId)
                ->sum('price');
            }

        // Assuming you are joining these tables based on product_id
        $pricingData = DB::table('recipe_master')
        ->join('rm_for_recipe', 'rm_for_recipe.product_id', '=', 'recipe_master.product_id')
        ->leftjoin('pm_for_recipe', 'pm_for_recipe.product_id', '=', 'recipe_master.product_id')
        ->leftjoin('oh_for_recipe', 'oh_for_recipe.product_id', '=', 'recipe_master.product_id')
        ->join('product_master', 'product_master.id', '=', 'recipe_master.product_id')
        ->where('recipe_master.product_id', $productId)
            ->where('recipe_master.status', 'active')
            ->select(
                'rm_for_recipe.raw_material_id as rm_id',
                'rm_for_recipe.quantity as rm_quantity',
                'rm_for_recipe.price as rm_price',
                'rm_for_recipe.amount as rm_amount',
                'pm_for_recipe.packing_material_id as pm_id',
                'pm_for_recipe.quantity as pm_quantity',
                'pm_for_recipe.price as pm_price',
                'pm_for_recipe.amount as pm_amount',
                'oh_for_recipe.overheads_id as oh_id',
                'oh_for_recipe.quantity as oh_quantity',
                'oh_for_recipe.price as oh_price',
                'oh_for_recipe.amount as oh_amount',
                'rm_for_recipe.id as rid',
                'pm_for_recipe.id as pid',
                'oh_for_recipe.id as ohid',
                'recipe_master.Output as rpoutput',
                'product_master.tax as product_tax',
            )
            ->get();

            // Fetch names for IDs separately
            $pricingData->transform(function ($item) {
                $item->rm_name = DB::table('raw_materials')->where('id', $item->rm_id)->value('name');
                $item->pm_name = DB::table('packing_materials')->where('id', $item->pm_id)->value('name');
                $item->oh_name = DB::table('overheads')->where('id', $item->oh_id)->value('name');
                return $item;
            });

        // $totalRmCost = $pricingData->rm_amount;
            // $totalPmCost = $pricingData->pm_amount;
            // $totalOhCost = $pricingData->oh_amount;
            $totalCost = $totalRmCost + $totalPmCost + $totalOhCost;

            $rpoutput = $pricingData->isNotEmpty() ? $pricingData->first()->rpoutput : null;
            $product_tax = $pricingData->isNotEmpty() ? $pricingData->first()->product_tax : null;
            $itemtype = 'Own';
            $tradingCost = 0;
        }
        // Pass the data to the view
        // return view('overallCost.addoverallcosting', compact('totalOhCost','totalPmCost','totalRmCost'));

        return response()->json([
            'totalRmCost' => $totalRmCost,
            'totalPmCost' => $totalPmCost,
            'totalOhCost' => $totalOhCost,
            'rpoutput' => $rpoutput,
            'tradingCost' => $tradingCost,
            'product_tax' => $product_tax,
            'itemtype' => $itemtype,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'productId' => 'required',
            // 'inputRmcost' => 'required|numeric',
            // 'inputPmcost' => 'required|numeric',
            // 'inputRmPmcost' => 'required|numeric',
            // 'inputOverhead' => 'required|numeric',
                // 'inputRmSgmrp' => 'required|numeric',
                // 'inputPmSgmrp' => 'required|numeric',
                // 'inputSgMrp' => 'required|numeric',
                // 'inputSgMargin' => 'required|numeric',
                // 'inputOhAmt' => 'required|numeric',
            'inputTax' => 'required|numeric',
            'inputMargin' => 'required|numeric',
            'inputMarginAmt' => 'required|numeric',
            'inputDiscount' => 'required|numeric',
            'inputTotalCost' => 'required|numeric',
            'inputSuggRate' => 'required|numeric',
            'inputSuggRatebf' => 'required|numeric',
            'inputSuggMrp' => 'required|numeric',
        ]);
        // $isTrading = $request->input('productType') === 'Trading';
        // dd($request->inputPmcost,$request->inputRmPmcost,$request->inputOverhead);
        try {

            OverallCosting::create([
                'productId' => $request->productId,
                'rm_cost_unit' => (float) $request->inputRmcost,
                'pm_cost_unit' => (float) $request->inputPmcost,
                'rm_pm_cost' =>  (float) $request->inputRmPmcost,
                'overhead' => (float) $request->inputOverhead,
                // 'rm_sg_mrp' => (float) $request->inputRmSgmrp,
                // 'pm_sg_mrp' => (float) $request->inputPmSgmrp,
                // 'sg_mrp' => (float) $request->inputSgMrp,
                // 'sg_margin' => (float) $request->inputSgMargin,
                // 'oh_amt' => (float) $request->inputOhAmt,
                'total_cost' => (float) $request->inputTotalCost,
                'tax' => (float) $request->inputTax,
                'margin' => (float) $request->inputMargin,
                'margin_amt' =>(float) $request->inputMarginAmt,
                'discount' => (float) $request->inputDiscount,
                'sugg_rate' => (float) $request->inputSuggRate,
                'sugg_rate_bf' => (float) $request->inputSuggRatebf,
                'suggested_mrp' => (float) $request->inputSuggMrp,
                'status' => 'active',
            ]);

        } catch (\Exception $e) {
           \Log::error('Error inserting OverallCosting data: ' . $e->getMessage());
            return back()->with('error', 'Error saving data: ' . $e->getMessage());
        }
        // Redirect to another page with a success message
        return redirect()->route('overallcosting.index')->with('success', 'Costing saved successfully!');
    }
    public function show($id)
    {
        // Fetch the specific OverallCosting record
        $costing = DB::table('overall_costing')
            ->join('product_master', 'overall_costing.productId', '=', 'product_master.id')
            ->select(
                'overall_costing.*',
                'product_master.name as product_name'
            )
            ->where('overall_costing.id', $id)
            ->where('overall_costing.status', 'active')
            ->get(); // Retrieve only records

        // Check if data exists
        if (!$costing) {
            return response()->json(['success' => false, 'message' => 'Overall-Costing was not found.'],404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Overall-Costing was fetched.',
            'data' => $costing
        ]);
    }


    public function edit($id)
    {


        $productId = DB::table('overall_costing')  // get product id
        ->where('id', $id)
        ->value('productId');

        $producttax = DB::table('product_master') // get product tax
        ->where('id', $productId)
        ->value('tax');

        $rpoutput = DB::table('recipe_master')
        ->where('product_id', $productId)
            ->where('status', 'active')
            ->value('Output');

        $totalRmCost = DB::table('rm_for_recipe')
        ->where('product_id', $productId)
            ->sum('amount');

        $costA = ($rpoutput && $rpoutput > 0) ? ($totalRmCost / $rpoutput) : 0;
        $rmCost = number_format($costA, 2);

        $totalPmCost = DB::table('pm_for_recipe')
        ->where('product_id', $productId)
            ->sum('amount');

        $costB = ($rpoutput && $rpoutput > 0) ? ($totalPmCost / $rpoutput) : 0;
        $pmCost = number_format($costB, 2);

        $totalOhCost = DB::table('oh_for_recipe')
        ->where('product_id', $productId)
        ->sum('amount');

        // $totalMohCost = 0;

        if (empty($totalOhCost)) { // If NULL or 0
            $totalOhCost = DB::table('moh_for_recipe')
            ->where('product_id', $productId)
            ->sum('price');
        }

        $costC = ($rpoutput && $rpoutput > 0) ? ($totalOhCost / $rpoutput) : 0;
        $ohCost = number_format($costC, 2);

        // Fetch the specific OverallCosting record
        $costing = DB::table('overall_costing')
            ->join('product_master', 'overall_costing.productId', '=', 'product_master.id')
            ->select(
                'overall_costing.*',
                'product_master.name as product_name',
            )
            ->where('overall_costing.id', $id)
            ->first(); // Retrieve only one record

        // Check if data exists
        if (!$costing) {
            return redirect()->route('overallcosting.index')->with('error', 'Record not found.');
        }

        // Return the view with costing data
        return view('overallCost.editoverallCosting', compact('costing','rmCost','pmCost','ohCost','rpoutput','producttax'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'inputRmcost' => 'required|numeric',
            'inputPmcost' => 'required|numeric',
            'inputRmPmcost' => 'required|numeric',
            'inputOverhead' => 'required|numeric',
            // 'inputRmSgmrp' => 'required|numeric',
            // 'inputPmSgmrp' => 'required|numeric',
            // 'inputSgMrp' => 'required|numeric',
            // 'inputSgMargin' => 'required|numeric',
            // 'inputOhAmt' => 'required|numeric',
            'inputTotalCost' => 'required|numeric',
            'inputTax' => 'required|numeric',
            'inputMargin' => 'required|numeric',
            'inputMarginAmt' => 'required|numeric',
            'inputDiscount' => 'required|numeric',
            'inputSuggRate' => 'required|numeric',
            'inputSuggRatebf' => 'required|numeric',
            'inputSuggMrp' => 'required|numeric',
        ]);

        try {
            DB::table('overall_costing')->where('id', $id)->update([
                'rm_cost_unit' => (float) $request->inputRmcost,
                'pm_cost_unit' => (float) $request->inputPmcost,
                'rm_pm_cost' => (float) $request->inputRmPmcost,
                'overhead' => (float) $request->inputOverhead,
                // 'rm_sg_mrp' => (float) $request->inputRmSgmrp,
                // 'pm_sg_mrp' => (float) $request->inputPmSgmrp,
                // 'sg_mrp' => (float) $request->inputSgMrp,
                // 'sg_margin' => (float) $request->inputSgMargin,
                // 'oh_amt' => (float) $request->inputOhAmt,
                'total_cost' => (float) $request->inputTotalCost,
                'tax' => (float) $request->inputTax,
                'margin' => (float) $request->inputMargin,
                'margin_amt' => (float) $request->inputMarginAmt,
                'discount' => (float) $request->inputDiscount,
                'sugg_rate' => (float) $request->inputSuggRate,
                'sugg_rate_bf' => (float) $request->inputSuggRatebf,
                'suggested_mrp' => (float) $request->inputSuggMrp,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating data: ' . $e->getMessage());
        }

        return redirect()->route('overallcosting.index')->with('success', 'overallCosting updated successfully!');
    }
    public function delete(Request $request)
    {
        $ids = $request->input('ids'); // Get the 'ids' array from the request

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No valid IDs provided.']);
        }

        try {
            // Update the status of raw materials to 'inactive'
            OverallCosting::whereIn('id', $ids)->update(['status' => 'inactive']);

            return response()->json(['success' => true, 'message' => 'Overall-Costing was inactive successfully.']);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['success' => false, 'message' => 'Error updating Overall Costing: ' . $e->getMessage()]);
        }
    }


}
