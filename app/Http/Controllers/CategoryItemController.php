<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
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

    public function show()
    {
    $categories = DB::table('categories')->get(); // Fetch all category data
    $categoriesitems = DB::table('categoryitems')->paginate(10);
    return view('categories', compact('categories', 'categoriesitems')); // Match view name
    }

    // public function getCategoriesItems($categoryId)
    // {
    //     $categoriesitems = DB::table('categoryitems')->where('categoryId',$categoryId)->get(); // Fetch all category data
    //     return view('categories', compact('categoriesitems'));
    // }
}
