<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\CategoryItems;
use App\Models\RawMaterial;
use App\Models\UniqueCode;
use Illuminate\Http\Request;


class RawMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Fetch all category items
        $categoryitems = DB::table('categoryitems')->get();

        // If it's an AJAX request for filtered raw materials
        if ($request->ajax()) {
            // Get selected category IDs from the request
            $selectedCategoryIds = $request->input('category_ids', []);
                dd($selectedCategoryIds);
            // If no categories are selected, return all raw materials
            if (empty($selectedCategoryIds)) {
                $rawMaterials = DB::table('raw_materials as rm')
                    ->leftJoin('categoryitems as c1', 'rm.category_id1', '=', 'c1.id')
                    ->leftJoin('categoryitems as c2', 'rm.category_id2', '=', 'c2.id')
                    ->leftJoin('categoryitems as c3', 'rm.category_id3', '=', 'c3.id')
                    ->leftJoin('categoryitems as c4', 'rm.category_id4', '=', 'c4.id')
                    ->leftJoin('categoryitems as c5', 'rm.category_id5', '=', 'c5.id')
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
                        'c5.itemname as category_name5'
                    )
                    ->get();
            } else {
                // Fetch raw materials filtered by the selected category IDs
                $rawMaterials = DB::table('raw_materials as rm')
                    ->leftJoin('categoryitems as c1', 'rm.category_id1', '=', 'c1.id')
                    ->leftJoin('categoryitems as c2', 'rm.category_id2', '=', 'c2.id')
                    ->leftJoin('categoryitems as c3', 'rm.category_id3', '=', 'c3.id')
                    ->leftJoin('categoryitems as c4', 'rm.category_id4', '=', 'c4.id')
                    ->leftJoin('categoryitems as c5', 'rm.category_id5', '=', 'c5.id')
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

            // Return filtered raw materials as JSON response
            return response()->json([
                'rawMaterials' => $rawMaterials
            ]);
        }

        // Default view, return all raw materials and category items
        $rawMaterials = DB::table('raw_materials as rm')
            ->leftJoin('categoryitems as c1', 'rm.category_id1', '=', 'c1.id')
            ->leftJoin('categoryitems as c2', 'rm.category_id2', '=', 'c2.id')
            ->leftJoin('categoryitems as c3', 'rm.category_id3', '=', 'c3.id')
            ->leftJoin('categoryitems as c4', 'rm.category_id4', '=', 'c4.id')
            ->leftJoin('categoryitems as c5', 'rm.category_id5', '=', 'c5.id')
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
                'c5.itemname as category_name5'
            )
            ->get();

        return view('rawMaterial', compact('rawMaterials', 'categoryitems'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rawMaterialCategories = DB::table('categoryitems')->get(); // Fetch all category data
        return view('addRawMaterial', compact('rawMaterialCategories')); // Match view name
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'uom' => 'required|string|in:Ltr,Kgs',
            'category_ids' => 'required|array',
            'category_ids.*' => 'integer|exists:categoryitems,id',
            'price' => 'required|string',
            'price_update_frequency' => 'required|string',
            'price_threshold' => 'required|string'
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
                'price' => $request->price,
                'price_update_frequency' => $request->price_update_frequency,
                'price_threshold' => $request->price_threshold,
            ]);
        } catch (\Exception $e) {
            // \Log::error('Error inserting data: ' . $e->getMessage());
            dd($e->getMessage());
        }


        return redirect()->back()->with('success', 'Raw Material created successfully.');
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
    public function destroy(string $id)
    {
        //
    }
}
