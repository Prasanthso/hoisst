<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OverAllCostingController extends Controller
{
    public function create(){

        $recipeproducts = DB::table('recipe_master')
        ->join('product_master', 'recipe_master.product_id', '=', 'product_master.id') // Join with the products table
        ->select('recipe_master.product_id as id','product_master.name as name') // Select the product name from the products table
        ->where('recipe_master.status','active')
        ->get();
        return view('overallCost.addoverallcosting', compact('recipeproducts'));
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
            // return view('overallCost.addoverallcosting', compact('totalOhCost','totalPmCost','totalRmCost'));

            return response()->json([
                'totalRmCost' => $totalRmCost,
                'totalPmCost' => $totalPmCost,
                'totalOhCost' => $totalOhCost,
            ]);
    }

    public function store()
    {

    }

}
