<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Models\Recipe;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = DB::table('product_master')->where('recipe_created_status', 'yes')->get();
        return view('recipedetails.receipeDetails_Description', compact('recipes'));
    }

    public function show($id)
    {
    // Fetch the recipe by ID
    $recipe = Recipe::findOrFail($id);
//    $recipe = DB::table('recipedetails')
//     ->where('product_id', $id)
//     ->get();
      // Pass the recipe to the view
    return view('recipedetails.receipeDetails_Description', compact('recipe'));
    }

    public function fetchRecipeDetails($id)
    {
        $recipe = Recipe::find($id);
        // $recipe = DB::table('recipedetails')
        // ->where('product_id', $id)
        // ->get();

        if (!$recipe) {
            return response()->json(['error' => 'Recipe not found'], 404);
        }

        return response()->json($recipe);
    }

    public function create()
    {
        $recipes = DB::table('product_master')->where('recipe_created_status', 'no')->get();
        return view('recipedetails.addReceipeDetails', compact('recipes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'productId' => 'required|exists:product_master,id',
            'recipeDescription' => 'required|string',
            'receipeInstruction' => 'required|string',
            'receipevideo' => 'nullable|file|mimes:mp4,avi,flv|max:10240', // Adjust MIME types and size limit
        ]);

        // Handle video upload
        if ($request->hasFile('receipevideo')) {
            // Save file directly to 'public/uploads'
            $filePath = $request->file('receipevideo')->move(public_path('uploads'), $request->file('receipevideo')->getClientOriginalName());
            $validated['receipevideo'] = 'uploads/' . $request->file('receipevideo')->getClientOriginalName();
        }
       else {
            return back()->withErrors(['receipevideo' => 'Failed to upload video.']);
        }

        // if ($request->hasFile('receipevideo') && $request->file('receipevideo')->isValid()) {
        //     $filePath = $request->file('receipevideo')->store('uploads', 'public');
        //     $validated['receipevideo'] = $filePath;
        // }

        // Create a new recipe
        Recipe::create([
            'product_id' => $request->productId,
            'description' => $request->recipeDescription,
            'instructions' => $request->receipeInstruction,
            'video_path' => $validated['receipevideo'] ?? null,
        ]);

        DB::table('product_master')
        ->where('id', $request->productId) // Replace $id with the actual ID
        ->update(['recipe_created_status' => 'yes']);

        return redirect()->route('receipedetails.index')->with('success', 'Recipe details added successfully!');
    }

    public function edit($id)
    {
            // $editrecipe = Recipe::findOrFail($id); // Fetch the recipe details
            $editrecipe = Recipe::with('product')->findOrFail($id);

            // dd($editrecipe);
            // Return the view with recipe details & description data
            return view('recipedetails.editReceipeDetails', compact('editrecipe'));
    }

    public function update(Request $request, $id)
    {
        $editrecipe = Recipe::findOrFail($id);

        $validated = $request->validate([
            // 'productId' => 'required|exists:product_master,id',
            'recipeDescription' => 'required|string',
            'receipeInstruction' => 'required|string',
            'receipevideo' => 'nullable|file|mimes:mp4,avi,flv|max:10240', // Adjust MIME types and size limit
        ]);

        // Handle video upload
        if ($request->hasFile('receipevideo')) {
            // Save file directly to 'public/uploads'
            $filePath = $request->file('receipevideo')->move(public_path('uploads'), $request->file('receipevideo')->getClientOriginalName());
            $validated['receipevideo'] = 'uploads/' . $request->file('receipevideo')->getClientOriginalName();
        }
       else {
            return back()->withErrors(['receipevideo' => 'Failed to upload video.']);
        }

        try {
            // Update the raw material record
            $rawMaterial->update([
                'description' => $request->recipeDescription,
                'instructions' => $request->receipeInstruction,
                'video_path' => $validated['receipevideo'] ?? null,
            ]);
        } catch (\Exception $e) {
            // Handle the error gracefully (e.g., log it and show an error message)
            // \Log::error('Error updating recipe: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an issue updating the recipe details.');
        }
        // $editrecipe->update($request->all()); // Update recipe details

        return redirect()->route('receipedetails.index')->with('success', 'Recipe updated successfully.');
    }
}
