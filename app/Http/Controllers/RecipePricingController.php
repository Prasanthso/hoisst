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
        $products = DB::table('product_master')->get();

        return view('pricing' , compact('rawMaterials', 'packingMaterials', 'overheads', 'products'));
    }


    public function showRecipePricingList(){
        return view('recipePricing');
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

        // Validate incoming data
    $validated = $request->validate([
        'product_id' => 'required|exists:product_master,id',
        'rpoutput' => 'required|string', // Adjust to 'numeric|min:0' if it's a number
        'rpuom' => 'required|string',
        // 'rptotalCost' => 'required|numeric|min:0',
        // 'singleCost' => 'required|numeric|min:0',
    ]);

    $rpCode = UniqueCode::generateRpCode();
    // $rptotalCost = 0;
    // $singleCost = 0;


    try {
        // Create a new recipe
      $Rp =  RecipeMaster::create([
        'product_id' => 1, // Use a valid product_id from your database
        'rpcode' => 'Rp0001',
        'Output' => '100',
        'uom' => 'Kgs',
           ]);
        return response()->json([
            'success' => true,
            'message' => 'Recipe pricing added successfully.',
            'data' => $Rp,
        ]);
    } catch (\Exception $e) {
        // Log the error and return an error response
        // \Log::error('Error inserting data: ' . $e->getMessage());
        return redirect()->back()->with('error', 'An error occurred while adding the recipe-pricing.');
    }

    // Redirect back with a success message
    return redirect()->route('receipepricing.index')->with('success', 'Recipe-pricing added successfully.');
}

    /**
     * Display the specified resource.
     */

    public function show(Request $request)
    {
        /*
        $id=1;
        //
        // $rawMaterials = DB::table('raw_materials')->get();
        // $packingMaterials = DB::table('packing_materials')->get();
        // $overheads = DB::table('overheads')->get();
        $products = DB::table('product_master')->get();

        // // Join all tables based on product_id
        // $rpDetails = DB::table('recipe_master')
        // ->join('product_master', 'recipe_master.product_id', '=', 'product_master.id')
        // ->join('raw_materials', 'raw_materials.product_id', '=', 'product_master.id')
        // ->join('packing_materials', 'packing_materials.product_id', '=', 'product_master.id')
        // ->join('overheads', 'overheads.product_id', '=', 'product_master.id')
        // ->where('recipe_master.product_id', $id) // Filter by the given product_id
        // ->select(
        //     'recipe_master.*',
        //     'product_master.name as product_name',
        //     'raw_materials.name as raw_material_name',
        //     'packing_materials.name as packing_material_name',
        //     'overheads.name as overhead_name'
        // )
        // ->get();

        return view('viewPricing' , compact('products'));
        */
    }


    public function showPricingForm(Request $request)
    {
        // Retrieve all products to display in the dropdown
        $products = Product::all();

        // If a product is selected, fetch the pricing data
        if ($request->has('product_id')) {
            $productId = $request->input('product_id');

            // Assuming you are joining these tables based on product_id
            $pricingData = DB::table('recipe_master')
                ->join('rm_for_recipe', 'rm_for_recipe.product_id', '=', 'recipe_master.product_id')
                ->leftjoin('pm_for_recipe', 'pm_for_recipe.product_id', '=', 'recipe_master.product_id')
                ->leftjoin('oh_for_recipe', 'oh_for_recipe.product_id', '=', 'recipe_master.product_id')
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
                    'oh_for_recipe.price as oh_price'
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

            // Pass the data to the view
            return view('viewPricing', compact('products', 'pricingData','totalCost'));
        }

        return view('viewPricing', compact('products'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
