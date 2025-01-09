<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\CategoryItems;
use App\Models\RmForRecipe;
use App\Models\UniqueCode;
use Illuminate\Http\Request;

class RmForRecipeController extends Controller
{
    public function rmstore(Request $request)
    {
        dd($request->all());  // To see the incoming data
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'raw_material_id' => 'required|exists:raw_materials,id',
            'quantity' => 'required|numeric',
            'code' => 'required|string',
            'uom' => 'required|string',
            'price' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        RmForRecipe::create([
            'raw_material_id' => $request->raw_material_id,
            'product_id' => 1,
            'quantity' => $request->quantity,
            'code' => $request->code,
            'uom' => $request->uom,
            'price' => $request->price,
            'amount' => $request->amount,
        ]);

        $rmRecipe = DB::table('rm_for_recipe')->get();

        return redirect()->back()->with([
            'success' => 'Data saved successfully!',
            'rmRecipe' => $rmRecipe,
        ]);
       
    }

    public function saveRawMaterials(Request $request)
    {
         dd($request->all());

        $request->validate([
            'raw_material_id' => 'required|exists:raw_materials,id',
        ]);

        // Store the data in the database
        RmForRecipe::create([
            'raw_material_id' => $request->raw_material_id,
            'product_id' => 1,
        ]);

        $rmRecipe = DB::table('rm_for_recipe')->get();

    return redirect()->back()->with([
        'success' => 'Data saved successfully!',
        'rmRecipe' => $rmRecipe,
    ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
