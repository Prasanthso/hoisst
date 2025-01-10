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
            $categoriesitems = CategoryItems::paginate(10);
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
}
