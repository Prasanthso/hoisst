<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\CategoryItems;
use App\Models\PmForRecipe;
use App\Models\UniqueCode;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PmForRecipeController extends Controller
{
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
        $storeid = $request->session()->get('store_id');
        // dd($request->all());
        try {
            // Validate the request
            $request->validate([
                'packing_material_id' => 'required|exists:packing_materials,id',
                'product_id' => 'required|exists:product_master,id',
                'quantity' => 'required|numeric',
                'amount' => 'required|numeric',
                'code' => 'required|string',
            ]);

            // Create the record
            $pmForRecipe = PmForRecipe::create([
                'packing_material_id' => $request->packing_material_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'code' => $request->code,
                'uom' => $request->uom ?? 'default_uom',
                'price' => $request->price ?? 0,
                'amount' => $request->amount,
                'store_id' => $storeid
            ]);

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Raw Material updated successfully.',
                'data' => $pmForRecipe,
                'pmInserted_id' => $pmForRecipe->id,
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
        $storeid = $request->session()->get('store_id');
        try {
            // Validate the request
            $request->validate([
                'quantity' => 'required|numeric',
                'amount' => 'required|numeric',
            ]);
            // dd($request);
              // Perform the update
                $updated = DB::table('pm_for_recipe')
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
            $pmForRecipe = PmForRecipe::where('store_id', $storeid)
                        ->where('id', $id)
                        ->firstOrFail();  //findOrFail($id);

            // Delete the record
            $pmForRecipe->delete();

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
