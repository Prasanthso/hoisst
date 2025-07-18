<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\CategoryItems;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CategoryItemController extends Controller
{
    // public function index()
    // {
    //     // Fetch category names from the 'categories' table
    //     $categories = DB::table('categories')->pluck('categoryname');

    //     // Pass categories to the view
    //     return view('categories', ['categories' => $categories]);
    // }

    public function index(Request $request)
    {
        $storeid = $request->session()->get('store_id');

        $categories = DB::table('categories')->get();
        $categoryIds = $request->input('category_ids');
        $searchValue = $request->input('categoryItem', '');
        $statusValue = $request->input('statusValue', '');

        $query = DB::table('categoryitems')
            ->join('categories', 'categoryitems.categoryId', '=', 'categories.id')
            ->select('categoryitems.*', 'categories.categoryname')
            ->where('categoryitems.store_id', $storeid);

        // Always apply status filter first
        if (!empty($statusValue)) {
            if ($statusValue === 'all' || in_array('all', (array)$statusValue)) {
                $statusValue = ['active', 'inactive'];
            } else {
                $statusValue = (array) $statusValue;
            }
            $query->whereIn('categoryitems.status', $statusValue);
        }

        // Then apply other filters
        if (!empty($searchValue)) {
            $query->where('categoryitems.itemname', 'LIKE', "{$searchValue}%");
            $categoriesitems = $query->get();
        }
        if (!empty($categoryIds)) {
            $categoryIds = explode(',', $categoryIds);
            $query->whereIn('categoryitems.categoryId', $categoryIds);
            $categoriesitems = $query->orderBy('categoryitems.itemname', 'asc')->get();
        }
       if(!empty($statusValue)) {
            $categoriesitems = $query->orderBy('categoryitems.itemname', 'asc')->paginate(10);
        }

        // Return paginated or non-paginated response
        if ($request->ajax()) {
            $categoriesitems = $query->paginate(10);
            return response()->json([
                'categoriesitems' => $categoriesitems,
            ]);
        } else {
            $categoriesitems = $query->paginate(10);
            return view('category.categories', compact('categories', 'categoriesitems'));
        }

        // if (!empty($searchValue)) {
        //     $query->where('categoryitems.itemname', 'LIKE', "{$searchValue}%");
        //     // ->whereIn('categoryitems.status', (array) $statusValue);
        //     $categoriesitems = $query->get();
        // } elseif (!empty($categoryIds)) {
        //     $categoryIds = explode(',', $categoryIds);
        //     $query->whereIn('categoryitems.categoryId', $categoryIds);
        //             // ->whereIn('categoryitems.status', (array) $statusValue);
        //     $categoriesitems = $query->orderBy('categoryitems.itemname', 'asc')->get();
        // } else {
        //     $categoriesitems = $query
        //     // ->whereIn('categoryitems.status', (array) $statusValue)
        //                         ->orderBy('categoryitems.itemname', 'asc')
        //                             ->paginate(10);
        // }

        // if ($request->ajax()) {
        //     return response()->json([
        //         'categoriesitems' => $categoriesitems,
        //     ]);
        // }

        // return view('category.categories', compact('categories', 'categoriesitems'));
    }


    /**
     * Display the create form.
     */
    public function create(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        $storeid = 0;
    $categories = DB::table('categories')->where('store_id', $storeid)->get(); // Fetch all category data
    return view('category.addCategory', compact('categories')); // Match view name
    }

    /**
     * Store a new category item.
     */
    public function store(Request $request)
    {
        try{
            $storeid = $request->session()->get('store_id');
            $request->validate([
                'categoryId' => 'required|integer',
                'itemname' => [
                    'required',
                    'string',
                    'max:255',
                    // 'unique:categoryitems,itemname',
                    function ($attribute, $value, $fail) use ($storeid){
                        // Convert input to lowercase and remove spaces
                        $formattedValue = strtolower(str_replace(' ', '', $value));
                        // Fetch existing names from the database (case-insensitive)
                        $existingNames = CategoryItems::where('store_id', $storeid)->pluck('itemname')->map(function ($itemname) {
                            return strtolower(str_replace(' ', '', $itemname));
                        })->toArray();
                        if (in_array($formattedValue, $existingNames)) {
                            $fail('This name is duplicate. Please choose a different one.');
                        }
                    }
                ],  // 'itemname' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

        CategoryItems::create([
            'categoryId' => $request->categoryId,
            'itemname' => $request->itemname,
            'description' => $request->description ?? 'none',
            'created_user' => auth()->id(), // Assuming the user is authenticated
            'status' => 'active',
            'store_id' => $storeid,
        ]);

        return redirect()->back()->with('success', 'Category item created successfully.');
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

    // public function getCategoriesItems($categoryId)
    // {
    //     $categoriesitems = DB::table('categoryitems')->where('categoryId',$categoryId)->get(); // Fetch all category data
    //     return view('categories', compact('categoriesitems'));
    // }

    public function edit(Request $request,string $id)
    {
        $storeid = $request->session()->get('store_id');
        // $items = CategoryItems::with('category')->findOrFail($id);
        $items = CategoryItems::with('category')
                ->where('store_id', $storeid)
                ->where('id', $id)
                ->firstOrFail();

        // Return the view with categories and category items
        return view('category.editCategory', compact('items'));
    }

    public function update(Request $request,$id)
    {
         $storeid = $request->session()->get('store_id');
        // $categoriesitems = CategoryItems::findOrFail($id);
        $categoriesitems = CategoryItems::where('id', $id)
                            ->where('store_id', $storeid)
                            ->firstOrFail();
        try{
        $stritemName = strtolower(preg_replace('/\s+/', '', $request->itemname));
        // Check for existing CategoryItems with the same normalized name or HSN code
        $existingItems =CategoryItems::where('store_id', $storeid)->where(function ($query) use ($stritemName) {
            $query->whereRaw("LOWER(REPLACE(itemname, ' ', '')) = ?", [$stritemName]);
        })
        ->where('id', '!=', $categoriesitems->id) // Exclude the current product
        ->first();

        if ($existingItems) {
            if ($strName == strtolower(preg_replace('/\s+/', '', $existingItems->itemname))) {
                return redirect()->back()->with('error', 'Category name already exist.');
            }
        }

        $request->validate([
            // 'categoryId' => 'required|integer',
            'itemname' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try{

            $categoriesitems->update([
                // 'categoryId' => $request->categoryId,
                'itemname' => $request->itemname,
                'description' => $request->description,
                'status' => $request->status,
                'created_user' => auth()->id(), // Assuming the user is authenticated
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with('error', 'There was an issue updating the category items.');
        }
        session()->flash('success', 'Category item updated successfully.');
        return redirect()->route('categoryitem.index');
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

    public function deleteConfirmation(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No valid IDs provided.'], 400);
        }

        try {
            // Update only CategoryItems that are NOT present in any of the related tables
            $updatedCount = CategoryItems::whereIn('id', $ids)
                ->whereNotExists(function ($query) use ($storeid) {
                    $query->select(DB::raw(1))
                        ->from('raw_materials')
                        ->where('store_id', $storeid)
                        ->where(function ($q) {
                            for ($i = 1; $i <= 10; $i++) {
                                $q->orWhereColumn("raw_materials.category_id{$i}", 'categoryitems.id');
                            }
                        });
                })
                ->whereNotExists(function ($query) use ($storeid) {
                    $query->select(DB::raw(1))
                        ->from('packing_materials')
                        ->where('store_id', $storeid)
                        ->where(function ($q) {
                            for ($i = 1; $i <= 10; $i++) {
                                $q->orWhereColumn("packing_materials.category_id{$i}", 'categoryitems.id');
                            }
                        });
                })
                ->whereNotExists(function ($query) use ($storeid) {
                    $query->select(DB::raw(1))
                        ->from('overheads')
                        ->where('store_id', $storeid)
                        ->where(function ($q) {
                            for ($i = 1; $i <= 10; $i++) {
                                $q->orWhereColumn("overheads.category_id{$i}", 'categoryitems.id');
                            }
                        });
                })
                ->whereNotExists(function ($query) use ($storeid) {
                    $query->select(DB::raw(1))
                        ->from('product_master')
                        ->where('store_id', $storeid)
                        ->where(function ($q) {
                            for ($i = 1; $i <= 10; $i++) {
                                $q->orWhereColumn("product_master.category_id{$i}", 'categoryitems.id');
                            }
                        });
                })
                ->update(['status' => 'inactive']);

            return response()->json([
                'success' => true,
                'message' => $updatedCount > 0 ? 'Category items marked as inactive successfully.' : 'No category items were updated.',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating category items: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating category items. Please try again later.',
            ], 500);
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
                // Update only CategoryItems that are NOT present in any of the related tables
                $itemsToDelete = CategoryItems::whereIn('id', $ids)
                    ->whereNotExists(function ($query) use ($storeid) {
                        $query->select(DB::raw(1))
                            ->from('raw_materials')
                            ->where('store_id', $storeid)
                            ->where(function ($q) {
                                for ($i = 1; $i <= 10; $i++) {
                                    $q->orWhereColumn("raw_materials.category_id{$i}", 'categoryitems.id');
                                }
                            });
                    })
                    ->whereNotExists(function ($query) use ($storeid) {
                        $query->select(DB::raw(1))
                            ->from('packing_materials')
                            ->where('store_id', $storeid)
                            ->where(function ($q) {
                                for ($i = 1; $i <= 10; $i++) {
                                    $q->orWhereColumn("packing_materials.category_id{$i}", 'categoryitems.id');
                                }
                            });
                    })
                    ->whereNotExists(function ($query) use ($storeid) {
                        $query->select(DB::raw(1))
                            ->from('overheads')
                            ->where('store_id', $storeid)
                            ->where(function ($q) {
                                for ($i = 1; $i <= 10; $i++) {
                                    $q->orWhereColumn("overheads.category_id{$i}", 'categoryitems.id');
                                }
                            });
                    })
                    ->whereNotExists(function ($query) use ($storeid) {
                        $query->select(DB::raw(1))
                            ->from('product_master')
                            ->where('store_id', $storeid)
                            ->where(function ($q) {
                                for ($i = 1; $i <= 10; $i++) {
                                    $q->orWhereColumn("product_master.category_id{$i}", 'categoryitems.id');
                                }
                            });
                    })
                    ->get();

                    if ($itemsToDelete->isNotEmpty()) {
                        return response()->json([
                            'success' => true,
                            'confirm' => true,
                            'message' => 'Are you want to delete this item of categoryitems. Do you want to proceed?',
                            // 'items' => $itemsToDelete, // Send the list of items for confirmation
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'No categoryitems can be deleted. They might be in use.',
                        ]);
                    }
            // return response()->json(['success' => true, 'message' => 'Category-item was inactive successfully.']);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['success' => false, 'message' => 'Error updating Category-item: ' . $e->getMessage()]);
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
            'SNo', 'categories', 'categoryname', 'description'];

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
            //   $categoryIds = [];
            $itemname = trim($row[2] ?? '');

            if( empty($itemname)) {
                $skippedRows[] = "Row ".($index + 1)." skipped: missing required field (itemname).";
                continue;
            }
            //   for ($i = 1; $i <= 10; $i++) {
                  $categoryId = !empty($row[1]) // Adjusting index to match $row[3] for category_id1
                      ? DB::table('categories')
                        ->whereRaw("REPLACE(LOWER(TRIM(categoryname)), ' ', '') = REPLACE(LOWER(TRIM(?)), ' ', '')", [trim(strtolower($row[1]))])
                          ->value('id')
                      : null;
            //   }
            $normalizedName = str_replace(' ', '', strtolower(trim($row[2])));

                $existingCategory = CategoryItems::whereRaw("
                    REPLACE(LOWER(TRIM(itemname)), ' ', '') = ?
                ", [$normalizedName])
                ->where('categoryId', $categoryId)
                ->where('store_id', $storeid)
                ->first();

                if ($existingCategory) {
                $duplicateNames[] = $row[2];
                continue; // Skip duplicate row
                }

            //   $itemtype_id = DB::table('item_type')->where('itemtypename',$row[17])->where('status', 'active')->value('id');

            CategoryItems::create([
                  'itemname' => $row[2] ?? null,
                  'categoryId' => $categoryId ?? null,
                  'description' => $row[3] ?? '',
                  'status' => 'active',
                  'store_id' => $storeid
              ]);
              $importedCount++;
          }

        // if ($importedCount === 0) {
        //     return back()->with('error', 'All rows are duplicates. Skipped: ' . implode(', ', $duplicateNames));
        // }

        $message = $importedCount . ' row(s) imported successfully.';
        if (!empty($duplicateNames)) {
            $message .= ' Skipped duplicates: ' . implode(', ', $duplicateNames);
        }
        if (!empty($skippedRows)) {
            // $message = $importedCount . ' row(s) not imported. Itemname is required';
            $message = 'Skipped rows: ' . implode(' | ', $skippedRows);

        }
          return back()->with('success',  $message);
        //   return back()->with('success', 'Excel file imported successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'There was an issue importing the Excel file. It might be due to an invalid file format or values. Please check the file and try again.');
        }
    }

    public function exportAll(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        $statusValue = $request->input('statusValue', '');

        try {
            $query = \App\Models\CategoryItems::select([
                'categoryitems.id',
                'categories.categoryname',
                'categoryitems.itemname',
                'categoryitems.description',
                'categoryitems.status'
            ])
            ->leftJoin('categories', 'categoryitems.categoryId', '=', 'categories.id')
            // ->where('categoryitems.status', $statusValue) // Only active items
            ->where('categoryitems.store_id', $storeid);

        if ($statusValue !== null && $statusValue !== '') {
            $query->where('categoryitems.status', $statusValue);
        }

        $categoryItems = $query->orderBy('categoryitems.itemname', 'asc')->get();

            return response()->json($categoryItems);

        } catch (\Exception $e) {
            // Return the error message if an exception occurs
            return response()->json(['error' => $e->getMessage()], 500);
        }
        // return response()->json($categoryItems);
    }


}
