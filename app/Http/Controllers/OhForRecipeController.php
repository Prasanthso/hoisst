<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\CategoryItems;
use App\Models\OhForRecipe;
use App\Models\MohForRecipe;
use App\Models\UniqueCode;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OhForRecipeController extends Controller
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
                'overheads_id' => 'required|exists:overheads,id',
                'product_id' => 'required|exists:product_master,id',
                'quantity' => 'required|numeric',
                'amount' => 'required|numeric',
                'code' => 'required|string',
            ]);

            // Create the record
            $ohForRecipe = OhForRecipe::create([
                'overheads_id' => $request->overheads_id,
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
                'message' => 'overheads updated successfully.',
                'data' => $ohForRecipe,
                'inserted_id' => $ohForRecipe->id,
            ]);
        } catch (\Exception $e) {
            // Handle the error gracefully
            \Log::error('Error storing overheads: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'There was an issue updating the overheads.',
                'error' => $e->getMessage()
            ], 500); // Internal Server Error
        }
    }

    public function storeManualOverhead(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        // Validate the incoming data
        $validatedData = $request->validate([
            'product_id' => 'required|exists:product_master,id',
            'manualOverheads' => 'required|string|max:255',
            'manualOverheadsType' => 'required|string|in:price,percentage',
            'manualOhPrice' => 'nullable|numeric|min:0',
            'manualOhPerc' => 'nullable|numeric|min:0',
        ]);

        // Create the new record in the database
        $mohForRecipe = MohForRecipe::create([
            'product_id' => $validatedData['product_id'],
            'name' => $validatedData['manualOverheads'],
            'oh_type' => $validatedData['manualOverheadsType'],
            'price' => $validatedData['manualOhPrice'] , //$validatedData['manualOverheadsType'] === 'price' ? $validatedData['manualOhPrice'] : 0,
            'percentage' => $validatedData['manualOhPerc'] , //$validatedData['manualOverheadsType'] === 'percentage' ? $validatedData['manualOhPerc'] : 0,
            'store_id' => $storeid,
        ]);

        // Return the success response with the inserted record ID
        return response()->json([
            'success' => true,
            'inserted_id' => $mohForRecipe->id, // Send back the inserted record ID
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
        $storeid = $request->session()->get('store_id');
        try {
            // Validate the request
            $request->validate([
                'quantity' => 'required|numeric',
                'amount' => 'required|numeric',
            ]);
            // dd($request);
            // Perform the update
            $updated = DB::table('oh_for_recipe')
                ->where('id', $request->id)
                ->where('store_id',$storeid)
                ->update([
                    'quantity' => $request->quantity,
                    'amount' => $request->amount,
                    'updated_at' => Carbon::now(),
                ]);

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
            $ohForRecipe = OhForRecipe::where('store_id', $storeid)
                        ->where('id', $id)
                        ->firstOrFail();   //findOrFail($id);

            // Delete the record
            $ohForRecipe->delete();

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

    /**
     * Remove the specified resource from storage.
     */
    public function mohDestroy(Request $request, $id)
    {
        $storeid = $request->session()->get('store_id');
        try {
            // Find the record by ID
            $mohForRecipe = MohForRecipe::where('store_id', $storeid)
            ->where('id', $id)
            ->firstOrFail();  //find($id);

            // Delete the record
            $mohForRecipe->delete();

            return response()->json([
                'success' => true,
                'message' => 'Record deleted successfully.',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting record: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'There was an issue deleting the record.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
