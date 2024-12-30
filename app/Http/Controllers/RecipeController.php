<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = DB::table('recipes')->get();
        return view('receipeDetails_Description', compact('recipes'));
    }
    //
    public function create()
    {
        $recipes = DB::table('recipes')->get();
        return view('addReceipeDetails', compact('recipes'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipeid' => 'required|integer',
            'recipeDescription' => 'required|string',
            'receipeInstruction' => 'required|string',
            'receipevideo' => 'nullable|file|mimes:mp4,avi,flv|max:10240', // Adjust MIME types and size limit
        ]);
        // Handle video upload
        if ($request->hasFile('receipevideo')) {
            $filePath = $request->file('receipevideo')->store('videos', 'public/uploads');
            $validated['receipevideo'] = $filePath;
        }

        // Create a new recipe
        // Recipe::create($validated);
        Recipe::create([
            'receipe_id' => $request->recipeid,
            'description' => $request->recipeDescription,
            'instructions' => $request->receipeInstruction,
            'video_path' => $filePath, // Assuming the user is authenticated
        ]);

        return redirect()->route('receipedetails.index')->with('success', 'Recipe details added successfully!');


    }
}
