<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Include this for database queries

class CategoryController extends Controller
{
    /**
     * Show the category selection page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch category names from the 'categories' table
        $categories = DB::table('categories')->pluck('categoryname');

        // Pass categories to the view
        return view('category', ['categories' => $categories]);
    }

    /**
     * Handle the form submission.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the selected category
        $request->validate([
            'category' => 'required|string',
        ]);

        // Get the selected category
        $selectedCategory = $request->input('category');

        // Perform necessary actions with the selected category (e.g., save to the database)
        // Example:
        // DB::table('user_categories')->insert(['category' => $selectedCategory]);

        return redirect()->back()->with('success', 'Category selected successfully: ' . $selectedCategory);
    }
}
