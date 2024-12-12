<?php

namespace App\Http\Controllers;

use App\Models\CategoryItems;
use Illuminate\Http\Request;

class CategoryItemController extends Controller
{
    /**
     * Display the create form.
     */
    public function create()
    {
        return view('categoryitem.create');
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
            // 'created_user' => auth()->id(), // Assuming the user is authenticated
        ]);

        return redirect()->back()->with('success', 'Category item created successfully.');
    }
}
