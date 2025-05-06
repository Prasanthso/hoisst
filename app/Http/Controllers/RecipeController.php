<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Models\Recipe;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        $recipes = DB::table('product_master')->where('store_id', $storeid)->where('recipe_created_status', 'yes')->get();
        return view('recipedetails.receipeDetails_Description', compact('recipes'));
    }

    public function recipeDetails(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        // $recipes = DB::table('product_master')->where('recipe_created_status', 'yes')->get();
        $recipes = DB::table('product_master')
        ->join('recipedetails', 'product_master.id', '=', 'recipedetails.product_id') // Adjust column names as needed
        ->where('product_master.recipe_created_status', 'yes')
        ->where('recipedetails.status', 'active')
        ->where('product_master.store_id', $storeid)
        ->select('product_master.*', 'recipedetails.id as rcpid','recipedetails.status') // Select required columns
        ->get();
        return view('recipedetails.receipeDetails_Description', compact('recipes'));
    }

    public function show(Request $request, $id)
    {
        $storeid = $request->session()->get('store_id');
        // Fetch the recipe by ID
        $recipe = Recipe::where('store_id', $storeid)
                ->where('id', $id)
                ->firstOrFail();  //findOrFail($id);
        // Pass the recipe to the view
        return view('recipedetails.receipeDetails_Description', compact('recipe'));
    }

    public function fetchRecipeDetails(Request $request, $id)
    {
        $storeid = $request->session()->get('store_id');
        $recipe = Recipe::where('store_id', $storeid)
                 ->where('id', $id)->first();  //find($id);

        if (!$recipe) {
            return response()->json(['error' => 'Recipe not found'], 404);
        }

        return response()->json($recipe);
    }

    public function create(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        $recipes = DB::table('product_master')->where('store_id', $storeid)->where('recipe_created_status', 'no')->get();
        return view('recipedetails.addReceipeDetails', compact('recipes'));
    }

    public function store(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        try{
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
            'store_id' => $storeid,
        ]);

        DB::table('product_master')
        ->where('id', $request->productId) // Replace $id with the actual ID
        ->where('store_id', $storeid)
        ->update(['recipe_created_status' => 'yes']);

        return redirect()->route('receipedetails.index')->with('success', 'Recipe details added successfully!');
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

    public function edit(Request $request, $id)
    {
        $storeid = $request->session()->get('store_id');
            // $editrecipe = Recipe::findOrFail($id); // Fetch the recipe details- old
         // $editrecipe = Recipe::with('product')->findOrFail($id);

            $editrecipe = DB::table('recipedetails')
            ->join('product_master', 'recipedetails.product_id', '=', 'product_master.id')
            ->where('recipedetails.id', $id)
            ->where('recipedetails.store_id', $storeid)
            ->select('recipedetails.id as rid','recipedetails.*', 'product_master.*') // You can select specific fields as needed
            ->first(); // Use first() because we expect a single result
            if (!$editrecipe) {
                return redirect()->route('receipedetails.index')->with('error', 'Recipe not found.');
            }
            // dd($editrecipe);
            // Return the view with recipe details & description data
            return view('recipedetails.editReceipeDetails', compact('editrecipe'));
    }

    public function update(Request $request, $id)
    {
        $storeid = $request->session()->get('store_id');

        $editrecipe = Recipe::where('store_id', $storeid)
                        ->where('id', $id)
                        ->firstOrFail();  //findOrFail($id);

        $validated = $request->validate([
            // 'productId' => 'required|exists:product_master,id',
            'recipeDescription' => 'required|string',
            'receipeInstruction' => 'required|string',
            'receipevideo' => 'nullable|file|mimes:mp4,avi,flv|max:10240', // Adjust MIME types and size limit
        ]);

        $oldVideo = $editrecipe->video_path; // Store the current video path before updating
        $newVideoPath = null;

    // Check if the request contains a file for 'receipevideo'
        if ($request->hasFile('receipevideo')) {
            // Validate that the file is valid
            if ($request->file('receipevideo')->isValid()) {
                 $originalName = $request->file('receipevideo')->getClientOriginalName();
                 $filePath = $request->file('receipevideo')->move(public_path('uploads'), $originalName);
                // Save the relative path to the validated data
                $validated['receipevideo'] = 'uploads/' . $originalName;
                $newVideoPath = 'uploads/' . $originalName;
            }
            // else {
            //     // Handle invalid file error
            //     return response()->json(['error' => 'Uploaded file is not valid'], 400);
            // }
        }
        //  else {
        //     // Handle missing file error
        //     return response()->json(['error' => 'No file uploaded'], 400);
        // }

        $updateDetailsData = [
            'id' => $id,
            'old_receipevideo' => $oldVideo,
            'receipevideo' => $newVideoPath,
        ];

        $validatedData = Validator::make($updateDetailsData, [
            'id' => 'required|exists:recipedetails,id',
            'receipevideo' => 'nullable|string',
            'old_receipevideo' => 'nullable|string',
        ])->validate();

        try {
            DB::transaction(function () use ($editrecipe, $request, $oldVideo, $newVideoPath, $validatedData, $storeid, $id) {
                $editrecipe->update([
                    'description' => $request['recipeDescription'],
                    'instructions' => $request['receipeInstruction'],
                    'video_path' => $newVideoPath ?? $oldVideo,
                    // 'store_id' => $storeid,
                ]);

                DB::table('recipedetails_histories')->insert([
                    'recipe_id' => $validatedData['id'],
                    'old_video' => $validatedData['old_receipevideo'] ?? '',
                    'new_video' => $validatedData['receipevideo'] ?? '',
                    'changed_by' => 1, // auth()->id(),
                    'approved_by' => 1, // auth()->id(),
                    'store_id' => $storeid,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
        } catch (\Exception $e) {
            // \Log::error('Error updating recipe details: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an issue updating the recipe details.');
        }

        return redirect()->route('receipedetails.index')->with('success', 'Recipe updated successfully.');
    }

    public function getRecipedetailsHistory(Request $request, $productId)
    {
        $storeid = $request->session()->get('store_id');
        // Get the recipe ID from the receipedetails table using product_id
        $recipe = DB::table('recipedetails')
            ->where('id', $productId)
            ->where('store_id', $storeid)
            ->first();

        if (!$recipe) {
            return response()->json(['message' => 'Recipe not found'], 404);
        }

        // Fetch the history for the specific recipe ID
        $recipeHistory = DB::table('recipedetails_histories')
            ->where('recipe_id', $recipe->id)
            ->where('store_id', $storeid)
            // ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json($recipeHistory);
    }

    public function delete(Request $request, $id)
    {
        $storeid = $request->session()->get('store_id');
        // Fetch the product_id from the Recipe table
        $recipe = Recipe::where('store_id', $storeid)->where('id', $id)->first();

        if (!$recipe) {
            return response()->json(['success' => false, 'message' => 'Recipe not found.']);
        }

        $productId = $recipe->product_id; // Get the product_id

        try {
            // Update the Recipe status to 'inactive'
            Recipe::where('store_id', $storeid)->where('id', $id)->update(['status' => 'inactive']);

            // Update the product_master table using the fetched product_id
            DB::table('product_master')->where('store_id', $storeid)->where('id', $productId)->update(['recipe_created_status' => 'no']);

            return response()->json(['success' => true, 'message' => 'Recipe details were set to inactive successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error updating Recipe details: ' . $e->getMessage()]);
        }
    }




    /*
    public function updateRecipedetails(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:recipedetails,id',
            'receipevideo' => 'nullable|file|mimes:mp4,avi,flv|max:10240',
            'old_receipevideo' => 'nullable|string', // Expect a file path, not a file
        ]);

        // $newVideoPath = null;
        // if ($request->hasFile('receipevideo') && $request->file('receipevideo')->isValid()) {
        //     $newVideoPath = 'uploads/' . time() . '_' . $request->file('receipevideo')->getClientOriginalName();
        //     $request->file('receipevideo')->move(public_path('uploads'), $newVideoPath);
        // }

        try {
            DB::table('recipedetails_histories')->insert([
                'recipe_id' => $validatedData['id'],
                'old_video' => $validatedData['old_receipevideo'],
                'new_video' => $validatedData['receipevideo'],
                'changed_by' => 1, //auth()->id(),
                'approved_by' => 1, // auth()->id(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error while logging the recipe details.');
        }

        return true;
    }
    */
}
