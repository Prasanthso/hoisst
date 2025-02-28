<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\CategoryItems;
use App\Models\RawMaterial;
use App\Models\Product;
use App\Models\UniqueCode;
use Illuminate\Http\Request;

class RecipePricingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rawMaterials = DB::table('raw_materials')->get();
        $packingMaterials = DB::table('packing_materials')->get();
        $overheads = DB::table('overheads')->get();
        // $products = DB::table('product_master')->get();
        $products = DB::table('product_master')
            ->leftJoin('recipe_master', 'product_master.id', '=', 'recipe_master.product_id') // Left join with recipe_master
            ->select('product_master.id as id', 'product_master.name as name') // Select the product name from product_master
            ->where(function ($query) {
                $query->whereNull('recipe_master.product_id') // Include products with no match in recipe_master
                    ->orWhere('recipe_master.status', '!=', 'active'); // Include products where status is not 'inactive'
            })
            ->get();

        return view('pricing', compact('rawMaterials', 'packingMaterials', 'overheads', 'products'));
    }


    public function showRecipePricingList()
    {
        $reports = DB::select("
            SELECT
                pm.id AS SNO,
                pm.name AS Product_Name,
                pm.price AS P_MRP,
                pm.tax As tax,
                oc.suggested_mrp AS S_MRP,

                -- Raw Material Cost
                SUM(COALESCE(rfr.quantity, 0) * COALESCE(rm.price, 0) / COALESCE(rmst.Output, 1)) AS RM_Cost,
                SUM((COALESCE(rfr.quantity, 0) * COALESCE(rm.price, 0) / COALESCE(rmst.Output, 1)) * 100 / COALESCE(oc.suggested_mrp, 1)) AS RM_perc,

                -- Packing Material Cost
                SUM(COALESCE(pfr.quantity, 0) * COALESCE(pkm.price, 0) / COALESCE(rmst.Output, 1)) AS PM_Cost,
                SUM((COALESCE(pfr.quantity, 0) * COALESCE(pkm.price, 0) / COALESCE(rmst.Output, 1)) * 100 / COALESCE(oc.suggested_mrp, 1)) AS PM_perc,

                -- Overhead Cost (excluding mofr.quantity from multiplication)

                (COALESCE(ofr.quantity, 0) * COALESCE(oh.price, 0) / COALESCE(rmst.Output, 1)) +
                    COALESCE(mofr.price, 0) / COALESCE(rmst.Output, 1)
                 AS OH_Cost,

                -- Overhead Percentage
                SUM(
                    ((COALESCE(rfr.quantity, 0) * COALESCE(rm.price, 0) / COALESCE(rmst.Output, 1)) +
                    (COALESCE(pfr.quantity, 0) * COALESCE(pkm.price, 0) / COALESCE(rmst.Output, 1))) *
                    (COALESCE(ofr.quantity, 0) * COALESCE(oh.price, 0) / COALESCE(rmst.Output, 1) +
                    COALESCE(mofr.price, 0) / COALESCE(rmst.Output, 1)) / 100
                ) AS OH_perc,

                -- Total Cost
                SUM((COALESCE(rfr.quantity, 0) * COALESCE(rm.price, 0) / COALESCE(rmst.Output, 1)) +
                    (COALESCE(pfr.quantity, 0) * COALESCE(pkm.price, 0) / COALESCE(rmst.Output, 1))) AS TOTAL,

                -- Total Percentage
                SUM(((COALESCE(rfr.quantity, 0) * COALESCE(rm.price, 0) / COALESCE(rmst.Output, 1)) +
                    (COALESCE(pfr.quantity, 0) * COALESCE(pkm.price, 0) / COALESCE(rmst.Output, 1))) * 100 / COALESCE(oc.suggested_mrp, 1)) AS Total_perc,

                -- Final Cost Calculation
                SUM(
                        (COALESCE(rfr.quantity, 0) * COALESCE(rm.price, 0) / COALESCE(rmst.Output, 1)) +
                        (COALESCE(pfr.quantity, 0) * COALESCE(pkm.price, 0) / COALESCE(rmst.Output, 1))
                    ) + (COALESCE(ofr.quantity, 0) * COALESCE(oh.price, 0) / COALESCE(rmst.Output, 1)) +
                        COALESCE(mofr.price, 0) / COALESCE(rmst.Output, 1)
                    AS COST,


                -- Selling Cost and Margin Calculations
                COALESCE(oc.suggested_mrp, 0) * 0.75 AS Selling_Cost,
                ((COALESCE(oc.suggested_mrp, 0) * 0.75) * 100) / (100 + pm.tax) AS Before_tax,

                -- Margin Calculation
                SUM((((COALESCE(oc.suggested_mrp, 0) * 0.75) * 100) / (100 + pm.tax)) -
                    (COALESCE(rfr.quantity, 0) * COALESCE(rm.price, 0) / COALESCE(rmst.Output, 1) +
                    COALESCE(pfr.quantity, 0) * COALESCE(pkm.price, 0) / COALESCE(rmst.Output, 1) +
                    COALESCE(ofr.quantity, 0) * COALESCE(oh.price, 0) / COALESCE(rmst.Output, 1) +
                    COALESCE(mofr.price, 0) / COALESCE(rmst.Output, 1))
                ) AS Margin,

                -- Margin Percentage
                SUM(
                    ((((COALESCE(oc.suggested_mrp, 0) * 0.75) * 100) / (100 + pm.tax)) -
                    (COALESCE(rfr.quantity, 0) * COALESCE(rm.price, 0) / COALESCE(rmst.Output, 1) +
                    COALESCE(pfr.quantity, 0) * COALESCE(pkm.price, 0) / COALESCE(rmst.Output, 1) +
                    COALESCE(ofr.quantity, 0) * COALESCE(oh.price, 0) / COALESCE(rmst.Output, 1) +
                    COALESCE(mofr.price, 0) / COALESCE(rmst.Output, 1))
                    ) / (((oc.suggested_mrp * 0.75) * 100) / (100 + pm.tax)) * 100
                ) AS Margin_perc,

                rmst.Output
            FROM
                product_master pm
            JOIN
                recipe_master rmst ON pm.id = rmst.product_id
            LEFT JOIN
                rm_for_recipe rfr ON rmst.product_id = rfr.product_id
            LEFT JOIN
                raw_materials rm ON rfr.raw_material_id = rm.id
            LEFT JOIN
                pm_for_recipe pfr ON rmst.product_id = pfr.product_id
            LEFT JOIN
                packing_materials pkm ON pfr.packing_material_id = pkm.id
            LEFT JOIN
                oh_for_recipe ofr ON rmst.product_id = ofr.product_id
            LEFT JOIN
                overheads oh ON ofr.overheads_id = oh.id
            LEFT JOIN
                moh_for_recipe mofr ON rmst.product_id = mofr.product_id
            LEFT JOIN
                overall_costing oc ON pm.id = oc.productId AND oc.status = 'active'
            WHERE
                rmst.status = 'active' AND oc.suggested_mrp IS NOT NULL
            GROUP BY
            pm.id, pm.name, pm.price, pm.tax, oc.suggested_mrp, rmst.Output, ofr.quantity, oh.price, mofr.price
            ORDER BY
            pm.name ASC;

        ");
        return view('recipePricing', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        //
    }

    /**
     * Display the specified resource.
     */

    public function show(string $id)
    {
        //
    }

    public function showPricingForm(Request $request)
    {
        // Retrieve all products to display in the dropdown
        // $products = Product::all();
        $rawMaterials = DB::table('raw_materials')->get();
        $packingMaterials = DB::table('packing_materials')->get();
        $overheads = DB::table('overheads')->get();
        $products = DB::table('recipe_master')
            ->join('product_master', 'recipe_master.product_id', '=', 'product_master.id') // Join with the products table
            ->select('recipe_master.product_id as id', 'product_master.name as name') // Select the product name from the products table
            ->where('recipe_master.status', 'active')
            ->get(); // Get all results
        $productId = $request->input('product_id');
        $pricingData = null;
        $totalRmCost = 0;
        $totalCost = 0;
        // If a product is selected, fetch the pricing data
        if($productId) //($request->has('product_id'))
        {
            // Assuming you are joining these tables based on product_id
            $pricingData = DB::table('recipe_master')
                ->join('rm_for_recipe', 'rm_for_recipe.product_id', '=', 'recipe_master.product_id')
                ->leftjoin('pm_for_recipe', 'pm_for_recipe.product_id', '=', 'recipe_master.product_id')
                ->leftjoin('oh_for_recipe', 'oh_for_recipe.product_id', '=', 'recipe_master.product_id')
                ->leftjoin('moh_for_recipe', 'moh_for_recipe.product_id', '=', 'recipe_master.product_id')
                // Joining with Master Tables
            ->leftJoin('raw_materials', 'rm_for_recipe.raw_material_id', '=', 'raw_materials.id')
            ->leftJoin('packing_materials', 'pm_for_recipe.packing_material_id', '=', 'packing_materials.id')
            ->leftJoin('overheads', 'oh_for_recipe.overheads_id', '=', 'overheads.id')
            ->where('recipe_master.product_id', $productId)
            ->select(
                    'rm_for_recipe.raw_material_id as rm_id',
                    'rm_for_recipe.quantity as rm_quantity',
                    'rm_for_recipe.code as rm_code',
                    'rm_for_recipe.uom as rm_uom',
                    'rm_for_recipe.price as rm_price',
                    'pm_for_recipe.packing_material_id as pm_id',
                    'pm_for_recipe.quantity as pm_quantity',
                    'pm_for_recipe.code as pm_code',
                    'pm_for_recipe.uom as pm_uom',
                    'pm_for_recipe.price as pm_price',
                    'oh_for_recipe.overheads_id as oh_id',
                    'oh_for_recipe.quantity as oh_quantity',
                    'oh_for_recipe.code as oh_code',
                    'oh_for_recipe.uom as oh_uom',
                    'oh_for_recipe.price as oh_price',
                    'moh_for_recipe.name as moh_name',
                    'moh_for_recipe.oh_type as moh_type',
                    'moh_for_recipe.price as moh_price',
                    'moh_for_recipe.percentage as moh_percentage',
                    'rm_for_recipe.id as rid',
                    'pm_for_recipe.id as pid',
                    'oh_for_recipe.id as ohid',
                    'moh_for_recipe.id as mohid',
                    'recipe_master.Output as rp_output',
                    // 'recipe_master.uom as rp_uom',
                )
                ->distinct()
                ->get();

            // Fetch names for IDs separately
            $pricingData->transform(function ($item) {
                $item->rm_name = DB::table('raw_materials')->where('id', $item->rm_id)->value('name');
                $item->pm_name = DB::table('packing_materials')->where('id', $item->pm_id)->value('name');
                $item->oh_name = DB::table('overheads')->where('id', $item->oh_id)->value('name');
                return $item;
            });

            $totalRmCost = $pricingData->sum(function ($data) {
                return $data->rm_quantity * $data->rm_price;
            });
            $totalPmCost = $pricingData->sum(function ($data) {
                return $data->pm_quantity * $data->pm_price;
            });
            $totalOhCost = $pricingData->sum(function ($data) {
                return $data->oh_quantity * $data->oh_price;
            });
            $totalCost = $totalRmCost + $totalPmCost + $totalOhCost;

            // Pass the data to the view
            return view('viewPricing', compact('products', 'pricingData', 'totalCost', 'totalRmCost'));
        }

        return view('viewPricing', compact('products','pricingData', 'totalCost', 'totalRmCost'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $productId = $id;

        $rawMaterials = DB::table('raw_materials')->get();
        $packingMaterials = DB::table('packing_materials')->get();
        $overheads = DB::table('overheads')->get();

        $products = DB::table('recipe_master')
            ->join('product_master', 'recipe_master.product_id', '=', 'product_master.id') // Join with the products table
            ->where('recipe_master.product_id', $productId)
            ->select('product_master.id as id', 'product_master.name as name', 'recipe_master.Output as rp_output', 'recipe_master.uom as rp_uom')
            ->get();

        $pricingData = null;
        $totalCost = 0;
        // If a product is selected, fetch the pricing data
        if ($productId) {
            // $productId = $request->input('product_id');

            // Assuming you are joining these tables based on product_id
            $pricingData = DB::table('recipe_master')
                ->join('rm_for_recipe', 'rm_for_recipe.product_id', '=', 'recipe_master.product_id')
                ->leftjoin('pm_for_recipe', 'pm_for_recipe.product_id', '=', 'recipe_master.product_id')
                ->leftjoin('oh_for_recipe', 'oh_for_recipe.product_id', '=', 'recipe_master.product_id')
                ->leftjoin('moh_for_recipe', 'moh_for_recipe.product_id', '=', 'recipe_master.product_id')
                ->where('recipe_master.product_id', $productId)
                ->select(
                    'rm_for_recipe.raw_material_id as rm_id',
                    'rm_for_recipe.quantity as rm_quantity',
                    'rm_for_recipe.code as rm_code',
                    'rm_for_recipe.uom as rm_uom',
                    'rm_for_recipe.price as rm_price',
                    'pm_for_recipe.packing_material_id as pm_id',
                    'pm_for_recipe.quantity as pm_quantity',
                    'pm_for_recipe.code as pm_code',
                    'pm_for_recipe.uom as pm_uom',
                    'pm_for_recipe.price as pm_price',
                    'oh_for_recipe.overheads_id as oh_id',
                    'oh_for_recipe.quantity as oh_quantity',
                    'oh_for_recipe.code as oh_code',
                    'oh_for_recipe.uom as oh_uom',
                    'oh_for_recipe.price as oh_price',
                    'moh_for_recipe.name as moh_name',
                    'moh_for_recipe.oh_type as moh_type',
                    'moh_for_recipe.price as moh_price',
                    'moh_for_recipe.percentage as moh_percentage',
                    'rm_for_recipe.id as rid',
                    'pm_for_recipe.id as pid',
                    'oh_for_recipe.id as ohid',
                    'moh_for_recipe.id as mohid',
                    'recipe_master.Output as rp_output',
                    // 'recipe_master.uom as rp_uom',
                )
                ->get();

            // Fetch names for IDs separately
            $pricingData->transform(function ($item) {
                $item->rm_name = DB::table('raw_materials')->where('id', $item->rm_id)->value('name');
                $item->pm_name = DB::table('packing_materials')->where('id', $item->pm_id)->value('name');
                $item->oh_name = DB::table('overheads')->where('id', $item->oh_id)->value('name');
                return $item;
            });

            $totalRmCost = $pricingData->sum(function ($data) {
                return $data->rm_quantity * $data->rm_price;
            });
            $totalPmCost = $pricingData->sum(function ($data) {
                return $data->pm_quantity * $data->pm_price;
            });
            $totalOhCost = $pricingData->sum(function ($data) {
                return $data->oh_quantity * $data->oh_price;
            });
            $totalCost = $totalRmCost + $totalPmCost + $totalOhCost;

            // Pass the data to the view
            return view('editPricing', compact('rawMaterials', 'packingMaterials', 'overheads', 'products', 'pricingData', 'totalCost'));
        }

        return view('editPricing', compact('rawMaterials', 'packingMaterials', 'overheads', 'products', 'pricingData', 'totalCost'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // Validate the product_id
        $request->validate([
            'product_id' => 'required|exists:product_master,id',
        ]);

        // Delete the pricing data for the selected product
        DB::table('recipe_master')
            ->where('product_id', $request->product_id)
            ->update(['status' => 'inactive']);

        DB::table('rm_for_recipe')
            ->where('product_id', $request->product_id)
            ->delete();
        DB::table('pm_for_recipe')
            ->where('product_id', $request->product_id)
            ->delete();
        DB::table('oh_for_recipe')
            ->where('product_id', $request->product_id)
            ->delete();
        DB::table('moh_for_recipe')
            ->where('product_id', $request->product_id)
            ->delete();

        // Redirect back with a success message
        return redirect()->route('receipepricing.index')->with('success', 'Recipe-Pricing data deleted successfully!');
    }
}
