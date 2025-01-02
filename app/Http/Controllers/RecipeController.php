<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Models\Recipe;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = DB::table('recipes')->get();
        return view('receipeDetails_Description', compact('recipes'));
    }

    public function show($id)
    {
    // Fetch the recipe by ID
    $recipe = Recipe::findOrFail($id);

    // Pass the recipe to the view
    return view('receipeDetails_Description', compact('recipe'));
    }

    public function fetchRecipeDetails($id)
    {
        $recipe = Recipe::find($id);

        if (!$recipe) {
            return response()->json(['error' => 'Recipe not found'], 404);
        }

        return response()->json($recipe);
    }

    public function create()
    {
        $recipes = DB::table('recipes')->get();
        return view('addReceipeDetails', compact('recipes'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipeId' => 'required|exists:recipes,id',
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
            'receipe_id' => $request->recipeId,
            'description' => $request->recipeDescription,
            'instructions' => $request->receipeInstruction,
            'video_path' => $validated['receipevideo'] ?? null,
        ]);

        return redirect()->route('receipedetails.index')->with('success', 'Recipe details added successfully!');
    }
}
