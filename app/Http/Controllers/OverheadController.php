<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\CategoryItems;
use App\Models\Overhead;
use App\Models\UniqueCode;
use Illuminate\Http\Request;

class OverheadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Fetch all category items
        $categoryitems = CategoryItems::ohCategoryItem();
        $selectedCategoryIds = $request->input('category_ids', []);

        if ($request->ajax()) {
            $selectedCategoryIds = explode(',', $selectedCategoryIds);
            $selectedCategoryIds = array_filter($selectedCategoryIds, fn($id) => is_numeric($id) && $id > 0);

            if (empty($selectedCategoryIds)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No category IDs provided',
                    'overheads' => []
                ]);
            }
                // Fetch packing materials filtered by the selected category IDs
                $overheads = DB::table('overheads as oh')
                ->leftJoin('categoryitems as c1', 'oh.category_id1', '=', 'c1.id')
                ->leftJoin('categoryitems as c2', 'oh.category_id2', '=', 'c2.id')
                ->leftJoin('categoryitems as c3', 'oh.category_id3', '=', 'c3.id')
                ->leftJoin('categoryitems as c4', 'oh.category_id4', '=', 'c4.id')
                ->leftJoin('categoryitems as c5', 'oh.category_id5', '=', 'c5.id')
                ->leftJoin('categoryitems as c6', 'oh.category_id6', '=', 'c6.id')
                ->leftJoin('categoryitems as c7', 'oh.category_id7', '=', 'c7.id')
                ->leftJoin('categoryitems as c8', 'oh.category_id8', '=', 'c8.id')
                ->leftJoin('categoryitems as c9', 'oh.category_id9', '=', 'c9.id')
                ->leftJoin('categoryitems as c10', 'oh.category_id10', '=', 'c10.id')
                ->select(
                    'oh.id',
                    'oh.name',
                    'oh.ohcode',
                    'oh.price',
                    'oh.uom',
                    'c1.itemname as category_name1',
                    'c2.itemname as category_name2',
                    'c3.itemname as category_name3',
                    'c4.itemname as category_name4',
                    'c5.itemname as category_name5',
                    'c6.itemname as category_name6',
                    'c7.itemname as category_name7',
                    'c8.itemname as category_name8',
                    'c9.itemname as category_name9',
                    'c10.itemname as category_name10'
                )
                    ->where(function ($query) use ($selectedCategoryIds) {
                        $query->whereIn('c1.id', $selectedCategoryIds)
                            ->orWhereIn('c2.id', $selectedCategoryIds)
                            ->orWhereIn('c3.id', $selectedCategoryIds)
                            ->orWhereIn('c4.id', $selectedCategoryIds)
                            ->orWhereIn('c5.id', $selectedCategoryIds)
                            ->orWhereIn('c6.id', $selectedCategoryIds)
                            ->orWhereIn('c7.id', $selectedCategoryIds)
                            ->orWhereIn('c8.id', $selectedCategoryIds)
                            ->orWhereIn('c9.id', $selectedCategoryIds)
                            ->orWhereIn('c10.id', $selectedCategoryIds);
                    })
                    ->where('oh.status', '=', 'active') // Filter by active status
                    ->get();
            // Return filtered packing materials as JSON response
            return response()->json([
                'status' => 'success',
                'message' => count($overheads) > 0 ? 'Overheads found' : 'No Overheads found',
                'overheads' => $overheads
            ]);
        }

        // Default view, return all packing materials and category items
        $overheads = DB::table('overheads as oh')
        ->leftJoin('categoryitems as c1', 'oh.category_id1', '=', 'c1.id')
        ->leftJoin('categoryitems as c2', 'oh.category_id2', '=', 'c2.id')
        ->leftJoin('categoryitems as c3', 'oh.category_id3', '=', 'c3.id')
        ->leftJoin('categoryitems as c4', 'oh.category_id4', '=', 'c4.id')
        ->leftJoin('categoryitems as c5', 'oh.category_id5', '=', 'c5.id')
        ->leftJoin('categoryitems as c6', 'oh.category_id6', '=', 'c6.id')
        ->leftJoin('categoryitems as c7', 'oh.category_id7', '=', 'c7.id')
        ->leftJoin('categoryitems as c8', 'oh.category_id8', '=', 'c8.id')
        ->leftJoin('categoryitems as c9', 'oh.category_id9', '=', 'c9.id')
        ->leftJoin('categoryitems as c10', 'oh.category_id10', '=', 'c10.id')
        ->select(
            'oh.id',
            'oh.name',
            'oh.ohcode',
            'oh.price',
            'oh.uom',
            'c1.itemname as category_name1',
            'c2.itemname as category_name2',
            'c3.itemname as category_name3',
            'c4.itemname as category_name4',
            'c5.itemname as category_name5',
            'c6.itemname as category_name6',
            'c7.itemname as category_name7',
            'c8.itemname as category_name8',
            'c9.itemname as category_name9',
            'c10.itemname as category_name10'
        )
        ->where('oh.status', '=', 'active') // Filter by active status
        ->paginate(10);

        return view('overheads.overheads', compact('overheads', 'categoryitems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $overheadsCategories = CategoryItems::ohCategoryItem();
        return view('overheads.addOverheads', compact('overheadsCategories')); // Match view name
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'uom' => 'required|string|in:Ltr,Kgs,Nos',
            'category_ids' => 'required|array',
            'category_ids.*' => 'integer|exists:categoryitems,id',
            'price' => 'required|string',
            'update_frequency' => 'required|string|in:Days,Weeks,Monthly,Yearly',
            'price_update_frequency' => 'required|string',
            'price_threshold' => 'required|string',
            'hsncode' => 'required|string',
            'itemtype' => 'required|string',
            'tax' => 'required|string',
        ]);

        $categoryIds = $request->category_ids;

        $ohCode = UniqueCode::generateOhCode();

        try {
            Overhead::create([
                'name' => $request->name,
                'ohcode' => $ohCode,
                'uom' => $request->uom,
                'category_id1' => $categoryIds[0] ?? null,
                'category_id2' => $categoryIds[1] ?? null,
                'category_id3' => $categoryIds[2] ?? null,
                'category_id4' => $categoryIds[3] ?? null,
                'category_id5' => $categoryIds[4] ?? null,
                'category_id6' => $categoryIds[5] ?? null,
                'category_id7' => $categoryIds[6] ?? null,
                'category_id8' => $categoryIds[7] ?? null,
                'category_id9' => $categoryIds[8] ?? null,
                'category_id10' => $categoryIds[9] ?? null,
                'price' => $request->price,
                'update_frequency' => $request->update_frequency,
                'price_update_frequency' => $request->price_update_frequency,
                'price_threshold' => $request->price_threshold,
                'hsncode' => $request->hsncode,
                'itemtype' => $request->itemtype,
                'tax' => $request->tax,
            ]);
        } catch (\Exception $e) {
            // \Log::error('Error inserting data: ' . $e->getMessage());
            dd($e->getMessage());
        }


        return redirect()->route('overheads.index')->with('success', 'Overheads created successfully.');
    }

    public function updatePrices(Request $request)
    {
        $validatedData = $request->validate([
            'updatedMaterials' => 'required|array',
            'updatedMaterials.*.id' => 'required|exists:overheads,id',
            'updatedMaterials.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($validatedData) {
                foreach ($validatedData['updatedMaterials'] as $material) {
                    // Fetch the current material
                    $currentMaterial = Overhead::find($material['id']);

                    // Check if the price has changed
                    if ($currentMaterial->price != $material['price']) {
                        // Log the price update in the rm_price_histories table
                        DB::table('oh_price_histories')->insert([
                            'overheads_id' => $currentMaterial->id,
                            'old_price' => $currentMaterial->price,
                            'new_price' => $material['price'],
                            'updated_by' => 1, // Ensure user is authenticated
                            'updated_at' => now(),
                        ]);

                        // Update the raw material price
                        $currentMaterial->update(['price' => $material['price']]);
                    }
                }
            });

            return response()->json(['success' => true, 'message' => 'Prices updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating prices.'], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function getOhPriceHistory($id)
    {
        $priceHistory = DB::table('oh_price_histories')
        ->where('overheads_id', $id)
            ->orderBy('updated_at', 'desc') // Replace 'id' with the column you want to sort by
            ->get();
        return response()->json(['priceDetails' => $priceHistory]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // / Fetch all categories
        $overheadsCategories = CategoryItems::ohCategoryItem();

        // Fetch the specific raw material by its ID
        $overheads = DB::table('overheads')->where('id', $id)->first(); // Fetch the single raw material entry

        // Return the view with raw material data and categories
        return view('overheads.editOverheads', compact('overheads', 'overheadsCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the existing raw material by ID
        $rawMaterial = Overhead::findOrFail($id);

        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'uom' => 'required|string|in:Ltr,Kgs,Nos',
            'category_ids' => 'required|array',
            'category_ids.*' => 'integer|exists:categoryitems,id',
            'price' => 'required|string',
            'update_frequency' => 'required|string|in:Days,Weeks,Monthly,Yearly',
            'price_update_frequency' => 'required|string',
            'price_threshold' => 'required|string',
            'hsncode' => 'required|string',
            'itemtype' => 'required|string',
            'tax' => 'required|string',
        ]);

        $categoryIds = $request->category_ids;

        try {
            // Update the raw material record
            $rawMaterial->update([
                'name' => $request->name,
                'uom' => $request->uom,
                'category_id1' => $categoryIds[0] ?? null,
                'category_id2' => $categoryIds[1] ?? null,
                'category_id3' => $categoryIds[2] ?? null,
                'category_id4' => $categoryIds[3] ?? null,
                'category_id5' => $categoryIds[4] ?? null,
                'category_id6' => $categoryIds[5] ?? null,
                'category_id7' => $categoryIds[6] ?? null,
                'category_id8' => $categoryIds[7] ?? null,
                'category_id9' => $categoryIds[8] ?? null,
                'category_id10' => $categoryIds[9] ?? null,
                'price' => $request->price,
                'update_frequency' => $request->update_frequency,
                'price_update_frequency' => $request->price_update_frequency,
                'price_threshold' => $request->price_threshold,
                'hsncode' => $request->hsncode,
                'itemtype' => $request->itemtype,
                'tax' => $request->tax,
            ]);
        } catch (\Exception $e) {
            // Handle the error gracefully (e.g., log it and show an error message)
            // \Log::error('Error updating raw material: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an issue updating the overhead.');
        }

        // Return a success message and redirect back
        return redirect()->route('overheads.index')->with('success', 'Overheads updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        $ids = $request->input('ids'); // Get the 'ids' array from the request

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No valid IDs provided.']);
        }

        try {
            // Update the status of raw materials to 'inactive'
            Overhead::whereIn('id', $ids)->update(['status' => 'inactive']);

            return response()->json(['success' => true, 'message' => 'Raw materials marked as inactive successfully.']);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['success' => false, 'message' => 'Error updating raw materials: ' . $e->getMessage()]);
        }
    }
}
