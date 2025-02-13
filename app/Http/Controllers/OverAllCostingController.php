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
        $recipeproducts = DB::table('recipe_master')
            ->join('product_master', 'recipe_master.product_id', '=', 'product_master.id')
            ->leftJoin('overall_costing', function ($join) {
                $join->on('recipe_master.product_id', '=', 'overall_costing.productId')
                     ->where('overall_costing.status', 'active'); // Ensures active costing is filtered out
            })
            ->where('recipe_master.status', 'active')
            ->whereNull('overall_costing.productId') // This now ensures there's no active costing
            ->select('recipe_master.product_id as id', 'product_master.name as name')
            ->get();

        return view('overallCost.addoverallCosting', compact('recipeproducts'));
    }

    public function getABCcost(Request $request)
    {
         // If a product is selected, fetch the pricing data
        $productId = $request->query('productId');  // Get productId from request

        if (!$productId) {
            return response()->json(['error' => 'Product ID is required'], 400);
        }
            // Assuming you are joining these tables based on product_id
            $pricingData = DB::table('recipe_master')
                ->join('rm_for_recipe', 'rm_for_recipe.product_id', '=', 'recipe_master.product_id')
                ->leftjoin('pm_for_recipe', 'pm_for_recipe.product_id', '=', 'recipe_master.product_id')
                ->leftjoin('oh_for_recipe', 'oh_for_recipe.product_id', '=', 'recipe_master.product_id')
                ->join('product_master', 'product_master.id', '=', 'recipe_master.product_id')
                ->where('recipe_master.product_id', $productId)
                ->select(
                    'rm_for_recipe.raw_material_id as rm_id',
                    'rm_for_recipe.quantity as rm_quantity',
                    'rm_for_recipe.price as rm_price',
                    'pm_for_recipe.packing_material_id as pm_id',
                    'pm_for_recipe.quantity as pm_quantity',
                    'pm_for_recipe.price as pm_price',
                    'oh_for_recipe.overheads_id as oh_id',
                    'oh_for_recipe.quantity as oh_quantity',
                    'oh_for_recipe.price as oh_price',
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

                $totalRmCost = $pricingData->sum(function($data) {
                    return $data->rm_quantity * $data->rm_price;
                });
                $totalPmCost = $pricingData->sum(function($data) {
                    return $data->pm_quantity * $data->pm_price;
                });
                $totalOhCost = $pricingData->sum(function($data) {
                    return $data->oh_quantity * $data->oh_price;
                });
                $totalCost = $totalRmCost + $totalPmCost + $totalOhCost;

                $rpoutput = $pricingData->isNotEmpty() ? $pricingData->first()->rpoutput : null;
                $product_tax = $pricingData->isNotEmpty() ? $pricingData->first()->product_tax : null;

            // Pass the data to the view
            // return view('overallCost.addoverallcosting', compact('totalOhCost','totalPmCost','totalRmCost'));

            return response()->json([
                'totalRmCost' => $totalRmCost,
                'totalPmCost' => $totalPmCost,
                'totalOhCost' => $totalOhCost,
                'rpoutput' => $rpoutput,
                'product_tax' => $product_tax,
            ]);
    }

    public function store(Request $request)
    {
        // dd($request);
        $validatedData = $request->validate([
            'productId' => 'required|exists:recipe_master,product_id',
            'inputRmcost' => 'required|numeric',
            'inputPmcost' => 'required|numeric',
            'inputRmPmcost' => 'required|numeric',
            'inputOverhead' => 'required|numeric',
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

        try {
            OverallCosting::create([
                'productId' => $request->productId,
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
                'margin_amt' =>(float) $request->inputMarginAmt,
                'discount' => (float) $request->inputDiscount,
                'sugg_rate' => (float) $request->inputSuggRate,
                'sugg_rate_bf' => (float) $request->inputSuggRatebf,
                'suggested_mrp' => (float) $request->inputSuggMrp,
                'status' => 'active',
            ]);
        } catch (\Exception $e) {
            // Handle error by logging or displaying the message
            \Log::error('Error inserting OverallCosting data: ' . $e->getMessage());
            return back()->with('error', 'Error saving data: ' . $e->getMessage());
        }
        // Redirect to another page with a success message
        return redirect()->route('overallcosting.create')->with('success', 'Costing saved successfully!');
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
        // Fetch the specific OverallCosting record
        $costing = DB::table('overall_costing')
            ->join('product_master', 'overall_costing.productId', '=', 'product_master.id')
            ->select(
                'overall_costing.*',
                'product_master.name as product_name'
            )
            ->where('overall_costing.id', $id)
            ->first(); // Retrieve only one record

        // Check if data exists
        if (!$costing) {
            return redirect()->route('overallcosting.index')->with('error', 'Record not found.');
        }

        // Return the view with costing data
        return view('overallCost.editoverallCosting', compact('costing'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
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
