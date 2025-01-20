<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\CategoryItems;
use App\Models\RmForRecipe;
use App\Models\UniqueCode;
use Illuminate\Http\Request;

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
    public function store(Request $request)
    {
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

            $rpCode = UniqueCode::generateRpCode();

           $rp = RecipeMaster::create([
                'product_id' => $request->product_id,
                'rpcode' => $rpCode,
                'rpoutput' => $request->rpoutput,
                'rpuom' => $request->rpuom,
            ]);

            // Create the record
            $rmForRecipe = RmForRecipe::create([
                'raw_material_id' => $request->raw_material_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'code' => $request->code,
                'uom' => $request->uom ?? 'default_uom',
                'price' => $request->price ?? 0,
                'amount' => $request->amount,
            ]);

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Raw Material updated successfully.',
                'data' => $rmForRecipe,
                'rmInserted_id' => $rmForRecipe->id,
            ]);
        } catch (\Exception $e) {
            // Handle the error gracefully
            \Log::error('Error storing raw material: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'There was an issue updating the raw material.',
                'error' => $e->getMessage()
            ], 500); // Internal Server Error
        }
    }

    public function saveRawMaterials(Request $request)
    {
         dd($request->all());

        $request->validate([
            'raw_material_id' => 'required|exists:raw_materials,id',
        ]);

        // Store the data in the database
        RmForRecipe::create([
            'raw_material_id' => $request->raw_material_id,
            'product_id' => 1,
        ]);

        $rmRecipe = DB::table('rm_for_recipe')->get();

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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Find the record by ID
            $rmForRecipe = RmForRecipe::findOrFail($id);

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
