<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\CategoryItems;
use App\Models\RawMaterial;
use App\Models\UniqueCode;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\IOFactory;

class RawMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Fetch all category items
        $storeid = $request->session()->get('store_id');
        $categoryitems = CategoryItems::rmCategoryItem($storeid);
        $selectedCategoryIds = $request->input('category_ids', []);
        $searchValue = $request->input('rmText','');

        if ($request->ajax()) {
            if(!empty($searchValue))
            {
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
                    ->where('rm.store_id', $storeid)
                    ->Where('rm.name', 'LIKE', "{$searchValue}%")
                    // ->orderBy('rm.name', 'asc') // Filter by active status
                    ->get();

                return response()->json([
                    'status' => 'success',
                    'message' => count($rawMaterials) > 0 ? 'rawMaterials found' : 'No rawMaterials found',
                    'rawMaterials' => $rawMaterials
                ]);
            }
        else{
            $selectedCategoryIds = explode(',', $selectedCategoryIds);
            // If no categories are selected, return all raw materials with status 'active'
            if (empty($selectedCategoryIds)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No category IDs provided',
                    'rawMaterials' => []
                ]);
            }
            else {
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
                    // ->where('rm.status', '=', 'active')
                    ->where('rm.store_id', $storeid)
                    ->orderBy('rm.name', 'asc') // Filter by active status
                    ->get();
                    // ->paginate(10);
                     // Return filtered raw materials as JSON response
            return response()->json([
                'status' => 'success',
                'message' => count($rawMaterials) > 0 ? 'rawMaterials found' : 'No rawMaterials found',
                'rawMaterials' => $rawMaterials
            ]);
            }
        }

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
            ->where('rm.store_id', $storeid)
            ->orderBy('rm.name', 'asc') // Filter by active status
            ->paginate(10);

        return view('rawMaterial.rawMaterial', compact('rawMaterials', 'categoryitems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        $rawMaterialCategories = CategoryItems::rmCategoryItem($storeid);
        $itemtype = DB::table('item_type')->where('status', '=', 'active')->where('store_id', 0)->get();

        return view('rawMaterial.addRawMaterial', compact('rawMaterialCategories', 'itemtype')); // Match view name
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        try{
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // 'unique:raw_materials,name',
                function ($attribute, $value, $fail) use ($storeid) {
                    // Convert input to lowercase and remove spaces
                    $formattedValue = strtolower(str_replace(' ', '', $value));
                    // Fetch existing names from the database (case-insensitive)
                    $existingNames = RawMaterial::where('store_id', $storeid)->pluck('name')->map(function ($name) {
                        return strtolower(str_replace(' ', '', $name));
                    })->toArray();
                    // Check if the formatted input already exists
                    if (in_array($formattedValue, $existingNames)) {
                        $fail('This name is duplicate. Please choose a different one.');
                    }
                }
            ],  //'name' => 'required|string|max:255|unique:raw_materials,name',
            'uom' => 'required|string|in:Ltr,Kgm,Gm,Nos',
            'category_ids' => 'required|array',
            'category_ids.*' => 'integer|exists:categoryitems,id',
            'price' => 'required|string',
            'update_frequency' => 'required|string|in:Days,Weeks,Monthly,Yearly',
            'price_update_frequency' => 'required|string',
            'price_threshold' => 'required|string',
            'hsncode' => 'required|string',
            'itemweight' => 'required|string',
            'itemType_id' => 'integer|exists:item_type,id',
                // 'itemtype' => 'required|string',
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
                    'itemType_id' => $request->itemType_id,
                    'tax' => $request->tax,
                    'store_id' => $storeid,
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
        $storeid = $request->session()->get('store_id');
        $validatedData = $request->validate([
            'updatedMaterials' => 'required|array',
            'updatedMaterials.*.id' => 'required|exists:raw_materials,id',
            'updatedMaterials.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($validatedData, $storeid) {
                foreach ($validatedData['updatedMaterials'] as $material) {
                    // Fetch the current material
                    $currentMaterial = RawMaterial::where('store_id', $storeid)->where('id', $material['id'])->first();  //find($material['id']);

                    // Check if the price has changed
                    if ($currentMaterial->price != $material['price']) {
                        // Log the price update in the rm_price_histories table
                        DB::table('rm_price_histories')->insert([
                            'raw_material_id' => $currentMaterial->id,
                            'old_price' => $currentMaterial->price,
                            'new_price' => $material['price'],
                            'updated_by' => 1, // Ensure user is authenticated
                            'store_id' => $storeid,
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
    public function getRmPriceHistory(Request $request, $id)
    {
        $storeid = $request->session()->get('store_id');
        $priceHistory = DB::table('rm_price_histories')
        ->where('raw_material_id', $id)
        ->where('store_id', $storeid)
        ->orderBy('updated_at', 'desc') // Replace 'id' with the column you want to sort by
        ->get();
        return response()->json(['priceDetails' => $priceHistory]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $storeid = $request->session()->get('store_id');
        // Fetch all categories
        $rawMaterialCategories = CategoryItems::rmCategoryItem($storeid);

        $itemtype = DB::table('item_type')->where('status', '=', 'active')->where('store_id', 0)->get();
        $selectedItemType = $rawMaterial->itemType_id ?? null;
        // Fetch the specific raw material by its ID
        $rawMaterial = DB::table('raw_materials')->where('store_id', $storeid)->where('id', $id)->first(); // Fetch the single raw material entry

        // Return the view with raw material data and categories
        return view('rawMaterial.editRawMaterial', compact('rawMaterial', 'rawMaterialCategories', 'itemtype', 'selectedItemType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $storeid = $request->session()->get('store_id');
        // Find the existing raw material by ID
        $rawMaterial = RawMaterial::where('store_id', $storeid)
                        ->where('id', $id)
                        ->firstOrFail();  //findOrFail($id);

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
                'itemType_id' => 'integer|exists:item_type,id',
                'tax' => 'required|string',
        ]);

        $categoryIds = $request->category_ids;

        try {
            // for duplicate
            $strName = strtolower(preg_replace('/\s+/', '', $request->name));
            // $strHsnCode = strtolower(preg_replace('/\s+/', '', $request->hsncode));

            // Check for existing product with the same normalized name or HSN code
            $existingMaterial = RawMaterial::where(function ($query) use ($strName) {
                $query->whereRaw("LOWER(REPLACE(name, ' ', '')) = ?", [$strName]);
                    // ->orWhereRaw("LOWER(REPLACE(hsncode, ' ', '')) = ?", [$strHsnCode]);
            })
            ->where('id', '!=', $rawMaterial->id) // Exclude the current product
            ->where('store_id', $storeid)
            ->first();

            if ($existingMaterial) {
                // if ($strName == strtolower(preg_replace('/\s+/', '', $existingMaterial->name)) &&
                //     $strHsnCode == strtolower(preg_replace('/\s+/', '', $existingMaterial->hsncode))) {
                //     return redirect()->back()->with('error', 'Both Material Name and HSN Code already exist.');
                // }
                if ($strName == strtolower(preg_replace('/\s+/', '', $existingMaterial->name))) {
                    return redirect()->back()->with('error', 'Material Name already exists.');
                }
                //  elseif ($strHsnCode == strtolower(preg_replace('/\s+/', '', $existingMaterial->hsncode))) {
                //     return redirect()->back()->with('error', 'HSN Code already exists.');
                // }
            }

            if ($rawMaterial->price != $request->price) {
                DB::table('rm_price_histories')->insert([
                    'raw_material_id' => $rawMaterial->id,
                    'old_price' => $rawMaterial->price, // Correct way to get the old price
                    'new_price' => $request->price,
                    'updated_by' => 1, // Ensure user is authenticated
                    'store_id' => $storeid,
                    'updated_at' => now(),
                ]);
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
                    'itemType_id' => $request->itemType_id,
                    'tax' => $request->tax,
                    'store_id' => $storeid,
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
        $storeid = $request->session()->get('store_id');
        $ids = $request->input('ids'); // Get the 'ids' array from the request

        if (!$ids || !is_array($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid IDs provided.',
            ]);
        }

        try {
            // Update only if the raw materials are NOT referenced in rm_for_recipe
            $updatedCount = RawMaterial::where('store_id',$storeid)
                ->whereIn('id', $ids)
                ->whereNotExists(function ($query) use ($storeid) {
                    $query->select(DB::raw(1))
                        ->from('rm_for_recipe')
                        ->where('store_id',$storeid)
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
        $storeid = $request->session()->get('store_id');
        $ids = $request->input('ids'); // Get the 'ids' array from the request

        if (!$ids || !is_array($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid IDs provided.',
            ]);
        }

        try {
            // Update only if the raw materials are NOT referenced in rm_for_recipe
            $itemsToDelete = RawMaterial::where('store_id',$storeid)
                ->whereIn('id', $ids)
                ->whereNotExists(function ($query) use ($storeid) {
                    $query->select(DB::raw(1))
                        ->from('rm_for_recipe')
                        ->where('store_id',$storeid)
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
        // import excel data to db
    public function importExcel(Request $request)
    {
        $storeid = $request->session()->get('store_id');
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
            return back()->with('error', 'Missing headers or Matching headers: ' . implode(', ', $missingHeaders));
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
            // check for duplicates based on 'name' and 'hsnCode'
            $existingRawmaterial = RawMaterial::whereRaw("
                REPLACE(LOWER(TRIM(name)), ' ', '') = ?
            ", [$normalizedName])
            ->where('store_id', $storeid)
            // ->where('hsncode', $row[3])
            ->first();
        // $existingRawmaterial = RawMaterial::whereRaw("
        //     REPLACE(LOWER(TRIM(name)), ' ', '') = ?
        // ", [str_replace(' ', '', strtolower(trim($row[1])))])
        // ->where('hsncode', $row[3])
        // ->first();

         if ($existingRawmaterial) {
             $duplicateNames[] = $row[1];
             continue; // Skip duplicate row
         }
            $rmCode = UniqueCode::generateRmCode();

            // $rm_categoryId = DB::table('categories')
            // ->whereRaw("REPLACE(LOWER(TRIM(categoryname)), ' ', '') = ?", ['rawmaterials']) // removes all spaces
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
                        ->where('categoryId', $rm_categoryId)
                        ->where('status', 'active')
                        ->whereRaw("REPLACE(LOWER(TRIM(itemname)), ' ', '') = REPLACE(LOWER(TRIM(?)), ' ', '')", [$itemName])
                        ->value('id');

                    // If not found, insert a new record
                    if (!$itemId) {
                        $itemId = DB::table('categoryitems')->insertGetId([
                            'categoryId' => $rm_categoryId,
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
                        ->where('categoryId', 1)
                        ->where('status', 'active')
                        ->where('store_id', $storeid)
                        // ->where('itemname', $row[$i + 3])
                        ->whereRaw("REPLACE(LOWER(TRIM(itemname)), ' ', '') = REPLACE(LOWER(TRIM(?)), ' ', '')", [trim(strtolower($row[$i + 4]))])
                        ->value('id')
                    : null;
            }
            $itemtype_id = DB::table('item_type')->where('itemtypename',$row[20])->where('status', 'active')->where('store_id', 0)->value('id');

            RawMaterial::create([
                'name' => $row[1] ?? null,
                'rmcode' => $rmCode ?? null,
                'uom' => $row[2] ?? null,
                'hsncode' => $row[3] ?? null,
                'itemweight' => $row[4] ?? null,
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
                'store_id' => $storeid
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

    public function exportAll(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        $categories = \App\Models\CategoryItems::where('store_id', $storeid)->pluck('itemname', 'id');

        $rawMaterials = \App\Models\RawMaterial::select([
            'id',
            'name',
            'rmcode',
            'price',
            'uom',
            'category_id1',
            'category_id2',
            'category_id3',
            'category_id4',
            'category_id5',
            'category_id6',
            'category_id7',
            'category_id8',
            'category_id9',
            'category_id10'
        ])
            ->where('status', 'active')  // Filter active records
            ->where('store_id', $storeid)
            ->orderBy('name', 'asc')     // Sort by name ASC
            ->get();


        $rawMaterialsWithNames = $rawMaterials->map(function ($item) use ($categories) {
            $categoryNames = [];

            for ($i = 1; $i <= 10; $i++) {
                $field = 'category_id' . $i;
                if (!empty($item->$field) && isset($categories[$item->$field])) {
                    $categoryNames[] = $categories[$item->$field];
                }
            }

            return [
                'id' => $item->id,
                'name' => $item->name,
                'rmcode' => $item->rmcode,
                'price' => $item->price,
                'uom' => $item->uom,
                'categories' => implode(', ', $categoryNames), // Comma-separated for easier frontend use
            ];
        });

        return response()->json($rawMaterialsWithNames);
    }
/*
    public function importCSV(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $file = fopen($request->file('file')->getPathname(), "r");

        // Skip the first row (header)
        fgetcsv($file);

        while (($row = fgetcsv($file, 1000, ",")) !== FALSE) {
            $rmCode = UniqueCode::generateRmCode();
            RawMaterial::create([
                'name' => $row[0] ?? null,
                'rmcode' => $rmCode ?? null,
                'uom' => $row[1] ?? null,
                'hsncode' => $row[2] ?? null,
                'itemweight' => $row[3] ?? null,
                'category_id1' => $row[4] ?? null,
                'category_id2' => $row[5] ?? null,
                'category_id3' => $row[6] ?? null,
                'category_id4' => $row[7] ?? null,
                'category_id5' => $row[8] ?? null,
                'category_id6' => $row[9] ?? null,
                'category_id7' => $row[10] ?? null,
                'category_id8' => $row[11] ?? null,
                'category_id9' => $row[12] ?? null,
                'category_id10' => $row[13] ?? null,
                'price' => $row[14],
                'tax' => $row[15],
                'update_frequency' => $row[16],
                'price_update_frequency' => $row[17],
                'price_threshold' => $row[18],
                'itemType_id' => $row[19],
            ]);
        }

        fclose($file);

        return back()->with('success', 'CSV data imported successfully!');
    }
*/

}
