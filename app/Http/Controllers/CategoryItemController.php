<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\CategoryItems;
use Illuminate\Http\Request;


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
        $categories = DB::table('categories')->get();
        $categoryIds = $request->input('category_ids');

        if (!empty($categoryIds)) {
            // Convert comma-separated string to an array
            $categoryIds = explode(',', $categoryIds);

            // Fetch items matching the category IDs
            $categoriesitems = CategoryItems::whereIn('categoryId', $categoryIds)->get();
        } else {
            // Fetch all items when no categories are selected
            $categoriesitems = CategoryItems::where('status','active')->paginate(10);
        }

        if ($request->ajax()) {
            return response()->json([
                'categoriesitems' => $categoriesitems,
            ]);
        }

        return view('categories', compact('categories', 'categoriesitems'));
    }


    /**
     * Display the create form.
     */
    public function create()
    {
    $categories = DB::table('categories')->get(); // Fetch all category data
    return view('addcategory', compact('categories')); // Match view name
    }

    /**
     * Store a new category item.
     */
    public function store(Request $request)
    {
        $request->validate([
            'categoryId' => 'required|integer',
            'itemname' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        CategoryItems::create([
            'categoryId' => $request->categoryId,
            'itemname' => $request->itemname,
            'description' => $request->description,
            'created_user' => auth()->id(), // Assuming the user is authenticated
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Category item created successfully.');
    }


    // public function getCategoriesItems($categoryId)
    // {
    //     $categoriesitems = DB::table('categoryitems')->where('categoryId',$categoryId)->get(); // Fetch all category data
    //     return view('categories', compact('categoriesitems'));
    // }

    public function edit(string $id)
    {
        $items = CategoryItems::with('category')->findOrFail($id);

        // Return the view with categories and category items
        return view('editCategory', compact('items'));
    }

    public function update(Request $request,$id)
    {
        $categoriesitems = CategoryItems::findOrFail($id);

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
                'created_user' => auth()->id(), // Assuming the user is authenticated
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with('error', 'There was an issue updating the category items.');
        }
        session()->flash('success', 'Category item updated successfully.');
        return redirect()->route('categoryitem.index');
    }

    public function deleteConfirmation(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No valid IDs provided.'], 400);
        }

        try {
            // Update only CategoryItems that are NOT present in any of the related tables
            $updatedCount = CategoryItems::whereIn('id', $ids)
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('raw_materials')
                        ->where(function ($q) {
                            for ($i = 1; $i <= 10; $i++) {
                                $q->orWhereColumn("raw_materials.category_id{$i}", 'categoryitems.id');
                            }
                        });
                })
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('packing_materials')
                        ->where(function ($q) {
                            for ($i = 1; $i <= 10; $i++) {
                                $q->orWhereColumn("packing_materials.category_id{$i}", 'categoryitems.id');
                            }
                        });
                })
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('overheads')
                        ->where(function ($q) {
                            for ($i = 1; $i <= 10; $i++) {
                                $q->orWhereColumn("overheads.category_id{$i}", 'categoryitems.id');
                            }
                        });
                })
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('product_master')
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
        $ids = $request->input('ids'); // Get the 'ids' array from the request

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No valid IDs provided.']);
        }
            try {
                // Update only CategoryItems that are NOT present in any of the related tables
                $itemsToDelete = CategoryItems::whereIn('id', $ids)
                    ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('raw_materials')
                            ->where(function ($q) {
                                for ($i = 1; $i <= 10; $i++) {
                                    $q->orWhereColumn("raw_materials.category_id{$i}", 'categoryitems.id');
                                }
                            });
                    })
                    ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('packing_materials')
                            ->where(function ($q) {
                                for ($i = 1; $i <= 10; $i++) {
                                    $q->orWhereColumn("packing_materials.category_id{$i}", 'categoryitems.id');
                                }
                            });
                    })
                    ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('overheads')
                            ->where(function ($q) {
                                for ($i = 1; $i <= 10; $i++) {
                                    $q->orWhereColumn("overheads.category_id{$i}", 'categoryitems.id');
                                }
                            });
                    })
                    ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('product_master')
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
}
