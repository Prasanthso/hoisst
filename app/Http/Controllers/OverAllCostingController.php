<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\OverallCosting;

class OverAllCostingController extends Controller
{
    public function index(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        // $products = DB::table('product_master')->get();
        $costings = DB::table('overall_costing')
            ->join('product_master', 'overall_costing.productId', '=', 'product_master.id')
            ->select(
                'overall_costing.*',
                'product_master.name as product_name' // Select product name from product_master
            )
            ->where('overall_costing.status', 'active') // Fetch only active records
            ->where('overall_costing.store_id',$storeid)->get(); // Retrieve all active overall_costing records
        return view('overallCost.overallCosting', compact('costings'));
    }

    public function create(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        $recipeproducts = DB::table('recipe_master')
            ->join('product_master', 'recipe_master.product_id', '=', 'product_master.id')
            ->leftJoin('overall_costing', function ($join) use ($storeid) {
                $join->on('recipe_master.product_id', '=', 'overall_costing.productId')
                    ->where('overall_costing.status', 'active') // Ensures active costing is filtered out
                    ->where('overall_costing.store_id',$storeid);
            })
            ->where('recipe_master.status', 'active')
            ->where('recipe_master.store_id',$storeid)
            ->whereNull('overall_costing.productId') // This now ensures there's no active costing
            ->select('recipe_master.product_id as id', 'product_master.name as name')
            ->get();

        // $recipeproducts = DB::table('product_master as pd')
        // ->leftJoin('item_type as it', 'pd.itemType_Id', '=', 'it.id')
        // ->leftJoin('recipe_master as rp', 'pd.id', '=', 'rp.product_id')
        // ->leftJoin('overall_costing as ovc', function ($join) {
        //     $join->on('pd.id', '=', 'ovc.productId')
        //          ->where('ovc.status', 'active'); // Exclude active costing
        // })
        // ->where(function ($query) {
        //     $query->where(function ($q) {
        //         $q->where('it.itemtypename', 'Trading')
        //           ->where('pd.status', 'active'); // Rule 1: Active Trading products
        //     })
        //     ->orWhere('rp.status', 'active'); // Rule 2: Recipe exists & active
        // })
        // ->whereNull('ovc.productId') // Rule 3: Not in active overall_costing
        // ->select('pd.id as id', 'pd.name as name')
        // ->get();

        return view('overallCost.addOverallCosting', compact('recipeproducts'));
    }

    public function getABCcost(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        // If a product is selected, fetch the pricing data
        $productId = $request->query('productId');  // Get productId from request

        if (!$productId) {
            return response()->json(['error' => 'Product ID is required'], 400);
        }
        // $product = DB::table('product_master')
        //     ->join('item_type', 'product_master.itemType_Id', '=', 'item_type.id') // Join product with itemtype
        //     ->where('product_master.id', $productId) // Filter by productId
        //     ->where('item_type.itemtypename', 'Trading') // Check if item type is 'Trading'
        //     ->select('product_master.*') // Select product details
        //     ->first();

        //    if($product)
        //    {
        //     $totalRmCost = 0;
        //     $totalPmCost = 0;
        //     $totalOhCost = 0;
        //     $rpoutput = 1;
        //     $tradingCost = $product->price ?? 0;  // Ensure price is set, default to 0 if null
        //     $product_tax = $product->tax ?? 0;
        //     $itemtype = 'Trading';
        //    }

        // Raw Material total
        $totalRmCost = DB::table('rm_for_recipe as r')
            ->join('raw_materials as rm', 'r.raw_material_id', '=', 'rm.id')
            ->where('r.product_id', $productId)
            ->sum(DB::raw('(r.quantity * rm.price)'));

        // Packing Material total
        $totalPmCost = DB::table('pm_for_recipe as p')
            ->join('packing_materials as pm', 'p.packing_material_id', '=', 'pm.id')
            ->where('p.product_id', $productId)
            ->sum(DB::raw('(p.quantity * pm.price)'));

        // Overheads
        $totalOhCost = DB::table('oh_for_recipe as o')
            ->join('overheads as oh', 'o.overheads_id', '=', 'oh.id')
            ->where('o.product_id', $productId)
            ->sum(DB::raw('(o.quantity * oh.price)'));

        // MOH fallback
        $totalMohCost = 0;
        if ($totalOhCost == 0) {
            $totalMohCost = DB::table('moh_for_recipe')
                ->where('product_id', $productId)
                ->sum('price');
        }

        // Assuming you are joining these tables based on product_id
        $pricingData = DB::table('recipe_master')
            ->join('rm_for_recipe', 'rm_for_recipe.product_id', '=', 'recipe_master.product_id')
            ->leftjoin('pm_for_recipe', 'pm_for_recipe.product_id', '=', 'recipe_master.product_id')
            ->leftjoin('oh_for_recipe', 'oh_for_recipe.product_id', '=', 'recipe_master.product_id')
            ->leftjoin('moh_for_recipe', 'moh_for_recipe.product_id', '=', 'recipe_master.product_id')
            ->join('product_master', 'product_master.id', '=', 'recipe_master.product_id')
             ->leftJoin('raw_materials', 'rm_for_recipe.raw_material_id', '=', 'raw_materials.id')
            ->leftJoin('packing_materials', 'pm_for_recipe.packing_material_id', '=', 'packing_materials.id')
            ->leftJoin('overheads', 'oh_for_recipe.overheads_id', '=', 'overheads.id')
            ->where('product_master.store_id',$storeid)
            ->where('recipe_master.product_id', $productId)
            ->where('recipe_master.status', 'active')
            ->where('recipe_master.store_id',$storeid)
            ->select(
                'rm_for_recipe.raw_material_id as rm_id',
                'rm_for_recipe.quantity as rm_quantity',
                'raw_materials.price as rm_price',
                'rm_for_recipe.amount as rm_amount',
                'pm_for_recipe.packing_material_id as pm_id',
                'pm_for_recipe.quantity as pm_quantity',
                'packing_materials.price as pm_price',
                'pm_for_recipe.amount as pm_amount',
                'oh_for_recipe.overheads_id as oh_id',
                'oh_for_recipe.quantity as oh_quantity',
                'overheads.price as oh_price',
                'oh_for_recipe.amount as oh_amount',
                'moh_for_recipe.price as moh_price',
                'rm_for_recipe.id as rid',
                'pm_for_recipe.id as pid',
                'oh_for_recipe.id as ohid',
                'recipe_master.Output as rpoutput',
                'product_master.tax as product_tax',
            )
            ->get();

        // Fetch names for IDs separately
        $pricingData->transform(function ($item) use ($storeid) {
            $item->rm_name = DB::table('raw_materials')->where('store_id',$storeid)->where('id', $item->rm_id)->value('name');
            $item->pm_name = DB::table('packing_materials')->where('store_id',$storeid)->where('id', $item->pm_id)->value('name');
            $item->oh_name = DB::table('overheads')->where('store_id',$storeid)->where('id', $item->oh_id)->value('name');
            return $item;
        });

        $rpoutput = $pricingData->isNotEmpty() ? $pricingData->first()->rpoutput : null;
        $product_tax = $pricingData->isNotEmpty() ? $pricingData->first()->product_tax : null;
        // $itemtype = 'Own';
        // $tradingCost = 0;

        // Pass the data to the view
        // return view('overallCost.addoverallcosting', compact('totalOhCost','totalPmCost','totalRmCost'));

        return response()->json([
            'totalRmCost' => $totalRmCost,
            'totalPmCost' => $totalPmCost,
            'totalOhCost' => $totalOhCost > 0 ? $totalOhCost : $totalMohCost,
            'rpoutput' => $rpoutput,
            // 'tradingCost' => $tradingCost,
            'product_tax' => $product_tax,
            // 'itemtype' => $itemtype,
        ]);
    }

    public function store(Request $request)
    {
        $storeid = $request->session()->get('store_id');

        try {
            $validated = $request->validate([
                'productId' => 'required|exists:product_master,id',
                'inputRmcost' => 'required|numeric|min:0',
                'inputPmcost' => 'required|numeric|min:0',
                'inputRmPmcost' => 'required|numeric|min:0',
                'inputOverhead' => 'required|numeric|min:0',
                'inputTotalCost' => 'required|numeric|min:0',
                'inputTax' => 'required|numeric|min:0',
                'inputMargin' => 'required|numeric|min:0',
                'inputMarginAmt' => 'required|numeric|min:0',
                'inputDiscount' => 'required|numeric|min:0',
                'markupDiscount' => 'required|numeric|min:0',
                'inputSuggRate' => 'required|numeric|min:0',
                'inputSuggRatebf' => 'required|numeric|min:0',
                'inputSuggMrp' => 'required|numeric|min:0',
            ]);

            OverallCosting::create([
                'productId' => $validated['productId'],
                'rm_cost_unit' => (float) $validated['inputRmcost'],
                'pm_cost_unit' => (float) $validated['inputPmcost'],
                'rm_pm_cost' => (float) $validated['inputRmPmcost'],
                'overhead' => (float) $validated['inputOverhead'],
                'total_cost' => (float) $validated['inputTotalCost'],
                'tax' => (float) $validated['inputTax'],
                'og_margin' => (float) $request->og_margin,
                'margin' => (float) $validated['inputMargin'],
                'margin_amt' => (float) $validated['inputMarginAmt'],
                'discount' => (float) $validated['inputDiscount'],
                'markupDiscount' => (float) $validated['markupDiscount'],
                'sugg_rate' => (float) $validated['inputSuggRate'],
                'sugg_rate_bf' => (float) $validated['inputSuggRatebf'],
                'suggested_mrp' => (float) $validated['inputSuggMrp'],
                'status' => 'active',
                'store_id' => $storeid,
            ]);

            return redirect()->route('overallcosting.index')->with('success', 'Costing saved successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error inserting OverallCosting data: ' . $e->getMessage());
            return back()->with('error', 'Error saving data: ' . $e->getMessage())->withInput();
        }
    }
    public function show(Request $request, $id)
    {
        $storeid = $request->session()->get('store_id');
        $productId = DB::table('overall_costing')  // get product id
            ->where('id', $id)
            ->where('store_id',$storeid)
            ->value('productId');

        $producttax = DB::table('product_master') // get product tax
            ->where('id', $productId)
            ->where('store_id',$storeid)
            ->value('tax');

        $rpoutput = DB::table('recipe_master')
            ->where('product_id', $productId)
            ->where('status', 'active')
            ->where('store_id',$storeid)
            ->value('Output');

        $totalRmCost = DB::table('rm_for_recipe')
            ->join('raw_materials', 'rm_for_recipe.raw_material_id', '=', 'raw_materials.id')
            ->where('rm_for_recipe.product_id', $productId)
            ->where('raw_materials.status', 'active') // Ensure the status column exists
            ->sum(DB::raw('rm_for_recipe.quantity * COALESCE(raw_materials.price, 0)'));

        $costA = ($rpoutput && $rpoutput > 0) ? ($totalRmCost / $rpoutput) : 0;
        $rmCost = number_format($costA, 2);

        $totalPmCost = DB::table('pm_for_recipe')
            ->join('packing_materials', 'pm_for_recipe.packing_material_id', '=', 'packing_materials.id')
            ->where('pm_for_recipe.product_id', $productId)
            ->where('packing_materials.status', 'active') // Ensure the status column exists
            ->sum(DB::raw('pm_for_recipe.quantity * COALESCE(packing_materials.price, 0)'));

        $costB = ($rpoutput && $rpoutput > 0) ? ($totalPmCost / $rpoutput) : 0;
        $pmCost = number_format($costB, 2);

        $totalOhCost = DB::table('oh_for_recipe')
            ->join('overheads', 'oh_for_recipe.overheads_id', '=', 'overheads.id')
            ->where('oh_for_recipe.product_id', $productId)
            ->where('overheads.status', 'active') // Ensure the status column exists
            ->sum(DB::raw('oh_for_recipe.quantity * COALESCE(overheads.price, 0)'));

        // $totalMohCost = 0;
        if (empty($totalOhCost)) { // If NULL or 0
            $totalOhCost = DB::table('moh_for_recipe')
                ->where('product_id', $productId)
                ->sum('price');
        }

        $costC = ($rpoutput && $rpoutput > 0) ? ($totalOhCost / $rpoutput) : 0;
        $ohCost = number_format($costC, 2);
        $totalCost = $rmCost + $pmCost + $ohCost;

        // Fetch the specific OverallCosting record
        $costing = DB::table('overall_costing')
            ->join('product_master', 'overall_costing.productId', '=', 'product_master.id')
            ->select(
                'overall_costing.*',
                'product_master.name as product_name',
                // 'product_master.margin as margin',
            )
            ->where('overall_costing.id', $id)
            ->where('overall_costing.status', 'active')
            ->where('overall_costing.store_id',$storeid)
            ->get(); // Retrieve only records

        // Check if data exists
        if (!$costing) {
            return response()->json(['success' => false, 'message' => 'Overall-Costing was not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Overall-Costing was fetched.',
            'data' => [
                'costing' => $costing,
                'rmCost' => $rmCost,
                'totalCost' => $totalCost
            ]
        ]);
    }

    public function edit(Request $request, $id)
    {
        $storeid = $request->session()->get('store_id');
        $productId = DB::table('overall_costing')  // get product id
            ->where('id', $id)
            ->where('store_id',$storeid)
            ->value('productId');

        $producttax = DB::table('product_master') // get product tax
            ->where('id', $productId)
            ->where('store_id',$storeid)
            ->value('tax');

        $rpoutput = DB::table('recipe_master')
            ->where('product_id', $productId)
            ->where('status', 'active')
            ->where('store_id',$storeid)
            ->value('Output');

        $totalRmCost = DB::table('rm_for_recipe')
            ->join('raw_materials', 'rm_for_recipe.raw_material_id', '=', 'raw_materials.id')
            ->where('rm_for_recipe.product_id', $productId)
            ->where('raw_materials.status', 'active') // Ensure the status column exists
            ->sum(DB::raw('rm_for_recipe.quantity * COALESCE(raw_materials.price, 0)'));

        $costA = ($rpoutput && $rpoutput > 0) ? ($totalRmCost / $rpoutput) : 0;
        $rmCost = number_format($costA, 2);

        $totalPmCost = DB::table('pm_for_recipe')
            ->join('packing_materials', 'pm_for_recipe.packing_material_id', '=', 'packing_materials.id')
            ->where('pm_for_recipe.product_id', $productId)
            ->where('packing_materials.status', 'active') // Ensure the status column exists
            ->sum(DB::raw('pm_for_recipe.quantity * COALESCE(packing_materials.price, 0)'));

        $costB = ($rpoutput && $rpoutput > 0) ? ($totalPmCost / $rpoutput) : 0;
        $pmCost = number_format($costB, 2);

        $totalOhCost = DB::table('oh_for_recipe')
            ->join('overheads', 'oh_for_recipe.overheads_id', '=', 'overheads.id')
            ->where('oh_for_recipe.product_id', $productId)
            ->where('overheads.status', 'active') // Ensure the status column exists
            ->sum(DB::raw('oh_for_recipe.quantity * COALESCE(overheads.price, 0)'));

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
            ->where('overall_costing.store_id',$storeid)
            ->first(); // Retrieve only one record

        // Check if data exists
        if (!$costing) {
            return redirect()->route('overallcosting.index')->with('error', 'Record not found.');
        }

        // Return the view with costing data
        return view('overallCost.editoverallCosting', compact('costing', 'rmCost', 'pmCost', 'ohCost', 'rpoutput', 'producttax'));
    }

    public function update(Request $request, $id)
    {
        $storeid = $request->session()->get('store_id');
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
            'markupDiscount' => 'required|numeric',
            'inputSuggRate' => 'required|numeric',
            'inputSuggRatebf' => 'required|numeric',
            'inputSuggMrp' => 'required|numeric',
        ]);

        try {
            DB::table('overall_costing')->where('id', $id)->where('store_id',$storeid)->update([
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
                'og_margin' => (float) $request->og_margin,
                'margin' => (float) $request->inputMargin,
                'margin_amt' => (float) $request->inputMarginAmt,
                'discount' => (float) $request->inputDiscount,
                'markupDiscount' => (float) $request->markupDiscount,
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
        $storeid = $request->session()->get('store_id');
        $ids = $request->input('ids'); // Get the 'ids' array from the request

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No valid IDs provided.']);
        }

        try {
            // Update the status of raw materials to 'inactive'
            OverallCosting::whereIn('id', $ids)->where('store_id',$storeid)->update(['status' => 'inactive']);

            return response()->json(['success' => true, 'message' => 'Overall-Costing was inactive successfully.']);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['success' => false, 'message' => 'Error updating Overall Costing: ' . $e->getMessage()]);
        }
    }
}
