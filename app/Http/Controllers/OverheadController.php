<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\CategoryItems;
use App\Models\Overhead;
use App\Models\UniqueCode;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\IOFactory;

class OverheadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        // Fetch all category items
        $categoryitems = CategoryItems::ohCategoryItem($storeid);
        $selectedCategoryIds = $request->input('category_ids', []);
        $searchValue = $request->input('ohText','');
        $statusValue = $request->input('statusValue', 'active');
        if ($request->ajax()) {
            if(!empty($searchValue))
            {
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
                    'c10.itemname as category_name10',
                    'oh.status'
                )
                    // ->where('oh.status', '=', 'active') // Filter by active status
                    ->where('oh.store_id', $storeid)
                    ->Where('oh.name', 'LIKE', "{$searchValue}%")
                    // ->orderBy('oh.name', 'asc')
                    ->get();
                // Return filtered packing materials as JSON response
                return response()->json([
                    'status' => 'success',
                    'message' => count($overheads) > 0 ? 'Overheads found' : 'No Overheads found',
                    'overheads' => $overheads
                ]);
            }
        if(!empty($selectedCategoryIds)) {
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
                    'c10.itemname as category_name10',
                    'oh.status'
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
                    // ->where('oh.status', '=', 'active') // Filter by active status
                    ->where('oh.store_id', $storeid)
                    ->orderBy('oh.name', 'asc')
                    ->get();
            // Return filtered packing materials as JSON response
            return response()->json([
                'status' => 'success',
                'message' => count($overheads) > 0 ? 'Overheads found' : 'No Overheads found',
                'overheads' => $overheads
            ]);
        }
          if(!empty($statusValue))
            {
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
                    'c10.itemname as category_name10',
                    'oh.status'
                )
                    ->where('oh.status', '=', $statusValue) // Filter by active status
                    ->where('oh.store_id', $storeid)
                    ->Where('oh.name', 'LIKE', "{$searchValue}%")
                    // ->orderBy('oh.name', 'asc')
                    ->get();
                // Return filtered packing materials as JSON response
                return response()->json([
                    'status' => 'success',
                    'message' => count($overheads) > 0 ? 'Overheads found' : 'No Overheads found',
                    'overheads' => $overheads
                ]);
            }
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
            'c10.itemname as category_name10',
            'oh.status'
        )
        // ->where('oh.status', '=', $statusValue) // Filter by active status
        ->where('oh.store_id', $storeid)
        ->orderBy('oh.name', 'asc')
        ->paginate(10);

        return view('overheads.overheads', compact('overheads', 'categoryitems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        $overheadsCategories = CategoryItems::ohCategoryItem($storeid);
        $itemtype = DB::table('item_type')->where('status', '=', 'active')->where('store_id', 0)->get();
        return view('overheads.addOverheads', compact('overheadsCategories', 'itemtype')); // Match view name
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
            // 'unique:overheads,name',
            function ($attribute, $value, $fail) use ($storeid) {
                // Convert input to lowercase and remove spaces
                $formattedValue = strtolower(str_replace(' ', '', $value));
                // Fetch existing names from the database (case-insensitive)
                $existingNames = Overhead::where('store_id', $storeid)->pluck('name')->map(function ($name) {
                    return strtolower(str_replace(' ', '', $name));
                })->toArray();
                if (in_array($formattedValue, $existingNames)) {
                    $fail('This name is duplicate. Please choose a different one.');
                }
            }
        ],
            'uom' => 'required|string|in:Ltr,Kgm,Gm,Nos',
            'category_ids' => 'required|array',
            'category_ids.*' => 'integer|exists:categoryitems,id',
            'price' => 'required|string',
            'update_frequency' => 'required|string|in:Days,Weeks,Monthly,Yearly',
            'price_update_frequency' => 'required|string',
            'price_threshold' => 'required|string',
            // 'hsncode' => 'required|string',
            'itemweight' => 'required|string',
            // 'itemType_id' => 'integer|exists:item_type,id',
            // 'tax' => 'required|string',
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
                // 'hsncode' => $request->hsncode,
                'itemweight' => $request->itemweight,
                'store_id' => $storeid,
                // 'itemType_id' => $request->itemType_id,
                // 'tax' => $request->tax,
            ]);
        } catch (\Exception $e) {
            // \Log::error('Error inserting data: ' . $e->getMessage());
            dd($e->getMessage());
        }

        return redirect()->route('overheads.index')->with('success', 'Overheads created successfully.');
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
            'updatedMaterials.*.id' => 'required|exists:overheads,id',
            'updatedMaterials.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($validatedData, $storeid ) {
                foreach ($validatedData['updatedMaterials'] as $material) {
                    // Fetch the current material
                    $currentMaterial = Overhead::where('store_id', $storeid)->where('id', $material['id'])->first();  //find($material['id']);

                    // Check if the price has changed
                    if ($currentMaterial->price != $material['price']) {
                        // Log the price update in the rm_price_histories table
                        DB::table('oh_price_histories')->insert([
                            'overheads_id' => $currentMaterial->id,
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
    public function getOhPriceHistory(Request $request, $id)
    {
        $storeid = $request->session()->get('store_id');
        $priceHistory = DB::table('oh_price_histories')
        ->where('overheads_id', $id)
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
        // // Fetch all categories
        $overheadsCategories = CategoryItems::ohCategoryItem($storeid);

        $itemtype = DB::table('item_type')->where('status', '=', 'active')->where('store_id', 0)->get();
        $selectedItemType = $overheads->itemType_id ?? null;
        // Fetch the specific raw material by its ID
        $overheads = DB::table('overheads')->where('store_id', $storeid)->where('id', $id)->first(); // Fetch the single raw material entry

        // Return the view with raw material data and categories
        return view('overheads.editOverheads', compact('overheads', 'overheadsCategories', 'itemtype', 'selectedItemType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $storeid = $request->session()->get('store_id');
        // Find the existing raw material by ID
        $overheads = Overhead::where('store_id', $storeid)
                    ->where('id', $id)
                    ->firstOrFail();  //findOrFail($id);
        try {
            // for duplicate
            $strName = strtolower(preg_replace('/\s+/', '', $request->name));

            // Check for existing overheads with the same normalized name or HSN code
            $existingOverhead = Overhead::where(function ($query) use ($strName) {
                $query->whereRaw("LOWER(REPLACE(name, ' ', '')) = ?", [$strName]);

            })
            ->where('id', '!=', $overheads->id) // Exclude the current product
            ->where('store_id', $storeid)
            ->first();

            if ($existingOverhead) {

                if ($strName == strtolower(preg_replace('/\s+/', '', $existingOverhead->name))) {
                    return redirect()->back()->with('error', 'Overhead Name already exists.');
                }
                // elseif ($strHsnCode == strtolower(preg_replace('/\s+/', '', $existingOverhead->hsncode))) {
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
            // 'hsncode' => 'required|string',
            'itemweight' => 'required|string',
                // 'itemType_id' => 'integer|exists:item_type,id',
                // 'tax' => 'required|string',
            ]);

        $categoryIds = $request->category_ids;

        if ($overheads->price != $request->price) {
            DB::table('oh_price_histories')->insert([
                'overheads_id' => $overheads->id,
                'old_price' => $overheads->price, // Correct way to get the old price
                'new_price' => $request->price,
                'updated_by' => 1, // Ensure user is authenticated
                'store_id' => $storeid,
                'updated_at' => now(),
            ]);
        }

        try {
            // Update the raw material record
            $overheads->update([
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
                // 'hsncode' => $request->hsncode,
                'itemweight' => $request->itemweight,
                    'status' => $request->status,
                    'store_id' => $storeid,
                // 'itemType_id' => $request->itemType_id,
                    // 'tax' => $request->tax,
                ]);
        } catch (\Exception $e) {
            // Handle the error gracefully (e.g., log it and show an error message)
            // \Log::error('Error updating raw material: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an issue updating the overhead.');
        }

        // Return a success message and redirect back
        return redirect()->route('overheads.index')->with('success', 'Overheads updated successfully.');
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
            return response()->json(['success' => false, 'message' => 'No valid IDs provided.']);
        }

        try {
            // Update the status of raw materials to 'inactive'
            // Overhead::whereIn('id', $ids)->update(['status' => 'inactive']);
            $updatedCount = Overhead::where('store_id',$storeid)
            ->whereIn('id', $ids)
            ->whereNotExists(function ($query) use ($storeid) {
                $query->select(DB::raw(1))
                    ->from('oh_for_recipe')
                    ->where('store_id',$storeid)
                    ->whereColumn('oh_for_recipe.overheads_id', 'overheads.id'); // Ensure correct column name
            })
            ->update(['status' => 'inactive']);

            return response()->json([
                'success' => true,
                'message' => $updatedCount > 0 ? 'Overheads marked as inactive successfully.' : 'No overheads were updated.',
            ]);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['success' => false, 'message' => 'Error updating overheads: ' . $e->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        $ids = $request->input('ids'); // Get the 'ids' array from the request

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No valid IDs provided.']);
        }

        try {
            // Update the status of raw materials to 'inactive'
            // Overhead::whereIn('id', $ids)->update(['status' => 'inactive']);
            $itemsToDelete = Overhead::where('store_id',$storeid)
            ->whereIn('id', $ids)
            ->whereNotExists(function ($query) use ($storeid){
                $query->select(DB::raw(1))
                    ->from('oh_for_recipe')
                    ->where('store_id',$storeid)
                    ->whereColumn('oh_for_recipe.overheads_id', 'overheads.id'); // Ensure correct column name
            })
            ->get();

        // If there are items that can be deleted, return a confirmation message
        if ($itemsToDelete->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'confirm' => true,
                'message' => 'Are you want to delete this item of overheads. Do you want to proceed?',
                // 'items' => $itemsToDelete, // Send the list of items for confirmation
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No overheads item can be deleted. They might be in use.',
            ]);
        }
            // return response()->json(['success' => true, 'message' => 'Raw materials marked as inactive successfully.']);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['success' => false, 'message' => 'Error updating overheads: ' . $e->getMessage()]);
        }
    }

      // import excel data to db
      public function importExcel(Request $request)
      {
        $storeid = $request->session()->get('store_id');
        try{

          $request->validate([
              'excel_file' => 'required|mimes:xlsx,xls,csv|max:2048'
          ]);

          $file = $request->file('excel_file');

          // Load spreadsheet
          $spreadsheet = IOFactory::load($file->getPathname());
          $sheet = $spreadsheet->getActiveSheet();
          $rows = $sheet->toArray();

            // ✅ Define Expected Headers
            $expectedHeaders = [
                'sno', 'name', 'uom', 'itemweight', 'category_id1', 'category_id2',
                 'category_id3', 'category_id4', 'category_id5', 'category_id6', 'category_id7',
                 'category_id8', 'category_id9', 'category_id10', 'price',
                 'update_frequency', 'price_update_frequency', 'price_threshold' // 'itemType'
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

              $existingOverhead = Overhead::whereRaw("
                      REPLACE(LOWER(TRIM(name)), ' ', '') = ?
                  ", [$normalizedName])
                ->where('store_id', $storeid)
                ->first();

            if ($existingOverhead) {
                $duplicateNames[] = $row[1];
                continue; // Skip duplicate row
            }

              $ohCode = UniqueCode::generateOhCode();
            //   $oh_categoryId = DB::table('categories')
            //   ->whereRaw("REPLACE(LOWER(TRIM(categoryname)), ' ', '') = ?", ['overheads']) // removes all spaces
            //   ->value('id');

              $categoryIds = [];
              $name = trim($row[1] ?? '');
              $uom = trim($row[2] ?? '');
              $itemwgt = trim($row[3] ?? '');
              $price = trim($row[14] ?? '');
              $frequency = trim($row[15] ?? '');
              $priceupdatefrequency = trim($row[16] ?? '');
              $thershold = trim($row[17] ?? '');
              $categoryIds['id1'] = trim($row[4] ?? '');
              /*
              for ($i = 1; $i <= 10; $i++) {
                $itemNameRaw = $row[$i + 3] ?? null;

                $name = trim($row[1] ?? '');
                $uom = trim($row[2] ?? '');
                $itemwgt = trim($row[3] ?? '');
                $price = trim($row[14] ?? '');
                $frequency = trim($row[15] ?? '');
                $priceupdatefrequency = trim($row[16] ?? '');
                $thershold = trim($row[17] ?? '');

                if (!empty($itemNameRaw)) {
                    $itemName = trim(strtolower($itemNameRaw));

                    // Try to find the ID with the normalized comparison
                    $itemId = DB::table('categoryitems')
                        ->where('categoryId', $oh_categoryId)
                        ->where('status', 'active')
                        ->whereRaw("REPLACE(LOWER(TRIM(itemname)), ' ', '') = REPLACE(LOWER(TRIM(?)), ' ', '')", [$itemName])
                        ->value('id');

                    // If not found, insert a new record
                    if (!$itemId) {
                        $itemId = DB::table('categoryitems')->insertGetId([
                            'categoryId' => $oh_categoryId,
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
                }
            }*/

        if (
            empty($name) ||
            empty($uom) ||
            empty($itemwgt) ||
            empty($price) ||
            empty($frequency) ||
            empty($priceupdatefrequency) ||
            empty($thershold) ||
            empty($categoryIds['id1']) // category_id1 must not be null
        ) {
            $skippedRows[] = "Row ".($index + 1)." skipped: missing required fields (name/uom/hsncode/itemwgt/price/tax/updatefrequency/priceupdatefrequency/threshold/itemType/category_id1).";
            continue;
        }
              for ($i = 1; $i <= 10; $i++) {
                  $categoryIds["id$i"] = !empty($row[$i + 3]) // Adjusting index to match $row[3] for category_id1
                      ? DB::table('categoryitems')
                          ->where('categoryId', 3) // here, 3 is overheads id
                          ->where('store_id', $storeid)
                          ->where('status', 'active')
                        //   ->whereRaw("TRIM(itemname) = ?", [trim($row[$i + 2])])
                        ->whereRaw("REPLACE(LOWER(TRIM(itemname)), ' ', '') = REPLACE(LOWER(TRIM(?)), ' ', '')", [trim(strtolower($row[$i + 3]))])
                        ->value('id')
                      : null;
              }
            //   $itemtype_id = DB::table('item_type')->where('itemtypename',$row[18])->where('status', 'active')->value('id');

              Overhead::create([
                  'name' => $row[1] ?? null,
                  'ohcode' => $ohCode ?? null,
                  'uom' => $row[2] ?? null,
                //   'hsnCode' => $row[2] ?? null,
                  'itemweight' => $row[3] ?? null,
                  'category_id1' => $categoryIds['id1'] ?? null,
                  'category_id2' => $categoryIds['id2'] ?? null,
                  'category_id3' => $categoryIds['id3'] ?? null,
                  'category_id4' => $categoryIds['id4'] ?? null,
                  'category_id5' => $categoryIds['id5'] ?? null,
                  'category_id6' => $categoryIds['id6'] ?? null,
                  'category_id7' => $categoryIds['id7'] ?? null,
                  'category_id8' => $categoryIds['id8'] ?? null,
                  'category_id9' => $categoryIds['id9'] ?? null,
                  'category_id10' => $categoryIds['id10'] ?? null,
                  'price' => $row[14],
                //   'tax' => $row[15],
                  'update_frequency' => $row[15],
                  'price_update_frequency' => $row[16],
                  'price_threshold' => $row[17],
                  'store_id' => $storeid,
                //   'itemType_id' => $itemtype_id,
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
            // if (!empty($skippedRows)) {
            //     $message .= ' Skipped rows: ' . implode(' | ', $skippedRows);
            // }
          return back()->with('success',  $message);
        //   return back()->with('success', 'Excel file imported successfully!');
        } catch (\Exception $e) {
            // Handle the error gracefully (e.g., log it and show an error message)
            // \Log::error('Error importing Excel file: ' . $e->getMessage());
           return back()->with('error', 'There was an issue importing the Excel file. It might be due to an invalid file format or values. Please check the file and try again.');
        }
      }

    public function exportAll(Request $request)
    {
        $storeid = $request->session()->get('store_id');
           $statusValue = $request->input('statusValue', '');
        $categories = \App\Models\CategoryItems::where('store_id', $storeid)->pluck('itemname', 'id');

        $query = \App\Models\Overhead::select([
            'id',
            'name',
            'ohcode',
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
            'category_id10',
            'status'
        ])
            // ->where('status', 'active')  // Filter active records
            ->where('store_id', $storeid);
            // ->orderBy('name', 'asc')     // Sort by name ASC
            // ->get();
        if ($statusValue !== null && $statusValue !== '') {
            $query->where('status', $statusValue);
        }
        $overheads = $query->orderBy('name', 'asc')->get();

        $overheadsWithNames = $overheads->map(function ($item) use ($categories) {
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
                'ohcode' => $item->ohcode,
                'price' => $item->price,
                'uom' => $item->uom,
                'status' => $item->status,
                'categories' => implode(', ', $categoryNames), // Comma-separated for easier frontend use
            ];
        });

        return response()->json($overheadsWithNames);
    }
}
