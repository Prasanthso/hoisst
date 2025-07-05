<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\CategoryItems;
use App\Models\RmForRecipe;
use App\Models\UniqueCode;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RmForRecipeController extends Controller
{
    public function rmstore(Request $request)
    {
        dd($request->all());  // To see the incoming data
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    // public function store(Request $request)
    // {
    //     $storeid = $request->session()->get('store_id');
    //     try {
    //         // Validate the request
    //         $request->validate([
    //             'raw_material_id' => 'required|exists:raw_materials,id',
    //             'product_id' => 'required|exists:product_master,id',
    //             'quantity' => 'required|numeric',
    //             'amount' => 'required|numeric',
    //             'code' => 'required|string',
    //             'rpoutput' => 'required|string',
    //             'rpuom' => 'required|string',
    //         ]);

    //         if($request->product_id)
    //         {
    //             $isProduct = DB::table('recipe_master')
    //             ->where('product_id', $request->product_id)
    //             ->where('store_id',$storeid)
    //             ->exists();

    //             if($isProduct == false)
    //             {
    //                 $rpCode = UniqueCode::generateRpCode();

    //                     $rp = DB::table('recipe_master')->insert([
    //                         'product_id' => $request->product_id,
    //                         'rpcode' => $rpCode,
    //                         'Output' => $request->rpoutput,
    //                         'uom' => $request->rpuom,
    //                         'totalCost' => 0,
    //                         'singleCost' => 0,
    //                         'status' => 'active',
    //                         'store_id' => $storeid
    //                     ]);
    //                     // Product::where('id', $request->product_id)
    //                     // ->where('status', 'active')
    //                     // ->update(['recipe_created_status' => 'yes']);
    //             }
    //             else if($isProduct == true)
    //             {
    //                 // $rpCode = UniqueCode::generateRpCode();
    //                 $rp = DB::table('recipe_master')
    //                 ->where('product_id', $request->product_id) // Condition to match the row(s) to update
    //                 ->where('store_id',$storeid)
    //                 ->update([
    //                     // 'rpcode' => $rpCode,
    //                     'Output' => $request->rpoutput,
    //                     'uom' => $request->rpuom,
    //                     'totalCost' => 0,
    //                     'singleCost' => 0,
    //                     'status' => 'active',
    //                 ]);
    //             }
    //             else{
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'There was an issue inserting.',
    //                     'error' => $e->getMessage()
    //                 ], 500);
    //             }
    //         }

    //         // Create the record
    //         $rmForRecipe = RmForRecipe::create([
    //             'raw_material_id' => $request->raw_material_id,
    //             'product_id' => $request->product_id,
    //             'quantity' => $request->quantity,
    //             'code' => $request->code,
    //             'uom' => $request->uom ?? 'default_uom',
    //             'price' => $request->price ?? 0,
    //             'amount' => $request->amount,
    //             'store_id' => $storeid
    //         ]);

    //         // Return success response
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Raw Material updated successfully.',
    //             'data' => $rmForRecipe,
    //             'rmInserted_id' => $rmForRecipe->id,
    //         ]);
    //     } catch (\Exception $e) {
    //         // Handle the error gracefully
    //         // \Log::error('Error storing raw material: ' . $e->getMessage());

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'There was an issue updating the raw material.',
    //             'error' => $e->getMessage()
    //         ], 500); // Internal Server Error
    //     }
    // }

    public function store(Request $request)
    {
        $storeid = $request->session()->get('store_id');

        try {
            // Validate the request
            $request->validate([
                'raw_material_id' => 'required|exists:raw_materials,id',
                'product_id' => 'required|exists:product_master,id',
                'quantity' => 'required|numeric',
                'amount' => 'required|numeric',
                'code' => 'required|string',
                'rpoutput' => 'required|string',
                'rpuom' => 'required|string',
            ]);

            // If you want to remove recipe_master logic completely, skip this block:
            /*
        if ($request->product_id) {
            $isProduct = DB::table('recipe_master')
                ->where('product_id', $request->product_id)
                ->where('store_id', $storeid)
                ->exists();

            if ($isProduct == false) {
                $rpCode = UniqueCode::generateRpCode();

                DB::table('recipe_master')->insert([
                    'product_id' => $request->product_id,
                    'rpcode' => $rpCode,
                    'Output' => $request->rpoutput,
                    'uom' => $request->rpuom,
                    'totalCost' => 0,
                    'singleCost' => 0,
                    'status' => 'active',
                    'store_id' => $storeid
                ]);
            } else {
                DB::table('recipe_master')
                    ->where('product_id', $request->product_id)
                    ->where('store_id', $storeid)
                    ->update([
                        'Output' => $request->rpoutput,
                        'uom' => $request->rpuom,
                        'totalCost' => 0,
                        'singleCost' => 0,
                        'status' => 'active',
                    ]);
            }
        }
        */

            // Create the record in rm_for_recipe
            $rmForRecipe = RmForRecipe::create([
                'raw_material_id' => $request->raw_material_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'code' => $request->code,
                'uom' => $request->uom ?? 'default_uom',
                'price' => $request->price ?? 0,
                'amount' => $request->amount,
                'store_id' => $storeid
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Raw Material updated successfully.',
                'data' => $rmForRecipe,
                'rmInserted_id' => $rmForRecipe->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'There was an issue updating the raw material.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function addRecipeCosting(Request $request)
    {
        $storeid = $request->session()->get('store_id');

        try {
            $request->validate([
                'product_id' => 'required|exists:product_master,id',
                'rpoutput' => 'required|string',
                'rpuom' => 'required|string'
            ]);

            $existing = DB::table('recipe_master')
                ->where('product_id', $request->product_id)
                ->where('store_id', $storeid)
                ->first();

            if (!$existing) {
                $rpCode = UniqueCode::generateRpCode();

                $id = DB::table('recipe_master')->insertGetId([
                    'product_id' => $request->product_id,
                    'rpcode' => $rpCode,
                    'Output' => $request->rpoutput,
                    'uom' => $request->rpuom,
                    'totalCost' => 0,
                    'singleCost' => 0,
                    'status' => 'active',
                    'store_id' => $storeid,
                    'created_at' => now(),
                ]);
            } else {
                DB::table('recipe_master')
                    ->where('product_id', $request->product_id)
                    ->where('store_id', $storeid)
                    ->update([
                        'Output' => $request->rpoutput,
                        'uom' => $request->rpuom,
                        'totalCost' => 0,
                        'singleCost' => 0,
                        'status' => 'active',
                    ]);

                $id = $existing->id;
            }

            return response()->json([
                'success' => true,
                'message' => 'Recipe master saved/updated successfully.',
                'recipe_id' => $id
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to store/update recipe master.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateRecipe(Request $request, $id)
    {
        $storeid = $request->session()->get('store_id');

        try {
            $request->validate([
                'product_id' => 'required|exists:product_master,id',
                'rpoutput' => 'required|string',
                'rpuom' => 'required|string'
            ]);

            $exists = DB::table('recipe_master')
                ->where('id', $id)
                ->where('store_id', $storeid)
                ->first();

            if (!$exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Recipe not found.',
                ], 404);
            }

            DB::table('recipe_master')
                ->where('id', $id)
                ->where('store_id', $storeid)
                ->update([
                    'product_id' => $request->product_id,
                    'Output' => $request->rpoutput,
                    'uom' => $request->rpuom,
                    'status' => 'active',
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Recipe updated successfully.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update recipe.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function saveRawMaterials(Request $request)
    {
        // dd($request->all());
        $storeid = $request->session()->get('store_id');
        $request->validate([
            'raw_material_id' => 'required|exists:raw_materials,id',
        ]);

        // Store the data in the database
        RmForRecipe::create([
            'raw_material_id' => $request->raw_material_id,
            'product_id' => 1,
            'store_id' => $storeid
        ]);

        $rmRecipe = DB::table('rm_for_recipe')->where('store_id',$storeid)->get();

    return redirect()->back()->with([
        'success' => 'Data saved successfully!',
        'rmRecipe' => $rmRecipe,
    ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, $id)
    {
        $storeid = $request->session()->get('store_id');
        try {
            // Validate the request
            $request->validate([
                'quantity' => 'required|numeric',
                'amount' => 'required|numeric',
            ]);
            // dd($request);
              // Perform the update
                $updated = DB::table('rm_for_recipe')
                ->where('id', $request->id)
                ->where('store_id',$storeid)
                ->update(['quantity' => $request->quantity,
                        'amount' => $request->amount,
                        'updated_at' => Carbon::now(),]);

            if ($updated) {
                return response()->json(['success' => 'Quantity updated successfully.']);
            } else {
                return response()->json(['error' => 'Update failed.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $storeid = $request->session()->get('store_id');
        try {
            // Find the record by ID
            $rmForRecipe = RmForRecipe::where('store_id', $storeid)
                            ->where('id', $id)
                            ->firstOrFail();  //findOrFail($id);

            // Delete the record
            $rmForRecipe->delete();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Record deleted successfully.',
            ]);
        } catch (\Exception $e) {
            // Handle the error gracefully
            \Log::error('Error deleting record: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'There was an issue deleting the record.',
                'error' => $e->getMessage()
            ], 500); // Internal Server Error
        }
    }
}
