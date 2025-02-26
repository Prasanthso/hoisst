<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\CategoryItems;
use App\Models\RawMaterial;
use App\Models\UniqueCode;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RawMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Fetch all category items
        $categoryitems = CategoryItems::rmCategoryItem();
        $selectedCategoryIds = $request->input('category_ids', []);

        if ($request->ajax()) {
            $selectedCategoryIds = explode(',', $selectedCategoryIds);
            // If no categories are selected, return all raw materials with status 'active'
            if (empty($selectedCategoryIds)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No category IDs provided',
                    'rawMaterials' => []
                ]);
            } else {
                // Fetch raw materials filtered by the selected category IDs and status 'active'
                $rawMaterials = DB::table('raw_materials as rm')
                ->leftJoin('categoryitems as c1', 'rm.category_id1', '=', 'c1.id')
                ->leftJoin('categoryitems as c2', 'rm.category_id2', '=', 'c2.id')
                ->leftJoin('categoryitems as c3', 'rm.category_id3', '=', 'c3.id')
                ->leftJoin('categoryitems as c4', 'rm.category_id4', '=', 'c4.id')
                ->leftJoin('categoryitems as c5', 'rm.category_id5', '=', 'c5.id')
                ->leftJoin('categoryitems as c6', 'rm.category_id6', '=', 'c6.id')
                ->leftJoin('categoryitems as c7', 'rm.category_id7', '=', 'c7.id')
                ->leftJoin('categoryitems as c8', 'rm.category_id8', '=', 'c8.id')
                ->leftJoin('categoryitems as c9', 'rm.category_id9', '=', 'c9.id')
                ->leftJoin('categoryitems as c10', 'rm.category_id10', '=', 'c10.id')
                ->select(
                    'rm.id',
                    'rm.name',
                    'rm.rmcode',
                    'rm.price',
                    'rm.uom',
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
                    ->where('rm.status', '=', 'active') // Filter by active status
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
                    ->where('rm.status', '=', 'active')
                    ->orderBy('rm.name', 'asc') // Filter by active status
                    ->get();
                    // ->paginate(10);
            }
            // Return filtered raw materials as JSON response
            return response()->json([
                'status' => 'success',
                'message' => count($rawMaterials) > 0 ? 'rawMaterials found' : 'No rawMaterials found',
                'rawMaterials' => $rawMaterials
            ]);
        }

        // Default view, return all raw materials with status 'active' and category items
        $rawMaterials = DB::table('raw_materials as rm')
        ->leftJoin('categoryitems as c1', 'rm.category_id1', '=', 'c1.id')
        ->leftJoin('categoryitems as c2', 'rm.category_id2', '=', 'c2.id')
        ->leftJoin('categoryitems as c3', 'rm.category_id3', '=', 'c3.id')
        ->leftJoin('categoryitems as c4', 'rm.category_id4', '=', 'c4.id')
        ->leftJoin('categoryitems as c5', 'rm.category_id5', '=', 'c5.id')
        ->leftJoin('categoryitems as c6', 'rm.category_id6', '=', 'c6.id')
        ->leftJoin('categoryitems as c7', 'rm.category_id7', '=', 'c7.id')
        ->leftJoin('categoryitems as c8', 'rm.category_id8', '=', 'c8.id')
        ->leftJoin('categoryitems as c9', 'rm.category_id9', '=', 'c9.id')
        ->leftJoin('categoryitems as c10', 'rm.category_id10', '=', 'c10.id')
        ->select(
            'rm.id',
            'rm.name',
            'rm.rmcode',
            'rm.price',
            'rm.uom',
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
            ->where('rm.status', '=', 'active')
            ->orderBy('rm.name', 'asc') // Filter by active status
            ->paginate(10);

        return view('rawMaterial.rawMaterial', compact('rawMaterials', 'categoryitems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rawMaterialCategories = CategoryItems::rmCategoryItem();
        return view('rawMaterial.addRawMaterial', compact('rawMaterialCategories')); // Match view name
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
        $request->validate([
            'name' => 'required|string|max:255|unique:raw_materials,name',
            'uom' => 'required|string|in:Ltr,Kgm,Gm,Nos',
            'category_ids' => 'required|array',
            'category_ids.*' => 'integer|exists:categoryitems,id',
            'price' => 'required|string',
            'update_frequency' => 'required|string|in:Days,Weeks,Monthly,Yearly',
            'price_update_frequency' => 'required|string',
            'price_threshold' => 'required|string',
            'hsncode' => 'required|string|unique:raw_materials,hsncode',
            'itemweight' => 'required|string',
            'itemtype' => 'required|string',
            'tax' => 'required|string',
        ]);

        $categoryIds = $request->category_ids;

        $rmCode = UniqueCode::generateRmCode();

        try {
            RawMaterial::create([
                'name' => $request->name,
                'rmcode' => $rmCode,
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
                'itemweight' => $request->itemweight,
                'itemtype' => $request->itemtype,
                'tax' => $request->tax,
            ]);
        } catch (\Exception $e) {
            // \Log::error('Error inserting data: ' . $e->getMessage());
            dd($e->getMessage());
        }
        return redirect()->route('rawMaterials.index')->with('success', 'Raw Material created successfully.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong! Could not save data.')
                ->withInput();
        }
    }


    public function updatePrices(Request $request)
    {
        $validatedData = $request->validate([
            'updatedMaterials' => 'required|array',
            'updatedMaterials.*.id' => 'required|exists:raw_materials,id',
            'updatedMaterials.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($validatedData) {
                foreach ($validatedData['updatedMaterials'] as $material) {
                    // Fetch the current material
                    $currentMaterial = RawMaterial::find($material['id']);

                    // Check if the price has changed
                    if ($currentMaterial->price != $material['price']) {
                        // Log the price update in the rm_price_histories table
                        DB::table('rm_price_histories')->insert([
                            'raw_material_id' => $currentMaterial->id,
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
    public function getRmPriceHistory($id)
    {
        $priceHistory = DB::table('rm_price_histories')
        ->where('raw_material_id', $id)
        ->orderBy('updated_at', 'desc') // Replace 'id' with the column you want to sort by
        ->get();
        return response()->json(['priceDetails' => $priceHistory]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Fetch all categories
        $rawMaterialCategories = CategoryItems::rmCategoryItem();

        // Fetch the specific raw material by its ID
        $rawMaterial = DB::table('raw_materials')->where('id', $id)->first(); // Fetch the single raw material entry

        // Return the view with raw material data and categories
        return view('rawMaterial.editRawMaterial', compact('rawMaterial', 'rawMaterialCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the existing raw material by ID
        $rawMaterial = RawMaterial::findOrFail($id);

    try{
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'uom' => 'required|string|in:Ltr,Kgm,Gm,Nos',
            'category_ids' => 'required|array',
            'category_ids.*' => 'integer|exists:categoryitems,id',
            'price' => 'required|string',
            'update_frequency' => 'required|string|in:Days,Weeks,Monthly,Yearly',
            'price_update_frequency' => 'required|string',
            'price_threshold' => 'required|string',
            'hsncode' => 'required|string',
            'itemweight' => 'required|string',
            'itemtype' => 'required|string',
            'tax' => 'required|string',
        ]);

        $categoryIds = $request->category_ids;

        try {
            // for duplicate
            $existingMaterial = RawMaterial::where(function ($query) use ($request) {
                $query->whereRaw('LOWER(name) = LOWER(?)', [$request->name])
                ->orWhereRaw('LOWER(hsncode) = LOWER(?)', [$request->hsncode]);
            })
            ->where('id', '!=', $rawMaterial->id) // Exclude current record from check
            ->first();

            if ($existingMaterial) {
                if (strtolower($existingMaterial->name) == strtolower($request->name) &&
                    strtolower($existingMaterial->hsncode) == strtolower($request->hsncode)) {
                    return redirect()->back()->with('error', 'Both Material Name and HSN Code already exist.');
                } elseif (strtolower($existingMaterial->name) == strtolower($request->name)) {
                    return redirect()->back()->with('error', 'Material Name already exists.');
                } elseif (strtolower($existingMaterial->hsncode) == strtolower($request->hsncode)) {
                    return redirect()->back()->with('error', 'HSN Code already exists.');
                }
            }

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
                'itemweight' => $request->itemweight,
                'itemtype' => $request->itemtype,
                'tax' => $request->tax,
            ]);
        } catch (\Exception $e) {
            // Handle the error gracefully (e.g., log it and show an error message)
            // \Log::error('Error updating raw material: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an issue updating the raw material.');
        }
        // Return a success message and redirect back
        return redirect()->route('rawMaterials.index')->with('success', 'Raw Material updated successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong! Could not update data.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteConfirmation(Request $request)
    {
        $ids = $request->input('ids'); // Get the 'ids' array from the request

        if (!$ids || !is_array($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid IDs provided.',
            ]);
        }

        try {
            // Update only if the raw materials are NOT referenced in rm_for_recipe
            $updatedCount = RawMaterial::whereIn('id', $ids)
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('rm_for_recipe')
                        ->whereColumn('rm_for_recipe.raw_material_id', 'raw_materials.id'); // Ensure correct column name
                })
                ->update(['status' => 'inactive']);

                return response()->json([
                    'success' => true,
                    'message' => $updatedCount > 0 ? 'Raw materials marked as inactive successfully.' : 'No raw materials were updated.',
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating raw materials: ' . $e->getMessage(),
            ]);
        }
    }

    public function delete(Request $request)
    {
        $ids = $request->input('ids'); // Get the 'ids' array from the request

        if (!$ids || !is_array($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid IDs provided.',
            ]);
        }

        try {
            // Update only if the raw materials are NOT referenced in rm_for_recipe
            $itemsToDelete = RawMaterial::whereIn('id', $ids)
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('rm_for_recipe')
                        ->whereColumn('rm_for_recipe.raw_material_id', 'raw_materials.id'); // Ensure correct column name
                })
                ->get();

            if ($itemsToDelete->isNotEmpty()) {
                return response()->json([
                    'success' => true,
                    'confirm' => true,
                    'message' => "Are you want to delete this item of raw material. Do you want to proceed?.",
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No raw materials item were deleted. They might be in use.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating raw materials: ' . $e->getMessage(),
            ]);
        }
    }

}
