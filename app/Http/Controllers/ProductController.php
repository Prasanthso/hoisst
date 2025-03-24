<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\CategoryItems;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\UniqueCode;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $categoryitems = CategoryItems::pdCategoryItem();
        $selectedCategoryIds = $request->input('category_ids', []);
        $searchValue = $request->input('pdText','');

        if ($request->ajax()) {
            if(!empty($searchValue))
            {

            $product = DB::table('product_master as pd')
            ->leftJoin('categoryitems as c1', 'pd.category_id1', '=', 'c1.id')
            ->leftJoin('categoryitems as c2', 'pd.category_id2', '=', 'c2.id')
            ->leftJoin('categoryitems as c3', 'pd.category_id3', '=', 'c3.id')
            ->leftJoin('categoryitems as c4', 'pd.category_id4', '=', 'c4.id')
            ->leftJoin('categoryitems as c5', 'pd.category_id5', '=', 'c5.id')
            ->leftJoin('categoryitems as c6', 'pd.category_id6', '=', 'c6.id')
            ->leftJoin('categoryitems as c7', 'pd.category_id7', '=', 'c7.id')
            ->leftJoin('categoryitems as c8', 'pd.category_id8', '=', 'c8.id')
            ->leftJoin('categoryitems as c9', 'pd.category_id9', '=', 'c9.id')
            ->leftJoin('categoryitems as c10', 'pd.category_id10', '=', 'c10.id')
            ->select(
                'pd.id',
                'pd.name',
                'pd.pdcode',
                'pd.price',
                'pd.uom',
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
            ->where('pd.status', '=', 'active') // Filter by active status
            ->where('pd.name', 'LIKE', "{$searchValue}%")
            ->get();
             // Return filtered raw materials as JSON response
             return response()->json([
                'status' => 'success',
                'message' => count($product) > 0 ? 'Products found' : 'No products found',
                'product' => $product
            ]);
            }
            else{
            $selectedCategoryIds = explode(',', $selectedCategoryIds);
            $selectedCategoryIds = array_filter($selectedCategoryIds, fn($id) => is_numeric($id) && $id > 0);

            // If no categories are selected, return all products
            if (empty($selectedCategoryIds)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No category IDs provided',
                    'product' => []
                ]);
            }

            // Fetch PRODUCTS filtered by the selected category IDs
            $product = DB::table('product_master as pd')
                ->leftJoin('categoryitems as c1', 'pd.category_id1', '=', 'c1.id')
                ->leftJoin('categoryitems as c2', 'pd.category_id2', '=', 'c2.id')
                ->leftJoin('categoryitems as c3', 'pd.category_id3', '=', 'c3.id')
                ->leftJoin('categoryitems as c4', 'pd.category_id4', '=', 'c4.id')
                ->leftJoin('categoryitems as c5', 'pd.category_id5', '=', 'c5.id')
                ->leftJoin('categoryitems as c6', 'pd.category_id6', '=', 'c6.id')
                ->leftJoin('categoryitems as c7', 'pd.category_id7', '=', 'c7.id')
                ->leftJoin('categoryitems as c8', 'pd.category_id8', '=', 'c8.id')
                ->leftJoin('categoryitems as c9', 'pd.category_id9', '=', 'c9.id')
                ->leftJoin('categoryitems as c10', 'pd.category_id10', '=', 'c10.id')
                ->select(
                    'pd.id',
                    'pd.name',
                    'pd.pdcode',
                    'pd.price',
                    'pd.uom',
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
                ->where('pd.status', '=', 'active') // Filter by active status
                ->orderBy('pd.name', 'asc')
                ->get();
            // Return filtered raw materials as JSON response
            return response()->json([
                'status' => 'success',
                'message' => count($product) > 0 ? 'Products found' : 'No products found',
                'product' => $product
            ]);
        }
        }

        // Default view, return all raw materials and category items
        $product = DB::table('product_master as pd')
            ->leftJoin('categoryitems as c1', 'pd.category_id1', '=', 'c1.id')
            ->leftJoin('categoryitems as c2', 'pd.category_id2', '=', 'c2.id')
            ->leftJoin('categoryitems as c3', 'pd.category_id3', '=', 'c3.id')
            ->leftJoin('categoryitems as c4', 'pd.category_id4', '=', 'c4.id')
            ->leftJoin('categoryitems as c5', 'pd.category_id5', '=', 'c5.id')
            ->leftJoin('categoryitems as c6', 'pd.category_id6', '=', 'c6.id')
            ->leftJoin('categoryitems as c7', 'pd.category_id7', '=', 'c7.id')
            ->leftJoin('categoryitems as c8', 'pd.category_id8', '=', 'c8.id')
            ->leftJoin('categoryitems as c9', 'pd.category_id9', '=', 'c9.id')
            ->leftJoin('categoryitems as c10', 'pd.category_id10', '=', 'c10.id')
            ->select(
                'pd.id',
                'pd.name',
                'pd.pdcode',
                'pd.price',
                'pd.uom',
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
        ->where('pd.status', '=', 'active') // Filter by active status
        ->orderBy('pd.name', 'asc')
        ->paginate(10);

        return view('product.products', compact('product', 'categoryitems'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $product = CategoryItems::pdCategoryItem();
        $itemtype = DB::table('item_type')->where('status','=','active')->get();
        return view('product.addProduct', compact('product','itemtype')); // Match view name
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
            'unique:product_master,name',
            function ($attribute, $value, $fail) {
                // Convert input to lowercase and remove spaces
                $formattedValue = strtolower(str_replace(' ', '', $value));
                // Fetch existing names from the database (case-insensitive)
                $existingNames = Product::pluck('name')->map(function ($name) {
                    return strtolower(str_replace(' ', '', $name));
                })->toArray();
                if (in_array($formattedValue, $existingNames)) {
                    $fail('This name is duplicate. Please choose a different one.');
                }
            }
        ],   //'name' => 'required|string|max:255',
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

        $pdCode = UniqueCode::generatePdCode();

        try {
            Product::create([
                'name' => $request->name,
                'pdcode' => $pdCode,
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
                'purcCost' => $request->purcCost,
                'margin' => $request->margin,
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
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
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
            'updatedMaterials.*.id' => 'required|exists:product_master,id',
            'updatedMaterials.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($validatedData) {
                foreach ($validatedData['updatedMaterials'] as $material) {
                    // Fetch the current material
                    $currentMaterial = Product::find($material['id']);

                    // Check if the price has changed
                    if ($currentMaterial->price != $material['price']) {
                        // Log the price update in the rm_price_histories table
                        DB::table('pd_price_histories')->insert([
                            'product_id' => $currentMaterial->id,
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
    public function getPdPriceHistory($id)
    {
        $priceHistory = DB::table('pd_price_histories')
            ->where('product_id', $id)
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
        // $productCategories = DB::table('categoryitems')->get();
        $productCategories = CategoryItems::pdCategoryItem();
        $itemtype = DB::table('item_type')->where('status','=','active')->get();
        $selectedItemType = $product->itemType_id ?? null;
        // Fetch the specific raw material by its ID
        $product = DB::table('product_master')->where('id', $id)->first(); // Fetch the single raw material entry

        // Return the view with raw material data and categories
        return view('product.editProduct', compact('product', 'productCategories','itemtype','selectedItemType'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the existing raw material by ID
        $product = Product::findOrFail($id);
        try {
            // for duplicate
            $strName = strtolower(preg_replace('/\s+/', '', $request->name));
            // $strHsnCode = strtolower(preg_replace('/\s+/', '', $request->hsnCode));

            // Check for existing product with the same normalized name or HSN code
            $existingProduct = Product::where(function ($query) use ($strName) {
                $query->whereRaw("LOWER(REPLACE(name, ' ', '')) = ?", [$strName]);
                    // ->orWhereRaw("LOWER(REPLACE(hsnCode, ' ', '')) = ?", [$strHsnCode]);
            })
            ->where('id', '!=', $product->id) // Exclude the current product
            ->first();

            if ($existingProduct) {
                // if ($strName == strtolower(preg_replace('/\s+/', '', $existingProduct->name)) &&
                //     $strHsnCode == strtolower(preg_replace('/\s+/', '', $existingProduct->hsnCode))) {
                //     return redirect()->back()->with('error', 'Both Product Name and HSN Code already exist.');
                // }
                if ($strName == strtolower(preg_replace('/\s+/', '', $existingProduct->name))) {
                    return redirect()->back()->with('error', 'Product Name already exists.');
                }
                // elseif ($strHsnCode == strtolower(preg_replace('/\s+/', '', $existingProduct->hsnCode))) {
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

        try {
            // Update the raw material record
            $product->update([
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
                'purcCost' => $request->purcCost,
                'margin' => $request->margin,
                'price' => $request->price,
                'tax' => $request->tax,
                'update_frequency' => $request->update_frequency,
                'price_update_frequency' => $request->price_update_frequency,
                'price_threshold' => $request->price_threshold,
            ]);
        } catch (\Exception $e) {
            // Handle the error gracefully (e.g., log it and show an error message)
            // \Log::error('Error updating raw material: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an issue updating the l.');
        }
        // Return a success message and redirect back
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
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
            // Update the status of raw materials to 'inactive'

            // $updatedCount = Product::whereIn('id', $ids)
            // ->whereNotExists(function ($query) {
            //     $query->select(DB::raw(1))
            //         ->from('recipedetails')
            //         ->whereColumn('recipedetails.product_id', 'product_master.id'); // Ensure correct column name
            // })
            // ->update(['status' => 'inactive']);

            $updatedCount = Product::whereIn('id', $ids)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('recipedetails')
                    ->whereColumn('recipedetails.product_id', 'product_master.id');
            })
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('recipe_master')
                    ->whereColumn('recipe_master.product_id', 'product_master.id');
            })
            ->update(['status' => 'inactive']);

            return response()->json([
                'success' => true,
                'message' => $updatedCount > 0 ? 'Products marked as inactive successfully.' : 'No Products were updated.',
            ]);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['success' => false, 'message' => 'Error updating Products: ' . $e->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        $ids = $request->input('ids'); // Get the 'ids' array from the request

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No valid IDs provided.']);
        }

        try {
            // Update the status of raw materials to 'inactive'
            $itemsToDelete = Product::whereIn('id', $ids)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('recipedetails')
                    ->whereColumn('recipedetails.product_id', 'product_master.id')
                    ->where('status', '!=', 'active');
            })
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('recipe_master')
                    ->whereColumn('recipe_master.product_id', 'product_master.id')
                    ->where('status', '!=', 'active');
            })
            ->get();

        // If there are items that can be deleted, return a confirmation message
        if ($itemsToDelete->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'confirm' => true,
                'message' => 'Are you want to delete this item of Products. Do you want to proceed?',
                // 'items' => $itemsToDelete, // Send the list of items for confirmation
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No Products item can be deleted. They might be in use.',
            ]);
        }
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['success' => false, 'message' => 'Error updating Products: ' . $e->getMessage()]);
        }
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

            // Loop through rows and insert into database
            foreach ($rows as $index => $row) {
                if ($index == 0) continue; // Skip the header row
                $pdCode = UniqueCode::generatePdCode();

                $categoryIds = [];

                for ($i = 1; $i <= 10; $i++) {
                    $categoryIds["id$i"] = !empty($row[$i + 3]) // Adjusting index to match $row[4] for category_id1
                        ? DB::table('categoryitems')
                            ->where('categoryId', 1)
                            ->where('status', 'active')
                            // ->where('itemname', $row[$i + 3])
                            ->whereRaw("LOWER(TRIM(itemname)) = LOWER(TRIM(?))", [trim(strtolower($row[$i + 3]))])
                            ->value('id')
                        : null;
                }
                $itemtype_id = DB::table('item_type')->where('itemtypename',$row[19])->where('status', 'active')->value('id');

                Product::create([
                    'name' => $row[0] ?? null,
                    'pdcode' => $pdCode ?? null,
                    'uom' => $row[1] ?? null,
                    'hsncode' => $row[2] ?? null,
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
                    'purcCost' => $row[14],
                    'margin' => $row[15],
                    'price' => $row[16],
                    'tax' => $row[17],
                    'update_frequency' => $row[18],
                    'price_update_frequency' => $row[19],
                    'price_threshold' => $row[20],
                    'itemType_id' => $itemtype_id,
                ]);
            }

            return back()->with('success', 'Excel file imported successfully!');
        }

}
