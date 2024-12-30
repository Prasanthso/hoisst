<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\CategoryItems;
use App\Models\PackingMaterial;
use App\Models\UniqueCode;
use Illuminate\Http\Request;

class PackingMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Fetch all category items
        $categoryitems = CategoryItems::pmCategoryItem();

        // If it's an AJAX request for filtered packing materials
        if ($request->ajax()) {
            // Get selected category IDs from the request
            $selectedCategoryIds = $request->input('category_ids', []);
            $selectedCategoryIds = explode(',', $selectedCategoryIds);
            // If no categories are selected, return all packing materials
            if (empty($selectedCategoryIds)) {
                $packingMaterials = DB::table('packing_materials as pm')
                    ->leftJoin('categoryitems as c1', 'pm.category_id1', '=', 'c1.id')
                    ->leftJoin('categoryitems as c2', 'pm.category_id2', '=', 'c2.id')
                    ->leftJoin('categoryitems as c3', 'pm.category_id3', '=', 'c3.id')
                    ->leftJoin('categoryitems as c4', 'pm.category_id4', '=', 'c4.id')
                    ->leftJoin('categoryitems as c5', 'pm.category_id5', '=', 'c5.id')
                    ->select(
                        'pm.id',
                        'pm.name',
                        'pm.pmcode',
                        'pm.price',
                        'pm.uom',
                        'c1.itemname as category_name1',
                        'c2.itemname as category_name2',
                        'c3.itemname as category_name3',
                        'c4.itemname as category_name4',
                        'c5.itemname as category_name5'
                    )
                    ->get();
            } else {
                // Fetch packing materials filtered by the selected category IDs
                $packingMaterials = DB::table('packing_materials as pm')
                    ->leftJoin('categoryitems as c1', 'pm.category_id1', '=', 'c1.id')
                    ->leftJoin('categoryitems as c2', 'pm.category_id2', '=', 'c2.id')
                    ->leftJoin('categoryitems as c3', 'pm.category_id3', '=', 'c3.id')
                    ->leftJoin('categoryitems as c4', 'pm.category_id4', '=', 'c4.id')
                    ->leftJoin('categoryitems as c5', 'pm.category_id5', '=', 'c5.id')
                    ->select(
                        'pm.id',
                        'pm.name',
                        'pm.pmcode',
                        'pm.price',
                        'pm.uom',
                        'c1.itemname as category_name1',
                        'c2.itemname as category_name2',
                        'c3.itemname as category_name3',
                        'c4.itemname as category_name4',
                        'c5.itemname as category_name5'
                    )
                    ->where(function ($query) use ($selectedCategoryIds) {
                        $query->whereIn('c1.id', $selectedCategoryIds)
                            ->orWhereIn('c2.id', $selectedCategoryIds)
                            ->orWhereIn('c3.id', $selectedCategoryIds)
                            ->orWhereIn('c4.id', $selectedCategoryIds)
                            ->orWhereIn('c5.id', $selectedCategoryIds);
                    })
                    ->get();
            }

            // Return filtered packing materials as JSON response
            return response()->json([
                'packingMaterials' => $packingMaterials
            ]);
        }

        // Default view, return all packing materials and category items
        $packingMaterials = DB::table('packing_materials as pm')
            ->leftJoin('categoryitems as c1', 'pm.category_id1', '=', 'c1.id')
            ->leftJoin('categoryitems as c2', 'pm.category_id2', '=', 'c2.id')
            ->leftJoin('categoryitems as c3', 'pm.category_id3', '=', 'c3.id')
            ->leftJoin('categoryitems as c4', 'pm.category_id4', '=', 'c4.id')
            ->leftJoin('categoryitems as c5', 'pm.category_id5', '=', 'c5.id')
            ->select(
                'pm.id',
                'pm.name',
                'pm.pmcode',
                'pm.price',
                'pm.uom',
                'c1.itemname as category_name1',
                'c2.itemname as category_name2',
                'c3.itemname as category_name3',
                'c4.itemname as category_name4',
                'c5.itemname as category_name5'
            )
            ->get();

        // Default view, return all packing materials and category items
        $packingMaterials = DB::table('packing_materials as pm')
        ->leftJoin('categoryitems as c1', 'pm.category_id1', '=', 'c1.id')
        ->leftJoin('categoryitems as c2', 'pm.category_id2', '=', 'c2.id')
        ->leftJoin('categoryitems as c3', 'pm.category_id3', '=', 'c3.id')
        ->leftJoin('categoryitems as c4', 'pm.category_id4', '=', 'c4.id')
        ->leftJoin('categoryitems as c5', 'pm.category_id5', '=', 'c5.id')
        ->select(
            'pm.id',
            'pm.name',
            'pm.pmcode',
            'pm.price',
            'pm.uom',
            'c1.itemname as category_name1',
            'c2.itemname as category_name2',
            'c3.itemname as category_name3',
            'c4.itemname as category_name4',
            'c5.itemname as category_name5'
        )
        ->paginate(10);

        return view('packingMaterial.packingMaterial', compact('packingMaterials', 'categoryitems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $packingMaterialCategories = CategoryItems::pmCategoryItem();
        return view('packingMaterial.addPackingMaterial', compact('packingMaterialCategories'));
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
            'price_update_frequency' => 'required|string',
            'price_threshold' => 'required|string'
        ]);

        $categoryIds = $request->category_ids;

        $pmCode = UniqueCode::generatePmCode();

        try {
            PackingMaterial::create([
                'name' => $request->name,
                'pmcode' => $pmCode,
                'uom' => $request->uom,
                'category_id1' => $categoryIds[0] ?? null,
                'category_id2' => $categoryIds[1] ?? null,
                'category_id3' => $categoryIds[2] ?? null,
                'category_id4' => $categoryIds[3] ?? null,
                'category_id5' => $categoryIds[4] ?? null,
                'price' => $request->price,
                'price_update_frequency' => $request->price_update_frequency,
                'price_threshold' => $request->price_threshold,
            ]);
        } catch (\Exception $e) {
            // \Log::error('Error inserting data: ' . $e->getMessage());
            dd($e->getMessage());
        }


        return redirect()->route('packingMaterials.index')->with('success', 'Packing Material created successfully.');
    }

    public function updatePrices(Request $request)
    {
        $validatedData = $request->validate([
            'updatedMaterials' => 'required|array',
            'updatedMaterials.*.id' => 'required|exists:packing_materials,id',
            'updatedMaterials.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($validatedData) {
                foreach ($validatedData['updatedMaterials'] as $material) {
                    // Fetch the current material
                    $currentMaterial = PackingMaterial::find($material['id']);

                    // Check if the price has changed
                    if ($currentMaterial->price != $material['price']) {
                        // Log the price update in the rm_price_histories table
                        DB::table('pm_price_histories')->insert([
                            'packing_material_id' => $currentMaterial->id,
                            'old_price' => $currentMaterial->price,
                            'new_price' => $material['price'],
                            'updated_by' => 1, // Ensure user is authenticated
                            'updated_at' => now(),
                        ]);

                        // Update the packing material price
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
    public function getPmPriceHistory($id)
    {
        $priceHistory = DB::table('pm_price_histories')
        ->where('packing_material_id', $id)
            ->orderBy('updated_at', 'desc') // Replace 'id' with the column you want to sort by
            ->get();
        return response()->json(['priceDetails' => $priceHistory]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $packingMaterialCategories = CategoryItems::pmCategoryItem();

        // Fetch the specific packing material by its ID
        $packingMaterial = DB::table('packing_materials')->where('id', $id)->first(); // Fetch the single packing material entry

        // Return the view with packing material data and categories
        return view('packingMaterial.editPackingMaterial', compact('packingMaterial', 'packingMaterialCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the existing packing material by ID
        $packingMaterial = PackingMaterial::findOrFail($id);

        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'uom' => 'required|string|in:Ltr,Kgs,Nos',
            'category_ids' => 'required|array',
            'category_ids.*' => 'integer|exists:categoryitems,id',
            'price' => 'required|string',
            'price_update_frequency' => 'required|string',
            'price_threshold' => 'required|string'
        ]);

        $categoryIds = $request->category_ids;

        try {
            // Update the packing material record
            $packingMaterial->update([
                'name' => $request->name,
                'uom' => $request->uom,
                'category_id1' => $categoryIds[0] ?? null,
                'category_id2' => $categoryIds[1] ?? null,
                'category_id3' => $categoryIds[2] ?? null,
                'category_id4' => $categoryIds[3] ?? null,
                'category_id5' => $categoryIds[4] ?? null,
                'price' => $request->price,
                'price_update_frequency' => $request->price_update_frequency,
                'price_threshold' => $request->price_threshold,
            ]);
        } catch (\Exception $e) {
            // Handle the error gracefully (e.g., log it and show an error message)
            // \Log::error('Error updating packing material: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an issue updating the packing material.');
        }

        // Return a success message and redirect back
        return redirect()->route('packingMaterials.index')->with('success', 'packing Material updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
