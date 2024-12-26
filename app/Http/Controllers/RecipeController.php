<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = DB::table('recipes')->get();
        return view('addreceipedetails', compact('recipes'));
    }
    //
    public function store()
    {
        $validated = $request->validate([
            'recipeid' => 'required|exists:recipes,id',
            'recipeDescription' => 'required|string',
            'receipeInstruction' => 'required|string',
            'receipevideo' => 'nullable|file|mimes:mp4,avi,flv|max:10240', // Adjust MIME types and size limit
        ]);
        // Handle video upload
        if ($request->hasFile('video_path')) {
            $filePath = $request->file('video_path')->store('videos', 'public');
            $validated['video_path'] = $filePath;
        }

        // Create a new recipe
        Recipe::create($validated);

        return redirect()->route('recipes.index')->with('success', 'Recipe details added successfully!');


    }
}
