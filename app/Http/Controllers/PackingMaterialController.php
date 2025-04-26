<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\CategoryItems;
use App\Models\PackingMaterial;
use App\Models\UniqueCode;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PackingMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Fetch all category items
        $categoryitems = CategoryItems::pmCategoryItem();
        $selectedCategoryIds = $request->input('category_ids', []);
        $searchValue = $request->input('pmText','');

        if ($request->ajax()) {
            if(!empty($searchValue))
            {
                 // Fetch packing materials filtered by the selected category IDs
                 $packingMaterials = DB::table('packing_materials as pm')
                 ->leftJoin('categoryitems as c1', 'pm.category_id1', '=', 'c1.id')
                 ->leftJoin('categoryitems as c2', 'pm.category_id2', '=', 'c2.id')
                 ->leftJoin('categoryitems as c3', 'pm.category_id3', '=', 'c3.id')
                 ->leftJoin('categoryitems as c4', 'pm.category_id4', '=', 'c4.id')
                 ->leftJoin('categoryitems as c5', 'pm.category_id5', '=', 'c5.id')
                 ->leftJoin('categoryitems as c6', 'pm.category_id6', '=', 'c6.id')
                 ->leftJoin('categoryitems as c7', 'pm.category_id7', '=', 'c7.id')
                 ->leftJoin('categoryitems as c8', 'pm.category_id8', '=', 'c8.id')
                 ->leftJoin('categoryitems as c9', 'pm.category_id9', '=', 'c9.id')
                 ->leftJoin('categoryitems as c10', 'pm.category_id10', '=', 'c10.id')
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
                     'c5.itemname as category_name5',
                     'c6.itemname as category_name6',
                     'c7.itemname as category_name7',
                     'c8.itemname as category_name8',
                     'c9.itemname as category_name9',
                     'c10.itemname as category_name10'
                 )
                 ->where('pm.status', '=', 'active')
                 ->Where('pm.name', 'LIKE', "{$searchValue}%")
                //  ->orderBy('pm.name', 'asc')
                 ->get();

             return response()->json([
                 'status' => 'success',
                 'message' => count($packingMaterials) > 0 ? 'packingMaterials found' : 'No packingMaterials found',
                 'packingMaterials' => $packingMaterials
             ]);
        }
        else{
            $selectedCategoryIds = explode(',', $selectedCategoryIds);
            // If no categories are selected, return all packing materials
            if (empty($selectedCategoryIds)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No category IDs provided',
                    'packingMaterials' => []
                ]);
            } else {
                // Fetch packing materials filtered by the selected category IDs
                $packingMaterials = DB::table('packing_materials as pm')
                    ->leftJoin('categoryitems as c1', 'pm.category_id1', '=', 'c1.id')
                    ->leftJoin('categoryitems as c2', 'pm.category_id2', '=', 'c2.id')
                    ->leftJoin('categoryitems as c3', 'pm.category_id3', '=', 'c3.id')
                    ->leftJoin('categoryitems as c4', 'pm.category_id4', '=', 'c4.id')
                    ->leftJoin('categoryitems as c5', 'pm.category_id5', '=', 'c5.id')
                    ->leftJoin('categoryitems as c6', 'pm.category_id6', '=', 'c6.id')
                    ->leftJoin('categoryitems as c7', 'pm.category_id7', '=', 'c7.id')
                    ->leftJoin('categoryitems as c8', 'pm.category_id8', '=', 'c8.id')
                    ->leftJoin('categoryitems as c9', 'pm.category_id9', '=', 'c9.id')
                    ->leftJoin('categoryitems as c10', 'pm.category_id10', '=', 'c10.id')
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
                        'c5.itemname as category_name5',
                        'c6.itemname as category_name6',
                        'c7.itemname as category_name7',
                        'c8.itemname as category_name8',
                        'c9.itemname as category_name9',
                        'c10.itemname as category_name10'
                    )
                    ->where('pm.status', '=', 'active')
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
                    // ->where('pm.status', '=', 'active') // Filter by active status
                    ->orderBy('pm.name', 'asc')
                    ->get();

                return response()->json([
                    'status' => 'success',
                    'message' => count($packingMaterials) > 0 ? 'packingMaterials found' : 'No packingMaterials found',
                    'packingMaterials' => $packingMaterials
                ]);
            }
        }
        }

        // Default view, return all packing materials and category items
        $packingMaterials = DB::table('packing_materials as pm')
        ->leftJoin('categoryitems as c1', 'pm.category_id1', '=', 'c1.id')
        ->leftJoin('categoryitems as c2', 'pm.category_id2', '=', 'c2.id')
        ->leftJoin('categoryitems as c3', 'pm.category_id3', '=', 'c3.id')
        ->leftJoin('categoryitems as c4', 'pm.category_id4', '=', 'c4.id')
        ->leftJoin('categoryitems as c5', 'pm.category_id5', '=', 'c5.id')
        ->leftJoin('categoryitems as c6', 'pm.category_id6', '=', 'c6.id')
        ->leftJoin('categoryitems as c7', 'pm.category_id7', '=', 'c7.id')
        ->leftJoin('categoryitems as c8', 'pm.category_id8', '=', 'c8.id')
        ->leftJoin('categoryitems as c9', 'pm.category_id9', '=', 'c9.id')
        ->leftJoin('categoryitems as c10', 'pm.category_id10', '=', 'c10.id')
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
            'c5.itemname as category_name5',
            'c6.itemname as category_name6',
            'c7.itemname as category_name7',
            'c8.itemname as category_name8',
            'c9.itemname as category_name9',
            'c10.itemname as category_name10'
        )
        ->where('pm.status', '=', 'active') // Filter by active status
        ->orderBy('pm.name', 'asc')
        ->paginate(10);

        return view('packingMaterial.packingMaterial', compact('packingMaterials', 'categoryitems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $packingMaterialCategories = CategoryItems::pmCategoryItem();
        $itemtype = DB::table('item_type')->where('status', '=', 'active')->get();

        return view('packingMaterial.addPackingMaterial', compact('packingMaterialCategories', 'itemtype'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
        $request->validate([
            'name' => [
            'required',
            'string',
            'max:255',
            'unique:packing_materials,name',
            function ($attribute, $value, $fail) {
                // Convert input to lowercase and remove spaces
                $formattedValue = strtolower(str_replace(' ', '', $value));
                // Fetch existing names from the database (case-insensitive)
                $existingNames = PackingMaterial::pluck('name')->map(function ($name) {
                    return strtolower(str_replace(' ', '', $name));
                })->toArray();
                // Check if the formatted input already exists
                if (in_array($formattedValue, $existingNames)) {
                    $fail('This name is duplicate. Please choose a different one.');
                }
            }
        ],  // 'name' => 'required|string|max:255|unique:packing_materials,name',
            'uom' => 'required|string|in:Ltr,Kgm,Gm,Nos',
            'category_ids' => 'required|array',
            'category_ids.*' => 'integer|exists:categoryitems,id',
            'price' => 'required|string',
            'update_frequency' => 'required|string|in:Days,Weeks,Monthly,Yearly',
            'price_update_frequency' => 'required|string',
            'price_threshold' => 'required|string',
            'hsnCode' => 'required|string',
            'itemType_id' => 'integer|exists:item_type,id',
            'itemWeight' => 'required|string',
            'tax' => 'required|string',
        ]);

        $categoryIds = $request->category_ids;

        $pmCode = UniqueCode::generatePmCode();

        try {
            PackingMaterial::create([
                'name' => $request->name,
                'pmcode' => $pmCode,
                'hsnCode' => $request->hsnCode,
                'uom' => $request->uom,
                'itemWeight' => $request->itemWeight,
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
                'itemType_id' => $request->itemType_id,
                'price' => $request->price,
                'tax' => $request->tax,
                'update_frequency' => $request->update_frequency,
                'price_update_frequency' => $request->price_update_frequency,
                'price_threshold' => $request->price_threshold,
            ]);
        } catch (\Exception $e) {
            // \Log::error('Error inserting data: ' . $e->getMessage());
            dd($e->getMessage());
        }
        return redirect()->route('packingMaterials.index')->with('success', 'Packing Material created successfully.');
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

        $itemtype = DB::table('item_type')->where('status', '=', 'active')->get();
        $selectedItemType = $packingMaterial->itemType_id ?? null;
        // Fetch the specific packing material by its ID
        $packingMaterial = DB::table('packing_materials')->where('id', $id)->first(); // Fetch the single packing material entry

        // Return the view with packing material data and categories
        return view('packingMaterial.editPackingMaterial', compact('packingMaterial', 'packingMaterialCategories', 'itemtype', 'selectedItemType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the existing packing material by ID
        $packingMaterial = PackingMaterial::findOrFail($id);
        try {

              $strName = strtolower(preg_replace('/\s+/', '', $request->name));
            //   $strHsnCode = strtolower(preg_replace('/\s+/', '', $request->hsnCode));
            // for duplicate
            $existingMaterial = PackingMaterial::where(function ($query) use ($strName) {
                $query->whereRaw("LOWER(REPLACE(name, ' ', '')) = ?", [$strName]);
                    // ->orWhereRaw("LOWER(REPLACE(hsncode, ' ', '')) = ?", [$strHsnCode]);
            })
            ->where('id', '!=', $packingMaterial->id) // Exclude the current product
            ->first();

            if ($existingMaterial) {
                // if ($strName == strtolower(preg_replace('/\s+/', '', $existingMaterial->name)) &&
                //     $strHsnCode == strtolower(preg_replace('/\s+/', '', $existingMaterial->hsnCode))) {
                //     return redirect()->back()->with('error', 'Both Material Name and HSN Code already exist.');
                // }
                if ($strName == strtolower(preg_replace('/\s+/', '', $existingMaterial->name))) {
                    return redirect()->back()->with('error', 'Material Name already exists.');
                }
                // elseif ($strHsnCode == strtolower(preg_replace('/\s+/', '', $existingMaterial->hsnCode))) {
                //     return redirect()->back()->with('error', 'HSN Code already exists.');
                // }
            }
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
            'hsnCode' => 'required|string',
                'itemType_id' => 'integer|exists:item_type,id',
                'itemWeight' => 'required|string',
            'tax' => 'required|string',
        ]);

        $categoryIds = $request->category_ids;

        if ($packingMaterial->price != $request->price) {
            DB::table('pm_price_histories')->insert([
                'packing_material_id' => $packingMaterial->id,
                'old_price' => $packingMaterial->price, // Correct way to get the old price
                'new_price' => $request->price,
                'updated_by' => 1, // Ensure user is authenticated
                'updated_at' => now(),
            ]);
        }
        try {
            // Update the packing material record
            $packingMaterial->update([
                'name' => $request->name,
                'hsnCode' => $request->hsnCode,
                'uom' => $request->uom,
                'itemWeight' => $request->itemWeight,
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
                'itemType_id' => $request->itemType_id,
                'price' => $request->price,
                'tax' => $request->tax,
                'update_frequency' => $request->update_frequency,
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
            return response()->json(['success' => false, 'message' => 'No valid IDs provided.']);
        }
        try {
            // Update only if the raw materials are NOT referenced in rm_for_recipe
            $updatedCount = PackingMaterial::whereIn('id', $ids)
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('pm_for_recipe')
                        ->whereColumn('pm_for_recipe.packing_material_id', 'packing_materials.id'); // Ensure correct column name
                })
                ->update(['status' => 'inactive']);

                return response()->json([
                    'success' => true,
                    'message' => $updatedCount > 0 ? 'Packing materials marked as inactive successfully.' : 'No packing materials were updated.',
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating packing materials: ' . $e->getMessage(),
            ]);
        }
    }
    public function delete(Request $request)
    {
        $ids = $request->input('ids'); // Get the 'ids' array from the request

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No valid IDs provided.']);
        }
        try {
            // First, check which items are not referenced in pm_for_recipe
            $itemsToDelete = PackingMaterial::whereIn('id', $ids)
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('pm_for_recipe')
                        ->whereColumn('pm_for_recipe.packing_material_id', 'packing_materials.id'); // Ensure correct column name
                })
                ->get();

            // If there are items that can be deleted, return a confirmation message
            if ($itemsToDelete->isNotEmpty()) {
                return response()->json([
                    'success' => true,
                    'confirm' => true,
                    'message' => 'Are you want to delete this item of packing material. Do you want to proceed?',
                    // 'items' => $itemsToDelete, // Send the list of items for confirmation
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No packing materials item can be deleted. They might be in use.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking packing materials: ' . $e->getMessage(),
            ]);
        }

        // try {
        //     // Update the status of raw materials to 'inactive'
        //     PackingMaterial::whereIn('id', $ids)->update(['status' => 'inactive']);

        //     return response()->json(['success' => true, 'message' => 'Raw materials marked as inactive successfully.']);
        // } catch (\Exception $e) {
        //     // Handle exceptions
        //     return response()->json(['success' => false, 'message' => 'Error updating raw materials: ' . $e->getMessage()]);
        // }
    }

        // import excel data to db
        public function importExcel(Request $request)
        {
            $request->validate([
                'excel_file' => 'required|mimes:xlsx,xls,csv|max:2048'
            ]);

            $file = $request->file('excel_file');

            // Load spreadsheet
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            if (empty($rows) || count($rows) < 2) {
                return back()->with('error', 'The uploaded file is empty or does not have enough data.');
            }

            // ✅ Define Expected Headers
            $expectedHeaders = [
               'sno', 'name', 'uom', 'hsncode', 'itemweight', 'category_id1', 'category_id2',
                'category_id3', 'category_id4', 'category_id5', 'category_id6', 'category_id7',
                'category_id8', 'category_id9', 'category_id10', 'price', 'tax',
                'update_frequency', 'price_update_frequency', 'price_threshold', 'itemType'
            ];

            // ✅ Get Headers from First Row
            $fileHeaders = array_map('trim', $rows[0]); // Trim spaces from headers

            // ✅ Check missing headers
            $missingHeaders = array_diff($expectedHeaders, $fileHeaders);
            $extraHeaders = array_diff($fileHeaders, $expectedHeaders);

            if (!empty($missingHeaders)) {
                return back()->with('error', 'Missing headers: ' . implode(', ', $missingHeaders));
            }

            if (!empty($extraHeaders)) {
                return back()->with('error', 'Extra headers found: ' . implode(', ', $extraHeaders));
            }
             // ✅ Check if headers match exactly (Order & Case-Sensitive Check)
            if ($fileHeaders !== $expectedHeaders) {
                return back()->with('error',' Invalid column order! Please ensure the headers are exactly: ' . implode(', ', $expectedHeaders));
            }
            $duplicateNames = [];
            $importedCount = 0;
            // Loop through rows and insert into database
            foreach ($rows as $index => $row) {
                if ($index == 0) continue; // Skip the header row

            $normalizedName = str_replace(' ', '', strtolower(trim($row[1])));
            $existingPacking = PackingMaterial::whereRaw("
                    REPLACE(LOWER(TRIM(name)), ' ', '') = ?
                ", [$normalizedName])
                // ->where('hsnCode', $row[3])
                ->first();

             if ($existingPacking) {
                 $duplicateNames[] = $row[1];
                 continue; // Skip duplicate row
             }

                $pmCode = UniqueCode::generatePmCode();
                // $pm_categoryId = DB::table('categories')
                // ->whereRaw("REPLACE(LOWER(TRIM(categoryname)), ' ', '') = ?", ['packingmaterials']) // removes all spaces
                // ->value('id');

                $categoryIds = [];
                $name = trim($row[1] ?? '');
                $uom = trim($row[2] ?? '');
                $hsncode = trim($row[3] ?? '');
                $itemwgt = trim($row[4] ?? '');
                $price = trim($row[15] ?? '');
                $ptax = trim($row[16] ?? '');
                $frequency = trim($row[17] ?? '');
                $priceupdatefrequency = trim($row[18] ?? '');
                $thershold = trim($row[19] ?? '');
                $itemType = trim($row[20] ?? '');
                $categoryIds['id1'] = trim($row[5] ?? '');
                /*
                for ($i = 1; $i <= 10; $i++) {
                    $itemNameRaw = $row[$i + 4] ?? null;

                    $name = trim($row[1] ?? '');
                    $uom = trim($row[2] ?? '');
                    $hsncode = trim($row[3] ?? '');
                    $itemwgt = trim($row[4] ?? '');
                    $price = trim($row[15] ?? '');
                    $ptax = trim($row[16] ?? '');
                    $frequency = trim($row[17] ?? '');
                    $priceupdatefrequency = trim($row[18] ?? '');
                    $thershold = trim($row[19] ?? '');
                    $itemType = trim($row[20] ?? '');

                    if (!empty($itemNameRaw) && trim($itemNameRaw) !== '') {
                        $itemName = trim(strtolower($itemNameRaw));

                        // Try to find the ID with the normalized comparison
                        $itemId = DB::table('categoryitems')
                            ->where('categoryId', $pm_categoryId)
                            ->where('status', 'active')
                            ->whereRaw("REPLACE(LOWER(TRIM(itemname)), ' ', '') = REPLACE(LOWER(TRIM(?)), ' ', '')", [$itemName])
                            ->value('id');

                        // If not found, insert a new record
                        if (!$itemId) {
                            $itemId = DB::table('categoryitems')->insertGetId([
                                'categoryId' => $pm_categoryId,
                                'itemname' => $itemNameRaw, // use original casing and spacing
                                'description' => 'none',
                                'status' => 'active',       // assuming default status is 'active'
                                'created_at' => now(),      // optional timestamps
                                'updated_at' => now()
                            ]);
                        }

                        $categoryIds["id$i"] = $itemId;
                    } else {
                        $categoryIds["id$i"] = null;
                        // return back()->with('error', 'category_Id1 is not null. you must fill it.');
                    }
                }
                */
                if (
                    empty($name) ||
                    empty($uom) ||
                    (empty($hsncode) || strlen($hsncode) > 8) ||
                    empty($itemwgt) ||
                    empty($price) ||
                    empty($ptax) ||
                    empty($frequency) ||
                    empty($priceupdatefrequency) ||
                    empty($thershold) ||
                    empty($itemType) ||
                    empty($categoryIds['id1']) // category_id1 must not be null
                ) {
                    $skippedRows[] = "Row ".($index + 1)." skipped: missing required fields (name/uom/hsncode/itemwgt/price/tax/updatefrequency/priceupdatefrequency/threshold/itemType/category_id1).";
                    continue;
                }
                        for ($i = 1; $i <= 10; $i++) {
                    $categoryIds["id$i"] = !empty($row[$i + 4]) // Adjusting index to match $row[4] for category_id1
                        ? DB::table('categoryitems')
                            ->where('categoryId', 2)
                            ->where('status', 'active')
                            // ->where('itemname', $row[$i + 3])
                            ->whereRaw("REPLACE(LOWER(TRIM(itemname)), ' ', '') = REPLACE(LOWER(TRIM(?)), ' ', '')", [trim(strtolower($row[$i + 4]))])
                            ->value('id')
                        : null;
                }
                $itemtype_id = DB::table('item_type')->where('itemtypename',$row[20])->where('status', 'active')->value('id');

                PackingMaterial::create([
                    'name' => $row[1] ,
                    'pmcode' => $pmCode ?? null,
                    'uom' => $row[2] ?? null,
                    'hsnCode' => $row[3] ?? null,
                    'itemWeight' => $row[4] ?? null,
                    'category_id1' => $categoryIds['id1'] ,
                    'category_id2' => $categoryIds['id2'] ?? null,
                    'category_id3' => $categoryIds['id3'] ?? null,
                    'category_id4' => $categoryIds['id4'] ?? null,
                    'category_id5' => $categoryIds['id5'] ?? null,
                    'category_id6' => $categoryIds['id6'] ?? null,
                    'category_id7' => $categoryIds['id7'] ?? null,
                    'category_id8' => $categoryIds['id8'] ?? null,
                    'category_id9' => $categoryIds['id9'] ?? null,
                    'category_id10' => $categoryIds['id10'] ?? null,
                    'price' => $row[15],
                    'tax' => $row[16],
                    'update_frequency' => $row[17],
                    'price_update_frequency' => $row[18],
                    'price_threshold' => $row[19],
                    'itemType_id' => $itemtype_id,
                ]);
                $importedCount++;
            }
            if ($importedCount === 0 && !empty($duplicateNames)) {
                return back()->with('error', 'All rows are duplicates. Skipped: ' . implode(', ', $duplicateNames));
            }

            $message = $importedCount . ' row(s) imported successfully.';
            if (!empty($duplicateNames)) {
                $message .= ' Skipped duplicates: ' . implode(', ', $duplicateNames);
            }
            if (!empty($skippedRows)) {
                $message .= ' Skipped rows: ' . implode(' | ', $skippedRows);
            }
            return back()->with('success',  $message);
            // return back()->with('success', 'Excel file imported successfully!');
        }
}
