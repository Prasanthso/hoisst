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
        $storeId = session('store_id'); // Get store ID from session
        // $storeId = $request->session()->get('store_id');
        $rawMaterials = DB::table('raw_materials')
            ->where('status', 'active')
            ->where('store_id', $storeId)
            ->get();

        $packingMaterials = DB::table('packing_materials')
            ->where('status', 'active')
            ->where('store_id', $storeId)
            ->get();
        $overheads = DB::table('overheads')
            ->where('status', 'active')
            ->where('store_id', $storeId)
            ->get();

        // $products = DB::table('product_master')->get();
        $products = DB::table('product_master')
            ->leftJoin('recipe_master', 'product_master.id', '=', 'recipe_master.product_id') // Left join with recipe_master
            ->select('product_master.id as id', 'product_master.name as name') // Select the product name from product_master
            ->where(function ($query) use ($storeId) {
                $query->whereNull('recipe_master.product_id') // Include products with no match in recipe_master
                        ->orWhere(function ($q) use ($storeId) {
                            $q->where('recipe_master.store_id', $storeId)
                            ->where('recipe_master.status', '!=', 'active');
                        }); // Include products where status is not 'inactive'
            })
            ->where('product_master.store_id', $storeId)
            ->get();

        return view('RecipePricing.pricing', compact('rawMaterials', 'packingMaterials', 'overheads', 'products'));
    }


    public function showRecipePricingList()
    {
        $storeId = session('store_id');

        $reports = DB::select("
        SELECT
            pm.id AS SNO,
            pm.name AS Product_Name,
            pm.price AS P_MRP,
            pm.tax AS tax,
            pm.margin AS margin,
            oc.suggested_mrp AS S_MRP,
            oc.discount AS discount,
            oc.markupDiscount AS markupDiscount,
            oc.margin_amt As margin_amt,
            rm_total.RM_IDs,
            rm_total.RM_Names,
            pm_total.PM_IDs,
            pm_total.PM_Names,
            COALESCE(rm_total.RM_Cost, 0) / COALESCE(rmst.Output, 1) AS RM_Cost,
            COALESCE(pm_total.PM_Cost, 0) / COALESCE(rmst.Output, 1) AS PM_Cost,
            COALESCE(oh_total.Overhead_Cost, 0) / COALESCE(rmst.Output, 1) AS OH_Cost,
            COALESCE(moh_total.MOH_Cost, 0) / COALESCE(rmst.Output, 1) AS MOH_Cost,
            rmst.Output
        FROM product_master pm
        JOIN recipe_master rmst ON pm.id = rmst.product_id
        LEFT JOIN (
            SELECT
                rfr.product_id,
                GROUP_CONCAT(DISTINCT rfr.raw_material_id ORDER BY rfr.raw_material_id ASC SEPARATOR ', ') AS RM_IDs,
                GROUP_CONCAT(DISTINCT rm.name ORDER BY rm.name ASC SEPARATOR ', ') AS RM_Names,
                SUM(COALESCE(rfr.quantity, 0) * COALESCE(rm.price, 0)) AS RM_Cost
            FROM rm_for_recipe rfr
            JOIN raw_materials rm ON rfr.raw_material_id = rm.id
            GROUP BY rfr.product_id
        ) AS rm_total ON pm.id = rm_total.product_id
        LEFT JOIN (
            SELECT
                pfr.product_id,
                GROUP_CONCAT(DISTINCT pfr.packing_material_id ORDER BY pfr.packing_material_id ASC SEPARATOR ', ') AS PM_IDs,
                GROUP_CONCAT(DISTINCT pkm.name ORDER BY pkm.name ASC SEPARATOR ', ') AS PM_Names,
                SUM(COALESCE(pfr.quantity, 0) * COALESCE(pkm.price, 0)) AS PM_Cost
            FROM pm_for_recipe pfr
            JOIN packing_materials pkm ON pfr.packing_material_id = pkm.id
            GROUP BY pfr.product_id
        ) AS pm_total ON pm.id = pm_total.product_id
        LEFT JOIN (
            SELECT
                ofr.product_id,
                SUM(COALESCE(ofr.quantity, 0) * COALESCE(oh.price, 0)) AS Overhead_Cost
            FROM oh_for_recipe ofr
            JOIN overheads oh ON ofr.overheads_id = oh.id
            GROUP BY ofr.product_id
        ) AS oh_total ON pm.id = oh_total.product_id
        LEFT JOIN (
            SELECT
                product_id,
                SUM(COALESCE(price, 0)) AS MOH_Cost
            FROM moh_for_recipe
            GROUP BY product_id
        ) AS moh_total ON pm.id = moh_total.product_id
        LEFT JOIN overall_costing oc
            ON pm.id = oc.productId AND oc.status = 'active'
        WHERE
            rmst.status = 'active'
            AND oc.suggested_mrp IS NOT NULL
            AND pm.store_id = :storeId
        ORDER BY pm.name ASC
    ", ['storeId' => $storeId]);

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
        $storeId = $request->session()->get('store_id');

        // Retrieve all products to display in the dropdown
        // $products = Product::all();
        $rawMaterials = DB::table('raw_materials')->where('store_id',$storeId)->where('status','active')->get();
        $packingMaterials = DB::table('packing_materials')->where('store_id',$storeId)->where('status','active')->get();
        $overheads = DB::table('overheads')->where('store_id',$storeId)->where('status','active')->get();
        $products = DB::table('recipe_master')
            ->join('product_master', 'recipe_master.product_id', '=', 'product_master.id') // Join with the products table
            ->select('recipe_master.product_id as id', 'product_master.name as name') // Select the product name from the products table
            ->where('recipe_master.status', 'active')
            ->where('recipe_master.store_id',$storeId)
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
            ->where('recipe_master.store_id',$storeId)
            ->select(
                    'rm_for_recipe.raw_material_id as rm_id',
                    'rm_for_recipe.quantity as rm_quantity',
                    'rm_for_recipe.code as rm_code',
                    'rm_for_recipe.uom as rm_uom',
                    'raw_materials.price as rm_price',
                    'pm_for_recipe.packing_material_id as pm_id',
                    'pm_for_recipe.quantity as pm_quantity',
                    'pm_for_recipe.code as pm_code',
                    'pm_for_recipe.uom as pm_uom',
                    'packing_materials.price as pm_price',
                    'oh_for_recipe.overheads_id as oh_id',
                    'oh_for_recipe.quantity as oh_quantity',
                    'oh_for_recipe.code as oh_code',
                    'oh_for_recipe.uom as oh_uom',
                    'overheads.price as oh_price',
                    'moh_for_recipe.name as moh_name',
                    'moh_for_recipe.oh_type as moh_type',
                    'moh_for_recipe.price as moh_price',
                    'moh_for_recipe.percentage as moh_percentage',
                    'rm_for_recipe.id as rid',
                    'pm_for_recipe.id as pid',
                    'oh_for_recipe.id as ohid',
                    'moh_for_recipe.id as mohid',
                    'recipe_master.Output as rp_output',
                    'recipe_master.uom as rp_uom',
                )
                ->distinct()
                ->get();

            // Fetch names for IDs separately
            $pricingData->transform(function ($item) use ($storeId) {
                $item->rm_name = DB::table('raw_materials')->where('store_id', $storeId)->where('id', $item->rm_id)->value('name');
                $item->pm_name = DB::table('packing_materials')->where('store_id', $storeId)->where('id', $item->pm_id)->value('name');
                $item->oh_name = DB::table('overheads')->where('store_id', $storeId)->where('id', $item->oh_id)->value('name');
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
            return view('RecipePricing.viewPricing', compact('products', 'pricingData', 'totalCost', 'totalRmCost'));
        }

        return view('RecipePricing.viewPricing', compact('products','pricingData', 'totalCost', 'totalRmCost'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $storeId = $request->session()->get('store_id');
        $productId = $id;

        // $storeId = session('store_id');

        $rawMaterials = DB::table('raw_materials')
            ->where('status', 'active')
            ->where('store_id', $storeId)
            ->get();

        $packingMaterials = DB::table('packing_materials')
            ->where('status', 'active')
            ->where('store_id', $storeId)
            ->get();

        $overheads = DB::table('overheads')
            ->where('status', 'active')
            ->where('store_id', $storeId)
            ->get();
        $products = DB::table('recipe_master')
            ->join('product_master', 'recipe_master.product_id', '=', 'product_master.id') // Join with the products table
            ->where('recipe_master.product_id', $productId)
            ->where('recipe_master.store_id', $storeId)
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
                     // Joining with Master Tables
            ->leftJoin('raw_materials', 'rm_for_recipe.raw_material_id', '=', 'raw_materials.id')
            ->leftJoin('packing_materials', 'pm_for_recipe.packing_material_id', '=', 'packing_materials.id')
            ->leftJoin('overheads', 'oh_for_recipe.overheads_id', '=', 'overheads.id')
                ->where('recipe_master.product_id', $productId)
                ->where('recipe_master.store_id', $storeId)
                ->select(
                    'rm_for_recipe.raw_material_id as rm_id',
                    'rm_for_recipe.quantity as rm_quantity',
                    'rm_for_recipe.code as rm_code',
                    'rm_for_recipe.uom as rm_uom',
                    'raw_materials.price as rm_price',
                    'pm_for_recipe.packing_material_id as pm_id',
                    'pm_for_recipe.quantity as pm_quantity',
                    'pm_for_recipe.code as pm_code',
                    'pm_for_recipe.uom as pm_uom',
                    'packing_materials.price as pm_price',
                    'oh_for_recipe.overheads_id as oh_id',
                    'oh_for_recipe.quantity as oh_quantity',
                    'oh_for_recipe.code as oh_code',
                    'oh_for_recipe.uom as oh_uom',
                    'overheads.price as oh_price',
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
            $pricingData->transform(function ($item) use ($storeId) {
                $item->rm_name = DB::table('raw_materials')->where('store_id', $storeId)->where('id', $item->rm_id)->value('name');
                $item->pm_name = DB::table('packing_materials')->where('store_id', $storeId)->where('id', $item->pm_id)->value('name');
                $item->oh_name = DB::table('overheads')->where('store_id', $storeId)->where('id', $item->oh_id)->value('name');
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
            return view('RecipePricing.editPricing', compact('rawMaterials', 'packingMaterials', 'overheads', 'products', 'pricingData', 'totalCost'));
        }

        return view('RecipePricing.editPricing', compact('rawMaterials', 'packingMaterials', 'overheads', 'products', 'pricingData', 'totalCost'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {}

    /**
     * Remove the specified resource from storage.
     */

     public function checkProductExists(Request $request)
    {
        $storeId = $request->session()->get('store_id');
        $exists = DB::table('overall_costing')
            ->where('productId', $request->productId)
            ->where('status', 'active')
            ->where('store_id', $storeId)
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function destroy(Request $request)
    {
        $storeId = $request->session()->get('store_id');
        // Validate the product_id
        $request->validate([
            'product_id' => 'required|exists:product_master,id',
        ]);

        $exists = DB::table('overall_costing')
        ->where('productId', $request->product_id)
        ->where('status', 'active')
        ->where('store_id', $storeId)
        ->exists();

        if ($exists) {
            return redirect()->route('receipepricing.index')->with('error', 'Recipe-Pricing data might be in use.');
        }

        // Delete the pricing data for the selected product
        DB::table('recipe_master')
            ->where('product_id', $request->product_id)
            ->where('store_id', $storeId)
            // ->whereNotExists(function ($query) {
            //     $query->select(DB::raw(1))
            //         ->from('overall_costing')
            //         ->whereColumn('overall_costing.productId', 'recipe_master.product_id');
            // })
            ->update(['status' => 'inactive']);

        DB::table('rm_for_recipe')
            ->where('product_id', $request->product_id)
            ->where('store_id', $storeId)
            ->delete();
        DB::table('pm_for_recipe')
            ->where('product_id', $request->product_id)
            ->where('store_id', $storeId)
            ->delete();
        DB::table('oh_for_recipe')
            ->where('product_id', $request->product_id)
            ->where('store_id', $storeId)
            ->delete();
        DB::table('moh_for_recipe')
            ->where('product_id', $request->product_id)
            ->where('store_id', $storeId)
            ->delete();
        DB::table('overall_costing')
            ->where('productId', $request->product_id)
            ->where('store_id', $storeId)
            ->delete();
        // Redirect back with a success message
        return redirect()->route('receipepricing.form')->with('success', 'Recipe-Pricing data deleted successfully!');

    }
}
